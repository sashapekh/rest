<?php


use JetBrains\PhpStorm\NoReturn;

#[NoReturn] function dd($variable)
{
    echo '<pre>' . var_export($variable, true) . '</pre>';
    exit();
}


function parseSegment(string $url): ?array
{
    $list = explode('/', $url);

    $list = array_filter($list, function ($item) {
        return !empty($item);
    });
    return empty($list) ? null : $list;
}