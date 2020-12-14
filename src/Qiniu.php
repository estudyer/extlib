<?php
namespace libraries;

use Exception;
use libraries\exception\ConfigException;
use libraries\exception\QiniuException;
use libraries\library\qiniu\Options;
use libraries\messages\Codes;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

/**
 * Class Qiniu
 * @package libraries
 */
class Qiniu {
	/**
	 * Qiniu constructor.
	 * @param $configs
	 * @throws ConfigException|QiniuException
	 */
	public function __construct($configs) {
		if (empty($configs) || !is_array($configs)) {
			throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));
		}

		if (empty($configs['host'])) {
			throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));
		}

		if (empty($configs['bucket'])) {
			throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));
		}

		if (empty($configs['secret'])) {
			throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));
		}

		if (empty($configs['access'])) {
			throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));
		}

		Options::$HOST = $configs['host'];
		Options::$BUCKET = $configs['bucket'];
		Options::$SECRET = $configs['secret'];
		Options::$ACCESS = $configs['access'];
	}

	/**
	 * @return mixed
	 */
	public function token() {
		$auth = new Auth(Options::$ACCESS, Options::$SECRET);

		return $auth->uploadToken(Options::$BUCKET);
	}

	/**
	 * @param $token
	 * @param $string
	 * @param null $toFile
	 * @return mixed
	 * @throws QiniuException
	 */
	public function put($token, $string, $toFile = null) {
		if (empty($token)) {
			throw new QiniuException(Codes::QINIU_TOKEN_INVALID);
		}

		if (empty($string)) {
			throw new QiniuException(Codes::QINIU_FILESTRING_INVALID);
		}

		if (null === $toFile || empty($toFile)) {
			$toFile = $this->createName();
		}

		try {
			$upload = new UploadManager();

			list($res, $err) = $upload->put($token, $toFile, $string);
		} catch (Exception $exception) {
			throw new QiniuException(lmsg(Codes::QINIU_UPLOADFAILED, ['error' => $exception->getMessage()]));
		}

		if ($err !== null) {
			throw new QiniuException(lmsg(Codes::QINIU_UPLOADFAILED, ['error' => $err]));
		}

		return $res;
	}

	/**
	 * @param $token
	 * @param $file
	 * @param null $toFile
	 * @return mixed
	 * @throws QiniuException
	 */
	public function putFile($token, $file, $toFile = null) {
		if (empty($token)) {
			throw new QiniuException(Codes::QINIU_TOKEN_INVALID);
		}

		if (empty($file) || !is_file($file)) {
			throw new QiniuException(Codes::QINIU_FILESTRING_INVALID);
		}

		if (null === $toFile || empty($toFile)) {
			$toFile = $this->createName();
		}

		try {
			$upload = new UploadManager();

			list($res, $err) = $upload->putFile($token, $toFile, $file);
		} catch (Exception $exception) {
			throw new QiniuException(lmsg(Codes::QINIU_UPLOADFAILED, ['error' => $exception->getMessage()]));
		}

		if ($err !== null) {
			throw new QiniuException(lmsg(Codes::QINIU_UPLOADFAILED, ['error' => $err]));
		}

		return $res;
	}

	/**
	 * @param string $path
	 * @return string
	 */
	public function createName($path = 'Actual', $suffix = '.jpg'): string{
		$name = date('Y-m-d') . DIRECTORY_SEPARATOR . mtime() . rand(1, 99999) . $suffix;
		return empty($path) ? $name : $path . DIRECTORY_SEPARATOR . $name;

	}
}