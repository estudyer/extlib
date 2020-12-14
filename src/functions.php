<?php

use libraries\messages\Messages;

if(!function_exists('mtime')) {
    /**
     * @return float
     */
    function mtime(): float
    {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }
}

if(!function_exists('lmsg')) {
    /**
     * @param $code
     * @param array $replaces
     * @return string|string[]
     */
    function lmsg($code, $replaces = []) {
        return Messages::get($code, $replaces);
    }
}