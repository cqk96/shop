<?php
namespace VirgoApi;
use Illuminate\Database\Capsule\Manager as DB;
use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\SendBatchSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;

class ApiSessionController2 extends ApiBaseController
{
	public $number;
	protected $functionObj;

	/**
	* api参数数组
	* @var array
	*/
	private $_configs;

	static $acsClient = null;

	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
		$this->userObj = new \EloquentModel\User;
		$this->_configs = parent::change();
	}

	/**
	* @SWG\Post(path="/api/v1/user/getRegisterVerify2", tags={"Msg"}, 
	*  summary="获取注册验证码", 
	*  description="根据传入的用户手机号 发送验证码短信 注: 非上线情况不开启真实发送短信",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="phone", type="string", required=true, in="formData"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json":{"data":"7075","status":{"code":"001","message":"验证码发送成功","success":true}} }
	*  )     
	* )
	*
	* 获取注册验证码
	* @author 	xww
	* @return 	json
	*/
	public function getRegisterVerify()
	{

		//检测必要字段
		$this->configValid('required',$this->_configs,['phone']);

		$phone = $this->_configs['phone'];

		//手机号长度验证
		$this->judgePhoneLength($phone);

		//手机号有效性验证
		$this->judgePhoneHead($phone);

		$user_is_existed = \EloquentModel\User::where('user_login', '=', $phone)->where("is_deleted", '=', 0)->count();

		if($user_is_existed!=0){
			$return = $this->functionObj->toAppJson(null, '044', '用户已存在', false);
			$this->responseResult($return);
			return false;
		} else {
			//发送验证码
			// 判断当前验证码是否存在
			if(file_exists($_SERVER['DOCUMENT_ROOT'].'/tempCache/'.md5($phone).'.txt')){
				$mtime = filemtime($_SERVER['DOCUMENT_ROOT'].'/tempCache/'.md5($phone).'.txt');
				if($mtime+60>time()){
					// 小于1分钟
					$return = $this->functionObj->toAppJson(null, '067', '请求过于频繁', false,false);
			        //输出
			        $this->responseResult($return);
			        exit();
				} else {
					$this->getVerify($phone);	
				}
				clearstatcache();
			} else {
				clearstatcache();
				$this->getVerify($phone);
			}

		}

	}

	/**
	* send SMS
	* 默认发送注册验证码
	* @param    [$number]    string    要发送短信的手机号
	* @param    [$sname]    string    短信签名
	* @param    [$scode]    string    短信模板号
	* @return   void
	*/
	public function getVerify($number, $sname="山西思易洁汽车服务", $scode="SMS_126650416")
	{
		
		//生成验证码文件存储目录
		$this->functionObj->mkDir('/tempCache');

		//生成随机验证码
		$randomStr = $this->functionObj->getRandStr($type=1,$length=4);

		//短信验证码
		$fname = $_SERVER['DOCUMENT_ROOT'].'/tempCache/'.md5($number).'.txt';
		
		//存储验证码
		file_put_contents($fname, $randomStr);
		
		require_once dirname(__DIR__) . '/../modules/Ali/aliyun-dysms-php-sdk/api_sdk/vendor/autoload.php';

		// // 加载区域结点配置
		// Config::load();
		
		// // 初始化SendSmsRequest实例用于设置发送短信的参数
  //       $request = new SendSmsRequest();

  //       // 必填，设置短信接收号码
  //       $request->setPhoneNumbers($number);

  //       // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
  //       $request->setSignName($sname);

  //       // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
  //       $request->setTemplateCode($scode);

  //       // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
  //       $request->setTemplateParam(json_encode(array(  // 短信模板中字段的值
  //           "code"=>$randomStr
  //       ), JSON_UNESCAPED_UNICODE));

  //       // 发起访问请求
  //       $acsResponse = static::getAcsClient()->getAcsResponse($request);

        if(false){//$acsResponse->Code!="OK"
   //      	$codeArr = (array)$resp->code;
			// $msgArr = (array)$resp->sub_msg;
        	// $code = empty($resp->code)? '0000':$resp->code;
        	// $msg = empty($resp->sub_msg)? '0000':$resp->sub_msg;
        	$return = $this->functionObj->toAppJson(null, $acsResponse->Code, $acsResponse->Message, false,false);
        } else {
        	$return = $this->functionObj->toAppJson($randomStr, '001', '验证码发送成功', true,false);
        }

        //输出
        $this->responseResult($return);
        exit();
	}

	/**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    public static function getAcsClient() {
        //产品名称:云通信流量服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        $accessKeyId = "LTAI8yjjoGMBnk3g"; // AccessKeyId

        $accessKeySecret = "4pUeNCPbZ9v3IjNplhPvkqiwrgFkzd"; // AccessKeySecret

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";


        if(static::$acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }

	//与注册时逻辑相反,重置密码发送验证码要确保手机号存在
	public function restPasswordVerify()
	{
		
		//检测必要字段
		$this->configValid('required',$this->_configs,['phone']);

		$phone = $this->_configs['phone'];

		$has = \EloquentModel\User::where(function($query)  use ($phone) {
										$query->orWhere('user_login', '=', $phone)
											  ->orWhere('phone', '=', $phone);
								  })
								  ->where("is_deleted", '=', 0)
		                          ->count();

		if(empty($has)){
			$return = $this->functionObj->toAppJson(null, '042', '手机号不存在', false);
			$this->responseResult($return);
			return false;
		} else{
			$this->getVerify($phone,"身份验证",'SMS_9675343');
		}

	}

	public function forgetPasswordVerify()
	{
		//验证码是否正确判断
		$this->VerifyCaptchaThroughMD5File($_POST['phone'],$_POST['verify']);
		echo $this->functionObj->toAppJson(null, '0029', '验证通过，请输入新的密码', true);
	}

	/**
	* 修改，忘记密码
	* @author xww
	* @return void
	*/
	public function resetPassword()
	{
		
		ob_clean();

		$validateRs = $this->functionObj->validateApiParams('required',$_POST,['phone','password','VerificationCode']);
		if(!$validateRs['success']){
			$messages = implode(',', $validateRs['message']);
			echo $this->functionObj->toAppJson(null, '014', $messages, false);
			return false;
		}

		$phone=$_POST['phone'];
		//$password=$_POST['password'];
		//转义
		$pwd = get_magic_quotes_gpc()==1? $_POST['password']:addslashes($_POST['password']);
		$date['password']=$pwd;//md5($pwd);
		//写人数据库--access_token生成
		//$date['access_token']=$this->functionObj->tokenStr();
	
		//判断密码与旧密码是否相同
		$user = \EloquentModel\User::where("user_login", '=', $_POST['phone'])->take(1)->get()->toArray();
		if($user[0]['password']==$_POST['password']){
			echo $this->functionObj->toAppJson(null, '031', '原密码与新密码相同', false);
			return false;
		}
		
		//判断验证码
		//验证码是否正确判断
		$this->VerifyCaptchaThroughMD5File($_POST['phone'],$_POST['VerificationCode']);

		$waitUser =\EloquentModel\User::where(function($query)  use ($phone) {
										$query->orWhere('user_login', '=', $phone)
											  ->orWhere('phone', '=', $phone);
								  })
								  ->where("is_deleted", '=', 0)
		                          ->first();
		
		if( empty( $waitUser ) ) {
			echo $this->functionObj->toAppJson(null, '006', '用户不存在', false);
			return false;
		}
		
		$rs=$this->userObj->where("id", $waitUser['id'])
					        ->update($date);

		if ($rs) {
			unlink($_SERVER['DOCUMENT_ROOT'].'/tempCache/'.md5($_POST['phone']).'.txt');
			// setcookie("user_login",$data['user_login'],time()+3600*5,'/');
			// setcookie("user_id",$rs,time()+3600*5,'/');
			echo $this->functionObj->toAppJson(null, '001', '忘记密码成功', true);
		} else {
			echo $this->functionObj->toAppJson(null, '014', '忘记密码失败', false);
		}	  
	}

	/**
	* @SWG\Get(path="/api/v1/user/loginVerify", tags={"User"}, 
	*  summary="用户登录",
	*  description="通过传入手机号, 密码(加密后) 进行用户登录, 登录后会改变token",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="phone", description="账号(手机号)", type="string", required=true, in="query"),
	*  @SWG\Parameter(name="password", description="密码", type="string", required=true, in="query"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": { "id": 1, "user_login": "18758037930", "access_token": "99c38b4d1625a5890ad79643c90d8bfb64cddd28", "avatar": "/images/default-avatar.png", "phone": null, "name": null, "nationality": null, "ethnicity": null, "political": 0, "university": null, "major": null, "education": 0, "address": null, "nickname": "清风明月_TzbP", "gender": 3, "age": 3, "introduce": null, "birthday": null, "nativePlace": null, "joinTime": null, "workExperience": null, "workingLifeTime": "0", "createTime": 1530252596 }, "status": { "code": "001", "message": "获取用户成功", "success": true } }},
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/User"
	*   )
	*  )
	* )
	* 用户登录
	* @author xww
	* @return string/object
	*/
	public function loginVerify()
	{
		
		if(empty($this->_configs['type'])){
			//正常登录
			//检测必要字段
			$this->configValid('required',$this->_configs,['phone', 'password']);

			$password = get_magic_quotes_gpc()? $this->_configs['password']:addslashes($this->_configs['password']);

			//获取用户
			$user = \EloquentModel\User::where("user_login", '=', $this->_configs['phone'])
										->where("password", $password)
										->where("is_deleted", '=', 0)
										->take(1)
										->get()
										->toArray();
		} else {
			//第三方登录
			$this->configValid('required',$this->_configs,['type', 'openid']);

			//判断是哪个
			switch ($this->_configs['type']) {
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
			$user = \EloquentModel\User::where($columnName, '=', $this->_configs['openid'])
										->where("is_deleted", '=', 0)
										->take(1)
										->get()
										->toArray();
		}
		
		if(!empty($user)){
				
			//token失效重新生成token
			$data['access_token'] = $this->getToken();
			$data['token_expire_time'] = time()+86400*30;
			
			\EloquentModel\User::where('id', '=', $user[0]['id'])->update($data);

			if(!empty($this->_configs['type'])){
				/*
				//下线
				$this->letHimGo($phone);
				*/
			}

			// 重新获取用户
			$uid = $user[0]['id'];
			unset($user);
			$user  = \EloquentModel\User::find($uid)->toArray();

			$user['nativePlace'] = $user['native_place'];
			$user['joinTime'] = $user['join_time'];
			$user['workExperience'] = $user['work_experience'];
			$user['workingLifeTime'] = $user['working_life_time'];
			$user['createTime'] = $user['create_time'];

			// 获取用户角色
			$roleToUserModel = new \VirgoModel\RoleToUserModel;
			$rids = $roleToUserModel->getUserRoleIds( $uid );
			$rids = empty($rids)? null:$rids;
			$user['roles'] = $rids;

			// 获取用户部门
			$roleToDepartmentModel = new \VirgoModel\DepartmentModel;
			$departIds = $roleToDepartmentModel->getUserDepartmentId( $uid );
			$departIds = empty($departIds)? null:$departIds;
			$user['departmentIds'] = $departIds;

			unset($user['password']);
			unset($user['is_deleted']);
			// unset($user['id']);
			unset($user['token_expire_time']);
			unset($user['native_place']);
			unset($user['join_time']);
			unset($user['work_experience']);
			unset($user['working_life_time']);
			unset($user['create_time']);
			unset($user['update_time']);

			unset($user['wechat_openid']);
			unset($user['sina_openid']);
			unset($user['qq_openid']);
			
			$return = $this->functionObj->toAppJson($user, '001', '获取用户成功', true);
		} else {
			//获取失败

			// 非三方登录
			if(empty($this->_configs['type'])){
				$has_account = \EloquentModel\User::where("user_login", '=', $this->_configs['phone'])
										->where("is_deleted", '=', 0)
										->take(1)
										->count();
				if($has_account){
					$return = $this->functionObj->toAppJson(null, '046', '密码错误', false);
				} else {
					$return = $this->functionObj->toAppJson(null, '042', '用户不存在', false);
				}						
			} else {
				$return = $this->functionObj->toAppJson(null, '042', '用户不存在', false);
			}

		}

		// 登录测试
		// error_log($return, 3, $_SERVER['DOCUMENT_ROOT']."/".time().".txt");
		
		//输出
		$this->responseResult($return);

	}

	/**
	*$_POST['access_token']
	*/
	public function getUserInfo()
	{
		$access_token=$_POST['access_token'];
		$user=$this->userObj->where('is_deleted','=',0)
					  		->where('access_token','=',$access_token)
					  		->get();
		if(!empty($user[0])){
			unset($user[0]['password'],$user[0]['access_token'],$user[0]['is_deleted']);
			echo $this->functionObj->toAppJson($user[0],'1005','获取用户信息成功',true);
		}else{
			echo $this->functionObj->toAppJson(null,'1006','该用户不存在，可能已经被删除',true);
		}
	}
			
	/**
	* 注册行为
	* @author xww
	* @return void
	*/
	public function doRegister()
	{
		/*
		* 根据model来判定是以何种方式进行注册
		* 缺省model 则为默认普通注册方式
		* model：
		* 0 => 普通注册
		* 1 => 绑定手机号注册
		* 2 => 第三方新账号注册
		*/
		$model = empty($this->_configs['model'])? 0:$this->_configs['model'];

		switch ($model) {
			case 0:
				$this->doRegisterNormal();
				break;
			case 1:
				$this->doRegisterNormalModelFirst();
				break;
			case 2:
				$this->doRegisterNormalModelSecond();
				break;
			default:
				$this->doRegisterNormal();
				break;
		}
	}

	/**
	* 强制下线
	* 此处使用极光推送
	* @author xww
	* @param  [$phone] string
	* @return void
	*/
	public function letHimGo($phone)
	{
		
		$app_key = '29dec5f933618755f9a80a1b';
		$master_secret = '190736f23e276da58137c163';
		$client = new \JPush\Client($app_key, $master_secret);
		try {
			$pushData['type'] = 5;
			$pushData['return'] = ['push_time'=>time()];
			$push_payload = $client->push()
							->setPlatform('all')
							->addAlias([$phone])
							->message('下线通知', [
							  'title' => '下线通知',
							  'content_type' => 'text',
							  'extras' => $pushData
							])
							->send();
		}catch (\JPush\Exceptions\APIConnectionException $e) {
			// 极光连接异常捕获
			error_log("Connention_Problem:".$e, 3, $_SERVER['DOCUMENT_ROOT']."/jpushLog/".time().".txt");
		} catch (\JPush\Exceptions\APIRequestException $e) {
			// 极光请求异常捕获
			error_log("Request_Problem:".$e, 3, $_SERVER['DOCUMENT_ROOT']."/jpushLog/".time().".txt");
		}

	}

	/**
	* @SWG\Post(path="/api/v1/user/doRegister", tags={"User"},
	*  produces={"application/json"},
	*  summary="用户注册接口 注册后用户身份是普通用户",
	*  description="根据用户手机号，短信验证码，密码注册一个用户,注册成功返回用户对象",
	*  @SWG\Parameter(name="phone", type="string", required=true, in="formData"),
	*  @SWG\Parameter(name="captcha", type="string", required=true, in="formData"),
	*  @SWG\Parameter(name="password", type="string", required=true, in="formData"),
	*  @SWG\Response(
	*	response=200,
	*	description="操作成功",
	*   examples={"application/json": {"data": "User","status":{"code":"001","message":"验证码发送成功","success":true}} },
	*   @SWG\Schema(
	*	type="object",
	*	ref="#/definitions/User"
	*	)
	*  )
	* )
	* 普通注册
	* 根据用户手机号，短信验证码，密码注册一个用户
	* 当手机号存在时抛出异常
	* @author xww
	* @return string/object
	*/
	public function doRegisterNormal()
	{
		
		date_default_timezone_set("PRC");
		
		//验证
		$this->configValid('required',$this->_configs,['phone','captcha', 'password']);

		$phone = $this->_configs['phone'];
		
		$verify = $this->_configs['captcha'];

		$password = $this->_configs['password'];

		/*
		* 判断手机号是否存在 绑定时可根据这个判断是否是第一次绑定加注册
		*/
		$has_phone = \EloquentModel\User::where('user_login', '=', $phone)
											->where("is_deleted", '=', 0)
			                                ->count();

		/*
		* 如果手机号存在且正常则直接失败
		*/
		if($has_phone!=0){
			$return = $this->functionObj->toAppJson(null, '009', '账号已存在', false);
			//返回
			$this->responseResult($return);
		}

		//验证码是否正确判断
		$this->VerifyCaptchaThroughMD5File($phone,$verify);

		//注册
		$data['user_login'] = $phone;
		$data['password'] = get_magic_quotes_gpc()? $password:addslashes($password);
		$data['access_token'] = $this->functionObj->tokenStr();

		// 昵称
		$data['nickname'] = $this->functionObj->getNickName('清风明月_');

		// 头像
		$data['avatar'] = "/images/default-avatar.png";

		//token过期时间 ()  10天后过期
		$data['token_expire_time'] = time()+86400*10;

		//创建时间戳
		$data['create_time'] = time();
		$data['update_time'] = time();

		$recordId = \EloquentModel\User::insertGetId($data);

		if($recordId){

			//插入角色
			\EloquentModel\RoleToUser::insert(['role_id'=>4, 'user_id'=>$recordId]);
			// $this->registerThirdPart($recordId);
			$user = \EloquentModel\User::find($recordId);

			$user['nativePlace'] = $user['native_place'];
			$user['joinTime'] = $user['join_time'];
			$user['workExperience'] = $user['work_experience'];
			$user['workingLifeTime'] = $user['working_life_time'];
			$user['createTime'] = $user['create_time'];

			unset($user['password']);
			unset($user['is_deleted']);
			// unset($user['id']);
			unset($user['token_expire_time']);
			unset($user['native_place']);
			unset($user['join_time']);
			unset($user['work_experience']);
			unset($user['working_life_time']);
			unset($user['create_time']);
			unset($user['update_time']);

			unset($user['wechat_openid']);
			unset($user['sina_openid']);
			unset($user['qq_openid']);

			$return = $this->functionObj->toAppJson($user, '001', '注册成功', true);

			//删除验证码文件
			$this->deleteCaptchaFile($this->_configs['phone']);

			//返回
			$this->responseResult($return);

		} else {

			//注册失败
			$return = $this->functionObj->toAppJson(null, '005', '注册失败', false);
			$this->responseResult($return);

		}
		
	}

	/**
	* 模式一注册(绑定手机号进行注册 当手机号不存在则为注册否则为绑定)
	* need param: type openid phone
	* type=>1微信 2=>新浪 3=>qq
	* @author xww
	* @return void
	*/
	public function doRegisterNormalModelFirst()
	{
		
		//验证
		$this->configValid('required',$this->_configs,['type','openid', 'phone', 'captcha']);

		//验证码是否正确判断
		$this->VerifyCaptchaThroughMD5File($this->_configs['phone'],$this->_configs['captcha']);

		/*
		* 通过判断type进行判断 三方绑定
		* type=>1微信 2=>新浪 3=>qq
		*/

		//判断是哪个
		switch ($this->_configs['type']) {
			case 1:
				$data['wechat_openid'] = $this->_configs['openid'];
				break;
			case 2:
				$data['sina_openid'] = $this->_configs['openid'];
				break;
			case 3:
				$data['qq_openid'] = $this->_configs['openid'];
				break;
			default:
				$return = $this->functionObj->toAppJson(null, '013', '参数错误', false);
				$this->responseResult($return);
				break;
		}

		/*
		* 判断手机号是否存在 判断是绑定还是第一次注册
		*/
		$has_phone = \EloquentModel\User::where('user_login', '=', $this->_configs['phone'])
											->where("is_deleted", '=', 0)
			                                ->count();

		if($has_phone!=0){
			//绑定
			$is_bind = true;
		} else {
			//注册
			$is_bind = false;

			$this->configValid('required',$this->_configs,['password']);
			$data['access_token'] = $this->functionObj->tokenStr();
			//token过期时间 ()  10天后过期
			$data['token_expire_time'] = time()+86400*10;
			//创建时间戳
			$data['create_time'] = time();
			$data['password'] = get_magic_quotes_gpc()? $this->_configs['password']:addslashes($this->_configs['password']);
			$data['user_login'] = $this->_configs['phone'];
		}

		if($is_bind){
			$rs = \EloquentModel\User::where("user_login", '=', $this->_configs['phone'])
								->where("is_deleted", '=', 0)
								->update($data);
		} else {
			$rs = \EloquentModel\User::insertGetId($data);
		}

		$type_purpose = [1=>'微信',2=> '新浪',3=>'qq'];
		if($rs){
			if(!$is_bind){
				//注册第三方
				//插入角色
				\EloquentModel\RoleToUser::insert(['role_id'=>4, 'user_id'=>$rs]);
				$this->registerThirdPart($rs);
				$user = \EloquentModel\User::find($rs);
			}
			$user = \EloquentModel\User::where("user_login", '=', $this->_configs['phone'])->get()->toArray()[0];
			unset($user['password']);

			//删除验证码文件
			$this->deleteCaptchaFile($this->_configs['phone']);

			//绑定成功
			$return = $this->functionObj->toAppJson(null, '001', '绑定'.$type_purpose[$this->_configs['type']].'成功', true);
			//返回
			$this->responseResult($return);
		} else {
			//绑定失败
			$code_purpose = [1=>'034',2=> '035',3=>'036'];
			$return = $this->functionObj->toAppJson(null, $code_purpose[$this->_configs['type']], '绑定'.$type_purpose[$this->_configs['type']].'失败', true);
			//返回
			$this->responseResult($return);
		}

	}

	/**
	* 模式二注册  将第三方以新账号行为直接注册
	* @author xww
	* @return string/object
	*/
	public function doRegisterNormalModelSecond()
	{
		
		//验证
		$this->configValid('required',$this->_configs,['type','openid']);

		/*
		* 通过判断type进行判断 三方绑定
		* type=>1微信 2=>新浪 3=>qq
		*/

		//判断是哪个
		switch ($this->_configs['type']) {
			case 1:
				$data['wechat_openid'] = $this->_configs['openid'];
				$columnName = 'wechat_openid';
				break;
			case 2:
				$data['sina_openid'] = $this->_configs['openid'];
				$columnName = 'sina_openid';
				break;
			case 3:
				$data['qq_openid'] = $this->_configs['openid'];
				$columnName = 'qq_openid';
				break;
			default:
				$return = $this->functionObj->toAppJson(null, '013', '参数错误', false);
				//返回
				$this->responseResult($return);
				break;
		}

		//判断是否存在
		$has_account = \EloquentModel\User::where($columnName, '=', $this->_configs['openid'])
								->where("is_deleted", '=', 0)
								->count();

		//已绑定  返回错误
		if($has_account){
			$return = $this->functionObj->toAppJson(null, '009', '第三方账号已经存在', false);
			//返回
			$this->responseResult($return);
		}

		$data['access_token'] = $this->functionObj->tokenStr();
		//token过期时间 ()  10天后过期
		$data['token_expire_time'] = time()+86400*10;
		//创建时间戳
		$data['create_time'] = time();

		//创建新账号
		$rs = \EloquentModel\User::insertGetId($data);

		if($rs){
			//插入角色
			\EloquentModel\RoleToUser::insert(['role_id'=>4, 'user_id'=>$rs]);
			$user = \EloquentModel\User::find($rs);
			unset($user['password']);
			$return = $this->functionObj->toAppJson($user, '001', '注册成功', true);
			$this->responseResult($return);
		} else {
			//注册失败
			$return = $this->functionObj->toAppJson(null, '005', '注册失败', false);
			$this->responseResult($return);
		}

	}

	/**
	* 验证手机号是否正确
	* @author wkl
	* @param string $phone
	*/
	public function phoneVerify($phone='')
	{
		
		if($this->phoneExist($phone)){
			if ($this->judgePhoneLength($phone)) {
				if ($this->judgePhoneHead($phone)) {
					return true;
				} else {
					echo $this->functionObj->toAppJson(null, '0102', '请输入正确的手机号', false);
					return false;
				}
				
			} else {
				return false;
			}
			
		}else{
			return false;
		}

	}

	/**
	* web 账户第三方注册
	* @author xww
	* @param  [$uid] int/string  用户表id
	* @return void
	*/
	public function registerThirdPart($uid)
	{
		/*
		//查询用户
		$user = \EloquentModel\User::find($uid);

		//环信注册
		$huanXinUtilObj = new \VirgoUtil\HuanXinUtil;

		//get token
		$token = $huanXinUtilObj->getToken();

		//环信注册
		$rs = $huanXinUtilObj->registerUser($user['login'], $user['password']);

		//解析
		$headersArr = explode("\r\n", $rs['header']);
		$LineOneArr = explode(" ", $headersArr[0]);

		if($LineOneArr[1]!='200'){
			//环信注册失败--需要回滚

			//删除角色表中用户
			\EloquentModel\RoleToUser::where('role_id', '=', 4)
									 ->where('user_id', '=', $uid)
									 ->delete();

			//删除用户
			\EloquentModel\User::where('id', '=', $uid)->delete();

			$huanxinBody = json_decode($rs['body'], true);
			
			$return = $this->functionObj->toAppJson(null, $LineOneArr[1], '环信注册失败，回退用户注册。失败信息:'.$huanxinBody['error'], false);
			
			//返回
			$this->responseResult($return);

		}
		*/
	}

	/**
	* 删除验证码文件
	* @author xww
	* @param  [$phone]  string
	* @return void
	*/
	public function deleteCaptchaFile($phone)
	{
		$fpath = $_SERVER['DOCUMENT_ROOT']."/tempCache/".md5($phone).".txt";
		if(file_exists($fpath)){
			unlink($fpath);
		}
	}

	/**
	*手机号是否存在验证
	* @param string $phone
	*/
	public function phoneExist($phone='')
	{	
		$user_is_existed = \EloquentModel\User::where('user_login', '=', $phone)->get();
		if(count($user_is_existed)!=0){
			echo $this->functionObj->toAppJson(null, '0101', '该手机号已经被注册', true);
			return false;
		} else{
			return true;
		}

	}

	/**
	* 手机号长度是否为11位验证
	* @author   wkl
	* @param    [$phone]  string
	* @return   void
	*/
	public function judgePhoneLength($phone='')
	{	

		if (strlen($phone)!=11) {
			$return = $this->functionObj->toAppJson(null, '040', '手机号长度不正确，请输入正确的手机号', false);
			$this->responseResult($return);
		}

	}

	/**
	* 手机号是否合法验证
	* @author  wkl
	* @param   [$phone]    string
	* @return  void
	*/
	public function judgePhoneHead($phone='')
	{	
		
		if(!preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $phone)){
			$return = $this->functionObj->toAppJson(null, '040', '手机号不合法', false);
			$this->responseResult($return);
		}

	}

	/**
	* 根据面貌id获取政治面貌
	* @author 	xww
	* @param 	int/string 		$typeId
	* @return 	string
	*/
	public function getPoliticalText($typeId)
	{
		switch ((int)$typeId) {
			case 1:
				return '团员';
				break;
			case 2:
				return '预备党员';
				break;
			case 3:
				return '党员';
				break;
			default:
				return '群众';
				break;
		}
	}

	/**
	* 根据学历id获取学历
	* @author 	xww
	* @param 	int/string 		$typeId
	* @return 	string
	*/
	public function getEducationText($typeId)
	{
		switch ((int)$typeId) {
			case 1:
				return '博士';
				break;
			case 2:
				return '硕士';
				break;
			case 3:
				return '本科';
				break;
			case 4:
				return '专科';
				break;
			case 5:
				return '高中';
				break;
			case 6:
				return '初中';
				break;
			default:
				return '';
				break;
		}
	}


	
}