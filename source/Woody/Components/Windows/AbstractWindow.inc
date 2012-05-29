<?php

namespace Woody\Components\Windows;

use \Woody\Components\Component;
use \Woody\Components\Container;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;
use \Woody\System\WindowConstraints;

abstract class AbstractWindow extends Component implements Container {
  /**
   * the child controls of this window
   *
   * @var SplObjectStorage
   */
  protected $children = null;

  /*
    const CENTERED              = WBC_CENTER;

    const TITLE_BAR_HEIGHT      = 30;

    const BORDER_WIDTH          = 2;
   */

  public function __construct($type, $label, Point $topLeftCorner, Dimension $dimension) {
    parent::__construct($label, $topLeftCorner, $dimension);

    //todo: type via decorators???
    $this->type = $type;

    $this->parent = null;

    // set the default event handler for closing the window
    //$this->setEventHandler(self::getDefaultCallback());
  }

  public function create(Component $parent = null) {
    $this->controlID = wb_create_window(
      $this->parent === null ? null : $this->parent->getControlID(), $this->type, $this->value, $this->topLeftCorner->x,
      $this->topLeftCorner->y, $this->dimension->width, $this->dimension->height,
      $this->style | WBC_TASKBAR | WBC_NOTIFY, $this->getParameters()
    );

    static::$components[$this->controlID] = $this;

    $this->children = new \SplObjectStorage();

    return $this;
  }

  /**
   * This method destroys a window.
   *
   * @return AbstractWindow $this
   */
  public function destroy() {
    wb_destroy_window($this->controlID);

    return $this;
  }

  /**
   * This method starts the main loop of the window.
   *
   * @return AbstractWindow $this
   */
  public function startEventHandler() {
    wb_set_handler($this->controlID, 'globalWinBinderEventHandler');

    wb_main_loop();

    return $this;
  }

  /**
   * @inheritDoc
   *
   * The resizing will adhere to some form of sanatizing, as windows widths/heights of e.g. "0" are not possible.
   */
  protected function resize(Dimension $dimension) {
    return parent::resize(WindowConstraints::getInstance()->enforceConstraints($dimension));
  }

  /**
   * This method adds a control to the window. Calling this method twice with the same object behaves like moving the
   * control from one parent to the other.
   *
   * @param Component $control
   * @return Frame
   */
  public function add(Component $control) {
    if($control->parent !== null) {
      $control->parent->remove($control);
    }

    $control->create($this);

    $this->children[$control] = $control->controlID;

    return $this;
  }

  public function remove(Component $control) {
    if($this->children->offsetExists($control)) {
      $this->children->offsetUnset($control);

      // removing implies destroying the winbinder resource here
      wb_destroy_control($control->controlID);

      $control->controlID = null;
      $control->parent = null;
    }

    return $this;
  }

  /**
   * This method is a helper for getting the event parameters
   *
   * @return int
   */
  private function getParameters() {
    return $this->param
      | WBC_MOUSEDOWN
      | WBC_MOUSEUP
      | WBC_DBLCLICK
      /* | WBC_MOUSEMOVE */
      | WBC_KEYDOWN
      | WBC_KEYUP
      | WBC_GETFOCUS
      | WBC_CUSTOMDRAW
      | WBC_REDRAW
      | WBC_RESIZE
      | WBC_HEADERSEL;
  }
}