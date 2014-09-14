<?php

namespace ws\loewe\Woody\Components\Controls;

use ArrayObject;
use SplFixedArray;
use ws\loewe\Utils\Geom\Dimension;
use ws\loewe\Utils\Geom\Point;

class Tab extends Control {
  /**
   * the collection of pages associated with each winbinder tab page
   *
   * @var ArrayObject
   */
  private $pages;

  /**
   *
   * @var boolean determines, whether the pages get vertical scrollbars once
   * they overflow
   */
  private $autoscroll;

  /**
   * This method acts as the constructor of the class.
   *
   * @param Point $topLeftCorner the top left corner of the edit box
   * @param Dimension $dimension the dimension of the edit box
   */
  public function __construct(Point $topLeftCorner, Dimension $dimension, $autoscroll = false) {
    parent::__construct(null, $topLeftCorner, $dimension);

    $this->type       = TabControl;
    $this->pages      = new ArrayObject();
    $this->autoscroll = $autoscroll;
  }

  /**
   * This method adds a new tab page to the tab control.
   *
   * @param string $header the name of the tab page
   */
  public function addTabPage($header) {
    // create a winbinder tab page item
    wb_create_items($this->controlID, $header);

    if($this->autoscroll) {
      $this->pages[$header] = new AutoScrollFrame(
        null,
        Point::createInstance(-2, -9),
        Dimension::createInstance($this->dimension->width, $this->dimension->height),
        $this->pages->count());
    }

    else {
      $this->pages[$header] = new Frame(
        null,
        Point::createInstance(-2, -9),
        Dimension::createInstance($this->dimension->width, $this->dimension->height),
        $this->pages->count());
    }
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

  /**
   * This method returns a new collection of the tab pages of the control.
   *
   * @return Frame[]
   */
  public function getTabPages() {
    $pages = new SplFixedArray($this->pages->count());

    $index = 0;
    foreach($this->pages as $page) {
      $pages[$index++] = $page;
    }

    return $pages;
  }

  /**
   * This method sets the focus on the page with the given header.
   *
   * @param string $header the name of the tab page to be focused.
   */
  public function setFocus($header) {
    if(!$this->pages->offsetExists($header)) {
      return;
    }

    $index = 0;
    foreach($this->pages as $title => $frame) {
      if($title == $header) {
        wb_set_selected($this->getControlID(), $index);
        return;
      }

      $index++;
    }
  }
}