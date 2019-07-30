<?php
namespace VirgoApi\User\Instruction;
use Illuminate\Database\Capsule\Manager as DB;
use VirgoApi;
class ApiInstructionController extends VirgoApi\ApiBaseController{

	/**
	* api参数数组
	* @var array
	*/
	private $_configs;

	public function __construct()
	{
		$this->_configs = parent::change();
		$this->functionObj = new \VirgoUtil\Functions;
	}

	/**
	* @SWG\Get(path="/api/v1/user/instruction/creatorLists", tags={"Instruction"}, 
	*  summary="获取我创建的指令列表",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "CreatorInstructionList", "status": { "code": "001", "message": "获取我创建的指令列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/CreatorInstructionList")
	*   )
	*  )
	* )
	*/
	public function creatorLists()
	{
		
		try {

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\InstructionsMessageModel;

			//验证
			$this->configValid('required',$this->_configs,['page', 'size']);

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$data = $model->getCreatorInstructionLists($uid, $skip, $size);
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取我创建的指令列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/user/instruction/lists", tags={"Instruction"}, 
	*  summary="获取指派给我的指令列表",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="statusId", type="integer", required=false, in="query", description="是否已读 默认0否1是", default=0),
	*  @SWG\Parameter(name="isDone", type="integer", required=false, in="query", description="是否已完成 默认0否1是", default=0),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "InstructionList", "status": { "code": "001", "message": "获取指派给我的指令列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/InstructionList")
	*   )
	*  )
	* )
	* 获取指派给我的指令列表
	* @author 	xww
	* @return 	json
	*/
	public function lists()
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
			$this->configValid('required',$this->_configs,['page', 'size']);

			/*0未读 1已读*/
			$statusId = empty( $this->_configs['statusId'] )? null:$this->_configs['statusId'];

			/*0未完成 1已完成*/
			$isDone = empty( $this->_configs['isDone'] )? null:$this->_configs['isDone'];

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$data = $model->getInstructionLists($uid, $statusId, $isDone, $skip, $size);
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取指派给我的指令列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}
		
	}

	/**
	* @SWG\Post(path="/api/v1/user/instruction/tagRead", tags={"Instruction"}, 
	*  summary="标记阅读一条指令",
	*  description="用户鉴权后 通过传入记录id标记阅读一条指令",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="记录id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "标记指令已读成功", "success": true } } }
	*  )
	* )
	* 标记阅读一条指令
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
			$this->configValid('required',$this->_configs,['id']);

			$id = $this->_configs['id'];

			$data = $model->getUserInstructionWithId($uid, $id);

			if( empty($data) ) {
				throw new \Exception("无法查询到数据", '006');
			}

			if( $data['is_read']==1 ) {
				throw new \Exception("该条指令已阅读", '093');
			}

			$updateData['is_read'] = 1;
			$updateData['update_time'] = time();

			$rs = $model->partUpdate( $id, $updateData );
			if( !$rs ) {
				throw new \Exception("标记指令已读失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '标记指令已读成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/user/instruction/tagDone", tags={"Instruction"}, 
	*  summary="标记完成一条指令",
	*  description="用户鉴权后 通过传入记录id标记阅读一条指令",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="记录id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "标记指令已完成成功", "success": true } } }
	*  )
	* )
	* 标记完成一条指令
	* @author 	xww
	* @return 	json
	*/
	public function tagDone()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			$userName = $user[0]['name'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\WebStationAlreadyPushedResultModel;

			// 指令对象
			$instructionsMessageModel = new \VirgoModel\InstructionsMessageModel;

			// 用户对象
			$userModel = new \VirgoModel\UserModel;

			//验证
			$this->configValid('required',$this->_configs,['id']);

			DB::beginTransaction();

			$isBlock = true;

			$id = $this->_configs['id'];

			$data = $model->getUserInstructionWithId($uid, $id);

			if( empty($data) ) {
				throw new \Exception("无法查询到数据", '006');
			}

			$instruction = $instructionsMessageModel->read( $data['msg_id'] );

			if( empty($instruction) ) {
				throw new \Exception("无法查询到指令", '006');
			}

			if( $data['is_done']==1 ) {
				throw new \Exception("该条指令已完成", '093');
			}

			$updateData['is_done'] = 1;
			$updateData['update_time'] = time();

			$rs = $model->partUpdate( $id, $updateData );
			if( !$rs ) {
				throw new \Exception("标记指令完成失败", '003');
			}

			$content = $userName . "完成了您的一条指令";

			/*反向通知创建者信息*/
			$insertData['type_id'] = 1;
			$insertData['msg_id'] = 0;
			$insertData['user_id'] = $instruction['author_id'];
			$insertData['times'] = 1;
			$insertData['is_done'] = 1;
			$insertData['pushed_result'] = 1;
			$insertData['extra_content'] = $content;
			$insertData['create_time'] = time();
			$insertData['update_time'] = time();

			$recordId = $model->create( $insertData );

			if( !$recordId ) {
				throw new \Exception("新建消息通知失败", '005');
			}

			$toUser = $userModel->readSingleTon( $instruction['author_id'] );

			if( empty($toUser) ) {
				throw new \Exception("无法查询到指令发送人", '006');
			}

			$username = $toUser['user_login'];

			$app_key = $GLOBALS['globalConfigs']['jpush']['appkey'];
			$master_secret = $GLOBALS['globalConfigs']['jpush']['masterSecret'];

			/*没有极光配置 暂时不进行推送*/
			if( empty($app_key) || empty($master_secret) ) {
				throw new \Exception("未配置极光推送", '098');
			}

			$client = new \JPush\Client($app_key, $master_secret);
			$push_payload = $client->push()
							    ->setPlatform('all')
							    ->addAlias($username)
							    ->message($content, [
								  'title' => '您有一条新消息',
								  'content_type' => 'text'
								])
							    ->send();

			DB::commit();
			$return = $this->functionObj->toAppJson(null, '001', '标记指令完成成功', true);

		} catch(\Exception $e) {

			if( isset($isBlock) ) {
				DB::rollback();
			}

			$code = empty($e->getCode())? '095':$e->getCode();
			$return = $this->functionObj->toAppJson(null, str_pad($code, 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 获取指令推送的人员列表信息
	* @author 	xww
	* @return 	json
	*/
	public function userLists()
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
			$this->configValid('required',$this->_configs,['id', 'page', 'size']);

			$id = $this->_configs['id'];

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$data = $model->getInstructionUserLists($id, $skip, $size);
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取推送人员列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/user/instruction/part/tagRead", tags={"Instruction"}, 
	*  summary="标记用户部分消息已读",
	*  description="用户鉴权后 通过传入的ids标记消息",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="ids", type="string", required=true, in="formData", description="指令id以,分隔的字符串"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "标记部分指令已读成功", "success": true } } }
	*  )
	* )
	* 标记部分消息已读
	* @author 	xww
	* @return 	json
	*/
	public function partTagRead()
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

			$records = $model->getUserMultipleInstructionWithIds($uid, $ids);

			if( empty($records) ) {
				throw new \Exception("无法查询到数据", '006');
			}

			$rs = $model->updateUserMultipleInstructionReadWithIds($uid, $ids);

			if( !$rs ) {
				throw new \Exception("标记部分指令已读失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '标记部分指令已读成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/user/instruction/all/tagRead", tags={"Instruction"}, 
	*  summary="标记全部指令已读",
	*  description="用户鉴权 如果不存在未读指令则失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "标记全部指令已读成功", "success": true } } }
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

			$hasRecord = $model->hasUserUnreadInstruction($uid);

			if( empty($hasRecord) ) {
				throw new \Exception("用户没有未读指令", '006');
			}

			$rs = $model->updateUserUnreadInstructionRead($uid);

			if( !$rs ) {
				throw new \Exception("标记全部指令已读失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '标记全部指令已读成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 我创建的指令列表--之后台使用
	* @SWG\Get(path="/api/v1/user/instruction/backCreatorLists", tags={"Instruction"}, 
	*  summary="获取指令列表",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "AreaListsObj", "code": "001", "message": "获取指令列表成功", "totalCount": 3 } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/AreaListsObj"
	*   )
	*  )
	* )
	*/
	public function backCreatorLists()
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
			$pageObj = $model->getInstructionListsObject($authorId, $skip, $size);

			$data = [];

			$data = empty($pageObj->data)? null:$pageObj->data;
			// $data['totalPage'] = intval( ceil($pageObj->totalCount/$pageObj->size) );
			// $data['currentPage'] = intval( $pageObj->current_page );
			$totalCount = $pageObj->totalCount;

			$return = $this->functionObj->toLayuiJson($data, '001', '获取指令列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}

}