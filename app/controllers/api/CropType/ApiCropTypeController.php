<?php
namespace VirgoApi\CropType;
class ApiCropTypeController extends \VirgoApi\ApiBaseController{

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
	* @SWG\Get(path="/api/v1/cropType/lists", tags={"CropType"}, 
	*  summary="获取作物管理 作物列表",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="name", type="string", required=false, in="query", description="作物种类名"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data":"CropTypeListsObj", "code": "001", "message": "获取作物种类列表成功", "totalCount": 14 } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/CropTypeListsObj"
	*   )
	*  )
	* )
	* 获取作物种类列表
	* @author 	xww
	* @return 	json
	*/
	public function lists()
	{

		try {

			//验证 
			$user = $this->getUserApi($this->_configs, 1);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 作物种类对象
			$model = new \VirgoModel\CropTypeModel;

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

			/*种类名称*/
			$name =  empty( $this->_configs['name'] ) ? null:$this->_configs['name'];

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$pageObj = $model->getListsObject($skip, $size, $name);

			$data = [];

			$data = empty($pageObj->data)? null:$pageObj->data;
			// $data['totalPage'] = intval( ceil($pageObj->totalCount/$pageObj->size) );
			// $data['currentPage'] = intval( $pageObj->current_page );
			$totalCount = $pageObj->totalCount;

			$return = $this->functionObj->toLayuiJson($data, '001', '获取作物种类列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/cropType/all", tags={"CropType"}, 
	*  summary="获取全部作物种类",
	*  description="用户鉴权后即可",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "CropTypeLists", "status": { "code": "001", "message": "获取全部作物种类成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/CropTypeLists")
	*   )
	*  )
	* )
	* 获取全部作物种类
	* @author 	xww
	* @return 	json
	*/
	public function all()
	{

		try {

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 作物种类对象
			$model = new \VirgoModel\CropTypeModel;

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

			$return = $this->functionObj->toAppJson($data, '001', '获取全部作物种类成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/cropType/create", tags={"CropType"}, 
	*  summary="增加作物种类",
	*  description="用户鉴权后 通过传入的作物种类名来创建新作物种类 同名就失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="作物种类名称"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data":null, "status": { "code": "001", "message": "增加作物种类成功", "success": true } } },
	*  )
	* )
	* 增加作物种类
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

			// 作物种类对象
			$model = new \VirgoModel\CropTypeModel;

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
			$this->configValid('required',$this->_configs,['name']);

			$name = $this->_configs['name'];

			// 获取指定名称的作物种类
			$record = $model->getRecordWithName( $name );

			if( !empty($record) ) {
				throw new \Exception("同名作物种类已存在", '026');	
			}

			$data['name'] = $name;
			$data['create_time'] = time();
			$data['update_time'] = time();

			$rs = $model->create($data);

			if( !$rs ) {
				throw new \Exception("增加作物种类失败", '005');
			}

			$return = $this->functionObj->toAppJson(null, '001', '增加作物种类成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/cropType/delete", tags={"CropType"}, 
	*  summary="删除作物种类",
	*  description="用户鉴权后 通过传入以,分隔的角色ids字符串进行删除角色 可单独也可批量删除",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="ids", type="string", required=true, in="formData", description="作物ids字符串 以,分隔每个id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data":null, "status": { "code": "001", "message": "删除作物种类成功", "success": true } } }
	*  )
	* )
	* 删除作物种类
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

			// 作物种类对象
			$model = new \VirgoModel\CropTypeModel;

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

				if( empty($idsArr) ) {
					continue;
				}

				$ids[] = $singleId;

			}

			if( empty($ids) ) {
				throw new \Exception("Wrong Param ids", '014');
			}

			$records = $model->getCropTypeArray( $ids );

			if( empty($records) ) {
				throw new \Exception("已经删除过了或查询不到对应记录", '006');
			}

			$updateData = [];
			$updateData['is_deleted'] = 1;

			$rs = $model->multipartUpdate($ids, $updateData);
			unset($updateData);

			if( !$rs ) {
				throw new \Exception("删除作物种类失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '删除作物种类成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/cropType/read", tags={"CropType"}, 
	*  summary="查看作物种类详情",
	*  description="用户鉴权后 通过传入的作物种类id 获取该作物种类详情",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="作物种类id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data":"CropType", "status": { "code": "001", "message": "获取作物类型详情成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/CropType"
	*   )
	*  )
	* )
	* 查看记录详情
	* @author 	xww
	* @return 	json
	*/
	public function read()
	{

		try {

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 作物类型对象
			$model = new \VirgoModel\CropTypeModel;

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

			$data = $model->readSingleTon( $id );

			if( empty($data) ) {
				throw new \Exception("查询不到数据 可能不存在或是已经删除", '006');
			}
			
			$return = $this->functionObj->toAppJson($data, '001', '获取作物类型详情成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/cropType/update", tags={"CropType"}, 
	*  summary="更新作物种类",
	*  description="用户鉴权后 通过传入的作物种类id,名称更新作物种类信息",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="作物种类id"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="作物种类名称"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data":null, "status": { "code": "001", "message": "更新作物种类成功", "success": true } } }
	*  )
	* )
	* 更新作物种类
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

			// 作物种类对象
			$model = new \VirgoModel\CropTypeModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 5]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和更新数据权限", '070');
			}

			//验证
			$this->configValid('required',$this->_configs,['id', 'name']);

			$id = $this->_configs['id'];
			$name = $this->_configs['name'];

			$updateData['name'] = $name;
			$updateData['update_time'] = time();

			$rs = $model->partUpdate($id, $updateData);

			if( !$rs ) {
				throw new \Exception("更新作物种类失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '更新作物种类成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/cropType/detail", tags={"CropType"}, 
	*  summary="查看作物种类详情",
	*  description="用户鉴权后 通过传入的作物种类id 获取该作物种类详情 有统计数量 时间较长",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="作物种类id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data":"CropType", "status": { "code": "001", "message": "获取作物类型详情成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/CropType"
	*   )
	*  )
	* )
	* 查看记录详情
	* @author 	xww
	* @return 	json
	*/
	public function detail()
	{

		try {

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 作物类型对象
			$model = new \VirgoModel\CropTypeModel;

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

			$data = $model->readSingleDetail( $id );

			if( empty($data) ) {
				throw new \Exception("查询不到数据 可能不存在或是已经删除", '006');
			}
			
			$return = $this->functionObj->toAppJson($data, '001', '获取作物类型详情成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}