<?php

namespace Woody\Components\Controls;

use \Woody\App\TestApplication;
use \Woody\Components\Timer\Timer;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

/**
 * Test class for EditArea.
 * Generated by PHPUnit on 2011-11-15 at 23:23:15.
 */
class CheckboxTest extends \PHPUnit_Framework_TestCase {
  /**
   * the checkbox to test
   *
   * @var \Woody\Components\Controls\Checkbox
   */
  private $checkbox = null;

  /**
   * the test application
   *
   * @var \Woody\App\TestApplication
   */
  private $application = false;

  /**
   * the timer for the test application
   *
   * @var \Woody\Components\Timer\Timer
   */
  private $timer = null;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    $this->application = new TestApplication();

    $this->checkbox = new Checkbox(FALSE, new Point(20, 20), new Dimension(20, 20));

    $this->application->getWindow()->getRootPane()->add($this->checkbox);
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {
  }

  /**
   * This method tests resizing the checkbox.
   *
   * @covers \Woody\Components\Controls\Checkbox::resizeTo
   */
  public function testResizeTo() {
    $callback = function() {
      $expected = new Dimension(15, 15);
      $this->checkbox->resizeTo($expected);

      $actual = $this->checkbox->getDimension();
      $this->assertEquals($expected->width, $actual->width);
      $this->assertEquals($expected->height, $actual->height);

      
      $this->checkbox->resizeTo(new Dimension(150, 150));

      $actual = $this->checkbox->getDimension();
      $this->assertEquals(Checkbox::MAX_WIDTH, $actual->width);
      $this->assertEquals(Checkbox::MAX_HEIGHT, $actual->height);

      $this->timer->destroy();
      $this->application->stop();
    };

    $this->timer = new Timer($callback, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * This method tests retrieving the value of the checkbox.
   *
   * @covers \Woody\Components\Controls\Checkbox::isChecked
   */
  public function testIsChecked() {
    $this->timer = new Timer(function() {
                              $this->assertEquals(FALSE, $this->checkbox->isChecked());

                              $this->checkbox->setChecked(TRUE);
                              $this->assertEquals(TRUE, $this->checkbox->isChecked());

                              $this->checkbox->setChecked(FALSE);
                              $this->assertEquals(FALSE, $this->checkbox->isChecked());

                              $this->timer->destroy();

                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }

  /**
   * This method tests setting the value of the checkbox.
   *
   * @covers \Woody\Components\Controls\Checkbox::setChecked
   */
  public function testSetChecked() {
    $this->timer = new Timer(function() {
                              $this->assertEquals(FALSE, $this->checkbox->isChecked());

                              $this->checkbox->setChecked(TRUE);
                              $this->assertEquals(TRUE, $this->checkbox->isChecked());

                              $this->checkbox->setChecked(FALSE);
                              $this->assertEquals(FALSE, $this->checkbox->isChecked());

                              $this->checkbox->setChecked(TRUE);
                              $this->assertEquals(TRUE, $this->checkbox->isChecked());

                              $this->timer->destroy();

                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }
}