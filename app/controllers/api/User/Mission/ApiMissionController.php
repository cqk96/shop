<?php
namespace VirgoApi\User\Mission;
use Illuminate\Database\Capsule\Manager as DB;
class ApiMissionController extends \VirgoApi\ApiBaseController{

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
	* 根据预计完成时间格式2018-01-02,用户id，标题，内容新建徒弟任务
	* @author 	xww
	* @return 	json
	*/
	public function create()
	{
		
		try {

			$userObj = new \VirgoModel\UserModel;

			if(empty($_COOKIE['user_id'])) {
			 	$user = $this->getUserApi($this->_configs);	
			 	$creatorId = $user[0]['id'];
			} else {
				//获取用户
				$id = $_COOKIE['user_id'];
				$record = $userObj->readSingleTon($id);
				if(empty($record)) {
					throw new \Exception("用户不存在", '006');
				}

				// pc端要求徒弟id
				$this->configValid('required',$this->_configs,['pupilId']);

				$pupilUser = $userObj->readSingleTon($this->_configs['pupilId']);

				if(empty($pupilUser)) {
					throw new \Exception("徒弟用户不存在", '006');
				}

				$user[] = $pupilUser->toArray();
				$creatorId = $id;
			}

			// 需要
			$this->configValid('required',$this->_configs,['title', 'estimatedTime', 'content']);

			// 查找师傅
			$masterPupilModelObj = new \VirgoModel\MasterPupilModel;
			$masterId = $masterPupilModelObj->getUserMaster($user[0]['id']);
			$masterId = empty($masterId)? 0:$masterId;

			// 创建新增数组
			$insertData['user_id'] = $user[0]['id'];
			$insertData['master_id'] = $masterId;
			$insertData['title'] = $this->_configs['title'];
			$insertData['content'] = $this->_configs['content'];
			$insertData['estimated_time'] = strtotime($this->_configs['estimatedTime'])? strtotime($this->_configs['estimatedTime']." 23:59:59"):0;
			$insertData['status_id'] = 0;
			$insertData['creator_id'] = $creatorId;
			$insertData['is_deleted'] = 0;
			$insertData['create_time'] = time();
			$insertData['update_time'] = time();

			// 新建目标
			$pupilGoalModelObj = new \VirgoModel\MissionModel;
			$rs = $pupilGoalModelObj->create($insertData);
			if(!$rs) {
				throw new \Exception("添加任务失败", "005");
			}

			$return = $this->functionObj->toAppJson(null, '001', "添加任务成功", true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 更新徒弟任务
	* @author 	xww
	* @return 	json
	*/
	public function update()
	{
		
		try {

			$userObj = new \VirgoModel\UserModel;


			if(empty($_COOKIE['user_id'])) {
			 	throw new \Exception("请重新登录", '007');
			}

			//获取用户
			$id = $_COOKIE['user_id'];
			$record = $userObj->readSingleTon($id);
			if(empty($record)) {
				throw new \Exception("用户不存在", '006');
			}

			unset($this->_configs['/api/v1/user/mission/update']);

			$user[] = $record->toArray();

			$this->configValid('required',$this->_configs,['id']);

			$pupilGoalModelObj = new \VirgoModel\MissionModel;
			$data = $pupilGoalModelObj->read($this->_configs['id']);

			if(empty($data)) {
				throw new \Exception("数据不存在", '006');
			}

			if( ($data['user_id'] != $user[0]['id']) && ($data['creator_id'] != $user[0]['id']) ) {
				throw new \Exception("无法操作其他人数据", '029');
			}

			$judgeColumns = ['id', 'title', 'content', 'estimated_time', 'process_id', 'master_message'];

			foreach ($this->_configs as $key => $value) {
				if(! in_array($key, $judgeColumns) ) {
					throw new \Exception("含有不允许修改字段".$key, '61');
				}
			}

			$recordId = $this->_configs['id'];

			// 更新时间
			$this->_configs['update_time'] = time();
			unset($this->_configs['id']);

			if(!empty($this->_configs['estimated_time'])) {
				$this->_configs['estimated_time'] = strtotime($this->_configs['estimated_time'])? strtotime($this->_configs['estimated_time']." 23:59:59"):0;
			}

			$rs = $pupilGoalModelObj->updateParts($recordId, $this->_configs);
			if(!$rs) {
				throw new \Exception("修改任务失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', "修改任务成功", true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 完成目标
	* @author 	xww
	* @return 	json
	*/
	public function complete()
	{
		
		try {

			$userObj = new \VirgoModel\UserModel;

			if(empty($_COOKIE['user_id'])) {
			 	throw new \Exception("重新登录", '007');
			}

			//获取用户
			$id = $_COOKIE['user_id'];
			$record = $userObj->readSingleTon($id);
			if(empty($record)) {
				throw new \Exception("用户不存在", '006');
			}

			$user[] = $record->toArray();

			$this->configValid('required',$this->_configs,['id']);

			$pupilGoalModelObj = new \VirgoModel\MissionModel;
			$data = $pupilGoalModelObj->read($this->_configs['id']);

			if(empty($data)) {
				throw new \Exception("数据不存在", '006');
			}

			if($data['status_id']==1) {
				throw new \Exception("已完成", '086');	
			}

			if( $data['master_id'] != $user[0]['id'] && $data['creator_id'] != $user[0]['id'] ) {
				throw new \Exception("无法操作其他人数据", '029');
			}

			$updateData['status_id'] = 1;	
			$updateData['update_time'] = time();
			
			$rs = $pupilGoalModelObj->updateParts($this->_configs['id'], $updateData);
			if(!$rs) {
				throw new \Exception("设置完成失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', "设置完成成功", true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 我的任务列表
	* @author 	xww
	* @return 	json
	*/
	public function lists()
	{

		try {

			$user = $this->getUserApi($this->_configs);

			//验证 
			$this->configValid('required',$this->_configs,['page', 'size']);

			// 分页
			$page = empty((int)$this->_configs['page']) || (int)$this->_configs['page']<1 ? 1:(int)$this->_configs['page'];
			$page -= 1;
			$size = empty((int)$this->_configs['size']) || (int)$this->_configs['size']<0 ? 5:(int)$this->_configs['size'];
			$skip = $page*$size;

			$pupilGoalModelObj = new \VirgoModel\MissionModel;

			$data = $pupilGoalModelObj->getPupilMissionsLists($user[0]['id'], $skip, $size);
			$data = empty($data)? null:$data;

			$dataCount = $pupilGoalModelObj->getPupilMissionsListsCount( $user[0]['id'] );
			$totalPage = is_null($size)? 1:ceil( $dataCount / $size );
			$totalPage = is_null($data)? 0:$totalPage;

			$return = $this->functionObj->toAppJson(['data'=>$data, 'totalPage'=>$totalPage], '001', "获取我的任务列表成功", true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 我的任务详情
	* @author 	xww
	* @return 	json
	*/
	public function read()
	{
		
		try{

			$user = $this->getUserApi($this->_configs);

			//验证 
			$this->configValid('required',$this->_configs,['id']);

			$id = $this->_configs['id'];

			$pupilGoalModelObj = new \VirgoModel\MissionModel;

			$data = $pupilGoalModelObj->getPupilUserRecord($id, $user[0]['id']);

			if( is_null($data) ) {
				throw new \Exception("数据不存在", '006');
			}

			$returnData['statusId'] = $data['status_id'];
			$returnData['estimatedTime'] = $data['estimated_time'];
			$returnData['title'] = $data['title'];
			$returnData['content'] = $data['content'];

			$return = $this->functionObj->toAppJson($returnData, '001', "获取我的任务详情成功", true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 徒弟自己完成任务
	* @author 		xww
	* @return 		json
	*/
	public function userComplete()
	{

		try {

			$user = $this->getUserApi($this->_configs);

			//验证 
			$this->configValid('required',$this->_configs,['id']);

			$pupilGoalModelObj = new \VirgoModel\MissionModel;

			$id = $this->_configs['id'];

			$data = $pupilGoalModelObj->getPupilUserRecord($id, $user[0]['id']);

			if( $data['status_id']==1 ) {
				throw new \Exception("已完成任务", '086');
			}

			$updateData['status_id'] = 1;	
			$updateData['update_time'] = time();
			
			$rs = $pupilGoalModelObj->updateParts($id, $updateData);
			if(!$rs) {
				throw new \Exception("设置完成失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', "设置完成成功", true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}