<?php

ini_set('max_execution_time', 120);
ini_set('set_time_limit', 120);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('ERROR_PATH', getcwd() . "/error.log");

require 'vendor/autoload.php';
spl_autoload_register(function ($class) {
  include $class . '.php';
});

session_start();
error_log('------- Main started' . PHP_EOL, 3, ERROR_PATH);

$instagramUsername = 'sannebent03';
$replace = true;

$insta = new InstagramCrawler($instagramUsername);
$vision = new VisionImageScan;
$scanResult = new ScanResult();

if (empty($_SESSION['userResults'][$instagramUsername]) || $replace == true) {
  $result = [];
  error_log('Getting images' . PHP_EOL, 3, ERROR_PATH);
  $images = $insta->getUserMedia(7);
  error_log('--- Loop started' . PHP_EOL, 3, ERROR_PATH);


  foreach ($images as $image) {
    $imageCaption = $image['caption'];
    $imageSrc = $image['src'];
    error_log('-Vision started (' . $imageSrc . ')' . PHP_EOL, 3, ERROR_PATH);
    $visionResult = $vision->getImageResult($imageSrc);
    error_log('-Vision returned ', 3, ERROR_PATH);
//      array_push($result, ['vision' => $visionResult, 'image' => $image]);
//      $resultTemp[] = [$visionResult['labels'], 'image' => $image];
    $scanResult->generateDominantLabels($visionResult['labels']);

    if (empty($visionResult['labels'])) {
      error_log('without labels' . PHP_EOL, 3, ERROR_PATH);
    } else {
      error_log('with ' . count($visionResult['labels']) . ' labels' . PHP_EOL, 3, ERROR_PATH);
    }
  }

  error_log('--- Loop ended' . PHP_EOL, 3, ERROR_PATH);

  $_SESSION['userResults'][$instagramUsername] = $scanResult->getDominantLabels();
}

foreach ($_SESSION['userResults'] as $username => $labels) {
  if (!empty($labels)) {
    arsort($labels);
    $_SESSION['userResults'][$username] = $labels;
  }
}
error_log('Result encoded printed' . PHP_EOL, 3, ERROR_PATH);
echo json_encode($_SESSION['userResults']);
error_log('==== Main Ended' . PHP_EOL, 3, ERROR_PATH);

