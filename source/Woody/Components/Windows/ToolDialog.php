<?php

namespace ws\loewe\Woody\Components\Windows;

use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Geom\Dimension;

class ToolDialog extends AbstractWindow {
  public function __construct($label, Point $point, Dimension $dimension) {
    parent::__construct(ToolDialog, $label, $point, $dimension);
  }
}