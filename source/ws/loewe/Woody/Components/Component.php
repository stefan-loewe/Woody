<?php

namespace ws\loewe\Woody\Components;

use \ws\loewe\Woody\Event\ActionListener;
use \ws\loewe\Woody\Event\FocusListener;
use \ws\loewe\Woody\Event\KeyListener;
use \ws\loewe\Woody\Event\MouseListener;
use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Geom\Dimension;

/**
 * This class defines the basic frame for any component of a graphical user interface.
 *
 * @author stefan.loewe.ws
 */
abstract class Component implements IComponent {
  /**
   * the internal winbinder control id of this component
   *
   * @var int
   */
  protected $controlID = null;

  /**
   * the logical id of this component, i.e the woody id
   *
   * @var int
   */
  protected $id = null;

  /**
   * the type of the component, i.e. the winbinder constant representing the control type
   *
   * @var int
   */
  protected $type = null;

  /**
   * the top left corner of the component
   *
   * @var ws\loewe\Utils\Geom\Point
   */
  protected $topLeftCorner = null;

  /**
   * the dimension of the component
   *
   * @var ws\loewe\Utils\Geom\Dimension
   */
  protected $dimension = null;

  /**
   * the value (e.g. value of an EditBox, the label of a Frame, the selection of a ListBox) of the control
   *
   * @var string
   */
  protected $value = null;

  /**
   * the parent container of this window
   *
   * @var Container
   */
  protected $parent = null;

  /**
   * the collection of action listeners registered for the component
   *
   * @var \SplObjectStorage
   */
  private $actionListeners = null;

  /**
   * the collection of mouse listeners registered for the component
   *
   * @var \SplObjectStorage
   */
  private $mouseListeners = null;

  /**
   * the collection of action listeners registered for the component
   *
   * @var \SplObjectStorage
   */
  private $keyListeners = null;

  /**
   * the collection of focus listeners registered for the component
   *
   * @var \SplObjectStorage
   */
  private $focusListeners = null;

  // @TODO handle param and style with constructor injection, subclassing or decorator pattern?
  protected $style = 0;
  protected $param = 0;

  protected static $components = array();

  /**
   * static internal integer, that is used as id generator for creating components, starting at 1024 not to interfer
   * with special control ids (e.g. 8 is window close)
   *
   * @var int
   */
  private static $IDENTIFIER = 1024;

  public function __construct($value, Point $topLeftCorner, Dimension $dimension) {
    $this->id = self::getUniqueID();

    $this->value = $value;

    $this->topLeftCorner = new Point($topLeftCorner->x, $topLeftCorner->y);

    $this->dimension = new Dimension($dimension->width, $dimension->height);
  }

  /**
   * This method return an unique identifier for each control
   *
   * The fact that this method is public is an implementation detail - the Timer class needs this functionality, too.
   * Do not call this method yourself.
   *
   * @return int
   */
  public static function getUniqueID() {
    return ++self::$IDENTIFIER;
  }

  /**
   * This method returns the component having the given winbinder id.
   *
   * Normally, it should not be neccassary to call this method, but rather manage references to the respective controls
   * within the dialog class. This is currently only needed internally for event handling. Note that a component has to
   * be created first (@see Component::create) to be returned by this method.
   *
   * @param int $id the winbinder id of the component
   * @return Component the component with the respective winbinder id
   */
  public static function getComponentByID($id) {
    return self::$components[$id];
  }

  /**
   * This method creates a component physically as a winbinder control.
   *
   * Do not call this method manually, it is called by ws\loewe\Woody\Components\Container::add only. It is an implementation
   * side-effect that it is public.
   *
   * @param Component $parent the parent component of this component
   * @return $this
   */
  abstract protected function create(Component $parent);

  public function getControlID() {
    return $this->controlID;
  }

  public function getID() {
    return $this->id;
  }

  public function getParent() {
    return $this->parent;
  }

  public function getPosition() {
    return new Point($this->topLeftCorner->x, $this->topLeftCorner->y);
  }

  public function getDimension() {
    return new Dimension($this->dimension->width, $this->dimension->height);
  }

  public function moveBy(Dimension $dimension) {
    return $this->move($this->topLeftCorner->moveBy($dimension));
  }

  public function moveTo(Point $topLeftCorner) {
    return $this->move($topLeftCorner);
  }

  /**
   * This method moves the top left corner of the component to the given point, respecting the bounds of the screen.
   *
   * @param Point $topLeftCorner
   * @return Component $this
   */
  protected function move(Point $topLeftCorner) {
    wb_set_position($this->controlID, $topLeftCorner->x, $topLeftCorner->y);

    $this->topLeftCorner = new Point($topLeftCorner->x, $topLeftCorner->y);

    return $this;
  }

