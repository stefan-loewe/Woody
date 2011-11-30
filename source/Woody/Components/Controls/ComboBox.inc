<?php

namespace Woody\Components\Controls;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class ComboBox extends ListControl
{
    const DEFAULT_HEIGHT = 350;

    public function __construct(Point $topLeftCorner, Dimension $dimension)
    {
        parent::__construct($topLeftCorner, $dimension);

        $this->type = ComboBox;
    }
}