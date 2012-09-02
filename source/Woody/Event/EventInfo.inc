<?php

namespace Woody\Event;

use \Woody\Components\Component;

class EventInfo {
  use \Utils\Common\ValueObject;

  private $windowID    = null;
  private $id          = null;
  private $controlID   = null;
  private $typeID      = null;
  private $propertyID  = null;
  private $source      = null;

  public function __construct($windowID, $woodyID, $controlID, $typeID, $propertyID) {
    $this->windowID    = $windowID;
    $this->id          = $woodyID;
    $this->controlID   = $controlID;
    $this->typeID      = $typeID;
    $this->propertyID  = $propertyID;
    $this->source      = Component::getComponentByID($controlID);
  }

  public function isWindowCloseEvent() {
    return $this->id === IDCLOSE;
  }

  public function isTimerEvent() {
    return self::isWindowEvent($this->windowID, $this->controlID) && $this->id != 0 && $this->id != IDCLOSE;
  }

  public function isFocusEvent() {
    return $this->typeID === WBC_GETFOCUS;
  }

  public function isMouseEvent() {
    return ($this->typeID & WBC_MOUSEDOWN) || ($this->typeID & WBC_MOUSEUP) || ($this->typeID & WBC_DBLCLICK);
  }

  public function isKeyEvent() {
    return ($this->typeID === WBC_KEYDOWN) || ($this->typeID === WBC_KEYUP);
  }

  public function isControlEvent() {
    return $this->id !== 0;
  }

  public function isWindowEvent() {
    return $this->windowID == $this->controlID;
  }

  public function isWindowResizeEvent() {
    return $this->typeID === WBC_RESIZE;
  }
}