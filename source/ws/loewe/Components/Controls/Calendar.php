<?php

namespace ws\loewe\Woody\Components\Controls;

use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Geom\Dimension;

class Calendar extends Control implements Actionable {
  /**
   * This method acts as the constructor of the class.
   *
   * The date of the calendar is always the current date. In order to set a new date, you need to call setTimestamp
   * after having added the calendar to its container control.
   *
   * @param Point $topLeftCorner the top left corner of the calendar
   * @param Dimension $dimension the dimension of the calendar
   */
  public function __construct(Point $topLeftCorner, Dimension $dimension) {
    parent::__construct(null, $topLeftCorner, $dimension);

    $this->type = Calendar;
  }

  /**
   * This method returns the date of the calendar.
   *
   * This method is more complex than it needs to be - this is due to the fact, that wb_get_value($this->controlID)
   * returns wrong dates, e.g. on 2010_11_01, maybe because of DST.
   *
   * @return \DateTime the date of the calendar
   */
  public function getDate() {

    $data = pack('v8', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ');

    // 0x1001 = MCM_GETCURSEL
    wb_send_message($this->controlID, 0x1001, 0, wb_get_address($data));

    $date       = unpack('syear/smonth/swDayOfWeek/sday/swHour/swMinute/swSecond/swMilliseconds', $data);
    $dateString = $date['year'].'-'.$date['month'].'-'.$date['day'].' 00:00:00';
    $dateTime   = \DateTime::createFromFormat('Y-m-d H:i:s', $dateString);

    return $dateTime;
  }

  /**
   * This method sets the date of the calendar.
   *
   * As with getDate, this method is also more complex than it needs to be. One would think that here, the value of the
   * calender is the one set right before, but there seems to be a bug when calculating the offset for the day light
   * savings time (DST). However, DST is not of interest here at all, as we only talk about dates, but if you set a date
   * like e.g 2012-03-28, the day in the calendar would be 2012-03-27 (23:00:00). Another option would be this:
   * <code>
   *  $data = pack('v8', '2012', '11', '', '28', '00', '00', '00', '00');
   *  wb_send_message($this->controlID, 0x1002, 0, wb_get_address($data));
   * </code>
   *
   * @param \DateTime the date of the calendar
   */
  public function setDate(\DateTime $date) {
    // get the current date's timestamp, ...
    $timestamp = $date->getTimestamp();

    // ... set it, ...
    wb_set_value($this->controlID, $timestamp);

    // ... and if the actual timestamp ...
    $actual = wb_get_value($this->controlID);

    // ... differs from the expected, add the difference to the timestamp
    if(($difference = $timestamp - $actual) != 0)
      wb_set_value($this->controlID, $date->getTimestamp() + $difference);
  }
}