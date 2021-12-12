<?php
return [
    'register_reward' => 6600,      //新用户奖励

    'first_round_count' => 5,             // 首轮题目数量
    'default_round_count' => 20,          // 每轮题目数量

    'daily_limit' => 120,           // 每日答题限制

    'max_money' => 29985,           //用户余额最多

    'ad_double_min' => 10,        //翻倍最小百分比
    'ad_double_max' => 20,        //翻倍最大百分比

    'daily_limit_reward' => [       // 限制后配置
        'reward_rand' => [1, 1],
        'full_ad_skip' => 1,
        'model_ad_skip' => 0
    ],

    'first_round_reward' => [           // 首轮金额配置
        'reward_rand' => [300, 600],
        'full_ad_skip' => 3,
        'model_ad_skip' => 0
    ],

    'reward_config' => [                   // 红包金额配置
        [
            'start' => 0,
            'end' => 10000,
            'reward_rand' => [200, 300],
            'full_ad_skip' => 5,
            'model_ad_skip' => 7
        ],
        [
            'start' => 10001,
            'end' => 15000,
            'reward_rand' => [100, 200],
            'full_ad_skip' => 4,
            'model_ad_skip' => 5
        ],
        [
            'start' => 15001,
            'end' => 25000,
            'reward_rand' => [50, 100],
            'full_ad_skip' => 3,
            'model_ad_skip' => 4
        ],
        [
            'start' => 25001,
            'end' => 29000,
            'reward_rand' => [1, 50],
            'full_ad_skip' => 2,
            'model_ad_skip' => 4
        ],
        [
            'start' => 29001,
            'end' => 29985,
            'reward_rand' => [1, 1],
            'full_ad_skip' => 1,
            'model_ad_skip' => 4
        ],
        [
            'start' => 29985,
            'end' => 29999,
            'reward_rand' => [0, 0],
            'full_ad_skip' => 1,
            'model_ad_skip' => 4
        ],

    ]
];
