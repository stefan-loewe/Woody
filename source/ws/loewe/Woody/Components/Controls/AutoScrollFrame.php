<?php

namespace ws\loewe\Woody\Components\Controls;

use ws\loewe\Utils\Geom\Dimension;
use ws\loewe\Utils\Geom\Point;
use ws\loewe\Woody\Components\Component;
use ws\loewe\Woody\Event\ActionAdapter;

class AutoScrollFrame extends Frame  {

  private $scrollPane;

  private $scrollBar;

  /**
   * the extra outset, so that the scroll pane is outside the actual frame, to
   * hide the border around the scroll pane
   *
   * @var int
   */
  private static $SCROLL_PANE_EXTRA_OUTSET  = 10;

  /**
   * the width and height used for the scroll pane
   *
   * @var int
   */
  private static $SCROLL_PANE_DIMENSION     = 32767;

  /**
   * This method acts as the constructor of the class.
   *
   * @param string $label the label of the frame
   * @param Point $topLeftCorner the top left corner of the frame
   * @param Dimension $dimension the dimension of the frame, denoting the height of the bounding box - including the
   * border but without the excess vertical space used by the (optional) label of the frame
   * @param int $tabIndex the index of the tab page where to add the frame, only needed when adding frames to tabs
   *
   */
  public function __construct($label, Point $topLeftCorner, Dimension $dimension, $tabIndex = null) {
    parent::__construct($label, $topLeftCorner, $dimension, $tabIndex);
  }

  protected function create(Component $parent) {
    parent::create($parent);

    wb_set_handler($this->controlID, 'globalWinBinderEventHandler');

    $this->addToRootFrame($this->scrollPane = new Frame(null,
      Point::createInstance(-self::$SCROLL_PANE_EXTRA_OUTSET, -self::$SCROLL_PANE_EXTRA_OUTSET),
      Dimension::createInstance(self::$SCROLL_PANE_DIMENSION + -self::$SCROLL_PANE_EXTRA_OUTSET, self::$SCROLL_PANE_DIMENSION)));

    $this->addToRootFrame($this->scrollBar  = new ScrollBar(Point::createInstance($this->dimension->width - 25, 10),
      Dimension::createInstance(18, $this->dimension->height - 35)));

    $SCROLL_PANE_EXTRA_OUTSET = self::$SCROLL_PANE_EXTRA_OUTSET;
    $this->scrollBar->addActionListener(new ActionAdapter(function($event) use ($SCROLL_PANE_EXTRA_OUTSET) {
      $actualDimension = $this->getActualVerticalDimensionOfFrame();

      $overflow     = $actualDimension - $this->dimension->height;
      $stepping     = $overflow / 100;
      $scrollOffset = ($stepping * $event->getSource()->getOffset()) * (-1);

      $this->scrollPane->moveTo(Point::createInstance(-$SCROLL_PANE_EXTRA_OUTSET, -$SCROLL_PANE_EXTRA_OUTSET + $scrollOffset));

      // to avoid redraw artifacts
      $this->hide()->show();
    }));

    $this->scrollBar->hide();
  }

  public function add(Component $control) {
    $this->addToScrollPane($control);
  }

  /**
   * This method adds controls to the actual frame.
   *
   * Only the scroll pane and scrollbar should be added to the actual frame.
   *
   * @param Component $control the control to add
   */
  private function addToRootFrame(Component $control) {
    parent::add($control);
  }

  /**
   * This method adds controls to the scroll pane.
   *
   * All controls other than the scroll pane and scrollbar should be added to the scroll pane.
   *
   * @param Component $control the control to add
   */
  private function addToScrollPane(Component $control) {
    $control->create($this->scrollPane);

    $control->moveBy(Dimension::createInstance(self::$SCROLL_PANE_EXTRA_OUTSET, self::$SCROLL_PANE_EXTRA_OUTSET + 5));
    $this->scrollPane->children[$control] = $control->controlID;

    if($this->overflows()) {
      $this->scrollBar->show();
    } else {
      $this->scrollBar->hide();
    }

    return $this;
  }

  public function remove(Component $control) {
    $this->scrollPane->remove($control);
  }

  /**
   * This method determines, if the components contained in the frame take up
   * more vertical space then it is available in the frame.
   *
   * @return boolean true, if the components contained in the frame take up
   * more vertical space then it is available in the frame, else false
   */
  private function overflows() {
    $maxYOffset = 0;
    foreach($this->scrollPane->children as $child) {
      $maxYOffset = max($maxYOffset, $child->getDimension()->height + $child->getPosition()->y);
    }

    return $maxYOffset > $this->getDimension()->height;
  }

  /**
   * This methood determines the vertical space taken up by the components contained in the frame.
   *
   * @return int
   */
  private function getActualVerticalDimensionOfFrame() {
    $maxYOffset = 0;
    foreach($this->scrollPane->children as $child) {
      $maxYOffset = max($maxYOffset, $child->getPosition()->y + $child->getDimension()->height);
    }

    return $maxYOffset +  self::$SCROLL_PANE_EXTRA_OUTSET;
  }

  public function resizeBy(Dimension $dimension) {
    parent::resizeBy($dimension);

    $this->scrollPane->resizeBy($dimension);

    $this->scrollBar->resizeBy($dimension);
  }
}