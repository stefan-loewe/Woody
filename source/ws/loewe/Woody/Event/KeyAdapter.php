<?php

namespace ws\loewe\Woody\Event;

class KeyAdapter implements KeyListener {
  /**
   * the callback to be executed for the key-pressed event, if null, no callback for this event type will be executed
   *
   * @var \Closure
   */
  private $onKeyPressed = null;

  /**
   * the callback to be executed for the key-released event, if null, no callback for this event type will be executed
   *
   * @var \Closure
   */
  private $onKeyReleased = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param \Closure $onKeyPressed the callback to be executed for the key-pressed event, if null, no callback for this
   * event type will be executed
   * @param \Closure $onKeyReleased the callback to be executed for the key-released event, if null, no callback for
   * this event type will be executed
   */
  public function __construct(\Closure $onKeyPressed = null, \Closure $onKeyReleased = null) {
    $this->onKeyPressed = $onKeyPressed;
    $this->onKeyReleased = $onKeyReleased;
  }

  public function keyPressed(KeyEvent $event) {
    if($this->onKeyPressed != null)
      $this->onKeyPressed->__invoke($event);
  }

  public function keyReleased(KeyEvent $event) {
    if($this->onKeyReleased != null)
      $this->onKeyReleased->__invoke($event);
  }
}