<?php
namespace VirgoApi\Role\Operation;
use VirgoApi;
class ApiOperationController extends VirgoApi\ApiBaseController{

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
	* @SWG\Get(path="/api/v1/role/operation/inAll", tags={"Role"}, 
	*  summary="获取角色有的操作权限",
	*  description="鉴权后 通过传入的角色id 获取操作权限列表  有标记字段标识是否有此权限",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="roleId", type="integer", required=true, in="query", description="角色id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "RoleOperationLists", "status": { "code": "001", "message": "获取角色操作权限列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/RoleOperationLists"
	*   )
	*  )
	* )
	* 获取角色有的操作权限
	* 鉴权后 通过传入的角色id 获取操作权限列表  有标记字段标识是否有此权限
	* @author 	xww
	* @return 	json
	*/
	public function inAll()
	{
		
		try{

			// 验证 
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

			$data = $model->getRoleOperations($id);
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取角色操作权限列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/role/operation/update", tags={"Role"}, 
	*  summary="更新角色操作权限",
	*  description="传入令牌和账号进行鉴权 鉴权后 通过传入的角色id 和权限字符串以,分隔的权限id进行创建 通过标识doEmpty来规定是否执行清空角色操作权限操作",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="ids", type="string", required=true, in="formData", description="权限id字符串 以,分隔"),
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
	*   examples={"application/json": { "data": null, "status": { "code": "001", "message": "更新角色操作权限成功", "success": true } } }
	*  )
	* )
	* 
	* 更新角色操作权限
	* 传入令牌和账号进行鉴权 鉴权后 通过传入的角色id 和权限字符串以,分隔的权限id进行创建 通过标识doEmpty来规定是否执行清空角色操作权限操作
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

			// 角色操作权限对象
			$model = new \VirgoModel\OperatePrivilegeToRoleModel;

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

			// 操作权限ids
			$operationIds = explode(",", $this->_configs['ids']);

			// 角色id
			$roleId = (int)$this->_configs['roleId'];

			$rs = $model->saveRoleOperations($roleId, $operationIds, $doEmpty);

			if( !$rs ) {
				throw new \Exception("更新角色操作权限失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '更新角色操作权限成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}