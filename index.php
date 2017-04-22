<?php

ini_set('max_execution_time', 0);
ini_set('set_time_limit', 0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!defined('ERROR_PATH'))
  define('ERROR_PATH', getcwd() . "/error.log");
if (!defined('INSTABUDDIES_FILE'))
  define('INSTABUDDIES_FILE', getcwd() . "/instabuddies.json");


require 'vendor/autoload.php';
spl_autoload_register(function ($class) {
  include $class . '.php';
});

$generating = false;
$json = json_decode(file_get_contents(INSTABUDDIES_FILE), true);
if (isset($_POST['instragramUsername']) && !empty($_POST['instragramUsername'])) {
  $username = $_POST['instragramUsername'];
  if (empty($json[$username])) {
    $instabuddy = new Instabuddy();
    $instabuddy->addToAnalyse($username);
    $generating = true;
  } else {
    header('Location: /result.php?user=' . $username);
  }
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
<div class="row">
  <div class="large-12 columns">
    <h1><a href="/">Instabuddies</a></h1>
  </div>
</div>

<?php if ($generating): ?>
  <div class="row">
    <div class="large-12 columns">
      <div class="callout">
        <h3>Thanks for trying out InstaBuddies!</h3>
        <p>Your profile is being analysed right now. Sit back, relax, and come back in a few minutes.</p>
      </div>
    </div>
  </div>

<?php else: ?>
<div class="row">
  <div class="large-12 columns">
    <div class="callout">
      <h3>We&rsquo;re stoked you want to try InstaBuddies! </h3>
      <p>To get going, check if your Instagram account is set to public and type in your username below!</p>
      <form action="" method="post">
        <div class="row">
          <div class="large-12 columns">
            <label>Instagram Username</label>
            <input id="instragramUsername" name="instragramUsername" type="text" placeholder="marijnbent" />
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
<?php endif; ?>

<script src="js/vendor/jquery.js"></script>
<script src="js/vendor/what-input.js"></script>
<script src="js/vendor/foundation.js"></script>
<script src="js/app.js"></script>
</body>
</html>

