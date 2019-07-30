<?php
namespace VirgoApi\User\Message;
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
	* 获取用户未读消息数量
	* @author 	xww
	* @return 	json
	*/
	public function unreadCount()
	{
		
		try{

			if(empty($_COOKIE['user_id'])) {
				
				//获取用户
				$user = $this->getUserApi($this->_configs);	

			} else {
				$userObj = new \VirgoModel\UserModel;
				//获取用户
				$id = $_COOKIE['user_id'];
				$record = $userObj->readSingleTon($id);
				if(empty($record)) {
					throw new \Exception("用户不存在", '006');
				}
				$user[] = $record->toArray();
			}

			// 实例化对象
			$userMessageModelObj = new \VirgoModel\UserMessageModel;
			$data = $userMessageModelObj->getUserUnreadCount($user[0]['id']);

			// 点评消息
			$returndata[0] = 0;

			// 站内通知
			$returndata[1] = 0;

			for ($i=0; $i < count($data); $i++) { 
				
				if( $data[$i]['messageTypeId'] == 1 ) {
					$returndata[0] = $data[$i]['totalCount'];
				} else if( $data[$i]['messageTypeId'] == 2 ) {
					$returndata[1] = $data[$i]['totalCount'];
				}

			}

			$return = $this->functionObj->toAppJson($returndata, '001', '获取用户未读消息数量成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* 获取用户消息列表
	* @author 	xww
	* @deprecated
	* @return 	json
	*/
	// public function lists()
	// {
		
	// 	try{

	// 		if(empty($_COOKIE['user_id'])) {
				
	// 			//获取用户
	// 			$user = $this->getUserApi($this->_configs);	

	// 		} else {
	// 			$userObj = new \VirgoModel\UserModel;
	// 			//获取用户
	// 			$id = $_COOKIE['user_id'];
	// 			$record = $userObj->readSingleTon($id);
	// 			if(empty($record)) {
	// 				throw new \Exception("用户不存在", '006');
	// 			}
	// 			$user[] = $record->toArray();
	// 		}

	// 		//验证
	// 		$this->configValid('required',$this->_configs,['page', 'size']);

	// 		$typeId = empty($this->_configs['messageTypeId'])? 1:(int)$this->_configs['messageTypeId'];

	// 		// 实例化对象
	// 		$userMessageModelObj = new \VirgoModel\UserMessageModel;

	// 		// 判断是否有未读消息
	// 		$hasData = $userMessageModelObj->hasUnreadMessage($user[0]['id'], $typeId);
	// 		if( $hasData ) {
	// 			$userMessageModelObj->readThatMessages($user[0]['id'], $typeId);
	// 		}

	// 		// 分页
	// 		$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
	// 		$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
	// 		$page -= 1;
	// 		$skip = $page*$size;

	// 		$data = $userMessageModelObj->getUserMessageLists($user[0]['id'], $skip, $size, $typeId);

	// 		$data = empty($data)? null:$data;
			
	// 		$dataCount = $userMessageModelObj->getUserMessageListsCount($user[0]['id'], $typeId);
	// 		$totalPage = is_null($size)? 1:ceil( $dataCount / $size );
	// 		$totalPage = is_null($data)? 0:$totalPage;

	// 		$return = $this->functionObj->toAppJson(['data'=>$data, 'totalPage'=>$totalPage], '001', '获取用户消息列表成功', true);

	// 	} catch(\Exception $e) {
	// 		$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
	// 	} finally {
	// 		//输出
	// 		$this->responseResult($return);
	// 	}

	// }

	/**
	* @SWG\Get(path="/api/v1/user/message/lists", tags={"Message"}, 
	*  summary="获取我收到的消息列表",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "MessageLists", "status": { "code": "001", "message": "获取消息列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/MessageLists")
	*   )
	*  )
	* )
	* 获取用户消息列表
	* @author 	xww
	* @return 	json
	*/
	public function lists()
	{

		try {

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\WebStationAlreadyPushedResultModel;

			//验证
			$this->configValid('required',$this->_configs,['page', 'size']);

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$data = $model->getUserMessageLists($uid, $skip, $size);

			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取消息列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/user/message/part/tagRead", tags={"Message"}, 
	*  summary="标记用户部分消息已读",
	*  description="用户鉴权后 通过传入的ids标记消息",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="ids", type="string", required=true, in="formData", description="消息id以,分隔的字符串"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "标记部分消息已读成功", "success": true } } }
	*  )
	* )
	* 标记部分消息已读
	* @author 	xww
	* @return 	json
	*/
	public function tagRead()
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
			$this->configValid('required',$this->_configs,['ids']);

			$idsArr = explode(",", $this->_configs['ids']);

			$ids = [];
			for ($i=0; $i < count($idsArr); $i++) { 
				$id = (int)$idsArr[$i];
				$ids[] = $id;
			}

			if( empty($ids) ) {
				throw new \Exception("Wrong Param ids", '014');
			}

			$records = $model->getUserMultipleMessageWithIds($uid, $ids);

			if( empty($records) ) {
				throw new \Exception("无法查询到数据", '006');
			}

			$rs = $model->updateUserMultipleMessageReadWithIds($uid, $ids);

			if( !$rs ) {
				throw new \Exception("标记部分消息已读失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '标记部分消息已读成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/user/message/all/tagRead", tags={"Message"}, 
	*  summary="标记全部消息已读",
	*  description="用户鉴权 如果不存在未读消息则失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "标记全部消息已读成功", "success": true } } }
	*  )
	* )
	* 标记全部消息已读
	* @author 	xww
	* @return 	json
	*/
	public function allTagRead()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\WebStationAlreadyPushedResultModel;

			$hasRecord = $model->hasUserUnreadMessage($uid);

			if( empty($hasRecord) ) {
				throw new \Exception("用户没有未读消息", '006');
			}

			$rs = $model->updateUserUnreadMessageRead($uid);

			if( !$rs ) {
				throw new \Exception("标记全部消息已读失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '标记全部消息已读成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}
