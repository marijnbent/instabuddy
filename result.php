<?php

define('ERROR_PATH', getcwd() . "/error.log");
define('INSTABUDDIES_FILE', getcwd() . "/instabuddies.json");

require 'vendor/autoload.php';

spl_autoload_register(function ($class) {
  include $class . '.php';
});

$instabuddiesJson = json_decode(file_get_contents(INSTABUDDIES_FILE), true);
error_log('JSON decoded' . PHP_EOL, 3, ERROR_PATH);

if (!empty($_GET['user'])) {
  $usernameCompared = $_GET['user'];
} else {
  header('Location: /');
}

if (!empty($instabuddiesJson[$usernameCompared])) {
  $instabuddy = new Instabuddy();
  $buddies = $instabuddy->getSimilarUser($usernameCompared, 3);

  $imageArray = [];
  foreach ($buddies as $username => $comparison) {
    $imageArray = array_merge($imageArray, array_slice($instabuddiesJson[$username]['images'], 0, 6));
  }
} else {
  header('Location: /');
}


?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Instabuddies</title>
  <link rel="stylesheet" href="css/foundation.css">
  <link rel="stylesheet" href="css/app.css">
</head>
<body>

<div id="imagegrid">
  <?php foreach ($imageArray as $img) { ?>
    <img src="<?= $img['thumbnail']; ?>" data-highres="<?= $img['src']; ?>" alt="<?= $img['caption']; ?>"/>
  <?php } ?>
</div>
<div id="imagegrid-overlay"></div>

<div class="compared row">
  <div class="large-12 columns">
    <div class="buddy">
      <?php $user = $instabuddiesJson[$usernameCompared]['user']; ?>
      <img src="<?= $user['photo']; ?>"/>
      <div class="buddy--content">
        <p class="username"><?= $user['name'] . ' (' . $user['username'] . ')'; ?></p>
      </div>
    </div>
  </div>
</div>

<div class="buddies row">
  <?php foreach ($buddies as $username => $comparison):
    $user = $instabuddiesJson[$username]['user']
    ?>
    <div class="large-4 columns">
      <div class="buddy">
        <img src="<?= $user['photo']; ?>"/>
        <span class="comparison"><?= $comparison; ?>%</span>
        <div class="buddy--content">
          <p
            class="username"><?= $user['name'] . ' (<a target="_blank" href="http://instagram.com/' . $user['username'] . '">' . $user['username'] . '</a>)'; ?></p>
          <p class="bio"><?= $user['description']; ?></p>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<script src="js/vendor/jquery.js"></script>
<script src="js/vendor/what-input.js"></script>
<script src="js/vendor/foundation.js"></script>

<script src="/js/grid.min.js"></script>
<script src="js/app.js"></script>

<script>
  $(function () {
    $('#imagegrid').photosetGrid({
      layout: '5454', //18 = 6pp
      width: '100%',
      gutter: '5px',
      highresLinks: true,
      lowresWidth: 300,
      rel: 'gallery-01',
      borderActive: false
    });
  });
</script>
</body>
</html>