  public function resizeBy(Dimension $dimension) {
    return $this->resize($this->dimension->resizeBy($dimension));
  }

  public function resizeTo(Dimension $dimension) {
    return $this->resize($dimension);
  }

  /**
   * This method resizes the component to the given dimension, respecting the minimal size of a window.
   *
   * @param Dimension $dimension the new dimension of the component.
   * @return Component $this
   */
  protected function resize(Dimension $dimension) {
    $width = $dimension->width;
    $height = $dimension->height;

    wb_set_size($this->controlID, $width, $height);

    $this->dimension = new Dimension($width, $height);

    return $this;
  }

  /**
   * This method adds an action listener to the component.
   *
   * @param ActionListener $actionListener the action listener to add
   * @return Component $this
   */
  public function addActionListener(ActionListener $actionListener) {
    if($this->actionListeners == null) {
      $this->actionListeners = new \SplObjectStorage();
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

  /**
   * This method adds a focus listener to the component.
   *
   * @param FocusListener $focusListener the focus listener to add
   * @return Component $this
   */
  public function addFocusListener(FocusListener $focusListener) {
    if($this->focusListeners == null) {
      $this->focusListeners = new \SplObjectStorage();
    }

    $this->focusListeners->attach($focusListener);

    return $this;
  }

  /**
   * This method returns the collection of focus listeners registered for the component.
   *
   * @return \SplObjectStorage the collection of focus listeners registered for the component
   */
  public function getFocusListeners() {
    return ($this->focusListeners == null) ? new \SplObjectStorage() : $this->focusListeners;
  }

  /**
   * This method removes a focus listener from the component.
   *
   * @param FocusListener $focusListener the focus listener to remove
   * @return Component $this
   */
  public function removeFocusListener(FocusListener $focusListener) {
    if($this->focusListeners != null) {
      $this->focusListeners->detach($focusListener);
    }

    return $this;
  }

  /**
   * This method adds a key listener to the component.
   *
   * @param KeyListener $keyListener the key listener to add
   * @return Component $this
   */
  public function addKeyListener(KeyListener $keyListener) {
    if($this->keyListeners == null) {
      $this->keyListeners = new \SplObjectStorage();
    }

    $this->keyListeners->attach($keyListener);

    return $this;
  }

  /**
   * This method returns the collection of key listeners registered for the component.
   *
   * @return \SplObjectStorage the collection of key listeners registered for the component
   */
  public function getKeyListeners() {
    return ($this->keyListeners == null) ? new \SplObjectStorage() : $this->keyListeners;
  }

  /**
   * This method removes a key listener from the component.
   *
   * @param KeyListener $keyListener the key listener to remove
   * @return Component $this
   */
  public function removeKeyListener(KeyListener $keyListener) {
    if($this->keyListeners != null) {
      $this->keyListeners->detach($keyListener);
    }

    return $this;
  }

  /**
   * This method adds a mouse listener to the component.
   *
   * @param MouseListener $mouseListener the mouse listener to add
   * @return Component $this
   */
  public function addMouseListener(MouseListener $mouseListener) {
    if($this->mouseListeners == null) {
      $this->mouseListeners = new \SplObjectStorage();
    }

    $this->mouseListeners->attach($mouseListener);

    return $this;
  }

  /**
   * This method returns the collection of mouse listeners registered for the component.
   *
   * @return \SplObjectStorage the collection of mouse listeners registered for the component
   */
  public function getMouseListeners() {
    return ($this->mouseListeners == null) ? new \SplObjectStorage() : $this->mouseListeners;
  }

  /**
   * This method removes a mouse listener from the component.
   *
   * @param MouseListener $mouseListener the mouse listener to remove
   * @return Component $this
   */
  public function removeMouseListener(MouseListener $mouseListener) {
    if($this->mouseListeners != null) {
      $this->mouseListeners->detach($mouseListener);
    }

    return $this;
  }

  public function refresh($now = TRUE) {
    wb_refresh($this->controlID, $now);

    return $this;
  }

  public function enable() {
    wb_set_enabled($this->controlID, TRUE);

    return $this;
  }

  public function disable() {
    wb_set_enabled($this->controlID, FALSE);

    return $this;
  }

  public function show() {
    wb_set_visible($this->controlID, TRUE);

    return $this;
  }

  public function hide() {
    wb_set_visible($this->controlID, FALSE);

    return $this;
  }
}
