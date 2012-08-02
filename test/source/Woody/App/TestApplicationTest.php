<?php

namespace Woody\App;

use \Woody\Components\Timer\Timer;

/**
 * Test class for TestApplication.
 * Generated by PHPUnit on 2012-07-05 at 20:39:44.
 */
class TestApplicationTest extends \PHPUnit_Framework_TestCase {
  /**
   * @var TestApplication the application to test
   */
  private $application;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    $this->application = new TestApplication();
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
   * @covers \Woody\App\TestApplication::__construct
   * @covers \Woody\App\Application::__construct
   * @covers \Woody\App\Application::getInstance
   */
  public function testConstruct() {
    $this->assertInstanceOf('\Woody\App\Application', $this->application);
    $this->assertInstanceOf('\Woody\App\Application', $this->application->getInstance());

    $this->timer = new Timer(function() {
          $this->timer->destroy();
          $this->application->stop();
        }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();
    $this->application->start();
  }

  /**
   * This method tests getting the window from the application.
   *
   * @covers \Woody\App\Application::getWindow
   */
  public function testGetWindow() {
    $this->assertInstanceOf('Woody\Components\Windows\AbstractWindow', $this->application->getWindow());

    $this->timer = new Timer(function() {
          $this->timer->destroy();
          $this->application->stop();
        }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();
    $this->application->start();
  }

  /**
   * This method tests starting and stopping the application.
   *
   * @covers \Woody\App\TestApplication::start
   * @covers \Woody\App\TestApplication::stop
   */
  public function testStart() {
        $this->timer = new Timer(function() {
          $this->timer->destroy();
          $this->application->stop();
        }, $this->application->getWindow(), Timer::TEST_TIMEOUT);

    $this->timer->start();
    $this->application->start();
  }
}