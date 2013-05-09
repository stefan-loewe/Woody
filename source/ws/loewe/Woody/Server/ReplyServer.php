<?php

namespace ws\loewe\Woody\Server;

use \ws\loewe\Woody\Components\Windows\AbstractWindow;
use \ws\loewe\Woody\Components\Timer\Timer;
use \ws\loewe\Utils\Sockets\ServerSocket;
use \ws\loewe\Utils\Sockets\Server;
use \ws\loewe\Utils\Logging\Logger;

class ReplyServer extends Server {
  /**
   * the window this server is associated with, normally this would be the main
   * window of the application
   *
   * @var \ws\loewe\Woody\Components\Windows\AbstractWindow
   */
  private $window = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param AbstractWindow $window the window associated with the server, normally the main window of the application
   * @param int $port the port the server is bound to.
   */
  public function __construct(AbstractWindow $window, $port) {
    parent::__construct('127.0.0.1', $port, 0);

    $this->window = $window;
  }

  /**
   * @inheritdoc
   *
   * In this simple example, the message of the client is reveresed and sent back.
   */
  protected function processClient(ServerSocket $clientSocket) {
    $message = trim($clientSocket->read(2048));

    Logger::log(Logger::DEBUG, 'client said: '.$message);

    $debug = FALSE;
    if($debug) {
      $answer = '<html><h1>Hello Visitor</h1><br><img src="A1.png"></html>';
    }
    else {
      $answer = $this->operation($message);
    }
    
    $clientSocket->write($answer);

    Logger::log(Logger::DEBUG, 'server replied '.$answer);

    $this->disconnectClient($clientSocket);
  }

  /**
   * This is just a dummy operation for demonstration purposes, reversing the
   * input string and returning it.
   *
   * @param string $input the input string
   * @return string the input string reversed
   */
  private function operation($input) {
    return strrev($input);
  }

  public function start($interval = 1000) {
    $this->isRunning = TRUE;

    $callback = function() {
      $this->loopOnce();
    };

    $timer = new Timer($callback, $this->window, $interval);

    $timer->start();
  }

  /**
   * This method performs the client-socket selection exactly once, instead of
   * doing it constantly in a loop as in Server::run(). This allows any client
   * code to model the client-socket selection as prefered.
   */
  private function loopOnce() {
    $this->select();
  }
}