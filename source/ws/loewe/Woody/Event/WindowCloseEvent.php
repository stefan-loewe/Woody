<?php

namespace ws\loewe\Woody\Event;

use ws\loewe\Woody\Components\Windows\AbstractWindow;

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
    $source = $this->getSource();
    
    if($source instanceof AbstractWindow) {
      $closeListener = $source->getWindowCloseListener();

      if($closeListener !== null) {
        $closeListener->windowClosed($this);
      }
    } else {
      throw new \RuntimeException('Dispatching WindowCloseEvent from a non-window control!');
    }
  }
}