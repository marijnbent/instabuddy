<?php
require 'vendor/autoload.php';
use Google\Cloud\Vision\VisionClient;

class VisionImageScan
{
  private $imageUrl;
  private $googleVisionCredentials = __DIR__ . '/googleVisionSecret.json';

  public function getImageResult($imageUrl)
  {
    $this->imageUrl = $imageUrl;

    $vision = new VisionClient([
      'projectId' => 'tidy-fort-135623',
      'keyFile' => json_decode(file_get_contents($this->googleVisionCredentials), true),
      'keyFilePath' => $this->googleVisionCredentials
    ]);

    $image = $vision->image(
      $this->imageUrl, ['FACE_DETECTION', 'LABEL_DETECTION', 'LANDMARK_DETECTION']
    );

    $result = $vision->annotate($image);

    /**
     * @var \Google\Cloud\Vision\Annotation $result ;
     */
    if (empty($result->error())) {

      $labels = [];
      /**
       * @var integer $key
       * @var \Google\Cloud\Vision\Annotation\Entity $label
       */
      foreach ($result->labels() as $key => $label) {
        $label = [
          'label' => $label->description(),
          'score' => $label->score()
        ];
        array_push($labels, $label);
      }

      $faces = [];
      /**
       * @var integer $key
       * @var \Google\Cloud\Vision\Annotation\Entity $face
       */
      foreach ($result->faces() as $key => $face) {
        $face = [
          'faceConfidence' => $face->detectionConfidence(),
          'joyScore' => $face->joyLikelihood(),
          'angerScore' => $face->angerLikelihood(),
          'sorrowScore' => $face->sorrowLikelihood(),
          'surpriseScore' => $face->surpriseLikelihood(),
          'headwearScore' => $face->headwearLikelihood()
          ];
        array_push($faces, $face);
      }

      $landmarks = [];
      /**
       * @var integer $key
       * @var \Google\Cloud\Vision\Annotation\Entity $landmark
       */
      foreach ($result->landmarks() as $key => $landmark) {
        $landmark = [
          'score' => $landmark->score(),
          'description' => $landmark->description(),
          'location' => $landmark->locations()
        ];
        array_push($landmarks, $landmark);
      }

      $result = [
        'faces' => $faces,
        'labels' => $labels,
        'landmark' => $landmarks
      ];

      return $result;

    }

    return $result->error();
  }
}


