<?php

namespace Woody\Components\Controls;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class ComboBox extends ListControl {

    /**
     * This method acts as the constructor of the class.
     *
     * @param Point $topLeftCorner the top left corner of the combobox
     * @param Dimension $dimension the dimension of the combobox
     */
    public function __construct(Point $topLeftCorner, Dimension $dimension) {
        parent::__construct($topLeftCorner, $dimension);

        $this->type = ComboBox;
    }
}