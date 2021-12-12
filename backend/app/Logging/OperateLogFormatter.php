<?php

namespace App\Logging;


class OperateLogFormatter
{
    /**
     * Customize the given logger instance.
     *
     * @param $logger
     * @return void
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new JsonFormatter());
        }
    }
}
