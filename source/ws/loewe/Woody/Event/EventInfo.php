<?php

namespace ws\loewe\Woody\Event;

use \ws\loewe\Woody\Components\Component;

class EventInfo {
  use \ws\loewe\Utils\Common\ValueObject;

  private $windowID    = null;
  private $id          = null;
  private $controlID   = null;
  private $type        = null;
  private $property    = null;
  private $source      = null;

  public function __construct($windowID, $woodyID, Component $component, $typeID, $propertyID) {
    $this->windowID    = $windowID;
    $this->id          = $woodyID;
    $this->controlID   = $component->getControlID();
    $this->type        = $typeID;
    $this->property    = $propertyID;

    $this->source      = $component;
  }

  public function isWindowEvent() {
    return $this->windowID == $this->controlID;
  }

  public function isWindowResizeEvent() {
    return (is_int($this->type) ? $this->type === WBC_RESIZE : FALSE);
  }

  public function isWindowCloseEvent() {
    return $this->id === IDCLOSE;
  }

  public function isOkEvent() {
    return $this->isWindowEvent() && $this->id == 1 && $this->id != IDCLOSE;
  }

  public function isCancelEvent() {
    return $this->isWindowEvent() && $this->id == 2 && $this->id != IDCLOSE;
  }

  public function isTimerEvent() {
    return $this->isWindowEvent() && $this->id != 0 && $this->id != IDCLOSE;
  }

  public function isControlEvent() {
    return $this->id !== 0;
  }

  public function isFocusEvent() {
    return (is_int($this->type) ? $this->type === WBC_GETFOCUS : FALSE);
  }

  public function isMouseEvent() {
    if(is_int($this->type)) {
      return ($this->type & WBC_MOUSEDOWN) || ($this->type & WBC_MOUSEUP) || ($this->type & WBC_DBLCLICK);
    }
    else {
      return FALSE;
    }
  }

  public function isKeyEvent() {
    if(is_int($this->type)) {
      return ($this->type === WBC_KEYDOWN) || ($this->type === WBC_KEYUP);
    }
    else {
      return FALSE;
    }
  }
}
