<?php

namespace Woody\Components\Windows;

use \Woody\Components\Component;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;
use \Woody\System\WindowConstraints;
use \Woody\Event\WindowResizeListener;
use \Woody\Components\Controls\Frame;
use \Woody\Event\WindowCloseListener;

abstract class AbstractWindow extends Component {

  /**
   * the close listener registered for this window
   *
   * @var \Woody\Event\WindowCloseListener
   */
  protected $closeListener        = null;

  /**
   * the collection of resize listeners registered for this window
   *
   * @var \SplObjectStorage
   */
  protected $resizeListeners      = null;

  /**
   * the root pane of the window
   *
   * @var Woody\Components\Controls\Frame
   */
  protected $rootPane             = null;

  public function __construct($type, $label, Point $topLeftCorner, Dimension $dimension) {
    parent::__construct($label, $topLeftCorner, $dimension);

    $this->type   = $type;

    $this->parent = null;
  }

  public function create(Component $parent = null) {
    $this->controlID = wb_create_window(
      $parent === null ? null : $parent->getControlID(),
      $this->type,
      $this->value,
      $this->topLeftCorner->x,
      $this->topLeftCorner->y,
      $this->dimension->width,
      $this->dimension->height,
      $this->style | WBC_TASKBAR | WBC_NOTIFY,
      $this->getParameters()
    );

    static::$components[$this->controlID] = $this;

    $this->createRootPane();

    return $this;
  }

  /**
   * This method creates the root pane for this window. All other controls have to be added to this frame.
   */
  private function createRootPane() {
    $rootPaneTopLeftCorner  = new Point(-1, -8);
    $rootPaneDimension      = new Dimension($this->dimension->width, $this->dimension->height);

    $this->rootPane = new Frame('', $rootPaneTopLeftCorner, $rootPaneDimension);
    $this->rootPane->create($this);
  }

  /**
   * This method closes the window.
   *
   * @return AbstractWindow $this
   */
  public function close() {
    $this->destroy();

    return $this;
  }

  /**
   * This method destroys the window.
   *
   * @return AbstractWindow $this
   */
  private function destroy() {
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
    parent::resize(WindowConstraints::getInstance()->enforceConstraints($dimension));

    $this->rootPane->resizeTo($dimension);

    return $this;
  }

  /**
   * This method returns the root pane of this window.
   *
   * @return Frame the root pane of this window
   */
  public function getRootPane() {
    return $this->rootPane;
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

  /**
   * This method sets the close listener for this window, which can be used to do some clean-up duties before destroying
   * the window.
   *
   * @param WindowCloseListener $closeListener the resize listener to add
   * @return AbstractWindow $this
   */
  public function setWindowCloseListener(WindowCloseListener $closeListener) {
    $this->closeListener = $closeListener;

    return $this;
  }

  /**
   * This method returns the current close listener for this window.
   *
   * @return WindowCloseListener
   */
  public function getWindowCloseListener() {
    return $this->closeListener;
  }

  /**
   * This method removes the close listener for this window.
   *
   * @return AbstractWindow $this
   */
  public function removeWindowCloseListener() {
    $this->closeListener = null;

    return $this;
  }

  /**
   * This method adds a resize listener to this window.
   *
   * @param WindowResizeListener $resizeListener the resize listener to add
   * @return AbstractWindow $this
   */
  public function addWindowResizeListener(WindowResizeListener $resizeListener) {
    if($this->resizeListeners == null) {
      $this->resizeListeners = new \SplObjectStorage();
    }

    $this->resizeListeners->attach($resizeListener);

    return $this;
  }

  /**
   * This method returns the collection of resize listeners registered for this window.
   *
   * @return \SplObjectStorage the collection of resize listeners registered for this window
   */
  public function getWindowResizeListeners() {
    return ($this->resizeListeners == null) ? new \SplObjectStorage() : $this->resizeListeners;
  }

  /**
   * This method removes a resize listener from this window.
   *
   * @param ResizeListener $resizeListener the resize listener to remove
   * @return AbstractWindow $this
   */
  public function removeWindowResizeListener(WindowResizeListener $resizeListener) {
    if($this->resizeListeners != null) {
      $this->resizeListeners->detach($resizeListener);
    }

    return $this;
  }
}