<?php

namespace ws\loewe\Woody\Event;

final class NoOpEvent extends Event {
  /**
   * This method acts as the constructor of the class.
   *
   * @param EventInfo the event info containing the raw data of this event
   */
  public function __construct(EventInfo $eventInfo) {
    parent::__construct($eventInfo);
  }

  /**
   * This event does not trigger any actions, hence, it's dispatch method is empty.
   */
  public function dispatch() { }

  /**
   * This method returns the string representation of the event.
   *
   * @return string the string representation of the event
   */
  public function __toString() {
    return get_class($this);
  }
}