<?php
namespace libraries;

use libraries\exception\ConfigException;
use libraries\http\NiuxinRequest;
use libraries\library\niuxin\Options;
use libraries\messages\Codes;

/**
 * Class Niuxin
 * @package libraries
 */
class Niuxin
{

    /**
     * Niuxin constructor.
     * @param $configs
     * @throws ConfigException
     */
    public function __construct($configs)
    {
        if (empty($configs) || !is_array($configs)) {
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));
        }

        if (!isset($configs['appkey']) || empty($configs['appkey']))
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));

        if (!isset($configs['secret']) || empty($configs['secret']))
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));

        Options::$APPKEY = $configs['appkey'];
        Options::$SECRET = $configs['secret'];
        if(isset($configs['logPath']) && !empty($configs['logPath'])) {
            Options::$LOGPATH = $configs['logPath'];
        }
    }

    /**
     * @return http\Response|mixed
     */
    public function token()
    {
        $request = new NiuxinRequest();
        return $request->post(Options::TOKEN, [
            'appkey' => Options::$APPKEY,
            'secretkey' => Options::$SECRET
        ]);
    }

    /**
     * @param $data
     * @return http\Response|mixed
     */
    public function call($data)
    {
        $request = new NiuxinRequest();
        return $request->post(Options::CALL, $data);
    }

    /**
     * @param $data
     * @return http\Response|mixed
     */
    public function orderLog($data)
    {
        $data = ['appkey' => Options::$APPKEY, 'secretkey' => Options::$SECRET] + $data;
        $request = new NiuxinRequest();
        return $request->post(Options::ORDERLOGS, json_encode($data), ['Content-Type:application/json']);
    }
}