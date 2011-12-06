<?php

namespace Woody\Components\Controls;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

abstract class SelectBox extends ListControl {

    /**
     * This method acts as the constructor of the class.
     *
     * @param Point $topLeftCorner the top left corner of the select box
     * @param Dimension $dimension the dimension of the select box
     */
    public function __construct(Point $topLeftCorner, Dimension $dimension) {
        parent::__construct($topLeftCorner, $dimension);
    }
}