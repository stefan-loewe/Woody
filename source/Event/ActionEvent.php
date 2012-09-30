<?php

namespace ws\loewe\Woody\Event;

class ActionEvent extends Event {
  /**
   * This method acts as the constructor of the class.
   *
   * @param EventInfo the event info containing the raw data of this event
   */
  public function __construct(EventInfo $eventInfo) {
    parent::__construct($eventInfo);
  }

  public function dispatch() {
    foreach($this->getSource()->getActionListeners() as $actionListener) {
      $actionListener->actionPerformed($this);
    }
  }

  /**
   * This method returns the string representation of the event.
   *
   * @return string the string representation of the event
   */
  public function __toString() {
    return parent::__toString().PHP_EOL.
            'A/C/S = '.$this->isAltKeyPressed().'/'.$this->isCtrlKeyPressed().'/'.$this->isShiftKeyPressed();
  }
}