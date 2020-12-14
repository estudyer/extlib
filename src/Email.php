<?php
namespace libraries;

use libraries\exception\ConfigException;
use libraries\exception\EmailException;
use libraries\exception\Exceptions;
use libraries\library\email\Options;
use libraries\messages\Codes;
use PHPMailer\PHPMailer\{Exception, PHPMailer};

/**
 * Class Email
 * @package libraries
 */
class Email
{
    /**
     * @var PHPMailer
     */
    private $email;

    public function __construct($configs) {
        if (empty($configs) || !is_array($configs)) {
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));
        }

        if(!isset($configs['host']) || empty($configs['host']))
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));

        if(!isset($configs['account']) || empty($configs['account']))
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));

        if(!isset($configs['password']) || empty($configs['password']))
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));

        if(!isset($configs['name']) || empty($configs['name']))
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));

        if(!isset($configs['port']) || empty($configs['port']))
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));

        if(!isset($configs['receiver']) || empty($configs['receiver']))
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));

        if(!isset($configs['debug']))
            throw new ConfigException(lmsg(Codes::CONFIG_FORMAT_INVALID));

        Options::$HOST          = $configs['host'];
        Options::$ACCOUNT       = $configs['account'];
        Options::$PASSWORD      = $configs['password'];
        Options::$ACCOUNT_NAME  = $configs['name'];
        Options::$PORT          = $configs['port'];
        Options::$RECEIVER      = $configs['receiver'];
        Options::$DEBUG         = intval($configs['debug']);

        $this->iniEmail();
    }

    /**
     * @throws EmailException
     */
    private function iniEmail() {
        try {
            $this->email = new PHPMailer(true);
            $this->email->Host = Options::$HOST;
            $this->email->Port = Options::$PORT;
            $this->email->isSMTP();
            $this->email->CharSet = 'UTF8';
            $this->email->SMTPDebug = Options::$DEBUG;
            $this->email->Username = Options::$ACCOUNT;
            $this->email->Password = Options::$PASSWORD;
            $this->email->SMTPSecure = 'ssl';
            $this->email->SMTPAuth = true;
            $this->email->isHTML = true;
            $this->email->Timeout = 10;
            $this->email->setFrom(Options::$ACCOUNT, Options::$ACCOUNT_NAME);
        } catch (Exception $exception) {
            throw new EmailException($exception->getMessage());
        }
    }

    public function attachment($attachments) {
        if(is_array($attachments)) {
            foreach($attachments as $attachment) {
                $this->email->addAttachment($attachment);
            }
        } else {
            $this->email->addAttachment($attachments);
        }

        return $this;
    }

    /**
     * @param $subject
     * @param $content
     * @return bool
     * @throws EmailException
     */
    public function send($subject, $content) {
        try {
            $this->email->Subject = $subject;
            $this->email->Body = $content;
            $this->addReceiver(Options::$RECEIVER);
            return (bool) $this->email->send();
        } catch (Exception $exception) {
            throw new EmailException(lmsg(Codes::EMAIL_SENDFAIL, ['error' => $exception->getMessage()]));
        }
    }

    /**
     * @param $subject
     * @param $content
     * @param $to
     * @return bool
     * @throws EmailException
     */
    public function sendTo($subject, $content, $to) {
        try {
            $this->email->Subject = $subject;
            $this->email->Body = $content;
            $this->addReceiver($to);
            return (bool) $this->email->send();
        } catch (Exception $exception) {
            throw new EmailException(lmsg(Codes::EMAIL_SENDFAIL, ['error' => $exception->getMessage()]));
        }
    }

    /**
     * @param array $receivers
     * @return $this
     * @throws EmailException
     * @throws Exception
     */
    protected function addReceiver($receivers = []) {
        if(empty($receivers)) throw new EmailException(lmsg(Codes::EMAIL_SENDFAIL, ['error' => 'no receiver']));

        if(!is_array($receivers)) {
            $receivers = [$receivers];
        }

        try {
            foreach ($receivers as $receiver) {
                if(is_array($receiver)) $this->email->addAddress(reset($receiver), end($receiver));
                else $this->email->addAddress($receiver, $receiver);
            }
        } catch (Exception $exception) {
            throw new EmailException(lmsg(Codes::EMAIL_SENDFAIL, ['error' => 'add receiver failed']));
        }

        return $this;
    }
}