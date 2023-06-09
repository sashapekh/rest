<?php

namespace Sashapekh\SimpleRest\Core\Request;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Sashapekh\SimpleRest\Core\MessageTrait;
use Sashapekh\SimpleRest\Core\Route\Route;
use Sashapekh\SimpleRest\Core\Route\RouteObject;
use Sashapekh\SimpleRest\Core\Route\RouteResolver;
use Sashapekh\SimpleRest\Core\Stream\Stream;
use Sashapekh\SimpleRest\Core\Uri\Uri;

class Request implements RequestInterface
{
    use MessageTrait;

    private UriInterface $uri;

    /** @var null|Stream */
    private $body = null;

    private string $method;

    private ?RouteObject $routeObject;
    public function __construct()
    {
        $this->protocolVersion = preg_replace('/[^0-9.]/', '', $_SERVER['SERVER_PROTOCOL']);
        $this->headers = getallheaders();
        $this->headerNames = array_keys(getallheaders());
        $this->uri = new Uri();
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);

        $this->routeObject = RouteResolver::make(
            Route::getRouteList(),
            $this->uri,
            $this->method
        );
    }

    public function getRouteObject(): ?RouteObject
    {
        return $this->routeObject;
    }

    /**
     * @return string
     */
    public function getRequestTarget(): string
    {
        $target = $this->uri->getPath();
        if ($target === '') {
            $target = '/';
        }
        if ($this->uri->getQuery() != '') {
            $target .= '?' . $this->uri->getQuery();
        }

        return $target;
    }

    /**
     * @param string $requestTarget
     * @return RequestInterface|$this
     */
    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        // not used
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return RequestInterface|$this
     */
    public function withMethod(string $method): RequestInterface
    {
        $method = strtoupper($method);
        if ($method === $this->method) {
            return $this;
        }
        $new = clone $this;
        $new->method = $method;
        return $new;
    }

    /**
     * @return UriInterface
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * @param UriInterface $uri
     * @param bool $preserveHost
     * @return RequestInterface|$this
     */
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $new = clone $this;
        $new->uri = $uri;
        return $new;
    }

    public function getFormData(): array
    {
        try {
            return json_decode(file_get_contents("php://input"), true) ?? [];
        } catch (\Exception $exception) {
            return [];
        }
    }

    public function getDynamicValueByKey(string $name): mixed
    {
        $segments = $this->uri->segments();
        foreach ($this->routeObject->getDynamicSegments() as $key => $value) {
            if ($name === $value['key']) {
                return $segments[$value['position']] ?? null;
            }
        }
        return null;
    }
}