<?php
/**
* php version 5.5.12
* @author  xww<5648*****@qq.com>
* @copyright  xww  20161214
* @version 1.0.0
*/
namespace VirgoApi;
use Illuminate\Database\Capsule\Manager as DB;
class ApiSessionController extends ApiBaseController
{

	/**
	* 自定义函数对象
	* @var object
	*/
	private $_functionObj;

	/**
	* api参数数组
	* @var array
	*/
	private $_configs;

	public function __construct()
	{
		$this->_configs = parent::change();
		$this->_functionObj = new \VirgoUtil\Functions;
		parent::__construct();
	}

	public function update()
	{
		
		ob_clean();
		$validateRs = $this->functionObj->validateApiParams('required',$_POST,['access_token','user_login']);
		if(!$validateRs['success']){
			$messages = implode(',', $validateRs['message']);
			echo $this->functionObj->toAppJson(null, '014', $messages, false);
			return false;
		}

		$user = \EloquentModel\User::where('user_login', '=', $_POST['user_login'])
				    ->where('access_token', '=', $_POST['access_token'])
		            ->get();

		$this->judgeUser($user[0]['access_token'], $_POST['user_login'], 60*60*24*10);

		unset($_POST['user_login']);
		unset($_POST['access_token']);

		//特殊值判断

		//性别
		if(!empty($_POST['gender']) && is_string($_POST['gender'])){
			$_POST['gender'] = $this->functionObj->getGenderNum($_POST['gender']);
		}

		if(!empty($_POST)){
			try{
				$rs = $this->functionObj->editColumnsValueById('\\EloquentModel\\User', $_POST , array('id'=>$user[0]['id']));
				if($rs){
					echo $this->functionObj->toAppJson(null, '001', '修改成功', true);
				} else {
					echo $this->functionObj->toAppJson(null, '003', '修改失败,没什么东西好修改', false);
				}
			}catch(\Exception $e){
				echo $this->functionObj->toAppJson(null, '003', '修改失败,错误:'.$e->getMessage(), false);
			}
		} else {
			echo $this->functionObj->toAppJson(null, '003', '修改失败,没有符合键值对的修改', false);
		}
		
	}

	// public function forgetPassword()
	// {
	// 	if(empty($_POST['captcha'])){
	// 		echo $this->functionObj->toAppJson(array(), '014', '验证码不为空', false);
	// 		return false;
	// 	}

	// 	if(empty($_POST['password'])){
	// 		echo $this->functionObj->toAppJson(array(), '014', '密码不为空', false);
	// 		return false;
	// 	}

	// 	if(empty($_COOKIE[$this->username])){
	// 		echo $this->functionObj->toAppJson(array(), '011', '验证码失效', false);
	// 		return false;
	// 	}

	// 	if($_COOKIE[$this->username]!=$_POST['captcha']){
	// 		echo $this->functionObj->toAppJson(array(), '008', '验证码不符', false);
	// 		return false;
	// 	}

	// 	$data['password'] = md5($_POST['password']);
	// 	$rs = User::where('user_login', '=', $this->username)->update($data);
	// 	if($rs)
	// 		echo $this->functionObj->toAppJson(array(), '001', '密码重置成功', true);
	// 	else
	// 		echo $this->functionObj->toAppJson(array(), '003', '密码重置失败', false);
	// }

	public function getRegisterVerify()
	{
		
		$phone = $_REQUEST['phone'];
		$user_is_existed = \EloquentModel\User::where('user_login', '=', $phone)->get();
		if(count($user_is_existed)!=0){
			echo $this->functionObj->toAppJson(array(), '009', '该手机号已经被注册', true);
			return false;
		} else
			$this->getVerify($phone);

	}

	public function getVerify($username, $sname="注册验证", $scode="SMS_9675339")
	{
		
		//todo  判断用户是否存在		

		
		if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/tempCache/'))
			mkdir($_SERVER['DOCUMENT_ROOT'].'/tempCache/');

		$randomStr = $this->functionObj->getRandStr($type=1,$length=4);
		//短信验证码
		$fname = $_SERVER['DOCUMENT_ROOT'].'/tempCache/'.md5($username).'.txt';
		file_put_contents($fname, $randomStr);

		
		$c = new \TopClient;
        $c->appkey = '23425675';
        $c->secretKey = '0f6fa9c777e6b52c8a4769637be83737';
        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        //$req->setExtend("123456");
        // $req->setSmsType("normal");
        // $req->setSmsFreeSignName($sname);
        // $req->setSmsParam("{\"code\":\"".$randomStr."\",\"product\":\"航桓科技\"}");
        // $req->setRecNum($username);
        // $req->setSmsTemplateCode($scode);
        // $resp = $c->execute($req);
        //var_dump($resp);
        /**/
        if(isset($resp->code))
			echo $this->functionObj->toAppJson(null, $resp->code, $resp->sub_msg, false);
		else
			echo $this->functionObj->toAppJson(null, '001', '验证码发送成功', true);

	}

	public function test()
	{
	 	echo "test";
	}

	//nickname
	public function getNickName()
	{
	
		$ok = true;
		$nickname = '';
		while($ok){
			$nicknameStr = $this->functionObj->getNickName();
			$nickname_is_used = User::where('nickname','=',$nicknameStr)->get();
			if(count($nickname_is_used)==0){
				$nickname = $nicknameStr;
				$ok = false;
			}
		}

		return $nickname;

	}

	/**
	* 我的工单
	*/
	public function workOrders()
	{
		ob_clean();
		
		$validateRs = $this->functionObj->validateApiParams('required',$_POST,['access_token','user_login']);
		if(!$validateRs['success']){
			$messages = implode(',', $validateRs['message']);
			echo $this->functionObj->toAppJson(null, '014', $messages, false);
			return false;
		}

		$this->judgeUser($_POST['access_token'], $_POST['user_login'], 60*60*24*10);

		$user = \EloquentModel\User::where("user_login", '=', $_POST['user_login'])
								   ->where("access_token", '=', $_POST['access_token'])
								   ->where("is_deleted", '=', 0)
								   ->get()
								   ->toArray();

		$workOrdersObj = \EloquentModel\WorkOrders::where("user_id", '=', $user[0]['id'])
												  ->where("is_deleted", '=', 0)
												  ->orderBy("created_time", 'desc');
		
		//分页
		if(!empty($_POST['page']) && !empty($_POST['size'])){
			$page = $_POST['page']<=1? 1:$_POST['page'];
			$page = $page-1;
			
			$take = $_POST['size'];
			$skip = $page*$take;
			$workOrdersObj = $workOrdersObj->take($take)
								   ->skip($skip);
		}

		$needs = array(
        		'content',
        		'work_status',
        		'created_time',
        		'id'
        		);

		if(!empty($_POST['extra'])){
			$extraColumn = explode(',', $_POST['extra']);
			foreach ($extraColumn as $extraColumn_key => $extraColumn_val) {
				array_push($needs, $extraColumn_val);
			}
		}

		$workOrders = $workOrdersObj->get()
							        ->toArray();
		
									        
		if(!empty($workOrders)){
			$return = array();
			$status = ['未处理', '已处理', '处理中'];
			foreach ($workOrders as $key => $value) {
				//$value['work_status'] = $status[$value['work_status']];
				//工单状态
				if(empty($value['worker_id']) && empty($value['work_status'])){
					$value['work_status'] = $status[0];
				} else if(empty($value['work_status'])){
					$value['work_status'] = $status[2];
				} else {
					$value['work_status'] = $status[1];
				}
				$value['created_time'] = date("Y-m-d",$value['created_time']);
	        	$temp = $this->functionObj->getNeedDataArray($value,$needs);
	        	
	        	array_push($return, $temp);
	        }
	        $code = '001';
			$message = '获取成功';
		} else {
			$return = null;
			$code = '006';
			$message = '没有符合条件数据';
		}

		//输出
		echo $this->functionObj->toAppJson($return, $code, $message, true);

	}
	
