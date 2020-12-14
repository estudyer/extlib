<?php
namespace libraries\exception;

class Exceptions extends \Exception {
	protected $data = [];

	final public function setData($label, array $data) {
		$this->data[$label] = $data;
	}

	final public function getData() {
		return $this->data;
	}
}