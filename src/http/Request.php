<?php

namespace libraries\http;

/**
 * Class Request
 * @package http
 */
class Request
{
    /**
     * @var int
     */
    protected $timeout = 30;

    /**
     * @var bool
     */
    protected $log = true;

    /**
     * @var array
     */
    protected $redirect = [
        CURLOPT_REFERER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 5,
    ];

    /**
     * @param $url
     * @param array $data
     * @param array $header
     * @param null $response
     * @return Response|mixed
     */
    public function get($url, $data = [], $header = [], $response = null)
    {
        $options = [
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HEADERFUNCTION => [$this, 'headerResponse'],
            //CURLOPT_WRITEFUNCTION   => [$this, 'responseInfo']
        ];

        $options = $this->redirect + $options;

        if (!empty($data)) {
            if (is_array($data)) {
                $data = http_build_query($data);
            }

            if (strpos($url, '?') !== false) {
                $url .= '&' . $data;
            } else {
                $url .= '?' . $data;
            }
        }

        return $this->exec($url, $options, $response);
    }

    /**
     * @param $url
     * @param $data
     * @param array $header
     * @param null $response
     * @return Response|mixed
     */
    public function post($url, $data, $header = [], $response = null)
    {
        $options = [
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HEADERFUNCTION => [$this, 'headerResponse'],
            //CURLOPT_WRITEFUNCTION   => [$this, 'responseInfo']
        ];

        return $this->exec($url, $options, $response);
    }

    public function delete($url, $data, $header = [], $response = null)
    {
        $options = [
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HEADERFUNCTION => [$this, 'headerResponse'],
            //CURLOPT_WRITEFUNCTION   => [$this, 'responseInfo']
        ];

        return $this->exec($url, $options, $response);
    }

    public function put($url, $data, $header = [], $response = null)
    {
        $options = [
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HEADERFUNCTION => [$this, 'headerResponse'],
            //CURLOPT_WRITEFUNCTION   => [$this, 'responseInfo']
        ];

        return $this->exec($url, $options, $response);
    }

    public function headerResponse($curl, $header)
    {
        //return 0; // 断开连接
        return strlen($header);
    }

    public function responseInfo($curl, $info)
    {
    }

    /**
     * @param $url
     * @param $options
     * @param null $responseHandle
     * @return Response|mixed
     */
    protected function exec($url, $options, $responseHandle = null)
    {
        $curl = curl_init($url);

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);

        if($this->log === true) {
            $this->log($curl, $options, $response);
        }

        $data = [
            'info' => curl_getinfo($curl),
            'options' => $options,
            'error' => [
                'errno' => curl_errno($curl),
                'error' => curl_error($curl)
            ]
        ];

        if (null !== $responseHandle) {
            if(is_array($responseHandle)) {
                curl_close($curl);
                return call_user_func_array($responseHandle, [$response, $data]);
            } else if(!$responseHandle instanceof Response) {
                curl_close($curl);
                return new $responseHandle($response, $data);
            } else if(is_callable($responseHandle)) {
                curl_close($curl);
                return $responseHandle($response, $data);
            } else if(!class_exists($responseHandle)) {
                curl_close($curl);
                return ['response' => $response, 'data' => $data];
            }
            $response = new $responseHandle($response, $curl);
        } else if (method_exists($this, 'response')) {
            curl_close($curl);
            return $this->response($response, $data);
        } else if (null === $responseHandle) {
            $response = new Response($response, $curl);
        }
        $response->options($options);

        curl_close($curl);

        return $response;
    }

    /**
     * @param $curl
     * @param $options
     * @param $result
     */
    protected function log($curl, $options, $result) {
        $path = __DIR__ . '/../logs/' . date('Y-m-d') . '/request/';
        if(!is_dir($path)) mkdir($path, 0777, true);

        if(!file_exists($path . 'request.file')) {
            file_put_contents($path . 'request.file', 'request_' . mtime());
        }

        $file = $path . file_get_contents($path . 'request.file') . '.log';
        if(is_file($file)) {
            if(filesize($file) > 5 * 1024 * 1024) {
                file_put_contents($path . 'request.file', 'request_' . mtime());
                $file = file_get_contents($path . 'request.file');
            }
        }

        $info = curl_getinfo($curl);
        $log = [
            '==================================[[START]]===============================',
            '时间：' . date('Y-m-d H:i:s') . '[' . mtime() . ']',
            '地址：' . $info['url'],
            'code：' . $info['http_code'],
            'params：' . json_encode($options[CURLOPT_POSTFIELDS]),
            'header：' . json_encode($options[CURLOPT_HTTPHEADER])
        ];
        if(strlen($result) <= 255) {
            $log[] = 'Result：' . $result;
        }

        if($info['http_code'] !== 200) {
            $log[] = 'Errno：' . curl_errno($curl) . ' Error：' . curl_error($curl);
        }
        $log[] = '==================================[[E N D]]===============================';

        $content = join(PHP_EOL, $log);
        error_log($content . PHP_EOL . PHP_EOL, 3, $file);
    }
}