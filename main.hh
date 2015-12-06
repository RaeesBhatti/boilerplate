<?php
define('APP_ENV', 'MAIN');
require(__DIR__.'/core/app-load.php');
header('Content-Type: text/html; charset=utf-8');
echo Helper::go(HTTP::GET, $_GET, $_POST, $_SERVER, $_COOKIE);
