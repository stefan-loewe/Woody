<?php

namespace ws\loewe\Woody\Components\Controls;

use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Geom\Dimension;

class Spinner extends Control implements Actionable {
  /**
   * This method acts as the constructor of the class.
   *
   * @param Point $topLeftCorner the top left corner of the spinner
   * @param Dimension $dimension the dimension of the spinner
   */
  public function __construct(Point $topLeftCorner, Dimension $dimension) {
    parent::__construct(null, $topLeftCorner, $dimension);

    $this->type = Spinner;
  }

  /**
   * This method gets the current value of the spinner.
   *
   * @return int the current value of the spinner
   */
  public function getValue() {
    return wb_get_value($this->controlID);
  }

  /**
   * This method sets the current value of the spinner.
   *
   * @param $value the new value of the spinner
   * @return \ws\loewe\Woody\Components\Controls\Spinner $this
   */
  public function setValue($value) {
    wb_set_value($this->controlID, $value);

    return $this;
  }

  /**
   * This method sets the range of the slider.
   *
   * @param int $min the minimal value of the slider
   * @param int $max the maximal value of the slider
   * @return \ws\loewe\Woody\Components\Controls\Spinner $this
   */
  public function setRange($min, $max) {
    wb_set_range($this->controlID, $min, $max);

    return $this;
  }
}