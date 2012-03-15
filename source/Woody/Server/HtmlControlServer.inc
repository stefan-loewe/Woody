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

/**
 * This class implements a HTTP server for WinBinder HtmlControl.
 *
 * This class implements a HTTP server for WinBinder HtmlControl. As WinBinder HtmlControl cannot interact with the main application in the ordinar way, they have to communicate with the main application through a socket connection. To achieve this, the main application starts an instance of this class, registers the HtmlControls, which are notified when a new HTTP request read.
 */
class HtmlControlServer extends Server {
    /**
     * the window this server is associated with, normally this would be the main window of the application
     *
     * @var \Woody\Components\Windows\AbstractWindow
     */
    private $window         = null;

    /**
     * the collection of HtmlControls being registered
     *
     * @var \SplObjectStorage
     */
    private $htmlControls   = null;

    /**
     * This method acts as the constructor of the class.
     *
     * @param int $port the port the server is bound to.
     */
    public function __construct(AbstractWindow $window, $port) {
        parent::__construct('127.0.0.1', $port, 0);

        $this->window       = $window;

        $this->htmlControls = new \SplObjectStorage();
    }

    /**
     * This method registers a given HtmlControl.
     *
     * @param \Woody\Components\Controls\HtmlControl $control the HtmlControl to be registered
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
     * It reads upto 2048 bytes, packs it into a respective HTTPRequest object, and routes it to all registered HtmlControls.
     */
    protected function processClient(ServerSocket $clientSocket) {
        $message = trim($clientSocket->read(2048));

        Logger::log(Logger::DEBUG, 'client said: '.$message);

        foreach($this->htmlControls as $htmlControl) {
            var_dump('... creating event ...');
            \Woody\Event\EventFactory::createEvent(
                    //not public $htmlControl->getWindow()->getControlID(),
                    null,
                    $htmlControl->getID(),
                    $htmlControl->getControlID(),
                    $clientSocket,
                    $request = HttpRequestFactory::createRequest($message));
        }

        $this->disconnectClient($clientSocket);
    }

    /**
     * This method runs the server, asynchronous using a timer, selecting new connection with the given interval.
     */
    public function start($interval = 1000) {
        $this->isRunning = TRUE;

        $timer = new Timer(function() {
                                $this->loopOnce();
                            }, $this->window, $interval);

        $timer->start();

        return $this;
    }

    /**
     * This method performs the client-socket selection exactly once, instead of doing it constantly in a loop as in Server::run(). This allows any client code to model the client-socket selection as prefered.
     */
    private function loopOnce() {
        $this->select();
    }
}