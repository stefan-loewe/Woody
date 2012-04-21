<?php

namespace Woody\Server;

use \Woody\Components\Windows\AbstractWindow;
use \Woody\Components\Controls\HtmlControl;
use \Woody\Components\Timer\Timer;
use \Woody\Event\EventHandler;
use \Utils\Sockets\ServerSocket;
use \Utils\Sockets\Server;
use \Utils\Http\HttpRequestFactory;
use \Utils\Http\HttpRequest;
use \Utils\Logging\Logger;

class ReplyServer extends Server {
  /**
   * the window this server is associated with, normally this would be the main window of the application
   *
   * @var \Woody\Components\Windows\AbstractWindow
   */
  private $window = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param int $port the port the server is bound to.
   */
  public function __construct(AbstractWindow $window, $port) {
    parent::__construct('127.0.0.1', $port, 0);

    $this->window = $window;
  }

  /**
   * @inheritdoc
   *
   * It reads upto 2048 bytes, packs it into a respective HTTPRequest object, and routes it to all registered HtmlControls.
   */
  protected function processClient(ServerSocket $clientSocket) {
    $message = trim($clientSocket->read(2048));

    Logger::log(Logger::DEBUG, 'client said: '.$message);

    $answer = $this->operation($message);
    $answer = '<html><h1>HI, huge text</h1><br>followed by an image ... <br><img src="A1.png"></html>';
    $clientSocket->write($answer);

    Logger::log(Logger::DEBUG, 'server replied '.$answer);

    $this->disconnectClient($clientSocket);
  }

  /**
   * This is just a dummy operation for demonstration purposes, reversing the input string and returning it.
   *
   * @param string $input the input string
   * @return string the input string reversed
   */
  private function operation($input) {
    return strrev($input);
  }

  public function start($interval = 1000) {
    $this->isRunning = TRUE;

    $timer = new Timer(function() {
                      $this->loopOnce();
                    }, $this->window, $interval);

    $timer->start();
  }

  /**
   * This method performs the client-socket selection exactly once, instead of doing it constantly in a loop as in Server::run(). This allows any client code to model the client-socket selection as prefered.
   */
  private function loopOnce() {
    $this->select();
  }
}