<?php
require 'vendor/autoload.php';

class InstagramCrawler
{
  private $instagramUsername;
  private $user;
  private $userId;

  public function __construct($instagramUsername)
  {

    if (!empty($instagramUsername)) {
      $this->instagramUsername = $instagramUsername;
      $user = Bolandish\Instagram::getUserByUsername($this->instagramUsername);
      $this->user = $user;
      if (!empty($user)) {
        $this->userId = $user->id;
      } else {
        //iets doen met lege user
      }
    }
  }

  public function getUserInfo()
  {
    $userArray = [
      'name' => $this->user->full_name,
      'id' => $this->userId,
      'description' => $this->user->biography,
      'private' => $this->user->is_private,
      'photo' => $this->user->profile_pic_url,
      'username' => $this->instagramUsername
      //TODO: count images?
    ];
    return $userArray;
  }

  public function getUserMedia($count)
  {

    /**
     * Get media from user
     */
    $images = Bolandish\Instagram::getMediaByUserID($this->userId, $count);
    $imagesArray = [];
    foreach ($images as $image) {
      if ($image->is_video == false) {
        if (!empty($image->caption)) {
          $imageDescription = $image->caption;
        } else {
          $imageDescription = false;
        }
        array_push($imagesArray, ['caption' => $imageDescription, 'src' => $image->display_src, 'thumbnail' => $image->thumbnail_src]);
      }
    }
    return $imagesArray;
    // Checks if media is accessible
//        if (empty($imagesArray)) {
//          if ($userIsPrivate) {
//            $code = 'user_private';
//          } else {
//            $code = 'user_no_media';
//          }
//        } else {
//          $code = 'success';
//        }

  }
}


//codes = user_private, user_no_media, success, user_invalid

