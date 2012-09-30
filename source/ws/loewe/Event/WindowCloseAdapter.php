<?php

namespace ws\loewe\Woody\Event;

class WindowCloseAdapter implements WindowCloseListener {
  /**
   * the callback to be executed when the window is closed
   *
   * @var callable
   */
  private $onWindowClosed = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param callable $onWindowClosed the callback to be executed when the window is closed
   */
  public function __construct(callable $onWindowClosed) {
    $this->onWindowClosed = $onWindowClosed;
  }

  public function windowClosed(WindowCloseEvent $event) {
    $this->onWindowClosed->__invoke($event);
  }
}