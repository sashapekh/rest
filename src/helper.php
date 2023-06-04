<?php

// simple method to display pretty variable
function dd($variable)
{
    echo '<pre>' . var_export($variable, true) . '</pre>';
    exit();
}

// parse url string to array
function parseSegment(string $uri): ?array
{
    $list = explode('/', $uri);

    $list = array_filter($list, function ($item) {
        return !empty($item);
    });
    return empty($list) ? null : array_values($list);
}