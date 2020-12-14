<?php
namespace libraries\http;

class Response {
	$url 		= '';

	$code 		= 200;

	$error_no 	= 0;

	$errors		= '';

	$response   = '';

	$info 		= [];

	public function __construct($response, $ch = null) {
		if(null !== $ch && is_resource($ch)) {
			$this->iniInfo(curl_getinfo($ch));
			$this->iniError(curl_errno($ch), curl_error($ch));
		}

		$this->iniResponse($response);
	}

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

	public function iniInfo($info) {
		$this->url = $info['url'];
		$this->code = $info['http_code'];

		unset($info['url'], $info['http_code']);

		$this->info = $info;

		return $this;
	}

	public function iniError($errno, $error) {
		$this->errno = $errno;

		$this->errors = $error;

		return $this;
	}
}