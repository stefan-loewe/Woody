<?php

namespace ws\loewe\Woody\Components\Controls;

use \ws\loewe\Woody\Components\Component;
use \ws\loewe\Woody\Util\Image\ImageResource;
use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Geom\Dimension;

class ImageButton extends Button {
  /**
   * the image resource the image button is associated with
   *
   * @var \ws\loewe\Woody\Utils\ImageResource
   */
  private $imageResource = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param \ws\loewe\Woody\Utils\ImageResource $imageResource the image resource the image button is associated with
   * @param Point $topLeftCorner the top left corner of the image
   * @param Dimension $dimension the dimension of the image
   */
  public function __construct(ImageResource $imageResource, Point $topLeftCorner, Dimension $dimension) {
    parent::__construct(null, $topLeftCorner, $dimension);

    $this->imageResource = $imageResource;
  }

  protected function create(Component $parent) {
    parent::create($parent);

    $this->setImage();
  }

  /**
   * This method sets the image of the image button.
   *
   * @return \ws\loewe\Woody\Components\Controls\ImageButton $this
   */
  private function setImage() {
    $bitmap = $this->imageResource->getResource();

    wb_set_image($this->controlID, $bitmap);
    wb_destroy_image($bitmap);

    return $this;
  }
}