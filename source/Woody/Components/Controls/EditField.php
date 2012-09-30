<?php

namespace ws\loewe\Woody\Components\Controls;

use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Geom\Dimension;

abstract class EditField extends Control implements Actionable {
  /**
   * This method acts as the constructor of the class.
   *
   * @param mixed $value the preset value of the edit field
   * @param Point $topLeftCorner the top left corner of the edit field
   * @param Dimension $dimension the dimension of the edit field
   */
  public function __construct($value, Point $topLeftCorner, Dimension $dimension) {
    parent::__construct($value, $topLeftCorner, $dimension);

    $this->type = EditBox;
  }

  /**
   * This method returns the value of the edit field.
   *
   * @param boolean $trimmed determines whether or not to returned the trimmed value
   * @return string the value of the edit field
   */
  public function getValue($trimmed = TRUE) {
    $value = wb_get_text($this->controlID);

    if($trimmed) {
      $value = trim($value);
    }

    if(strlen($value) === 0) {
      $value = null;
    }

    return $value;
  }

  /**
   * This method sets the value of the edit field.
   *
   * @param mixed $newValue the new value of the edit field.
   * @return \ws\loewe\Woody\Components\Controls\EditField $this
   */
  public function setValue($newValue) {
    //$this->pauseEvents = TRUE;

    wb_set_text($this->controlID, $newValue);

    //$this->pauseEvents = FALSE;

    return $this;
  }

  /**
   * This method sets the edit field to be either read-only or not.
   *
   * @param boolean $isReadonly the read-only state of the edit field
   * @return \ws\loewe\Woody\Components\Controls\EditField $this
   */
  public function setReadOnly($isReadonly) {
    // 0x00CF = EM_SETREADONLY
    wb_send_message($this->controlID, 0x00CF, $isReadonly, 1);

    return $this;
  }

  /**
   * This method sets the cursor of the edit field to the specified index.
   *
   * @param int $index the index to set the cursor to
   * @return EditField $this
   */
  public function setCursor($index) {
    // 0x00B1 = EM_SETSEL
    wb_send_message($this->controlID, 0x00B1, $index, $index);

    return $this;
  }
}