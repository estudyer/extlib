<?php
namespace libraries\library\niuxin;

class Options {
	static $APPKEY = '';

	static $SECRET = '';

	static $LOGPATH = '';

	const TOKEN = 'https://as01.nxcloud.com/v1/call/token';

	const CALL = 'https://as01.nxcloud.com/v1/call/makecall';

	const HANGUP = 'https://as01.nxcloud.com/v1/call/killcall';

	const ORDERLOGS = 'https://api.nxcloud.com/api/voiceSms/getSipCdrByOrderid';
}