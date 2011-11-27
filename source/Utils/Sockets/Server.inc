<?php

namespace Utils\Sockets;

use Utils\Logging\Logger;

/**
 * This class can be used to build a custom socket server. It wraps a ServerSocket {@see Utils\Sockets\ServerSocket), which is being opened for connecting and allows for communication with that ServerSocket. The subclass only has to implement the abstract method <code>Server::processClient(ServerSocket $clientSocket)</code>, then <code>__contruct()</code> and <code>run()</code> the server, waiting for connections.
 */
abstract class Server
{
    /**
     * the socket of the server
     *
     * @var ServerSocket
     */
    protected $socket    = null;

    /**
     * the collection of clients currently connected to the server
     *
     * @var array[string]ServerSocket
     */
    protected $clients    = array();

    /**
     * the adress of the socket
     *
     * @var string
     */
    private $address    = '127.0.0.1';

    /**
     * the port of the socket
     *
     * @var int
     */
    private $port    = 9999;

    /**
     * the timeout of the socket-select call
     *
     * @var int
     */
    protected $timeout    = 0;

    /**
     * This method acts as the constructor of the class.
     *
     * @param string $address the address to which the server socket is bound to
     * @param int $port the port to which the server socket is bound to
     * @param int $timeout the timeout of the socket-select call
     */
    public function __construct($address, $port, $timeout)
    {
        $this->address = $address;

        $this->port    = $port;

        $this->timeout = $timeout;

        $this->socket  = new ServerSocket();

        $this->socket->bind($this->address, $this->port);

        $this->socket->listen();
    }

    /**
     * This method returns an array of the clients reading the server socket - including the server socket itself.
     *
     * @return resource[int]
     */
    private function getReadingClients()
    {
        $readingClients = array($this->socket->getHandle());

        foreach($this->clients as $currentClient)
            $readingClients[] = $currentClient->getHandle();

        return $readingClients;
    }

    /**
     * This method decides whether the given resource is the resource of the master socket itself.
     *
     * @param type $resource the resource to test
     * @return boolean true, if the given resource is the resource of the master socket, else false
     */
    private function isMasterSocketHandle($resource)
    {
        return $this->socket->getHandle() === $resource;
    }

    /**
     * This method adds the given client to the collection of connected clients.
     *
     * @param ServerSocket $client the client to add
     */
    protected function addClient(ServerSocket $client)
    {
        Logger::log(Logger::INFO, 'connecting client = '.(string)$client->getHandle());

        $this->clients[md5((string)$client->getHandle())] = $client;
    }

    /**
     * This method disconnects a given client from the server. Hence, it is also removed from the collection of collected clients.
     *
     * @param ServerSocket $client
     */
    protected function disconnectClient(ServerSocket $client)
    {
        Logger::log(Logger::INFO, 'disconnecting client = '.(string)$client->getHandle());

        $client->close();

        //if(!isset($this->clients[md5((string)$handle)]))
            //Logger::log(Logger::ERROR, 'tried to unregister not-existing client!');

        unset($this->clients[md5((string)$client->getHandle())]);
    }

    /**
     * This method shuts the server down, i.e. it disconnects each connected client, closes the server socket and exits the main loop.
     */
    protected function shutdown()
    {
        $this->isRunning = FALSE;

        //Logger::log(Logger::INFO, 'shutting down server ...!');
        foreach($this->clients as $client)
            $this->disconnectClient($client);

        //Logger::log(Logger::INFO, 'closing server socket');
        $this->socket->close();
    }

    /**
     * This method defines what to do, once a client is connected. Each subclass can implement this accordingly.
     *
     * @param ServerSocket $clientSocket the ServerSocket of the connecting client
     */
    abstract protected function processClient(ServerSocket $clientSocket);

    /**
     * This method runs the server, i.e. starts the main loop and waits for clients to connect.
     */
    public function run()
    {
        $this->isRunning = TRUE;

        while($this->isRunning)
            $this->select();
    }

    /**
     * This method performs the client-socket selection.
     */
    protected function select()
    {
        $readingClients = $this->getReadingClients();

        $writingClients = array();

        $checkedClients = $this->getReadingClients();

        if($this->socket->select($readingClients, $writingClients, $checkedClients, $this->timeout) !== FALSE)
        {
            Logger::log(Logger::INFO, 'new loop ... ');
            // the server socket is one of the reading sockets => new clients trying to connect
            if(in_array($this->socket->getHandle(), $readingClients))
            {
                Logger::log(Logger::INFO, 'new connection at master socket'.$this->socket->getHandle());

                if(($acceptedClient = $this->socket->accept()) === FALSE)
                    throw new ServerSocketAcceptionFailedException($this->socket);
                else
                    $this->addClient(new ServerSocket($acceptedClient));
            }

            // handle each reading client
            foreach($readingClients as $readingClient)
            {
                if(!$this->isMasterSocketHandle($readingClient))
                    $this->processClient($clientSocket = $this->clients[md5((string)$readingClient)]);
            }

            // handle each erroneous client
            foreach($checkedClients as $checkedClient)
            {
                Logger::log(Logger::ERROR, 'EXCEPTION!!!');
                $this->disconnectClient($this->clients[md5((string)$readingClient)]);
            }
        }
        else
            throw new ServerSocketSelectionFailedException($this->socket);
    }
}