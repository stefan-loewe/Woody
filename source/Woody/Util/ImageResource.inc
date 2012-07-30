<?php

namespace Woody\Util;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class ImageResource {
  /**
   * the dimension of the image resource
   *
   * @var \Utils\Geom\Dimension
   */
  private $dimension = null;

  /**
   * the handle to the raw resource
   *
   * @var int
   */
  private $resource = null;

  /**
   * This method creates a blank image.
   *
   * @param \Utils\Geom\Dimension $dimension the dimension of the image
   * @return \Woody\Util\ImageResource the blank image
   */
  public static function create(Dimension $dimension) {
    return new ImageResource($dimension, null);
  }

  /**
   * This method creates an image from the given file with the dimensions of the image, or with the supplied dimension.
   *
   * @param string the file name of the image file
   * @param \Utils\Geom\Dimension $dimension the dimension to which to crop the image
   * @return \Woody\Util\ImageResource the blank image
   */
  public static function createFromFile($imageFileName, Dimension $dimension = null) {
    if($dimension == null) {
      $dimension = getimagesize($imageFileName);
      $dimension = new Dimension($dimension[0], $dimension[1]);
    }

    return new ImageResource($dimension, $imageFileName);
  }

  /**
   * This method acts as the constructor of the class.
   *
   * @param \Utils\Geom\Dimension $dimension the dimension of the image
   * @param string $imageFileName the image file to create the image from
   */
  private function __construct(Dimension $dimension, $imageFileName = null) {
    $this->dimension = new Dimension($dimension->width, $dimension->height);

    if($imageFileName !== null) {
      $this->createResourceFromFile($imageFileName);
    }
    else {
      $this->createResourceBlank();
    }
  }

  /**
   * This method creates a blank image resource.
   */
  private function createResourceBlank() {
    $this->resource = wb_create_image(
        $this->dimension->width,
        $this->dimension->height);
  }

  /**
   * This method creates an image resource from a given image file.
   *
   * @param string $imageFileName the image file to create the image from
   */
  private function createResourceFromFile($imageFileName) {
    $dib = FreeImage_Load(self::isJpeg($imageFileName) ? FIF_JPEG : FIF_BMP, $imageFileName, 0);

    $this->resource = wb_create_image(
      $this->dimension->width,
      $this->dimension->height,
      FreeImage_GetInfoHeader($dib),
      FreeImage_GetBits($dib));

    FreeImage_Unload($dib);
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

  /**
   * This method returns the dimension of the image resource.
   *
   * @return \Utils\Geom\Dimension the image resource
   */
  public function getDimension() {
    return new Dimension($this->dimension->width, $this->dimension->height);
  }

  /**
   * This method draws a line on the image.
   *
   * @param \Utils\Geom\Point $source the source point of the line
   * @param \Utils\Geom\Point $target the target point of the line
   * @param int $color the color of the line
   * @param boolean $width the width of the line in pixels
   * @param boolean $style the sytle of the line
   */
  public function drawLine(Point $source, Point $target, $color, $width = null, $style = null) {
    wb_draw_line($this->resource, $source->x, $source->y, $target->x, $target->y, $color, $width, $style);
  }

  /**
   * This method draws a rectangle on the image.
   *
   * @param \Utils\Geom\Point $tlc the top left corner of the rectangle
   * @param \Utils\Geom\Dimension $dim the dimension of the rectangle
   * @param int $color the color of the rectangle
   * @param boolean $filled whether or not to draw the rectangle filled
   * @param boolean $width the width of the line in pixels
   * @param boolean $style the sytle of the line
   */
  public function drawRectangle(Point $tlc, Dimension $dim, $color, $filled = TRUE, $width = null, $style = null) {
    wb_draw_rect($this->resource,
      $tlc->x,
      $tlc->y,
      $dim->width,
      $dim->height,
      $color,
      $filled,
      $width,
      $style);
  }

  /**
   * This method returns the handle to the raw resource.
   *
   * @return int
   */
  public function getResource() {
    return $this->resource;
  }
}