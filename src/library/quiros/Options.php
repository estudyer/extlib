<?php
namespace libraries\library\quiros;

/**
 * Class Options
 * @package libraries\library\quiros
 */
class Options
{
    static $CLIENT = 0;

    static $SECRET = '';

    static $ENV = 0;

    static $LOGPATH = '';

    const GRANT_TYPE = 'client_credentials';

    const TOKEN = [
        1 => 'http://66.96.249.34:8888/api/v1/oauth/token',
        2 => 'http://66.96.249.34:8888/api/v1/oauth/token',
        3 => 'http://66.96.249.34:8888/api/v1/oauth/token'
    ];
    const CALL = [
        1 => 'http://66.96.249.34:8888/api/v2/click2call',
        2 => 'http://66.96.249.34:8888/api/v2/click2call',
        3 => 'http://66.96.249.34:8888/api/v2/click2call'
    ];
    const LOGS = [
        1 => 'http://66.96.249.34:8888/api/v2/getResultCall',
        2 => 'http://66.96.249.34:8888/api/v2/getResultCall',
        3 => 'http://66.96.249.34:8888/api/v2/getResultCall'
    ];
    const PHONELOGS = [
        1 => 'http://66.96.249.34:8888/api/v2/getCDRByPhone',
        2 => 'http://66.96.249.34:8888/api/v2/getCDRByPhone',
        3 => 'http://66.96.249.34:8888/api/v2/getCDRByPhone'
    ];
    const LOGIDLOGS = [
        1 => 'http://66.96.249.34:8888/api/v2/getCDRByID',
        2 => 'http://66.96.249.34:8888/api/v2/getCDRByID',
        3 => 'http://66.96.249.34:8888/api/v2/getCDRByID'
    ];
    const DOCIDLOGS = [
        1 => 'http://66.96.249.34:8888/api/v2/getCDRByDocID',
        2 => 'http://66.96.249.34:8888/api/v2/getCDRByDocID',
        3 => 'http://66.96.249.34:8888/api/v2/getCDRByDocID'
    ];
    const DOWNLOAD = [
        1 => 'http://66.96.249.34:8888/api/v2/cdrdownload',
        2 => 'http://66.96.249.34:8888/api/v2/cdrdownload',
        3 => 'http://66.96.249.34:8888/api/v2/cdrdownload'
    ];
}