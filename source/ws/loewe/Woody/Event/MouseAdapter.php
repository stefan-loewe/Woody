<?php

namespace ws\loewe\Woody\Event;

class MouseAdapter implements MouseListener {
  /**
   * the callback to be executed for the mouse-pressed event, if null, no callback for this event type will be executed
   *
   * @var callable
   */
  private $onMousePressed = null;

  /**
   * the callback to be executed for the mouse-released event, if null, no callback for this event type will be executed
   *
   * @var callable
   */
  private $onMouseReleased = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param callable $onMousePressed the callback to be executed for the mouse-pressed event, if null, no callback for
   * this event type will be executed
   * @param callable $onMouseReleased the callback to be executed for the mouse-released event, if null, no callback for
   * this event type will be executed
   */
  public function __construct(callable $onMousePressed = null, callable $onMouseReleased = null) {
    $this->onMousePressed  = $onMousePressed;
    $this->onMouseReleased = $onMouseReleased;
  }

  public function mousePressed(MouseEvent $event) {
    if($this->onMousePressed != null) {
      $this->onMousePressed($event);
    }
  }

  public function mouseReleased(MouseEvent $event) {
    if($this->onMouseReleased != null) {
      $this->onMouseReleased($event);
    }
  }
}