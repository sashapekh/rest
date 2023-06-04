<?php

use Sashapekh\SimpleRest\Core\Request\RequestHelper;
use Sashapekh\SimpleRest\Core\Router\Router;
use Sashapekh\SimpleRest\Core\Router\RouteResolver;

require_once __DIR__ . '/../src/helper.php';
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/bootstrap.php';
// load helpers variable for request
(new RequestHelper())->make();

//get parsed routes from web.php
$routes = Router::getRouteList();
$currentRoute = RouteResolver::make($routes);

$uri = new \Sashapekh\SimpleRest\Core\Uri\Uri();
$request = new \Sashapekh\SimpleRest\Core\Request\Request();
