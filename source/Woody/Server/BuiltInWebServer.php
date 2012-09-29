<?php

namespace Woody\Server;

use \Woody\Components\Windows\AbstractWindow;
use \Woody\Components\Controls\HtmlControl;

class BuiltInWebServer {
  /**
   * the window associated with the server, normally the main window of the appication
   *
   * @var AbstractWindow
   */
  private $window = null;

  /**
   *
   * @var type the port on which the server listens
   */
  private $port = 0;

  /**
   * the server used to communicate with the router script of the built-in php web server
   *
   * @var HtmlControlServer
   */
  private $replyServer = null;

  /**
   * the php process running the built-in php web server
   *
   * @var resource
   */
  private $process = null;

  /**
   * the folder representing the document root
   *
   * @var string
   */
  private $documentRoot = null;

  /**
   * the path to the PHP executable, e.g., "C:\\Program Files\\PHP\\php.exe"
   *
   * @var string
   */
  private $pathToPhpExecutable = null;

  /**
   * the script which handles the routing for PHP's internal webserver
   *
   * @var string
   */
  private $routerScript = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param AbstractWindow $window the window associated with the server, normally the main window of the application
   * @param int $port the port on which the server listens
   * @param string $documentRoot the document root of the server
   * @param string $pathToPhpExecutable the path to the PHP executable, e.g., "C:\\Program Files\\PHP\\php.exe"
   * @param string $routerScript the script which handles the routing for PHP's internal webserver
   * @param HtmlControlServer the server used to communicate with the router script of the built-in php web server
   */
  public function __construct(
      AbstractWindow $window,
      $port,
      $documentRoot,
      $pathToPhpExecutable,
      $routerScript,
      HtmlControlServer $replyServer) {
    $this->window               = $window;
    $this->port                 = $port;
    $this->documentRoot         = $documentRoot;
    $this->pathToPhpExecutable  = $pathToPhpExecutable;
    $this->routerScript         = $routerScript;
    $this->replyServer          = $replyServer;
  }

  /**
   * This method starts the web server.
   *
   * @return BuiltInWebServer $this
   */
  public function start() {
    $this->startWebServerProcess();

    $this->startHtmlReplyServer();

    return $this;
  }

  /**
   * This method stops the web server.
   *
   * @return BuiltInWebServer $this
   */
  public function stop() {
    proc_terminate($this->process);

    return $this;
  }

  /**
   * This method starts the web server process.
   */
  private function startWebServerProcess() {
    $descriptors = array();
    $pipes = array();

    // bypass_shell is true, so that process can be terminated, and is not "embedded" in cmd.exe process
    $this->process = proc_open(
      $this->pathToPhpExecutable.' -S 127.0.0.1:'.$this->port.' -t "'.$this->documentRoot.'" '.$this->routerScript,
      $descriptors,
      $pipes,
      null,
      null,
      array('bypass_shell' => true)
    );
  }

  /**
   * This method registers a HtmlControl for this server, so that the HtmlControl is notified when new data is
   * available.
   *
   * @param HtmlControl $control the HtmlControl to register
   * @return BuiltInWebServer $this
   */
  public function register(HtmlControl $control) {
    $this->replyServer->register($control);

    return $this;
  }

  /**
   * This method unregisters the given HtmlControl.
   *
   * @param HtmlControl $control the HtmlControl to be removed
   * @return HtmlControlServer $this
   */
  public function unregister(HtmlControl $control) {
    $this->replyServer->unregister($control);

    return $this;
  }

  /**
   * This method starts the HtmlControlServer which processes messages from the router script of the built-in php web
   * server.
   */
  private function startHtmlReplyServer() {
    $this->replyServer->start(100);
  }
}