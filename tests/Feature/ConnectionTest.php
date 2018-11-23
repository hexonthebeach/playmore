<?php
require __DIR__ .'/../../vendor/autoload.php';


class ConnectionTest extends PHPUnit_Framework_TestCase
{

    /**
     * Connection result
     */
    public function testConnection()
    {
        $pm = new \PlayMore\PlayMore();

        $this->assertFalse(
            $pm->connect('localhost')
        );
    }

    /**
     * Disconnection result
     */
    public function testDisconnection()
    {
        $pm = new \PlayMore\PlayMore();
        $pm->connect('localhost');

        $this->assertTrue(
            $pm->disconnect()
        );
    }

    /**
     * Disconnection result
     */
    public function testConnectivityTest()
    {
        $pm = new \PlayMore\PlayMore();
        $pm->connect('localhost');

        $this->assertFalse(
            $pm->test()
        );
    }

}