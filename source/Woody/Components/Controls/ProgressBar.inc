<?php

namespace Woody\Components\Controls;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class ProgressBar extends Control {

    /**
     * the identifier of the message for getting a value from a progress bar
     *
     * PBM_GETPOS = (WM_USER + 8) = 400 + 8 = 408
     *
     * @var int
     */
    private static $PBM_GETPOS = 0x408;

    /**
     * This method acts as the constructor of the class.
     *
     * @param Point $topLeftCorner the top left corner of the progress bar
     * @param Dimension $dimension the dimension of the progress bar
     */
    public function __construct(Point $topLeftCorner, Dimension $dimension) {
        parent::__construct('', $topLeftCorner, $dimension);

        $this->type = Gauge;
    }

    /**
     * This method gets the current value of the progress bar.
     *
     * @return int the current value of the progress bar
     */
    public function getProgress() {
        // wb_get_value always returns 0 for a gauge, so get the value with a low-level call
        return wb_send_message($this->controlID, self::$PBM_GETPOS);
    }

    /**
     * This method sets the current value of the progress bar.
     *
     * @param $value the new value of the progress bar
     * @return \Woody\Components\Controls\ProgressBar $this
     */
    public function setProgress($value) {
        wb_set_value($this->controlID, $value);

        return $this;
    }

    /**
     * This method sets the range of the progress bar.
     *
     * @param int $min the minimal value of the progress bar
     * @param int $max the maximal value of the progress bar
     * @return \Woody\Components\Controls\ProgressBar $this
     */
    public function setRange($min, $max) {
        wb_set_range($this->controlID, $min, $max);

        return $this;
    }
}