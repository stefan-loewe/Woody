<?php

namespace Woody\Layouts;

use \Woody\Components\Container;
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
    // for frames
    $containerInsetX = 4;
    $containerInsetY = 8;
    
    if($container instanceof \Woody\Components\Windows\AbstractWindow) {
      $containerInsetX = 10;
      $containerInsetY = 30;
    }

    $containerDimension = $container->getDimension();
    $componentDimension = new Dimension(
            intval(($containerDimension->width - $containerInsetX - ($this->columns - 1) * $this->horizontalGap) / $this->columns),
            intval(($containerDimension->height - $containerInsetY - ($this->rows - 1) * $this->verticalGap) / $this->rows));
    
    $initialOffsetX = 0;
    $initialOffsetY = 0;
    if($container instanceof \Woody\Components\Controls\Frame) {
      $initialOffsetX = 7;
      $initialOffsetY = 13;
    }
    foreach($container->getComponents() as $index => $component) {
      $component->resizeTo($componentDimension);
      
      $xOffset = $initialOffsetX + ($this->horizontalGap + $componentDimension->width) * ($index % $this->columns);
      $yOffset = $initialOffsetY + ($this->verticalGap + $componentDimension->height) * floor(($index / $this->columns));
      
      $component->moveTo(new \Utils\Geom\Point($xOffset, $yOffset));      
    }
  }
}