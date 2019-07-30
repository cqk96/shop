<?php
namespace VirgoApi\Department\User;
class ApiUserController extends \VirgoApi\ApiBaseController
{

	/**
	* api参数数组
	* @var array
	*/
	private $_configs;

	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
		$this->model = new \VirgoModel\DepartmentRelUserModel;
		$this->_configs = parent::change();
	}

	/**
	* @SWG\Get(path="/api/v1/department/user/lists", tags={"Department"}, 
	*  summary="获取该部门拥有的用户列表",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="部门id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": { "data":  "DepartmentUserListsObj", "totalPage": 4, "currentPage": 1 }, "status": { "code": "001", "message": "获取部门用户列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/DepartmentUserListsObj"
	*   )
	*  )
	* )
	* 获取该部门拥有的用户列表
	* @author 	xww
	* @return 	json
	*/
	public function lists()
	{

		try {

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 部门用户对象
			$model = new \VirgoModel\DepartmentRelUserModel;

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

			// 分页
			// $page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			// $size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			// $page -= 1;
			// $skip = $page*$size;

			$pageObj = $model->getListsObject($id);

			$data = [];

			$data['data'] = empty($pageObj->data)? null:$pageObj->data;
			$data['totalPage'] = intval( ceil($pageObj->totalCount/$pageObj->size) );
			$data['currentPage'] = intval( $pageObj->current_page );

			$return = $this->functionObj->toAppJson($data, '001', '获取部门用户列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/department/user/create", tags={"Department"}, 
	*  summary="添加部门用户",
	*  description="用户鉴权后 通过传入的部门id和用户id述来创建部门用户",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="部门id"),
	*  @SWG\Parameter(name="userId", type="integer", required=true, in="formData", description="用户id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "DepartmentUserLists", "status": { "code": "001", "message": "创建部门员工成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/DepartmentUserLists"
	*   )
	*  )
	* )
	* 添加部门用户
	* @author 	xww
	* @return 	string/object 	json
	*/
	public function create()
	{
		
		try {

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 部门用户对象
			$model = new \VirgoModel\DepartmentRelUserModel;

			// 部门对象
			$departmentModel = new \VirgoModel\DepartmentModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 2]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和添加数据权限", '070');
			}

			// 必要验证 id
			$this->configValid('required',$this->_configs,['id', "userId"]);

			$id = $this->_configs['id'];

			$userId = $this->_configs['userId'];

			// 判断是否存在该部门
			$hasRecord = $departmentModel->readSingelTon($id);
			if(empty($hasRecord)) {
				throw new \Exception("部门不存在或已删除", '006');
			}

			// 判断是否存在该用户
			$hasUserData = $userModel->readSingleTon($userId);
			if(empty($hasUserData)){
				throw new \Exception("用户不存在或已删除", '006');	
			}

			// 判断是否已经存在关联
			$hasRelData = $this->model->hasRel($id, $userId);

			if(!empty($hasRelData)) {
				throw new \Exception("用户已经和该部门关联", '068');	
			}

			// 插入数据
			$insertData['department_id'] = $id;
			$insertData['user_id'] = $userId;
			$insertData['create_time'] = time();
			$insertData['update_time'] = time();

			/*生成数据*/
			$insertRs = $this->model->create($insertData);

			if(!$insertRs) {
				throw new \Exception("生成部门员工失败", '005');
			}

			$data = $this->model->getRelUser($insertRs);
			$data['relId'] = $data['id'];
			unset($data['id']);
			unset($data['department_id']);
			unset($data['user_id']);
			unset($data['is_deleted']);
			unset($data['create_time']);
			unset($data['update_time']);

			$return = $this->functionObj->toAppJson($data, '001', '创建部门员工成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/department/user/delete", tags={"Department"}, 
	*  summary="移除部门用户",
	*  description="用户鉴权后 通过传入的部门用户列表中返回的redId来移除部门用户",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="部门用户列表中返回的redId"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": null, "status": { "code": "001", "message": "删除部门用户成功", "success": true } } }
	*  )
	* )
	* 移除部门用户
	* @author 	xww
	* @return 	string/object 	json 	
	*/
	public function delete()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 部门用户对象
			$model = new \VirgoModel\DepartmentRelUserModel;

			// 部门对象
			$departmentModel = new \VirgoModel\DepartmentModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 3]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和删除数据权限", '070');
			}

			// 必要验证 id
			$this->configValid('required',$this->_configs,['id']);

			$id = $this->_configs['id'];

			// 判断是否有此部门
			$hasRecord = $this->model->read( $id );
			if( empty($hasRecord) ) { 
				throw new \Exception("部门用户不存在或已删除", '006'); 
			}

			$updateData['update_time'] = time();
			$updateData['is_deleted'] = 1;

			// 进行删除操作
			$rs = $this->model->updatePart($this->_configs['id'], $updateData);
			unset($updateData);

			if(!$rs) {
				throw new \Exception("删除部门用户失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '删除部门用户成功', true);			

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* 搜索没有进行过部门录入的用户 进行搜索
	* @author 	xww
	* @return 	json
	*/
	public function notInSearch()
	{

		try{

			//验证 
			$this->configValid('required',$this->_configs,['search']);		

			$userObj = new \VirgoModel\UserModel;

			$data = $userObj->searchNotInDepartmentUser($this->_configs['search']);

			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', "查询成功", true);
			
		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/department/users", tags={"Department"}, 
	*  summary="app员工管理",
	*  description="用户鉴权后 通过传入的page,size获取数据列表 不包括自己",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "ManagementStaffLists", "status": { "code": "001", "message": "获取部门用户列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/ManagementStaffLists")
	*   )
	*  )
	* )
	* 员工管理--附带搜索
	* @author 	xww
	* @return 	json
	*/
	public function users()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 实例化对象--部门
			$model = new \VirgoModel\DepartmentModel;

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

			$search = empty( $this->_configs['search'] )? null:$this->_configs['search'];
	
			if( $uid==1 ) {
				$departmentIds=null;
			} else {

				// 获取用户所在部门
				$ids = $model->getUserDepartmentId( $uid );

				$departmentIds = [];

				$temp = [];
				$tempIds = [];
				$hasTree = false;
				for ($i=0; $i < count($ids); $i++) { 
					
					// 获取对应的所有部门ids
					$curDepartmentTree = $model->getTopMenu( $ids[ $i ], 0, 1000);

					if( !empty($curDepartmentTree) ) {
						$hasTree = true;
						$temp[$i] = $curDepartmentTree;

						$tempIds[ $i ] = [];

						/*放弃本身节点*/
						for ($j=1; $j <count($curDepartmentTree); $j++) {

							$tempIds[ $i ][] = $curDepartmentTree[$j]['id'];

						}

					}

				}

				// 用户不存在部门
				if( !$hasTree ) {
					$departmentIds = [-1];
				} else {


					for ($i=0; $i < count($ids); $i++) { 

						foreach ($tempIds as $key => $valueArr) {
							
							if( in_array($ids[ $i ], $valueArr) ) {
								unset( $tempIds[ $i ] );
								break;
							}

						}

					}

					if( empty($tempIds) ) {
						$departmentIds = [-1];
					} else {
						
						foreach ($tempIds as $key => $valueArr) {

							for ($i=0; $i < count($valueArr); $i++) { 
								array_push($departmentIds, $valueArr[$i]);
							}

						}

					}

				}

			}

			$data = $model->getDepartmentsUserLists($departmentIds, $skip, $size, $search, [$uid] );
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', "查询员工列表成功", true);
			
		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/department/departmentUsers", tags={"Department"}, 
	*  summary="获取消息or指令可以选择的接收用户",
	*  description="用户鉴权后 通过传入的page,size获取数据列表 不包括自己",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "MessageReceiverStaffLists", "status": { "code": "001", "message": "获取接收员工列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/MessageReceiverStaffLists")
	*   )
	*  )
	* )
	* 获取消息or指令可以选择的接收用户
	* @author 	xww
	* @return 	json
	*/
	public function departmentUsers()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 实例化对象--部门
			$model = new \VirgoModel\DepartmentModel;

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

			$search = empty( $this->_configs['search'] )? null:$this->_configs['search'];
	
			if( $uid==1 ) {
				$departmentIds=null;
			} else {

				// 获取用户所在部门
				$ids = $model->getUserDepartmentId( $uid );

				$departmentIds = [];
				$temp = [];
				$tempIds = [];
				$hasTree = false;
				for ($i=0; $i < count($ids); $i++) { 
					
					// 获取对应的所有部门ids
					$curDepartmentTree = $model->getTopMenu( $ids[ $i ], 0, 1000);

					if( !empty($curDepartmentTree) ) {
						$hasTree = true;
						$temp[$i] = $curDepartmentTree;

						$tempIds[ $i ] = [];

						/*放弃本身节点*/
						for ($j=1; $j <count($curDepartmentTree); $j++) {

							$tempIds[ $i ][] = $curDepartmentTree[$j]['id'];

						}

					}

				}

				// 用户不存在部门
				if( !$hasTree ) {
					$departmentIds = [-1];
				} else {


					for ($i=0; $i < count($ids); $i++) { 

						foreach ($tempIds as $key => $valueArr) {
							
							if( in_array($ids[ $i ], $valueArr) ) {
								unset( $tempIds[ $i ] );
								break;
							}

						}

					}

					if( empty($tempIds) ) {
						$departmentIds = [-1];
					} else {
						
						foreach ($tempIds as $key => $valueArr) {

							for ($i=0; $i < count($valueArr); $i++) { 
								array_push($departmentIds, $valueArr[$i]);
							}

						}

					}

				}

			}

			$data = $model->getPackageDepartmentsUserLists($departmentIds, $skip, $size, $search, [$uid] );
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', "获取接收员工列表成功", true);
			
		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}