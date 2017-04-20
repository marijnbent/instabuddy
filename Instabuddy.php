<?php
require 'vendor/autoload.php';
spl_autoload_register(function ($class) {
  include $class . '.php';
});
ini_set('max_execution_time', 1000);
ini_set('set_time_limit', 1000);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('ERROR_PATH', getcwd() . "/error.log");
define('INSTABUDDIES_FILE', getcwd() . "/instabuddies.json");

class Instabuddy
{

  private $jsonFile;

  public function __construct()
  {
    error_log('------- Main started' . PHP_EOL, 3, ERROR_PATH);
    $this->jsonFile = json_decode(file_get_contents(INSTABUDDIES_FILE), true);
    error_log('JSON decoded' . PHP_EOL, 3, ERROR_PATH);

  }

  public function newJsonEntry($instagramUsername, $replace = false)
  {
    $instagram = new InstagramCrawler($instagramUsername);

    if (empty($this->jsonFile[$instagramUsername]) || $replace == true) {
      error_log('Getting images from ' . $instagramUsername . PHP_EOL, 3, ERROR_PATH);
      $images = $instagram->getUserMedia(300);
      $userInfo = $instagram->getUserInfo();

      $imagesSrc = [];
      foreach ($images as $image) {
        $imagesSrc[] = $image['src'];
      }

      error_log('---Vision started (' . count($images) . ' images)' . PHP_EOL, 3, ERROR_PATH);
      $scan = new VisionImageScan();
      $labels = $scan->imagesScan($imagesSrc);
      error_log('---Vision returned' . PHP_EOL, 3, ERROR_PATH);

      $generatedEntry = ['labels' => $labels, 'images' => $images, 'meta' => ['last_updated' => time()], 'user' => $userInfo];

      if (!empty($generatedEntry['labels'])) {
        arsort($generatedEntry['labels']);
      }

      $this->jsonFile = json_decode(file_get_contents(INSTABUDDIES_FILE), true);
      error_log('[- Base JSON refreshed' . PHP_EOL, 3, ERROR_PATH);
      $this->jsonFile[$instagramUsername] = $generatedEntry;
      $this->jsonFile = json_encode($this->jsonFile);
      file_put_contents(INSTABUDDIES_FILE, $this->jsonFile);
      error_log(' -] Result saved' . PHP_EOL, 3, ERROR_PATH);

    }
  }
}