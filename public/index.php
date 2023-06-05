<?php

use Sashapekh\SimpleRest\Core\App;
use Sashapekh\SimpleRest\Core\Log\Log;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/routes/web.php';
define("DEFAULT_ROOT_PATH", __DIR__ . '/../');

Log::info('application start initialize');

try {
    (new App())->run();
} catch (Exception $exception) {
    Log::error($exception);
}


