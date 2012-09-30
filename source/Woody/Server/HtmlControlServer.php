<?php

namespace ws\loewe\Woody\Server;

use \ws\loewe\Woody\Components\Windows\AbstractWindow;
use \ws\loewe\Woody\Components\Controls\HtmlControl;
use \ws\loewe\Woody\Components\Timer\Timer;
use \ws\loewe\Woody\Event\EventFactory;
use \ws\loewe\Woody\Event\EventInfo;
use \ws\loewe\Utils\Sockets\ServerSocket;
use \ws\loewe\Utils\Sockets\Server;
use \ws\loewe\Utils\Http\HttpRequestFactory;
use \ws\loewe\Utils\Logging\Logger;

/**
 * This class implements a HTTP server for WinBinder HtmlControl.
 *
 * This class implements a HTTP server for WinBinder HtmlControl. As WinBinder
 * HtmlControl cannot interact with the main application in the ordinar way,
 * they have to communicate with the main application through a socket
 * connection. To achieve this, the main application starts an instance of this
 * class, registers the HtmlControls, which are notified when a new HTTP
 * request read.
 */
class HtmlControlServer extends Server {
  /**
   * the window this server is associated with, normally this would be the main
   * window of the application
   *
   * @var \ws\loewe\Woody\Components\Windows\AbstractWindow
   */
  private $window = null;

  /**
   * the collection of HtmlControls being registered
   *
   * @var \SplObjectStorage
   */
  private $htmlControls = null;

  /**
   * This method acts as the constructor of the class.
   *
   * @param AbstractWindow $window the window associated with the server, normally the main window of the application
   * @param int $port the port the server is bound to.
   */
  public function __construct(AbstractWindow $window, $port) {
    parent::__construct('127.0.0.1', $port, 0);

    $this->window = $window;

    $this->htmlControls = new \SplObjectStorage();
  }

  /**
   * This method registers a given HtmlControl.
   *
   * @param \ws\loewe\Woody\Components\Controls\HtmlControl $control the HtmlControl to
   * be registered
   *
   * @return HtmlControlServer $this
   */
  public function register(HtmlControl $control) {
    $this->htmlControls[$control] = TRUE;

    return $this;
  }

  /**
   * This method unregisters the given HtmlControl.
   *
   * @param HtmlControl $control the HtmlControl to be removed
   * @return HtmlControlServer $this
   */
  public function unregister(HtmlControl $control) {
    $this->htmlControls->detach($control);

    return $this;
  }

  /**
   * @inheritdoc
   *
   * It reads upto 2048 bytes, packs it into a respective HTTPRequest object,
   * and routes it to all registered HtmlControls.
   */
  protected function processClient(ServerSocket $connectedClient) {
    $message = trim($connectedClient->read(2048));

    Logger::log(Logger::DEBUG, 'client said: '.$message);

    // foreach registered HtmlControl, create an event,
    // and pass it to that HtmlControl
    foreach($this->htmlControls as $htmlControl) {
      $eventInfo = new EventInfo(0,
        $htmlControl->getID(),
        $htmlControl,
        $connectedClient,
        HttpRequestFactory::createRequest($message));
      
      $events = EventFactory::createEvent($eventInfo);
      
      foreach($events as $event) {
        $event->dispatch($event);
      }
    }

    $this->disconnectClient($connectedClient);
  }

  public function start($interval = 1000) {
    $this->isRunning = TRUE;

    $timer = new Timer(
      function() {
        $this->loopOnce();
      },
      $this->window,
      $interval
    );

    $timer->start();

    return $this;
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