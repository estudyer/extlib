<?php
namespace libraries\http;

/**
 * Class Request
 * @package http
 */
class Request {
	/**
	 * @var int
	 */
	protected $timeout = 30;

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
	public function get($url, $data = [], $header = [], $response = null) {
		$options = [
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_TIMEOUT => $this->timeout,
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_HEADERFUNCTION => [$this, 'headerResponse'],
			//CURLOPT_WRITEFUNCTION   => [$this, 'responseInfo']
		];

		$options = $this->redirect + $options;

		if (!empty($data)) {
			if (!is_array($data)) {
				$data = http_build_query($url);
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
	public function post($url, $data, $header = [], $response = null) {
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

	public function delete($url, $data, $header = [], $response = null) {
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

	public function put($url, $data, $header = [], $response = null) {
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

	public function headerResponse($curl, $header) {
		//return 0; // 断开连接
		return strlen($header);
	}

	public function responseInfo($curl, $info) {}

	/**
	 * @param $url
	 * @param $options
	 * @param null $responseHandle
	 * @return Response|mixed
	 */
	protected function exec($url, $options, $responseHandle = null) {
		$curl = curl_init($url);

		curl_setopt_array($curl, $options);

		$response = curl_exec($curl);

		if (null === $responseHandle) {
			$response = new Response($response, $curl);
		} else {
			$response = new $responseHandle($response, $curl);
		}

		curl_close($curl);

		return $response;
	}
}