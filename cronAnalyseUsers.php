<?php

ini_set('max_execution_time', 0);
ini_set('set_time_limit', 0);

if (!defined('ERROR_PATH'))
  define('ERROR_PATH', getcwd() . "/error.log");

require 'vendor/autoload.php';
spl_autoload_register(function ($class) {
  include $class . '.php';
});

error_log('-------= Cron started' . PHP_EOL, 3, ERROR_PATH);
$instabuddy = new Instabuddy();
$usersToAnalyse = $instabuddy->getToAnalyseJson();
foreach ($usersToAnalyse as $user) {
  $instabuddy->newJsonEntry($user, false);
}
error_log('-------= Cron ended' . PHP_EOL, 3, ERROR_PATH);

