<?php

namespace Woody\Server;

use \Utils\Sockets\ServerSocket;
use \Utils\Sockets\CallbackServer;
use \Utils\Http\HttpRequest;

/**
 * This class implements a callback server for WinBinder HtmlControl.
 *
 * This class implements a callback server for WinBinder HtmlControl. As WinBinder HtmlControl cannot for interact with the main application in the ordinar way, they have to communicate with the main application through a socket connection. To achieve this, the main application starts an instance of this class, registers a callback that handles requests for any interactive HtmlControl.
 * The class uses a \SplObjectStorage collection for storing callback associated to an HtmlControl
 *
 * @todo this has to be moved over to the woody/FotoImporter project/namespace
 */
class HtmlControlServer extends CallbackServer
{
    /**
     * This method acts as the constructor of the class.
     *
     * @param int $port the port the server is bound to.
     */
    public function __construct($port)
    {
        parent::__construct('127.0.0.1', $port, 0);

        $this->callbacks = new \SplObjectStorage();
    }

    /**
     * This method registers a new callback for the given HtmlControl.
     *
     * @param \Woody\Components\Controls\HtmlControl $control the HtmlControl to which the callback has to be registered
     * @param \Closure $callback the callback to register
     * @return HtmlControlServer $this
     */
    public function register(\Closure $callback, HtmlControl $control)
    {
        $this->callbacks[$control] = $callback;

        return $this;
    }

    /**
     * This method unregisters the callback registered for the given HtmlControl.
     *
     * @param HtmlControl $control the HtmlControl for which the callback has to be removed.
     * @return HtmlControlServer $this
     */
    public function unregister(\Closure $callback, HtmlControl $control)
    {
        $this->callbacks->detach($control);

        return $this;
    }

    /**
     * This method actually executes the callbacks.
     *
     * @param ServerSocket the server socket of the connected client
     * @param HttpRequest $request the request to process
     */
    protected function executeCallback(ServerSocket $clientSocket, HttpRequest $request)
    {
        foreach($this->callbacks as $currentCallback)
            $currentCallback->__invoke($request);

        $clientSocket->write("goodbye " + $request->getKeyValuePairs()['id']);
    }

    /**
     * This method performs the client-socket selection exactly once, instead of doing it constantly in a loop as in Server::run(). This allows any client code to model the client-socket selection as prefered.
     */
    public function loopOnce()
    {
        $this->select();
    }
}