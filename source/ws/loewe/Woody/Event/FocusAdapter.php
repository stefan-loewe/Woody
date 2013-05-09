<?php

namespace ws\loewe\Woody\Event;

class FocusAdapter implements FocusListener {
  /**
   * the callback to be executed for the focus-gained event, if null, no callback for this event type will be executed
   *
   * @var \Closure
   */
  private $onFocusGained = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param \Closure $onFocusGained the callback to be executed for the focus-gained event, if null, no callback for
   * this event type will be executed
   */
  public function __construct(\Closure $onFocusGained) {
    $this->onFocusGained = $onFocusGained;
  }

  public function focusGained(FocusEvent $event) {
    if($this->onFocusGained != null)
      $this->onFocusGained->__invoke($event);
  }
}