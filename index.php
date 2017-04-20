<?php

ini_set('max_execution_time', 1000);
ini_set('set_time_limit', 1000);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('ERROR_PATH', getcwd() . "/error.log");
define('INSTABUDDIES_FILE', getcwd() . "/instabuddies.json");

require 'vendor/autoload.php';
spl_autoload_register(function ($class) {
  include $class . '.php';
});

error_log('------- Main started' . PHP_EOL, 3, ERROR_PATH);


error_log('==== Main Ended' . PHP_EOL, 3, ERROR_PATH);
