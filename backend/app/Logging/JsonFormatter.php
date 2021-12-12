<?php

namespace App\Logging;

use Monolog\Formatter\JsonFormatter as BaseJsonFormatter;

class JsonFormatter extends BaseJsonFormatter
{
    public function format(array $record) :string
    {
        // 最终记录的数组转成Json并记录进日志
        $newRecord = [
            'time' => $record['datetime']->format('Y-m-d H:i:s'),
            'timestamp' => time(),
            'level_name' => $record['level_name'],
            'message' => $record['message'],
        ];

        if (!empty($record['context'])) {
            $newRecord = array_merge($newRecord, $record['context']);
        }

        $json = $this->toJson($this->normalize($newRecord), true) . ($this->appendNewline ? "\n" : '');

        return $json;
    }
}
