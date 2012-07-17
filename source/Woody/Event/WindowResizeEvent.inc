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
   * @param int $windowID the winbinder identifier for the control or origin
   * @param int $id the identifier for the control or origin
   * @param int $controlID the winbinder identifier for the control or origin
   * @param int $type the winbinder identifier for the type of event
   * @param int $property the winbinder identifier for properties of the event
   * @param Dimension $oldDim the old dimension of the component before resizing
   * @param Dimension $newDim the new dimension of the component after resizing
   */
  public function __construct($windowID, $id, $controlID, $type, $property, Dimension $oldDim, Dimension $newDim) {
    parent::__construct($windowID, $id, $controlID, $type, $property);

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
    return new Dimension(
      $this->newDimension->width - $this->oldDimension->width,
      $this->newDimension->height - $this->oldDimension->height);
  }
}