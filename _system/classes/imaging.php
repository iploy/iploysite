<?php

class Imaging
{
   public static function cropImage($src_file, $target_file, $x, $y, $width, $height)
   {
      list($width_orig, $height_orig, $type) = getimagesize($src_file);

      if ($width_orig == 0 || $height_orig == 0) {
         return false;
      }
     
      if ($type == IMG_JPG) {
         $image = @imagecreatefromjpeg($src_file);
      }
      else if ($type == IMG_PNG || $type == 3) { // php bug seemingly..
         $image = @imagecreatefrompng($src_file);
      }
      else if ($type == IMG_GIF) {
         $image = @imagecreatefromgif($src_file);
      }
      else {
         return false;
      }
      if (!$image) {
         return false;
      }
      $x = abs(intval($x));
      $y = abs(intval($y));
      $width = abs(intval($width));
      $height = abs(intval($height));
     
      if ( $width == 0 || $height == 0 || (($x + $width) > $width_orig) || (($y + $height) > $height_orig) ) {
         return false;
      }
     
      $image_p = imagecreatetruecolor($width, $height);
      if ( !imagecopy($image_p, $image, 0, 0, $x, $y, $width, $height) ) {
         return false;
      }
      return imagejpeg($image_p, $target_file, 95);
   }
}

?>