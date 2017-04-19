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

$instabuddiesJson = json_decode(file_get_contents(INSTABUDDIES_FILE), true);
error_log('JSON decoded' . PHP_EOL, 3, ERROR_PATH);

$instagramUsername = 'rarisonpablo21';
$replace = false;

$insta = new InstagramCrawler($instagramUsername);
$vision = new VisionImageScan;
$scanResult = new ScanResult();

if (empty($instabuddiesJson[$instagramUsername]) || $replace == true) {
  $result = [];
  error_log('Getting images from ' . $instagramUsername . PHP_EOL, 3, ERROR_PATH);
  $images = $insta->getUserMedia(300);
  error_log('--- Loop started with ' . count($images) . ' images on ' . date("Y-m-d H:i:s") . PHP_EOL, 3, ERROR_PATH);


  foreach ($images as $image) {
    $imageCaption = $image['caption'];
    $imageSrc = $image['src'];
    error_log('-Vision started (' . $imageSrc . ')' . PHP_EOL, 3, ERROR_PATH);
    $visionResult = $vision->getImageResult($imageSrc);
    error_log('-Vision returned ', 3, ERROR_PATH);
    $scanResult->generateDominantLabels($visionResult['labels']);

    if (empty($visionResult['labels'])) {
      error_log('without labels' . PHP_EOL, 3, ERROR_PATH);
    } else {
      error_log('with ' . count($visionResult['labels']) . ' labels' . PHP_EOL, 3, ERROR_PATH);
    }
  }

  error_log('--- Loop ended on ' . date("Y-m-d H:i:s") . PHP_EOL, 3, ERROR_PATH);
  $generatedEntry = ['labels' => $scanResult->getDominantLabels(), 'images' => $images, 'meta' => ['last_updated' => time()]];

  if (!empty($generatedEntry['labels'])) {
    arsort($generatedEntry['labels']);
  }

  $instabuddiesJson = json_decode(file_get_contents(INSTABUDDIES_FILE), true);
  error_log('Base JSON refreshed' . PHP_EOL, 3, ERROR_PATH);
  $instabuddiesJson[$instagramUsername] = $generatedEntry;
  $instabuddiesJson = json_encode($instabuddiesJson);
  file_put_contents(INSTABUDDIES_FILE, $instabuddiesJson);
  error_log('Result saved' . PHP_EOL, 3, ERROR_PATH);

}

echo json_encode($instabuddiesJson);
error_log('Result encoded printed' . PHP_EOL, 3, ERROR_PATH);

error_log('==== Main Ended' . PHP_EOL, 3, ERROR_PATH);

