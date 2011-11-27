<?php

namespace Utils\Sockets;

/**
 * This class is a wrapper for a client socket, i.e. it can connect to a Server (@see Utils\Sockets\Server) or ServerSocket (@see Utils\Sockets\ServerSocket) and start communicating.
 */
class ClientSocket
{
    /**
     * the wrapped socket resource
     *
     * @var resource
     */
    protected $socket       = null;

    /**
     * the host address to which the socket is connecting
     *
     * @var string
     */
    protected $hostAddress  = null;

    /**
     * the port to which the socket is connecting
     *
     * @var int
     */
    protected $portNumber   = null;

    /**
     * the timeout after which trying to establish a connection is aborted
     *
     * @var int
     */
    protected $timeout   = 5;

    /**
     * This method acts as the constructor of the class.
     *
     * @param string $hostAddress the host address to which the socket will be connecting
     * @param int $portNumber the host port to which the socket will be connecting
     * @param int $timeout the timeout after which trying to establish a connection is aborted
     */
    public function __construct($hostAddress, $portNumber, $timeout = 5)
    {
        $this->hostAddress  = $hostAddress;

        $this->portNumber   = $portNumber;
        
        $this->timeout      = $timeout;
    }

    /**
     * This method connects the socket to the server socket.
     */
    public function connect()
    {
        $errnum = null;

        $errstr = null;

        $this->socket = fsockopen($this->hostAddress, $this->portNumber, $errnum, $errstr, $this->timeout);
    }

    /**
     * This method disconnects the socket from the server socket.
     *
     * @todo obviously highly dependent on the server socket
     */
    public function disconnect()
    {
        $this->send('');
    }

    /**
     * This methos sends a message to the server.
     *
     * @param string $message the message to send
     */
    public function send($message)
    {
        fwrite($this->socket, $message);
    }

    /**
     * This method reads a string, up to the given length, from the socket
     *
     * @param int $length the number of charachters to read from the socket
     * @return string the string read from the socket
     */
    public function read($length)
    {
        return fread($this->socket, $length);
    }

    /**
     * This method reads a complete line or a string up to the given length, what ever occurs first.
     *
     * @param int $length the maximal number of charachters to read from the socket
     * @return string the string read from the socket
     */
    public function readLine($length)
    {
        return fgets($this->socket, $length);
    }

    /**
     * This method closes the socket connection.
     */
    public function close()
    {
        Logger::log(Logger::INFO, 'closing socket ...');

        fclose($this->socket);
    }

    /**
     * This method sets the socket to non-blocking mode.
     */
    public function setNonBlocking()
    {
        stream_set_blocking($this->socket, 0);
    }
}