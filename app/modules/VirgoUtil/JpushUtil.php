<?php

namespace VirgoUtil;
class JpushUtil {
	protected $appKey = '29dec5f933618755f9a80a1b';
	protected $masterSecret = '190736f23e276da58137c163';
	protected $userRegisterUrl = 'https://api.im.jpush.cn/v1/users/';

	public function __construct()
	{

	}

	public function doRegister($username, $password, $extra=[])
	{
		
		$authorization = $this->getAuthorization();

		$data[0]['username'] = $username;
		$data[0]['password'] = $password;
		if(!empty($extra)){
			foreach ($extra as $key => $value) {
				$data[0][$key] = $value;
			}
		}

		$headers = array(
			"Authorization: Basic ".$authorization,
			"Content-Type: application/json; charset=utf-8"
			);
		

		$rs = $this->doPost($this->userRegisterUrl, json_encode($data), $headers);
		
		return $this->parseRegisterReturn($rs);

	}

	/**
	* 解析用户注册返回
	* @param 	[$data]		注册返回
	*/
	public function parseRegisterReturn($data)
	{
		
		$temp['success'] = true;
		$temp['code'] = '001';
		$temp['message'] = 'Register OK';

		$returnArr = json_decode($data, true);
		if(!empty($returnArr['error'])){
			$temp['success'] = false;
			$temp['code'] = $returnArr['error']['code'];
			$temp['message'] = $returnArr['error']['message'];
		}

		return $temp;

	}

	/**
	* 获取验证
	*/
	public function getAuthorization()
	{
		$secretStr = $this->appKey.":".$this->masterSecret;
		return base64_encode($secretStr);
	}

	public function doPost($url, $data, $headers=[])
	{
		
		$ch = curl_init();
		CURL_SETOPT($ch, CURLOPT_URL, $url);
		CURL_SETOPT($ch, CURLOPT_POST, 1);
		CURL_SETOPT($ch, CURLOPT_POSTFIELDS, $data);
		CURL_SETOPT($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		if(!empty($headers)){
			CURL_SETOPT($ch, CURLOPT_HTTPHEADER,$headers);
		}

		$out = curl_exec($ch);

		curl_close($ch);
		return $out;

	}

}