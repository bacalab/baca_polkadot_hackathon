<?php

namespace App\Libs\Wechat;

use App\Events\RechargeSuccessEvent;
use App\Jobs\WechatWarnNoticeJob;
use App\Models\Rich\DiamondRecord;
use App\Models\Rich\NewDiamondRecord;
use App\Models\Rich\RechargeTrade;
use App\Models\User;
use App\Services\RechargeService;
use App\Services\UserService;
use EasyWeChat\Kernel\Exceptions\Exception as WxException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Support\Collection;
use EasyWeChat\Payment\Application;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Overtrue\LaravelWeChat\Facade;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class Pay
{

    /**
     * android支付
     * 详细说明见官方文档：https://pay.weixin.qq.com/wiki/doc/api/app/app.php?chapter=9_1
     * @param $title
     * @param $money
     * @param $tradeNo
     * @param string $notifyUrl
     * @return array|Collection|object|ResponseInterface|string
     */
    public static function AndroidPay($title, $money, $tradeNo, $notifyUrl = '')
    {
        $logArr = ['title' => $title, 'money' => $money, 'tradeNo' => $tradeNo, 'notifyUrl' => $notifyUrl];
        try {
            $payment = Facade::payment();
            $appNameCn = config('app.name_cn');
            if (empty($notifyUrl)) {
                $notifyUrl = route('payNotify');
            }
            $data = [
                'body' => $appNameCn . '-' . $title,
                'out_trade_no' => $tradeNo,
                'total_fee' => $money,
                'trade_type' => 'APP',
                'notify_url' => $notifyUrl,
            ];
            $result = $payment->order->unify($data);

            if (Arr::get($result, 'return_code') === 'FAIL') {
                logger()->error("androidPayErr", ['params' => $data, 'result' => $result]);
            } else {
                $encodeResult = $payment->jssdk->appConfig($result['prepay_id']);
                logger()->info("androidPaySuccess", ['params' => $data, 'result' => $result, 'encode_result' => $encodeResult]);
                $result = $encodeResult;
            }
            return $result;
        } catch (Exception $e) {
            $logArr['msg'] = $e->getMessage();
        } catch (InvalidConfigException $e) {
            $logArr['msg'] = $e->getMessage();
        } catch (InvalidArgumentException $e) {
            $logArr['msg'] = $e->getMessage();
        }
        logger()->info('getAndroidPayErr', $logArr);
        return [];
    }

    public static function H5Pay($title, $money, $tradeNo, $notifyUrl = '')
    {
        $logArr = ['title' => $title, 'money' => $money, 'tradeNo' => $tradeNo, 'notifyUrl' => $notifyUrl];
        try {
            $payment = Facade::payment();
            $appNameCn = config('app.name_cn');
            if (empty($notifyUrl)) {
                $notifyUrl = route('payNotify');
            }
            $data = [
                'body' => $appNameCn . '-' . $title,
                'out_trade_no' => $tradeNo,
                'total_fee' => $money,
                'trade_type' => 'MWEB',
                'notify_url' => $notifyUrl,
            ];
            $result = $payment->order->unify($data);

            if (Arr::get($result, 'return_code') === 'FAIL') {
                logger()->error("H5PayErr", ['params' => $data, 'result' => $result]);
            }
            return $result;
        } catch (\Exception $e) {
            $logArr['msg'] = $e->getMessage();
        } catch (InvalidConfigException $e) {
            $logArr['msg'] = $e->getMessage();
        } catch (InvalidArgumentException $e) {
            $logArr['msg'] = $e->getMessage();
        }
        logger()->info('getH5PayErr', $logArr);
        return [];
    }

    /**
     * 支付回调
     * @param Application $payment
     * @return Response|void
     */
    public static function payCallback(Application $payment)
    {
        try {
            $response = $payment->handlePaidNotify(function ($message, $fail) {
                if (empty($message)) {
                    return true;
                }
                // 流水单号
                $no = Arr::get($message, 'out_trade_no', '');
                logger()->info("paySuccessNotify", $message);
                if (Arr::get($message, 'result_code') === 'SUCCESS') {
                    // 支付成功处理
                    /** @var RechargeTrade $record */
                    $record = RechargeTrade::query()->where(['no' => $no])->first();
                    if (empty($record)) {
                        WechatWarnNoticeJob::dispatch('充值回调错误通知', '流水单号: ' . $no . '\n原因: 流水单号不存在', Notice::ALERT);
                    }
                    if (RechargeTrade::STATUS_WAITING == $record->status) {
                        $money = $record->amount / 100;
                        if (!app()->environment('production')) {
                            $money = 99;
                        }
                        $rid = 0;
                        $flag = false;
                        $isFirst = RechargeTrade::query()
                            ->where('user_id', $record->user_id)
                            ->where('status', RechargeTrade::STATUS_SUCCESS)
                            ->where('type', RechargeTrade::TYPE_DIAMOND)
                            ->value('id') ? 0 : 1;
                        DB::beginTransaction();
                        try {
                            $record->is_first = $isFirst;
                            $record->status = RechargeTrade::STATUS_SUCCESS;
                            $record->time_end = now()->format('Y-m-d H:i:s');
                            $record->transaction_id = $message['transaction_id'] ?? '';
                            $record->save();
                            $diamond = RechargeService::getDiamondByMenuId($record->menu_id);
                            $rid = UserService::dealNewDiamond($diamond, $record->user_id, NewDiamondRecord::RECHARGE, $no, 1, $record->user_id, NewDiamondRecord::IS_INCOME);
                            DB::commit();
                            $flag = true;
                        } catch (Exception $e) {
                            DB::rollBack();
                            logger()->error('paySuccessNotifyTreatErr', ['no' => $no, 'msg' => $e->getMessage()]);
                        }
                        if ($flag && $rid) {
                            $diamond = RechargeService::getDiamondByMenuId($record->menu_id);
                            $uid = $record->user_id;
                            event(new RechargeSuccessEvent($diamond, $uid, $no, $record->amount));
                        }
                    }
                    logger()->info("paySuccessNotifyFinish");
                } elseif (Arr::get($message, 'result_code') === 'FAIL') {
                    // 用户支付失败处理
                    WechatWarnNoticeJob::dispatch('充值回调失败通知', '流水单号: ' . $no, Notice::ALERT);
                    logger()->error("paySuccessFail", $message);
                    return $fail('支付失败');
                }

                return true;
            });
            return $response;
        } catch (WxException $e) {
            logger()->error('wxPayCallbackErr', ['msg' => $e->getMessage()]);
            return;
        }
    }
}
