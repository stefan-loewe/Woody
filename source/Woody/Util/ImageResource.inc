<?php

namespace Woody\Util;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class ImageResource {
  /**
   * the file name of the image to create the image resource for
   *
   * @var string
   */
  private $imageFileName = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param string $imageFileName the file to use as image
   */
  public function __construct($imageFileName, Dimension $dimension = null) {
    $this->imageFileName = $imageFileName;

    if($dimension == null) {
      $this->dimension = getimagesize($this->imageFileName);
      $this->dimension = new Dimension($this->dimension[0], $this->dimension[1]);
    }
    else {
      $this->dimension = new Dimension($dimension->width, $dimension->height);
    }
  }

  /**
   * This method returns the image resource as bitmap.
   *
   * @return string the image resource as bitmap
   */
  public function getBitmap() {
    $dib = FreeImage_Load(self::isJpeg($this->imageFileName) ? FIF_JPEG : FIF_BMP, $this->imageFileName, 0);

    $bmp = wb_create_image(
      $this->dimension->width,
      $this->dimension->height,
      FreeImage_GetInfoHeader($dib), FreeImage_GetBits($dib)
    );

    FreeImage_Unload($dib);

    return $bmp;
  }

  /**
   * This method determines if the given image is a jpeg or not.
   *
   * @param string $imageFileName the file name of the image file to determine its file type
   *
   * @return boolean true, if the image is a jpeg, else false
   * @todo copied from ImageButton - extract image resource as class !?!?!
   */
  private static function isJpeg($imageFileName) {
    return substr($imageFileName, -4) === '.jpg';
  }
}