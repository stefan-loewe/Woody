<?php

namespace Utils\Sockets;

use Utils\Logging\Logger;
use Utils\Http\HttpRequestFactory;
use Utils\Http\HttpRequest;

/**
 * This class implements a simple callback server.
 *
 * This class implements a simple callback server, i.e. callback can be registered, which then get the received requests as input. The callbacks are executed in order of their registration, and it is the responsibility of the callbacks to do something meaningful.
 */
class CallbackServer extends Server
{
    /**
     * collection of closures that are registered with the CallbackServer
     *
     * @var ArrayObject
     */
    private $callbacks = null;

    /**
     * This method acts as the constructor of the class
     *
     * @param string $address the address to which the socket is bound
     * @param int $port the port to which the socket is bound
     * @param int $timeout the amount of seconds before a timeout occurs
     */
    public function __construct($address, $port, $timeout)
    {
        parent::__construct($address, $port, $timeout);

        $this->callbacks = new \ArrayObject();
    }

    /**
     * This method registers a new callback for the server.
     *
     * @param \Closure $callback the callback to register
     * @return CallbackServer this callback server
     */
    public function register(\Closure $callback)
    {
        $this->callbacks[] = $callback;

        return $this;
    }

    /**
     * This method unregisters a callback from the server.
     *
     * @param \Closure $callback the callback to unregister
     * @return CallbackServer this callback server
     */
    public function unregister(\Closure $callback)
    {
        foreach($this->callbacks as $index => $currentCallback)
        {
            if($currentCallback === $callback)
                $this->callbacks->offsetUnset($index);
        }

        return $this;
    }

    /**
     * @inheritdoc
     *
     * It reads upto 2048 bytes, packs it into a respective HTTPRequest object, and executes the registered callbacks - as long no callback returns false. If a callback does return false, no more callbacks will be called, and the server's main loop stops.
     */
    protected function processClient(ServerSocket $clientSocket)
    {
        $message = trim($clientSocket->read(2048));

        Logger::log(Logger::DEBUG, 'client said: '.$message);

        $request = HttpRequestFactory::createRequest($message);

        foreach($this->callbacks as $currentCallback)
        {
            if(($this->isRunning = $currentCallback->__invoke($request)))
                break;
        }

        $this->disconnectClient($clientSocket);
    }
}