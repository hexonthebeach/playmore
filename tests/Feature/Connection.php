<?php
require __DIR__ .'/../../vendor/autoload.php';


class Connection extends PHPUnit_Framework_TestCase
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

}