	/**
	* 查看子工单
	*/
	public function subWorkOrders()
	{
		ob_clean();
		$validateRs = $this->functionObj->validateApiParams('required',$_POST,['access_token','user_login','id']);
		if(!$validateRs['success']){
			$messages = implode(',', $validateRs['message']);
			echo $this->functionObj->toAppJson(null, '014', $messages, false);
			return false;
		}

		$this->judgeUser($_POST['access_token'], $_POST['user_login'], 60*60*24*10);

		$subWorkOrdersObj = \EloquentModel\SubWorkOrder::where("work_order_id", '=', $_POST['id'])
														->where("is_deleted", '=', 0)
														->orderBy("create_time",'desc');

		//分页
		if(!empty($_POST['page']) && !empty($_POST['size'])){
			$page = $_POST['page']<=1? 1:$_POST['page'];
			$page = $page-1;
			
			$take = $_POST['size'];
			$skip = $page*$take;
			$subWorkOrdersObj = $subWorkOrdersObj->take($take)
								   ->skip($skip);
		}

		$nots = array();
		if(!empty($_POST['extra'])){
			$extraColumn = explode(',', $_POST['extra']);
			foreach ($extraColumn as $extraColumn_key => $extraColumn_val) {
				array_push($nots, $extraColumn_val);
			}
		}

		$subWorkOrders = $subWorkOrdersObj->get()
		 					->toArray();

		if(empty($subWorkOrders)){
			$return = null;
			$code = '006';
			$message = '没有子工单';
		} else {
			$return = array();
			foreach ($subWorkOrders as $key => $value) {
				$value['create_time'] = empty($this->functionObj->timeDistance(time()-$value['create_time']))? date("Y-m-d",$value['create_time']):$this->functionObj->timeDistance(time()-$value['create_time']);
				$value['type'] = 1;
				$value['has_attachment'] = empty($value['sub_attachment_url'])? false:true;
				//查看追问工单的回复
				$replies = \EloquentModel\ReplyWorkOrder::where("item_id", '=', $value['id'])
											 ->where("item_type", '=', 1)
											 ->get(['id','content','reply_attachment_url'])
											 ->toArray();
				if(empty($replies)){
					$replyList = null;
				} else {
					foreach ($replies as $replies_key => $replies_value) {
						$replies[$replies_key]['type'] = 3;
						$replies[$replies_key]['has_attachment'] = empty($replies_value['reply_attachment_url'])? false:true;
						//unset($replies[$replies_key]['reply_attachment_url']);
					}
					$replyList = $replies;
				}

				$value['replyList'] = $replyList;

				$value = $this->functionObj->deleteNotNeedDataArray($value,$nots);				 
				array_push($return, $value);

			}
			$code = '001';
			$message = '获取成功';

		}

		//输出
		echo $this->functionObj->toAppJson($return, $code, $message, true);

	}

	/**
	* 签到
	*/
	public function doSignIn()
	{

		ob_clean();
		$validateRs = $this->functionObj->validateApiParams('required',$_POST,['access_token','user_login']);
		if(!$validateRs['success']){
			$messages = implode(',', $validateRs['message']);
			echo $this->functionObj->toAppJson(null, '014', $messages, false);
			return false;
		}

		$this->judgeUser($_POST['access_token'], $_POST['user_login'], 60*60*24*10);

		$user = \EloquentModel\User::where("user_login", '=', $_POST['user_login'])
								   ->where("access_token", '=', $_POST['access_token'])
								   ->where("is_deleted", '=', 0)
								   ->get()
								   ->toArray();

		//是否已经签过到
		$time_start  = strtotime(date("Y-m-d")." 00:00:00");
		$time_end = strtotime(date("Y-m-d")." 23:59:59");						   
		$has_sign_in = \EloquentModel\UserSignIn::where("user_id",'=',$user[0]['id'])->whereBetween('create_time', [$time_start, $time_end])->count();
		if(!empty($has_sign_in)){
			$code = '026';
			$message = "已经签过到了";
			$success = false;
			$return = null;
		} else {
			$code = '001';
			$message = "签到成功";
			$success = true;

			$data['user_id'] = $user[0]['id'];
			$data['create_time'] = time();
			$rs = \EloquentModel\UserSignIn::insert($data);
			if($rs){
				//增加积分
				$score = 1;
				\EloquentModel\User::where('id', '=', $user[0]['id'])->increment('score', $score);
			}

			$return = array();
			$userNew = \EloquentModel\User::where('id','=',$user[0]['id'])->get()->toArray();
			//var_dump($userNew);
			foreach ($userNew as $key => $value) {
				$return['score'] = $value['score'];
				$return['time'] = time();
				//array_push($return, $temp);
			}
		}

		//输出
		echo $this->functionObj->toAppJson($return, $code, $message, $success);

	}

