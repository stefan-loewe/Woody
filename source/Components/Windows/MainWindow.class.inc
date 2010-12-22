<?php

namespace Woody\Components\Windows;

use Woody\Components\Windows\AbstractWindow;
use Woody\Utils\Geom\Point;
use Woody\Utils\Geom\Dimension;

class MainWindow extends AbstractWindow
{
    public function __construct($id, $label, Point $point, Dimension $dimension, Window $parentWindow = null, $style = 0, $param = 0)
    {
        parent::__construct(AppWindow, $id, $label, $point, $dimension, $style, $param, $parentWindow);
    }
}