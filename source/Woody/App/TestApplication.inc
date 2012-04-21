<?php

namespace Woody\App;

use \Woody\Components\Windows\MainWindow;
use \Woody\Components\Timer\Timer;
use \Utils\Geom\Point;
use \Utils\Geom\Dimension;

class TestApplication extends Application {
  /**
   * the interval for the shutdown timer
   */
  private static $SHUT_DOWN_TIMER_INTERAL = 1000;

  /**
   * the shutdown timer of the application
   *
   * @var \Closure
   */
  private $shutdownTimer = null;

  /**
   * boolean flag to determine if the application is running
   *
   * @var boolean
   */
  private $isRunning = false;

  /**
   * This method acts as the constructor of the class.
   */
  public function __construct() {
    $this->window = new MainWindow('TestApp', new Point(50, 50), new Dimension(300, 200));

    $this->shutdownTimer = new Timer(function() {
                      if(!$this->isRunning) {
                        $this->shutdownTimer->destroy();

                        $this->window->destroy();
                      }
                    }, $this->window, self::$SHUT_DOWN_TIMER_INTERAL);

    $this->window->create(null);

    $this->shutdownTimer->start($this->window);
  }

  /**
   * This method returns the main window of the test application
   *
   * @return \Woody\Components\Windows\AbstractWindow
   */
  public function getWindow() {
    return $this->window;
  }

  /**
   * This method starts the test application.
   *
   * @return \Woody\App\TestApplication $this
   */
  public function start() {
    $this->isRunning = TRUE;

    $this->window->startEventHandler();

    return $this;
  }

  /**
   * This method stops the test application.
   *
   * The stopping is delayed for up to the length of the interval of self::$SHUT_DOWN_TIMER_INTERAL, as this is the interval in which the shutdown timer is executed.
   *
   * @return \Woody\App\TestApplication $this
   */
  public function stop() {
    $this->isRunning = FALSE;
  }
}