<?php
namespace PlayMore;

use PlayMore\Exceptions\ConnectionException;
use PlayMore\Exceptions\FileNotFoundException;
use PlayMore\Exceptions\ReceiveException;
use PlayMore\Exceptions\SendException;

/**
 * Class ClayCom
 *
 * this has all the knowledge of the available commands
 * how to format the commands and how to process the responses into usable values
 */
class ClayCom
{

    protected $claysocket;

    private $password;

    const DEFAULT_PASSWORD = false;
    const DEFAULT_PORT     = 3333;


    /**
     * Connect to a miner
     *
     * @param $host
     * @param $port
     * @param $password
     * @return bool
     */
    public function connect($host, $port = self::DEFAULT_PORT, $password = self::DEFAULT_PASSWORD)
    {
        // prepare a socket connection
        $this->claysocket = new ClaySocket($host, $port);

        // set a password if provided
        $this->password = $password;

        try{
            $this->claysocket->connect();
        }catch (ConnectionException $playMoreConnectionException){
            return false;
        }

        return true;
    }

    /**
     * Disconnect from a miner
     *
     * @return bool
     */
    public function disconnect()
    {
        return $this->claysocket->close();
    }


    /**
     * Get Statistics from the rig
     *
     * @return array
     * @throws \Exception
     */
    protected function miner_getstat1()
    {
        // set up the command
        $command = $this->command(__FUNCTION__);

        // send command
        $send = $this->claysocket->send($command);

        // receive a response
        $response = $this->claysocket->receive();

        // suddenly a wild array appears
        $array = json_decode($response, true, 5);

        $out = $this->process_getstat($array);

        return $out;
    }


    /**
     * Get Detailed Statitics from the rig
     *
     * @return array
     * @throws \Exception
     */
    protected function miner_getstat2()
    {
        // set up the command
        $command = $this->command(__FUNCTION__);

        // send command
        $send = $this->claysocket->send($command);

        // receive a response
        $response = $this->claysocket->receive();

        // suddenly a wild array appears
        $array = json_decode($response, true, 5);

        $out = $this->process_getstat($array, 2);

        return $out;
    }


    /**
     * Get the content of a specific file
     *
     * @param string $filename
     * @return string Content from the file
     * @throws FileNotFoundException
     */
    protected function miner_getfile($filename)
    {
        // set up the command
        $command = $this->command(__FUNCTION__, [$filename]);

        // send command
        $send = $this->claysocket->send($command);

        // receive a response
        $response = $this->claysocket->receive();

        // suddenly a wild array appears
        // [result] = {"id": 0, "result": ["config.txt", "23.....31"], "error": null}
        $array = json_decode($response, true, 5);

        if( $array['error'] != null ){
            throw new FileNotFoundException($array['error']);
        }

        return hex2bin($array['result'][1]);
    }


    /**
     * Set new content for a specific file
     *
     * @param $filename
     * @param $content
     * @return bool
     */
    protected function miner_file($filename, $content)
    {
        // set up the command
        $command = $this->command(__FUNCTION__, [
            $filename,
            bin2hex($content),
        ]);

        // send command
        return $this->claysocket->send($command);
    }


    /**
     * Restart the rig
     *
     * @return bool
     */
    protected function miner_restart()
    {
        // set up the command
        $command = $this->command(__FUNCTION__);

        // send command
        try {
            return $this->claysocket->send($command);

        }catch (SendException $sendException){
            return false;
        }
    }


    /**
     * Reboot the rig
     *
     * @return bool
     */
    protected function miner_reboot()
    {
        // set up the command
        $command = $this->command(__FUNCTION__);

        // send command
        try {
            return $this->claysocket->send($command);

        }catch (SendException $sendException){
            return false;
        }
    }


    /**
     * Set Mining mode per GPU
     *
     * @param int $gpu GPU index key
     * @param int $mode Mining mode
     * @return bool
     */
    protected function control_gpu($gpu, $mode)
    {
        // set up the command
        $command = $this->command(__FUNCTION__, [
            $gpu,
            $mode,
        ]);

        // send command
        try {
            return $this->claysocket->send($command);

        }catch (SendException $sendException){
            return false;
        }
    }


