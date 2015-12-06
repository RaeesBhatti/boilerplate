<?php
define('APP_ENV', 'API');
require(__DIR__.'/core/app-load.php');
header('Content-Type: text/html; charset=utf-8');
echo Helper::go($_SERVER['REQUEST_METHOD'], $_GET, $_POST, $_SERVER, $_COOKIE);
