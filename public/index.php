<?php

use Sashapekh\SimpleRest\Core\App;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/routes/web.php';

(new App())->run();
