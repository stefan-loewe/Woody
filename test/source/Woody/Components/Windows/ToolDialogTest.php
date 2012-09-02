<?php

namespace Woody\Components\Windows;

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

/**
 * Test class for ToolDialog.
 * Generated by PHPUnit on 2010-11-25 at 20:49:16.
 */
class ToolDialogTest extends \PHPUnit_Framework_TestCase {
  /**
   * @var ModalDialog
   */
  protected $window;

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
   * This method tests constructing the tool dialog.
   *
   * @covers \Woody\Components\Windows\ToolDialog::__construct
   */
  public function testConstruct() {
    $this->window = new ModalDialog('ToolDialog', new Point(121, 343), new Dimension(987, 654));

    $this->assertEquals($this->window->getPosition()->x, 121);
    $this->assertEquals($this->window->getPosition()->y, 343);

    $this->assertEquals($this->window->getDimension()->width, 987);
    $this->assertEquals($this->window->getDimension()->height, 654);

    $this->assertNull($this->window->getControlID());
    $this->assertNotNull($this->window->getID());
  }
}