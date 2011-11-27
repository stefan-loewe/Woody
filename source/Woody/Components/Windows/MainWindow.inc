<?php

namespace Woody\Components\Windows;

use Woody\Components\Windows\AbstractWindow;
use Woody\Utils\Geom\Point;
use Woody\Utils\Geom\Dimension;

class MainWindow extends AbstractWindow
{
    public function __construct($label, Point $point, Dimension $dimension)
    {
        parent::__construct(AppWindow, $label, $point, $dimension);
    }
}