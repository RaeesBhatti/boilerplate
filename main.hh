<?php
define('APP_ENV', 'MAIN');
require(__DIR__.'/core/app-load.php');
header('Content-Type: text/html; charset=utf-8');
echo App::go(HTTP::GET, $_SERVER['REQUEST_URI'], $_GET, $_POST, $_SERVER, $_COOKIE);
