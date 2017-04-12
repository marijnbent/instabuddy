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

// Annotate an image, detecting faces.
  $image = $vision->image(
    $image, ['labels']
  );

  $result = $vision->annotate($image);

  /**
   * @var \Google\Cloud\Vision\Annotation $result;
   */

  if (empty($result->error())) {
    /**
     * @var integer $key
     * @var \Google\Cloud\Vision\Annotation\Entity $label
     */

    $allLabels = [];

    foreach ($result->labels() as $key => $label) {
      $labels = [$label->description(), $label->score()];
      array_push($allLabels, $labels);
    }

    echo json_encode($allLabels);
  }
}

