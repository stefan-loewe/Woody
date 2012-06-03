<?php

namespace Woody\Components\Controls;

use \Woody\App\TestApplication;
use \Woody\Components\Timer\Timer;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

/**
 * Test class for Slider.
 * Generated by PHPUnit on 2011-12-18 at 19:31:01.
 */
class SliderTest extends \PHPUnit_Framework_TestCase {

  /**
   * the progress bar to test
   *
   * @var \Woody\Components\Controls\Slider
   */
  private $slider = null;

  /**
   * the test application
   *
   * @var \Woody\App\TestApplication
   */
  private $application = false;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    $this->application = new TestApplication();

    $this->slider = new Slider(new Point(20, 20), new Dimension(80, 20));

    $this->application->getWindow()->add($this->slider);
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {

  }

  /**
   * @covers \Woody\Components\Controls\Slider::getValue
   * @covers \Woody\Components\Controls\Slider::setValue
   */
  public function testGetSetValue() {
    $this->timer = new Timer(function() {
                              $this->assertEquals(0, $this->slider->getValue());

                              $this->slider->setValue(100);
                              $this->assertEquals(100, $this->slider->getValue());

                              $this->timer->destroy();
                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * @covers \Woody\Components\Controls\Slider::setRange
   */
  public function testSetRange() {
    $this->timer = new Timer(function() {
                              $this->assertEquals(0, $this->slider->getValue());

                              $this->slider->setRange(0, Timer::TEST_TIMEOUT);
                              $this->slider->setValue(100);
                              $this->assertEquals(100, $this->slider->getValue());

                              $this->timer->destroy();
                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }
}