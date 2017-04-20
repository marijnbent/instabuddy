<?php

define('ERROR_PATH', getcwd() . "/error.log");
define('INSTABUDDIES_FILE', getcwd() . "/instabuddies.json");

require 'vendor/autoload.php';

spl_autoload_register(function ($class) {
  include $class . '.php';
});

$instabuddiesJson = json_decode(file_get_contents(INSTABUDDIES_FILE), true);
error_log('JSON decoded' . PHP_EOL, 3, ERROR_PATH);

if (!empty($_POST['username'])) {
  $username = $_POST['username'];
}
$username = 'marijnbent';

if (!empty($instabuddiesJson[$username])) {
  $instabuddy = new Instabuddy();
  $buddySimilar = $instabuddy->getSimilarUser($username);
  $buddyUsername = array_keys($buddySimilar)[0];
  $comparisonPercentage = $buddySimilar[$buddyUsername];

  $user1Images = $instabuddiesJson[$username]['images'];
  $user2Images = $instabuddiesJson[$buddyUsername]['images'];
  $user1Info = $instabuddiesJson[$username]['user'];
  $user2Info = $instabuddiesJson[$buddyUsername]['user'];
  $user1Images = array_slice($user1Images, 0, 8);
  $user2Images = array_slice($user2Images, 0, 8);

  $imageArray = [];
  foreach ($user1Images as $image) {
    $imageArray[] = [
      'src' => $image['src'],
      'caption' => $image['caption'],
      'thumbnail' => $image['thumbnail'],
    ];
  }
  foreach ($user2Images as $image) {
    $imageArray[] = [
      'src' => $image['src'],
      'caption' => $image['caption'],
      'thumbnail' => $image['thumbnail'],
    ];
  }

}


?>
<html>
<head>
  <style>
    body {
      margin: 0;
      padding 0;
      font-family: Helvetica, sans-serif;
    }

    #imagegrid {
      height: 100vh;
      width: 100vw;
      overflow: hidden;
    }

    #imagegrid-overlay {
      background-color: rgba(0, 0, 0, 0.3);
      position: absolute;
      top: 0;
      height: 100vh;
      width: 100vw;
    }

    .photoset-row {
      height: 25vh !important;
    }

    #imagegrid img {
      object-fit: cover;
      height: 100% !important;
      margin-top: 0 !important;
    }

    .buddies {
      position: absolute;
      top: 0;
      background: white;
      padding: 10px;
      border-radius: 3px;
      margin-left: 20vw;
      margin-right: 20vw;
      display: block;
      width: 60vw;
      margin-top: 30vh;
      min-height: 300px;
    }

    .buddies .left {
      width: 40%;
      margin-left: 5%;
      float: left;
    }

    .buddies .right {
      width: 40%;
      margin-right: 5%;
      float: right;
    }

    .buddies .percentage {
      font-size: 60px;
      width: 100%;
      display: block;
      text-align: center;
      padding-top: 10px;
      padding-bottom: 20px;
    }
  </style>
</head>
<body>


<div id="imagegrid">
  <?php foreach ($imageArray as $img) { ?>
    <img src="<?= $img['thumbnail']; ?>" data-highres="<?= $img['src']; ?>" alt="<?= $img['caption']; ?>"/>
  <?php } ?>
</div>
<div id="imagegrid-overlay"></div>

<div class="buddies">
  <span class="percentage"><?= $comparisonPercentage; ?>%</span>
  <div class="left">
    <img src="<?= $user1Info['photo']; ?>"/>
    <p class="username"><?= $user1Info['name'] . ' (' . $user1Info['username'] . ')'; ?></p>
    <p class="bio"><?= $user1Info['description']; ?></p>
  </div>
  <div class="right">
    <img src="<?= $user2Info['photo']; ?>"/>
    <p class="username"><?= $user2Info['name'] . ' (' . $user2Info['username'] . ')'; ?></p>
    <p class="bio"><?= $user2Info['description']; ?></p>
  </div>
</div>

<script
  src="https://code.jquery.com/jquery-2.2.4.min.js"
  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
  crossorigin="anonymous"></script>
<script src="/js/grid.min.js"></script>

<script>
  $(function () {
    $('#imagegrid').photosetGrid({
      layout: '5434',
      width: '100%',
      gutter: '5px',
      highresLinks: true,
      lowresWidth: 300,
      rel: 'gallery-01',
      borderActive: false,

      onInit: function () {
      },
      onComplete: function () {

        $('.photoset-grid').css({
          'visibility': 'visible'
        });

      }
    });


  });
</script>
</body>
</html>
