<?php

namespace Sashapekh\SimpleRest\Controllers;

use Sashapekh\SimpleRest\Core\Request\Request;
use Sashapekh\SimpleRest\Core\Response\Response;

class IndexController
{
    public function index(Request $request): Response
    {
        return (new Response())->json(['message' => 'main page']);
    }
}