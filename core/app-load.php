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

require(APP_ROOT.'/external/redis/autoload.php');
require(APP_ROOT.'/external/xhp/init.php');
require(APP_ROOT.'/external/mongo-php-library/src/functions.php');
require(APP_ROOT.'/external/mongo-php-library/src/Model/BSONDocument.php');
require(APP_ROOT.'/external/mongo-php-library/src/Model/BSONArray.php');
spl_autoload_register(function($Name) {

  if (substr($Name, 0, 4) === 'xhp_') {
    $ClassName = substr($Name, 4);
    $FilePath = __DIR__.'/xhp/'.$ClassName.'.php';
  } else {
    // Converts Theme_User_Home to theme/user/home.php
    $Chunks = explode('_', $Name);
    $FilePath = APP_ROOT.'/'.strtolower(implode('/', $Chunks)).'.php';
  }

  if ($FilePath !== null && file_exists($FilePath)) {
    require($FilePath);
  }
});
spl_autoload_register(function($Name){
  // MongoDB library
  if(substr($Name, 0, 7) !== 'MongoDB') return;
  $Path = APP_ROOT.'/external/mongo-php-library/src/'.str_replace("\\", '/', substr($Name, 8)).'.php';
  if(file_exists($Path)){
    require($Path);
  }
});

require(APP_ROOT.'/config.hh');
require(APP_ROOT.'/credentials.hh');
require(__DIR__.'/exceptions.php');
require(__DIR__.'/session.php');
require(__DIR__.'/helper.php');
require(__DIR__.'/page.php');
require(__DIR__.'/theme.php');
require(__DIR__.'/router.php');
require(__DIR__.'/shapes.php');
require(__DIR__.'/validate.php');
require(__DIR__.'/app.php');
