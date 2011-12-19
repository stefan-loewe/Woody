<?php

namespace Woody\Components\Controls;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class PushButton extends Control {
    /**
     * This method acts as the constructor of the class.
     *
     * @param string $label the label of the push button
     * @param Point $topLeftCorner the top left corner of the push button
     * @param Dimension $dimension the dimension of the push button
     */
    public function __construct($label, Point $topLeftCorner, Dimension $dimension) {
        parent::__construct($label, $topLeftCorner, $dimension);

        $this->type = PushButton;
    }

    public function getLabel() {
        return wb_get_text($this->controlID);
    }

    public function setLabel($label) {
        return wb_set_text($this->controlID, $label);
    }
}