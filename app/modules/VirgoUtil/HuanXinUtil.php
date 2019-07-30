<?php

namespace VirgoUtil;
class HuanXinUtil {
	// protected $orgName  = 'hanghuantech';
	// protected $appName  = 'lipei';
	protected $baseUrl = 'https://a1.easemob.com';
	// protected $clientId = 'YXA6x2NrQJRDEeagIJtZBNbg5A';
	// protected $clientSecret = 'YXA6etyznauh9sS0VsGclECZoCXL7eA';

	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
	}

	/**
	* 设置配置属性
	* @author 	xww
	* @param 	array 	$data
	* @return 	void
	*/
	public function setProperties($data)
	{
		foreach ($data as $key => $value) {
			$this->{$key} = $value;
		}
	}

	/**
	* 获取用户--单个
	*/
	public function getUser($username,$token)
	{
		$headers = array(
			"Authorization:Bearer ".$token
			);
		$userUrl = $this->baseUrl."/".$this->orgName."/".$this->appName."/users/".$username;
		return $this->doGet($userUrl, $headers);
	}

	/**
	* 注册用户
	*/
	public function registerUser($username, $password, $nickname=null)
	{
		$userUrl = $this->baseUrl."/".$this->orgName."/".$this->appName."/users";
		$headers = array(
			"Content-Type:application/json"
			);

		$data['username'] = $username;
		$data['password'] = $password;
		$data['nickname'] = is_null($nickname)? $username:$nickname;

		$rs = $this->doPost($userUrl, json_encode($data), $headers);
		return $rs;
		//$this->parseUser($rs);

	}

	/**
	* 解析用户注册
	*/
	public function parseUser($result)
	{
		
		// $resultArr = json_decode($result, true);
		// if(!empty($resultArr['error'])){
		// 	//输出
		// 	$messages = '环信用户注册失败';
		// 	error_log("header:".$resultArr['header']."\r\nbody:".$resultArr['body'], 3, $_SERVER['DOCUMENT_ROOT']."/huanxinLog/".time().".txt");
		// 	echo $this->functionObj->toAppJson(null, '027', $messages, false);
		// 	exit();
		// }

	}

	/**
	* 更新用户
	*/
	public function updateUserPwd($username, $password, $token)
	{
		$path = $this->baseUrl."/".$this->orgName."/".$this->appName."/users/".$username.'/password';
		$headers = array(
			"Authorization: Bearer ".$token
			);
		$data['newpassword'] = $password;
		$rs = $this->doPut($path, json_encode($data), $headers);
		return $rs;
	}

	/**
	* 删除用户
	*/
	public function deleteUser($username, $token)
	{
		$path = $this->baseUrl."/".$this->orgName."/".$this->appName."/users/".$username;
		$headers = array(
			"Authorization: Bearer ".$token
			);
		$rs = $this->doMethod($path, "DELETE", $headers);
		return $rs;
	}

	/**
	* 请求token
	*/
	public function requestToken()
	{
		
		$tokenUrl = $this->baseUrl."/".$this->orgName."/".$this->appName."/token";
		$headers = array(
			"Content-Type:application/json"
			);
		$data['grant_type'] = 'client_credentials';
		$data['client_id'] = $this->clientId;
		$data['client_secret'] = $this->clientSecret;

		return $this->doPost($tokenUrl, json_encode($data), $headers);

	}

	/**
	* 用来获取token
	*/
	public function getToken()
	{
		
		$token = \EloquentModel\HuanXinToken::take(1)->get()->toArray();
		if(empty($token)){
			$rs = $this->requestToken();
			$rsArr = json_decode($rs['body'], true);
			if(empty($rsArr['error'])){
				//OK

				//save
				$save['expire_time'] = $rsArr['expires_in'];
				$save['create_time'] = time();
				$save['token'] = $rsArr['access_token'];

				\EloquentModel\HuanXinToken::insert($save);

				return $rsArr['access_token'];
			} else{
				//输出
				$messages = '环信获取token失败';
				error_log("header:".$rs['header']."\r\nbody:".$rs['body'], 3, $_SERVER['DOCUMENT_ROOT']."/huanxinLog/".time().".txt");
				echo $this->functionObj->toAppJson(null, '027', $messages, false);
				exit();
			}
		} else {
			//判断过期
			if(($token[0]['create_time']+$token[0]['expire_time'])<=time()){
				$rs = $this->requestToken();
				$rsArr = json_decode($rs['body'], true);
				if(empty($rsArr['error'])){
					//OK

					//update
					$update['expire_time'] = $rsArr['expires_in'];
					$update['create_time'] = time();
					$update['token'] = $rsArr['access_token'];

					\EloquentModel\HuanXinToken::where("token",'=',$token[0]['token'])->update($update);

					return $rsArr['access_token'];
				} else{
					//输出
					$messages = '环信获取token失败';
					error_log("header:".$rs['header']."\r\nbody:".$rs['body'], 3, $_SERVER['DOCUMENT_ROOT']."/huanxinLog/".time().".txt");
					echo $this->functionObj->toAppJson(null, '027', $messages, false);
					exit();
				}
			} else {
				return $token[0]['token'];
			}

		}

	}

	public function doPost($url, $data, $headers=[])
	{
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		if(!empty($headers)){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		$out = curl_exec($ch);
		

		$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($out, 0, $headerSize);
		$rs['header'] = $header;
		$rs['body'] = substr($out, $headerSize);
		curl_close($ch);
		return $rs;

	}

	public function doPut($url, $data, $headers=[])
	{
		
		$ch = curl_init(); //初始化CURL句柄 
		curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出 
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); //设置请求方式 
		curl_setopt($ch,CURLOPT_HTTPHEADER,array("X-HTTP-Method-Override: PUT"));//设置HTTP头信息
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//设置提交的字符串
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		if(!empty($headers)){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		
		$out = curl_exec($ch);

		$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($out, 0, $headerSize);
		$rs['header'] = $header;
		$rs['body'] = substr($out, $headerSize);
		curl_close($ch);
		return $rs;
	}

	/**
	* 进行删除等其他操作
	*/
	public function doMethod($url, $method="GET", $headers=[], $data=[] )
	{
		
		$ch = curl_init(); //初始化CURL句柄 
		curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出 
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method); //设置请求方式 
		// curl_setopt($ch,CURLOPT_HTTPHEADER,array("X-HTTP-Method-Override: $method"));//设置HTTP头信息

		if( !empty($data) ) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//设置提交的字符串
		}

		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		if(!empty($headers)){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		
		$out = curl_exec($ch);

		$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($out, 0, $headerSize);
		$rs['header'] = $header;
		$rs['body'] = substr($out, $headerSize);
		curl_close($ch);
		return $rs;
	}

	public function doGet($url, $headers=[])
	{
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		if(!empty($headers)){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		$out = curl_exec($ch);
		

		$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($out, 0, $headerSize);
		$rs['header'] = $header;
		$rs['body'] = substr($out, $headerSize);
		curl_close($ch);
		return $rs;

	}

}