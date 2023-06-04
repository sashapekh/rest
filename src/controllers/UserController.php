<?php

namespace Sashapekh\SimpleRest\Controllers;

use Sashapekh\SimpleRest\Repository\UserRepository;

class UserController
{
    public function __construct(
        private readonly UserRepository $userRepository = new UserRepository()
    ) {
    }

    public function all()
    {
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($this->userRepository->all());
    }

    public function findById(int $id)
    {
        http_send_status(200);
        header("Content-Type: application/json");
        echo json_encode($this->userRepository->find($id));
    }
}