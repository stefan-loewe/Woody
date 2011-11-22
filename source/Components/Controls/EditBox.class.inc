<?php

namespace Woody\Components\Controls;

use \Woody\Utils\Geom\Point;
use \Woody\Utils\Geom\Dimension;

class EditBox extends Control
{
    // decorator ?!?!?!?!
    const NUMBERS_ONLY = WBC_NUMBER;

    /**
     * This method acts as the constructor of the class.
     *
     * @param mixed $value the preset value of the edit box
     * @param Point $topLeftCorner the top left corner of the edit box
     * @param Dimension $dimension the dimension of the edit box
     */
    public function __construct($value, Point $topLeftCorner, Dimension $dimension/*$style = Control::DEFAULT_STYLE, $param = 0, $tabIndex = 0*/)
    {
        parent::__construct($value, $topLeftCorner, $dimension);

        $this->type = EditBox;
    }

    /**
     * This method returns the value of the edit box.
     *
     * @param boolean $trimmed determines whether or not to returned the trimmed value
     * @return string the value of the edit box
     */
    public function getValue($trimmed = TRUE)
    {
        $value = wb_get_text($this->controlID);

        if($trimmed)
            $value = trim($value);

        if(strlen($value) === 0)
            $value = null;

        return $value;
    }

    /**
     * This method sets the value of the edit box.
     *
     * @param mixed $newValue the new value of the edit box.
     * @return \Woody\Components\Controls\EditBox $this
     */
    public function setValue($newValue)
    {
        //$this->pauseEvents = TRUE;

        wb_set_text($this->controlID, $newValue);

        //$this->pauseEvents = FALSE;

        return $this;
    }

    /**
     * This method sets the edit box to be either read-only or not.
     *
     * @param boolean $isReadonly the read-only state of the edit box
     * @return \Woody\Components\Controls\EditBox $this
     */
    public function setReadOnly($isReadonly)
    {
        // still in doubt about the last parameter !?!? 1 seems to work
        wb_send_message($this->controlID, 0x00CF, $isReadonly, 1);

        return $this;
    }
}