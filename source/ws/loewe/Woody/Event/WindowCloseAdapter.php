<?php

namespace ws\loewe\Woody\Event;

class WindowCloseAdapter implements WindowCloseListener {
  /**
   * the callback to be executed when the window is closed
   *
   * @var \Closure
   */
  private $onWindowClosed = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param \Closure $onWindowClosed the callback to be executed when the window is closed
   */
  public function __construct(\Closure $onWindowClosed) {
    $this->onWindowClosed = $onWindowClosed;
  }

  public function windowClosed(WindowCloseEvent $event) {
    $this->onWindowClosed->__invoke($event);
  }
}