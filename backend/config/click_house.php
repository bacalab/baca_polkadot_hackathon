<?php

return [
    'answer' => [
        'host' => env('CLICKHOUSE_DEFAULT_HOST', ''),
        'port' => env('CLICKHOUSE_DEFAULT_PORT', ''),
        'username' => env('CLICKHOUSE_DEFAULT_USERNAME', ''),
        'password' => env('CLICKHOUSE_DEFAULT_PASSWORD', '')
    ]
];