	/**
	* @SWG\Post(path="/api/v1/user/updatePWD", tags={"User"}, 
	*  summary="修改密码",
	*  description="传入账号 旧密码 新密码进行密码更新 如果账号不存在或新老密码相同或环信修改失败 则提示失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="olderPWD", type="string", required=true, in="formData", description="旧密码(加密后)"),
	*  @SWG\Parameter(name="newerPWD", type="string", required=true, in="formData", description="新密码(加密后)"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": null, "status": { "code": "001", "message": "修改密码成功", "success": true } } }
	*  )
	* )
	* 修改密码
	*/
	public function updatePWD()
	{

		try{

			//验证
			$this->configValid('required',$this->_configs,['user_login', 'olderPWD', 'newerPWD']);

			DB::beginTransaction();

			$isBlock = true;

			$user_login = $this->_configs['user_login'];
			$olderPWD = $this->_configs['olderPWD'];
			$newerPWD = $this->_configs['newerPWD'];

			$model = new \VirgoModel\UserModel;

			// 实例化对象--环信对象
			$huanXinUtilObj = new \VirgoUtil\HuanXinUtil;

			$user = \EloquentModel\User::where("user_login", '=', $user_login)
									   ->where("is_deleted", '=', 0)
									   ->get()
									   ->toArray();

			if( empty($user) ) {
				throw new \Exception("无法查询到该账号", '006');
			}

			$uid = $user[0]['id'];

			if( $user[0]['password']!=$olderPWD ) {
				throw new \Exception("旧密码不正确", '028');
			}

			if( $user[0]['password']==$newerPWD ) {
				throw new \Exception("密码与原密码相同", '056');
			}

			$data['update_time'] = time();
			$data['password'] = $newerPWD;

			$rs = $model->partUpdate($uid, $data);
			
			if( !$rs){
				throw new \Exception("修改密码失败", '003');
			}

			/*环信注册模块*/
			$huanXinConfigs = $GLOBALS['globalConfigs']['huanXin'];
			$huanXinUtilObj->setProperties($huanXinConfigs);

			// get 环信token
			$token = $huanXinUtilObj->getToken();

			// 获取用户单个
			$rs = $huanXinUtilObj->getUser($user_login, $token);

			//解析
			$headersArr = explode("\r\n", $rs['header']);
			$LineOneArr = explode(" ", $headersArr[0]);

			if($LineOneArr[1]=='200'){

				// 更新密码
				$rs = $huanXinUtilObj->updateUserPwd($user_login, $newerPWD, $token);
				$headersArr = explode("\r\n", $rs['header']);
				$LineOneArr = explode(" ", $headersArr[0]);
				if($LineOneArr[1]!='200'){
					throw new \Exception("环信修改用户密码失败, code: " . $LineOneArr[1], '095');
				}
				
			}

			DB::commit();
			$return = $this->functionObj->toAppJson(null, '001', '修改密码成功', true);

		} catch(\Exception $e) {

			if( isset($isBlock) ) {
				DB::rollback();
			}

			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);

		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 我的收藏
	*/
	public function collected()
	{

		ob_clean();
		$validateRs = $this->functionObj->validateApiParams('required',$_POST,['access_token','user_login']);
		if(!$validateRs['success']){
			$messages = implode(',', $validateRs['message']);
			echo $this->functionObj->toAppJson(null, '014', $messages, false);
			return false;
		}

		$this->judgeUser($_POST['access_token'], $_POST['user_login'], 60*60*24*10);

		$user = \EloquentModel\User::where("user_login", '=', $_POST['user_login'])
								   ->where("access_token", '=', $_POST['access_token'])
								   ->where("is_deleted", '=', 0)
								   ->get()
								   ->toArray();

		//文章列表
		$collectNewsObj = \EloquentModel\CollectNews::where("user_id", '=', $user[0]['id'])
												->leftJoin("news", 'news.id',"=", 'collect_news.news_id')
												->select("news.*", "collect_news.created_time as collect_time");

		//分页
		if(!empty($_POST['page']) && !empty($_POST['size'])){
			$page = $_POST['page']<=1? 1:$_POST['page'];
			$page = $page-1;
			
			$take = $_POST['size'];
			$skip = $page*$take;
			$collectNewsObj = $collectNewsObj->take($take)
								   ->skip($skip);
		}

		$collectNews = $collectNewsObj->get()->toArray();


		$needs = array(
        		'title',
        		'cover',
        		'id'
        		);

    	if(!empty($_POST['extra'])){
			$extraColumn = explode(',', $_POST['extra']);
			foreach ($extraColumn as $extraColumn_key => $extraColumn_val) {
				array_push($needs, $extraColumn_val);
			}
		}

		if(empty($collectNews)){
			$return = null;
			$code = '006';
			$message = "没有符合条件数据";
		} else {
			$return = array();
			foreach ($collectNews as $key => $value) {
				$collectNews[$key]['cover'] = empty($value['cover'])? '':"http://".$_SERVER['HTTP_HOST'].$value['cover'];
				$temp = $this->functionObj->getNeedDataArray($value,$needs);
				array_push($return, $temp);
			}
			$code = '001';
			$message = "获取成功";
		}

		//输出
		echo $this->functionObj->toAppJson($return, $code, $message, true);
		
	}

	/**
	* 举报
	*/
	public function reportComment()
	{

		ob_clean();
		$validateRs = $this->functionObj->validateApiParams('required',$_POST,['access_token','user_login','comment_id', 'reason']);
		if(!$validateRs['success']){
			$messages = implode(',', $validateRs['message']);
			echo $this->functionObj->toAppJson(null, '014', $messages, false);
			return false;
		}

		$this->judgeUser($_POST['access_token'], $_POST['user_login'], 60*60*24*10);

		$user = \EloquentModel\User::where("user_login", '=', $_POST['user_login'])
								   ->where("access_token", '=', $_POST['access_token'])
								   ->where("is_deleted", '=', 0)
								   ->get()
								   ->toArray();

		//判断用户是否已经举报过了
		$has_report = \EloquentModel\ReportComment::where("comment_id", '=', $_POST['comment_id'])
									->where("user_id", '=', $user[0]['id'])
									->count();						   
		if(!empty($has_report)){
			//输出
			echo $this->functionObj->toAppJson(null, '026', '您已举报过该条评论', false);
			return false;
		}

		$data['comment_id'] = $_POST['comment_id'];
		$data['user_id'] = $user[0]['id'];
		$data['reason'] = $_POST['reason'];
		$data['create_time'] = time();

		$rs = \EloquentModel\ReportComment::insert($data);

		if($rs){
			$code = '001';
			$message = "举报成功";
			$success = true;
		} else {
			$code = '005';
			$message = "举报失败";
			$success = false;
		}

		//输出
		echo $this->functionObj->toAppJson(null, $code, $message, $success);

	}

	/**
	* 我的评论
	*/
	public function myComments()
	{
		ob_clean();
		$validateRs = $this->functionObj->validateApiParams('required',$_POST,['access_token','user_login']);
		if(!$validateRs['success']){
			$messages = implode(',', $validateRs['message']);
			echo $this->functionObj->toAppJson(null, '014', $messages, false);
			return false;
		}

		$this->judgeUser($_POST['access_token'], $_POST['user_login'], 60*60*24*10);

		$user = \EloquentModel\User::where("user_login", '=', $_POST['user_login'])
								   ->where("access_token", '=', $_POST['access_token'])
								   ->where("is_deleted", '=', 0)
								   ->get()
								   ->toArray();

		$commentsObj = \EloquentModel\CommentNews::leftJoin("news", 'news.id', '=', 'comment_news.news_id')
												->where("comment_news.user_id", '=', $user[0]['id'])
												->select("news.id","comment_news.created_time","comment_news.content", 'comment_news.comment_id', 'comment_news.is_deleted','news.title','comment_news.id as origin_id');

		//分页
		if(!empty($_POST['page']) && !empty($_POST['size'])){
			$page = $_POST['page']<=1? 1:$_POST['page'];
			$page = $page-1;
			
			$take = $_POST['size'];
			$skip = $page*$take;
			$commentsObj = $commentsObj->take($take)
								   ->skip($skip);
		}

		$comments = $commentsObj->get(['id','user_login', 'nickname'])->toArray();

		$usersTemp = \EloquentModel\User::get()->toArray();
		foreach ($usersTemp as $user_key => $user_val) {
			$users[$user_val['id']] = $user_val;
		}
		
		if(!empty($comments)){
			foreach ($comments as $key => $value) {
				$toName = '';
				if($value['comment_id']!=0){
					$temp = \EloquentModel\CommentNews::find($value['comment_id']);
					if(!empty($temp)){
						$toName = empty($users[$temp['user_id']]['nickname'])? $users[$temp['user_id']]['user_login']:$users[$temp['user_id']]['nickname'];
					}
				}
				$comments[$key]['to_name'] = $toName;
				$comments[$key]['name'] = empty($user[0]['nickname'])? $user[0]['user_login']:$user[0]['nickname'];
				$comments[$key]['avatar'] = empty($user[0]['avatar'])? '':"http://".$_SERVER['HTTP_HOST'].$user[0]['avatar'];
				$comments[$key]['created_time'] = date("Y-m-d",$value['created_time']);
			}
			$code = '001';
			$message = "获取成功";
			$return = $comments;
		} else {
			$return = null;
			$code = '006';
			$message = "没有符合条件记录";
		}

		//输出
		echo $this->functionObj->toAppJson($return, $code, $message, true);

	}

	/**
	* @SWG\Get(path="/api/v1/user/passwordEncrypt", tags={"User"}, 
	*  summary="用户登录密码加密",
	*  description="传入密码， 加密后输出",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="password", type="string", required=true, in="query", description="登录密码"),
	*  @SWG\Response(
	*   response=200,
	*   description="获取成功",
	*   examples={"application/json": { "data": "nciou979d472a84804b9f647bc185a877a8b5dijdm", "status": { "code": "001", "message": "加密成功", "success": true } } }
	*  )
	* )
	* 密码加密 统一
	* @author xww
	* @return void
	*/
	public function passwordEncrypt()
	{
		//加密规则 "nciou"+md5 32+"dijdm"
		//检测必要字段
		$this->configValid('required',$this->_configs,['password']);

		$password = "nciou".md5($this->_configs['password'])."dijdm";

		$return = $this->functionObj->toAppJson($password, '001', '加密成功', true);
		$this->responseResult($return);

	}

	/**
	* 将用户设置为登录
	* 存储于cookie中
	* @author xww
	* @return void
	*/
	public function setUserIn()
	{
		
		$this->configValid('required',$this->_configs,['user_login', 'access_token']);

		//判断用户是否存在 
		$has = \EloquentModel\User::where("user_login", '=', $this->_configs['user_login'])
							->where("access_token", '=', $this->_configs['access_token'])
							->where("is_deleted", '=', 0)
							->count();

		$rs1 = false;
		$rs2 = false;
		if($has){
			//10天过期
			$rs1 = setcookie("user_login",   $this->_configs['user_login'], time()+86400*10,'/');
			$rs2 = setcookie("access_token", $this->_configs['access_token'], time()+86400*10,'/');
		}

		// error_log("rs1:".(int)$rs1."rs2:".(int)$rs2, 3, $_SERVER['DOCUMENT_ROOT']."/".time().".txt");
		// error_log("user_login:".$this->_configs['user_login']."access_token:".$this->_configs['access_token'], 3, $_SERVER['DOCUMENT_ROOT']."/".time().".txt");
		if($rs1 && $rs2){
			// 登录测试
			// error_log("user_login:".$this->_configs['user_login']."access_token:".$this->_configs['access_token'], 3, $_SERVER['DOCUMENT_ROOT']."/".time().".txt");
			$return = $this->functionObj->toAppJson(null, '001', '用户登录成功', true);
		} else {
			$return = $this->functionObj->toAppJson(null, '067', '用户登录失败', false);
		}

		//输出
		$this->responseResult($return);

	}

	/**
	* 前台用户登出
	* @author xww
	* @return void
	*/
	public function userOut()
	{
		if(!empty($_COOKIE['user_login']) && !empty($_COOKIE['access_token'])){
			setcookie("user_login", '', time()-1, '/');
			setcookie("access_token", '', time()-1, '/');
			//验证码
			setcookie("restTime", '', time()-1, '/');
		}
	}

	/**
	* 前台修改用户基本数据 出生日期 昵称 性别
	* @author xww
	* @return void
	*/
	public function editBaseInfo()
	{
		
		//过期或者没有登录时 提醒登录
		if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
			$return = $this->functionObj->toAppJson(null, '002', '需要登录', false);
		} else {
			$user = $this->getUser();

			//需要用户重新登录
			if(empty($user)){
				$return = $this->functionObj->toAppJson(null, '002', '需要登录', false);
			} else {
				$data['nickname'] = empty($this->_configs['nickname'])? '':$this->_configs['nickname'];
				$data['gender'] = empty($this->_configs['gender'])? 3:$this->_configs['gender'];
				$year = empty($this->_configs['year'])? date("Y", time()):$this->_configs['year'];
				$month = empty($this->_configs['month'])? date("m", time()):$this->_configs['month'];
				$day = empty($this->_configs['day'])? date("d", time()):$this->_configs['day'];
				$data['birthday'] = strtotime($year.'-'.$month.'-'.$day.' 00:00:00');
				$rs = \EloquentModel\User::where("user_login", '=', $_COOKIE['user_login'])
								->where("access_token", '=', $_COOKIE['access_token'])
								->update($data);
				if($rs){
					//成功
					$return = $this->functionObj->toAppJson(null, '001', '修改成功', true);
				} else {
					//失败
					$return = $this->functionObj->toAppJson(null, '003', '修改失败', false);
				}

			}

		}

		$this->responseResult($return);

	}

