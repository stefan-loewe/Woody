<?php

namespace Woody\App;

abstract class Application {
  /**
   * the main window of the application
   *
   * @var \Woody\Components\Windows\AbstractWindow
   */
  protected $window = null;
  
  protected static $instance = null;

  /**
   * This method acts as the constructor of the class.
   */
  public function __construct() {
    if(static::$instance === null) {
      static::$instance = $this;
    }
  }
  
  public static function getInstance() {
    return static::$instance;
  }
  
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