    /**
     * Return the command in json format
     *
     * @param string $method Method name
     * @param array $params Parameters needed for command
     * @return string JSON encoded command
     */
    private function command($method, $params = [])
    {
        // default minimal command object
        $return = new \stdClass();
        $return->id      = rand(0,1024);
        $return->jsonrpc = '2.0';
        $return->method  = $method;

        // check for available params
        if(count($params)){
            $return->params = $params;
        }

        // check for password
        if( $this->password !== self::DEFAULT_PASSWORD ){
            $return->psw = $this->password;
        }

        return json_encode($return);
    }


    /**
     * Process status and details responses
     *
     * @param $array
     * @param int $getstat
     * @return array
     * @throws \Exception
     */
    private function process_getstat($array, $getstat = 1)
    {
        // explode separated strings
        // 0 = miner version
        // 1 = minutes since start
        // 2 = eth details
        $array['result'][2] = explode(';', $array['result'][2]);
        // 3 = eth details per GPU
        $array['result'][3] = explode(';', $array['result'][3]);
        // 4 = second coin details
        $array['result'][4] = explode(';', $array['result'][4]);
        // 5 = second details per GPU
        $array['result'][5] = explode(';', $array['result'][5]);
        // 6 = temp and fan speed per GPU
        $array['result'][6] = explode(';', $array['result'][6]);
        // 7 = pool names
        $array['result'][7] = explode(';', $array['result'][7] . ';;', 2);
        // 8 = share details per coin
        $array['result'][8] = explode(';', $array['result'][8]);

        // if command was miner_getstat2 there are some more details
        if( $getstat == 2 ) {
            // 9 = eth accepted per gpu
            $array['result'][9] = explode(';', $array['result'][9]);
            // 10 = eth rejected per gpu
            $array['result'][10] = explode(';', $array['result'][10]);
            // 11 = eth invalid per gpu
            $array['result'][11] = explode(';', $array['result'][11]);
            // 12 = second coin accepted per gpu
            $array['result'][12] = explode(';', $array['result'][12]);
            // 13 = second coin rejected per gpu
            $array['result'][13] = explode(';', $array['result'][13]);
            // 14 = second coin invalid per gpu
            $array['result'][14] = explode(';', $array['result'][14]);
        }


        // process array into a readable format
        $out = [
            'miner' => [
                'version' => $array['result'][0],
                'running' => $array['result'][1],
                'started' => (new \DateTime())->sub( new \DateInterval('PT' . $array['result'][1] . 'M') ) ,
            ],

            'ethereum' => [
                'hashrate' => $array['result'][2][0] * 1000,
                'shares'   => $array['result'][2][1],
                'rejected' => $array['result'][2][2],
                'invalid'  => $array['result'][8][0],
                'pool'     => $array['result'][7][0],
                'pool_sw'  => $array['result'][8][1],
            ],

            'second_coin' => [
                'hashrate' => $array['result'][4][0] * 1000,
                'shares'   => $array['result'][4][1],
                'rejected' => $array['result'][4][2],
                'invalid'  => $array['result'][8][2],
                'pool'     => $array['result'][7][1],
                'pool_sw'  => $array['result'][8][3],
            ],

            'gpus' => [],

            'request' => $array['id'],
            'error'   => $array['error'],
        ];

        // gpu specific details
        for ( $i = 0; $i < count($array['result'][3]); $i++ ){
            $gpu = [
                'hashrate_eth' => $array['result'][3][$i] * 1000,
                'hashrate_second' => $array['result'][5][$i] * 1000,

                // temp and speed are paired so the keys need a little computation
                'temp'     => $array['result'][6][($i * 2)],
                'fanspeed' => $array['result'][6][($i * 2) + 1],
            ];

            // if command was miner_getstat2 there are some more details
            if( $getstat == 2 ){
                $gpu['accepted_eth'] = $array['result'][9][$i];
                $gpu['rejected_eth'] = $array['result'][10][$i];
                $gpu['invalid_eth']  = $array['result'][11][$i];
                $gpu['accepted_second'] = $array['result'][12][$i];
                $gpu['rejected_second'] = $array['result'][13][$i];
                $gpu['invalid_second']  = $array['result'][14][$i];
            }

            $out['gpus'][] = $gpu;
        }

        return $out;
    }

}