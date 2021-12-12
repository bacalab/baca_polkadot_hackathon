<?php

namespace App\Libs\Wechat;


use EasyWeChat\Factory;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\RuntimeException;
use EasyWeChat\Kernel\Messages\TextCard;
use Illuminate\Support\Arr;

class Notice
{
    const MESSAGE = 'message';
    const ALERT = 'alert';

    private static $typeMap = [
        // 业务消息通知
        self::MESSAGE => [
            'ids' => [5],
            'app_config' => 'wechat.work.warn',
        ],
        // 业务&系统 监控报警
        self::ALERT => [
            'ids' => [6],
            'app_config' => 'wechat.work.alert',
        ]
    ];

    public static function send($title, $content, $type)
    {
        $config = Arr::get(self::$typeMap, $type);
        $partyIds = Arr::get($config, 'ids', []);
        $appConfig = Arr::get($config, 'app_config');

        $app = Factory::work(config($appConfig));
        $env = app()->environment();
        if (!in_array($env, ['production'])) {
            logger(['wechatAlert'], ['info' => $env . ' 环境不发送提醒']);
            return;
        }
        $message = new TextCard([
            'title' => '答题APP' . $env . $title,
            'description' => "<div class=\"highlight\">{$content}</div>",
            'url' => 'http://admin-manage.pinbs.cn/admin',
        ]);
        try {
            $app->messenger->message($message)->toParty($partyIds)->send();
        } catch (InvalidArgumentException $e) {
            logger()->error('sendWxNoticeErr', ['msg' => $e->getMessage(), 'title' => $title, 'content' => $content, 'type' => $type]);
        } catch (RuntimeException $e) {
            logger()->error('sendExNoticeErr', ['msg' => $e->getMessage(), 'title' => $title, 'content' => $content, 'type' => $type]);
        }
    }
}
