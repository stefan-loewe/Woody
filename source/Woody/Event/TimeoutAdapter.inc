<?php

namespace Woody\Event;

class TimeoutAdapter implements TimeoutListener {
  /**
   * the callback to be executed when the timeout occurs
   *
   * @var callable
   */
  private $onTimeout = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param callable $onTimeout the callback to be executed when the timeout occurs
   */
  public function __construct(callable $onTimeout) {
    $this->onTimeout = $onTimeout;
  }

  public function timeout(TimeoutEvent $event) {
    $this->onTimeout->__invoke($event);
  }
}