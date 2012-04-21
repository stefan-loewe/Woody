<?php

namespace Woody\Components\Controls;

use \Woody\Components\Component;
use \Woody\Components\Container;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class Tab extends Control implements Container {
  /**
   * the collection of tab pages
   *
   * @var \ArrayObject
   */
  private $pages = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param Point $topLeftCorner the top left corner of the edit box
   * @param Dimension $dimension the dimension of the edit box
   */
  public function __construct(Point $topLeftCorner, Dimension $dimension) {
    parent::__construct(null, $topLeftCorner, $dimension);

    $this->pages = new \ArrayObject();
    $this->type = TabControl;
  }

  public function addPage($title) {
    $page = wb_create_items($this->controlID, $title);

    $this->pages[$title] = $page;
  }

  public function add(Component $control) {
  }

  /**
   * This method removes a control from the parent.
   *
   * @param Component $control
   */
  public function remove(Component $control) {
  }
}