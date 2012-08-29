<?php

namespace Woody\Components\Controls;

use \Woody\Components\Component;
use \Woody\Components\Container;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class Tab extends Control {
  /**
   * the collection of tab pages
   *
   * @var \ArrayObject
   */
  private $pages = null;
  /**
   * the collection of frames associated with the tab pages
   *
   * @var \ArrayObject
   */
  private $frames = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param Point $topLeftCorner the top left corner of the edit box
   * @param Dimension $dimension the dimension of the edit box
   */
  public function __construct(Point $topLeftCorner, Dimension $dimension) {
    parent::__construct(null, $topLeftCorner, $dimension);

    $this->pages  = new \ArrayObject();
    $this->frames = new \ArrayObject();
    $this->type   = TabControl;
  }

  public function addPage($title) {
    $page = wb_create_items($this->controlID, $title);

    $this->pages[$title] = $page;
    var_dump('count($this->pages) - 1 = '.(count($this->pages)));
    $f = new Frame($title, new Point(0, -7), new Dimension(200, 100), count($this->pages));
    
    $f->create($this);
    
    $this->frames[] = $f;
    
    //actually, add a Frame here for each new tag page, and add controls to the frame
  }
  
  public function getTabPage($tabPageIndex) {
    return $this->frames[$tabPageIndex];
  }
}