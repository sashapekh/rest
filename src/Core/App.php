<?php

namespace Sashapekh\SimpleRest\Core;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sashapekh\SimpleRest\Core\Request\Request;
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
        $this->noResolvedRouteCheck();
        $class =  $this->currentRoute->getControllerClass();
        $method =  $this->currentRoute->getControllerMethod();

        $response = (new $class)->{$method}(
            $this->request
        );
        $this->resolveResponse($response);
    }
    private function resolveResponse(Response $response)
    {
        http_response_code($response->getStatusCode());
        if ($response->isJson())
        {
            echo json_encode($response->getJsonData());
            exit();
        }
    }
    private function noResolvedRouteCheck(): void
    {

        if (empty($this->currentRoute)) {
            http_response_code(404);
            if ($this->request->isJson()) {
                echo json_encode(['code' => 404, 'message'  => HttpHelper::getPhraseByStatusCode(404)]);
                exit();
            } else {
                echo sprintf("<h1>%s</h1>", HttpHelper::getPhraseByStatusCode(404));
                exit();
            }
       }
    }
}