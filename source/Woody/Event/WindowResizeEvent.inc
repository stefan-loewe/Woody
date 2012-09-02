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
   * @param Dimension $oldDim the old dimension of the component before resizing
   * @param Dimension $newDim the new dimension of the component after resizing
   */
  public function __construct(EventInfo $eventInfo, Dimension $oldDim, Dimension $newDim) {
    parent::__construct($eventInfo);

    $this->oldDimension = $oldDim;
    $this->newDimension = $newDim;
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