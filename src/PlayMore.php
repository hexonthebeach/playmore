<?php
namespace PlayMore;

/**
 * Class PlayMore
 *
 * Library for managing a Claymore miner
 */
class PlayMore extends ClayCom
{
    const FILE_CONFIG = 'config.txt';
    const FILE_DPOOLS = 'dpools.txt';
    const FILE_EPOOLS = 'epools.txt';

    /**
     * get status from the rig
     * @return mixed $response
     * @throws \Exception
     */
    public function status()
    {
        return $this->miner_getstat1();
    }


    /**
     * get details from the rig
     * @return mixed $response
     * @throws \Exception
     */
    public function details()
    {
        return $this->miner_getstat2();
    }


    /**
     * restart the mining process
     * @return bool
     */
    public function restart()
    {
        return $this->miner_restart();
    }


    /**
     * reboots the mining rig
     * @return bool
     */
    public function reboot()
    {
        return $this->miner_reboot();
    }


    /**
     * set mining mode for one or all GPUs
     * @param int $gpu - index key or -1 for all
     * @param int $mode - disable, eth-only or dual (use GPUMode class)
     * @return bool
     */
    public function setGpuMode(int $gpu, int $mode)
    {
        return $this->control_gpu($gpu, $mode);
    }


    /**
     * get the config file
     * @return string file content
     * @throws Exceptions\FileNotFoundException
     */
    public function config()
    {
        return $this->miner_getfile(self::FILE_CONFIG);
    }


    /**
     * set new content for the config file
     * @param string $content
     * @return bool
     */
    public function setConfig(string $content)
    {
        return $this->miner_file(self::FILE_CONFIG, $content);
    }


    /**
     * get the epools file, containing ethereum pool details
     * @return string file content
     * @throws Exceptions\FileNotFoundException
     */
    public function epools()
    {
        return $this->miner_getfile(self::FILE_EPOOLS);
    }


    /**
     * set new content for the epools file, containing ethereum pool details
     * @param string $content
     * @return bool
     */
    public function setEpools(string $content)
    {
        return $this->miner_file(self::FILE_EPOOLS, $content);
    }


    /**
     * get the dpools file, containing second coin pool details
     * @return string file content
     * @throws Exceptions\FileNotFoundException
     */
    public function dpools()
    {
        return $this->miner_getfile(self::FILE_DPOOLS);
    }


    /**
     * set new content for the dpools file, containing second coin pool details
     * @param string $content
     * @return bool
     */
    public function setDpools(string $content)
    {
        return $this->miner_file(self::FILE_DPOOLS, $content);
    }


    /**
     * test if the connection to the Miner is available
     * @return bool
     */
    public function test()
    {
        return $this->claysocket->test();
    }
}