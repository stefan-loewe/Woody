<?php

namespace ws\loewe\Woody\Components\Timer;

use \ws\loewe\Woody\Components\Windows\MainWindow;
use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Geom\Dimension;
use \ws\loewe\Woody\Components\Timer\TimerAlreadyRunningException;
use \ws\loewe\Woody\Components\Timer\TimerNotRunningException;

/**
 * Test class for Timer.
 * Generated by PHPUnit on 2011-11-16 at 21:19:10.
 */
class TimerTest extends \PHPUnit_Framework_TestCase {
  /**
   * the timer to test
   *
   * @var \ws\loewe\Woody\Components\Timer\Timer
   */
  private $timer = null;

  /**
   * the window to which the timer is bound to
   *
   * @var \ws\loewe\Woody\Components\Windows\MainWindow
   */
  private $window = null;

  /**
   * the counter for testing timer callback call-counts
   *
   * @var int
   */
  private $counter = 0;

  /**
   * This method sets up a plain window for testing.
   */
  protected function setUp() {
    $this->window = new MainWindow($this->getName().'-'.basename(__FILE__), new Point(50, 50), new Dimension(300, 200));

    $this->window->create(null);

    $this->counter = 0;
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {
  }

  /**
   * This method tests the construction of a timer.
   *
   * @covers ws\loewe\Woody\Components\Timer\Timer::__construct
   */
  public function testConstruct() {
    $callback = function() {
      $this->timer->destroy();
      $this->window->close();

      $this->assertEquals(1, ++$this->counter);
    };
    $this->timer = new Timer($callback, $this->window, Timer::TEST_TIMEOUT);

    $this->timer->start();
    $this->window->startEventHandler();
  }

  /**
   * This method tests the starting of a timer by checking if the callback gets executed at least once after the timer
   * has been started.
   *
   * @covers ws\loewe\Woody\Components\Timer\Timer::start
   */
  public function testStart() {
    $callback = function() {
      $this->timer->destroy();
      $this->window->close();

      $this->assertEquals(1, ++$this->counter);
    };
    $this->timer = new Timer($callback, $this->window, Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->window->startEventHandler();
  }

  /**
   * This method tests the destroying of the timer.
   *
   * @covers ws\loewe\Woody\Components\Timer\Timer::destroy
   */
  public function testDestroy() {
    $callback = function() {
      ++$this->counter;

      $this->timer->destroy();
      $this->window->close();

      $this->assertEquals(1, $this->counter);
    };
    $this->timer = new Timer($callback, $this->window, Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->window->startEventHandler();
  }

  /**
   * This method tests the returning of the timer id.
   *
   * @covers ws\loewe\Woody\Components\Timer\Timer::getID
   */
  public function testGetID() {
    $callback = function() {
      $this->assertEquals($this->window->getID() + 2, $this->timer->getID());

      $this->timer->destroy();
      $this->window->close();

      $this->assertEquals($this->window->getID() + 2, $this->timer->getID());
    };
    $this->timer = new Timer($callback, $this->window, Timer::TEST_TIMEOUT);

    $this->assertEquals($this->window->getID() + 2, $this->timer->getID());

    $this->timer->start();

    $this->assertEquals($this->window->getID() + 2, $this->timer->getID());

    $this->window->startEventHandler();

    $this->assertEquals($this->window->getID() + 2, $this->timer->getID());
  }

  /**
   * This method tests the returning of the timer id.
   *
   * @covers ws\loewe\Woody\Components\Timer\Timer::getTimerByID
   */
  public function testGetTimerByID() {
    $callback = function() {
      $this->timer->destroy();
      $this->window->close();
    };
    $this->timer = new Timer($callback, $this->window, Timer::TEST_TIMEOUT);

    $timer = Timer::getTimerByID($this->timer->getID());

    $this->assertEquals($timer->getID(), $this->timer->getID());
    $this->assertNull(Timer::getTimerByID(1000));

    $this->timer->start();
    $this->window->startEventHandler();

    $this->assertEquals($timer->getID(), $this->timer->getID());
    $this->assertNull(Timer::getTimerByID(1000));
  }

  /**
   * This method tests the returning of the execution counter of the timer.
   *
   * @covers ws\loewe\Woody\Components\Timer\Timer::getExecutionCount
   */
  public function testGetExecutionCount() {
    $callback = function() {
      if($this->timer->getExecutionCount() > 0) {
        $this->assertEquals(1, $this->timer->getExecutionCount());

        $this->timer->destroy();
        $this->window->close();
      }
    };

    $this->timer = new Timer($callback, $this->window, Timer::TEST_TIMEOUT);

    $this->assertEquals(0, $this->timer->getExecutionCount());

    $this->timer->start();
    $this->window->startEventHandler();
  }

  /**
   * This method tests if the \ws\loewe\Woody\Components\Timer\TimerAlreadyRunningException is
   * thrown, when starting an already started timer.
   *
   * @covers \ws\loewe\Woody\Components\Timer\Timer::start
   * @covers \ws\loewe\Woody\Components\Timer\TimerAlreadyRunningException::__construct
   */
  public function testTimerAlreadyRunningException() {
    $callback = function() {
      try {
        $this->timer->start();
      }
      catch(TimerAlreadyRunningException $tare) {
        $this->timer->destroy();
        $this->window->close();

        return;
      }

      $this->fail('The expected TimerAlreadyRunningException has not been raised.');
    };
    $this->timer = new Timer($callback, $this->window, Timer::TEST_TIMEOUT);

    $this->timer->start();

    $this->window->startEventHandler();
  }

  /**
   * This method tests if the TimerNotRunningException are thrown,
   * when destroying an timer which was not yet started.
   *
   * @covers ws\loewe\Woody\Components\Timer\Timer::destroy
   * @covers \ws\loewe\Woody\Components\Timer\TimerNotRunningException::__construct
   */
  public function testTimerNotRunningDestroyException() {
    $this->timer = new Timer(function(){}, $this->window, Timer::TEST_TIMEOUT);

    try {
      $this->timer->destroy();
    }
    catch(TimerNotRunningException $tnre) {
      $this->window->close();
      return;
    }

    $this->fail('The expected TimerNotRunningException has not been raised.');
  }

  /**
   * This method tests determining if the timer is running.
   *
   * @covers ws\loewe\Woody\Components\Timer\Timer::isRunning
   */
  public function testIsRunning() {
    $callback = function() {
      $this->assertTrue($this->timer->isRunning());

      $this->timer->destroy();
      $this->assertFalse($this->timer->isRunning());

      $this->window->close();
    };
    $this->timer = new Timer($callback, $this->window, Timer::TEST_TIMEOUT);

    $this->assertFalse($this->timer->isRunning());
    $this->timer->start();
    $this->assertTrue($this->timer->isRunning());

    $this->window->startEventHandler();
  }

  /**
   * This method tests adding, getting and removing timeout listeners.
   *
   * @covers \ws\loewe\Woody\Components\Timer\Timer::addTimeoutListener
   * @covers \ws\loewe\Woody\Components\Timer\Timer::getTimeoutListeners
   * @covers \ws\loewe\Woody\Components\Timer\Timer::removeTimeoutListener
   */
  public function testWindowResizeListeners() {
    $timeoutListener = $this->getMockBuilder('\ws\loewe\Woody\Event\TimeoutAdapter')
      ->disableOriginalConstructor()
      ->getMock();

    $this->timer = new Timer(function() {}, $this->window, Timer::TEST_TIMEOUT);
    
    $this->assertEquals($this->timer, $this->timer->addTimeoutListener($timeoutListener));
    $this->assertTrue($this->timer->getTimeoutListeners()->contains($timeoutListener));

    $this->assertEquals($this->timer, $this->timer->removeTimeoutListener($timeoutListener));
    $this->assertFalse($this->timer->getTimeoutListeners()->contains($timeoutListener));
    
    $this->window->close();
  }
}