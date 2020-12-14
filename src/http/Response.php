<?php
namespace libraries\http;

/**
 * Class Response
 * @package libraries\http
 */
class Response {
    /**
     * @var string
     */
	public $url = '';

    /**
     * @var int
     */
	public $code = 200;

    /**
     * @var int
     */
	public $error_no = 0;

    /**
     * @var string
     */
	public $errors = '';

    /**
     * @var string
     */
	public $response = '';

    /**
     * @var array
     */
	public $info = [];

    /**
     * @var array
     */
	public $options = [];

    /**
     * Response constructor.
     * @param $response
     * @param null $ch
     */
	public function __construct($response, $ch = null) {
		if(null !== $ch && is_resource($ch)) {
			$this->iniInfo(curl_getinfo($ch));
			$this->iniError(curl_errno($ch), curl_error($ch));
		}

		$this->iniResponse($response);
	}

    /**
     * @param $response
     * @return $this
     */
	public function iniResponse($response) {
		$this->response = $response;

		if(null === $response || empty($response)) {
			return $this;
		}

		if(json_encode(json_decode($response, true)) == $response) {
			$this->response = json_decode($response, true);
		}

		return $this;
	}

    /**
     * @param $info
     * @return $this
     */
	public function iniInfo($info) {
		$this->url = $info['url'];
		$this->code = $info['http_code'];

		unset($info['url'], $info['http_code']);

		$this->info = $info;

		return $this;
	}

    /**
     * @param $errno
     * @param $error
     * @return $this
     */
	public function iniError($errno, $error) {
		$this->errno = $errno;

		$this->errors = $error;

		return $this;
	}

    /**
     * @param $options
     * @return $this
     */
	public function options($options) {
	    $this->options = $options;

	    return $this;
    }
}