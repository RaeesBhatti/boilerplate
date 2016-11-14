<?php

if(array_key_exists('REQUEST_URI', $_SERVER)){
    if(substr($_SERVER['REQUEST_URI'], 0, 5) === '/api/'){

        define('APP_ENV', 'API');
        require(__DIR__.'/../app/core/app-load.php');
        header('Content-Type: application/json');

        if (!array_key_exists('HTTP_X_AUTH', $_SERVER)) {
            http_response_code(400);
        } else {
            $Post = json_decode(file_get_contents('php://input'), 1);
            if (!is_array($Post)) {
                $Post = new HH\ImmMap();
            } else {
                $Post = new HH\ImmMap($Post);
            }
            echo Helper::go($_SERVER['REQUEST_METHOD'], new HH\ImmMap($_GET), $Post, new HH\ImmMap($_SERVER), new HH\ImmMap($_COOKIE));
        }
    } else {

        define('APP_ENV', 'MAIN');
        require(__DIR__.'/../app/core/app-load.php');
        echo Helper::go(HTTP::GET, new HH\ImmMap($_GET), new HH\ImmMap(), new HH\ImmMap($_SERVER), new HH\ImmMap($_COOKIE));
    }
} else {
    require(__DIR__.'/../app/core/app-load.php');
}
