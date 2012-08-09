<?php

namespace Woody\Components\Controls;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class Slider extends Control implements Actionable {
  /**
   * This method acts as the constructor of the class.
   *
   * @param Point $topLeftCorner the top left corner of the slider
   * @param Dimension $dimension the dimension of the slider
   */
  public function __construct(Point $topLeftCorner, Dimension $dimension) {
    parent::__construct(null, $topLeftCorner, $dimension);

    $this->type = Slider;
  }

  /**
   * This method gets the current value of the slider.
   *
   * @return int the current value of the slider
   */
  public function getValue() {
    return wb_get_value($this->controlID);
  }

  /**
   * This method sets the current value of the slider.
   *
   * @param $value the new value of the slider
   * @return \Woody\Components\Controls\Slider $this
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
   * @return \Woody\Components\Controls\Slider $this
   */
  public function setRange($min, $max) {
    wb_set_range($this->controlID, $min, $max);

    return $this;
  }
}