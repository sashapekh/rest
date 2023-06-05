<?php

namespace Sashapekh\SimpleRest\Core\Log;

use Psr\Log\LogLevel;

class Log
{
    public static function info(\Stringable|string $message, array $context = []): void
    {
        $log = Logger::getInstance();
        $log->log(LogLevel::INFO, $message, $context);
    }

    public static function error(\Exception $exception): void
    {
        $log = Logger::getInstance();
        $log->log(LogLevel::ERROR, $exception->getMessage(), $exception->getTrace());
    }

}