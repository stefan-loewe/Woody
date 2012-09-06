<?php

namespace Woody\Event;

use \Woody\Components\Windows\MainWindow;
use \Woody\Components\Controls\EditBox;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

/**
 * Test class for Event.
 * Generated by PHPUnit on 2012-07-04 at 19:14:25.
 */
class EventTest extends \PHPUnit_Framework_TestCase {
  /**
   * @var Event the event to test
   */
  protected $event;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    $eventInfo = $this->getMockBuilder('\Woody\Event\EventInfo')
      ->disableOriginalConstructor()
      ->getMock();
    $this->event = new KeyEvent($eventInfo);
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {
  }

  /**
   * This method tests getting properties from the event.
   *
   * @covers \Woody\Event\Event::__get
   */
  public function test__get() {
    $this->assertEquals(0, $this->event->windowID);
  }

  /**
   * This method tests getting the source of the event.
   *
   * @covers \Woody\Event\Event::getSource
   */
  public function testGetSource() {
    $window   = new MainWindow('MainWindow', new Point(50, 50), new Dimension(300, 200));
    $control  = new EditBox('', new Point(20, 20), new Dimension(100, 18));
    $window->create()->getRootPane()->add($control);

    $event = new KeyEvent(new EventInfo($window->getID(), $control->getID(), $control, 0, 0));

    $this->assertEquals($control, $event->getSource());

    $window->close();
  }

  /**
   * This method tests determining if the alt key is pressed.
   *
   * @covers \Woody\Event\Event::isAltKeyPressed
   */
  public function testIsAltKeyPressed() {
    $this->assertFalse($this->event->isAltKeyPressed());
  }

  /**
   * This method tests determining if the control key is pressed.
   *
   * @covers \Woody\Event\Event::isCtrlKeyPressed
   */
  public function testIsCtrlKeyPressed() {
    $this->assertFalse($this->event->isCtrlKeyPressed());
  }

  /**
   * This method tests determining if the shift key is pressed.
   *
   * @covers \Woody\Event\Event::isShiftKeyPressed
   */
  public function testIsShiftKeyPressed() {
    $this->assertFalse($this->event->isShiftKeyPressed());
  }

  /**
   * This method tests the string representation of the event.
   *
   * @covers \Woody\Event\Event::__toString
   */
  public function testToString() {
    $this->assertTrue(strpos($this->event->__toString(), 'windowID = ') !== FALSE);
  }
}