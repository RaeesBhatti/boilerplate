<?php
define('APP_ENV', 'API');
require(__DIR__.'/core/app-load.php');
header('Content-Type: text/html; charset=utf-8');
echo App::go($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $_GET, $_POST, $_SERVER, $_COOKIE);
