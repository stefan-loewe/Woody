<?php

namespace ws\loewe\Woody\Layouts;

/**
 * Test class for GridLayout.
 * Generated by PHPUnit on 2012-07-07 at 00:15:34.
 */
class GridLayoutTest extends \PHPUnit_Framework_TestCase {

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
   * This method tests creating the application.
   *
   * @covers \ws\loewe\Woody\Layouts\GridLayout::__construct
   */
  public function testConstruct() {
    $this->assertInstanceOf('ws\loewe\Woody\Layouts\GridLayout', new GridLayout(1, 1));
  }

  /**
   * This method tests layouting a container.
   *
   * @covers \ws\loewe\Woody\Layouts\GridLayout::layout
   * @covers \ws\loewe\Woody\Layouts\GridLayout::getComponentDimension
   */
  public function testLayout() {
    // create and configure a mock for a standard control, here an edit box
    $editBox = $this->getMockBuilder('\ws\loewe\Woody\Components\Controls\EditBox')
      ->disableOriginalConstructor()
      ->getMock();
    $editBox->expects($this->once())
      ->method('resizeTo');
    $editBox->expects($this->once())
      ->method('moveTo');

    // also create and configure a mock for a frame ...
    $frame = $this->getMockBuilder('\ws\loewe\Woody\Components\Controls\Frame')
      ->disableOriginalConstructor()
      ->getMock();
    // .. which is made to contain the edit box mock
    $frame->expects($this->once())
      ->method('getComponents')
      ->will($this->returnValue(array($editBox)));
    $frame->expects($this->once())
      ->method('getDimension')
      ->will($this->returnValue(new ws\loewe\Utils\Geom\Dimension(200, 100)));

    // layout the edit box in the frame
    (new GridLayout(1, 1))->layout($frame);
  }
}