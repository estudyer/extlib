<?php
namespace libraries\messages;

/**
 * Class Messages
 * @package libraries\messages
 */
class Messages
{
    private static $messages = [
        Codes::CONFIG_FORMAT_INVALID    => '',
        Codes::QINIU_NOTFOUND           => '',
        Codes::QINIU_TOKEN_INVALID      => '',
        Codes::QINIU_FILESTRING_INVALID => '',
        Codes::QINIU_UPLOADFAILED       => '上传失败：error',
        Codes::EMAIL_SENDFAIL           => '发送失败：error'
    ];

    /**
     * @param $code
     * @param array $replaces
     * @return string|string[]
     */
    public static function get($code, $replaces = []) {
        $message = array_key_exists($code, self::$messages) ? self::$messages[$code] : '';

        foreach ($replaces as $key => $replace) {
            $message = str_replace($key, $replaces, $message);
        }

        return $message;
    }
}