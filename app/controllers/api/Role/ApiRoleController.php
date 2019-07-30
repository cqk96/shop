<?php
namespace VirgoApi\Role;
class ApiRoleController extends \VirgoApi\ApiBaseController{

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
	* @SWG\Get(path="/api/v1/role/lists", tags={"Role"}, 
	*  summary="获取角色管理 角色列表",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "BackRoleListsObj", "code": "001", "message": "获取角色列表成功", "success": true } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/BackRoleListsObj"
	*   )
	*  )
	* )
	* 获取角色列表 
	* 鉴权后通过 传入分页对象获取数据列表
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

			// 角色对象
			$model = new \VirgoModel\SysRoleModel;

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

			$return = $this->functionObj->toLayuiJson($data, '001', '获取角色列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/role/create", tags={"Role"}, 
	*  summary="创建角色",
	*  description="用户鉴权后 通过传入的角色名和角色描述来创建新角色 可以上传指定角色字典",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="角色名称"),
	*  @SWG\Parameter(name="description", type="string", required=true, in="formData", description="角色描述"),
	*  @SWG\Parameter(name="typeId", type="integer", required=false, in="formData", description="角色字典类型 不传则是默认使用系统规则生成"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data":null, "status": { "code": "001", "message": "增加角色成功", "success": true } } },
	*  )
	* )
	* 创建角色
	* 鉴权后通过 传入角色名和描述 来创建新角色 同样typeId的会创建失败
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

			// 角色对象
			$model = new \VirgoModel\SysRoleModel;

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
			$this->configValid('required',$this->_configs,['name', 'description']);

			$name = $this->_configs['name'];
			$description = $this->_configs['description'];
			$typeId = empty($this->_configs['typeId']) || (int)$this->_configs['typeId']<=0 ? $model->getNextTypeId():$this->_configs['typeId'];

			// 判断这个角色类型是否存在
			$rs = $model->hasTypeId( $typeId );

			if( $rs ) {
				throw new \Exception("已存在该类型id的角色", '026');
			}

			$insertData['name'] = $name;
			$insertData['description'] = $description;
			$insertData['type_id'] = $typeId;
			$insertData['deleted'] = 0;

			$rs = $model->createSingleTon( $insertData );

			if( !$rs ) {
				throw new \Exception("添加角色数据失败", '005');
			}

			$return = $this->functionObj->toAppJson(null, '001', '增加角色成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/role/delete", tags={"Role"}, 
	*  summary="删除角色",
	*  description="用户鉴权后 通过传入以,分隔的角色ids字符串进行删除角色",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="roleIds", type="string", required=true, in="formData", description="角色字符串id 以,分隔每个id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data":null, "status": { "code": "001", "message": "删除角色成功", "success": true } } }
	*  )
	* )
	* 删除角色
	* 鉴权后 通过传入以,分隔的角色ids字符串进行删除角色
	* @author 		xww
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

			// 角色对象
			$model = new \VirgoModel\SysRoleModel;

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
			$this->configValid('required',$this->_configs,['roleIds']);

			$roleIdsArr = explode(",", $this->_configs['roleIds']);

			$roleIds = [];

			for ($i=0; $i < count($roleIdsArr); $i++) { 
				$roleId = (int)$roleIdsArr[$i];

				if( empty($roleId) ) {
					continue;
				}

				$roleIds[] = $roleId;

			}

			if( empty($roleIds) ) {
				throw new \Exception("Wrong Param roleIds", "014");
			}

			$records = $model->getRoleArray( $roleIds );

			if( empty($records) ) {
				throw new \Exception("已经删除过了或查询不到对应记录", '006');
			}

			$updateData = [];
			$updateData['deleted'] = 1;

			$rs = $model->multipartUpdate($roleIds, $updateData);

			if( !$rs ) {
				throw new \Exception("删除角色失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '删除角色成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/role/read", tags={"Role"}, 
	*  summary="查看角色详情",
	*  description="用户鉴权后 通过传入的角色id 获取该角色详情",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="roleId", type="integer", required=true, in="query", description="角色id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data":"Role", "status": { "code": "001", "message": "获取角色详情成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/Role"
	*   )
	*  )
	* )
	* 查看角色详情
	* 通过传入的角色id 获取该角色详情
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

			// 角色对象
			$model = new \VirgoModel\SysRoleModel;

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
			$this->configValid('required',$this->_configs,['roleId']);

			$id = $this->_configs['roleId'];

			$data = $model->readSingleTon( $id );

			if( empty($data) ) {
				throw new \Exception("查询不到数据", '006');
			}

			unset($data['deleted']);
			unset($data['department_scope']);
			
			$return = $this->functionObj->toAppJson($data, '001', '获取角色详情成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/role/update", tags={"Role"}, 
	*  summary="更新角色",
	*  description="用户鉴权后 通过传入的角色id,名称,描述更新角色信息",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="roleId", type="integer", required=true, in="formData", description="角色id"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="角色名称"),
	*  @SWG\Parameter(name="description", type="string", required=true, in="formData", description="角色描述"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data":null, "status": { "code": "001", "message": "更新角色信息成功", "success": true } } }
	*  )
	* )
	* 更新角色
	* 鉴权后 通过传入的角色id,名称,描述更新角色信息
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

			// 角色对象
			$model = new \VirgoModel\SysRoleModel;

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
			$this->configValid('required',$this->_configs,['roleId', 'name',"description"]);

			$id = $this->_configs['roleId'];
			$name = $this->_configs['name'];
			$description = $this->_configs['description'];

			$updateData['name'] = $name;
			$updateData['description'] = $description;

			$rs = $model->partUpdate($id, $updateData);

			if( !$rs ) {
				throw new \Exception("更新角色失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '更新角色信息成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/role/all", tags={"Role"}, 
	*  summary="获取全部角色",
	*  description="鉴权",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data":"AllRole", "status": { "code": "001", "message": "获取全部角色成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/AllRole"
	*   )
	*  )
	* )
	* 获取全部角色
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

			// 角色对象
			$model = new \VirgoModel\SysRoleModel;

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

			$return = $this->functionObj->toAppJson($data, '001', '获取全部角色成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}