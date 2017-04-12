<?php
require 'vendor/autoload.php';

if (isset($_GET['instagramUsername']) && !empty($_GET['instagramUsername'])) {

  $instaUsr = $_GET['instagramUsername'];
  $user = Bolandish\Instagram::getUserByUsername($instaUsr);

  if (!empty($user)) {
    $userId = $user->id;
    $userFullName = $user->full_name;
    $userIsPrivate = $user->is_private;
    $userDescription = $user->biography;
    $userProfilePhoto = $user->profile_pic_url;

    $userArray = [
      'name' => $userFullName,
      'id' => $userId,
      'description' => $userDescription,
      'private' => $userIsPrivate,
      'photo' => $userProfilePhoto,
      'username' => $_GET['instagramUsername'],
    ];

    /**
     * Get media from user
     */
    $images = Bolandish\Instagram::getMediaByUserID($userId);
    $imagesArray = [];
    foreach ($images as $image) {
      if ($image->is_video == false) {

        if (!empty($image->caption)) {
          $imageDescription = $image->caption;
        } else {
          $imageDescription = false;
        }

        $imageSrc = $image->display_src;
        array_push($imagesArray, ['caption' => $imageDescription, 'src' => $imageSrc]);
      }
    }

    // Checks if media is accessible
    if (empty($imagesArray)) {
      if ($userIsPrivate) {
        $code = 'user_private';
      } else {
        $code = 'user_no_media';
      }
    } else {
      $code = 'success';
    }

    $result = [
      'code' => $code,
      'user' => $userArray,
      'images' => $imagesArray,
    ];

    echo json_encode($result);
  } else {
    echo json_encode(['code' => 'user_invalid']); //empty user
  }
}

//codes = user_private, user_no_media, success, user_invalid

