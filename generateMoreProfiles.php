<?php

ini_set('max_execution_time', 0);
ini_set('set_time_limit', 0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!defined('ERROR_PATH'))
  define('ERROR_PATH', getcwd() . "/error.log");
if (!defined('INSTABUDDIES_FILE'))
  define('INSTABUDDIES_FILE', getcwd() . "/instabuddies.json");

require 'vendor/autoload.php';
spl_autoload_register(function ($class) {
  include $class . '.php';
});

error_log('-------= MOAR started' . PHP_EOL, 3, ERROR_PATH);

$hashtags = [
  'curacao',
  'vacation',
  'ajax',
  'summer',
  'beach',
  'thailand',
];


foreach ($hashtags as $hashtag) {
  $posts = Bolandish\Instagram::getMediaByHashtag($hashtag, 10);
  foreach ($posts as $post) {
    $username = $post->owner->username;
    $user = Bolandish\Instagram::getUserByUsername($username);

    if ($user->followed_by->count < 700 && $user->follows->count < 700 && $user->media->count > 100 && $user->media->count < 350) {
      $instabuddy = new Instabuddy();
      $instabuddy->newJsonEntry($username, false);

    }
  }
}



error_log('-------= MOAR ended' . PHP_EOL, 3, ERROR_PATH);

