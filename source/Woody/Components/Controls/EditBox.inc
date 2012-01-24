<?php

namespace Woody\Components\Controls;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class EditBox extends EditField {
    public $actionListeners = array();
    /**
     * This method acts as the constructor of the class.
     *
     * @param mixed $value the preset value of the edit box
     * @param Point $topLeftCorner the top left corner of the edit box
     * @param Dimension $dimension the dimension of the edit box
     */
    public function __construct($value, Point $topLeftCorner, Dimension $dimension) {
        parent::__construct($value, $topLeftCorner, $dimension);
    }

    public function addActionListener(\Woody\Event\ActionListener $actionListener) {
        //echo PHP_EOL.'hey, you triggered an action on the field with the value '.$this->value;
        // implements \Woody\Event\ActionListener
        echo PHP_EOL.'addedActionListener';

        $this->actionListeners[] = $actionListener;
    }
}