<?php

namespace Sashapekh\SimpleRest\Core\Router;

class RouteObject
{
    private $route;
    private ?array $uriSegments;
    private ?int $segmentCount;
    private $httpMethod;
    private string $controllerClass;
    private ?string $controllerMethod;
    private ?array $dynamicSegments = [];

    public function __construct(
        string $route,
        string $httpMethod,
        string $controllerClass,
        string $controllerMethod = null,
        array $middlewares = []
    ) {
        $this->route = $route;
        $this->httpMethod = $httpMethod;
        $this->controllerClass = $controllerClass;
        $this->controllerMethod = $controllerMethod;
        $this->parseSegments();
        $this->assignDynamicSegments();
    }

    private function parseSegments(): void
    {
        $this->uriSegments = parseSegment($this->route);
        $this->segmentCount = is_null($this->uriSegments) ? 0 : count($this->uriSegments);
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return array|null
     */
    public function getUriSegments(): ?array
    {
        return $this->uriSegments;
    }

    /**
     * @return int|null
     */
    public function getSegmentCount(): ?int
    {
        return $this->segmentCount;
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    /**
     * @return string
     */
    public function getControllerClass(): string
    {
        return $this->controllerClass;
    }

    /**
     * @return string|null
     */
    public function getControllerMethod(): ?string
    {
        return $this->controllerMethod;
    }

    private function assignDynamicSegments(): void
    {
        if (!empty($this->uriSegments)) {
            foreach ($this->uriSegments as $index => $value) {
                $result = preg_match('/(?<={)(.*?)(?=})/', $value);
                if ($result > 0) {
                    $this->dynamicSegments[$index] = $value;
                }
            }
        }
    }

    /**
     * @return array|null
     */
    public function getDynamicSegments(): ?array
    {
        return $this->dynamicSegments;
    }
}