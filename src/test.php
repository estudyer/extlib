<?php

/**
 *
 */
class Test {
	public function qiniu() {
		$config = [
			'host' => 'http://qiniu.qiniu.com',
			'bucket' => 'qiniubucket',
			'secret' => 'secret',
			'access' => 'access',
		];

		$qiniu = new Qiniu($config);

		$token = $qiniu->token();

		$file = $qiniu->createName('Actual/img');
		$content = file_get_contents(__DIR__ . '/img.txt');
		var_dump($qiniu->put($token, $content, $file));

		/*$content = __DIR__ . '/img.txt';
		var_dump($qiniu->putFile($token, $content, $file));*/
	}

	public function email() {
		$config = [
			'host' => 'smtp.163.com',
			'port' => '465',
			'name' => 'show name',
			'account' => 'account@163.com',
			'password' => 'password',
			'receiver' => ['to@163.com'],
			'debug' => 0,
		];

		$email = new Email($config);

		//var_dump($email->send('hello test', 'bbbbbbbbbbbbbbbbbbbbbbbbb'));
		//var_dump($email->sendTo('hello test', 'bbbbbbbbbbbbbbbbbbbbbbbbb', 'to@qq.com'));
	}

	public function niuxin() {
		$config = [
			'appkey' => 'appkey',
			'secret' => 'secret',
		];

		$niuxin = new Niuxin($config);
		$token = $niuxin->token()['token'];
		/*$data = [
			            'orderid' => '11111sep1',
			            'phone' => '08111111111',
			            'username' => '622998590002',
			            'token' => $token
			        ];
		*/
		dd($niuxin->orderLog(['orderid' => '11111sep1']));
	}

	public function quiros() {
		$quiros = new Quiros([
			'client' => 'client_id',
			'secret' => 'client secret',
			'env' => 'env value',
		]);
		$token = $quiros->token();
		$token = $token['token_type'] . ' ' . $token['access_token'];
		$data = [
			'docid' => mtime(),
			'cusid' => rand(1, 1000),
			'outbound' => '08111111111',
			'optid' => 1,
			'extension' => 6210,
		];
		var_dump($quiros->call($data, $token));
	}

	public function saiduo() {
		$saiduo = new Saiduo([
			'app_name' => 'appname',
			'app_key' => 'appkey',
			'secret' => 'secret',
			'line' => 'line',
			'method' => 'single/group',
		]);

		$token = $saiduo->token()['data']['token'];
		//dd($saiduo->create([], $token));
		/*dd($saiduo->tasks([
	            'Number' => 10,
	            'Page' => 1,
	            'Type' => 'normal'
*/
		//dd($saiduo->detail('xxxxxxxxxxxxxxxxxxxxxx', $token));
		/*dd($saiduo->stop([
	            'Action' => 'Stop',
	            'TaskID' => 'xxxxxxxxxxxxxxxxxxxxxx'
*/
		/*dd($saiduo->voice([
			            'TaskID' => 'xxxxxxxxxxxxxxxxxxxxxx',
			            'Callee' => '+628111111111'
		*/
		/*dd($saiduo->stopPhone([
			            'CalleeNumber' => '+6281111111111',
			            'TaskID' => 'xxxxxxxxxxxxxxxxxxxxxx'
		*/
		dd($saiduo->voiceZip(['TaskID' => 'xxxxxxxxxxxxxxxxxxxxxx'], $token));
	}

	public function huiyu() {
		$huiyu = new Huiyu([
			'app_name' => 'appname',
			'app_code' => 'appcode',
			'app_key' => 'appkey',
			'account_id' => 'account_id',
		]);

		//dd($huiyu->accounts());
		//dd($huiyu->words(['accountId' => 1]));
		//dd($huiyu->robots(['accountId' => 1]));
		//dd($huiyu->create([]));
		/*dd($huiyu->tasks([
	            'beginTime' => date('Y-m-d H:i:s', strtotime('-1 days')),
	            'endTime' => date('Y-m-d H:i:s'),
	            'wordsTemplateId' => 0,
	            'page' => 1,
	            'pagesize' => 100
*/
		/*dd($huiyu->stopPhone([
			            'batchNo' => 'xxxxxxxxxx',
			            'phone' => '08111111111'
		*/
		/*dd($huiyu->detail([
			            'batchno' => 'xxxxxxxxxx',
			            'page' => 1,
			            'pagesize' => 10,
			            'rotation' => 0
		*/
		/*dd($huiyu->phoneDetail([
			            'batchno' => 'xxxxxxxxxxxx',
			            'phone' => '081111111111'
		*/
		dd($huiyu->balance([]));
		//dd($huiyu->voice(['uuid' => 'xxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxx']));
	}
}