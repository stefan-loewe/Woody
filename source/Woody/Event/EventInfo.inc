<?php

namespace Woody\Event;

use \Woody\Components\Component;

class EventInfo {
  use \Utils\Common\ValueObject;

  private $windowID    = null;
  private $id          = null;
  private $controlID   = null;
  private $type        = null;
  private $property    = null;
  private $source      = null;

  public function __construct($windowID, $woodyID, $controlID, $typeID, $propertyID) {
    $this->windowID    = $windowID;
    $this->id          = $woodyID;
    $this->controlID   = $controlID;
    $this->type        = $typeID;
    $this->property    = $propertyID;

    if($this->controlID !== 0)
      $this->source      = Component::getComponentByID($this->controlID);
  }

  public function isWindowEvent() {
    return $this->windowID == $this->controlID;
  }

  public function isWindowResizeEvent() {
    return $this->type === WBC_RESIZE;
  }

  public function isWindowCloseEvent() {
    return $this->id === IDCLOSE;
  }

  public function isTimerEvent() {
    return $this->isWindowEvent() && $this->id != 0 && $this->id != IDCLOSE;
  }

  public function isControlEvent() {
    return $this->id !== 0;
  }

  public function isFocusEvent() {
    return $this->type === WBC_GETFOCUS;
  }

  public function isMouseEvent() {
    return ($this->type & WBC_MOUSEDOWN) || ($this->type & WBC_MOUSEUP) || ($this->type & WBC_DBLCLICK);
  }

  public function isKeyEvent() {
    return ($this->type === WBC_KEYDOWN) || ($this->type === WBC_KEYUP);
  }
}