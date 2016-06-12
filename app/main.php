<?php
define('APP_ENV', 'MAIN');
require(__DIR__.'/core/app-load.php');
echo Helper::go(HTTP::GET, new HH\ImmMap($_GET), HH\ImmMap{}, new HH\ImmMap($_SERVER), new HH\ImmMap($_COOKIE));
