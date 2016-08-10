<?php
define('APP_ENV', 'API');
require(__DIR__.'/core/app-load.php');
header('Content-Type: application/json');

if (!array_key_exists('HTTP_X_AUTH', $_SERVER)) {
	http_response_code(400);
} else {
	$Post = json_decode(file_get_contents('php://input'), 1);
	if (!$Post) {
		$Post = HH\ImmMap{};
	} else {
		$Post = new HH\ImmMap($Post);
	}
	echo Helper::go($_SERVER['REQUEST_METHOD'], new HH\ImmMap($_GET), $Post, new HH\ImmMap($_SERVER), new HH\ImmMap($_COOKIE));
}
