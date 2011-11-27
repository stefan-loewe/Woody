<?php

namespace Utils\Sockets;

use Utils\Logging\Logger;

/**
 * This class is a wrapper for a server socket, i.e. a client (@see Utils\Sockets\ClientSocket) can connect to, and communication with.
 */
class ServerSocket
{
    /**
     * flag for PHP's internal socket_shutdown function, to shutdown socket for reading sockets
     *
     * @var int
     */
    protected static $SHUTDOWN_READING_SOCKETS  = 0;

    /**
     * flag for PHP's internal socket_shutdown function, to shutdown socket for writing sockets
     *
     * @var int
     */
    protected static $SHUTDOWN_WRITING_SOCKETS  = 1;

    /**
     * the wrapped socket resource of the ServerSocket
     *
     * @var resource
     */
    protected $handle                           = null;

    /**
     * This method acts as the constructor of the class.
     *
     * @param resource $handle an existing socket resource or null - in the later case, a new socket resource will be created.
     * @todo maybe not the best idea to pass in an existing resource - maybe find function that checks whether this is a socket or not
     */
    public function __construct($handle = null)
    {
        if($handle === null)
        {
            $handle = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

            if($handle === FALSE)
                throw new ServerSocketCreationFailedException($this);
            else
                $this->handle = $handle;
        }

        else if(is_resource($handle))
            $this->handle = $handle;
        else
            throw new \InvalidArgumentException('The handle must be either a valid stream socket resource or null');
    }

    /**
     * This method acts as the destructor of the class.
     */
    public function __destruct()
    {
        Logger::log(Logger::INFO, 'ServerSocket::__destruct() of resouce '.$this->handle);
        // DO NOT close the socket here, as the object might cease to exist, but the socket might still be open for a valid reason!
    }

    /**
     * This method returns the internal socket resource.
     *
     * @return resource
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * This method binds the socket to the given host address and port.
     *
     * @param type $hostAddress the host address to which the socket will be bound
     * @param type $portNumber the port to which the socket will be bound
     */
    public function bind($hostAddress, $portNumber)
    {
        Logger::log(Logger::INFO, 'binding socket to '.$hostAddress.':'.$portNumber);

        if(!socket_bind($this->handle, $hostAddress, $portNumber))
            throw new ServerSocketBindingFailedException($this);
    }

    /**
     * This method sets the socket to non-blocking.
     *
     * @return boolean true if successful, else false
     */
    public function setNonBlocking()
    {
        return socket_set_nonblock($this->handle);
    }

    /**
     * This method listens on a socket for new connection requests.
     *
     * @return boolean true if successful, else false
     * @todo make queue length parameter (currently hardcoded to 5)
     */
    public function listen()
    {
        return socket_listen($this->handle, 5);
    }

    /**
     * This method runs the select system call on the given arrays of sockets with the specified timeout.
     *
     * @param array $readingClients a reference to the array of reading clients of the socket
     * @param array $writingClients a reference to the array of writing clients of the socket
     * @param array $checkedClients a reference to the array of checked clients of the socket
     * @param int $timeout the timeout used for the select system call
     * @return type
     */
    public function select(array &$readingClients, array &$writingClients, array &$checkedClients, $timeout = null)
    {
        return socket_select($readingClients, $writingClients, $checkedClients, $timeout);
    }

    /**
     * This method accepts an incoming connection on the socket.
     *
     * @return mixed a new socket resource on success, else false
     */
    public function accept()
    {
        return socket_accept($this->handle);
    }

    /**
     * This method reads a string of the maximal given lenght from the socket.
     *
     * @param type $length the maximal length of the string to read
     * @return string the string read from the socket, or false on error
     */
    public function read($length)
    {
        return socket_read($this->handle, $length, PHP_BINARY_READ);
    }

    /**
     * This method reads a complete line from the socket.
     *
     * @param type $length the maximal length of the string to read
     * @return string the string read from the socket, or false on error
     */
    public function readLine($length)
    {
        return socket_read($this->handle, $length, PHP_NORMAL_READ);
    }

    /**
     * This method writes a string to the socket.
     *
     * @param string $string the string to write to the socket
     * @param int $length the maximal length of the string to write
     * @return int the number of bytes successfully written to the socket or false on failure
     */
    public function write($string, $length = null)
    {
        Logger::log(Logger::INFO, 'writing to socket: '.$string);

        $string = $string."\n";
        return socket_write($this->handle, $string, $length === null ? strlen($string) : $length);
    }

    /**
     * This method returns the error message associated with the last error that occured.
     *
     * @return string the error message associated with the last error that occured
     */
    public function getLastErrorMessage()
    {
        return socket_strerror(socket_last_error($this->handle));
    }

    /**
     * This method closes the socket connection.
     */
    public function close()
    {
        // see http://www.php.net/manual/en/function.socket-close.php#66810 for next two lines
        socket_set_block($this->handle);

        socket_set_option($this->handle, SOL_SOCKET, SO_LINGER, array('l_onoff' => 1, 'l_linger' => 1));

        // see http://www.php.net/manual/en/function.socket-shutdown.php#91021 for next two lines
        //$result = socket_shutdown($this->handle, self::$SHUTDOWN_WRITING_SOCKETS);

        //$result = socket_shutdown($this->handle, self::$SHUTDOWN_READING_SOCKETS);

        socket_close($this->handle);
    }
}