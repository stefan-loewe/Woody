<?php

namespace Woody\Components\Controls;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class InvisibleArea extends Control {
  /**
   * This method acts as the constructor of the class.
   *
   * @param Point $topLeftCorner the top left corner of the invisible area
   * @param Dimension $dimension the dimension of the invisible area
   */
  public function __construct($value, Point $topLeftCorner, Dimension $dimension) {
    parent::__construct(null, $topLeftCorner, $dimension);

    $this->type = InvisibleArea;
  }
}