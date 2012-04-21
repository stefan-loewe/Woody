<?php

namespace Woody\Event;

class FocusAdapter implements FocusListener {
  /**
   * the callback to be executed for the focus-gained event, if null, no callback for this event type will be executed
   *
   * @var callable
   */
  private $onFocusGained = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param callable $onFocusGained the callback to be executed for the focus-gained event, if null, no callback for this event type will be executed
   */
  public function __construct(callable $onFocusGained) {
    $this->onFocusGained = $onFocusGained;
  }

  public function focusGained(FocusEvent $event) {
    if($this->onFocusGained != null)
      $this->onFocusGained->__invoke($event);
  }
}