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

error_log('------- Test started' . PHP_EOL, 3, ERROR_PATH);

$instabuddiesJson = json_decode(file_get_contents(INSTABUDDIES_FILE), true);
error_log('JSON decoded' . PHP_EOL, 3, ERROR_PATH);

$userToCheck = 'sannebent03';
foreach ($instabuddiesJson as $username => $result) {
  if ($username !== $userToCheck) {
    $array1 = $instabuddiesJson[$userToCheck]['labels'];
    $array2 = $result['labels'];
    $matches = array_intersect_key($array1, $array2);
    $similarity = round(count($matches)/(count($array1))*100);
    echo 'SIMILARITY ' . $username . ' / ' . $userToCheck . ': ' . $similarity . '%<br>';
  }
}

