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

if(!function_exists('array_get')) {
    /**
     * @param $array
     * @param $key
     * @param null $default
     * @param null $deepKey
     * @return mixed|null
     */
    function array_get($array, $key, $default = null, $deepKey = null)
    {
        if (!is_array($array)) return $default;

        $keys = explode('.', $key);
        $length = count($keys);
        foreach ($keys as $index => $key) {
            if (!is_array($array) || !array_key_exists($key, $array)) return $default;

            $array = $array[$key];
            if ($length - 1 == $index) {
                if (null === $deepKey) return $array;

                return array_get($array, $deepKey, $default);
            }
        }

        return $default;
    }
}