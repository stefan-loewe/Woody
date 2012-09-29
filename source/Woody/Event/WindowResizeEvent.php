<?php

namespace Woody\Event;

use \Utils\Geom\Dimension;

class WindowResizeEvent extends Event {

  /**
   * the old dimension of the component before resizing
   *
   * @var Dimension
   */
  private $oldDimension = null;

  /**
   * the new dimension of the component after resizing
   *
   * @var Dimension
   */
  private $newDimension = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param EventInfo the event info containing the raw data of this event
   */
  public function __construct(EventInfo $eventInfo) {
    parent::__construct($eventInfo);

    // the previous dimension is still stored in the Woody object
      $this->oldDimension = $this->getSource()->getDimension();

    // the winbinder resource already has the new dimension
    $newDim = wb_get_size($this->getSource()->getControlID());
    $this->newDimension = new Dimension($newDim[0], $newDim[1]);
  }

  public function dispatch() {
    foreach($this->getSource()->getWindowResizeListeners() as $resizeListener) {
      $resizeListener->windowResized($this);
    }
  }

  /**
   * This method returns the old dimension of the component before resizing
   *
   * @return Dimension
   */
  public function getOldDimension() {
    return $this->oldDimension;
  }

  /**
   * This method returns the new dimension of the component after resizing
   *
   * @return Dimension
   */
  public function getNewDimension() {
    return $this->newDimension;
  }

  /**
   * This method returns the delta of the old and new dimension, i.e. new Dimension(newX - oldX, newY - oldY)
   *
   * @return Dimension
   */
  public function getDeltaDimension() {
    return $this->newDimension->resizeBy(new Dimension(-$this->oldDimension->width, -$this->oldDimension->height));
  }
}