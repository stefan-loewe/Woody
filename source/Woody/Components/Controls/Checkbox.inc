<?php

namespace Woody\Components\Controls;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class Checkbox extends Control {
  
  const VISIBLE_HEIGHT = 17;
  /**
   * This method acts as the constructor of the class.
   *
   * @param boolean $value the preset value of the checkbox
   * @param Point $topLeftCorner the top left corner of the checkbox
   * @param Dimension $dimension the dimension of the checkbox
   */
  public function __construct($value, Point $topLeftCorner, Dimension $dimension) {
    parent::__construct($value, $topLeftCorner, $dimension);

    $this->type = CheckBox;
  }

  /**
   * This method returns whether the checkbox is checked or not.
   *
   * @return boolean true, if the checkbox is checked, else false
   */
  public function isChecked() {
    // comparison with === 1 fails for checked checkbox
    return wb_get_value($this->controlID) == 1;
  }

  /**
   * This method sets the checkbox checked or unchecked, depending on the given value.
   *
   * @param boolean $isChecked a flag whether to check or uncheck the checkbox
   * @return Checkbox $this
   */
  public function setChecked($isChecked) {
    wb_set_value($this->controlID, $isChecked);

    return $this;
  }
}