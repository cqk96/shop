<?php
namespace VirgoApi\Department;
use Illuminate\Database\Capsule\Manager as DB;
class ApiDepartmentController extends \VirgoApi\ApiBaseController
{

	/**
	* api参数数组
	* @var array
	*/
	private $_configs;

	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
		$this->model = new \VirgoModel\DepartmentModel;
		$this->_configs = parent::change();
	}

	/**
	* @SWG\Get(path="/api/v1/department/lists", tags={"Department"}, 
	*  summary="获取部门管理 部门列表",
	*  description="需要鉴权 传入page,size获取分页对象",
	*  produces="{application/json}",
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="条数"),
	*  @SWG\Parameter(name="name", type="string", required=false, in="query", description="部门名"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "Department",  "code": "001", "message": "获取部门列表成功", "totalCount": 3 } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/Department"
	*   )
	*  )
	* )
	* 部门列表
	* @author 	xww
	* @return 	json
	*/
	public function lists()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs, 1);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 部门对象
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

			$name = empty( $this->_configs['name'] )? null:$this->_configs['name'];

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			/*获取全部部门*/
			$pageObj = $model->getListsObject($skip, $size, $name);
			// $data = empty($data)? null:$data;

			// $data = [];

			$data = empty($pageObj->data)? []:$pageObj->data;
			// $data['totalPage'] = intval( ceil($pageObj->totalCount/$pageObj->size) );
			// $data['currentPage'] = intval( $pageObj->current_page );
			$totalCount = $pageObj->totalCount;

			$return = $this->functionObj->toLayuiJson($data, '001', '获取部门列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/department/all", tags={"Department"}, 
	*  summary="获取所有部门",
	*  description="需要鉴权",
	*  produces="{application/json}",
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "DepartmentList", "status": { "code": "001", "message": "获取部门列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/DepartmentList"
	*   )
	*  )
	* )
	* 获取所有部门
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

			// 部门对象
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

			$data = $model->getAll();

			$return = $this->functionObj->toAppJson($data, '001', '获取所有部门成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/department/create", tags={"Department"}, 
	*  summary="创建部门或子部门",
	*  description="该接口可以创建顶级部门和下级部门 用户鉴权后 通过传入的部门名 选择传入上级部门id来创建新部门",
	*  produces="{application/json}",
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="部门名称"),
	*  @SWG\Parameter(name="pid", type="integer", required=false, in="formData", description="上级部门id 默认0顶级部门", default=0),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "DepartmentInfo", "status": { "code": "001", "message": "创建部门成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/DepartmentInfo"
	*   )
	*  )
	* )
	* 添加部门
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

			// 部门对象
			$model = new \VirgoModel\DepartmentModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 2]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和增加数据权限", '070');
			}

			// 必要验证 id
			$this->configValid('required',$this->_configs,['name']);

			$name = $this->_configs['name'];
			$pid = empty($this->_configs['pid']) || (int)$this->_configs['pid']<=0? 0:(int)$this->_configs['pid'];

			$hasRecord = $model->getSameSituationDepartment($name, $pid);

			if( !empty($hasRecord) ) {
				throw new \Exception("已存在该部门", '026');
			}

			$insertData['name'] = $name;
			$insertData['p_department_id'] = $pid;
			$insertData['create_time'] = time();
			$insertData['update_time'] = time();

			/*生成数据*/
			$insertRs = $this->model->create($insertData);

			if(!$insertRs) {
				throw new \Exception("生成部门失败", '005');
			}

			$data = $this->model->readSingelTon($insertRs)->toArray();

			$return = $this->functionObj->toAppJson($data, '001', '创建部门成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/department/delete", tags={"Department"}, 
	*  summary="删除部门 以及 下属子部门",
	*  description="该接口可以删除删除部门 以及 下属子部门 用户鉴权后 通过传入当前部门id进行删除 但如果存在用户则删除失败",
	*  produces="{application/json}",
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="要删除的部门id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": null, "status": { "code": "001", "message": "删除部门成功", "success": true } } }
	*  )
	* )
	* 删除部门 以及 他所有的子部门
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

			// 部门对象
			$model = new \VirgoModel\DepartmentModel;

			// 部门用户对象
			$departmentUserModel = new \VirgoModel\DepartmentRelUserModel;

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
			$hasRecord = $this->model->readSingelTon( $id );
			if( empty($hasRecord) ) { 
				throw new \Exception("部门不存在或已删除", '006'); 
			}

			// 获取这个部门所有关联部门
			$relDepartments = $model->getTopMenu($id, 0, 1000);

			$departmentIds = [];
			for ($i=0; $i < count($relDepartments); $i++) { 
				$departmentIds[] = $relDepartments[$i]['id'];
			}

			// 部门下的用户数量
			$personCount = $departmentUserModel->getDepartmentUsersCount($departmentIds);

			if( $personCount ) {
				throw new \Exception("部门仍然存在用户,请先移除完用户后 再尝试", '003'); 	
			}

			// 进行删除操作
			$this->model->deleteRelMenu( $id );

			$return = $this->functionObj->toAppJson(null, '001', '删除部门成功', true);			

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/department/update", tags={"Department"}, 
	*  summary="修改部门名称",
	*  description="用户鉴权后 通过传入当前部门id 和修改后的名称 进行名称修改",
	*  produces="{application/json}",
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="要修改的部门id"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="修改后的部门名称"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": null, "status": { "code": "001", "message": "修改部门成功", "success": true } } }
	*  )
	* )
	* 修改部门名称
	* @author 	xww
	* @return 	string/object 	json
	*/
	public function update()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 部门对象
			$model = new \VirgoModel\DepartmentModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 5]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和修改数据权限", '070');
			}

			// 必要验证 id
			$this->configValid('required',$this->_configs,['id', 'name']);

			$id = $this->_configs['id'];
			$name = $this->_configs['name'];

			// 判断是否有此菜单
			$hasRecord = $this->model->readSingelTon( $id );
			if( empty($hasRecord) ) {
			 	throw new \Exception("部门不存在或已删除", '006'); 
			}

			$data['update_time'] = time();
			$data['name'] = $name;

			// 更新
			$rs = $this->model->updateParts($id, $data);
			unset($data);

			if( !$rs ) {
				throw new \Exception("修改部门失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '修改部门成功', true);			

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/department/createHigher", tags={"Department"}, 
	*  summary="创建部门的上级部门",
	*  description="该接口可以创建指定部门的上级部门 用户鉴权后 通过传入的部门名 传入当前部门id来创建该部门的上级部门",
	*  produces="{application/json}",
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="部门名称"),
	*  @SWG\Parameter(name="cid", type="integer", required=true, in="formData", description="当前部门的id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "DepartmentInfo", "status": { "code": "001", "message": "添加上级部门成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/DepartmentInfo"
	*   )
	*  )
	* )
	* 添加上级部门
	* @author 	xww
	* @return 	string/object 	json
	*/
	public function createHigher()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 部门对象
			$model = new \VirgoModel\DepartmentModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 2]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和增加数据权限", '070');
			}

			// 必要验证 子部门id和部门名称
			$this->configValid('required',$this->_configs,['name', 'cid']);

			// 开启事务
			DB::beginTransaction();

			$isBlock = true;

			// 参数
			$curTime = time();

			// 判断是否有此部门
			$record = $this->model->readSingelTon($this->_configs['cid']);

			if( empty($record) ) { 
				throw new \Exception("子部门不存在或已删除", '006');
			}

			// 先 生成记录
			$insertData['name'] = $this->_configs['name'];
			$insertData['p_department_id'] = 0;
			$insertData['create_time'] = time();
			$insertData['update_time'] = time();

			/*生成数据*/
			$lastId = $this->model->create($insertData);

			if(!$lastId) {
				throw new \Exception("生成部门失败", '005');
			}

			// 查询该子id拥有的父亲id
			$parentId = $record['p_department_id'];

			// 修改本身
			$updateArr_first['update_time'] = $curTime;
			$updateArr_first['p_department_id'] = $lastId;
			$rs1 = $this->model->updateParts($this->_configs['cid'], $updateArr_first);
			if(!$rs1) {
				throw new \Exception("更新部门失败", '003');		
			}

			if($parentId!=0) {
				// 有上级

				// 修改记录
				$updateArr_second['update_time'] = $curTime;
				$updateArr_second['p_department_id'] = $parentId;
				$rs2 = $this->model->updateParts($lastId, $updateArr_second);
				if(!$rs2) {
					throw new \Exception("更新部门失败", '003');		
				}

			}

			DB::commit();

			// 获取数据
			$data = $this->model->readSingelTon($lastId);

			$return = $this->functionObj->toAppJson($data, '001', '添加上级部门成功', true);

		} catch(\Exception $e) {

			if( isset($isBlock) ) {
				DB::rollback();	
			}
			
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);

		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/department/updateDepartmentInfo", tags={"Department"}, 
	*  summary="修改部门信息",
	*  description="用户鉴权后 通过传入当前部门id 和名称 进行本身部门修改 其他根据参数进行修改",
	*  produces="{application/json}",
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="要修改的部门id"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="修改后的部门名称"),
	*  @SWG\Parameter(name="pid", type="integer", required=false, in="formData", description="要修改的上级部门id"),
	*  @SWG\Parameter(name="parentEmpty", type="integer", required=false, in="formData", description="是否进行清空上级部门操作，0否1是", default=0),
	*  @SWG\Parameter(name="cids", type="string", required=false, in="formData", description="要修改的下级部门id 以,分隔的字符串"),
	*  @SWG\Parameter(name="childrenEmpty", type="integer", required=false, in="formData", description="是否进行清空下级部门操作，0否1是", default=0),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": null, "status": { "code": "001", "message": "修改部门信息成功", "success": true } } }
	*  )
	* )
	* 修改部门信息
	* @author 	xww
	* @return 	json
	*/ 
	public function updateDepartmentInfo()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 部门对象
			$model = new \VirgoModel\DepartmentModel;

			DB::beginTransaction();

			$isBlock = true;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 5]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和修改数据权限", '070');
			}

			// 必要验证 id
			$this->configValid('required',$this->_configs,['id', 'name']);

			// 部门数据
			$id = $this->_configs['id'];
			$name = $this->_configs['name'];

			// 上级部门
			$pid = empty($this->_configs['pid']) || (int)$this->_configs['pid']==0 ? null:(int)$this->_configs['pid'];

			// 用来表示是否清空上级部门
			$parentEmpty = empty($this->_configs['parentEmpty'])? 0:1;

			// 下级部门
			$cidArr = empty($this->_configs['cids'])? null:explode(",", $this->_configs['cids']);

			// 用来表示是否清空下级部门
			$childrenEmpty = empty($this->_configs['childrenEmpty'])? 0:1;

			// 自己更新+上级部门更新
			// 清空上级部门
			if( $parentEmpty ) {
				$data['p_department_id'] = 0;
			} else {

				if( !is_null($pid) ) {
					$hasRecord = $this->model->readSingelTon( $pid );
					if( empty($hasRecord) ) {
					 	throw new \Exception("上级部门不存在或已删除", '006'); 
					}
					$data['p_department_id'] = $pid;
				}

			}

			// 判断是否有此部门
			$hasRecord = $this->model->readSingelTon( $id );
			if( empty($hasRecord) ) {
			 	throw new \Exception("部门不存在或已删除", '006'); 
			}

			$data['update_time'] = time();
			$data['name'] = $name;

			// 更新
			$rs = $this->model->updateParts($id, $data);
			unset($data);

			if( !$rs ) {
				throw new \Exception("修改部门失败", '003');
			}


			// 清空下级部门
			if( $childrenEmpty ) {

				$hasRecord = $this->model->getChildrensDepartments( $id );

				if( !empty($hasRecord) ) {

					$rs = $this->model->changeChildrenParentDeparment( $id );

					if( !$rs ) {
						throw new \Exception("清空下级部门失败", '003');
					}

				}

			} else {

				if( !is_null($cidArr) ) {

					$cids = [];
					for ($i=0; $i < count($cidArr); $i++) { 

						$cid = (int)$cidArr[$i];
						if( !$cid ) {
							continue;
						}

						$cids[] = $cid;

					}

					if( !empty($cids) ) {

						$hasRecord = $this->model->getChildrensDepartments( $id );

						if( !empty($hasRecord) ) {

							$rs = $this->model->changeChildrenParentDeparment( $id );

							if( !$rs ) {
								throw new \Exception("清空下级部门失败", '003');
							}

						}

						$rs = $this->model->setChildrenParentDeparment( $id, $cids);
						if( !$rs ) {
						 	throw new \Exception("设置下级部门失败", '003'); 
						}

					}
					
				}

			}

			DB::commit();
			
			$return = $this->functionObj->toAppJson(null, '001', '修改部门信息成功', true);

		} catch(\Exception $e) {

			if( isset($isBlock) ) {
				DB::rollback();	
			}
			
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);

		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* 获取当前部门下层子部门 以及部门用户
	* @author 	xww
	* @return 	json
	*/
	public function childrenAndUser()
	{
		
		try{

			if(empty($_COOKIE['user_id'])) {
				
				//获取用户
				$user = $this->getUserApi($this->_configs);	

			} else {
				$userObj = new \VirgoModel\UserModel;
				//获取用户
				$id = $_COOKIE['user_id'];
				$record = $userObj->readSingleTon($id);
				if(empty($record)) {
					throw new \Exception("用户不存在", '006');
				}
				$user[] = $record->toArray();
			}

			// 必要验证 id
			$this->configValid('required',$this->_configs,['pid']);

			// 实例化对象

			// 部门用户
			$departmentRelUserModelObj = new \VirgoModel\DepartmentRelUserModel;

			// 部门
			$departmentModelObj = new \VirgoModel\DepartmentModel;

			// 获取下层部门
			$departments = $departmentModelObj->getChildrensDepartments( $this->_configs['pid'] );
			$departments = empty($departments)? null:$departments;

			// 获取当前部门用户
			$users = $departmentRelUserModelObj->getDepartmentsUsers( [ $this->_configs['pid'] ] );
			$users = empty($users)? null:$users;

			$return = $this->functionObj->toAppJson([ 'departments'=>$departments, 'users'=>$users ], '001', '获取下层部门以及本部门用户成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/department/read", tags={"Department"}, 
	*  summary="获取部门详情/添加部门页面",
	*  description="需要鉴权 传入部门id获取部门详情 详情中除了自身数据外还包括两个列表，上级部门列表与下级部门列表通过 checked字段标明是否选中",
	*  produces="{application/json}",
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=false, in="query", description="部门id 可空"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "DepartmentInfoDetail", "status": { "code": "001", "message": "获取部门详情成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/DepartmentInfoDetail"
	*   )
	*  )
	* )
	* 获取部门详情
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

			// 部门对象
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

			// 必要验证 子部门id和部门名称
			// $this->configValid('required', $this->_configs,['id']);

			$id = empty($this->_configs['id'])? null:$this->_configs['id'];

			$data = $model->getDepartmentInfoWidthParentAndChildren( $id );
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取部门详情成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/department/selection", tags={"Department"}, 
	*  summary="可供选择的部门列表",
	*  description="鉴权 获取用户可以选择的部门列表",
	*  produces="{application/json}",
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "DepartmentSelectionLists", "status": { "code": "001", "message": "获取用户可选择的部门列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    ref="#/definitions/DepartmentSelectionLists"
	*   )
	*  )
	* )
	* 可供选择的部门列表
	* @author 	xww
	* @return 	json
	*/
	public function selection()
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

			if( $uid==1 ) {
				$departmentIds=null;
			} else {

				// 获取用户所在部门
				$ids = $model->getUserDepartmentId( $uid );

				$departmentIds = [];
				for ($i=0; $i < count($ids); $i++) { 
					// 获取对应的所有部门ids
					$temp = $model->getTopMenu( $ids[ $i ], 0, 1000);
					for ($j=0; $j <count($temp); $j++) { 
						$departmentIds[] = $temp[$j]['id'];
					}
				}

				// 用户不存在部门
				if( empty($departmentIds) ) {
					$departmentIds = [-1];
				}

			}

			$data = $model->getNamedDepartments( $departmentIds );
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取用户可选择的部门列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/department/createDepartmentInfo", tags={"Department"}, 
	*  summary="创建部门信息",
	*  description="用户鉴权后 通过传入部门名称 进行部门创建",
	*  produces="{application/json}",
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="部门名称"),
	*  @SWG\Parameter(name="pid", type="integer", required=false, in="formData", description="上级部门id"),
	*  @SWG\Parameter(name="parentEmpty", type="integer", required=false, in="formData", description="是否进行清空上级部门操作，0否1是", default=0),
	*  @SWG\Parameter(name="cids", type="string", required=false, in="formData", description="下级部门id 以,分隔的字符串"),
	*  @SWG\Parameter(name="childrenEmpty", type="integer", required=false, in="formData", description="是否进行清空下级部门操作，0否1是", default=0),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": null, "status": { "code": "001", "message": "创建部门信息成功", "success": true } } }
	*  )
	* )
	* 修改部门信息
	* @author 	xww
	* @return 	json
	*/ 
	public function createDepartmentInfo()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 部门对象
			$model = new \VirgoModel\DepartmentModel;

			DB::beginTransaction();

			$isBlock = true;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 5]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和修改数据权限", '070');
			}

			// 必要验证 id
			$this->configValid('required',$this->_configs,['name']);

			// 部门数据
			$name = $this->_configs['name'];

			// 上级部门
			$pid = empty($this->_configs['pid']) || (int)$this->_configs['pid']==0 ? null:(int)$this->_configs['pid'];

			$hasRecord = $model->getSameSituationDepartment($name, $pid);

			if( !empty($hasRecord) ) {
				throw new \Exception("已存在该部门", '026');
			}

			$insertData['name'] = $name;
			$insertData['p_department_id'] = $pid;
			$insertData['create_time'] = time();
			$insertData['update_time'] = time();

			/*生成数据*/
			$recordId = $model->create($insertData);

			if(!$recordId) {
				throw new \Exception("生成部门失败", '005');
			}

			$id = $recordId;
			// 用来表示是否清空上级部门
			$parentEmpty = empty($this->_configs['parentEmpty'])? 0:1;

			// 下级部门
			$cidArr = empty($this->_configs['cids'])? null:explode(",", $this->_configs['cids']);

			// 用来表示是否清空下级部门
			$childrenEmpty = empty($this->_configs['childrenEmpty'])? 0:1;

			// 自己更新+上级部门更新
			// 清空上级部门
			if( $parentEmpty ) {
				$data['p_department_id'] = 0;
			} else {

				if( !is_null($pid) ) {
					$hasRecord = $this->model->readSingelTon( $pid );
					if( empty($hasRecord) ) {
					 	throw new \Exception("上级部门不存在或已删除", '006'); 
					}
					$data['p_department_id'] = $pid;
				}

			}


			// 清空下级部门
			if( $childrenEmpty ) {

				$hasRecord = $this->model->getChildrensDepartments( $id );

				if( !empty($hasRecord) ) {

					$rs = $this->model->changeChildrenParentDeparment( $id );

					if( !$rs ) {
						throw new \Exception("清空下级部门失败", '003');
					}

				}

			} else {

				if( !is_null($cidArr) ) {

					$cids = [];
					for ($i=0; $i < count($cidArr); $i++) { 

						$cid = (int)$cidArr[$i];
						if( !$cid ) {
							continue;
						}

						$cids[] = $cid;

					}

					if( !empty($cids) ) {

						$hasRecord = $this->model->getChildrensDepartments( $id );

						if( !empty($hasRecord) ) {

							$rs = $this->model->changeChildrenParentDeparment( $id );

							if( !$rs ) {
								throw new \Exception("清空下级部门失败", '003');
							}

						}

						$rs = $this->model->setChildrenParentDeparment( $id, $cids);
						if( !$rs ) {
						 	throw new \Exception("设置下级部门失败", '003'); 
						}

					}
					
				}

			}

			DB::commit();
			
			$return = $this->functionObj->toAppJson($recordId, '001', '创建部门信息成功', true);

		} catch(\Exception $e) {

			if( isset($isBlock) ) {
				DB::rollback();	
			}
			
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);

		} finally {
			//输出
			$this->responseResult($return);
		}

	}
	/* 修改部门信息
	* @author 	xww
	* @return 	json
	*/ 
	public function createDepartment()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 部门对象
			$model = new \VirgoModel\DepartmentModel;

			DB::beginTransaction();

			$isBlock = true;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 5]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和修改数据权限", '070');
			}

			// 必要验证 id
			$this->configValid('required',$this->_configs,['name']);

			// 部门数据
			$name = $this->_configs['name'];

			// // 上级部门
			// $pid = empty($this->_configs['pid']) || (int)$this->_configs['pid']==0 ? null:(int)$this->_configs['pid'];

			$hasRecord = $model->getSameSituationDepartment($name, $pid);

			if( !empty($hasRecord) ) {
				throw new \Exception("已存在该部门", '026');
			}

			$insertData['name'] = $name;
			$insertData['p_department_id'] = $pid;
			$insertData['create_time'] = time();
			$insertData['update_time'] = time();

			/*生成数据*/
			$recordId = $model->create($insertData);

			if(!$recordId) {
				throw new \Exception("生成部门失败", '005');
			}

			$id = $recordId;
			// // 用来表示是否清空上级部门
			// $parentEmpty = empty($this->_configs['parentEmpty'])? 0:1;

			// // 下级部门
			// $cidArr = empty($this->_configs['cids'])? null:explode(",", $this->_configs['cids']);

			// // 用来表示是否清空下级部门
			// $childrenEmpty = empty($this->_configs['childrenEmpty'])? 0:1;

			// 自己更新+上级部门更新
			// 清空上级部门
			// if( $parentEmpty ) {
			// 	$data['p_department_id'] = 0;
			// } else {

			// 	if( !is_null($pid) ) {
			// 		$hasRecord = $this->model->readSingelTon( $pid );
			// 		if( empty($hasRecord) ) {
			// 		 	throw new \Exception("上级部门不存在或已删除", '006'); 
			// 		}
			// 		$data['p_department_id'] = $pid;
			// 	}

			// }


			// // 清空下级部门
			// if( $childrenEmpty ) {

			// 	$hasRecord = $this->model->getChildrensDepartments( $id );

			// 	if( !empty($hasRecord) ) {

			// 		$rs = $this->model->changeChildrenParentDeparment( $id );

			// 		if( !$rs ) {
			// 			throw new \Exception("清空下级部门失败", '003');
			// 		}

			// 	}

			// } else {

			// 	if( !is_null($cidArr) ) {

			// 		$cids = [];
			// 		for ($i=0; $i < count($cidArr); $i++) { 

			// 			$cid = (int)$cidArr[$i];
			// 			if( !$cid ) {
			// 				continue;
			// 			}

			// 			$cids[] = $cid;

			// 		}

			// 		if( !empty($cids) ) {

			// 			$hasRecord = $this->model->getChildrensDepartments( $id );

			// 			if( !empty($hasRecord) ) {

			// 				$rs = $this->model->changeChildrenParentDeparment( $id );

			// 				if( !$rs ) {
			// 					throw new \Exception("清空下级部门失败", '003');
			// 				}

			// 			}

			// 			$rs = $this->model->setChildrenParentDeparment( $id, $cids);
			// 			if( !$rs ) {
			// 			 	throw new \Exception("设置下级部门失败", '003'); 
			// 			}

			// 		}
					
			// 	}

			// }

			DB::commit();
			
			$return = $this->functionObj->toAppJson($recordId, '001', '创建部门信息成功', true);

		} catch(\Exception $e) {

			if( isset($isBlock) ) {
				DB::rollback();	
			}
			
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);

		} finally {
			//输出
			$this->responseResult($return);
		}

	}

}