<?php
namespace VirgoApi\Diary\Monthly;
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
	* @SWG\Post(path="/api/v1/diary/monthly/create", tags={"MonthlyDiary"}, 
	*  summary="创建月报",
	*  description="用户鉴定后 通过传入规定参数创建新月报 如果不存在场长、公司高管角色则会失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="year", type="integer", required=true, in="formData", description="填写年份"),
	*  @SWG\Parameter(name="month", type="integer", required=true, in="formData", description="填写月份 1-12"),
	*  @SWG\Parameter(name="content", type="string", required=true, in="formData", description="工作内容/姓名"),
	*  @SWG\Parameter(name="transliteration", type="string", required=true, in="formData", description="姓名译音"),
	*  @SWG\Parameter(name="maintenance", type="string", required=true, in="formData", description="养护情况"),
	*  @SWG\Parameter(name="weed", type="string", required=true, in="formData", description="除草情况"),
	*  @SWG\Parameter(name="mechanical_usage", type="string", required=true, in="formData", description="机械使用情况"),
	*  @SWG\Parameter(name="fertilization", type="string", required=true, in="formData", description="施肥情况"),
	*  @SWG\Parameter(name="other_work", type="string", required=false, in="formData", description="其他情况"),
	*  @SWG\Parameter(name="remarks", type="string", required=false, in="formData", description="备注"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "添加月报成功", "success": true } } }
	*  )
	* )
	* 创建日志
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
			$model = new \VirgoModel\MonthlyDiaryModel;

			// 用户角色对象
			$roleToUserModel = new \VirgoModel\RoleToUserModel;

			// 日志审阅对象
			$diaryReadModel = new \VirgoModel\DiaryReadModel;

			//验证
			$this->configValid('required',$this->_configs,['year', 'month', 'content', 'transliteration', 'maintenance', 'weed', 'mechanical_usage', 'fertilization']);

			DB::beginTransaction();

			$isBlock = true;

			$year = $this->_configs['year'];
			$month = $this->_configs['month'];
			$content = $this->_configs['content'];
			$transliteration = $this->_configs['transliteration'];
			$maintenance = $this->_configs['maintenance'];
			$weed = $this->_configs['weed'];
			$mechanical_usage = $this->_configs['mechanical_usage'];
			$fertilization = $this->_configs['fertilization'];

			$other_work = empty( $this->_configs['other_work'] )? '':$this->_configs['other_work'];
			$remarks = empty( $this->_configs['remarks'] )? '':$this->_configs['remarks'];

			// 判断该用户是否已有指定年份 ，月份月报
			$record = $model->getUserDiaryWithDate($uid, $year, $month);

			if( !empty($record) ) {
				throw new \Exception("已经有该年份月报", '026');
			}

			$data['year'] = $year;
			$data['month'] = $month;
			$data['user_id'] = $uid;
			$data['content'] = $content;
			$data['transliteration'] = $transliteration;
			$data['maintenance'] = $maintenance;
			$data['weed'] = $weed;
			$data['mechanical_usage'] = $mechanical_usage;
			$data['fertilization'] = $fertilization;
			$data['other_work'] = $other_work;
			$data['remarks'] = $remarks;
			$data['create_time'] = time();
			$data['update_time'] = time();

			$recordId = $model->create( $data );
			unset($data);

			if( !$recordId ) {
				throw new \Exception("新建月报失败", '005');
			}

			/*获取场长角色用户*/
			$roleTypeId = [1108];
			$farmLeaderIdsArr = $roleToUserModel->getRoleUsers( $roleTypeId );

			if( empty($farmLeaderIdsArr) ) {
				throw new \Exception("不存在场长角色用户，回滚", '006');
			}

			/*获取公司高管角色用户*/
			$roleTypeId = [1109];
			$companyExecutivesLeaderIdsArr = $roleToUserModel->getRoleUsers( $roleTypeId );

			if( empty($companyExecutivesLeaderIdsArr) ) {
				throw new \Exception("不存在公司高管角色用户，回滚", '006');
			}

			/*创建场长审阅*/
			$relData = [];
			for ($i=0; $i < count($farmLeaderIdsArr); $i++) { 

				$temp['user_id'] = $uid;
				$temp['type_id'] = 1;
				$temp['item_id'] = $recordId;
				$temp['to_user_id'] = $farmLeaderIdsArr[$i]['user_id'];
				$temp['to_type_id'] = 1108;
				$temp['status_id'] = 0;
				$temp['create_time'] = time();
				$temp['update_time'] = time();

				$relData[] = $temp;
				unset($temp);
			}
			$rs = $diaryReadModel->multipleCreate( $relData );
			unset( $relData );
			if( !$rs ) {
				throw new \Exception("创建场长待审阅失败", '005');
			}

			/*创建公司高管审阅*/
			$relData = [];
			for ($i=0; $i < count($companyExecutivesLeaderIdsArr); $i++) { 

				$temp['user_id'] = $uid;
				$temp['type_id'] = 1;
				$temp['item_id'] = $recordId;
				$temp['to_user_id'] = $companyExecutivesLeaderIdsArr[$i]['user_id'];
				$temp['to_type_id'] = 1109;
				$temp['status_id'] = 0;
				$temp['create_time'] = time();
				$temp['update_time'] = time();

				$relData[] = $temp;
				unset($temp);

			}
			$rs = $diaryReadModel->multipleCreate( $relData );
			unset( $relData );
			if( !$rs ) {
				throw new \Exception("创建公司高管待审阅失败", '005');
			}

			DB::commit();

			$return = $this->functionObj->toAppJson(null, '001', '添加月报成功', true);

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
	* @SWG\Post(path="/api/v1/diary/monthly/update", tags={"MonthlyDiary"}, 
	*  summary="更新月报",
	*  description="用户鉴定后 通过传入规定参数更新月报 如果已被阅读则会修改失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="记录id"),
	*  @SWG\Parameter(name="year", type="integer", required=true, in="formData", description="填写年份"),
	*  @SWG\Parameter(name="month", type="integer", required=true, in="formData", description="填写月份 1-12"),
	*  @SWG\Parameter(name="content", type="string", required=true, in="formData", description="工作内容/姓名"),
	*  @SWG\Parameter(name="transliteration", type="string", required=true, in="formData", description="姓名译音"),
	*  @SWG\Parameter(name="maintenance", type="string", required=true, in="formData", description="养护情况"),
	*  @SWG\Parameter(name="weed", type="string", required=true, in="formData", description="除草情况"),
	*  @SWG\Parameter(name="mechanical_usage", type="string", required=true, in="formData", description="机械使用情况"),
	*  @SWG\Parameter(name="fertilization", type="string", required=true, in="formData", description="施肥情况"),
	*  @SWG\Parameter(name="other_work", type="string", required=false, in="formData", description="其他情况"),
	*  @SWG\Parameter(name="remarks", type="string", required=false, in="formData", description="备注"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "修改月报成功", "success": true } } }
	*  )
	* )
	* 更新月报
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
			$model = new \VirgoModel\MonthlyDiaryModel;

			// 日志审阅对象
			$diaryReadModel = new \VirgoModel\DiaryReadModel;

			//验证
			$this->configValid('required',$this->_configs,['id', 'year', 'month', 'content', 'transliteration', 'maintenance', 'weed', 'mechanical_usage', 'fertilization']);

			$id = $this->_configs['id'];
			$year = $this->_configs['year'];
			$month = $this->_configs['month'];
			$content = $this->_configs['content'];
			$transliteration = $this->_configs['transliteration'];
			$maintenance = $this->_configs['maintenance'];
			$weed = $this->_configs['weed'];
			$mechanical_usage = $this->_configs['mechanical_usage'];
			$fertilization = $this->_configs['fertilization'];
			$other_work = empty( $this->_configs['other_work'] )? '':$this->_configs['other_work'];
			$remarks = empty( $this->_configs['remarks'] )? '':$this->_configs['remarks'];

			/*获取该用户指定id月报*/
			$record = $model->getUserDiaryWithId($uid, $id);

			if( !$record ) {
				throw new \Exception("无法查询到月报", '006');
			}

			/*查询是否已经被审阅过*/
			$read = $diaryReadModel->readTheDiary($id);

			if( $read ) {
				throw new \Exception("月报已经被阅读无法修改", '097');
			}

			$data['year'] = $year;
			$data['month'] = $month;
			$data['content'] = $content;
			$data['transliteration'] = $transliteration;
			$data['maintenance'] = $maintenance;
			$data['weed'] = $weed;
			$data['mechanical_usage'] = $mechanical_usage;
			$data['fertilization'] = $fertilization;
			$data['other_work'] = $other_work;
			$data['remarks'] = $remarks;
			$data['update_time'] = time();

			$rs = $model->partUpdate($id, $data);

			if( !$rs ) {
				throw new \Exception("修改月报失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '修改月报成功', true);

		} catch(\Exception $e) {

			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/diary/monthly/detail", tags={"MonthlyDiary"}, 
	*  summary="月报详情",
	*  description="用户鉴定后 通过传入的id获取数据",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="记录id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "MonthlyDiaryDetail", "status": { "code": "001", "message": "获取我的阅读月报列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/MonthlyDiaryDetail")
	*   )
	*  )
	* )
	* 月报详情
	* @author 		xww
	* @return 		json
	*/
	public function detail()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 对象
			$model = new \VirgoModel\MonthlyDiaryModel;

			// 日志审阅对象
			$diaryReadModel = new \VirgoModel\DiaryReadModel;

			//验证
			$this->configValid('required',$this->_configs,['id']);

			$id = $this->_configs['id'];

			$data = $model->read( $id );
			$data = empty($data)? null:$data;

			if( !empty($data) ) {
				/*判断是否有该用户该日志的未查看情况  如果存在则进行更新*/
				$hasRecord = $diaryReadModel->getUserMonthlyDiaryWaitReadWithItemId(1, $id, $uid);

				if( !empty($hasRecord) ) {
					/*更新已读*/
					$rs = $diaryReadModel->setReadForUserRead(1, $id, $uid);
					if( !$rs ) {
						throw new \Exception("更新月报已读状态失败", '003');
					}
				}

				unset($data['user_id']);
				unset($data['is_deleted']);
				unset($data['update_time']);
			}

			$return = $this->functionObj->toAppJson($data, '001', '获取月报详情成功', true);

		} catch(\Exception $e) {

			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}