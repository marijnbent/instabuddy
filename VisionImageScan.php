<?php
require 'vendor/autoload.php';

use Google\Cloud\Vision\VisionClient;

class VisionImageScan
{
  private $maxImagesPerRequest = 12;

  private $visionClient;
  private $googleVisionCredentials = __DIR__ . '/googleVisionSecret.json';

  private $allLabels;
  private $blacklistedLabels = ['photo','image','photograph'];

  public function __construct() {
    $this->visionClient = new VisionClient([
      'projectId' => 'tidy-fort-135623',
      'keyFile' => json_decode(file_get_contents($this->googleVisionCredentials), true),
      'keyFilePath' => $this->googleVisionCredentials
    ]);

  }

  public function imagesScan($imagesSrc)
  {
    $result = [];
    $imagesChunks = array_chunk($imagesSrc, $this->maxImagesPerRequest);
    foreach ($imagesChunks as $chunk) {
      $images = $this->visionClient->images(
        $chunk, ['FACE_DETECTION', 'LABEL_DETECTION', 'LANDMARK_DETECTION']
      );
      error_log('>Vision send (' . count($chunk) . ' images)' . PHP_EOL, 3, ERROR_PATH);
      $results = $this->visionClient->annotateBatch($images);
      error_log('<Vision received' . PHP_EOL, 3, ERROR_PATH);

      /**
       * @var \Google\Cloud\Vision\Annotation $result ;
       */
      foreach($results as $result) {
        $this->handleAnnotationResult($result);
      }
    }

    return $this->getAllLabels();
  }

  /**
   * @param \Google\Cloud\Vision\Annotation $result
   * @return array
   */
  private function handleAnnotationResult($result) {
    /**
     * @var \Google\Cloud\Vision\Annotation $result ;
     */
    $labels = [];
    if (empty($result->error())) {
      /**
       * @var integer $key
       * @var \Google\Cloud\Vision\Annotation\Entity $label
       */
      if (!empty($result->labels())) {
        foreach ($result->labels() as $key => $label) {
          $labels[] = $label->description();
        }
      }
    }
    $this->generateDominantLabels($labels);
  }

  /**
   * @param $labels
   */
  private function generateDominantLabels($labels)
  {
    $allLabels = $this->allLabels;
    foreach ($labels as $label) {
      if (!in_array($label, $this->blacklistedLabels)) {
        if (!empty($allLabels[$label])) {
          $allLabels[$label] = intval($allLabels[$label]) + 1;
        } else {
          $allLabels[$label] = 1;
        }
      }
    }
    $this->dominantLabels = $allLabels;
  }

  /**
   * @return mixed
   */
  public function getAllLabels()
  {
    return $this->allLabels;
  }


}


