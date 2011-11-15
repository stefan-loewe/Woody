<?php

namespace Woody\Components\Controls;

use Woody\Utils\Geom\Point;
use Woody\Utils\Geom\Dimension;

class EditBox extends Control
{
    const NUMBERS_ONLY = WBC_NUMBER;

    public function __construct($value, Point $topLeftCorner, Dimension $dimension/*$style = Control::DEFAULT_STYLE, $param = 0, $tabIndex = 0*/)
    {
        parent::__construct($value, $topLeftCorner, $dimension);

        $this->type = EditBox;
    }

    public function getValue($trimmed = TRUE)
    {
        $value = wb_get_text($this->controlID);

        if($trimmed)
            $value = trim($value);

        if(strlen($value) === 0)
            $value = null;

        return $value;
    }

    public function setValue($newValue)
    {
        //$this->pauseEvents = TRUE;

        wb_set_text($this->controlID, $newValue);

        //$this->pauseEvents = FALSE;

        return $this;
    }

    public function setReadOnly($isReadonly)
    {
        // still in doubt about the last parameter !?!? 1 seems to work
        wb_send_message($this->controlID, 0x00CF, $isReadonly, 1);
    }
}