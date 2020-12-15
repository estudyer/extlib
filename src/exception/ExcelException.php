<?php
namespace libraries\exception;

use Throwable;

/**
 * Class ExcelException
 * @package libraries\exception
 */
class ExcelException extends Exceptions
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}