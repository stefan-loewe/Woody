<?php

namespace Woody\App;

abstract class Application {
  /**
   * the main window of the application
   *
   * @var \Woody\Components\Windows\AbstractWindow
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
   * @return \Woody\Components\Windows\AbstractWindow
   */
  public function getWindow() {
    return $this->window;
  }

  /**
   * This method starts the application.
   */
  abstract public function start();

  /**
   * This method stops the application.
   */
  abstract public function stop();
}