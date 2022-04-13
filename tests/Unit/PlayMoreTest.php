<?php

namespace Unit;

require __DIR__ .'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

    class PlayMoreTest extends TestCase
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