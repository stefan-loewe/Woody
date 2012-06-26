<?php

namespace Woody\Components;

use \Woody\Components\Windows\MainWindow;
use \Woody\Components\Timer\Timer;
use \Woody\Components\Controls\Label;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

/**
 * Test class for Component.
 * Generated by PHPUnit on 2011-11-15 at 23:23:15.
 */
class ComponentTest extends \PHPUnit_Framework_TestCase {
  /**
   * the component to test
   *
   * @var Component
   */
  private $component      = null;

  /**
   * the top-left corner of the component
   *
   * @var Point
   */
  private $topLeftCorner  = null;

  /**
   * the dimension of the component
   *
   * @var Dimension
   */
  private $dimension      = null;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    $this->window = new MainWindow('MainWindow2', new Point(121, 343), new Dimension(300, 200));
    $this->window->create();

    $this->topLeftCorner  = new Point(20, 20);
    $this->dimension      = new Dimension(100, 20);
    $this->component      = new Label('TestLabel', $this->topLeftCorner, $this->dimension);
    $this->window->add($this->component);
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {
    $this->window->destroy();
  }

  /**
   * This method tests getting and setting the timestamp from the calendar.
   *
   * @covers \Woody\Components\Component::getUniqueID
   * @covers \Woody\Components\Component::getComponentByID
   * @covers \Woody\Components\Component::getControlID
   * @covers \Woody\Components\Component::getID
   */
  public function testComponentSimple() {
    $id1 = Component::getUniqueID();
    $id2 = Component::getUniqueID();
    $this->assertEquals(1, $id2 - $id1);

    $component = Component::getComponentByID($this->component->getControlID());
    $this->assertEquals($this->component->getID(), $component->getID());
    $this->assertEquals($this->component->getControlID(), $component->getControlID());
  }

  /**
   * This method tests getting and setting the timestamp from the calendar.
   *
   * @covers \Woody\Components\Component::getParent
   */
  public function testComponentGetParent() {
    $this->assertEquals($this->window, $this->component->getParent());
  }

  /**
   * This method tests getting and setting the timestamp from the calendar.
   *
   * @covers \Woody\Components\Component::getPosition
   */
  public function testComponentGetPosition() {
    $this->assertEquals($this->topLeftCorner->x, $this->component->getPosition()->x);
    $this->assertEquals($this->topLeftCorner->y, $this->component->getPosition()->y);
    $this->assertFalse($this->topLeftCorner === $this->component->getPosition());
  }

  /**
   * This method tests getting and setting the timestamp from the calendar.
   *
   * @covers \Woody\Components\Component::getDimension
   */
  public function testComponentGetDimension() {
    $this->assertEquals($this->dimension->width, $this->component->getDimension()->width);
    $this->assertEquals($this->dimension->height, $this->component->getDimension()->height);
    $this->assertFalse($this->dimension === $this->component->getDimension());
  }
}