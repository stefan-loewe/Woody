<?php

namespace Woody\Components\Controls;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class Label extends Control {
  /**
   * This method acts as the constructor of the class.
   *
   * @param string $label the label of the label control
   * @param Point $topLeftCorner the top left corner of the label control
   * @param Dimension $dimension the dimension of the label control
   */
  public function __construct($label, Point $topLeftCorner, Dimension $dimension) {
    parent::__construct($label, $topLeftCorner, $dimension);

    $this->type = Label;
  }

  /**
   * This method returns the label of the label control
   *
   * @return string the label of the label control
   */
  public function getLabel() {
    return wb_get_text($this->controlID);
  }

  /**
   * This method sets the label of the label control
   *
   * @param string $label the label of the label control
   * @return \Woody\Components\Controls\Label $this
   */
  public function setLabel($label) {
    wb_set_text($this->controlID, $label);

    return $this;
  }
}