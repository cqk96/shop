<?php
namespace VirgoApi\Diary\TenDayDiary;
use Illuminate\Database\Capsule\Manager as DB;
class ApiDiaryController extends \VirgoApi\ApiBaseController{

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
	* @SWG\Post(path="/api/v1/diary/tenDayDiary/create", tags={"TenDayDiary"}, 
	*  summary="发表十日报日志",
	*  description="用户鉴定后 通过传入的指定参数 创建十日报 如果没有对应审批角色 则十日报生成也会失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="department_id", type="integer", required=true, in="formData", description="部门id"),
	*  @SWG\Parameter(name="acre_id", type="integer", required=true, in="formData", description="地块id"),
	*  @SWG\Parameter(name="issue", type="integer", required=true, in="formData", description="期号"),
	*  @SWG\Parameter(name="year", type="integer", required=true, in="formData", description="年份e.g 2018"),
	*  @SWG\Parameter(name="start_time", type="string", required=true, in="formData", description="时间区间开始 e.g 2018-08-03"),
	*  @SWG\Parameter(name="end_time", type="string", required=true, in="formData", description="时间区间结束 e.g 2018-08-13"),
	*  @SWG\Parameter(name="current_work_content", type="string", required=true, in="formData", description="当前工作内容"),
	*  @SWG\Parameter(name="number_of_group_members", type="integer", required=true, in="formData", description="班组成员数"),
	*  @SWG\Parameter(name="working_members_count", type="integer", required=true, in="formData", description="机动人员数"),
	*  @SWG\Parameter(name="completion_of_current_term", type="string", required=true, in="formData", description="本期完成情况"),
	*  @SWG\Parameter(name="next_working_plan", type="string", required=false, in="formData", description="下期工作计划"),
	*  @SWG\Parameter(name="existing_problems", type="string", required=false, in="formData", description="存在的问题"),
	*  @SWG\Parameter(name="prior_period_existing_problems", type="string", required=false, in="formData", description="上期存在的问题"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": null, "status": { "code": "001", "message": "发表十日报成功", "success": true } } }
	*  )
	* )
	* 发表十日报日志
	* @author 	xww
	* @return 	json
	*/
	public function create()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\TenDayDiaryModel;

			// 用户角色对象
			$roleToUserModel = new \VirgoModel\RoleToUserModel;

			// 待审批日志对象
			$diaryExaminationModel = new \VirgoModel\DiaryExaminationModel;

			//验证
			$this->configValid('required',$this->_configs,['department_id', 'acre_id', 'issue', 'year', 'start_time', 'end_time', 'current_work_content', 'number_of_group_members', 'working_members_count', 'completion_of_current_term']);

			DB::beginTransaction();

			$isBlock = true;

			$configs = $this->_configs;
			unset($configs['/api/v1/diary/tenDayDiary/create']);
			unset($configs['user_login']);
			unset($configs['access_token']);

			$columns = [
				'department_id', 'acre_id', 'issue', 'start_time', 'end_time', 'current_work_content', 
				'next_working_plan','number_of_group_members', 'working_members_count', 'completion_of_current_term',
				'existing_problems', 'prior_period_existing_problems', 'year'
			];

			foreach ($configs as $key => $value) {
				
				if( !in_array($key, $columns) ) {
					throw new \Exception("含有不接收字段: " . $key, '014');
				}

			}

			if( !empty($configs['start_time']) ) {
				$configs['start_time'] = strtotime($configs['start_time'] . " 00:00:00");
				if( !$configs['start_time'] ) {
					throw new \Exception("错误的时间格式: start_time", '014');
				}
			}

			if( !empty($configs['end_time']) ) {
				$configs['end_time'] = strtotime($configs['end_time'] . " 23:59:59");
				if( !$configs['end_time'] ) {
					throw new \Exception("错误的时间格式: end_time", '014');
				}
			}

			// 获取指定用户的指定年份指定期数的日志
			$record = $model->getUserDiaryWithYearAndIssue($uid, $configs['year'], $configs['issue']);

			if( !empty($record) ) {
				throw new \Exception("已经存在该用户该年份该期数日报", '026');
			}

			$configs['user_id'] = $uid;
			$configs['create_time'] = time();
			$configs['update_time'] = time();
			$recordId = $model->create($configs);

			if( !$recordId ) {
				throw new \Exception("发表十日报失败", '005');
			}

			$roleIds = [1108];
			$approvers = $roleToUserModel->getRoleUsers( $roleIds );

			if( empty($approvers) ) {
				throw new \Exception("不存在具有场长角色的人员，日志回退", '006');
			}

			$userIds = [];
			for ($i=0; $i < count($approvers); $i++) { 
				$userIds[] = $approvers[$i]['user_id'];
			}

			// 进行日志审批
			$rs = $diaryExaminationModel->createWorks($uid, 1, $recordId, $userIds, 1108);

			if( !$rs ) {
				throw new \Exception("十日报提交审批失败", '005');
			}

