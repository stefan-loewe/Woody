<?php

namespace ws\loewe\Woody\Components\Controls;

use \ws\loewe\Woody\App\TestApplication;
use \ws\loewe\Woody\Components\Timer\Timer;
use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Geom\Dimension;

/**
 * Test class for PushButton.
 * Generated by PHPUnit on 2011-12-15 at 22:14:10.
 */
class PushButtonTest extends \PHPUnit_Framework_TestCase {

  /**
   * the push button to test
   *
   * @var \ws\loewe\Woody\Components\Controls\PushButton
   */
  private $pushButton = null;

  /**
   * the test application
   *
   * @var \ws\loewe\Woody\App\TestApplication
   */
  private $application = null;

  /**
   * the timer for the test application
   *
   * @var \ws\loewe\Woody\Components\Timer\Timer
   */
  private $timer = null;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    $this->application = new TestApplication();

    $this->pushButton = new PushButton('buttonLabel', new Point(20, 20), new Dimension(80, 20));

    $this->application->getWindow()->getRootPane()->add($this->pushButton);
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {
  }

  /**
   * @covers \ws\loewe\Woody\Components\Controls\PushButton::getLabel
   * @covers \ws\loewe\Woody\Components\Controls\PushButton::setLabel
   */
  public function testGetSetLabel() {
    $this->timer = new Timer(function() {
                              $this->assertEquals('buttonLabel', $this->pushButton->getLabel());

                              $this->pushButton->setLabel('pushButtonNew');
                              $this->assertEquals('pushButtonNew', $this->pushButton->getLabel());

                              $this->timer->destroy();
                              $this->application->stop();
                            }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->application->start();
  }
}