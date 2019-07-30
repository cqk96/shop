<?php
namespace VirgoApi\Acre;
class ApiAcreController extends \VirgoApi\ApiBaseController{

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
	* @SWG\Get(path="/api/v1/acre/lists", tags={"Acre"}, 
	*  summary="获取地块管理 地块列表",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "AcreListsObj", "code": "001", "message": "获取地块列表成功", "totalCount": 3 } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/AcreListsObj"
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
			$model = new \VirgoModel\AcreModel;

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

			$pageObj = $model->getListsObject($skip, $size);

			$data = [];

			$data = empty($pageObj->data)? null:$pageObj->data;
			// $data['totalPage'] = intval( ceil($pageObj->totalCount/$pageObj->size) );
			// $data['currentPage'] = intval( $pageObj->current_page );
			$totalCount = $pageObj->totalCount;

			$return = $this->functionObj->toLayuiJson($data, '001', '获取地块列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/acre/create", tags={"Acre"}, 
	*  summary="创建地块",
	*  description="用户鉴权后 通过传入的地块名、地块面积（平方米）、负责人、片区数来和农场id创建新地块",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="地块名称"),
	*  @SWG\Parameter(name="farmId", type="integer", required=true, in="formData", description="农场id"),
	*  @SWG\Parameter(name="acreage", type="string", required=true, in="formData", description="地块面积/㎡，可支持两位小数"),
	*  @SWG\Parameter(name="managerId", type="integer", required=true, in="formData", description="负责人用户id"),
	*  @SWG\Parameter(name="areaAmount", type="integer", required=true, in="formData", description="片区数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "添加农场成功", "success": true } } }
	*  )
	* )
	* 创建地块
	*/
	public function create()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 农场对象
			$farmModel = new \VirgoModel\FarmModel;

			// 地块对象
			$model = new \VirgoModel\AcreModel;

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
			$this->configValid('required',$this->_configs,['name', "farmId", 'acreage', 'managerId', 'areaAmount']);

			$name = $this->_configs['name'];
			$farm_id = (int)$this->_configs['farmId'];
			$acreage = (float)$this->_configs['acreage'];
			$manager_id = (int)$this->_configs['managerId'];
			$area_amount = (int)$this->_configs['areaAmount'];

			// 面积,片区数 0报错
			if( empty($acreage) || empty($area_amount) ) {
				throw new \Exception("acreage or areaAmount can not be null", '014');
			}

			// 查询用户
			$record = $userModel->readSingleTon( $manager_id );

			if( empty($record) ) {
				throw new \Exception("无法查询到负责人 可能不存在或已删除", '006');	
			}

			// 查询农场
			$record = $farmModel->readSingleTon($farm_id);

			if( empty($record) ) {
				throw new \Exception("无法查询到农场 可能不存在或已删除", '006');	
			}

			// 是否有同名 同属农场的地块创建过
			$record = $model->getFarmAcreWithName($farm_id, $name);
			if( !empty($record) ) {
				throw new \Exception("已存在同名 所属该农场地块", '026');
			}

			$insertData['name'] = $name;
			$insertData['farm_id'] = $farm_id;
			$insertData['manager_id'] = $manager_id;
			$insertData['acreage'] = $acreage;
			$insertData['area_amount'] = $area_amount;
			$insertData['is_deleted'] = 0;
			$insertData['create_time'] = time();
			$insertData['update_time'] = time();

			$rs = $model->create( $insertData );

			if( !$rs ) {
				throw new \Exception("添加地块失败", '005');
			}

			$return = $this->functionObj->toAppJson(null, '001', '创建地块成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/acre/delete", tags={"Acre"}, 
	*  summary="删除地块",
	*  description="用户鉴权后 通过传入的地块ids 进行单个或多个地块删除 如果存在下属片区则会失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="ids", type="string", required=true, in="formData", description="地块id 以,分隔的字符串"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "删除地块成功", "success": true } } }
	*  )
	* )
	* 删除地块
	* @author　		xww
	* @return 		json
	*/
	public function delete()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 地块对象
			$model = new \VirgoModel\AcreModel;

			// 片区对象
			$areaModel = new \VirgoModel\AreaModel;

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

			// 获取指定地块拥有的片区
			$hasRecord = $areaModel->getMultipleAreasByAcreId( $ids );

			if( !empty($hasRecord) ) {
				throw new \Exception("该地块仍然还有片区 请删除片区后再重试", "094");	
			}

			$data['is_deleted'] = 1;
			$data['update_time'] = time();
			$rs = $model->multiplePartUpdate($ids, $data);
			unset($data);

			if( !$rs ) {
				throw new \Exception("删除地块失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '删除地块成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/acre/read", tags={"Acre"}, 
	*  summary="地块详情",
	*  description="用户鉴权后 通过传入的id获取地块对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="地块记录id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "Acre", "status": { "code": "001", "message": "获取地块详情成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/Acre"
	*   )
	*  )
	* )
	* 查看详情
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

			// 地块对象
			$model = new \VirgoModel\AcreModel;

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
				throw new \Exception("地块数据可能不存在或已删除", '006');	
			}

			$return = $this->functionObj->toAppJson($data, '001', '获取地块详情成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/acre/update", tags={"Acre"}, 
	*  summary="修改地块",
	*  description="用户鉴权后 通过传入的地块记录id, 地块名、地块面积（亩）、负责人、农场id和片区数来更新地块信息",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="地块id"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="地块名称"),
	*  @SWG\Parameter(name="acreage", type="string", required=true, in="formData", description="地块面积/亩，可支持两位小数"),
	*  @SWG\Parameter(name="managerId", type="integer", required=true, in="formData", description="负责人用户id"),
	*  @SWG\Parameter(name="farmId", type="integer", required=true, in="formData", description="农场id"),
	*  @SWG\Parameter(name="areaAmount", type="integer", required=true, in="formData", description="片区数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "修改地块成功", "success": true } } }
	*  )
	* )
	* 修改地块
	* @author 		xww
	* @return 		json
	*/
	public function update()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 农场对象
			$farmModel = new \VirgoModel\FarmModel;

			// 地块对象
			$model = new \VirgoModel\AcreModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 5]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和修改数据权限", '070');
			}

			//验证
			$this->configValid('required',$this->_configs,['id', 'name', 'acreage', 'managerId', 'areaAmount', 'farmId']);

			$id = (int)$this->_configs['id'];
			$name = $this->_configs['name'];
			$acreage = (float)$this->_configs['acreage'];
			$manager_id = (int)$this->_configs['managerId'];
			$area_amount = (int)$this->_configs['areaAmount'];
			$farm_id = (int)$this->_configs['farmId'];

			// 查询数据
			$data = $model->readSingleTon($id);

			if( empty($data) ) {
				throw new \Exception("地块数据可能不存在或已删除", '006');	
			}

			// 面积,地块数 0报错
			if( empty($acreage) || empty($area_amount) ) {
				throw new \Exception("acreage or areaAmount can not be null", '014');
			}

			// 查询用户
			$record = $userModel->readSingleTon( $manager_id );

			if( empty($record) ) {
				throw new \Exception("无法查询到负责人 可能不存在或已删除", '006');	
			}

			// 查询农场
			$record = $farmModel->readSingleTon( $farm_id );

			if( empty($record) ) {
				throw new \Exception("无法查询到农场 可能不存在或已删除", '006');	
			}

			$updateData['name'] = $name;
			$updateData['acreage'] = $acreage;
			$updateData['farm_id'] = $farm_id;
			$updateData['manager_id'] = $manager_id;
			$updateData['area_amount'] = $area_amount;
			$updateData['update_time'] = time();

			$rs = $model->partUpdate($id, $updateData);

			if( !$rs ) {
				throw new \Exception("修改地块失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '修改地块成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/acre/all", tags={"Acre"}, 
	*  summary="获取所有地块",
	*  description="鉴权 获取所有地块 包括id和name",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "AllAcre", "status": { "code": "001", "message": "获取地块列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/AllAcre")
	*   )
	*  )
	* )
	* 获取所有片区
	* @author 	xww
	* @return 	json
	*/
	public function all() 
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\AcreModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 4]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和查看数据权限", '070');
			}

			$data = $model->getAll();

			$data = empty($data)? null:$data;
			$return = $this->functionObj->toAppJson($data, '001', '获取所有地块成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}