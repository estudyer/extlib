<?php
namespace libraries\exception;

use Throwable;

/**
 * Class QiniuException
 * @package libraries\exception
 */
class QiniuException extends Exceptions
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}