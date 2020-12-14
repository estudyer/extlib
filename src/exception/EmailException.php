<?php
namespace libraries\exception;

use Throwable;

/**
 * Class EmailException
 * @package libraries\exception
 */
class EmailException extends Exceptions
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}