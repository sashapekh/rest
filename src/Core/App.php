<?php

namespace Sashapekh\SimpleRest\Core;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sashapekh\SimpleRest\Core\Request\Request;
use Sashapekh\SimpleRest\Core\Request\RequestHandler;
use Sashapekh\SimpleRest\Core\Request\ServerRequest;
use Sashapekh\SimpleRest\Core\Response\Response;
use Sashapekh\SimpleRest\Core\Router\Router;
use Sashapekh\SimpleRest\Core\Router\RouteResolver;
use Sashapekh\SimpleRest\Core\Uri\Uri;
use Sashapekh\SimpleRest\Core\Router\RouteObject;


class App
{
    private ?RouteObject $currentRoute;
    private Uri $uri;
    private RequestInterface $request;
    private ServerRequestInterface $serverRequest;


    public function __construct()
    {
        $this->uri = new Uri();
        $this->request = new Request();
        $this->currentRoute = RouteResolver::make(
            Router::getRouteList(),
            $this->uri,
            $this->request
        );
        $this->serverRequest = new ServerRequest();
    }

    public function run()
    {
        session_start();
        $this->checkNotFound();

        $class = $this->currentRoute->getControllerClass();
        $method = $this->currentRoute->getControllerMethod();

        $this->middlewareHandle();

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
        echo json_encode($response->getJsonData());
        exit();
    }

    private function checkNotFound(): void
    {
        if (empty($this->currentRoute)) {
            http_response_code(HttpStatusCodes::NOT_FOUND_STATUS);
            if ($this->request->isJson()) {
                echo json_encode(
                    [
                        'code'    => HttpStatusCodes::NOT_FOUND_STATUS,
                        'message' => HttpHelper::getPhraseByStatusCode(HttpStatusCodes::NOT_FOUND_STATUS)
                    ]
                );
                exit();
            } else {
                echo sprintf(
                    "<h1>%s</h1>",
                    HttpHelper::getPhraseByStatusCode(HttpStatusCodes::NOT_FOUND_STATUS)
                );
                exit();
            }
        }
    }

    private function middlewareHandle(): void
    {
        $response = null;

        foreach ($this->currentRoute->getMiddlewares() as $middleware) {
            $class = $middleware;
            /** @var Response $response */
            $response = (new $class())
                ->process($this->serverRequest, new RequestHandler());
            if ($response->getStatusCode() !== HttpStatusCodes::OK_STATUS) {
                break;
            }
            $response = null;
        }

        if ($response) {
            $this->resolveResponse($response);
        }
    }
}