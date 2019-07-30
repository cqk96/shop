<?php
namespace VirgoApi\Instruction;
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
	* @SWG\Post(path="/api/v1/instruction/create", tags={"Instruction"}, 
	*  summary="推送指令",
	*  description="用户鉴定后 通过传入的指定参数推送指令",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="content", type="string", required=true, in="formData", description="指令内容"),
	*  @SWG\Parameter(name="userIds", type="string", required=true, in="formData", description="要推送的用户id列表 以,分隔每个id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": null, "status": { "code": "001", "message": "指令发送成功", "success": true } } }
	*  )
	* )
	* 增加消息
	* @author 	xww
	* @return 	json
	*/
	public function create()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 对象
			$model = new \VirgoModel\InstructionsMessageModel;

			// 用户对象
			$userModel = new \VirgoModel\UserModel;

			// 站内消息对象
			$webStationAlreadyPushedResultModel = new \VirgoModel\WebStationAlreadyPushedResultModel;

			//验证
			$this->configValid('required',$this->_configs,['content', 'userIds']);

			DB::beginTransaction();

			$isBlock = true;

			$content = $this->_configs['content'];
			$userIdsArr = explode(",", $this->_configs['userIds']);

			$userIds = [];
			for ($i=0; $i < count($userIdsArr); $i++) { 
				$userId = (int)$userIdsArr[$i];
				if( empty($userId) ) {
					continue;
				}
				$userIds[] = $userId;
			}

			if( empty($userIds) ) {
				throw new \Exception("Wrong Param userIds", '014');
			}

			$data['content'] = $content;
			$data['author_id'] = $uid;
			$data['is_pushed'] = 1;
			$data['create_time'] = time();
			$data['update_time'] = time();
			$recordId = $model->create( $data );
			unset($data);
			if( !$recordId ) {
				throw new \Exception("新建指令失败", '005');
			}		

			$app_key = $GLOBALS['globalConfigs']['jpush']['appkey'];
			$master_secret = $GLOBALS['globalConfigs']['jpush']['masterSecret'];

			/*没有极光配置 暂时不进行推送*/
			if( empty($app_key) || empty($master_secret) ) {
				throw new \Exception("未配置极光推送", '098');
			}

			/*建立三方推送*/
			for ($i=0; $i < count($userIds); $i++) {

				$curUserId =  $userIds[$i];

				$curUser = $userModel->readSingleTon($curUserId);

				if( empty($curUser) ) {
					continue;
				}

				$username = $curUser['user_login'];

				/*先进行推送*/				
				$pushedResult = 0;
				try{
					
					$client = new \JPush\Client($app_key, $master_secret);
					$push_payload = $client->push()
									    ->setPlatform('all')
									    ->addAlias($username)
									    ->message('您有一条新指令', [
										  'title' => '您有一条新指令',
										  'content_type' => 'text'
										])
									    ->send();
					$pushedResult = 1;

				} catch (\JPush\Exceptions\APIConnectionException $e) {
					$message = $e->getMessage();
				} catch (\JPush\Exceptions\APIRequestException $e) {
					$message = $e->getMessage();
				}

				$data['type_id'] = 2;
				$data['msg_id'] = $recordId;
				$data['user_id'] = $curUserId;
				$data['times'] = 1;
				$data['pushed_result'] = $pushedResult;
				$data['create_time'] = time();
				$data['update_time'] = time();

				/*创建推送消息结果*/
				$rs = $webStationAlreadyPushedResultModel->create( $data );

			}	

			DB::commit();
			$return = $this->functionObj->toAppJson(null, '001', '指令发送成功', true);

		} catch(\Exception $e) {

			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/instruction/detail", tags={"Instruction"}, 
	*  summary="我发起的指令详情",
	*  description="用户鉴权后 通过传入的id获取数据对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="记录id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "CreatorInstructionDetail", "status": { "code": "001", "message": "获取指令详情成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/CreatorInstructionDetail"
	*   )
	*  )
	* )
	* 指令详情
	* @author 	xww
	* @return 	json
	*/
	public function detail()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 指令对象
			$model = new \VirgoModel\InstructionsMessageModel;

			// 对象
			$webStationAlreadyPushedResultModel = new \VirgoModel\WebStationAlreadyPushedResultModel;

			//验证
			$this->configValid('required',$this->_configs,['id']);

			$id = $this->_configs['id'];

			$data = $model->read( $id );

			if( empty($data) ) {
				throw new \Exception("无法查询到指令", '006');
			}

			// 分页
			// $page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			// $size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			// $page -= 1;
			// $skip = $page*$size;

			if( empty($data) ) {
				$data = null;
			} else {
				$users = $webStationAlreadyPushedResultModel->getInstructionUserLists($id);

				$data['name'] = $user[0]['name'];
				$data['users'] = empty($users)? null:$users;
				
				unset($data['id']);
				unset($data['author_id']);
				unset($data['is_pushed']);
				unset($data['is_deleted']);
				unset($data['update_time']);
			}

			$return = $this->functionObj->toAppJson($data, '001', '获取指令详情成功', true);

		} catch(\Exception $e) {

			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}	

}