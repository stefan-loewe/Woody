<?php

namespace ws\loewe\Woody\Components\Controls;

use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Geom\Dimension;

class EditArea extends EditField {
  /**
   * This method acts as the constructor of the class.
   *
   * @param mixed $value the preset value of the edit area
   * @param Point $topLeftCorner the top left corner of the edit area
   * @param Dimension $dimension the dimension of the edit area
   */
  public function __construct($value, Point $topLeftCorner, Dimension $dimension) {
    parent::__construct($value, $topLeftCorner, $dimension);

    $this->style = $this->style | WBC_MULTILINE;
  }
}