			DB::commit();
			$return = $this->functionObj->toAppJson(null, '001', '发表十日报成功', true);

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
	* @SWG\Get(path="/api/v1/diary/tenDayDiary/user/term", tags={"TenDayDiary"}, 
	*  summary="获取用户 指定年份十日报的最大期号",
	*  description="用户验证后 通过传入年份获取期号",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="year", type="integer", required=true, in="query", description="年份"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "issue", "status": { "code": "001", "message": "获取农场列表成功", "success": true } } }
	*  )
	* )
	* 获取用户 指定年份的最大期号
	* @author 	xww
	* @return  	json
	*/
	public function userTerm()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 对象
			$model = new \VirgoModel\TenDayDiaryModel;

			//验证
			$this->configValid('required',$this->_configs,['year']);

			$year = $this->_configs['year'];

			$data = $model->getUserMaxIssueWithYear($uid, $year);
			$data = empty($data)? 0:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取用户填写期号成功', true);

		} catch(\Exception $e) {

			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/diary/tenDayDiary/detail", tags={"TenDayDiary"}, 
	*  summary="获取十日报日志详情--包括对应评价",
	*  description="用户验证后 通过传入年份获取期号",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="日志id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "TenDayDiary", "status": { "code": "001", "message": "获取十日报详情成功", "success": true } } },
	*   @SWG\Schema(
	*     type="object",
	*     ref="#/definitions/TenDayDiary",
	*    )
	*  )
	* )
	* 获取十日报日志详情--包括对应评价
	* @author 	xww
	* @return 	json
	*/
	public function detail()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 对象
			$model = new \VirgoModel\TenDayDiaryModel;

			// 评论对象
			$commentModel = new \VirgoModel\TenDayDiaryCommentModel;

			//验证
			$this->configValid('required',$this->_configs,['id']);

			$id = $this->_configs['id'];

			$data = $model->readDetail( $id );
			if( empty($data) ) {
				throw new \Exception("日志不存在", '006');
			}

			// 获取对应的评价--场长
			$data['farmLeaderEvaluation'] = $commentModel->getDiaryCommentContentWithTypeId($id, 1);

			// 获取对应的评价--高管
			$data['companyExecutivesEvaluation'] = $commentModel->getDiaryCommentContentWithTypeId($id, 2);

			$data['start_time'] = empty($data['start_time']) || !date("Y-m-d", $data['start_time'])? '':date("Y-m-d", $data['start_time']);
			$data['end_time'] = empty($data['end_time']) || !date("Y-m-d", $data['end_time'])? '':date("Y-m-d", $data['end_time']);
			$data['next_working_plan'] = empty($data['next_working_plan'])? '':$data['next_working_plan'];
			$data['existing_problems'] = empty($data['existing_problems'])? '':$data['existing_problems'];
			$data['prior_period_existing_problems'] = empty($data['prior_period_existing_problems'])? '':$data['prior_period_existing_problems'];

			unset( $data['department_id'] );
			unset( $data['acre_id'] );
			unset( $data['user_id'] );
			unset( $data['is_deleted'] );
			unset( $data['update_time'] );

			$return = $this->functionObj->toAppJson($data, '001', '获取十日报详情成功', true);

		} catch(\Exception $e) {

			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/diary/tenDayDiary/update", tags={"TenDayDiary"}, 
	*  summary="更新十日报日志",
	*  description="用户鉴定后 通过传入的日志id 和其他参数 更新十日报",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="记录id"),
	*  @SWG\Parameter(name="department_id", type="integer", required=true, in="formData", description="部门id"),
	*  @SWG\Parameter(name="acre_id", type="integer", required=true, in="formData", description="地块id"),
	*  @SWG\Parameter(name="issue", type="integer", required=true, in="formData", description="期号"),
	*  @SWG\Parameter(name="year", type="integer", required=true, in="formData", description="年份e.g 2018"),
	*  @SWG\Parameter(name="start_time", type="string", required=true, in="formData", description="时间区间开始 e.g 2018-08-03"),
	*  @SWG\Parameter(name="end_time", type="string", required=true, in="formData", description="时间区间结束 e.g 2018-08-13"),
	*  @SWG\Parameter(name="current_work_content", type="string", required=true, in="formData", description="当前工作内容"),
	*  @SWG\Parameter(name="number_of_group_members", type="integer", required=true, in="formData", description="班组成员数"),
	*  @SWG\Parameter(name="working_members_count", type="integer", required=true, in="formData", description="机动人员数"),
	*  @SWG\Parameter(name="completion_of_current_term", type="string", required=true, in="formData", description="本期完成情况"),
	*  @SWG\Parameter(name="next_working_plan", type="string", required=false, in="formData", description="下期工作计划"),
	*  @SWG\Parameter(name="existing_problems", type="string", required=false, in="formData", description="存在的问题"),
	*  @SWG\Parameter(name="prior_period_existing_problems", type="string", required=false, in="formData", description="上期存在的问题"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": null, "status": { "code": "001", "message": "更新十日报成功", "success": true } } }
	*  )
	* )
	* 修改日志
	* @author 	xww
	* @return 	json
	*/
	public function update()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\TenDayDiaryModel;

			// 评论对象
			$commentModel = new \VirgoModel\TenDayDiaryCommentModel;

			//验证
			$this->configValid('required',$this->_configs,['id', 'department_id', 'acre_id', 'issue', 'year', 'start_time', 'end_time', 'current_work_content', 'number_of_group_members', 'working_members_count', 'completion_of_current_term']);

			$id = $this->_configs['id'];

			$configs = $this->_configs;
			unset($configs['/api/v1/diary/tenDayDiary/update']);
			unset($configs['user_login']);
			unset($configs['access_token']);
			unset($configs['id']);

			$columns = [
				'department_id', 'acre_id', 'issue', 'start_time', 'end_time', 'current_work_content', 
				'next_working_plan','number_of_group_members', 'working_members_count', 'completion_of_current_term',
				'existing_problems', 'prior_period_existing_problems', 'year'
			];

			foreach ($configs as $key => $value) {
				
				if( !in_array($key, $columns) ) {
					throw new \Exception("含有不接收字段: " . $key, '014');
				}

			}

			if( !empty($configs['start_time']) ) {
				$configs['start_time'] = strtotime($configs['start_time'] . " 00:00:00");
				if( !$configs['start_time'] ) {
					throw new \Exception("错误的时间格式: start_time", '014');
				}
			}

			if( !empty($configs['end_time']) ) {
				$configs['end_time'] = strtotime($configs['end_time'] . " 23:59:59");
				if( !$configs['end_time'] ) {
					throw new \Exception("错误的时间格式: end_time", '014');
				}
			}

			// 获取指定用户的指定年份指定期数的日志
			$record = $model->read($id);

			if( empty($record) ) {
				throw new \Exception("无法查询到数据", '006');
			}

			if( $record['user_id'] != $uid ) {
				throw new \Exception("非日志所有者", '093');	
			}

			$hasReviewed = $commentModel->hasReviewed($id);
			if( $hasReviewed ) {
				throw new \Exception("已经在审核不允许修改", '096');
			}

			$configs['update_time'] = time();
			$rs = $model->partUpdate($id, $configs);

			if( !$rs ) {
				throw new \Exception("更新十日报失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '更新十日报成功', true);

		} catch(\Exception $e) {

			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/diary/tenDayDiary/missions", tags={"Diary"}, 
	*  summary="获取日志审批任务列表--包括完成与未完成",
	*  description="用户验证后 通过传入page,size 获取数据列表 可选statusId 获取完成列表（默认未完成）",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="statusId", type="integer", required=false, in="query", description="完成状态 0表示未完成，2表示已完成", default=0),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "UserMission", "status": { "code": "001", "message": "获取日志审批情况成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/UserMission")
	*   )
	*  )
	* )
	* 获取日志审批任务列表--包括完成与未完成
	* @author 	xww
	* @return 	json
	*/
	public function missions()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			//验证
			$this->configValid('required',$this->_configs,['page', 'size']);

			$model = new \VirgoModel\DiaryExaminationModel;

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			/*0代表 未完成 1代表已完成*/
			$status = is_null($this->_configs['statusId'])? null:$this->_configs['statusId'];

			$data = $model->getUserMissions($uid, $status, $skip, $size);
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取日志审批情况成功', true);

		} catch(\Exception $e) {

			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/diary/tenDayDiary/backMissions", tags={"Diary"}, 
	*  summary="获取后台日志审批任务列表--包括完成与未完成",
	*  description="用户验证后 通过传入page,size 获取数据列表 可选statusId 获取完成列表（默认未完成）",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="statusId", type="integer", required=false, in="query", description="完成状态 0表示未完成，2表示已完成", default=0),
	*  @SWG\Parameter(name="userName", type="string", required=false, in="query", description="用户名"),
	*  @SWG\Parameter(name="departmentName", type="string", required=false, in="query", description="部门名"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "UserMission", "code": "001", "message": "获取日志审批情况成功", "totalCount": 3 } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/UserMission")
	*   )
	*  )
	* )
	* 获取日志审批任务列表--包括完成与未完成
	* @author 	xww
	* @return 	json
	*/
	public function backMissions()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs, 1);

			$uid = $user[0]['id'];

			//验证
			$this->configValid('required',$this->_configs,['page', 'size']);

			$model = new \VirgoModel\DiaryExaminationModel;

			$userName = empty( $this->_configs['userName'] )? null:$this->_configs['userName'];
			$departmentName = empty( $this->_configs['departmentName'] )? null:$this->_configs['departmentName'];

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			/*0代表 未完成 1代表已完成*/
			$status = empty($this->_configs['statusId'])? 0:2;

			$pageObj = $model->getBackUserMissionsObj($uid, $status, $skip, $size, $userName, $departmentName);

			$data = $pageObj->data;
			$data = empty($data)? null:$data;
			$totalCount = $pageObj->totalCount;

			$return = $this->functionObj->toLayuiJson($data, '001', '获取日志审批情况成功', $totalCount);

		} catch(\Exception $e) {

			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}

}