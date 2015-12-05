<?php
// Initializing configurations
ignore_user_abort(true);
set_time_limit(300);
define('RAND_MAX', mt_getrandmax());
define('APP_ROOT', __DIR__.'/..');
define('APP_IN_CLI', !array_key_exists('REMOTE_ADDR', $_SERVER));
define('APP_DEBUG', !APP_IN_CLI && $_SERVER['REMOTE_ADDR'] === '127.0.0.1');

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
} else {
  session_start();
}

if (APP_DEBUG) {
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
}

require(APP_ROOT.'/external/xhp/init.php');
spl_autoload_register(function($Name) {

  $Chunks = explode('\\', $Name);

  if (count($Chunks) == 1) {
    // Converts Theme_User_Home to theme/user/home.php
    $Chunks = explode('_', $Name);
    $FilePath = APP_ROOT.'/'.strtolower(implode('/', $Chunks)).'.php';

    if (file_exists($FilePath)) {
      require($FilePath);
    }
  }
});

require(APP_ROOT.'/config.hh');
require(APP_ROOT.'/credentials.hh');
require(__DIR__.'/exceptions.php');
require(__DIR__.'/session.php');
require(__DIR__.'/shapes.php');
require(__DIR__.'/helper.php');
require(__DIR__.'/page.php');
require(__DIR__.'/router.php');
require(__DIR__.'/app.php');
