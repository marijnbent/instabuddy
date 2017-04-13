<?php
require 'vendor/autoload.php';

class ScanResult
{
  private $totalImages;
  private $dominantLabels;
  private $totalFaces;
  private $sorrowFaces;
  private $angerFaces;
  private $joyFaces;

  public function __construct() {
    $this->dominantLabels = [];
  }

  public function generateDominantLabels($labels) {
    $labelArray = $this->dominantLabels;
    foreach ($labels as $label) {
      if (!empty($labelArray[$label['label']])) {
        $labelArray[$label['label']] = intval($labelArray[$label['label']]) + 1;
      } else {
        $labelArray[$label['label']] = 1;
      }
    }
    $this->dominantLabels = $labelArray;
  }

  public function generateTotalImages($images) {

  }
  public function generateTotalFaces($faces) {

  }
  public function generateEmotionFaces($faces) {

  }

  /**
   * @return mixed
   */
  public function getSorrowFaces()
  {
    return $this->sorrowFaces;
  }

  /**
   * @param mixed $sorrowFaces
   */
  public function setSorrowFaces($sorrowFaces)
  {
    $this->sorrowFaces = $sorrowFaces;
  }

  /**
   * @return mixed
   */
  public function getTotalImages()
  {
    return $this->totalImages;
  }

  /**
   * @param mixed $totalImages
   */
  public function setTotalImages($totalImages)
  {
    $this->totalImages = $totalImages;
  }

  /**
   * @return mixed
   */
  public function getDominantLabels()
  {
    return $this->dominantLabels;
  }

  /**
   * @param mixed $dominantLabels
   */
  public function setDominantLabels($dominantLabels)
  {
    $this->dominantLabels = $dominantLabels;
  }

  /**
   * @return mixed
   */
  public function getTotalFaces()
  {
    return $this->totalFaces;
  }

  /**
   * @param mixed $totalFaces
   */
  public function setTotalFaces($totalFaces)
  {
    $this->totalFaces = $totalFaces;
  }

  /**
   * @return mixed
   */
  public function getAngerFaces()
  {
    return $this->angerFaces;
  }

  /**
   * @param mixed $angerFaces
   */
  public function setAngerFaces($angerFaces)
  {
    $this->angerFaces = $angerFaces;
  }

  /**
   * @return mixed
   */
  public function getJoyFaces()
  {
    return $this->joyFaces;
  }

  /**
   * @param mixed $joyFaces
   */
  public function setJoyFaces($joyFaces)
  {
    $this->joyFaces = $joyFaces;
  }
}