	/**
	* 修改用户头像 以base64资源方式
	*/
	public function avatarResource()
	{

		$this->configValid('required',$this->_configs,['imgResource', 'ext', 'header']);

		//过期或者没有登录时 提醒登录
		if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
			$return = $this->functionObj->toAppJson(null, '002', '需要登录', false);
		} else {
			
			$user = $this->getUser();
			if(empty($user)){
				$return = $this->functionObj->toAppJson(null, '002', '需要登录', false);
			} else {

				//修改用户头像
				$imgResource = str_replace($this->_configs['header'], '', $this->_configs['imgResource']);
				$fileContent = base64_decode($imgResource);
				$fname = '/upload/avatarCover/'.((int)microtime(true)).".".$this->_configs['ext'];
				$fpath = $_SERVER['DOCUMENT_ROOT'].$fname;
				file_put_contents($fpath, $fileContent);
				//更新头像
				$data['avatar'] = $fname;
				$rs = \EloquentModel\User::where("user_login", '=', $_COOKIE['user_login'])
								->where("access_token", '=', $_COOKIE['access_token'])
								->update($data);

				if($rs) {
					$return = $this->functionObj->toAppJson(null, '001', 'OK', true);
				} else {
					$return = $this->functionObj->toAppJson(null, '003', 'Faile', false);
				}

			}

		}

