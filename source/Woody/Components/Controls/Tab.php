<?php

namespace ws\loewe\Woody\Components\Controls;

use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Geom\Dimension;

class Tab extends Control {
  /**
   * the collection of pages associated with each winbinder tab page
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

    $this->type   = TabControl;
    $this->pages = new \ArrayObject();
  }

  /**
   * This method adds a new tab page to the tab control.
   *
   * @param string $header the name of the tab page
   */
  public function addTabPage($header) {
    // create a winbinder tab page item
    wb_create_items($this->controlID, $header);

    $this->pages[$header] = new Frame(
      null,
      new Point(-2, -9),
      new Dimension($this->dimension->width, $this->dimension->height),
      $this->pages->count()
    );
    $this->pages[$header]->create($this);
  }

  /**
   * This method returns a handle to the tab page with the given header.
   *
   * @param string $header the name of the tab page to be returned.
   * @return Frame
   */
  public function getTabPage($header) {
    return $this->pages[$header];
  }
}