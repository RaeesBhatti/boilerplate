<?php
// Initializing configurations
ignore_user_abort(true);
if(APP_ENV === 'DAEMON')
	set_time_limit(0);
else
	set_time_limit(300);
define('RAND_MAX', mt_getrandmax());
define('APP_ROOT', __DIR__.'/..');
define('DEPS_ROOT', __DIR__.'/../../deps');
define('APP_IN_CLI', !array_key_exists('REMOTE_ADDR', $_SERVER));
define('APP_DEBUG', !APP_IN_CLI && (key_exists('DEBUG', $_SERVER) && $_SERVER['DEBUG'] === 'TRUE'));

// Basic runtime checks
if (APP_IN_CLI) {
  // We are in CLI
  if (APP_ENV !== 'UNIT_TESTS') {
    echo "The website is not to be ran from CLI\n";
    exit(1);
  } else {
    // Initialize some placeholder ENV vars
    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    $_SERVER['REQUEST_URI'] = '/';
    $_SESSION = [];
  }
} elseif(
	(array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] === 'on') ||
	(array_key_exists('HTTP_X_FORWARDED_PROTO', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
	APP_DEBUG
	) {
  session_start();
}

if (APP_DEBUG) {
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

require(DEPS_ROOT.'/autoload.php');
spl_autoload_register(function($Name) {

	if (substr($Name, 0, 4) === 'xhp_') {
		$ClassName = substr($Name, 4);
		$FilePath = __DIR__.'/xhp/'.$ClassName.'.hh';
	} else {
		// Converts Theme_User_Home to theme/user/home.hh
		$Chunks = explode('_', $Name);
		$FilePath = APP_ROOT.'/'.strtolower(implode('/', $Chunks)).'.hh';
	}

	if ($FilePath !== null && file_exists($FilePath)) {
		require($FilePath);
	}
});

require(APP_ROOT.'/config.hh');
require(APP_ROOT.'/credentials.hh');
require(__DIR__.'/exceptions.hh');
require(__DIR__.'/session.hh');
require(__DIR__.'/helper.hh');
require(__DIR__.'/page.hh');
require(__DIR__.'/theme.hh');
require(__DIR__.'/router.hh');
require(__DIR__.'/shapes.hh');
require(__DIR__.'/validate.hh');
require(__DIR__.'/app.hh');
