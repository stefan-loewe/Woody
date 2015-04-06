<?php

namespace ws\loewe\Woody\Components\Controls;

use ArrayObject;
use LogicException;
use OutOfBoundsException;
use SplFixedArray;
use ws\loewe\Utils\Geom\Dimension;
use ws\loewe\Utils\Geom\Point;

class Tab extends Control {

  /**
   * a constant for the maximum dimensions of a tab control
   *
   * @var int
   */
  private static $MAX_SIZE = 32768;

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
   * @param string $pageId the id of the tab page
   * @param string $pageLabel the label of the tab page
   */
  public function addTabPage($pageId, $pageLabel = '') {
    if($this->hasTabPage($pageId)) {
      throw new LogicException('The tab control already contains a page with the header id '.$pageId);
    }

    // create a winbinder tab page item
    // the size set to MAX_SIZE in both dimensions, because tab pages cannot be
    // resized (WinBinder/WinApi bug), so when trying to increase the size of a
    // tab page the tab page is not getting larger
    // so we just make it really big, that it fits everything, and have to live
    // with the fact that it oveflows its parent, resulting in an ugly UI effect
    wb_set_size($this->controlID, self::$MAX_SIZE, self::$MAX_SIZE);
    wb_create_items($this->controlID, strlen($pageLabel) > 0 ? $pageLabel : $pageId);
    wb_set_size($this->controlID, $this->dimension->width, $this->dimension->height);

    if($this->autoscroll) {
      $this->pages[$pageId] = new AutoScrollFrame(
        null,
        Point::createInstance(-2, -9),
        Dimension::createInstance($this->dimension->width, $this->dimension->height),
        $this->pages->count());
    }

    else {
      $this->pages[$pageId] = new Frame(
        null,
        Point::createInstance(-2, -9),
        Dimension::createInstance($this->dimension->width, $this->dimension->height),
        $this->pages->count());
    }
    $this->pages[$pageId]->create($this);
  }

  /**
   * This method returns a handle to the tab page with the given header id.
   *
   * @param string $pageId the id of the tab page to be returned.
   * @return Frame the tab page
   */
  public function getTabPage($pageId) {
    if(!$this->hasTabPage($pageId)) {
      throw new OutOfBoundsException('The tab control does not contain a page with the header id '.$pageId);
    }

    return $this->pages[$pageId];
  }

  /**
   * This method check if the tab page with the given header id exists in this tab control.
   *
   * @param string $pageId the id of the tab page for which to perform the check
   * @return boolean true, if a tab page with the given header id exits, else false
   */
  public function hasTabPage($pageId) {
    return $this->pages->offsetExists($pageId);
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
   * This method gets the tab currently having the focus.
   *
   * @return Frame the tab page having the focus
   */
  public function getFocusedTab() {
    if($this->pages->count() === 0) {
      return null;
    }

    return $this->getTabPages()[$this->getIndexOfFocusedTab()];
  }

  /**
   * This method sets the focus on the page with the given header id.
   *
   * @param string $pageId the id of the tab page to be focused.
   */
  public function setFocus($pageId) {
    if(!$this->pages->offsetExists($pageId)) {
      throw new OutOfBoundsException('The tab control does not contain a page with the header id '.$pageId);
    }

    $index = 0;
    foreach($this->pages as $title => $frame) {
      if($title == $pageId) {
        wb_set_selected($this->getControlID(), $index);
        return;
      }

      $index++;
    }
  }

  public function focusTabByIndex($tabIndex) {
    wb_set_selected($this->getControlID(), $tabIndex);
  }

  public function focusNextTab() {
    $this->focusTab(+1);
  }

  public function focusPreviousTab() {
    $this->focusTab(-1);
  }

  private function focusTab($direction) {
    if($this->pages->count() === 0) {
      return;
    }

    $currentIndex = $this->getIndexOfFocusedTab();
    $newIndex = $currentIndex + $direction;

    if($newIndex < 0) {
      $newIndex = $this->pages->count() - 1;
    }

    else if($newIndex >= $this->pages->count()) {
      $newIndex = 0;
    }

    wb_set_selected($this->getControlID(), $newIndex);
  }

  private function getIndexOfFocusedTab() {
    return wb_get_selected($this->getControlID());
  }

  /**
   * This method resizes the tab by the given dimension.
   *
   * @param Dimension $dimension
   */
  public function resizeBy(Dimension $dimension) {
    parent::resizeBy($dimension);

    foreach($this->pages as $page) {
      $page->resizeBy($dimension);
    }
  }
}