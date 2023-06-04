<?php

namespace Sashapekh\SimpleRest\Core\Router;

use Sashapekh\SimpleRest\Core\Request\Request;
use Sashapekh\SimpleRest\Core\Uri\Uri;


class RouteResolver
{
    /** @param array<RouteObject> $routeList */
    /**
     * @param array<RouteObject> $routeList
     * @return RouteObject|null
     */
    public static function make(array $routeList, Uri $uri, Request $request): ?RouteObject
    {
        // dump selection
        $filterRouteList = array_values(
            array_filter($routeList, function (RouteObject $routeObject) use ($uri, $request) {
                return $uri->getPath() === $routeObject->getRoute()
                    && $request->getMethod() === $routeObject->getHttpMethod();
            })
        );

        if (!empty($filterRouteList)) {
            return $filterRouteList[0];
        }
        // can be dynamic route
        $requestSegments = parseSegment($uri->getPath());
        $filterDynamicSegment = array_filter($routeList, function (RouteObject $routeObject) use ($requestSegments) {
            if (empty($routeObject->getDynamicSegments())) {
                return false;
            }
            $buildUrl = null;

            foreach ($requestSegments as $key => $value) {

                if (isset($routeObject->getUriSegments()[$key])) {
                    if ($routeObject->getUriSegments()[$key] === $value) {
                        $buildUrl .= '/' . $value;
                    }

                    if (isset($routeObject->getDynamicSegments()[$key]['key'])) {
                        $buildUrl .= sprintf('/{%s}', $routeObject->getDynamicSegments()[$key]['key']);
                    }
                }
            }
            return $buildUrl === $routeObject->getRoute();
        });

        return count($filterDynamicSegment) > 0 ? array_values($filterDynamicSegment)[0] : null;
    }
}