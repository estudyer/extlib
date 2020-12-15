<?php
namespace libraries\library\huiyu;

/**
 * Class Options
 * @package libraries\library\huiyu
 */
class Options
{
    static $APPNAME = '';

    static $APPCODE = '';

    static $KEY = '';

    static $ACCOUNTID = '';

    static $LOGPATH = '';

    const ACCOUNTS = 'http://idapi.huiyuzhineng.com/open-api/getaccount';
    const WORDS = 'http://idapi.huiyuzhineng.com/open-api/getwordstemplate';
    const ROBOTS = 'http://idapi.huiyuzhineng.com/open-api/getrobot';
    const CREATE = 'http://idapi.huiyuzhineng.com/open-api/batch';
    const TASKS = 'http://idapi.huiyuzhineng.com/open-api/getbatchlist';
    const STOPPHONE = 'http://idapi.huiyuzhineng.com/open-api/singleCancelCall';
    const PHONELIST = 'http://idapi.huiyuzhineng.com/open-api/getcalldetail';
    const DETAIL = 'http://idapi.huiyuzhineng.com/open-api/getcalllist';
    const VOICE = 'http://idapi.huiyuzhineng.com/open-api/exportVoiceByUUID';
    const BANLANCE = 'http://idapi.huiyuzhineng.com/open-api/getbalance';
}