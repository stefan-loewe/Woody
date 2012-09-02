<?php

namespace Woody\Components\Windows;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class ToolDialog extends AbstractWindow {
  public function __construct($label, Point $point, Dimension $dimension) {
    parent::__construct(ToolDialog, $label, $point, $dimension);
  }
}