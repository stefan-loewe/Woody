<?php

namespace ws\loewe\Woody\App;

abstract class Application {
  /**
   * the main window of the application
   *
   * @var \ws\loewe\Woody\Components\Windows\AbstractWindow
   */
  protected $window = null;

  /**
   * the current instance of the application
   *
   * @var Application
   */
  protected static $instance = null;

  /**
   * This method acts as the constructor of the class.
   */
  public function __construct() {
    static::$instance = $this;
  }

  /**
   * This method return the current instance of the application.
   *
   * Note that this is not a singleton class.
   *
   * @return Application
   */
  public static function getInstance() {
    return static::$instance;
  }

  /**
   * This method returns the main window of the application.
   *
   * @return \ws\loewe\Woody\Components\Windows\AbstractWindow
   */
  public function getWindow() {
    return $this->window;
  }

  /**
   * This method starts the application.
   *
   * Any subclass of this class must call this method at the end of its own
   * implementation of start, so that the main loop is executed.
   */
  public function start() {
    wb_main_loop();
  }

  /**
   * This method stops the application.
   */
  abstract public function stop();
}