<?php

namespace Woody\Event;

use \Woody\Components\Component;
use \Utils\Geom\Point;

class FocusEvent extends Event {
  /**
   * the component which lost the focus, maybe null if no component was focused before
   *
   * @var Component
   */
  protected $lostFocusComponent = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param int $windowID the winbinder identifier for the control or origin
   * @param int $id the identifier for the control or origin
   * @param int $controlID the winbinder identifier for the control or origin
   * @param int $type the winbinder identifier for the type of event
   * @param int $property the winbinder identifier for properties of the event
   * @param $lostFocusComponent Component the component which lost the focus, which maybe null if no component was focused before
   */
  public function __construct($windowID, $id, $controlID, $type, $property, Component $lostFocusComponent = null) {
    parent::__construct($windowID, $id, $controlID, $type, $property);

    $this->lostFocusComponent = $lostFocusComponent;
    //var_dump($this->lostFocusComponent === null ? '$this->lostFocusComponent is null' : '$this->lostFocusComponent is NOT null');
  }

  /**
   * This method returns which component gained focus.
   *
   * @return Component the component which gained focus
   */
  public function getFocusGainedComponent() {
    return Component::getComponentByID($this->controlID);
  }

  /**
   * This method returns which component lost focus.
   *
   * @return Component the component which lost focus
   */
  public function getFocusLostComponent() {
    return Component::getControlByID($this->controlID);
  }

  /**
   * This method returns the string representation of the event.
   *
   * @return string the string representation of the event
   */
  public function __toString() {
    return parent::__toString().PHP_EOL.
            'gained = '.$this->getFocusGainedComponent()->getID().PHP_EOL.
            'lost = '.($this->lostFocusComponent === null ? 'none' : $this->lostFocusComponent->getID());
  }
}