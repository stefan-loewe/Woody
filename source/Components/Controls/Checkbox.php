<?php

namespace ws\loewe\Woody\Components\Controls;

use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Geom\Dimension;

class Checkbox extends Control implements Actionable {
  /**
   * the maximal width of a checkbox
   */
  const MAX_WIDTH = 17;

  /**
   * the maximal height of a checkbox
   */
  const MAX_HEIGHT = 17;

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
   * @inheritDoc
   *
   * A checkbox cannot be resized any larger than defined by VISIBLE_WIDTH and VISIBLE_HEIGHT, respectively.
   *
   * @param Dimension $dimension the Dimension to resize the Checkbox to.
   */
  public function resizeTo(Dimension $dimension) {
    if($dimension->width > Checkbox::MAX_WIDTH) {
      $dimension = $dimension->resizeTo(new Dimension(Checkbox::MAX_WIDTH, $dimension->height));
    }

    if($dimension->height > Checkbox::MAX_HEIGHT) {
      $dimension = $dimension->resizeTo(new Dimension($dimension->width, Checkbox::MAX_HEIGHT));
    }

    parent::resizeTo($dimension);
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