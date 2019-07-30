<?php
namespace VirgoApi\Farm;
class ApiFarmController extends \VirgoApi\ApiBaseController{

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
	* @SWG\Get(path="/api/v1/farm/lists", tags={"Farm"}, 
	*  summary="获取农场管理 农场列表",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "FarmListsObj" , "code": "001", "message": "获取农场列表成功", "totalCount": 3 } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/FarmListsObj"
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

			// 农场对象
			$model = new \VirgoModel\FarmModel;

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

			$return = $this->functionObj->toLayuiJson($data, '001', '获取农场列表成功', $pageObj->totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/farm/create", tags={"Farm"}, 
	*  summary="创建农场",
	*  description="用户鉴权后 通过传入的农场名、农场面积（亩）、负责人和地块数来创建新农场",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="农场名称"),
	*  @SWG\Parameter(name="acreage", type="string", required=true, in="formData", description="农场面积/亩，可支持两位小数"),
	*  @SWG\Parameter(name="managerId", type="integer", required=true, in="formData", description="负责人用户id"),
	*  @SWG\Parameter(name="acreAmount", type="integer", required=true, in="formData", description="地块数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "添加农场成功", "success": true } } }
	*  )
	* )
	* 增加农场
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
			$model = new \VirgoModel\FarmModel;

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
			$this->configValid('required',$this->_configs,['name', 'acreage', 'managerId', 'acreAmount']);

			$name = $this->_configs['name'];
			$acreage = (float)$this->_configs['acreage'];
			$manager_id = (int)$this->_configs['managerId'];
			$acre_amount = (int)$this->_configs['acreAmount'];

			// 面积,地块数 0报错
			if( empty($acreage) || empty($acre_amount) ) {
				throw new \Exception("acreage or acreAmount can not be null", '014');
			}

			// 查询用户
			$record = $userModel->readSingleTon( $manager_id );

			if( empty($record) ) {
				throw new \Exception("无法查询到负责人 可能不存在或已删除", '006');	
			}

			$insertData['name'] = $name;
			$insertData['acreage'] = $acreage;
			$insertData['manager_id'] = $manager_id;
			$insertData['acre_amount'] = $acre_amount;
			$insertData['create_time'] = time();
			$insertData['update_time'] = time();

			$rs = $model->create( $insertData );

			if( !$rs ) {
				throw new \Exception("添加农场失败", '005');
			}

			$return = $this->functionObj->toAppJson(null, '001', '添加农场成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/farm/delete", tags={"Farm"}, 
	*  summary="删除农场",
	*  description="用户鉴权后 通过传入的农场ids 进行农场删除 如果这些农场存在下属没有删除地块则失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="ids", type="string", required=true, in="formData", description="农场ids"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "删除农场成功", "success": true } } }
	*  )
	* )
	* 删除农场
	*/
	public function delete()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 农场对象
			$model = new \VirgoModel\FarmModel;

			// 地块对象
			$acreModel = new \VirgoModel\AcreModel;

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
				
				$singleId = (int)$idsArr[$i];
				if( empty($singleId) ) {
					continue;
				}

				$ids[] = $singleId;

			}

			if( empty($ids) ) {
				throw new \Exception("Error Processing Request", '014');
			}

			// 查询农场
			// $record = $model->readSingleTon($id);

			// if( empty($record) ) {
			// 	throw new \Exception("农场数据可能不存在或已删除", '006');	
			// }

			$records = $acreModel->getFarmsAcres( $ids );

			if( !empty($records) ) {
				throw new \Exception("该农场还有地块，无法进行删除", "094");
			}

			$updateData['is_deleted'] = 1;
			$updateData['update_time'] = time();

			$rs = $model->multiplePartUpdate($ids, $updateData);

			if( !$rs ) {
				throw new \Exception("删除农场失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '删除农场成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/farm/read", tags={"Farm"}, 
	*  summary="农场详情",
	*  description="用户鉴权后 通过传入的id获取农场对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="农场记录id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "Farm", "status": { "code": "001", "message": "查询农场情况成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/Farm"
	*   )
	*  )
	* )
	* 农场详情
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

			// 农场对象
			$model = new \VirgoModel\FarmModel;

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
				throw new \Exception("农场数据可能不存在或已删除", '006');	
			}

			$return = $this->functionObj->toAppJson($data, '001', '查询农场情况成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/farm/update", tags={"Farm"}, 
	*  summary="更新农场",
	*  description="用户鉴权后 通过传入的农场记录id, 农场名、农场面积（亩）、负责人和地块数来更新农场信息",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="农场id"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="农场名称"),
	*  @SWG\Parameter(name="acreage", type="string", required=true, in="formData", description="农场面积/亩，可支持两位小数"),
	*  @SWG\Parameter(name="managerId", type="integer", required=true, in="formData", description="负责人用户id"),
	*  @SWG\Parameter(name="acreAmount", type="integer", required=true, in="formData", description="地块数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "更新农场成功", "success": true } } }
	*  )
	* )
	* 更新农场
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
			$model = new \VirgoModel\FarmModel;

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
			$this->configValid('required',$this->_configs,['id', 'name', 'acreage', 'managerId', 'acreAmount']);

			$id = (int)$this->_configs['id'];
			$name = $this->_configs['name'];
			$acreage = (float)$this->_configs['acreage'];
			$manager_id = (int)$this->_configs['managerId'];
			$acre_amount = (int)$this->_configs['acreAmount'];

			// 查询数据
			$data = $model->readSingleTon($id);

			if( empty($data) ) {
				throw new \Exception("农场数据可能不存在或已删除", '006');	
			}

			// 面积,地块数 0报错
			if( empty($acreage) || empty($acre_amount) ) {
				throw new \Exception("acreage or acreAmount can not be null", '014');
			}

			// 查询用户
			$record = $userModel->readSingleTon( $manager_id );

			if( empty($record) ) {
				throw new \Exception("无法查询到负责人 可能不存在或已删除", '006');	
			}

			$updateData['name'] = $name;
			$updateData['acreage'] = $acreage;
			$updateData['manager_id'] = $manager_id;
			$updateData['acre_amount'] = $acre_amount;
			$updateData['update_time'] = time();

			$rs = $model->partUpdate($id, $updateData);

			if( !$rs ) {
				throw new \Exception("修改农场失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '修改农场成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/farm/all", tags={"Farm"}, 
	*  summary="获取所有农场",
	*  description="鉴权",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "AllFarm", "status": { "code": "001", "message": "查询农场情况成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/AllFarm"
	*   )
	*  )
	* )
	* 获取所有农场
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

			// 农场对象
			$model = new \VirgoModel\FarmModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 4]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和查看数据权限", '070');
			}

			// 查询数据
			$data = $model->getAll();
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '查询农场情况成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}


