<?php

namespace Sashapekh\SimpleRest\Core\Log;

class FileHandler implements HandlerInterface
{
    public function handle(array $vars): void
    {
        $file = DEFAULT_ROOT_PATH . 'logs/logger.log';
        $output = self::DEFAULT_FORMAT;
        foreach ($vars as $var => $val) {
            $output = str_replace('%' . $var . '%', $val, $output);
        }
        file_put_contents($file, $output . PHP_EOL, FILE_APPEND);
    }
}