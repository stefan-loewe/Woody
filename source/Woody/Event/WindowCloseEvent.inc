<?php

namespace Woody\Event;

class WindowCloseEvent extends Event {

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
    parent::__construct($windowID, $id, $controlID, $type, $property);
  }
}