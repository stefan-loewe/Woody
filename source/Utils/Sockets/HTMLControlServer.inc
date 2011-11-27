<?php

namespace Utils\Sockets;

/**
 * This class implements a callback server for WinBinder HTMLControls.
 *
 * This class implements a callback server for WinBinder HTMLControls. As WinBinder HTMLControls cannot for interact with the main application in the ordinar way, they have to communicate with the main application through a socket connection. To achieve this, the main application starts an instance of this class, registers a callback that handles requests for any interactive HTMLControl.
 *
 * @todo this has to be moved over to the woody/FotoImporter project/namespace
 */
class HTMLControlServer extends Server
{
    /**
     * collection of closures that are registered with the HTMLControlServer
     *
     * @var SplObjectStorage
     */
    private $callbacks = null;

    /**
     * This method acts as the constructor of the class.
     *
     * @param int $port the port the server is bound to.
     */
    public function __construct($port)
    {
        parent::__construct($port);

        $this->callbacks = new \SplObjectStorage();
    }

    /**
     * This method registers a new callback for the given HTMLControl.
     *
     * @param HTMLControl $control the HTMLControl to which the callback has to be registered
     * @param \Closure $callback the callback to register
     * @return CallbackServer this callback server
     */
    public function register(HTMLControl $control, \Closure $callback)
    {
        $this->callbacks[$control] = $callback;
    }

    /**
     * This method unregisters the callback registered for the given HTMLControl.
     *
     * @param HTMLControl $control the HTMLControl for which the callback has to be removed.
     */
    public function unregister(HTMLControl $control)
    {
        $this->callbacks->detach($control);
    }

    /**
     * This method processes a client request. In order to do so, the message of the request is passed in every registered callback, and the callback itself has to determine, if this request is of interest for it, and if so, process the request accordingly.
     *
     * @param ServerSocket $clientSocket the socket of the client request
     * @see HTTP\HttpRequest* for a better handling of HTTP requests
     * @see CallbackServer for a better implementation
     */
    protected function processClient(ServerSocket $clientSocket)
    {
        $message = trim($clientSocket->read(1024));

        $payload = $this->extractPayload($message);

        foreach($this->callbacks as $control)
            $this->callbacks[$control]->__invoke($payload);

        $this->disconnectClient($clientSocket);
    }

    /**
     * This method extracts the payload of (what is expected to be) a HTTP POST request.
     *
     * @param type $message the raw message of the request
     * @return string the POST string of the (what is expected to be) a HTTP POST request.
     * @todo might be redundant once HTTP\HttpRequest* is used, or class gets obsolete in favour of CallbackServer
     */
    private function extractPayload($message)
    {
        // get the position of the end of the HTTP header (delimited by a double line break)
        $doubleLineBreakPos = strpos($message, "\r\n\r\n");

        // right after the header, the POST string starts
        return substr($message, $doubleLineBreakPos + 4);
    }

    /**
     * This method extracts the parameters from a URL string.
     *
     * @param string $urlQuery the URL string from which the parameters are to be extracted
     * @return string[] the parameters as key-value pairs
     * @todo might be redundant once HTTP\HttpRequest* is used, or class gets obsolete in favour of CallbackServer
     */
    public static function getParameters($urlQuery)
    {
        $parameters = array();

        foreach(explode('&', $urlQuery) as $keyValuePair)
        {
            $keyValuePair = explode('=', $keyValuePair);
            $parameters[$keyValuePair[0]] = $keyValuePair[1];
        }

        return $parameters;
    }

    /**
     * This method performs the client-socket selection exactly once, instead of doing it constantly in a loop as in Server::run(). This allows any client code to model the client-socket selection as prefered.
     */
    public function loopOnce()
    {
        $this->select();
    }
}