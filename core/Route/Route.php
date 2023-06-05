<?php

namespace Sashapekh\SimpleRest\Core\Route;

use Sashapekh\SimpleRest\Core\HttpHelper;
use Sashapekh\SimpleRest\Core\Route\RouteObject;

class Route
{
    /** @var array<RouteObject> */
    private static array $routesList = [];

    /**
     * @throws \Exception
     */
    public static function get(string $route, array $params, array $middlewares = []): void
    {
        self::baseCheck($params);

        self::$routesList[] = new RouteObject(
            $route,
            HttpHelper::GET_METHOD,
            $params[0],
            $params[1] ?? null,
            $middlewares
        );
    }

    /**
     * @throws \Exception
     */
    public static function post(string $route, array $params, array $middlewares = []): void
    {
        self::baseCheck($params);

        self::$routesList[] = new RouteObject(
            $route,
            HttpHelper::POST_METHOD,
            $params[0],
            $params[1] ?? null,
            $middlewares
        );
    }

    public static function getRouteList(): array
    {
        return self::$routesList;
    }

    /**
     * @throws \Exception
     */
    private static function baseCheck(array $params): void
    {
        if (!isset($params[0])) {
            throw new \Exception('class name not passed to Router method');
        }
        if (!class_exists($params[0])) {
            throw new \Exception('first param in array is not a class');
        }
    }
}