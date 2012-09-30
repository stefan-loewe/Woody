<?php

namespace ws\loewe\Woody\Components\Controls;

use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Geom\Dimension;

abstract class Button extends Control implements Actionable {
  /**
   * This method acts as the constructor of the class.
   *
   * @param string $label the label of the push button
   * @param Point $topLeftCorner the top left corner of the push button
   * @param Dimension $dimension the dimension of the push button
   */
  public function __construct($label, Point $topLeftCorner, Dimension $dimension) {
    parent::__construct($label, $topLeftCorner, $dimension);

    $this->type = PushButton;
  }
}