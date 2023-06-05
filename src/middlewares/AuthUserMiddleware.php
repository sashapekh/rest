<?php

namespace Sashapekh\SimpleRest\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sashapekh\SimpleRest\Core\HttpHelper;
use Sashapekh\SimpleRest\Core\HttpStatusCodes;
use Sashapekh\SimpleRest\Core\Response\Response;
use Sashapekh\SimpleRest\Repository\UserRepository;

class AuthUserMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authHeader = $request->getHeader('Authorization');
        if (!empty($authHeader)) {
            $token = $authHeader['Authorization'];
            $token = str_replace("Token ", "", $token);
            if ($user = (new UserRepository())->checkToken($token)) {
                $_SESSION['user'] = $user;
                return new Response();
            }
        }

        return (new Response(HttpStatusCodes::FORBIDDEN_STATUS))->json([
            'message' => HttpHelper::getPhraseByStatusCode(HttpStatusCodes::FORBIDDEN_STATUS)
        ]);
    }
}