		$this->responseResult($return);

	}

	/**
	* 前台检测用户是否尚未过期
	* @author xww
	* @return void
	*/
	public function userStillIn()
	{
		if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
			$return = $this->functionObj->toAppJson(null, '002', '需要登录', false);
		} else {
			$user = $this->getUser();
			if(empty($user)){
				$return = $this->functionObj->toAppJson(null, '002', '需要登录', false);
			} else {
				$return = $this->functionObj->toAppJson(['user_login'=>$_COOKIE['user_login'], 'access_token'=>$_COOKIE['access_token']], '001', 'ok', true);
			}
		}

		$this->responseResult($return);

	}

	/**
	* 前台--修改密码,重置密码
	* @author xww
	* @return string json string
	*/
	public function resetPasswordFront()
	{
		if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
			$return = $this->functionObj->toAppJson(null, '002', '需要登录', false);
		} else {
			$user = $this->getUser();
			if(!empty($user)){

				//验证
				$this->configValid('required',$this->_configs,['captcha', 'password']);

				//验证码是否正确判断
				$this->VerifyCaptchaThroughMD5File($user[0]['user_login'],$this->_configs['captcha']);

				$password = get_magic_quotes_gpc()? $this->_configs['password']:addslashes($this->_configs['password']);
				
				//密码
				$data['password'] = "nciou".md5($password)."dijdm";
				if($data['password']==$user[0]['password']){
					//原始密码相同
					$return = $this->functionObj->toAppJson(null, '001', '原始密码和现在的密码相同', true);
				} else {
					//不相同
					$rs = \EloquentModel\User::where("id", '=', $user[0]['id'])->update($data);
					if($rs){
						//成功
						$return = $this->functionObj->toAppJson(null, '001', 'ok', true);
					} else {
						//失败
						$return = $this->functionObj->toAppJson(null, '003', 'false', false);
					}
				}
			} else {
				$return = $this->functionObj->toAppJson(null, '002', '需要登录', false);
			}

		}

		//输出
		$this->responseResult($return);

	}

	/**
	* 新建收获地址
	* @author xww
	* @return mixed
	*/
	public function createShippingAddress()
	{
		
		//验证
		$this->configValid('required',$this->_configs,['name', 'phone', 'province', 'city', 'address']);

		$data['name'] = $this->_configs['name'];
		$data['phone'] = $this->_configs['phone'];
		$data['province'] = $this->_configs['province'];
		$data['city'] = $this->_configs['city'];
		$data['other'] = empty($this->_configs['other'])? '':$this->_configs['other'];
		$data['address'] = $this->_configs['address'];
		$data['update_time'] = time();

		//获取用户
		$user = $this->getUserApi();

		$data['user_id'] = $user[0]['id'];

		//判断是否 已经有地址记录  没有的话就存储为默认地址
		$has = \EloquentModel\AddressManager::where("user_id", '=', $data['user_id'])
									 ->where("is_deleted", '=', 0)
									 ->count();

		if(!$has){
			//设置为默认
			$data['default_use'] = 1;
		}

		//创建地址
		$rs = \EloquentModel\AddressManager::insert($data);

		if($rs){
			//success
			$return = $this->functionObj->toAppJson(null, '001', 'ok', true);
		} else {
			//fail
			$return = $this->functionObj->toAppJson(null, '005', 'fail', false);
		}

		//输出
		$this->responseResult($return);

	}

	/**
	* 更改默认收获地址
	* @author xww
	* @return mixed
	*/
	public function defaultShippingAddress()
	{
		
		//验证
		$this->configValid('required',$this->_configs,['id']);

		//获取用户
		$user = $this->getUserApi();

		$data['user_id'] = $user[0]['id'];

		//判断是否在操作自己的数据
		$address = \EloquentModel\AddressManager::where("user_id", '=', $user[0]['id'])
									 ->where("id", '=', $this->_configs['id'])
									 ->where("is_deleted", '=', 0)
									 ->get()
									 ->toArray();

		if(count($address)==0){
			//非法操作
			$return = $this->functionObj->toAppJson(null, '029', '非法操作', false);
			//输出
			$this->responseResult($return);
			exit();
		}

		//判断是否已经是默认地址
		if($address[0]['default_use']==1){
			//没有修改
			$return = $this->functionObj->toAppJson(null, '047', '没有做任何修改', false);
			//输出
			$this->responseResult($return);
			exit();
		}

		//修改默认地址
		$rs = \EloquentModel\AddressManager::where("id", '=', $this->_configs['id'])
									 ->update(['default_use'=>1]);

		if($rs){
			//success
			\EloquentModel\AddressManager::where("id", '<>', $this->_configs['id'])
										->where("user_id", '=', $user[0]['id'])
									 ->update(['default_use'=>0]);
			$return = $this->functionObj->toAppJson(null, '001', 'ok', true);
		} else {
			//fail
			$return = $this->functionObj->toAppJson(null, '005', 'fail', false);
		}

		//输出
		$this->responseResult($return);

	}

	/**
	* 删除收获地址
	* @author xww
	* @return mixed
	*/
	public function deletetShippingAddress()
	{
		
		//验证
		$this->configValid('required',$this->_configs,['id']);

		//获取用户
		$user = $this->getUserApi();

		$data['user_id'] = $user[0]['id'];

		//判断是否在操作自己的数据
		$address = \EloquentModel\AddressManager::where("user_id", '=', $user[0]['id'])
									 ->where("id", '=', $this->_configs['id'])
									 // ->where("is_deleted", '=', 0)
									 ->get()
									 ->toArray();

		if(count($address)==0){
			//非法操作
			$return = $this->functionObj->toAppJson(null, '029', '非法操作', false);
			//输出
			$this->responseResult($return);
			exit();
		}

		//判断是否已经删除过了
		if($address[0]['is_deleted']==1){
			//没有修改
			$return = $this->functionObj->toAppJson(null, '047', '没有做任何修改', false);
			//输出
			$this->responseResult($return);
			exit();
		}

		if($address[0]['default_use']==1){
			//没有修改
			$return = $this->functionObj->toAppJson(null, '005', 'fail', false);
			//输出
			$this->responseResult($return);
			exit();	
		}
		

		//进行删除
		$rs = \EloquentModel\AddressManager::where("id", '=', $this->_configs['id'])
									 ->update(['is_deleted'=>1]);

		
		if($rs){
			//success
			$return = $this->functionObj->toAppJson(null, '001', 'ok', true);
		} else {
			//fail
			$return = $this->functionObj->toAppJson(null, '005', 'fail', false);
		}

		//输出
		$this->responseResult($return);

	}

	/**
	* 修改收货地址
	* @author
	* @return string/object json string
	*/
	public function updateShippingAddress()
	{

		//验证
		$this->configValid('required',$this->_configs,['name', 'phone', 'province', 'city', 'address', 'id']);

		$data['name'] = $this->_configs['name'];
		$data['phone'] = $this->_configs['phone'];
		$data['province'] = $this->_configs['province'];
		$data['city'] = $this->_configs['city'];
		$data['other'] = empty($this->_configs['other'])? '':$this->_configs['other'];
		$data['address'] = $this->_configs['address'];
		$data['update_time'] = time();

		//获取用户
		$user = $this->getUserApi();

		// $data['user_id'] = $user[0]['id'];

		//判断是否 已经有地址记录  没有的话就存储为默认地址
		$has = \EloquentModel\AddressManager::where("user_id", '=', $user[0]['id'])
									 ->where("is_deleted", '=', 0)
									 ->count();

		if(!$has){
			//设置为默认
			$data['default_use'] = 1;
		}

		//修改地址
		$legal = \EloquentModel\AddressManager::where("is_deleted", '=', 0)
		                                   ->find($this->_configs['id']);

		if(empty($legal) || $legal['user_id']!=$user[0]['id']){
			//操作非法
			$return = $this->functionObj->toAppJson(null, '029', 'fail', false);
		} else {
			$rs = \EloquentModel\AddressManager::where("id", '=', $this->_configs['id'])
                                           ->update($data);
            //判断是否数据存在
			if($rs){
				//success
				$return = $this->functionObj->toAppJson(null, '001', 'ok', true);
			} else {
				//fail
				$return = $this->functionObj->toAppJson(null, '003', 'fail', false);
			}
		}

		//输出
		$this->responseResult($return);

	}

	/**
	* 获取用户--前台
	* @author xww
	* @return array
	*/
	public function getUser()
	{
		
		$user = \EloquentModel\User::where("user_login", '=', $_COOKIE['user_login'])
								->where("access_token", '=', $_COOKIE['access_token'])
								->where("is_deleted", '=', 0)
								->take(1)
								->get()
								->toArray();

		//用户被删除
		if(empty($user)){
			return false;
		}

		return $user;

	}

	/**
	* 获取用户--用于api
	* 当没有传递type的时候  默认是以正常的请求方式获取用户即--user_login,access_token
	* 否则以openid+type的方式获取用户
	* 同时需要判断access_token是否过期
	* @author xww
	* @return array
	*/
	public function getUserApi()
	{
		
		if(empty($this->_configs['type'])){
			//正常方式
			//验证
			$this->configValid('required',$this->_configs,['user_login', 'access_token']);

			//获取用户
			$user = \EloquentModel\User::where("user_login", '=', $this->_configs['user_login'])
										->where("access_token", '=', $this->_configs['access_token'])
										->where("is_deleted", '=', 0)
										->take(1)
										->get()
										->toArray();
		} else {
			//openid + type方式
			//验证
			$this->configValid('required',$this->_configs,['openid', 'type']);

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

		//用户不存在
		if(empty($user)){
			$return = $this->functionObj->toAppJson(null, '007', '身份验证失败', false);
			//返回
			$this->responseResult($return);
			exit();
		}else {
			//判断token是否过期
			if($user[0]['token_expire_time']<=time()){
				//token过期
				$return = $this->functionObj->toAppJson(null, '016', 'token失效', false);
				//返回
				$this->responseResult($return);
				exit();
			}
		}
		return $user;

	}

	/**
	* 修改绑定手机号
	* @author xww
	* @return string/object json string
	*/
	public function editBindPhone()
	{
		
		//验证
		$this->configValid('required',$this->_configs,['loginPwd', 'phone', 'captcha']);

		//验证验证码
		$this->VerifyCaptchaThroughMD5File($this->_configs['phone'],$this->_configs['captcha']);

		// 检测新的手机号是否已经被使用
		$is_used = \EloquentModel\User::where("user_login", '=', $this->_configs['phone'])
							->where("is_deleted", '=', 0)
							->count();

		if($is_used){
			//被使用
			$return = $this->functionObj->toAppJson(null, '009', '账号已经被使用', false);
			//输出
			$this->responseResult($return);
			return false;
		}

		//获取用户--通过传递的user_login 和 access_token
		$user = $this->getUserApi();

		//密码加密
		$password = "nciou".md5($this->_configs['loginPwd'])."dijdm";

		//判断密码是否相同
		if($user[0]['password']!=$password){
			$return = $this->functionObj->toAppJson(null, '046', '密码输入有误', false);
			//输出
			$this->responseResult($return);
			return false;
		}

		//更换账号
		$rs = \EloquentModel\User::where("id", '=', $user[0]['id'])->update(['user_login'=>$this->_configs['phone']]);
		if($rs){
			//success
			$return = $this->functionObj->toAppJson(null, '001', 'ok', true);
		} else {
			//fail
			$return = $this->functionObj->toAppJson(null, '048', 'fail', false);
		}
		$this->responseResult($return);
		return false;
	}

	/*cookie*/
	public function showCookie()
	{
		var_dump($_COOKIE);
	}

	/**
	* 立即购买时存储商品以及属性
	* 修改为直接存储sku
	*/ 
	public function writeProduct()
	{
		
		//验证--商品id,数量
		$this->configValid('required',$this->_configs,['id', 'amounts']);
		if(!empty($_COOKIE['products'])){
			// 不空 
			$hasOriginProduct = false;
			$products = unserialize($_COOKIE['products']);
			foreach ($products as $id => $idsArr) {
				if($id==$this->_configs['id']){
					// 更新数据
					$hasOriginProduct = true;
					$idsArr['attributes'] = empty($this->_configs['attributes'])? []:explode(',', $this->_configs['attributes']);
					$idsArr['amounts'] = $this->_configs['amounts'];
					$idsArr['sku_id'] = $this->getSkuId($id, implode(',', $idsArr['attributes']));
					$products[$id] = $idsArr;
				}
			}

			if(!$hasOriginProduct){
				// 第一次加入
				$temp['attributes'] = empty($this->_configs['attributes'])? []:explode(',', $this->_configs['attributes']);
				$temp['amounts'] = $this->_configs['amounts'];
				$temp['sku_id'] = $this->getSkuId($this->_configs['id'], implode(',', $idsArr['attributes']));
				$products[$this->_configs['id']] = $temp;
				unset($temp);
			}

			// 重设cookie 并延长过期时间
			$productsStr = serialize($products);
			setcookie("products", $productsStr, time()+2*60*60, '/');
		} else {
			// 空

			// 商品属性id数组--存储关联商品属性的id数组
			$product[$this->_configs['id']]['attributes'] = empty($this->_configs['attributes'])? []:explode(',', $this->_configs['attributes']);

			// 数量
			$product[$this->_configs['id']]['amounts'] = $this->_configs['amounts'];

			// sku id
			$product[$this->_configs['id']]['sku_id'] = $this->getSkuId($this->_configs['id'], implode(',', $product[$this->_configs['id']]['attributes']));

			// 序列化
			$productStr = serialize($product);

			// 设置cookie
			setcookie("products", $productStr, time()+2*60*60, '/');

		}

		// 输出
		$return = $this->functionObj->toAppJson(unserialize($productsStr), '001', 'ok', true);
		$this->responseResult($return);
		return false;
			
	}

	/**
	* 加入购物车
	* @author xww
	* @return string/obkect json
	*/
	public function putInShopCart()
	{
		
		//验证--商品id,数量,用户令牌与账号
		$this->configValid('required',$this->_configs,['id', 'amounts', 'user_login', 'access_token']);

		//获取用户
		$user = $this->getUserApi();

		unset($this->_configs['user_login']);
		unset($this->_configs['access_token']);
		$this->_configs['user_id'] = $user[0]['id'];

		$this->_configs['attributes'] = empty($this->_configs['attributes'])? '':$this->_configs['attributes'];
		$this->_configs['attributes'] = $this->getSkuId($this->_configs['id'], $this->_configs['attributes']);

		// 加入到购物车之中
		$shopCartModelObj = new \VirgoModel\ShopCartModel;
		$rs = $shopCartModelObj->create($this->_configs);

		if($rs){
			//加入购物车成功
			$return = $this->functionObj->toAppJson(null, '001', 'ok', true);
		} else {
			$return = $this->functionObj->toAppJson(null, '005', '加入购物车失败', false);
		}

		// 输出
		$this->responseResult($return);

	}

	/**
	* 移出购物车
	* @author xww
	* @return jons string
	*/ 
	public function outShopCart()
	{
		
		//验证--购物车id,用户令牌与账号
		$this->configValid('required',$this->_configs,['id', 'user_login', 'access_token']);

		//获取用户
		$user = $this->getUserApi();

		$this->_configs['user_id'] = $user[0]['id'];

		//移出购物车
		$shopCartModelObj = new \VirgoModel\ShopCartModel;
		$rs = $shopCartModelObj->delete($this->_configs);

		if($rs){

			//移出购物车成功
			$arr = $this->getOtherParam([], '/api/v1/user/outShopCart');
			if(empty($arr)){
				$arr = null;
			}

			$return = $this->functionObj->toAppJson($arr, '001', 'ok', true);
		} else {
			$return = $this->functionObj->toAppJson(null, '012', '移出购物车失败', false);
		}

		// 输出
		$this->responseResult($return);

	}

	/**
	* 修改购物车
	* @author xww
	* @return string
	*/ 
	public function editShopCart()
	{
		
		//验证--购物车id,用户令牌与账号
		$this->configValid('required',$this->_configs,['user_login', 'access_token']);

		//获取用户
		$user = $this->getUserApi();

		$this->_configs['user_id'] = $user[0]['id'];

		//移出购物车
		$shopCartModelObj = new \VirgoModel\ShopCartModel;
		$rs = $shopCartModelObj->update($this->_configs);

		if($rs){
			$return = $this->functionObj->toAppJson(null, '001', 'ok', true);
		} else {
			$return = $this->functionObj->toAppJson(null, '003', '修改失败', false);
		}

		// 输出
		$this->responseResult($return);

	}

	/**
	* 删除订单
	* @author xww
	* @return string/object  json
	*/ 
	public function deleteOrder()
	{
		
		//验证--订单id,用户令牌与账号
		$this->configValid('required',$this->_configs,['user_login', 'access_token', 'id']);		

		//获取用户
		$user = $this->getUserApi();

		$data['order_status'] = 2;
		$rs  = \EloquentModel\WechatOrder::where("userid", '=', $user[0]['id'])
									  ->where("id", '=', $this->_configs['id'])
									  ->update($data);

		if($rs){
			//删除订单成功
			$arr = $this->getOtherParam([], '/api/v1/user/outShopCart');
			if(empty($arr)){
				$arr = null;
			}
			$return = $this->functionObj->toAppJson($arr, '001', 'ok', true);
		} else {
			$return = $this->functionObj->toAppJson(null, '012', '修改失败', false);
		}

		// 输出
		$this->responseResult($return);

	}

	/**
	* 获取此时适合立即购买的优惠券
	* @author xww
	* @return string json
	*/ 
	public function getSuitableCoupon()
	{
		
		//验证--商品id,数量,用户令牌与账号
		$this->configValid('required',$this->_configs,['user_login', 'access_token', 'id', 'amounts']);

		//获取用户
		$user = $this->getUserApi();

		// 不包含属性的总金额
		$productArr = \EloquentModel\ShopProduct::find($this->_configs['id'])->toArray();
		
		// 总金额
		// $total_fee = $productArr['sale_price']*((int)$this->_configs['amounts']);
		$total_fee = 0;
		
		// 计算当前总金额
		if(!empty($_COOKIE['products'])){
			$productsArr = unserialize($_COOKIE['products']);
			foreach ($productsArr as $productsArr_key => $productsArr_value) {
				if($productsArr_key==$this->_configs['id']){
					// $extraMoney = 0;
					// if(!empty($productsArr_value['attributes'])){
					// 	$extraMoney = \EloquentModel\ProductRelAttribute::whereIn("id", $productsArr_value['attributes'])->sum("price");
					// }
					if(!empty($productsArr_value['sku_id'])){
						$curSku = \EloquentModel\Sku::find($productsArr_value['sku_id']);
						if(!empty($curSku)){
							$total_fee = $curSku['price'];
						}
					}
					break;
				}
			}

			// 总金额
			$total_fee = $total_fee*$this->_configs['amounts'];

		}

		// 立即购买的优惠券
		$coupon = $this->getMaxDecreaseCouponForBuyRightNowOne($productsArr_value['sku_id'], $user[0]['id']);

		// $coupon = $this->getMaxDecreaseCoupon($this->_configs['id'], $user[0]['id'], $total_fee);

		$data = empty($coupon)? null:$coupon;

		$return = $this->functionObj->toAppJson($data, '001', 'ok', true);

		// 输出
		$this->responseResult($return);

	}

	/**
	* 获取此时能用最大减免优惠券数组应只有一条记录
	* 且该优惠券不能过期
	* @author xww
	* @param  $pids   		int/string   product's ids
	* @param  $uid   		int/string   user's id
	* @param  $totalPrice   int/string   total price
	* @return array
	*/ 
	public function getMaxDecreaseCoupon($pids, $uid, $totalPrice)
	{
		
		// 获取在此类与全场分类下该用户拥有的尚未使用的优惠券
		$couponsQuery = \EloquentModel\UserRelCoupons::leftJoin("coupons_rel_class", 'coupons_rel_class.coupon_id', '=', 'user_rel_coupons.coupon_id')
												->where("user_rel_coupons.user_id", '=', $uid)
												->where("is_used", '=', 0)
												->where("coupons_rel_class.is_deleted", '=', 0)
												->select("coupons_rel_class.*");
		
		// 此时专区id数组
		$zoneArr = \EloquentModel\ShopProduct::leftJoin("shop_product_class_rel_shop_product", 'shop_product_class_rel_shop_product.product_id', '=', 'shop_products.id')
								  ->leftJoin("shop_product_class", 'shop_product_class.id', '=', 'shop_product_class_rel_shop_product.spc_id')
								  ->whereIn("shop_products.id", explode(',', $pids))
								  ->distinct('spc_id')
								  ->get(['shop_product_class_rel_shop_product.spc_id'])
								  ->toArray();
        				  
		// 该商品有分类
		if(!empty($zoneArr) && count($zoneArr)==1){
			$zoneId = $zoneArr[0]['spc_id'];

			$couponsQuery = $couponsQuery->where(function($query)use($zoneId){
				$query->orWhere("class_id", '=', 0)
					  ->orWhere("class_id", '=', $zoneId);	
			});

		} else {
			$couponsQuery = $couponsQuery->where("class_id", '=', 0);
		}

		// 所有的优惠券
		$coupons = $couponsQuery->get()->toArray();

		// 当前能减免的额度
		$curDescreasePrice = 0;

		// 当前数组键值
		$curKey = 0;

		if(!empty($coupons)){
			foreach ($coupons as $key => $coupon) {

				// 判断优惠券是否过期
				if(($coupon['useful_time_end']-$coupon['useful_time_start'])<=0 || $coupon['useful_time_end']<time()){continue;}

				// 判断当前额度是否满足最小上限
				if($coupon['upper_limit']>$totalPrice) {continue;}

				// 判断是否比当前减免额度大
				if($coupon['decrease_price']>$curDescreasePrice){$curDescreasePrice=$coupon['decrease_price'];$curKey=$key;}

			}

			// 是否有可以使用的优惠券
			return empty($curDescreasePrice)? []:$coupons[$curKey];

		} else {
			return [];
		}

	}

	/**
	* 获取适合购物车的优惠券
	* @author xww
	* @return string json
	*/ 
	public function getSuitableCouponForShopCart()
	{

		//验证--购物车id,用户令牌与账号
		$this->configValid('required',$this->_configs,['user_login', 'access_token']);

		//获取用户
		$user = $this->getUserApi();

		//获取以,分隔的购物车id
		$lists = \EloquentModel\ShopCart::whereIn("id",explode(',', $this->_configs['ids']))
								->get()
								->toArray();

		if(empty($lists)){
			$return = $this->functionObj->toAppJson(null, '001', 'ok', true);
			// 输出
			$this->responseResult($return);
			return false;
		}


		// 存储商品id至ids  具体用途未知
		$ids = [];
		foreach ($lists as $singleCart) {
			if(!in_array($singleCart['product_id'], $ids)){array_push($ids, $singleCart['product_id']);}
		}

		// 计算总价格
		$total_fee = 0;
		$items = explode(',', $this->_configs['ids']);
		foreach ($items as $key => $item) {
			// 购物车源对象
			$scObj = new \EloquentModel\ShopCart;

			$shopCart = $scObj->where('is_deleted', '=', 0)
							    ->find($item);

			// 获取商品
			// $product = \EloquentModel\ShopProduct::find($shopCart['product_id']);

			$extraPrice = 0;
			$skuTemp = 0;
			if(!empty($shopCart['pra_ids'])){
				// $extraPrice = \EloquentModel\ProductRelAttribute::whereIn("id", explode(',', $shopCart['pra_ids']))
				// 								  ->sum("price");
				$skuTemp = \EloquentModel\Sku::find($shopCart['pra_ids'])['price'];
			}
			
			// 累加金额
			$total_fee = $total_fee+((int)$skuTemp+$extraPrice)*(int)$shopCart['amounts'];

		}
		
		// $coupon = $this->getMaxDecreaseCoupon(implode(',', $ids), $user[0]['id'], $total_fee);
		$coupon = $this->getMaxDecreaseCouponForShopCartOne(explode(',', $this->_configs['ids']), $user[0]['id']);

		$coupon = empty($coupon)? null:$coupon;
		$return = $this->functionObj->toAppJson($coupon, '001', 'ok', true);
		
		// 输出
		$this->responseResult($return);

	}

	/**
	* 评论一条订单
	* @author xww
	* @return string json
	*/
	public function comment()
	{
		
		//验证--订单id,用户令牌与账号,分数
		$this->configValid('required',$this->_configs,['user_login', 'access_token', 'id', 'score']);

		//获取用户
		$user = $this->getUserApi();

		// 订单号
		// $this->_configs['order_num'] = \EloquentModel\WechatOrder::find($this->_configs['id'])['order_num'];

		// 时间戳
		$data['create_time'] = time();
		$data['update_time'] = time();
		$data['order_id'] = $this->_configs['id'];
		$data['sku_id'] = empty($this->_configs['skuId'])? 0:$this->_configs['skuId'];
		$data['product_id'] = empty($this->_configs['productId'])? 0:$this->_configs['productId'];
		$data['user_id'] = $user[0]['id'];
		$data['content'] = empty($this->_configs['content'])? '该用户未做评价':$this->_configs['content'];
		$data['score'] = $this->_configs['score'];

		// 组图
		if(!empty($this->_configs['imgs'])){
			$data['images'] = serialize(explode('-,-', $this->_configs['imgs']));
		}

		// 结果
		$rs = \EloquentModel\OrderRelComment::insert($data);

		if($rs) { $return = $this->functionObj->toAppJson(null, '001', 'ok', true);}
		else {$return = $this->functionObj->toAppJson(null, '005', 'fail', false);}

		// 输出
		$this->responseResult($return);

	} 

	/**
	* 收藏与取消收藏商品
	* @author xww
	* @return string json
	*/ 
	public function collectProduct()
	{
		//验证--商品id,用户令牌与账号
		$this->configValid('required',$this->_configs,['id','user_login', 'access_token']);

		//获取用户
		$user = $this->getUserApi();

		$collect = \EloquentModel\CollectProduct::where("user_id", '=', $user[0]['id'])
										  ->where("product_id", '=', $this->_configs['id'])
										  ->get()
										  ->toArray();

		if(empty($collect)){
			$insert['product_id'] = $this->_configs['id'];
			$insert['user_id'] = $user[0]['id'];
			$insert['is_deleted'] = 0;
			$insert['create_time'] = time();
			$rs = \EloquentModel\CollectProduct::insert($insert);
			if($rs){$return = $this->functionObj->toAppJson(true, '001', 'ok', true);}
			else{$return = $this->functionObj->toAppJson(null, '005', 'Faile', false);}
		} else {
			$update['is_deleted'] = empty($collect[0]['is_deleted'])? 1:0;

			$rs = \EloquentModel\CollectProduct::where("id", '=', $collect[0]['id'])->update($update);
			if($rs){$return = $this->functionObj->toAppJson((bool)$collect[0]['is_deleted'], '001', 'ok', true);}
			else{$return = $this->functionObj->toAppJson(null, '003', 'Faile', false);}
		}

		// 输出
		$this->responseResult($return);

	}

	/**
	* 用户确认收货
	* @author xww
	* @return json string/object
	*/ 
	public function getGoods()
	{
		//验证--商品id,用户令牌与账号
		$this->configValid('required',$this->_configs,['order_num','user_login', 'access_token']);

		//获取用户
		$user = $this->getUserApi();

		// 订单对象
		$orderObj = new \EloquentModel\WechatOrder;

		$order = $orderObj->where("order_num", '=', $this->_configs['order_num'])
						  ->where("userid", '=', $user[0]['id'])
						  ->take(1)
						  ->get()
						  ->toArray();

		if(empty($order)){
			$return = $this->functionObj->toAppJson(null, '006', 'can not find', false);
		} else {
			// 将订单状态改为已收货
			$result = $orderObj->where("id", '=', $order[0]['id'])->update(['order_status'=>4]);
			if($result){
				// 修改成功
				$return = $this->functionObj->toAppJson(null, '001', 'ok', true);
			} else {
				// 修改失败
				$return = $this->functionObj->toAppJson(null, '003', 'fail', false);
			}

		}
		
		// 输出
		$this->responseResult($return);

	}

	/**
	* 获取适合属性字符串的skuid
	* @author xww
	* @param  [$id]        int/string   商品id
	* @param  [$attrStr]   string	    implode by ','  为空则寻找没有规格的属性
	* @return id           int
	*/ 
	public function getSkuId($id, $attrStr)
	{
		
		// 查询该商品所有sku
		$skus = \EloquentModel\Sku::where("product_id", '=', $id)
						   ->where("is_deleted", '=', 0)
						   ->get()
						   ->toArray();

		if(empty($skus)){
			// 空
			return '';
		} else {
			if(!empty($attrStr)){
				$attributesArr = explode(',', $attrStr);

				// 属性数量
				$attrCount = count($attributesArr);

				// 决定的sku
				$curSku = [];

				foreach ($skus as $sku) {
					$attrs = \EloquentModel\SkuRelAttribute::where("sku_id", '=', $sku['id'])
												  ->where("is_deleted", '=', 0)
												  ->get()
												  ->toArray();
					// 获取属性数组
					$attrIds = [];
					foreach ($attrs as $attr) {
						array_push($attrIds, $attr['sub_attribute_id']);
					}

					// 排序属性数组
					if(!empty($attrIds)) {sort($attrIds);}

					// 属性排序
					sort($attributesArr);

					$count = count($attrs);
					if($count==$attrCount&&$attrIds==$attributesArr) {$curSku = $sku; break;}
					
				}
			} else {
				// 决定的sku
				$curSku = [];
				foreach ($skus as $sku) {
					$temp = \EloquentModel\SkuRelAttribute::where("sku_id", '=', $sku['id'])
												  ->where("is_deleted", '=', 0)
												  ->get()
												  ->toArray();
					if(empty($temp)){$curSku = $sku;}
				}
			}
			
			if(empty($curSku)){
				// 没有符合sku
				return '';
			} else {
				return $curSku['id'];
			}

		}

	}

	/**
	* 关闭工单的评论
	* @author xww
	* @return json    string/object
	*/ 
	public function close()
	{

		try {
			//验证--订单id,用户令牌与账号,分数
			$this->configValid('required',$this->_configs,['user_login', 'access_token', 'id']);

			//获取用户
			$user = $this->getUserApi();

			$order  =\EloquentModel\WechatOrder::where("userid", $user[0]['id'])
												->where("id", $this->_configs['id'])
												->take(1)
												->get()
												->toArray();

			if(empty($order)){
				throw new \Exception("用户非订单所有人", '066');
			}

			$data['order_num'] = $order[0]['order_num'];
			$data['content'] = '';
			$data['score'] = 10;
			$data['pics'] = '';
			$data['create_time'] = time();
			$data['update_time'] = time();

			$rs = \EloquentModel\CommentOrder::insert($data);

			if($rs) {
				$return = $this->functionObj->toAppJson(null, '001', 'ok', true);	
			} else {
				throw new \Exception("关闭失败", '005');
			}

			// 获取工单对应的商品
			$prosucts = \EloquentModel\OrderRelComment::where("is_deleted", 0)
										  ->where("is_hidden", 0)
										  ->where("order_id", $this->_configs['order_id'])
										  ->select("product_id")
										  ->groupBy("product_id")
										  ->get()
										  ->toArray();
										  
			if(!empty($prosucts)){
				$staticViewObj = new \VirgoModel\StaticViewModel;
				for ($i=0; $i < count($prosucts); $i++) { 
					// 更新缓存
					$staticViewObj->createStaticView($prosucts[$i]['product_id'], $_SERVER['DOCUMENT_ROOT']."/../app/views/runtime/".md5($prosucts[$i]['product_id']).".html", '/detail?id='.$prosucts[$i]['product_id']);
				}
			}

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 提出退款申请
	* @author xww
	* @return json    string/object
	*/ 
	public function salesReturn()
	{
		
		try {

			//验证--订单id,用户令牌与账号,分数
			$this->configValid('required',$this->_configs,['reason', 'orderId','skuId', "receiveType", "account"]);

			$curTime = time();

			// 判断是否存在
			$has = \EloquentModel\SalesReturn::where("is_deleted", 0)
									  ->where("is_hidden", 0)
									  ->where("order_id", $this->_configs['orderId'])
									  ->where("sku_id", $this->_configs['skuId'])
									  ->count();

			if($has){
				throw new \Exception("申请已存在", '068');
			}

			$insertData['order_id'] = $this->_configs['orderId'];
			$insertData['sku_id'] = $this->_configs['skuId'];
			$insertData['return_reason_id'] = $this->_configs['reason'];
			$insertData['return_way'] = $this->_configs['receiveType'];
			$insertData['return_account'] = $this->_configs['account'];

			$insertData['create_time'] = $curTime;
			$insertData['update_time'] = $curTime;

			if(!empty($this->_configs['images'])){
				$insertData['images'] = $this->_configs['images'];
			}

			if(!empty($this->_configs['description'])){
				$insertData['description'] = $this->_configs['description'];
			}

			$rs = \EloquentModel\SalesReturn::insert($insertData);

			if(!$rs){
				throw new \Exception("数据新增失败", '006');
			}

			$return = $this->functionObj->toAppJson(null, '001', '申请已提交，请耐心等待', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 第一个10元活动 立即购买优惠券   金额范围于50-100之间
	*/ 
	public function getMaxDecreaseCouponForBuyRightNowOne($skuId, $uid)
	{

		// 获取全场分类下该用户拥有的尚未使用的优惠券
		$couponsQuery = \EloquentModel\UserRelCoupons::leftJoin("coupons_rel_class", 'coupons_rel_class.coupon_id', '=', 'user_rel_coupons.coupon_id')
												->where("user_rel_coupons.user_id", '=', $uid)
												->where("is_used", '=', 0)
												->where("active_flag", 1)
												->where("coupons_rel_class.is_deleted", '=', 0)
												->where("coupons_rel_class.class_id", 0)
												->select("coupons_rel_class.*");

		$curSku = \EloquentModel\Sku::find($skuId);

		// 所有的优惠券
		$coupons = $couponsQuery->get()->toArray();

		// 当前能减免的额度
		$curDescreasePrice = 0;

		// 当前数组键值
		$curKey = 0;

		if(!empty($coupons)){

			if(empty($curSku)){
				return [];
			}

			// 判断金额范围
			if($curSku['price']<6600){
				return [];	
			}

			$totalPrice = $curSku['price'];

			foreach ($coupons as $key => $coupon) {

				// 判断优惠券是否过期
				if(($coupon['useful_time_end']-$coupon['useful_time_start'])<=0 || $coupon['useful_time_end']<time()){continue;}

				// 判断当前额度是否满足最小上限
				if($coupon['upper_limit']>$totalPrice) {continue;}

				// 判断是否比当前减免额度大
				if($coupon['decrease_price']>$curDescreasePrice){$curDescreasePrice=$coupon['decrease_price'];$curKey=$key;}

			}

			// 是否有可以使用的优惠券
			return empty($curDescreasePrice)? []:$coupons[$curKey];

		} else {
			return [];
		}

	}

	/**
	* 第一个10元活动 购物车优惠券   金额范围于50-100之间
	*/ 
	public function getMaxDecreaseCouponForShopCartOne($ids, $uid)
	{

		// 获取全场分类下该用户拥有的尚未使用的优惠券
		$couponsQuery = \EloquentModel\UserRelCoupons::leftJoin("coupons_rel_class", 'coupons_rel_class.coupon_id', '=', 'user_rel_coupons.coupon_id')
												->where("user_rel_coupons.user_id", '=', $uid)
												->where("is_used", '=', 0)
												->where("active_flag", 1)
												->where("coupons_rel_class.is_deleted", '=', 0)
												->where("coupons_rel_class.class_id", 0)
												->select("coupons_rel_class.*");

		// 所有的优惠券
		$coupons = $couponsQuery->get()->toArray();

		// 当前能减免的额度
		$curDescreasePrice = 0;

		// 当前数组键值
		$curKey = 0;
		if(!empty($coupons)){
			
			// 循环购物车ids
			for ($i=0; $i < count($ids); $i++) {
				// 购物车源对象
				$scObj = new \EloquentModel\ShopCart;

				$shopCart = $scObj->where('is_deleted', '=', 0)
								    ->find($ids[$i]);

				if(empty($shopCart)){
					continue;
				}
				// 获取商品
				// $product = \EloquentModel\ShopProduct::find($shopCart['product_id']);

				$extraPrice = 0;
				$skuTemp = 0;
				if(!empty($shopCart['pra_ids'])){
					// $extraPrice = \EloquentModel\ProductRelAttribute::whereIn("id", explode(',', $shopCart['pra_ids']))
					// 								  ->sum("price");
					$skuTempObj = \EloquentModel\Sku::find($shopCart['pra_ids']);

					if(empty($skuTempObj)){
						continue;
					}

					$skuTemp = $skuTempObj['price'];

					// 判断金额范围
					if($skuTemp<6600){
						continue;	
					}

					$totalPrice = $skuTemp;

					foreach ($coupons as $key => $coupon) {

						// 判断优惠券是否过期
						if(($coupon['useful_time_end']-$coupon['useful_time_start'])<=0 || $coupon['useful_time_end']<time()){continue;}

						// 判断当前额度是否满足最小上限
						if($coupon['upper_limit']>$totalPrice) {continue;}

						// 判断是否比当前减免额度大
						if($coupon['decrease_price']>$curDescreasePrice){
							$curDescreasePrice=$coupon['decrease_price'];$curKey=$key;
						}

					}

				} else {
					continue;
				}
				
				// 累加金额
				// $total_fee = $total_fee+((int)$skuTemp+$extraPrice)*(int)$shopCart['amounts'];

			}

		} else {

			return [];

		}
		
		// 是否有可以使用的优惠券
		return empty($curDescreasePrice)? []:$coupons[$curKey];

	}

	/**
	* 更新用户头像
	* @author 	xww
	* @return 	json
	*/
	public function updateAvatar()
	{
		
		try{

			//获取用户
			$user = $this->getUserApi();

			// 修改用户头像
			$this->configValid('required',$this->_configs,['avatarStr', 'user_login', 'access_token'] );

			// 实例化对象
			$model = new \VirgoModel\UserModel;

			// 更新数据
			$data['update_time'] = time();
			$data['avatar'] = $this->_configs['avatarStr'];

			$rs = $model->partUpdate($user[0]['id'], $data);

			if( !$rs ) {
				throw new \Exception("更新头像失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '头像修改成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}