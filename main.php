<?php

spl_autoload_register(function ($class) {
  include $class . '.php';
});

$instagramUsername = 'marijnbent';
$insta = new InstagramCrawler($instagramUsername);
$vision = new VisionImageScan;
$scanResult = new ScanResult();

$result = [];
$images = $insta->getUserMedia();
foreach ($images as $image) {
  $imageCaption = $image['caption'];
  $imageSrc = $image['src'];
  $visionResult = $vision->getImageResult($imageSrc);
  array_push($result, ['vision' => $visionResult, 'image' => $image]);

  $scanResult->generateDominantLabels($visionResult['labels']);
}

echo json_encode($scanResult->getDominantLabels());
echo json_encode($result);