<?php

namespace ws\loewe\Woody\Components\Controls;

use \ws\loewe\Woody\Components\Component;
use \ws\loewe\Woody\Components\Container;
use \ws\loewe\Woody\Layouts\GridLayout;
use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Geom\Dimension;

class Frame extends Control implements Container {
  /**
   * the child controls of this frame
   *
   * @var \SplObjectStorage
   */
  protected $children = null;

  /**
   * the layout of frame
   *
   * @var \ws\loewe\Woody\Layouts\GridLayout
   */
  protected $layout = null;
  
  /**
   * the tab index of the frame, needed for adding the frame to a page of tab control
   *
   * @var int
   */
  protected $tabIndex = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param string $label the label of the frame
   * @param Point $topLeftCorner the top left corner of the frame
   * @param Dimension $dimension the dimension of the frame, denoting the height of the bounding box - including the
   *  border but without the excess vertical space used by the (optional) label of the frame
   */
  public function __construct($label, Point $topLeftCorner, Dimension $dimension, $tabIndex = null) {
    // the frame has to be increased by 8 pixels in height, to get the user specified height
    // these 8 pixels are subtracted by the Windows API when creating frames, as they are
    // reserved for the (optional) label
    parent::__construct($label, $topLeftCorner, $dimension->resizeBy(new Dimension(0, 8)));

    $this->type     = Frame;
    $this->tabIndex = $tabIndex;
    $this->children = new \SplObjectStorage();
  }

  protected function create(Component $parent) {
    parent::create($parent);

    wb_set_handler($this->controlID, 'globalWinBinderEventHandler');
  }

  /**
   * This method adds a control to the frame. Calling this method twice with the same object behaves like moving the
   * control from one parent to the other.
   *
   * The controls are only added logically to the frame, physically, they are added to the window to which the frame
   * belongs.
   * Furthermore, creating of the control (in respect to winbinder) is done only here, and not in the constructor, as
   * the parent element has to be known.
   *
   * @param Component $control
   * @return Frame
   */
  public function add(Component $control) {
    if($control->parent !== null) {
      $this->remove($control);
    }

    $control->create($this);

    $this->children[$control] = $control->controlID;

    return $this;
  }

  public function getComponents() {
    $components = new \SplFixedArray($this->children->count());

    foreach($this->children as $index => $child) {
      $components[$index] = $child;
    }

    return $components;
  }

  /**
   * This method removes a control from the parent.
   *
   * @param Component $control
   */
  public function remove(Component $control) {
    if($this->children->offsetExists($control)) {
      $this->children->offsetUnset($control);

      wb_destroy_control($control->controlID);

      $control->controlID = null;
      $control->parent = null;
    }

    return $this;
  }

  /**
   * This method returns the layout of the frame.
   *
   * @return \ws\loewe\Woody\Layouts\GridLayout the layout of the frame
   */
  public function getLayout() {
    return $this->layout;
  }

  /**
   * This method sets the layout of the frame.
   *
   * @param \ws\loewe\Woody\Layouts\GridLayout $layout the layout to set
   */
  public function setLayout(GridLayout $layout) {
    $this->layout = $layout;
  }
}