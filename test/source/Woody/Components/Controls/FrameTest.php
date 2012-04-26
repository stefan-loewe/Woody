<?php

namespace Woody\Components\Controls;

use \Woody\App\TestApplication;
use \Woody\Components\Timer\Timer;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

/**
 * Test class for EditBox.
 * Generated by PHPUnit on 2011-11-15 at 23:23:15.
 */
class FrameTest extends \PHPUnit_Framework_TestCase {
  /**
   * the frame to test
   *
   * @var Frame
   */
  private $frame = null;

  /**
   * the test application
   *
   * @var TestApplication
   */
  private $application = false;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    $this->application = new TestApplication();

    $this->frame = new Frame('frameLabel', new Point(20, 20), new Dimension(100, 18));

    $this->application->getWindow()->add($this->frame);
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {

  }

  /**
   * This method tests adding components to the frame.
   *
   * @covers \Woody\Components\Controls\Frame::add
   */
  public function testAdd() {
    $this->timer = new Timer(function() {
        $checkbox = new Checkbox(false, new Point(10, 10), new Dimension(10, 10));
        $this->assertNull($checkbox->getParent());

        $this->frame->add($checkbox);
        $this->assertEquals($this->frame, $checkbox->getParent());

        $this->frame->add($checkbox);
        $this->assertEquals($this->frame, $checkbox->getParent());

        $this->timer->destroy();
        $this->application->stop();
        }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start($this->application->getWindow());

    $this->application->start();
  }

  /**
   * This method tests removing components to the frame.
   *
   * @covers \Woody\Components\Controls\Frame::remove
   */
  public function testRemove() {
    $this->timer = new Timer(function() {
          $checkbox = new Checkbox(false, new Point(10, 10), new Dimension(10, 10));
          $this->assertNull($checkbox->getParent());

          $this->frame->remove($checkbox);
          $this->assertNull($checkbox->getParent());

          $this->frame->add($checkbox);
          $this->assertEquals($this->frame, $checkbox->getParent());

          $this->frame->remove($checkbox);
          $this->assertNull($checkbox->getParent());

          $this->timer->destroy();
          $this->application->stop();
        }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start($this->application->getWindow());

    $this->application->start();
  }
}