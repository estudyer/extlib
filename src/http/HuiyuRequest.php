<?php
namespace libraries\http;

use libraries\library\huiyu\Options;

/**
 * Class HuiyuRequest
 * @package libraries\http
 */
class HuiyuRequest extends Request
{
    public function response($response, $data) {
        if($data['info']['http_code'] !== 200) {
            $this->errorLog($response, $data);
            return $response;
        }

        return $response;
    }

    /**
     * @param $response
     * @param $data
     */
    public function errorLog($response, $data) {
        $path = !empty(Options::$LOGPATH) ? Options::$LOGPATH : __DIR__ . '/../logs/' . date('Y-m-d') . '/huiyu/';
        if(!is_dir($path)) mkdir($path, 0777, true);

        if(!file_exists($path . 'huiyu.file')) {
            file_put_contents($path . 'huiyu.file', 'huiyu_' . mtime());
        }

        $file = $path . file_get_contents($path . 'huiyu.file') . '.log';
        if(is_file($file)) {
            if(filesize($file) > 5 * 1024 * 1024) {
                file_put_contents($path . 'huiyu.file', 'huiyu_' . mtime());
                $file = file_get_contents($path . 'huiyu.file');
            }
        }

        $info = $data['info'];
        $log = [
            '==================================[[START]]===============================',
            '时间：' . date('Y-m-d H:i:s') . '[' . mtime() . ']',
            '地址：' . $info['url'],
            'code：' . $info['http_code'],
            'params：' . json_encode($data['options'][CURLOPT_POSTFIELDS]),
            'header：' . json_encode($data['options'][CURLOPT_HTTPHEADER])
        ];
        if(strlen($response) <= 255) {
            $log[] = 'Result：' . $response;
        }

        if($info['http_code'] !== 200) {
            $log[] = 'Errno：' . $data['error']['errno'] . ' Error：' . $data['error']['error'];
        }
        $log[] = '==================================[[E N D]]===============================';

        $content = join(PHP_EOL, $log);
        error_log($content . PHP_EOL . PHP_EOL, 3, $file);
    }
}