<?php
namespace libraries;

use libraries\exception\ConfigException;
use libraries\http\HuiyuRequest;
use libraries\library\huiyu\Options;
use libraries\messages\Codes;

/**
 * Class Huiyu
 * @package libraries
 */
class Huiyu
{
    /**
     * Huiyu constructor.
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

        if(!isset($configs['app_code']) || empty($configs['app_code']))
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));

        if(!isset($configs['app_key']) || empty($configs['app_key']))
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));

        if(!isset($configs['account_id']) || empty($configs['account_id']))
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));

        Options::$APPNAME = $configs['app_name'];
        Options::$APPCODE = $configs['app_code'];
        Options::$KEY = $configs['app_key'];
        Options::$ACCOUNTID = $configs['account_id'];

        if(isset($configs['logPath']) && !empty($configs['logPath'])) {
            Options::$LOGPATH = $configs['logPath'];
        }
    }

    /**
     * @return mixed
     */
    public function accounts() {
        $request = new HuiyuRequest();
        return json_decode($request->post(Options::ACCOUNTS, '{}', $this->header('{}')), true);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function words($data) {
        $request = new HuiyuRequest();
        return json_decode($request->post(Options::WORDS, json_encode($data), $this->header($data)), true);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function robots($data) {
        $request = new HuiyuRequest();
        return json_decode($request->post(Options::ROBOTS, json_encode($data), $this->header($data)), true);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create($data) {
        $request = new HuiyuRequest();
        return json_decode($request->post(Options::CREATE, json_encode($data), $this->header($data)), true);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function tasks($data) {
        $request = new HuiyuRequest();
        return json_decode($request->post(Options::CREATE, json_encode($data), $this->header($data)), true);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function stopPhone($data) {
        $request = new HuiyuRequest();
        return json_decode($request->post(Options::STOPPHONE, json_encode($data), $this->header($data)), true);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function detail($data) {
        $request = new HuiyuRequest();
        return json_decode($request->post(Options::DETAIL, json_encode($data), $this->header($data)), true);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function phoneDetail($data) {
        $request = new HuiyuRequest();
        return json_decode($request->post(Options::PHONELIST, json_encode($data), $this->header($data)), true);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function balance($data) {
        if(!isset($data['timespan'])) $data['timespan'] = time();
        $request = new HuiyuRequest();
        return json_decode($request->post(Options::BANLANCE, json_encode($data), $this->header($data)), true);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function voice($data) {
        $request = new HuiyuRequest();
        return $request->post(Options::VOICE, json_encode($data), $this->header($data));
    }

    /**
     * @param $data
     * @return string[]
     */
    public function header($data) {
        if(is_array($data)) $data = json_encode($data);

        return [
            'Content-Type:text/plain',
            'appcode:' . Options::$APPCODE,
            'sign:' . md5(join('|', [Options::$APPCODE, $data, Options::$KEY]))
        ];
    }
}