/**
	* @SWG\Post(path="/api/v1/farm/creategoods", tags={"Farm"}, 
	*  summary="创建商品",
	*  description="用户鉴权后 通过传入的商品名称，中文名，外文名，价格售价等创建商品如果存在同名同属地块的商品新建会失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="title", type="string", required=true, in="formData", description="商品名称"),
	*  @SWG\Parameter(name="chinese_title", type="string", required=true, in="formData", description="中文名"),
	*  @SWG\Parameter(name="foreign_title", type="string", required=true, in="formData", description="外文名"),
	*  @SWG\Parameter(name="price", type="integer", required=true, in="formData", description="价格"),
	*  @SWG\Parameter(name="Original_Price", type="integer", required=true, in="formData", description="原价"),
	*  @SWG\Parameter(name="discount", type="integer", required=true, in="formData", description="折扣"),
	*  @SWG\Parameter(name="rush_time", type="string", required=false, in="formData", description="剩余抢购时间"),
	*  @SWG\Parameter(name="rate", type="string", required=false, in="formData", description="好评率"),
	*  @SWG\Parameter(name="language", type="integer", required=true, in="formData", description="语言 1英语2中文3繁体中文4越南语5日语"),
	*  @SWG\Parameter(name="domain_name", type="string", required=false, in="formData", description="域名"),
	*  @SWG\Parameter(name="sales", type="string", required=false, in="formData", description="销量"),
	*  @SWG\Parameter(name="Inventory", type="string", required=false, in="formData", description="库存"),
	*  @SWG\Parameter(name="email", type="string", required=false, in="formData", description="联系邮箱"),
	*  @SWG\Parameter(name="LINE", type="string", required=false, in="formData", description="LINE"),
	*  @SWG\Parameter(name="pop800id", type="string", required=false, in="formData", description="pop800id"),
	*  @SWG\Parameter(name="Fb_Id", type="string", required=false, in="formData", description="FB通用像素id"),
	*  @SWG\Parameter(name="readme", type="string", required=false, in="formData", description="购买提示"),
	*  @SWG\Parameter(name="content", type="string", required=false, in="formData", description="内容"),
	*  @SWG\Parameter(name="logo", type="string", required=false, in="formData", description="logo"),
	*  @SWG\Parameter(name="thumbnail", type="string", required=false, in="formData", description="缩略图"),
	*  @SWG\Parameter(name="images", type="string", required=false, in="formData", description="图集"),
	*  @SWG\Parameter(name="properties", type="string", required=false, in="formData", description="属性"),
	*  @SWG\Parameter(name="setmeal", type="string", required=false, in="formData", description="套餐"),
	*  @SWG\Parameter(name="labels", type="string", required=false, in="formData", description="标签"),
	*  @SWG\Parameter(name="content", type="string", required=false, in="formData", description="内容"),

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
	public function creategoods()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 商品对象
			$FarmModel = new \VirgoModel\FarmModel;
			
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
			$this->configValid('required',$this->_configs,['title',	"foreign_title", "chinese_title", "price", "Original_Price",'discount','Inventory','sales','rate','rush_time','email','LINE','pop800id','Fb_Id']);

			/*事务*/
			DB::beginTransaction();

			$isBlock = true;

			$name = $this->_configs['title'];
			$price = (float)$this->_configs['price'];
			$Original_Price = (float)$this->_configs['Original_Price'];
			$discount = (float)$this->_configs['discount'];
			$Inventory = (int)$this->_configs['Inventory'];
			$sales = (int)$this->_configs['sales'];
			$LINE = (int)$this->_configs['LINE'];
			$pop800id = (int)$this->_configs['pop800id'];
			$Fb_Id = (int)$this->_configs['Fb_Id'];
			$readme = (int)$this->_configs['readme'];
			
	

			// 剩余抢购时间rush_time 格式e.g 2018-08-06
			$rush_time = empty($this->_configs['rush_time'])? 0:$this->_configs['rush_time'];

			

			// 名称,价格 0报错
			if( empty($title) || empty($price) ) {
				throw new \Exception("title or price can not be null", '014');
			}

			// 是否有同名商品创建过
			$record = $FarmModel->getName($title, $forgetPwdNotice,$chinese_title);
			if( !empty($record) ) {
				throw new \Exception("已存在同名商品", '026');
			}

			$insertData['title'] = $title;
			$insertData['price'] = $price;
			$insertData['Original_Price'] = $Original_Price;
			$insertData['discount'] = $discount;
			$insertData['sales'] = $sales;
			$insertData['Inventory'] = $Inventory;
			$insertData['rush_time'] = $rush_time;
			$insertData['readme'] = $readme;
			$insertData['LINE'] = $LINE;
			$insertData['remarks'] = $remarks;
			$insertData['thumbnail'] = $thumbnail;
			$insertData['images'] = $images;
			$insertData['updated_at'] = $updated_at;
			$insertData['language'] = $language;
			$insertData['domain_name'] = $domain_name;
			$insertData['is_deleted'] = 0;
			$insertData['create_time'] = time();
			$insertData['update_time'] = time();

			$recordId = $model->create( $insertData );

			if( !$recordId ) {
				throw new \Exception("添加商品失败", '005');
			}

			

			DB::commit();
			$return = $this->functionObj->toAppJson(null, '001', '创建商品成功', true);

		} catch(\Exception $e) {

			if( isset($isBlock) ) {
				DB::rollback();
			}

			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);

		} finally {
			$this->responseResult($return);
		}

	}

}