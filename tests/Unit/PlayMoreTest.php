<?php
    require __DIR__ .'/../../vendor/autoload.php';


    class PlayMoreTest extends PHPUnit_Framework_TestCase
    {

        /**
         * Test the initialization
         */
        public function testInstance()
        {
            $pm = new \PlayMore\PlayMore();

            $this->assertInstanceOf(
                \PlayMore\PlayMore::class,
                $pm
            );
        }

    }