<?php

function dd($str) {
    printf("%s\n", $str);
}

if (!function_exists('returnJson')) {
    function returnJson(array $data)
    {
        header('Content-Type:application/json; charset=utf8');
        exit(json_encode($data));
    }
}