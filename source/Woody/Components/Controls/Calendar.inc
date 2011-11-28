<?php

namespace Woody\Components\Controls;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

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
     * This method returns the date of the calendar.
     *
     * @return \DateTime the date of the calendar
     */
    public function getDate()
    {
        // wb_get_value($this->controlID) returns wrong dates, e.g. on 2010_11_ 01, maybe because of DST !?!?
        //return wb_get_value($this->controlID);

        $data = pack('v8', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ');

        // 0x1001 = MCM_GETCURSEL
        wb_send_message($this->controlID, 0x1001, 0, wb_get_address($data));

        $date = unpack('syear/smonth/swDayOfWeek/sday/swHour/swMinute/swSecond/swMilliseconds', $data);

        return \DateTime::createFromFormat('Y-m-d H:i:s', $date['year'].'-'.$date['month'].'-'.$date['day'].' 00:00:00');
    }

    /**
     * This method sets the date of the calendar.
     *
     * @param \DateTime the date of the calendar
     */
    public function setDate(\DateTime $date)
    {
        $timestamp = $date->getTimestamp();

        wb_set_value($this->controlID, $timestamp);

        // One would think that here, the value of the calender is the one set right before,
        // but there seems to be a bug when calculating the offset for the day light savings time (DST).
        // However, DST is not of interest here at all, as we only talk about dates, but if you
        // set a date like e.g 2012-03-28, the day in the calendar would be 2012-03-27 (23:00:00)
        // So, if ...
        $actual = wb_get_value($this->controlID);

        // ... there is a difference between the expecte and the actual value, add the diference to the timestamp
        if(($difference = $timestamp - $actual) != 0)
            wb_set_value($this->controlID, $date->getTimestamp() + $difference);
var_dump($difference);
        //$data = pack('v8', '2012', '11', '', '28', '00', '00', '00', '00');
        //wb_send_message($this->controlID, 0x1002, 0, wb_get_address($data));
    }
}