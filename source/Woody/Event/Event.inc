<?php

namespace Woody\Event;

use \Woody\Components\Component;

abstract class Event {
  /**
   * the winbinder identifier for the window or origin
   *
   * @var int
   */
  protected $windowID = null;

  /**
   * the identifier for the control or origin
   *
   * @var int
   */
  protected $id = null;

  /**
   * the winbinder identifier for the control or origin
   *
   * @var int
   */
  protected $controlID = null;

  /**
   * the winbinder identifier for the type of event
   *
   * @var int
   */
  protected $type = null;

  /**
   * the winbinder identifier for properties of the event
   *
   * @var int
   */
  protected $property = null;

  /**
   * the timestamp of the event
   *
   * @var int
   */
  protected $time = null;

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
    $this->windowID   = $windowID;
    $this->id         = $id;
    $this->controlID  = $controlID;
    $this->type       = $type;
    $this->property   = $property;
    $this->time       = microtime(TRUE);
  }

  /**
   * @TODO --> TRAIT
   */
  public function __get($memberName) {
    return $this->$memberName;
  }

  /**
   * This method returns the source of the event.
   *
   * @return Component the source of the event
   */
  public function getSource() {
    return Component::getComponentByID($this->controlID);
  }

  /**
   * This method determines if the Alt key was pressed when the event occurred.
   *
   * @return boolean true if the Alt key was pressed when the event occurred, else false
   */
  public function isAltKeyPressed() {
    return ($this->type & WBC_ALT) !== 0;
  }

  /**
   * This method determines if the Ctrl key was pressed when the event occurred.
   *
   * @return boolean true if the Ctrl key was pressed when the event occurred, else false
   */
  public function isCtrlKeyPressed() {
    return ($this->type & WBC_CONTROL) !== 0;
  }

  /**
   * This method determines if the Shift key was pressed when the event occurred.
   *
   * @return boolean true if the Shift key was pressed when the event occurred, else false
   */
  public function isShiftKeyPressed() {
    return ($this->type & WBC_SHIFT) !== 0;
  }

  /**
   * This method returns the string representation of the event.
   *
   * @return string the string representation of the event
   */
  public function __toString() {
    return PHP_EOL.'------- '.str_replace('Woody\\Event\\', '', get_class($this)).' -------------'.PHP_EOL.
            'windowID = '.$this->windowID.PHP_EOL.
            'id = '.$this->id.PHP_EOL.
            'controlID = '.$this->controlID.PHP_EOL.
            'param1 = '.$this->type.PHP_EOL.
            'param2 = '.$this->property;
  }
}