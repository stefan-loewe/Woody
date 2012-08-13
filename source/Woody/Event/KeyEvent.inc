<?php

namespace Woody\Event;

class KeyEvent extends Event {
  /**
   * This method acts as the constructor of the class.
   *
   * @param int $windowID the winbinder identifier for the control or origin
   * @param int $id the identifier for the control or origin
   * @param int $controlID the winbinder identifier for the control or origin
   * @param int $type the winbinder identifier for the type of event
   * @param int $property the winbinder identifier for properties of the event
   */
  public function __construct($windowID, $id, $controlID, $type, $property) {
    parent::__construct($windowID, $id, $controlID, $type, $property);
  }

  /**
   * This method returns which key was pressed.
   *
   * @return string the key which was pressed
   */
  public function getPressedKey() {
    return chr($this->property);
  }

  /**
   * This method returns the string representation of the event.
   *
   * @return string the string representation of the event
   */
  public function __toString() {
    return parent::__toString().PHP_EOL.
      'key = '.$this->getPressedKey().PHP_EOL.
      'A/C/S = '.$this->isAltKeyPressed()
      .'/'.$this->isCtrlKeyPressed()
      .'/'.$this->isShiftKeyPressed();
  }
}