<?php

namespace ws\loewe\Woody\Event;

class KeyEvent extends Event {
  /**
   * This method acts as the constructor of the class.
   *
   * @param EventInfo the event info containing the raw data of this event
   */
  public function __construct(EventInfo $eventInfo) {
    parent::__construct($eventInfo);
  }
  
  public function dispatch() {
    foreach($this->getSource()->getKeyListeners() as $keyListener) {
      if($this->isKeyDownEvent()) {
        $keyListener->keyPressed($this);
      }

      else if($this->isKeyUpEvent()) {
        $keyListener->keyReleased($this);
      }
    }
  }

  private function isKeyUpEvent() {
    return ($this->type & WBC_KEYUP) == WBC_KEYUP;
  }

  private function isKeyDownEvent() {
    return ($this->type & WBC_KEYDOWN) === WBC_KEYDOWN;
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