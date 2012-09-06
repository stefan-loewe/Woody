<?php

namespace Woody\Event;

/**
 * Test class for EventInfo.
 * Generated by PHPUnit on 2012-09-04 at 20:12:17.
 */
class EventInfoTest extends \PHPUnit_Framework_TestCase {
  /**
   * @var EventInfo
   */
  private $eventInfo;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {
  }

  /**
   * @covers Woody\Event\EventInfo::isWindowEvent
   * @covers Woody\Event\EventInfo::__construct
   */
  public function testIsWindowEvent() {
    $window = $this->getMockBuilder('\Woody\Components\Windows\MainWindow')
      ->disableOriginalConstructor()
      ->getMock();
    $window->expects($this->at(0))
        ->method('getControlID')
        ->will($this->returnValue(0));

    $this->eventInfo = new EventInfo(0, 0, $window, 0, 0);

    $this->assertTrue($this->eventInfo->isWindowEvent());
  }

  /**
   * @covers Woody\Event\EventInfo::isWindowResizeEvent
   */
  public function testIsWindowResizeEvent() {
    $window = $this->getMockBuilder('\Woody\Components\Windows\MainWindow')
      ->disableOriginalConstructor()
      ->getMock();
    $window->expects($this->at(0))
        ->method('getControlID')
        ->will($this->returnValue(0));
    
    $this->eventInfo = new EventInfo(0, 0, $window, WBC_RESIZE, 0);

    $this->assertTrue($this->eventInfo->isWindowResizeEvent());
  }

  /**
   * @covers Woody\Event\EventInfo::isWindowCloseEvent
   */
  public function testIsWindowCloseEvent() {
    $window = $this->getMockBuilder('\Woody\Components\Windows\MainWindow')
      ->disableOriginalConstructor()
      ->getMock();
    $window->expects($this->at(0))
        ->method('getControlID')
        ->will($this->returnValue(0));
    
    $this->eventInfo = new EventInfo(0, IDCLOSE, $window, 0, 0);

    $this->assertTrue($this->eventInfo->isWindowCloseEvent());
  }

  /**
   * @covers Woody\Event\EventInfo::isTimerEvent
   */
  public function testIsTimerEvent() {
    $window = $this->getMockBuilder('\Woody\Components\Windows\MainWindow')
      ->disableOriginalConstructor()
      ->getMock();
    $window->expects($this->at(0))
        ->method('getControlID')
        ->will($this->returnValue(0));
    $this->eventInfo = new EventInfo(0, 1, $window, 0, 0);

    $this->assertTrue($this->eventInfo->isTimerEvent());
  }

  /**
   * @covers Woody\Event\EventInfo::isControlEvent
   */
  public function testIsControlEvent() {
    $editBox = $this->getMockBuilder('\Woody\Components\Controls\EditBox')
      ->disableOriginalConstructor()
      ->getMock();
    $editBox->expects($this->at(0))
        ->method('getControlID')
        ->will($this->returnValue(0));
    $this->eventInfo = new EventInfo(0, 1, $editBox, 0, 0);

    $this->assertTrue($this->eventInfo->isControlEvent());
  }

  /**
   * @covers Woody\Event\EventInfo::isFocusEvent
   */
  public function testIsFocusEvent() {
    $editBox = $this->getMockBuilder('\Woody\Components\Controls\EditBox')
      ->disableOriginalConstructor()
      ->getMock();
    $editBox->expects($this->at(0))
        ->method('getControlID')
        ->will($this->returnValue(0));
    $this->eventInfo = new EventInfo(0, 1, $editBox, 0, 0);
    
    $this->eventInfo = new EventInfo(0, 0, $editBox, WBC_GETFOCUS, 0);

    $this->assertTrue($this->eventInfo->isFocusEvent());
  }

  /**
   * @covers Woody\Event\EventInfo::isMouseEvent
   */
  public function testIsMouseEvent() {
    $editBox = $this->getMockBuilder('\Woody\Components\Controls\EditBox')
      ->disableOriginalConstructor()
      ->getMock();
    $editBox->expects($this->at(0))
        ->method('getControlID')
        ->will($this->returnValue(0));

    $this->eventInfo = new EventInfo(0, 0, $editBox, WBC_MOUSEDOWN, 0);

    $this->assertTrue($this->eventInfo->isMouseEvent());
  }

  /**
   * @covers Woody\Event\EventInfo::isKeyEvent
   */
  public function testIsKeyEvent() {
    $editBox = $this->getMockBuilder('\Woody\Components\Controls\EditBox')
      ->disableOriginalConstructor()
      ->getMock();
    $editBox->expects($this->at(0))
        ->method('getControlID')
        ->will($this->returnValue(0));
    
    $this->eventInfo = new EventInfo(0, 0, $editBox, WBC_KEYDOWN, 0);

    $this->assertTrue($this->eventInfo->isKeyEvent());
  }
}