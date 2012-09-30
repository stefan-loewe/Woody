<?php

namespace ws\loewe\Woody\Event;

class WindowCloseEvent extends Event {
  /**
   * This method acts as the constructor of the class.
   *
   * @param EventInfo the event info containing the raw data of this event
   */
  public function __construct(EventInfo $eventInfo) {
    parent::__construct($eventInfo);
  }
  
  public function dispatch() {
    $closeListener = $this->getSource()->getWindowCloseListener();

    if($closeListener !== null) {
      $closeListener->windowClosed($this);
    }
  }
}