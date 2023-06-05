<?php

namespace Sashapekh\SimpleRest\Core\Log;

use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger
{

    private static $instances = [];

    protected const DEFAULT_DATETIME_FORMAT = 'c';

    /**
     * @var HandlerInterface
     */
    private $handler;

    protected function __construct() {
        $this->handler = new FileHandler();
    }

    protected function __clone() { }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }

    public static function getInstance()
    {
        $subclass = static::class;
        if (!isset(self::$instances[$subclass])) {

            self::$instances[$subclass] = new static();
        }
        return self::$instances[$subclass];
    }
    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string|\Stringable $message
     * @param array $context
     *
     * @return void
     *
     * @throws \Psr\Log\InvalidArgumentException
     */
    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $this->handler->handle([
            'message' => self::interpolate((string)$message, $context),
            'level' => strtoupper($level),
            'timestamp' => (new \DateTimeImmutable())->format(self::DEFAULT_DATETIME_FORMAT),
        ]);
    }

    protected static function interpolate(string $message, array $context = []): string
    {
        $replace = [];
        foreach ($context as $key => $val) {
            if (is_string($val) || method_exists($val, '__toString')) {
                $replace['{' . $key . '}'] = $val;
            }
        }
        return strtr($message, $replace);
    }
}