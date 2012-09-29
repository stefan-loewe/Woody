<?php

namespace Woody\Components\Controls;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class PushButton extends Button {
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

  /**
   * This method returns the label of the button.
   *
   * @return string the label of the button
   */
  public function getLabel() {
    return wb_get_text($this->controlID);
  }

  /**
   * This method sets the label of the button.
   *
   * @param string $label the label to set
   * @return \Woody\Components\Controls\PushButton
   */
  public function setLabel($label) {
    wb_set_text($this->controlID, $label);

    return $this;
  }
}