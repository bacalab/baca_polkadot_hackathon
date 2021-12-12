<?php

/*
 * This file is part of the overtrue/laravel-wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [
    /*
     * 默认配置，将会合并到各模块中
     */
    'defaults' => [
        /*
         * 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
         */
        'response_type' => 'array',

        /*
         * 使用 Laravel 的缓存系统
         */
        'use_laravel_cache' => true,

        /*
         * 日志配置
         *
         * level: 日志级别，可选为：
         *                 debug/info/notice/warning/error/critical/alert/emergency
         * file：日志文件位置(绝对路径!!!)，要求可写权限
         */
        'log' => [
            'level' => env('WECHAT_LOG_LEVEL', 'debug'),
            'file' => env('WECHAT_LOG_FILE', storage_path('logs/wechat.log')),
        ],
    ],

    /*
     * 路由配置
     */
    'route' => [
        /*
         * 开放平台第三方平台路由配置
         */
        // 'open_platform' => [
        //     'uri' => 'serve',
        //     'action' => Overtrue\LaravelWeChat\Controllers\OpenPlatformController::class,
        //     'attributes' => [
        //         'prefix' => 'open-platform',
        //         'middleware' => null,
        //     ],
        // ],
    ],

    /*
     * 公众号
     */
    'official_account' => [
        'default' => [
            'app_id' => env('WECHAT_OFFICIAL_ACCOUNT_APPID', 'your-app-id'),         // AppID
            'secret' => env('WECHAT_OFFICIAL_ACCOUNT_SECRET', 'your-app-secret'),    // AppSecret
            'token' => env('WECHAT_OFFICIAL_ACCOUNT_TOKEN', 'your-token'),           // Token
            'aes_key' => env('WECHAT_OFFICIAL_ACCOUNT_AES_KEY', ''),                 // EncodingAESKey
        ]
    ],

    /*
     * 微信支付
     */
    'payment' => [
//        'default' => [
//            'sandbox' => env('WECHAT_PAYMENT_SANDBOX', false),
//            'app_id' => env('WECHAT_OFFICIAL_ACCOUNT_APPID', ''),
//            'mch_id' => env('WECHAT_PAYMENT_MCH_ID', 'your-mch-id'),
//            'key' => env('WECHAT_PAYMENT_KEY', 'key-for-signature'),
//            'cert_path' => env('WECHAT_PAYMENT_CERT_PATH', 'path/to/cert/apiclient_cert.pem'),    // XXX: 绝对路径！！！！
//            'key_path' => env('WECHAT_PAYMENT_KEY_PATH', 'path/to/cert/apiclient_key.pem'),      // XXX: 绝对路径！！！！
//            'notify_url' => 'http://example.com/payments/wechat-notify',                           // 默认支付结果通知地址
//        ],
        //提现
        'transfer' => [
            'sandbox' => false,
            'app_id' => env('WECHAT_OFFICIAL_ACCOUNT_APPID', ''),
            'mch_id' => env('WECHAT_MCH_ID', 'your-mch-id'),
            'key' => env('WECHAT_PAYMENT_KEY', 'key-for-signature'),
            'cert_path' => base_path(env('WECHAT_PAYMENT_CERT_PATH', 'path/to/cert/apiclient_cert.pem')),    // XXX: 绝对路径！！！！
            'key_path' => base_path(env('WECHAT_PAYMENT_KEY_PATH', 'path/to/cert/apiclient_key.pem')),      // XXX: 绝对路径！！！！
        ]
    ],

    /*
     * 企业微信
     */
    'work' => [
        'warn' => [ // 企业微信业务数据通知
            'corp_id' => env('WITHDRAW_WARN_CORP_ID', ''),
            'agent_id' => intval(env('WITHDRAW_WARN_AGENT', '')),
            'secret' => env('WITHDRAW_WARN_SECRET', ''),
            'response_type' => 'array',
            'log' => [
                'level' => env('WECHAT_LOG_LEVEL', 'debug'),
                'file' => env('WECHAT_LOG_FILE', storage_path('logs/wechat_warn.log')),
            ]
        ],
        // 业务&&系统告警通知
        'alert' => [
            'corp_id' => env('WITHDRAW_ALERT_CORP_ID', ''),
            'agent_id' => intval(env('WITHDRAW_ALERT_AGENT', '')),
            'secret' => env('WITHDRAW_ALERT_SECRET', ''),
            'response_type' => 'array',
            'log' => [
                'level' => env('WECHAT_LOG_LEVEL', 'debug'),
                'file' => env('WECHAT_LOG_FILE', storage_path('logs/wechat_alert.log')),
            ]
        ],
    ],
];
