<?php

namespace Woody\Event;

use \Woody\Components\Windows\MainWindow;
use \Woody\Components\Controls\EditBox;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

/**
 * Test class for MouseEvent.
 * Generated by PHPUnit on 2012-06-27 at 20:30:53.
 */
class MouseEventTest extends \PHPUnit_Framework_TestCase {
  /**
   * @var MouseEvent the event to be tested
   */
  private $event = null;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    $eventInfo = $this->getMockBuilder('\Woody\Event\EventInfo')
      ->disableOriginalConstructor()
      ->getMock();

    // configure the mock to return the proper values for its members
    $eventInfo->expects($this->at(3))
      ->method('__get')
      ->with($this->equalTo('type'))
      ->will($this->returnValue(WBC_MOUSEDOWN | WBC_LBUTTON));
    $eventInfo->expects($this->at(4))
      ->method('__get')
      ->with($this->equalTo('property'))
      ->will($this->returnValue(10223723));

    $this->event = new MouseEvent($eventInfo);
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {
  }

  /**
   * This method tests creating the event.
   *
   * @covers \Woody\Event\MouseEvent::__construct
   * @covers \Woody\Event\Event::__construct
   */
  public function testConstruct() {
    $this->assertInstanceOf('\Woody\Event\MouseEvent', $this->event);
  }/**
   * This method tests dispatching the event.
   *
   * @covers \Woody\Event\MouseEvent::dispatch
   * @covers \Woody\Event\MouseEvent::isMouseDownEvent
   * @covers \Woody\Event\MouseEvent::isMouseUpEvent
   */
  public function testDispatch() {
    $window   = new MainWindow('MainWindow', new Point(50, 50), new Dimension(300, 200));
    $editbox  = new EditBox('', new Point(20, 20), new Dimension(100, 18));
    $window->create()->getRootPane()->add($editbox);

    $mouseListener = $this->getMockBuilder('\Woody\Event\MouseAdapter')
      ->disableOriginalConstructor()
      ->getMock();

    $mouseListener->expects($this->once())->method('mousePressed');
    $mouseListener->expects($this->once())->method('mouseReleased');
    $editbox->addMouseListener($mouseListener);

    $event = new MouseEvent(new EventInfo(0, $editbox->getID(), $editbox, WBC_MOUSEDOWN, 0));
    $event->dispatch();

    $event = new MouseEvent(new EventInfo(0, $editbox->getID(), $editbox, WBC_MOUSEUP, 0));
    $event->dispatch();

    $window->close();
  }

  /**
   * This methos tests getting the position of the mouse event.
   *
   * @covers \Woody\Event\MouseEvent::getPosition
   */
  public function testGetPosition() {
    $position = $this->event->getPosition();

    $this->assertEquals(107, $position->x);
    $this->assertEquals(156, $position->y);
  }

  /**
   * This method tests getting if the right button was pressed.
   *
   * @covers \Woody\Event\MouseEvent::getPressedButton
   */
  public function testGetPressedButton1() {
    $this->assertEquals(MouseEvent::BUTTON1, $this->event->getPressedButton());
  }

  /**
   * This method tests getting if the left button was pressed.
   *
   * @covers \Woody\Event\MouseEvent::getPressedButton
   */
  public function testGetPressedButton2() {
    $eventInfo = $this->getMockBuilder('\Woody\Event\EventInfo')
      ->disableOriginalConstructor()
      ->getMock();

    // configure the mock to return the proper values for its members
    $eventInfo->expects($this->at(3))
      ->method('__get')
      ->with($this->equalTo('type'))
      ->will($this->returnValue(WBC_MOUSEDOWN | WBC_RBUTTON));

    $this->event = new MouseEvent($eventInfo);
    $this->assertEquals(MouseEvent::BUTTON2, $this->event->getPressedButton());
  }

  /**
   * This method tests getting if the middle button was pressed.
   *
   * @covers \Woody\Event\MouseEvent::getPressedButton
   */
  public function testGetPressedButton3() {
    $eventInfo = $this->getMockBuilder('\Woody\Event\EventInfo')
      ->disableOriginalConstructor()
      ->getMock();

    // configure the mock to return the proper values for its members
    $eventInfo->expects($this->at(3))
      ->method('__get')
      ->with($this->equalTo('type'))
      ->will($this->returnValue(WBC_MOUSEDOWN | WBC_MBUTTON));

    $this->event = new MouseEvent($eventInfo);
    $this->assertEquals(MouseEvent::BUTTON3, $this->event->getPressedButton());
  }

  /**
   * This method tests getting the click count of the event.
   *
   * @covers \Woody\Event\MouseEvent::getClickCount
   */
  public function testGetClickCount() {
    $window = new MainWindow('MainWindow', new Point(50, 50), new Dimension(300, 200));
    $control1 = new EditBox('', new Point(20, 20), new Dimension(100, 18));
    $window->create()->getRootPane()->add($control1);

    // create a one-second delay to have a single-click only again
    sleep(1);

    $events = new \ArrayObject();
    $eventInfo = new EventInfo(0, $control1->getID(), $control1, WBC_MOUSEDOWN | WBC_LBUTTON, 0);

    // first click ...
    $events[] = new MouseEvent($eventInfo);
    // ... 2nd ...
    $events[] = new MouseEvent($eventInfo);
    // ... and 3rd
    $events[] = new MouseEvent($eventInfo);

    $this->assertEquals(1, $events[0]->getClickCount());
    $this->assertEquals(2, $events[1]->getClickCount());
    $this->assertEquals(3, $events[2]->getClickCount());

    // create a one-second delay to have a single-click only again
    sleep(1);

    $leftClickEvent   = EventFactory::createEvent($eventInfo)[0];

    $eventInfo = new EventInfo(0, $control1->getID(), $control1, WBC_MOUSEDOWN | WBC_RBUTTON, 0);
    $rightClickEvent  = EventFactory::createEvent($eventInfo)[0];

    $this->assertEquals(1, $leftClickEvent->getClickCount());
    $this->assertEquals(1, $rightClickEvent->getClickCount());

    $eventInfo    = new EventInfo(0, $control1->getID(), $control1, WBC_MOUSEUP, 0);
    $mouseUpEvent = EventFactory::createEvent($eventInfo)[0];
    $this->assertEquals(0, $mouseUpEvent->getClickCount());

    $window->close();
  }

  /**
   * This method tests getting the string representation of the event.
   *
   * @covers \Woody\Event\MouseEvent::__toString
   * @covers \Woody\Event\Event::__toString
   */
  public function test__toString() {
    $this->assertTrue(strpos($this->event->__toString(), 'button = ') !== FALSE);
    $this->assertTrue(strpos($this->event->__toString(), 'position = ') !== FALSE);
  }
}