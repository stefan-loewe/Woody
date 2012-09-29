<?php

namespace Woody\Event;

use Woody\Components\Timer\Timer;

class TimeoutEvent extends Event {
  /**
   * This method acts as the constructor of the class.
   *
   * @param EventInfo the event info containing the raw data of this event
   */
  public function __construct(EventInfo $eventInfo) {
    parent::__construct($eventInfo);
  }
  
  public function dispatch() {
    $timeoutListeners = Timer::getTimerByID($this->id)->getTimeoutListeners();

    foreach($timeoutListeners as $timeoutListener) {
      $timeoutListener->timeout($this);
    }
  }
}