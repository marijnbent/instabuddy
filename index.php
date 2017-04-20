<?php

if (!defined('ERROR_PATH'))
  define('ERROR_PATH', getcwd() . "/error.log");
if (!defined('INSTABUDDIES_FILE'))
  define('INSTABUDDIES_FILE', getcwd() . "/instabuddies.json");

require 'vendor/autoload.php';
spl_autoload_register(function ($class) {
  include $class . '.php';
});

error_log('------- Main started' . PHP_EOL, 3, ERROR_PATH);

$json = json_decode(file_get_contents(INSTABUDDIES_FILE), true);
echo count($json);

$instabuddy = new Instabuddy();
var_dump($instabuddy->getSimilarUser('sannebent03', 10));
die();
//$result = $instabuddy->newJsonEntry('sannebent03', false);
//error_log($result . PHP_EOL, 3, ERROR_PATH);

error_log('==== Main Ended' . PHP_EOL, 3, ERROR_PATH);

?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Foundation for Sites</title>
  <link rel="stylesheet" href="css/foundation.css">
  <link rel="stylesheet" href="css/app.css">
</head>
<body>
<div class="row">
  <div class="large-12 columns">
    <h1>Instabuddies</h1>
  </div>
</div>

<div class="row">
  <div class="large-12 columns">
    <div class="callout">
      <h3>We&rsquo;re stoked you want to try Foundation! </h3>
      <p>To get going, this file (index.html) includes some basic styles you can modify, play around with, or totally destroy to get going.</p>
      <p>Once you've exhausted the fun in this document, you should check out:</p>
      <form action="" method="post">
        <div class="row">
          <div class="large-12 columns">
            <label>Instagram Username</label>
            <input type="text" placeholder="marijnbent" />
          </div>
        </div>
        <div class="row">
          <div class="large-12 columns">
            <input type="submit" class="button" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="js/vendor/jquery.js"></script>
<script src="js/vendor/what-input.js"></script>
<script src="js/vendor/foundation.js"></script>
<script src="js/app.js"></script>
</body>
</html>

