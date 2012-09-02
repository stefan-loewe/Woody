<?php

namespace Woody\Event;

use \Woody\Components\Component;

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
   * @param EventInfo the event info containing the raw data of this event
   * @param $lostFocusComponent Component the component which lost the focus, which maybe null if no component was
   */
  public function __construct(EventInfo $eventInfo, Component $lostFocusComponent = null) {
    parent::__construct($eventInfo);

    $this->lostFocusComponent = $lostFocusComponent;
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
    return $this->lostFocusComponent;
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