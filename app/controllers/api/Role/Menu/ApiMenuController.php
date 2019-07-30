<?php
namespace VirgoApi\Role\Menu;
class ApiMenuController extends \VirgoApi\ApiBaseController{

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
	* @SWG\Get(path="/api/v1/role/menu/inAll", tags={"Role"}, 
	*  summary="获取当前角色 所被分配的菜单id列表",
	*  description="鉴权后通过 传入当前角色id获取被分配的菜单列表",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="角色id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "RoleMenuLists", "status": { "code": "001", "message": "获取用户角色菜单列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/RoleMenuLists"
	*   )
	*  )
	* )
	* 获取角色列表 
	* 鉴权后通过 传入当前角色id获取角色拥有的菜单列表
	* @author 	xww
	* @return 	json
	*/
	public function inAll()
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
			$this->configValid('required',$this->_configs,['id']);

			$id = $this->_configs['id'];

			$data = $model->getRoleMenus($id);
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取用户角色菜单列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/role/menu/update", tags={"Role"}, 
	*  summary="更新角色菜单",
	*  description="传入令牌和账号进行鉴权 鉴权后 通过传入的角色id 和菜单以,分隔的菜单ids进行创建 通过标识doEmpty来规定是否执行清空角色菜单操作",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="ids", type="string", required=true, in="formData", description="菜单ids 以,分隔"),
	*  @SWG\Parameter(name="roleId", type="integer", required=true, in="formData", description="角色id"),
	*  @SWG\Parameter(name="doEmpty", type="integer", required=false, in="formData", description="是否执行清空操作 0否1是默认0", default="0",
	*   @SWG\Schema(
	*    type="integer",
	*    enum="[0,1]"
	*   )
	*  ),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": null, "status": { "code": "001", "message": "更新用户角色菜单成功", "success": true } } }
	*  )
	* )
	* 
	* 更新角色分配给角色的菜单
	* 通过鉴权后 传入对应的数组字符串， 如果有标识清空 将不执行插入
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

			// 角色菜单对象
			$model = new \VirgoModel\RoleToMenuModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 4]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和更新数据权限", '070');
			}

			//验证
			$this->configValid('required',$this->_configs,['ids', "roleId"]);

			// 是否清空菜单 清空时并不新建
			$doEmpty = empty($this->_configs['doEmpty'])? false:true;

			// 菜单ids
			$menuIds = explode(",", $this->_configs['ids']);

			// 角色id
			$roleId = (int)$this->_configs['roleId'];

			$rs = $model->saveRoleMenus($roleId, $menuIds, $doEmpty);

			if( !$rs ) {
				throw new \Exception("更新用户角色菜单列表失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '更新用户角色菜单成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/role/menu/parentTreeInAll", tags={"Role"}, 
	*  summary="获取当前角色 所被分配的菜单id列表",
	*  description="鉴权后通过 传入当前角色id获取被分配的菜单列表",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="角色id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "ParentTreeRoleMenuLists", "status": { "code": "001", "message": "获取用户角色菜单列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/ParentTreeRoleMenuLists")
	*   )
	*  )
	* )
	* 获取角色列表 
	* 鉴权后通过 传入当前角色id获取角色拥有的菜单列表
	* @author 	xww
	* @return 	json
	*/
	public function parentTreeInAll()
	{
		
		try {

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 角色菜单对象
			$model = new \VirgoModel\RoleToMenuModel;

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

			$data = $model->getRoleParentTreeMenus($id);
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取用户角色菜单列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}