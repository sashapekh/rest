<?php

namespace Sashapekh\SimpleRest\Core;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sashapekh\SimpleRest\Core\Request\Request;
use Sashapekh\SimpleRest\Core\Request\RequestHandler;
use Sashapekh\SimpleRest\Core\Request\ServerRequest;
use Sashapekh\SimpleRest\Core\Response\Response;
use Sashapekh\SimpleRest\Core\Router\Route;
use Sashapekh\SimpleRest\Core\Router\RouteResolver;
use Sashapekh\SimpleRest\Core\Uri\Uri;
use Sashapekh\SimpleRest\Core\Router\RouteObject;


class App
{
    private Uri $uri;
    private RequestInterface $request;
    private ServerRequestInterface $serverRequest;


    public function __construct()
    {
        $this->uri = new Uri();
        $this->request = new Request();
        $this->serverRequest = new ServerRequest();
    }

    public function run()
    {
        session_start();
        $this->checkNotFound();

        // process middleware and be sure we can pass to next
        $this->middlewareHandle();

        $class = $this->request->getRouteObject()?->getControllerClass();
        $method = $this->request->getRouteObject()?->getControllerMethod();

        $response = (new $class)->{$method}(
            $this->request
        );
        $this->resolveResponse($response);
    }

    /**
     * @param Response $response
     * @return void
     */
    private function resolveResponse(Response $response): void
    {
        http_response_code($response->getStatusCode());
        // hard setup content-type
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response->getJsonData());
        exit();
    }

    private function checkNotFound(): void
    {
        if (empty($this->request->getRouteObject())) {
            http_response_code(HttpStatusCodes::NOT_FOUND_STATUS);

            echo json_encode(
                [
                    'code'    => HttpStatusCodes::NOT_FOUND_STATUS,
                    'message' => HttpHelper::getPhraseByStatusCode(HttpStatusCodes::NOT_FOUND_STATUS)
                ]
            );
            exit();
        }
    }

    private function middlewareHandle(): void
    {
        $middlewares = $this->request->getRouteObject()?->getMiddlewares();
        if (!$middlewares) {
            return;
        }
        foreach ($middlewares as $middleware) {
            $class = $middleware;
            /** @var Response $response */
            $response = (new $class())
                ->process($this->serverRequest, new RequestHandler());

            if ($response->getStatusCode() !== HttpHelper::OK_STATUS) {
                $this->resolveResponse($response);
            }
        }
    }
}