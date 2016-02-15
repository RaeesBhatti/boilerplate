<?php
define('APP_ENV', 'MAIN');
require(__DIR__.'/core/app-load.php');
echo Helper::go(HTTP::GET, $_GET, [], $_SERVER, $_COOKIE);
