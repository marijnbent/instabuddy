<?php
require 'vendor/autoload.php';
use Google\Cloud\Vision\VisionClient;

define('GOOGLE_APPLICATION_CREDENTIALS', __DIR__ . '/googleVisionSecret.json');


if (isset($_GET['imageUrl']) && !empty($_GET['imageUrl'])) {
  $image = $_GET['imageUrl'];

  $vision = new VisionClient([
    'projectId' => 'tidy-fort-135623',
    'keyFile' => json_decode(file_get_contents(GOOGLE_APPLICATION_CREDENTIALS), true),
    'keyFilePath' => GOOGLE_APPLICATION_CREDENTIALS
  ]);

  $image = $vision->image(
    $image, ['FACE_DETECTION', 'LABEL_DETECTION', 'LANDMARK_DETECTION']
  );

  $result = $vision->annotate($image);

//  echo json_encode($result);die();
  /**
   * @var \Google\Cloud\Vision\Annotation $result;
   */

  if (empty($result->error())) {

    $labels = [];
    /**
     * @var integer $key
     * @var \Google\Cloud\Vision\Annotation\Entity $label
     */
    foreach ($result->labels() as $key => $label) {
      $label = [$label->description(), $label->score()];
      array_push($labels, $label);
    }

    $faces = [];
    /**
     * @var integer $key
     * @var \Google\Cloud\Vision\Annotation\Entity $face
     */
    foreach ($result->faces() as $key => $face) {
      $face = $face->score();
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

    echo json_encode($result);
  }
}

