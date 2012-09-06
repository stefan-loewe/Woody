<?php

namespace Woody\Event;

use \Woody\Components\Controls\EditBox;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

/**
 * Test class for EventFactory.
 * Generated by PHPUnit on 2012-09-04 at 22:37:42.
 */
class EventFactoryTest extends \PHPUnit_Framework_TestCase {
  /**
   * event information on which the to-be-created event depends on
   * 
   * @var EventInfo
   */
  private $eventInfo = null;
  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    $this->eventInfo = $this->getMockBuilder('\Woody\Event\EventInfo')
      ->disableOriginalConstructor()
      ->getMock();
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {
  }

  /**
   * @covers \Woody\Event\EventFactory::createEvent
   * @covers \Woody\Event\EventFactory::createWindowClosedEvent
   */
  public function testCreateWindowCloseEvent() {
    $this->eventInfo->expects($this->once())
        ->method('isWindowCloseEvent')
        ->will($this->returnValue(TRUE));

    $this->assertInstanceOf('\Woody\Event\WindowCloseEvent', EventFactory::createEvent($this->eventInfo)[0]);
  }
  
  /**
   * @covers \Woody\Event\EventFactory::createEvent
   * @_covers \Woody\Event\EventFactory::createTimerEvent
   */
  public function testCreateTimerEvent() {return;
    $this->eventInfo->expects($this->once())
        ->method('isTimerEvent')
        ->will($this->returnValue(TRUE));
    
    $this->assertInstanceOf('\Woody\Event\TimerEvent', EventFactory::createEvent($this->eventInfo)[0]);
  }

  /**
   * @covers \Woody\Event\EventFactory::createEvent
   * @covers \Woody\Event\EventFactory::createFocusEvent
   */
  public function testCreateFocusEvent() {
    $this->eventInfo->expects($this->once())
        ->method('isFocusEvent')
        ->will($this->returnValue(TRUE));
    
    $this->assertInstanceOf('\Woody\Event\FocusEvent', EventFactory::createEvent($this->eventInfo)[0]);
  }

  /**
   * @covers \Woody\Event\EventFactory::createEvent
   * @covers \Woody\Event\EventFactory::createMouseEvent
   */
  public function testCreateMouseEvent() {
    $this->eventInfo->expects($this->once())
        ->method('isMouseEvent')
        ->will($this->returnValue(TRUE));
    
    $this->assertInstanceOf('\Woody\Event\MouseEvent', EventFactory::createEvent($this->eventInfo)[0]);
  }

  /**
   * @covers \Woody\Event\EventFactory::createEvent
   * @covers \Woody\Event\EventFactory::createKeyEvent
   */
  public function testCreateKeyEvent() {
    $this->eventInfo->expects($this->once())
        ->method('isKeyEvent')
        ->will($this->returnValue(TRUE));
    
    $this->assertInstanceOf('\Woody\Event\KeyEvent', EventFactory::createEvent($this->eventInfo)[0]);
  }

  /**
   * @covers \Woody\Event\EventFactory::createEvent
   * @covers \Woody\Event\EventFactory::createActionEvent
   */
  public function testCreateActionEvent() {
    // even with reflection, EventInfo::source could not be set to a component in an EventInfo mock object
    // ReflectionProperty::setAccessible claimed EventInfoMock::source would not exist
    // it did work when using a real EventInfo object or when setting the property to public
    $this->eventInfo = new EventInfo(1, 1, new EditBox('', new Point(1, 2), new Dimension(1, 2)), 0, 0);
    
    $this->assertInstanceOf('\Woody\Event\ActionEvent', EventFactory::createEvent($this->eventInfo)[0]);
  }

  /**
   * @covers \Woody\Event\EventFactory::createEvent
   * @covers \Woody\Event\EventFactory::createWindowResizeEvent
   */
  public function testCreateWindowResizeEvent() {return;
    $this->eventInfo->expects($this->once())
        ->method('isWindowResizeEvent')
        ->will($this->returnValue(TRUE));
    
    $this->assertInstanceOf('\Woody\Event\WindowResizeEvent', EventFactory::createEvent($this->eventInfo)[0]);
  }
}