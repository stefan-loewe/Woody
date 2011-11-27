<?php

namespace Utils\Sockets;

use Utils\Logging\Logger;

/**
 * This class is an implementation of the abstract Server (@see Utils\Sockets\Server) solely for testing purposes.
 */
class TestServer extends Server
{
    /**
     * This method acts as the constructor of the class.
     *
     * @param int $port the port the server is bound to.
     */
    public function __construct($port)
    {
        parent::__construct($port);
    }

    /**
     * This method allows testing of this server.
     *
     * @param ServerSocket $clientSocket
     */
    protected function processClient(ServerSocket $clientSocket)
    {
        $message = trim($clientSocket->read(1024));
        Logger::log(Logger::INFO, 'client said: '.$message);

        $result = '';
        if($message === 'time')
        {
            $result = '<html><body><span style="background-color:blue; font:red">'.time().'</span></body></html>';
            Logger::log(Logger::INFO, $result);
            //sleep(rand(1, 5));
        }
        else if($message === 'tame')
        {
            $result = date('Ymd');
            //sleep(rand(1, 5));
        }
        else if($message === 'terminate')
        {
            Logger::log(Logger::INFO, 'disconnecting ...');
            $this->disconnectClient($clientSocket);
        }
        else
            $result = 'wooot?';

        if($result)
        {
            Logger::log(Logger::INFO, 'replying ...');
            $clientSocket->write($result);
        }
    }
}