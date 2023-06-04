<?php

namespace Sashapekh\SimpleRest\Controllers;

use Sashapekh\SimpleRest\Core\HttpHelper;
use Sashapekh\SimpleRest\Core\Request\Request;
use Sashapekh\SimpleRest\Core\Response\Response;
use Sashapekh\SimpleRest\Repository\UserRepository;

class AuthController
{
    public function __construct(
        private readonly UserRepository $userRepository = new UserRepository()
    ) {
    }

    public function login(Request $request): Response|\Psr\Http\Message\MessageInterface
    {
        $formData = $request->getFormData();

        if ($user = $this->userRepository->getUserByCredential(
            $formData['email'] ?? '',
            $formData['password'] ?? ''
        )) {

            return (new Response())->json([
                'token' => base64_encode(
                    sprintf("%s:%s", $user->email, $user->getPassword())
                )
            ]);
        }

        return (new Response(401))->json([
            'message' => HttpHelper::getPhraseByStatusCode(401)
        ]);
    }
}