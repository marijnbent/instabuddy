<?php
ini_set('max_execution_time', 0);
ini_set('set_time_limit', 0);

require 'vendor/autoload.php';

spl_autoload_register(function ($class) {
  include $class . '.php';
});

if (!defined('ERROR_PATH'))
  define('ERROR_PATH', getcwd() . "/error.log");
if (!defined('INSTABUDDIES_FILE'))
  define('INSTABUDDIES_FILE', getcwd() . "/instabuddies.json");
if (!defined('TOANALYSE_FILE'))
  define('TOANALYSE_FILE', getcwd() . "/toAnalyse.json");

class Instabuddy
{

  private $jsonFile;

  public function __construct()
  {
    $this->jsonFile = json_decode(file_get_contents(INSTABUDDIES_FILE), true);
  }

  public function getInstabuddiesJson()
  {
    return json_decode(file_get_contents(INSTABUDDIES_FILE), true);
  }

  public function setInstabuddiesJson($newData)
  {
    file_put_contents(INSTABUDDIES_FILE, json_encode($newData));
    return true;
  }
  public function getToAnalyseJson()
  {
    return json_decode(file_get_contents(TOANALYSE_FILE), true);
  }

  public function setToAnalyseJson($newData)
  {
    file_put_contents(TOANALYSE_FILE, json_encode($newData));
    return true;
  }

  public function addToAnalyse($username)
  {
    $toAnalyse = $this->getToAnalyseJson();
    $toAnalyse[] = $username;
    $this->setToAnalyseJson($toAnalyse);
    return $toAnalyse;
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

      $generatedEntry = ['labels' => $labels, 'images' => array_slice($images, 0, 14), 'meta' => ['last_updated' => time()], 'user' => $userInfo];
//      $generatedEntry = ['labels' => $labels, 'images' => $images, 'meta' => ['last_updated' => time()], 'user' => $userInfo];

      if (!empty($generatedEntry['labels'])) {
        arsort($generatedEntry['labels']);
      }

      $this->jsonFile = json_decode(file_get_contents(INSTABUDDIES_FILE), true);
      error_log('[- Base JSON refreshed' . PHP_EOL, 3, ERROR_PATH);
      $this->jsonFile[$instagramUsername] = $generatedEntry;
      $this->jsonFile = json_encode($this->jsonFile);
      file_put_contents(INSTABUDDIES_FILE, $this->jsonFile);
      error_log(' -] Result saved from ' . $instagramUsername . PHP_EOL, 3, ERROR_PATH);

      return true;
    }
    return false;
  }

  public function getSimilarUser($instagramUsername, $returnedUsersCount = 1)
  {
    $userToCheck = $instagramUsername;
    $results = [];
    foreach ($this->jsonFile as $username => $result) {
      if ($username !== $userToCheck) {
        $arrayChecked = $this->jsonFile[$userToCheck]['labels'];
        $arrayBuddy = $result['labels'];

        $totalValueCountChecked = 0;
        foreach ($arrayChecked as $value) {
          $totalValueCountChecked = $totalValueCountChecked + intval($value);
        }
        $totalValueCountBuddy = 0;
        foreach ($arrayBuddy as $value) {
          $totalValueCountBuddy = $totalValueCountBuddy + intval($value);
        }

        $matches = array_intersect_key($arrayChecked, $arrayBuddy);

        $totalMatchCount = 0;
        foreach ($matches as $match => $valueChecked) {
          $totalMatchCount = $totalMatchCount + intval($arrayBuddy[$match]);
        }

        $similarity = round((intval($totalMatchCount) / intval($totalValueCountChecked)) * 100);
        if ($similarity > 100)
          $similarity = 100;
        $results[$username] = $similarity;
      }
    }
    arsort($results);
    return array_slice($results, 0, $returnedUsersCount);
  }
}