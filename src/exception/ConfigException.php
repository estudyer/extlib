<?php
namespace libraries\exception;

class ConfigException extends Exceptions {
	public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
	}
}