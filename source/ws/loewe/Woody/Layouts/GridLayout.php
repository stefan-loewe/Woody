<?php

namespace ws\loewe\Woody\Layouts;

use \ws\loewe\Woody\Components\Controls\Frame;
use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Geom\Dimension;

class GridLayout implements Layout {

  /**
   * the number of rows in the grid
   *
   * @var int
   */
  private $rows = 0;

  /**
   * the number of columns in the grid
   *
   * @var int
   */
  private $columns = 0;

  /**
   * the width of the horizontal gap in the grid
   *
   * @var int
   */
  private $horizontalGap = 0;

  /**
   * the height of the vertical gap in the grid
   *
   * @var int
   */
  private $verticalGap = 0;

  /**
   * This method acts as the constructor of the class.
   *
   * @param int $rows the number of rows in the grid
   * @param int $columns the number of columns in the grid
   * @param int $horizontalGap width of the horizontal gap in the grid
   * @param int $verticalGap the height of the vertical gap in the grid
   */
  public function __construct($rows, $columns, $horizontalGap = 0, $verticalGap = 0) {
    $this->rows           = $rows;
    $this->columns        = $columns;
    $this->horizontalGap  = $horizontalGap;
    $this->verticalGap    = $verticalGap;
  }

  public function layout(Frame $container) {
    $initialOffsetX   = 7;
    $initialOffsetY   = 13;

    $containerDimension = $container->getDimension();
    $componentDimension = $this->getComponentDimension($containerDimension);

    foreach($container->getComponents() as $index => $component) {
      $component->resizeTo($componentDimension);

      $xOffset = ($this->horizontalGap + $componentDimension->width) * ($index % $this->columns);
      $yOffset = ($this->verticalGap + $componentDimension->height) * floor(($index / $this->columns));

      $component->moveTo(new Point($initialOffsetX + $xOffset, $initialOffsetY + $yOffset));
    }
  }

  /**
   * This method computes the dimension to be used for each component in the container to be layouted.
   *
   * @param Dimension $containerDimension the dimension of the container
   * @return ws\loewe\Utils\Geom\Dimension the dimension to be used for each component in the container to be layouted.
   */
  private function getComponentDimension(Dimension $containerDimension) {
    $frameInsetX  = 10;
    $frameInsetY  = 18;

    $containerInnerWidth  = $containerDimension->width - $frameInsetX;
    $containerInnerWidth  = $containerInnerWidth - (($this->columns - 1) * $this->horizontalGap);
    $widthPerComponent    = $containerInnerWidth / $this->columns;

    $containerInnerHeight = $containerDimension->height - $frameInsetY;
    $containerInnerHeight = $containerInnerHeight - (($this->rows - 1) * $this->verticalGap);
    $heightPerComponent   = $containerInnerHeight / $this->rows;

    return new Dimension(intval($widthPerComponent), intval($heightPerComponent));
  }
}