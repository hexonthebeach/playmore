<?php

namespace Feature;

use PHPUnit\Framework\TestCase;

require __DIR__ .'/../../vendor/autoload.php';

class MethodsTest extends TestCase
{

    /**
     * Restart command
     */
    public function testRestart()
    {
        $pm = new \PlayMore\PlayMore();
        $pm->connect('localhost');

        // should fail due to lack of miner
        $this->assertFalse(
            $pm->restart()
        );
    }

    /**
     * Reboot command
     */
    public function testReboot()
    {
        $pm = new \PlayMore\PlayMore();
        $pm->connect('localhost');

        // should fail due to lack of connected miner
        $this->assertFalse(
            $pm->reboot()
        );
    }

    /**
     * SetGPUMode command
     */
    public function testSetGPUMode()
    {
        $pm = new \PlayMore\PlayMore();
        $pm->connect('localhost');

        // should fail due to lack of connected miner
        $this->assertFalse(
            $pm->setGpuMode(
                0,
                \PlayMore\GPUMode::DUAL
            )
        );
    }

}