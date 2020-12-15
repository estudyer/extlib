<?php
namespace libraries\library\saiduo;

/**
 * Class Options
 * @package libraries\library\saiduo
 */
class Options
{
    static $APPNAME = '';

    static $APPKEY = '';

    static $SECRET = '';

    static $LINE = '';

    static $METHOD = 'single';

    static $LOGPATH = '';

    const TOKEN = 'https://api.airudder.com/service/cloud/auth';
    const CREATE = 'https://api.airudder.com/service/cloud/task';
    const STOP = 'https://api.airudder.com/service/cloud/operation';
    const STOPPHONE = 'https://api.airudder.com/service/cloud/cancelphonecall';
    const LISTTASK = 'https://api.airudder.com/service/cloud/list';
    const DETAIL = 'https://api.airudder.com/service/cloud/detail';
    const ROBOTS = 'https://api.airudder.com/service/cloud/robot';
    const VOICEZIP = 'https://api.airudder.com/service/cloud/recording';
    const VOICE = 'https://api.airudder.com/service/cloud/case';
}