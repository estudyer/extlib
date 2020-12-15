<?php
namespace libraries;

use libraries\exception\ConfigException;
use libraries\http\SaiduoRequest;
use libraries\library\saiduo\Options;
use libraries\messages\Codes;

/**
 * Class Saiduo
 * @package libraries
 */
class Saiduo
{
    /**
     * Saiduo constructor.
     * @param $configs
     * @throws ConfigException
     */
    public function __construct($configs)
    {
        if (empty($configs) || !is_array($configs)) {
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));
        }

        if(!isset($configs['app_name']) || empty($configs['app_name']))
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));

        if(!isset($configs['app_key']) || empty($configs['app_key']))
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));

        if(!isset($configs['secret']) || empty($configs['secret']))
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));

        if(!isset($configs['line']) || empty($configs['line']))
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));

        if(!isset($configs['method']) || empty($configs['method']))
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));

        Options::$APPNAME = $configs['app_name'];
        Options::$APPKEY = $configs['app_key'];
        Options::$SECRET = $configs['secret'];
        Options::$LINE = $configs['line'];
        Options::$METHOD = $configs['method'];

        if(isset($configs['logPath']) && !empty($configs['logPath'])) {
            Options::$LOGPATH = $configs['logPath'];
        }
    }

    /**
     * @return http\Response|mixed
     */
    public function token() {
        $request = new SaiduoRequest();
        return json_decode($request->post(Options::TOKEN, [
            'APPKey' => Options::$APPKEY,
            'APPSecret' => Options::$SECRET
        ]), true);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create($data, $token) {
        $data['SIPLine'] = Options::$LINE;
        $data['RobotMethod'] = Options::$METHOD;
        $request = new SaiduoRequest();
        return json_decode($request->post(Options::CREATE, json_encode($data), ['Authorization:Token ' . $token]), true);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function stop($data, $token) {
        $request = new SaiduoRequest();
        return json_decode($request->post(Options::STOP, json_encode($data), ['Authorization:Token ' . $token]), true);
    }

    /**
     * @param $data
     * @param $token
     * @return mixed
     */
    public function stopPhone($data, $token) {
        $request = new SaiduoRequest();
        return json_decode($request->post(Options::STOPPHONE, json_encode($data), ['Authorization:Token ' . $token]), true);
    }

    /**
     * @param $data
     * @param $token
     * @return mixed
     */
    public function tasks($data, $token) {
        $request = new SaiduoRequest();
        return json_decode($request->get(Options::LISTTASK, $data, ['Authorization:Token ' . $token]), true);
    }

    /**
     * @param $taskId
     * @param $token
     * @return mixed
     */
    public function detail($taskId, $token) {
        $request = new SaiduoRequest();
        return json_decode($request->get(Options::DETAIL, [
            'TaskID' => $taskId
        ], ['Authorization:Token ' . $token]), true);
    }

    /**
     * @param $data
     * @param $token
     * @return mixed
     */
    public function voiceZip($data, $token) {
        $request = new SaiduoRequest();
        return json_decode($request->get(Options::VOICEZIP, $data, ['Authorization:Token ' . $token]), true);
    }

    /**
     * @param $data
     * @param $token
     * @return mixed
     */
    public function voice($data, $token) {
        $request = new SaiduoRequest();
        return json_decode($request->get(Options::VOICE, $data, ['Authorization:Token ' . $token], [$request, 'voiceResponse']), true);
    }
}