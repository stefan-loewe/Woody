<?php

namespace Woody\Event;

class ActionAdapter implements ActionListener {
  /**
   * the callback to be executed for the action-performed event, if null, no callback for this event type will be
   * executed
   *
   * @var callable
   */
  private $onActionPerformed = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param callable $onMousePressed the callback to be executed for the mouse-pressed event, if null, no callback for
   * this event type will be executed
   * @param callable $onMouseReleased the callback to be executed for the mouse-released event, if null, no callback for
   * this event type will be executed
   */
  public function __construct(callable $onActionPerformed) {
    $this->onActionPerformed = $onActionPerformed;
  }

  public function actionPerformed(ActionEvent $event) {
    $this->onActionPerformed->__invoke($event);
  }
}