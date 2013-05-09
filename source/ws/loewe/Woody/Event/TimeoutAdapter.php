<?php

namespace ws\loewe\Woody\Event;

class TimeoutAdapter implements TimeoutListener {
  /**
   * the callback to be executed when the timeout occurs
   *
   * @var \Closure
   */
  private $onTimeout = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param \Closure $onTimeout the callback to be executed when the timeout occurs
   */
  public function __construct(\Closure $onTimeout) {
    $this->onTimeout = $onTimeout;
  }

  public function timeout(TimeoutEvent $event) {
    $this->onTimeout->__invoke($event);
  }
}