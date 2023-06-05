<?php

namespace Sashapekh\SimpleRest\Core\Log;

interface HandlerInterface
{
    public const DEFAULT_FORMAT = '%timestamp% [%level%]: %message%';
    public function handle(array $vars): void;
}