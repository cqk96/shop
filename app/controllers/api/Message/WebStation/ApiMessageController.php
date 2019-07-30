<?php
namespace VirgoApi\Message\WebStation;
use Illuminate\Database\Capsule\Manager as DB;
class ApiMessageController extends \VirgoApi\ApiBaseController
{

	/**
	* api参数数组
	* @var array
	*/
	private $_configs;

	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
		$this->_configs = parent::change();
	}

	/**
	* @SWG\Post(path="/api/v1/message/create", tags={"Message"}, 
	*  summary="创建待推送消息",
	*  description="用户鉴定 传入内容和要推送的人员创建消息",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="content", type="string", required=true, in="formData", description="消息内容"),
	*  @SWG\Parameter(name="userIds", type="string", required=true, in="formData", description="用户id以,分隔"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "创建推送消息成功", "success": true } } }
	*  )
	* )
	* 创建消息 以及 待推送人员
	* @author 	xww
	* @return 	json
	*/
	public function create()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 必要验证 id userIds为,分割的用户id字符串
			$this->configValid('required',$this->_configs,['content', 'userIds']);

			DB::beginTransaction();

			$isBlock = true;

			// if( empty($_COOKIE['user_id']) ) {
			// 	throw new \Exception("请重新登陆", '007');
			// }

			// 实例化对象

			// 站内消息
			$webStationMessageModelObj = new \VirgoModel\WebStationMessageModel;

			// 站内待推送消息
			$webStationWaitForPushModelObj = new \VirgoModel\WebStationWaitForPushModel;

			$userId = $uid;

			$users = explode(",", $this->_configs['userIds']);

			for ($i=0; $i < count($users); $i++) { 
				$curId = (int)$users[$i];
				if( $curId == 0) {
					continue;
				}

				$userIds[] = $curId;

			}

			if( count($userIds)==0 ) {
				throw new \Exception("错误的参数", '014');
			}

			// 创建消息
			$data['content'] = $this->_configs['content'];
			$data['author_id'] = $userId;
			$data['type_id'] = 1;
			$data['is_pushed'] = 0;
			$data['is_deleted'] = 0;
			$data['create_time'] = time();
			$data['update_time'] = time();
			$msgId = $webStationMessageModelObj->create($data);
			unset($data);

			if( !$msgId ) {
				throw new \Exception("添加消息失败", '005');
			}

			$userMessageData = [];
			for ($i=0; $i < count($userIds); $i++) { 
				
				$temp['msg_id'] = $msgId;
				$temp['user_id'] = $userIds[$i];
				$temp['is_deleted'] = 0;
				$temp['create_time'] = time();
				$temp['update_time'] = time();

				$userMessageData[] = $temp;

			}

			$rs = $webStationWaitForPushModelObj->multiCreate( $userMessageData );

			if( !$rs ) {
				throw new \Exception("创建待推送消息失败", '005');				
			}

			DB::commit();

			$return = $this->functionObj->toAppJson(null, '001', '创建推送消息成功', true);

		} catch(\Exception $e) {

			if( isset($isBlock) ) {
				DB::rollback();	
			}
			
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/message/push", tags={"Message"}, 
	*  summary="推送消息",
	*  description="用户鉴定 将消息id传入后 进行极光推送",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="消息id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "推送成功", "success": true } } }
	*  )
	* )
	* 推送消息
	* @author 	xww
	* @return 	json
	*/
	public function push()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 必要验证 id userIds为,分割的用户id字符串
			$this->configValid('required',$this->_configs,['id']);

			DB::beginTransaction();

			$isBlock = true;

			// if( empty($_COOKIE['user_id']) ) {
			// 	throw new \Exception("请重新登陆", '007');
			// }

			// 实例化对象

			// 用户
			$userModelObj = new \VirgoModel\UserModel;

			// 用户消息
			// $userMessageModelObj = new \VirgoModel\UserMessageModel;

			// 站内消息
			$webStationMessageModelObj = new \VirgoModel\WebStationMessageModel;

			// 站内待推送消息
			$webStationWaitForPushModelObj = new \VirgoModel\WebStationWaitForPushModel;

			// 推送结果
			$webStationAlreadyPushedResultModelObj = new \VirgoModel\WebStationAlreadyPushedResultModel;

			$message = $webStationMessageModelObj->read( $this->_configs['id'] );
			if( empty($message) ) {
				throw new \Exception("消息不存在", '006');
			}

			$message = \EloquentModel\WebStationMessage::lockForUpdate()->find($this->_configs['id']);

			// 获取此时最大推送次数
			$times = $webStationAlreadyPushedResultModelObj->getMaxTimes($message['id'])+1;

			if( $message['is_pushed']==1 ) {
				throw new \Exception("消息已推送", '006');	
			}

			// 获取当前消息的所有待推送用户
			$waitingForUsers = $webStationWaitForPushModelObj->getMsgUsers($message['id']);
			if( empty($waitingForUsers) ) {
				throw new \Exception("消息不存在等待发送的用户", '006');		
			}

			// 推送消息
			$userMessages = [];
			$pushedMessages = [];
			$useIds = [];

			$app_key = $GLOBALS['globalConfigs']['jpush']['appkey'];
			$master_secret = $GLOBALS['globalConfigs']['jpush']['masterSecret'];

			/*没有极光配置 暂时不进行推送*/
			if( empty($app_key) || empty($master_secret) ) {
				throw new \Exception("未配置极光推送", '098');
			}


			/*循环递归推送*/
			$curTime = time();
			for ($i=0; $i < count($waitingForUsers); $i++) { 

				$curUser = $userModelObj->readSingleTon( $waitingForUsers[$i]['user_id'] );

				if( empty($waitingForUsers[$i]['user_id']) ) {
					continue;
				}

				$username = $curUser['user_login'];
				$pushedResult = 0;
				try {

					$client = new \JPush\Client($app_key, $master_secret);
					$push_payload = $client->push()
									    ->setPlatform('all')
									    ->addAlias($username)
									    ->message('您有一条新消息', [
										  'title' => '您有一条新消息',
										  'content_type' => 'text'
										])
									    ->send();
					$pushedResult = 1;

				} catch (\JPush\Exceptions\APIConnectionException $e) {
					$message = $e->getMessage();
				} catch (\JPush\Exceptions\APIRequestException $e) {
					$message = $e->getMessage();
				}

				// $useIds[] = $waitingForUsers[$i]['user_id'];

				// $userMessageData['message_type_id'] = 2;
				// $userMessageData['content'] = $message['content'];
				// $userMessageData['user_id'] = $waitingForUsers[$i]['user_id'];
				// $userMessageData['read'] = 0;
				// $userMessageData['create_time'] = time();
				// $userMessageData['update_time'] = time();

				// $userMessages[] = $userMessageData;


				$tempPushedMessages['type_id'] = 1;
				$tempPushedMessages['msg_id'] = $this->_configs['id'];
				$tempPushedMessages['user_id'] = $waitingForUsers[$i]['user_id'];
				$tempPushedMessages['times'] = $times;
				$tempPushedMessages['is_deleted'] = 0;
				$tempPushedMessages['is_done'] = 1;
				$tempPushedMessages['pushed_result'] = $pushedResult;
				$tempPushedMessages['create_time'] = $curTime;
				$tempPushedMessages['update_time'] = $curTime;

				// 将推送的消息加入到推送历史
				$rs4 = $webStationAlreadyPushedResultModelObj->create( $tempPushedMessages );

				// $pushedMessages[] = $tempPushedMessages;
			}

			// $rs1 = $userMessageModelObj->multiCreate( $userMessages );
			// if( !$rs1 ) {
			// 	throw new \Exception("消息推送用户失败", '005');
			// }

			// 将消息置为已推送
			$temp = [];
			$temp['is_pushed'] = 1;
			$temp['update_time'] = time();
			$rs2 = $webStationMessageModelObj->updateParts($this->_configs['id'], $temp);
			unset( $temp );
			if( !$rs2 ) {
				throw new \Exception("修改消息失败", '003');
			}

			// 将待推送里的消息删除
			$rs3 = $webStationWaitForPushModelObj->hardDeleteMsg($this->_configs['id']);

			if( !$rs3 ) {
				throw new \Exception("删除待推送消息用户失败", '012');
			}

			// 根据指定的用户id获取用户
			$pushUsers = $userModelObj->getUserWithIds( $useIds );

			// if( !empty($pushUsers) ) {
			// 	$toUserObj = [];
			// 	for ($i=0; $i < count($pushUsers); $i++) { 
			// 		if( strlen($pushUsers[$i]['user_login'])==11 ) {
			// 			$toUserObj[] = $pushUsers[$i]['user_login'];
			// 		}
			// 	}

			// 	if( !empty($toUserObj) ) {

			// 		try {

			// 			//推送
			// 			$app_key = $GLOBALS['globalConfigs']['jpush']['appkey'];
			// 			$master_secret = $GLOBALS['globalConfigs']['jpush']['masterSecret'];
			// 			$client = new \JPush\Client($app_key, $master_secret);
			// 			$push_payload = $client->push()
			// 							    ->setPlatform('all')
			// 							    ->addAlias($toUserObj)
			// 							    ->message('您有一条新站内信息', [
			// 								  'title' => '您有一条新站内信息',
			// 								  'content_type' => 'text'
			// 								])
			// 							    ->send();

			// 		} catch (\JPush\Exceptions\APIConnectionException $e) {
			// 			$message = $e->getMessage();
			// 		} catch (\JPush\Exceptions\APIRequestException $e) {
			// 			$message = $e->getMessage();
			// 		}

			// 	}

			// }

			// $returnMessage = '创建推送消息成功';

			// if( isset($message) ) {
			// 	$returnMessage .= ", 极光推送失败: " . $message;
			// }

			DB::commit();

			$return = $this->functionObj->toAppJson(null, '001', '推送成功', true);

		} catch(\Exception $e) {

			if( isset($isBlock) ) {
				DB::rollback();	
			}
			
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}	

	}

	/**
	* @SWG\Get(path="/api/v1/message/lists", tags={"Message"}, 
	*  summary="获取消息列表",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "AreaListsObj", "code": "001", "message": "获取消息列表成功", "totalCount": 3 } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/AreaListsObj"
	*   )
	*  )
	* )
	*/
	public function lists()
	{
		
		try {

			//验证 
			$user = $this->getUserApi($this->_configs, 1);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\WebStationAlreadyPushedResultModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 4]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和查看数据权限", '070');
			}

			//验证
			$this->configValid('required',$this->_configs,['page', 'size']);

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$authorId = $uid==1? null:$uid;

			/*0 未发送 1已发送*/
			$statusId = empty($this->_configs['statusId'])? 0:1;
			$pageObj = $model->getMessageListsObject($authorId, $statusId, $skip, $size);

			$data = [];

			$data = empty($pageObj->data)? null:$pageObj->data;
			// $data['totalPage'] = intval( ceil($pageObj->totalCount/$pageObj->size) );
			// $data['currentPage'] = intval( $pageObj->current_page );
			$totalCount = $pageObj->totalCount;

			$return = $this->functionObj->toLayuiJson($data, '001', '获取消息列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/message/result", tags={"Message"}, 
	*  summary="获取消息推送结果",
	*  description="用户鉴权后 传入消息id获取消息推送结果",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="消息id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "MessagePushResult", "status": { "code": "001", "message": "获取消息推送结果成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/MessagePushResult"
	*   )
	*  )
	* )
	* 获取消息推送结果
	* @author 	xww
	* @return 	json
	*/
	public function result()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\WebStationAlreadyPushedResultModel;

			//验证
			$this->configValid('required',$this->_configs,['id']);

			$id = $this->_configs['id'];

			$data = $model->getMessagePushResult($id);
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取消息推送结果成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/message/unpushed/detail", tags={"Message"}, 
	*  summary="获取未推送消息详情",
	*  description="用户鉴权后 传入消息id获取未推送消息详情",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="消息id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "MessageUnpushDetail", "status": { "code": "001", "message": "获取待推送消息详情成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/MessageUnpushDetail"
	*   )
	*  )
	* )
	* 获取未推送消息推送人员列表
	* @author 	xww
	* @return 	json
	*/
	public function unpushedDetail()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\WebStationWaitForPushModel;

			//验证
			$this->configValid('required',$this->_configs,['id']);

			$id = $this->_configs['id'];

			$data = $model->getUnpushedDetail($id);
			$data = empty($data)? null:$data;

			// if( empty($data) ) {
			// 	$userIds = null;
			// } else {
			// 	$userIds = [];
			// 	for ($i=0; $i < count($data); $i++) { 
			// 		$userIds[] = $data[$i]['user_id'];
			// 	}
			// }
			

			$return = $this->functionObj->toAppJson($data, '001', '获取待推送消息详情成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/message/update", tags={"Message"}, 
	*  summary="消息更新",
	*  description="用户鉴定 将消息id，内容，推送人员进行消息更新",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="msgId", type="integer", required=true, in="formData", description="消息id"),
	*  @SWG\Parameter(name="content", type="string", required=true, in="formData", description="消息内容"),
	*  @SWG\Parameter(name="userIds", type="string", required=true, in="formData", description="用户id以,分隔"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "推送成功", "success": true } } }
	*  )
	* )
	* 消息更新
	* @author 	xww
	* @return 	json
	*/
	public function update()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 必要验证 id userIds为,分割的用户id字符串
			$this->configValid('required',$this->_configs,["msgId", 'content', 'userIds']);

			DB::beginTransaction();

			$isBlock = true;

			$id = $this->_configs['msgId'];

			// if( empty($_COOKIE['user_id']) ) {
			// 	throw new \Exception("请重新登陆", '007');
			// }

			// 实例化对象

			// 站内消息
			$webStationMessageModelObj = new \VirgoModel\WebStationMessageModel;

			// 站内待推送消息
			$webStationWaitForPushModelObj = new \VirgoModel\WebStationWaitForPushModel;

			$data = $webStationMessageModelObj->read($id);

			if( empty($data) ) {
				throw new \Exception("数据不存在", '006');
			}

			// 更新消息
			$temp['content'] = $this->_configs['content'];
			$temp['update_time'] = time();
			$rs1 = $webStationMessageModelObj->updateParts($id, $temp);
			unset($temp);

			if( !$rs1 ) {
				throw new \Exception("消息更新失败", '003');
			}

			// 删除该消息待推送人员
			$hasRecords = $webStationWaitForPushModelObj->getMsgUsers($id);
			if( !empty($hasRecords) ) {
				$rs2 = $webStationWaitForPushModelObj->hardDeleteMsg($id);
				if( !$rs2 ) {
					throw new \Exception("消息待推送人员删除失败", '012');
				}
			}

			$userId = $uid;

			$users = explode(",", $this->_configs['userIds']);

			for ($i=0; $i < count($users); $i++) { 
				$curId = (int)$users[$i];
				if( $curId == 0) {
					continue;
				}

				$userIds[] = $curId;

			}

			if( count($userIds)==0 ) {
				throw new \Exception("错误的参数", '014');
			}

			$userMessageData = [];
			for ($i=0; $i < count($userIds); $i++) { 
				
				$temp['msg_id'] = $id;
				$temp['user_id'] = $userIds[$i];
				$temp['is_deleted'] = 0;
				$temp['create_time'] = time();
				$temp['update_time'] = time();

				$userMessageData[] = $temp;

			}

			$rs = $webStationWaitForPushModelObj->multiCreate( $userMessageData );

			if( !$rs ) {
				throw new \Exception("创建待推送人员消息失败", '005');				
			}

			DB::commit();

			$return = $this->functionObj->toAppJson(null, '001', '更新推送消息成功', true);

		} catch(\Exception $e) {

			if( isset($isBlock) ) {
				DB::rollback();	
			}
			
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

}