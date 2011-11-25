<?php

namespace Woody\Components\Controls;

use \Woody\Utils\Geom\Point;
use \Woody\Utils\Geom\Dimension;

/**
 * @todo maybe interfaces should use \DateTime class
 */
class Calendar extends Control
{
    /**
     * This method acts as the constructor of the class.
     *
     * The date of the calendar is always the current date. In order to set a new date, you need to call setTimestamp after having added the calendar to its container control.
     *
     * @param Point $topLeftCorner the top left corner of the calendar
     * @param Dimension $dimension the dimension of the calendar
     */
    public function __construct(Point $topLeftCorner, Dimension $dimension)
    {
        parent::__construct(null, $topLeftCorner, $dimension);

        $this->type = Calendar;
    }

    /**
     * This method returns the value of the edit field.
     *
     * @param boolean $trimmed determines whether or not to returned the trimmed value
     * @return string the value of the edit field
     */
    public function getTimestamp()
    {
        // wb_get_value($this->controlID) returns wrong dates, e.g. on 2010_11_ 01, maybe because of DST !?!?
        //return wb_get_value($this->controlID);

        $data = pack('v8', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ');

        // 0x1001 = MCM_GETCURSEL
        wb_send_message($this->controlID, 0x1001, 0, wb_get_address($data));

        $date = unpack('syear/smonth/swDayOfWeek/sday/swHour/swMinute/swSecond/swMilliseconds', $data);

        return mktime(0, 0, 0, $date['month'], $date['day'], $date['year']);
    }

    /**
     * This method sets the timestamp of the calendar.
     *
     * @param int $timestamp
     */
    public function setTimestamp($timestamp)
    {
        wb_set_value($this->controlID, $timestamp);
    }
}