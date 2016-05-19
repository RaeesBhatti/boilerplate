<?php
define('APP_ENV', 'MAIN');
require(__DIR__.'/core/app-load.php');
echo Helper::go(HTTP::GET, new HH\Map($_GET), HH\Map{}, new HH\Map($_SERVER), new HH\Map($_COOKIE));
