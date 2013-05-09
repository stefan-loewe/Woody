<?php

namespace ws\loewe\Woody\Event;

class WindowResizeAdapter implements WindowResizeListener {
  /**
   * the callback to be executed for the window resize event
   *
   * @var callable
   */
  private $onWindowResize = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param callable $onWindowResize the callback to be executed for the window resize event
   */
  public function __construct(callable $onWindowResize) {
    $this->onWindowResize = $onWindowResize;
  }

  public function windowResized(WindowResizeEvent $event) {
    $this->onWindowResize($event);
  }
}