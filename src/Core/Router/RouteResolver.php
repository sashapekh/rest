<?php

namespace Sashapekh\SimpleRest\Core\Router;

use Sashapekh\SimpleRest\Core\Request\RequestHelper;


class RouteResolver
{
    /** @param array<RouteObject> $routeList */
    /**
     * @param array<RouteObject> $routeList
     * @return RouteObject|null
     */
    public static function make(array $routeList): ?RouteObject
    {
        // dump selection
        $filterRouteList = array_values(
            array_filter($routeList, function (RouteObject $routeObject) {
                return RequestHelper::getRequestUri() === $routeObject->getRoute()
                    && RequestHelper::getRequestMethod() === $routeObject->getHttpMethod();
            })
        );

        if (!empty($filterRouteList)) {
            return $filterRouteList[0];
        }
        // can be dynamic route
        $requestSegments = parseSegment(RequestHelper::getRequestUri());

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

                    if (isset($routeObject->getDynamicSegments()[$key])) {
                        $buildUrl .= '/' . $routeObject->getDynamicSegments()[$key];
                    }
                }
            }
            return $buildUrl === $routeObject->getRoute();
        });

        return count($filterDynamicSegment) > 0 ? array_values($filterDynamicSegment)[0] : null;
    }
}