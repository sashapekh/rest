<?php

namespace Sashapekh\SimpleRest\Controllers;

use Sashapekh\SimpleRest\Core\Request\Request;
use Sashapekh\SimpleRest\Core\Response\Response;
use Sashapekh\SimpleRest\Repository\UserRepository;

class UserController
{
    public function __construct(
        private readonly UserRepository $userRepository = new UserRepository()
    ) {
    }

    public function all(Request $request): Response
    {
        return (new Response())->json(
            $this->userRepository->all()
        );
    }

    public function findById(Request $request): Response
    {

        return (new Response())->json($this->userRepository->find(1) ?? []);
    }
}