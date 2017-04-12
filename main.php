<?php

function __autoload($class_name) {
  require_once $class_name . '.php';
}

//$instagramUsername = 'marijnbent';
//$inst = new InstagramCrawler($instagramUsername);
//echo json_encode($inst->getUserMedia());

//$url = 'https://cloud.google.com/vision/docs/images/cat.jpg';
$url = 'https://static.afbeeldinguploaden.nl/1704/250246/5FqDDsFF.png';

$vision = new VisionImageScan();
print_r( $vision->getImageResult($url));
