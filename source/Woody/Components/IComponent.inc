<?php

namespace Woody\Components;

use \Woody\Components\Windows\AbstractWindow;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;
use \Woody\System\System;

interface IComponent {

  /**
   * This method returns the winbinder control id of the component.
   *
   * @return int
   */
  public function getControlID();

  /**
   * This method returns the id of the component.
   *
   * @return int
   */
  public function getID();

  /**
   * This method returns the top left corner of the component.
   *
   * @return Woody\Utils\Geom\Point the top left corner of the component.
   */
  public function getPosition();

  /**
   * This method returns the dimension of the top left corner of the component.
   *
   * @return Woody\Utils\Geom\Dimension the dimension of the control
   */
  public function getDimension();

  /**
   * This method moves the component by an offset, given as dimension.
   *
   * @param \Utils\Geom\Dimension $dimension the dimension by which this point shall be moved by
   * @return Component $this
   */
  public function moveBy(Dimension $dimension);

  /**
   * This method moves the top left corner of the component to the given point.
   *
   * @param Point $topLeftCorner the new point of the top left corner
   * @return Component $this
   */
  public function moveTo(Point $topLeftCorner);

  /**
   * This method resizes the component by the offset given as a dimension.
   *
   * @param Dimension $dimension the offset by which the component has to be resized
   * @return Component $this
   */
  public function resizeBy(Dimension $dimension);

  /**
   * This method resizes the component to the given dimension.
   *
   * @param Dimension $dimension the new dimension of the component.
   * @return Component $this
   */
  public function resizeTo(Dimension $dimension);
}