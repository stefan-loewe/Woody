<?php

namespace Woody\Components\Windows;

use Woody\Components\Windows\AbstractWindow;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class MainWindow extends AbstractWindow {
  public function __construct($label, Point $point, Dimension $dimension, $isVisible = true) {
    parent::__construct(AppWindow, $label, $point, $dimension);
  }
}