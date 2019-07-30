<?php
namespace VirgoApi\Area;
use Illuminate\Database\Capsule\Manager as DB;
use VirgoApi;
class ApiAreaController extends VirgoApi\ApiBaseController{

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
	* @SWG\Get(path="/api/v1/area/lists", tags={"Area"}, 
	*  summary="获取片区管理 片区列表",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="nameSearch", type="string", required=false, in="query", description="片区名"),
	*  @SWG\Parameter(name="cropTypeNameSearch", type="string", required=false, in="query", description="作物种类名"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "AreaListsObj", "code": "001", "message": "获取片区列表成功", "totalCount": 3 } },
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

			// 片区对象
			$model = new \VirgoModel\AreaModel;

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

			$nameSearch = empty($this->_configs['nameSearch'])? null:$this->_configs['nameSearch'];
			$cropTypeNameSearch = empty($this->_configs['cropTypeNameSearch'])? null:$this->_configs['cropTypeNameSearch'];

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$pageObj = $model->getListsObject($skip, $size, $nameSearch, $cropTypeNameSearch);

			$data = [];

			$data = empty($pageObj->data)? null:$pageObj->data;
			// $data['totalPage'] = intval( ceil($pageObj->totalCount/$pageObj->size) );
			// $data['currentPage'] = intval( $pageObj->current_page );
			$totalCount = $pageObj->totalCount;

			$return = $this->functionObj->toLayuiJson($data, '001', '获取片区列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/area/create", tags={"Area"}, 
	*  summary="创建片区",
	*  description="用户鉴权后 通过传入的片区名、片区面积（平方米）、负责人、作物数、片区类型、作物种类和地块id创建新片区 如果存在同名同属地块的片区新建会失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="片区名称"),
	*  @SWG\Parameter(name="acreId", type="integer", required=true, in="formData", description="地块id"),
	*  @SWG\Parameter(name="acreage", type="string", required=true, in="formData", description="片区面积/㎡，可支持两位小数"),
	*  @SWG\Parameter(name="typeId", type="integer", required=true, in="formData", description="片区类型 1水果2蔬菜"),
	*  @SWG\Parameter(name="cropAmount", type="integer", required=true, in="formData", description="作物数量"),
	*  @SWG\Parameter(name="cropTypeId", type="integer", required=true, in="formData", description="作物类别id"),
	*  @SWG\Parameter(name="managerIds", type="string", required=true, in="formData", description="管理人员ids 用户id以,分隔组成的字符串"),
	*  @SWG\Parameter(name="statusId", type="integer", required=false, in="formData", description="片区状态 默认0正常1虫害", default="0"),
	*  @SWG\Parameter(name="expectedMaturity", type="string", required=false, in="formData", description="预计成熟时间 e.g2018-08-06"),
	*  @SWG\Parameter(name="remarks", type="string", required=false, in="formData", description="备注"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "创建片区成功", "success": true } } }
	*  )
	* )
	* 创建片区
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

			// 地块对象
			$acreModel = new \VirgoModel\AcreModel;

			// 作物种类对象
			$cropTypeModel = new \VirgoModel\CropTypeModel;

			// 片区对象
			$model = new \VirgoModel\AreaModel;

			// 片区管理员关联对象
			$relModel = new \VirgoModel\AreaRelManagerModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 2]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和增加数据权限", '070');
			}

			//验证
			$this->configValid('required',$this->_configs,['name', "acreId", 'acreage', "typeId", "cropAmount", "cropTypeId", "managerIds"]);

			/*事务*/
			DB::beginTransaction();

			$isBlock = true;

			$name = $this->_configs['name'];
			$acre_id = (int)$this->_configs['acreId'];
			$acreage = (float)$this->_configs['acreage'];
			// $manager_id = (int)$this->_configs['managerId'];
			$type_id = (int)$this->_configs['typeId'];
			$crop_amount = (int)$this->_configs['cropAmount'];
			$crop_type_id = (int)$this->_configs['cropTypeId'];
			$status_id = empty($this->_configs['statusId']) || (int)$this->_configs['statusId']<=0? 0:(int)$this->_configs['statusId'];
			$remarks = empty($this->_configs['remarks'])? '':$this->_configs['remarks'];

			// 预计成熟时间 格式e.g 2018-08-06
			$expected_maturity = empty($this->_configs['expectedMaturity'])? 0:$this->_configs['expectedMaturity'];

			// 管理人员
			$managerIdsArr = explode(",", $this->_configs['managerIds'] );

			// 面积,作物数 0报错
			if( empty($acreage) || empty($crop_amount) ) {
				throw new \Exception("acreage or cropAmount can not be null", '014');
			}

			$managerIds = [];
			for ($i=0; $i < count($managerIdsArr); $i++) { 
				
				$temp = (int)$managerIdsArr[$i];
				if( !$temp ) {
					continue;
				}

				$managerIds[] = $temp;
				unset($temp);
			}

			if( empty($managerIds) ) {
				throw new Exception("Wrong Param managerIds ", "014");
			}

			// 查询用户
			// $record = $userModel->readSingleTon( $manager_id );

			// if( empty($record) ) {
			// 	throw new \Exception("无法查询到负责人 可能不存在或已删除", '006');	
			// }

			// 查询地块
			$record = $acreModel->readSingleTon($acre_id);

			if( empty($record) ) {
				throw new \Exception("无法查询到地块 可能不存在或已删除", '006');	
			}

			// 查询作物种类
			$record = $cropTypeModel->readSingleTon($crop_type_id);

			if( empty($record) ) {
				throw new \Exception("无法查询到作物种类 可能不存在或已删除", '006');	
			}

			// 是否有同名 同属地块的片区创建过
			$record = $model->getAcreAreaWithName($acre_id, $name);
			if( !empty($record) ) {
				throw new \Exception("已存在同名 所属该地块片区", '026');
			}

			// 成熟时间
			if( !empty($expected_maturity) && strtotime($expected_maturity) ) {
				$expected_maturity = strtotime($expected_maturity);
			}

			$insertData['name'] = $name;
			$insertData['acre_id'] = $acre_id;
			$insertData['type_id'] = $type_id;
			$insertData['crop_type_id'] = $crop_type_id;
			// $insertData['manager_id'] = $manager_id;
			$insertData['status_id'] = $status_id;
			$insertData['expected_maturity'] = $expected_maturity;
			$insertData['acreage'] = $acreage;
			$insertData['crop_amount'] = $crop_amount;
			$insertData['remarks'] = $remarks;
			$insertData['is_deleted'] = 0;
			$insertData['create_time'] = time();
			$insertData['update_time'] = time();

			$recordId = $model->create( $insertData );

			if( !$recordId ) {
				throw new \Exception("添加片区失败", '005');
			}


			DB::commit();
			$return = $this->functionObj->toAppJson(null, '001', '创建片区成功', true);

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
	* @SWG\Post(path="/api/v1/area/delete", tags={"Area"}, 
	*  summary="删除片区",
	*  description="用户鉴权后 通过传入的片区ids 进行单个或多个地块删除 如果存在下属作物则会失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="ids", type="string", required=true, in="formData", description="片区id以,分割的字符串"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "删除片区成功", "success": true } } }
	*  )
	* )
	* 删除片区
	* @author 	xww
	* @return 	json
	*/
	public function delete()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 作物对象
			$cropModel = new \VirgoModel\CropModel;

			// 片区对象
			$model = new \VirgoModel\AreaModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 3]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和删除数据权限", '070');
			}

			//验证
			$this->configValid('required',$this->_configs,['ids']);

			$idsArr = explode(",", $this->_configs['ids']);

			$ids = [];

			for ($i=0; $i < count($idsArr); $i++) { 
				$singleIds = (int)$idsArr[$i];

				if( empty($idsArr) ) {
					continue;
				}

				$ids[] = $singleIds;

			}

			if( empty($ids) ) {
				throw new \Exception("Wrong Param Ids", "014");
			}

			// 获取指定片区拥有的作物
			$hasRecord = $cropModel->getMultipleCropsByAreaId( $ids, 0, 1);

			if( !empty($hasRecord) ) {
				throw new \Exception("该片区仍然还有作物 请删除作物后再重试", "094");	
			}

			$data['is_deleted'] = 1;
			$data['update_time'] = time();
			$rs = $model->multiplePartUpdate($ids, $data);
			unset($data);

			if( !$rs ) {
				throw new \Exception("删除片区失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '删除片区成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/area/read", tags={"Area"}, 
	*  summary="片区详情",
	*  description="用户鉴权后 通过传入的片区id获取片区对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="片区记录id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "Area", "status": { "code": "001", "message": "获取片区详情成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/Area"
	*   )
	*  )
	* )
	* 查看片区详情
	* @author 	xww
	* @return 	json
	*/
	public function read()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 片区对象
			$model = new \VirgoModel\AreaModel;

			// 片区管理员关联对象
			$relModel = new \VirgoModel\AreaRelManagerModel;

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
			$this->configValid('required',$this->_configs,['id']);

			$id = $this->_configs['id'];

			// 查询数据
			$data = $model->readSingleTon($id);

			if( empty($data) ) {
				throw new \Exception("片区数据可能不存在或已删除", '006');	
			}

			// 获取片区对应的管理员id
			$managers = $relModel->getAreaManagers( $id );
			$data['managers'] = empty($managers)? nuLL:$managers;

			$return = $this->functionObj->toAppJson($data, '001', '获取片区详情成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/area/update", tags={"Area"}, 
	*  summary="修改片区",
	*  description="用户鉴权后 通过传入的片区id, 片区名、片区面积（平方米）、负责人、作物数、片区类型、作物种类和地块id修改片区 如果存在同名同属地块的片区修改会失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="片区id"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="片区名称"),
	*  @SWG\Parameter(name="acreId", type="integer", required=true, in="formData", description="地块id"),
	*  @SWG\Parameter(name="acreage", type="string", required=true, in="formData", description="片区面积/㎡，可支持两位小数"),
	*  @SWG\Parameter(name="managerId", type="integer", required=true, in="formData", description="负责人用户id"),
	*  @SWG\Parameter(name="typeId", type="integer", required=true, in="formData", description="片区类型 1水果2蔬菜"),
	*  @SWG\Parameter(name="cropAmount", type="integer", required=true, in="formData", description="作物数量"),
	*  @SWG\Parameter(name="cropTypeId", type="integer", required=true, in="formData", description="作物类别id"),
	*  @SWG\Parameter(name="statusId", type="integer", required=false, in="formData", description="片区状态 默认0正常1虫害", default="0"),
	*  @SWG\Parameter(name="managerIds", type="string", required=true, in="formData", description="管理人员ids 用户id以,分隔组成的字符串"),
	*  @SWG\Parameter(name="expectedMaturity", type="string", required=false, in="formData", description="预计成熟时间 e.g2018-08-06"),
	*  @SWG\Parameter(name="remarks", type="string", required=false, in="formData", description="备注"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "修改片区成功", "success": true } } }
	*  )
	* )
	* 修改片区
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

			// 地块对象
			$acreModel = new \VirgoModel\AcreModel;

			// 作物种类对象
			$cropTypeModel = new \VirgoModel\CropTypeModel;

			// 片区对象
			$model = new \VirgoModel\AreaModel;

			// 片区管理员关联对象
			$relModel = new \VirgoModel\AreaRelManagerModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 2]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和增加数据权限", '070');
			}

			//验证
			$this->configValid('required',$this->_configs,['id', 'name', "acreId", 'acreage', "typeId", "cropAmount", "cropTypeId", "managerIds"]);

			/*事务*/
			DB::beginTransaction();

			$isBlock = true;

			$id = (int)$this->_configs['id'];
			$name = $this->_configs['name'];
			$acre_id = (int)$this->_configs['acreId'];
			$acreage = (float)$this->_configs['acreage'];
			// $manager_id = (int)$this->_configs['managerId'];
			$type_id = (int)$this->_configs['typeId'];
			$crop_amount = (int)$this->_configs['cropAmount'];
			$crop_type_id = (int)$this->_configs['cropTypeId'];
			$status_id = empty($this->_configs['statusId']) || (int)$this->_configs['statusId']<=0? 0:(int)$this->_configs['statusId'];
			$remarks = empty($this->_configs['remarks'])? '':$this->_configs['remarks'];

			// 预计成熟时间 格式e.g 2018-08-06
			$expected_maturity = empty($this->_configs['expectedMaturity'])? 0:$this->_configs['expectedMaturity'];

			// 管理人员
			$managerIdsArr = explode(",", $this->_configs['managerIds'] );

			// 查询数据
			$data = $model->readSingleTon($id);
			if( empty($data) ) {
				throw new \Exception("片区数据可能不存在或已删除", '006');	
			}

			// 面积,作物数 0报错
			if( empty($acreage) || empty($crop_amount) ) {
				throw new \Exception("acreage or cropAmount can not be null", '014');
			}

			// 查询用户
			// $record = $userModel->readSingleTon( $manager_id );

			// if( empty($record) ) {
			// 	throw new \Exception("无法查询到负责人 可能不存在或已删除", '006');	
			// }

			// 查询地块
			$record = $acreModel->readSingleTon($acre_id);

			if( empty($record) ) {
				throw new \Exception("无法查询到地块 可能不存在或已删除", '006');	
			}

			// 查询作物种类
			$record = $cropTypeModel->readSingleTon($crop_type_id);

			if( empty($record) ) {
				throw new \Exception("无法查询到作物种类 可能不存在或已删除", '006');	
			}

			// 是否有同名 同属地块的片区创建过
			// $record = $model->getAcreAreaWithName($acre_id, $name);
			// if( !empty($record) ) {
			// 	throw new \Exception("已存在同名 所属该地块片区", '026');
			// }

			// 成熟时间
			if( !empty($expected_maturity) && strtotime($expected_maturity) ) {
				$expected_maturity = strtotime($expected_maturity);
				$updateData['expected_maturity'] = $expected_maturity;
			}

			$managerIds = [];
			for ($i=0; $i < count($managerIdsArr); $i++) { 
				
				$temp = (int)$managerIdsArr[$i];
				if( !$temp ) {
					continue;
				}

				$managerIds[] = $temp;
				unset($temp);
			}

			if( empty($managerIds) ) {
				throw new Exception("Wrong Param managerIds ", "014");
			}

			$updateData['name'] = $name;
			$updateData['acre_id'] = $acre_id;
			$updateData['type_id'] = $type_id;
			$updateData['crop_type_id'] = $crop_type_id;
			// $updateData['manager_id'] = $manager_id;
			$updateData['status_id'] = $status_id;
			$updateData['acreage'] = $acreage;
			$updateData['crop_amount'] = $crop_amount;
			$updateData['remarks'] = $remarks;
			$updateData['update_time'] = time();

			$rs = $model->partUpdate($id, $updateData);

			if( !$rs ) {
				throw new \Exception("修改片区失败", '005');
			}

			$records = $relModel->getAreaManagers($id);

			if( !empty($records) ) {
				$rs = $relModel->softDeleteAreaManagers($id);				

				if( !$rs ) {
					throw new \Exception("删除片区管理员失败", '003');
				}

			}

			$relData = [];
			for ($i=0; $i < count($managerIds); $i++) { 
				
				$temp['user_id'] = (int)$managerIds[$i];
				$temp['area_id'] = $id;
				$temp['create_time'] = time();
				$temp['update_time'] = time();

				$relData[] = $temp;
				unset($temp);
			}

			$rs = $relModel->multipleCreate( $relData );

			if( !$rs ) {
				throw new \Exception("添加片区管理人员失败", '005');
			}

			DB::commit();
			$return = $this->functionObj->toAppJson(null, '001', '修改片区成功', true);

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
	* @SWG\Get(path="/api/v1/area/archive/lists", tags={"Area"}, 
	*  summary="片区档案列表",
	*  description="片区档案列表 用户鉴定后 通过  page,size获取对应列表",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="片区id"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "ArchiveList", "status": { "code": "001", "message": "获取档案列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/ArchiveList")
	*   )  
	*  )
	* )
	* 片区档案列表
	* @author 	xww
	* @return json
	*/
	public function archiveLists()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			//验证
			$this->configValid('required',$this->_configs,['id', "page", "size"]);

			$id = $this->_configs['id'];

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;


			// 片区模板数据对象
			$model = new \VirgoModel\AreaTemplateDataModel;

			$data = $model->getLists($id, $skip, $size);
			$data = empty($data)? null:$data;

			// 构造跳转url
			for ($i=0; $i < count($data); $i++) { 
				$data[$i]['url'] = "http://" . $_SERVER['HTTP_HOST'] . "/front/archive/read?id=" . $data[$i]['id'] . "&dataType=2";
				unset($data[$i]['create_time']);
			}

			$return = $this->functionObj->toAppJson($data, '001', '获取片区档案列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/area/type/lists", tags={"Area"}, 
	*  summary="获取指定分类片区列表",
	*  description="App获取指定分类片区列表 鉴权后通过  page,size获取对应列表",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="acreId", type="integer", required=true, in="query", description="地块id"),
	*  @SWG\Parameter(name="areaType", type="integer", required=false, in="query", description="片区分类 默认1水果2蔬菜", default=1),
	*  @SWG\Parameter(name="search", type="string", required=false, in="query", description="搜索的片区名"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "AreaTypeDataLists", "status": { "code": "001", "message": "获取档案列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/AreaTypeDataLists")
	*   )  
	*  )
	* )
	* 获取指定分类的片区列表
	* @author 	xww
	* @return 	json
	*/
	public function typeLists()
	{

		try {

			// 验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 片区对象
			$model = new \VirgoModel\AreaModel;

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
			$this->configValid('required',$this->_configs,['page', 'size', 'acreId']);

			$acreId = $this->_configs['acreId'];

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$type = empty($this->_configs['areaType']) || $this->_configs['areaType']==1? 1:2;
			$search = empty($this->_configs['search'])? null:$this->_configs['search'];

			$data = $model->getAreaListsWithTypeAndAcreId($acreId, $type, $skip, $size, $uid, $search);
			$data = empty($data)? null:$data;

			for ($i=0; $i < count($data); $i++) { 
				$data[$i]['acreage'] = empty($data[$i]['acreage'])? "0.00":number_format($data[$i]['acreage'], 2, '.', '');
			}
			
			$return = $this->functionObj->toAppJson($data, '001', '获取指定类别片区列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}


	}

	/**
	* 获取作物有档案的操作时间
	* @SWG\Get(path="/api/v1/area/operateTime", tags={"Area"}, 
	*  summary="获取作物 有档案的操作时间",
	*  description="用户鉴权 传入作物id",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="记录id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data":"CropOperateLists", "code": "001", "message": "获取操作时间状态成功", "totalCount": 14 } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/CropOperateLists"
	*   )
	*  )
	* )
	* @author 	xww
	* @return 	json
	*/
	public function operateTime()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 模板对象
			$model = new \VirgoModel\AreaModel;

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
			$this->configValid('required',$this->_configs,['id']);

			$id = $this->_configs['id'];

			$data = $model->getCropOpereateDataTime( $id );
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', "获取操作时间状态成功", true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 获取作物有档案的操作时间
	* @SWG\Get(path="/api/v1/area/operateTime/templates", tags={"Crop"}, 
	*  summary="获取操作时间模板数据",
	*  description="用户鉴权 传入作物id 操作时间",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="记录id"),
	*  @SWG\Parameter(name="dateStr", type="string", required=true, in="query", description="模板操作时间 e.g 2018-08-26"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data":"CropOperateTemplateLists", "code": "001", "message": "获取操作时间状态成功", "totalCount": 14 } },
	*   @SWG\Schema(
	*    type="format",
	*    ref="#/definitions/CropOperateTemplateLists"
	*   )
	*  )
	* )
	* @author 	xww
	* @return 	json
	*/
	public function operateTimeTemplates()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 模板对象
			$model = new \VirgoModel\AreaModel;

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
			$this->configValid('required',$this->_configs,['id', 'dateStr']);

			$id = $this->_configs['id'];
			$dateStr = $this->_configs['dateStr'];

			if( !strtotime($dateStr) ) {
				throw new \Exception("Wrong Param dateStr", '014');
			}

			$data = $model->getCropOperateDateTimeTemplates( $id, $dateStr);
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', "获取操作时间模板数据成功", true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}
	
	/**
	* @SWG\Get(path="/api/v1/area/manager/type/lists", tags={"Area"}, 
	*  summary="获取指定分类片区列表",
	*  description="App获取指定分类片区列表 鉴权后通过  page,size获取对应列表",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="acreId", type="integer", required=true, in="query", description="地块id"),
	*  @SWG\Parameter(name="areaType", type="integer", required=false, in="query", description="片区分类 默认1水果2蔬菜", default=1),
	*  @SWG\Parameter(name="search", type="string", required=false, in="query", description="搜索的片区名"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "AreaTypeDataLists", "status": { "code": "001", "message": "获取档案列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/AreaTypeDataLists")
	*   )  
	*  )
	* )
	* 获取指定分类的片区列表
	* @author 	xww
	* @return 	json
	*/
	public function managerTypeLists()
	{

		try {

			// 验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 片区对象
			$model = new \VirgoModel\AreaModel;

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
			$this->configValid('required',$this->_configs,['page', 'size', 'acreId']);

			$acreId = $this->_configs['acreId'];

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$type = empty($this->_configs['areaType']) || $this->_configs['areaType']==1? 1:2;
			$search = empty($this->_configs['search'])? null:$this->_configs['search'];

			$data = $model->getAreaManagerListsWithTypeAndAcreId($acreId, $type, $skip, $size, $uid, $search);
			$data = empty($data)? null:$data;

			for ($i=0; $i < count($data); $i++) { 
				$data[$i]['acreage'] = empty($data[$i]['acreage'])? "0.00":number_format($data[$i]['acreage'], 2, '.', '');
			}
			
			$return = $this->functionObj->toAppJson($data, '001', '获取指定类别片区列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}


	}

}