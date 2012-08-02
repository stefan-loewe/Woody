<?php

namespace Woody\Layouts;

use \Woody\Components\Container;
use \Woody\Components\Controls\Checkbox;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class GridLayout {

  private $rows = 0;

  private $columns = 0;

  private $horizontalGap = 0;

  private $verticalGap = 0;

  public function __construct($rows, $columns, $horizontalGap = 0, $verticalGap = 0) {
    $this->rows           = $rows;
    $this->columns        = $columns;
    $this->horizontalGap  = $horizontalGap;
    $this->verticalGap    = $verticalGap;
  }

  public function layout(Container $container) {
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

  private function getComponentDimension($containerDimension) {
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