<?php

namespace ws\loewe\Woody\App;

use \ws\loewe\Woody\Components\Windows\MainWindow;
use \ws\loewe\Woody\Components\Timer\Timer;
use \ws\loewe\Utils\Geom\Point;
use \ws\loewe\Utils\Geom\Dimension;

class TestApplication extends Application {
  /**
   * the interval for the shutdown timer
   */
  private static $SHUT_DOWN_TIMER_INTERAL = 100;

  /**
   * the shutdown timer of the application
   *
   * @var Timer
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
  public function __construct(\PHPUnit_Framework_TestCase $testCase = null) {
    parent::__construct();

    $title = $this->getTitle($testCase);

    $this->window = new MainWindow($title, Point::createInstance(50, 50), Dimension::createInstance(300, 200));

    // the callback that actually closes the window
    $callback = function() {
      if(!$this->isRunning) {
        $this->shutdownTimer->destroy();
        $this->window->close();
      }
    };

    $this->shutdownTimer = new Timer($callback, $this->window, self::$SHUT_DOWN_TIMER_INTERAL);

    $this->window->create(null);

    $this->shutdownTimer->start();
  }

  /**
   * This method starts the test application.
   *
   * @return \ws\loewe\Woody\App\TestApplication $this
   */
  public function start() {
    $this->isRunning = TRUE;

    $this->window->startEventHandler();

    return $this;
  }

  /**
   * This method stops the test application.
   *
   * The stopping is delayed for up to the length of the interval of self::$SHUT_DOWN_TIMER_INTERAL, as this is the
   * interval in which the shutdown timer is executed.
   *
   * @return \ws\loewe\Woody\App\TestApplication $this
   */
  public function stop() {
    $this->isRunning = FALSE;
  }

  /**
   * This method gets the title for the main window of the application, to indicate which test case in which file is
   * currently executed.
   *
   * @param \PHPUnit_Framework_TestCase $testCase the test test cases that is run by this application
   * @return string the title for the main window containing the name of the test case and the corresponding file
   */
  private function getTitle(\PHPUnit_Framework_TestCase $testCase = null) {
    $title = 'TestApplication';

    if($testCase != null) {
      $testCaseClass  = new \ReflectionClass($testCase);
      $testCaseFile   = new \SplFileInfo($testCaseClass->getFileName());
      $title          = $testCase->getName().' in '.$testCaseFile->getBasename().' - '.$title;
    }

    return $title;
  }
}