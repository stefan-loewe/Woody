<?php

namespace ws\loewe\Woody\Event;

class WindowResizeAdapter implements WindowResizeListener {
  /**
   * the callback to be executed for the window resize event
   *
   * @var \Closure
   */
  private $onWindowResize = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param \Closure $onWindowResize the callback to be executed for the window resize event
   */
  public function __construct(\Closure $onWindowResize) {
    $this->onWindowResize = $onWindowResize;
  }

  public function windowResized(WindowResizeEvent $event) {
    $this->onWindowResize->__invoke($event);
  }
}