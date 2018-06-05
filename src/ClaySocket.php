<?php
namespace PlayMore;

use PlayMore\Exceptions\ConnectionException;
use PlayMore\Exceptions\ReceiveException;
use PlayMore\Exceptions\SendException;

/**
 * Class ClaySocket
 *
 * this takes care of the communication over the socket connection
 */
class ClaySocket
{
    // keeps the socket connection
    private $socket;

    // keeps the endpoint properties
    private $host;
    private $port;

    // keeps any errors returned from the socket
    private $errno;
    private $errstr;

    // set connection timeout
    const CONN_TIMEOUT = 5;


    /**
     * ClaySocket constructor.
     *
     * @param $host
     * @param $port
     */
    public function __construct($host, $port)
    {
        // set the host and port
        $this->host = $host;
        $this->port = $port;
    }


    /**
     * open the connection with the provided host and port
     * @throws ConnectionException
     */
    public function connect()
    {
        // if there is no connection yet
        if( !$this->socket ) {
            // open it
            $this->socket = fsockopen($this->host, $this->port, $this->errno, $this->errstr, self::CONN_TIMEOUT);
        }

        // if there is no socket or an error occurred very recently
        if( !$this->socket || $this->errno ){
            // throw a connection error
            throw new ConnectionException($this->errstr, $this->errno);
        }
    }


    /**
     * close the connection if it is still open
     *
     * @return bool
     */
    public function close()
    {
        // reset the connection error tracking variables
        $this->errno;
        $this->errstr;

        // see if closing the connection is needed
        if( $this->socket && fclose($this->socket) ){
            $this->socket = false;
        }

        // return success of the action
        return !boolval($this->socket);
    }


    /**
     * test the connection
     * @return bool
     * @throws ConnectionException
     */
    public function test()
    {
        // make sure there is an open connection
        $this->connect();

        // check connection
        return boolval($this->socket);
    }


    /**
     * send a command, and return the response
     * @param $message
     * @return string $response
     * @throws ConnectionException
     * @throws SendException
     */
    public function send($message)
    {
        // make sure there is an open connection
        $this->connect();

        // write data to socket
        $result = fwrite($this->socket, $message, strlen($message));

        // fwrite returns falls on error
        if( !$result ){
            throw new SendException('Sending command failed.');
        }

        // return the write-result
        return $result;
    }


    /**
     * receive an incoming message
     *
     * @return bool|string
     * @throws ReceiveException
     * @throws ConnectionException
     */
    public function receive()
    {
        // make sure there is an open connection
        $this->connect();

        // catch an incoming message
        $response = fgets($this->socket);

        // fgets returns falls on error
        if( !$response ){
            throw new ReceiveException('Reading response failed.');
        }

        // return the response
        return $response;
    }
}