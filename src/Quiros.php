<?php
namespace libraries;

use libraries\exception\ConfigException;
use libraries\http\QuirosRequest;
use libraries\library\quiros\Options;
use libraries\messages\Codes;

/**
 * Class Quiros
 * @package libraries
 */
class Quiros
{
    /**
     * Quiros constructor.
     * @param $configs
     * @throws ConfigException
     */
    public function __construct($configs)
    {
        if (empty($configs) || !is_array($configs)) {
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));
        }

        if(!isset($configs['client']) || empty($configs['client']))
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));

        if(!isset($configs['secret']) || empty($configs['secret']))
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));

        if(!isset($configs['env']) || !in_array($configs['env'], [1, 2, 3]))
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));

        Options::$CLIENT = $configs['client'];
        Options::$SECRET = $configs['secret'];
        Options::$ENV = $configs['env'];

        if(isset($configs['logPath']) && !empty($configs['logPath'])) {
            Options::$LOGPATH = $configs['logPath'];
        }
    }

    /**
     * @return http\Response|mixed
     */
    public function token() {
        $data = [
            'client_id' => Options::$CLIENT,
            'client_secret' => Options::$SECRET,
            'scope' => '*',
            'grant_type' => Options::GRANT_TYPE
        ];
        $request = new QuirosRequest();
        return $request->post(Options::TOKEN[Options::$ENV], json_encode($data));
    }

    /**
     * @param $data
     * @param $token
     * @return http\Response|mixed
     */
    public function call($data, $token) {
        $request = new QuirosRequest();
        return $request->post(Options::CALL[Options::$ENV], json_encode($data), [
            'Authorization:' . $token,
            'Content-Type: application/json'
        ]);
    }
}