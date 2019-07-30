<?php
/**
* php version 5.5.12
* @author  xww<5648*****@qq.com>
* @copyright  xww  20161214
* @version 1.0.0
*/

/*
* 函数列表
* 
* 验证api参数
* void configValid(array $configs)
*
* 输出结果给客户端
* void responseResult(string $return(json))
* 
* 去除用户输入非法字符
* array change()
* 
* 验证验证码是否正确以及超时
* void VerifyCaptchaThroughMD5File(string $number,string $captcha, int $time)
*/
namespace VirgoApi;
class ApiBaseController 
{
	
	/**
	* @var 自定义函数对象
	*/
	protected $functionObj;

	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
	}


	//判断token是否过期
	public function judgeUser($token, $username, $time=7200)
	{
		
		$rs = \EloquentModel\User::where('user_login', '=', $username)
				  ->where('access_token', '=', $token)
				  ->where("is_deleted", '=', 0)
		          ->get()
		          ->toArray();
		
		if(!empty($rs)){
			if($rs[0]['token_expire_time']<=time()){
				//token失效
				$data['access_token'] = $this->getToken();
				$data['token_expire_time'] = time()+$time;
				
				// \EloquentModel\User::where('id', '=', $rs[0]['id'])->update($data);
				echo $this->functionObj->toAppJson(null, '016', 'token失效', false);
				exit();
			}
		} else {
			$userRs = \EloquentModel\User::where('user_login', '=', $username)
								->get()
		          				->toArray();
		    if(empty($userRs)){
		    	echo $this->functionObj->toAppJson(null, '007', '用户不存在', false);
		    } else {
		    	$userDeletedRs = \EloquentModel\User::where('user_login', '=', $username)
								->where("is_deleted", '=', 1)
								->get()
		          				->toArray();
		        if(!empty($userDeletedRs)){
		        	echo $this->functionObj->toAppJson(null, '007', '用户惨遭删除', false);
		        } else {
		        	echo $this->functionObj->toAppJson(null, '007', '非法操作', false);
		        }
		    }
			
			exit();
		}

	}

	/**
	* 获取token
	* @return string
	*/
	public function getToken()
	{
		
		$ok = true;
		$access_token = '';
		while($ok){
			$tokenStr = $this->functionObj->tokenStr();
			$token_is_used = \EloquentModel\User::where('access_token','=',$tokenStr)->get();
			if(count($token_is_used)==0){
				$access_token = $tokenStr;
				$ok = false;
			}
		}

		return $access_token;

	}

	/**
	* 文件存储验证码方式
	* 文件默认存在tempCache目录下
	* @author  xww
	* @param   [$number]     string    手机号
	* @param   [$captcha]    string    验证码
	* @param   [$time]       int       验证时间默认30分钟
	* @return  void
	*/
	public function VerifyCaptchaThroughMD5File($number,$captcha, $time=1800)
	{

		$fname = $_SERVER['DOCUMENT_ROOT'].'/tempCache/'.md5($number).'.txt';
		
		if(!file_exists($fname)){
			clearstatcache();
			$return = $this->functionObj->toAppJson(null, '011', '验证码失效', false);
			$this->responseResult($return);
		} else {
			$mtime = filemtime($fname);
			if(($mtime+$time)<=time()){
				unlink($fname);
				clearstatcache();
				$return = $this->functionObj->toAppJson(null, '011', '验证码失效', false);
				$this->responseResult($return);
			} else {
				$fileCaptcha = file_get_contents($fname);
				if($captcha!=$fileCaptcha){
					$return = $this->functionObj->toAppJson(null, '008', '验证码不符', false);
					$this->responseResult($return);
				}
			}
		}

	}

	/**
	* 去除用户api参数进行去空以及html转化 以及解压缩
	* @author xww
	* @return array
	*/
	public static function change()
	{
		//被gzip压缩
		$headers = getallheaders();
		if(!empty($headers['Send-Data-Type'])){
			$config = array();
			$input = file_get_contents("php://input");
			$gzdecodeStr = gzdecode($input);
			$paramsArr = explode("&", $gzdecodeStr);
			for ($i=0; $i < count($paramsArr); $i++) { 
				list($key,$value) = explode('=', $paramsArr[$i]);
				$config[$key] = $value;
			}
		} else {
			$config = $_REQUEST;
		}
		foreach ($config as &$value) {

			if( !is_array($value) ) {
				$value = htmlentities(trim($value));	
			}
			
		}

		//注销
		unset($value);

		//返回
		return $config;

	}

	/**
	* 用来指定必须获取的字段 缺少则会返回错误
	* @author    xww
	* @param     [$action]        string    行为
	* @param     [$params]        array     等待验证的参数
	* @param     [$needParams]    array    进行验证的参数
	*/
	public function configValid($action, array $params, array $needParams)
	{
		ob_clean();
		$validateRs = $this->functionObj->validateApiParams($action,$params,$needParams);
		if(!$validateRs['success']){
			$messages = implode(',', $validateRs['message']);
			//根据头部判断返回
			$return = $this->functionObj->toAppJson(null, '014', $messages, false);
			$this->responseResult($return);
			exit;
		}

	}

	/**
	* 统一输出  根据自定义头进行判断 仅支持apache服务器
	* 默认返回json
	* @author    xww
	* @param     [$return]   返回给app端字符串
	* @return    void
	*/ 
	public function responseResult($return)
	{
		ob_clean();
		$headers = getallheaders();
		// header("HTTP/1.1 200 ok");
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
		if(!empty($headers['Receive-Data-Type'])){
			//根据压缩前后  数据比率判断是否压缩输出
			$gzip = gzencode($return, 9);
			$length = strlen($return);
			$gzipLength = strlen($gzip);
			if((float)ceil($length/$gzipLength)>1.5){
				header("Send-Data-Type:1");
				header("Content-Type:gzip");
				echo $gzip;
			} else {
				header('Content-type: application/json');
				echo $return;
			}
		} else {
			header('Content-type: application/json');
			echo $return;
		}
		exit;
	}

	/**
	* 获取其他get方式传递的参数
	* @param  [$inArr]      array 赋值于此数组上
	* @param  [$exceptStr]  哪个字符串上的是不要的 通常过滤url地址
	* @author xww
	* @return inArr array
 	*/ 
 	public function getOtherParam($inArr, $exceptStr)
 	{
 		foreach ($_GET as $url_key => $url_value) {
			if(!strripos($url_key, $exceptStr)){
				//去除地址的get  将其他传递
				$inArr[$url_key] = $url_value;
			}
		}
		return $inArr;
 	}
 	
 	/**
	* 获取用户--用于api
	* 当没有传递type的时候  默认是以正常的请求方式获取用户即--user_login,access_token
	* 否则以openid+type的方式获取用户
	* 同时需要判断access_token是否过期
	* @author 	xww
	* @param 	array 	$config
	* @param 	int/string 	$resType  为了layui等其他要求格式的 登录过期返回
	* @return 	array
	*/
	public function getUserApi($configs, $resType=null)
	{
		
		if(empty($configs['type'])){
			//正常方式
			//验证
			$this->configValid('required',$configs,['user_login', 'access_token']);

			//获取用户
			$user = \EloquentModel\User::where("user_login", '=', $configs['user_login'])
										->where("access_token", '=', $configs['access_token'])
										->where("is_deleted", '=', 0)
										->take(1)
										->get()
										->toArray();
		} else {
			//openid + type方式
			//验证
			$this->configValid('required',$configs,['openid', 'type']);

			//判断是哪个
			switch ($configs['type']) {
				case 1:
					$columnName = 'wechat_openid';
					break;
				case 2:
					$columnName = 'sina_openid';
					break;
				case 3:
					$columnName = 'qq_openid';
					break;
				default:
					$return = $this->functionObj->toAppJson(null, '013', '参数错误', false);
					//返回
					$this->responseResult($return);
					break;
			}

			//获取用户
			$user = \EloquentModel\User::where($columnName, '=', $configs['openid'])
										->where("is_deleted", '=', 0)
										->take(1)
										->get()
										->toArray();

		}

		//用户不存在
		if(empty($user)){
			$return = $this->functionObj->toAppJson(null, '007', '身份验证失败', false);

			if( !is_null($resType) && $resType==1 ) {
				/*layui*/
				$return = $this->functionObj->toLayuiJson(null, '016', '身份验证失败', 0);
			}

			//返回
			$this->responseResult($return);
			exit();
		} else {
			//判断token是否过期
			if($user[0]['token_expire_time']<=time()){
				//token过期
				$return = $this->functionObj->toAppJson(null, '016', 'token失效', false);

				if( !is_null($resType) && $resType==1 ) {
					/*layui*/
					$return = $this->functionObj->toLayuiJson(null, '016', '身份验证失败', 0);
				}
				//返回
				$this->responseResult($return);
				exit();
			}
		}

		return $user;

	}

	/**
	* 字段空时置为null
	* 暂时只支持2级
	* @author 	xww
	* @param 	array 		$data
	* @return 	array
	*/
	public function dataToNull($data)
	{

		foreach ($data as $key => &$singleTon) {
			if(is_array($singleTon)) {
				foreach ($singleTon as $singleTonkey => &$singleTonValue) {
					$singleTonValue = empty($singleTonValue)? null:$singleTonValue;
				}
			} else {
				$singleTon = empty($singleTon)? null:$singleTon;
			}
		}
		
		return $data;

	}

	/**
	* 根据日志类型id 获取对应文本信息
	* @author 	xww
	* @param 	int/string    	$typeId
	* @return 	string or null
	*/
	public function getDiaryTypeString($typeId)
	{

		switch (intval($typeId)) {
			case 1:
				return "日记";
				break;
			case 2:
				return "周记";
				break;
			case 3:
				return "月记";
				break;
			case 4:
				return "季记";
				break;
			case 5:
				return "年记";
				break;
			default:
				return null;
				break;
		}

	}

	/**
    * html提示
    * @author   xww
    * @param    string  $text
    * @return   string
    */
    public function showHtmlNotice($text)
    {
        
        $str = "<!DOCTYPE html>";
        $str .= '<html lang="en">';
        $str .= '<head>';
        $str .=     '<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">';
        $str .=     '<meta charset="UTF-8">';
        $str .=     '<title>提示</title>';
        $str .=     '<style type="text/css">';
        $str .=         'html,body,div,span,input,p,img,table,tbody,tr,td,a{padding: 0px; margin: 0px; } html,body {width: 100%; height: 100%; background-color: #6e6efa; font-size: 19px; padding-top: 72px; text-align: center; color: #FFF;     overflow: hidden; } .text-box { margin: 0 auto; width: 90%; line-height: 1.5; }';
        $str .=     '</style>';
        $str .= '</head>';
        $str .= '<body>';
        $str .= '<div class="text-box">';
        $str .= $text;
        $str .= '</div>';
        $str .= '</body>';
        $str .= '</html>';

        return $str;

    }

}