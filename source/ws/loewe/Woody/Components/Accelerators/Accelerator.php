<?php

namespace ws\loewe\Woody\Components\Accelerators;

use SplObjectStorage;
use ws\loewe\Woody\Components\Component;
use ws\loewe\Woody\Components\Controls\Actionable;
use ws\loewe\Woody\Components\Windows\AbstractWindow;
use ws\loewe\Woody\Event\ActionListener;

class Accelerator implements Actionable {

  /**
   * the internal id of this component
   *
   * @var int
   */
  private $id;

  /**
   * the keys bound to this control, e.g. array("Alt", "F2") for the key
   * combination of "ALT+F2"
   *
   * @var array
   */
  private $keys;

  /**
   * the window to which this accelerator is bound to
   *
   * @var AbstractWindow
   */
  private $window;

  /**
   * the collection of action listeners registered for the component
   *
   * @var \SplObjectStorage
   */
  private $actionListeners = null;

  /**
   * the collection of all accelerators
   *
   * @var array[int]Accelerator
   */
  private static $accelerators = array();

  public function __construct(array $keys) {
    $this->id   = Component::getUniqueID();
    $this->keys = $keys;

    self::$accelerators[$this->id] = $this;
  }

  public function bindToWindow(AbstractWindow $window) {
    $this->window = $window;

    wb_create_control($window->getControlID(), Accel, array(
        array($this->id, implode('+', $this->keys))
    ));
  }

  /**
   * This method returns the accelerator associated with the given woody id.
   *
   * @param int $acceleratorID the woody id of the timer
   * @return Accelerator the accelerator with the given id, or null if no accelerator with this id exists
   */
  public static function getAcceleratorByID($acceleratorID) {
    if(isset(self::$accelerators[$acceleratorID])) {
      return self::$accelerators[$acceleratorID];
    }

    return null;
  }

  /**
   * This method adds an action listener to the component.
   *
   * @param ActionListener $actionListener the action listener to add
   * @return Component $this
   */
  public function addActionListener(ActionListener $actionListener) {
    if($this->actionListeners == null) {
      $this->actionListeners = new SplObjectStorage();
    }

    $this->actionListeners->attach($actionListener);

    return $this;
  }

  /**
   * This method returns the collection of action listeners registered for the component.
   *
   * @return \SplObjectStorage the collection of action listeners registered for the component
   */
  public function getActionListeners() {
    return ($this->actionListeners == null) ? new \SplObjectStorage() : $this->actionListeners;
  }

  /**
   * This method removes an action listener from the component.
   *
   * @param ActionListener $actionListener the action listener to remove
   * @return Component $this
   */
  public function removeActionListener(ActionListener $actionListener) {
    if($this->actionListeners != null) {
      $this->actionListeners->detach($actionListener);
    }

    return $this;
  }
}