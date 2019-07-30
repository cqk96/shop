<?php
namespace VirgoApi\User;
use Illuminate\Database\Capsule\Manager as DB;
class ApiUserController extends \VirgoApi\ApiBaseController
{

	/**
	* api参数数组
	* @var array
	*/
	private $_configs;

	public function __construct()
	{
		$this->userObj = new \VirgoModel\UserModel;
		$this->_configs = parent::change();
		$this->functionObj = new \VirgoUtil\Functions;
	}

	/**
	* 查询用户
	* @author 	xww
	* @return 	void
	*/ 
	public function search()
	{
		
		try{

			//验证 
			$this->configValid('required',$this->_configs,['search']);		

			$data = $this->userObj->searchUser($this->_configs['search']);

			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', "查询成功", true);
			
		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 后台用户登录
	* @SWG\Get(path="/api/v1/user/backLogin", tags={"User"}, 
	*  summary="后台用户登录",
	*  description="通过传入手机号, 密码 进行用户登录, 登录后会改变token",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="username", description="登录账号", type="string", required=true, in="query"),
	*  @SWG\Parameter(name="password", description="密码", type="string", required=true, in="query"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": { "id": 1, "user_login": "18758037930", "access_token": "99c38b4d1625a5890ad79643c90d8bfb64cddd28", "avatar": "/images/default-avatar.png", "phone": null, "name": null, "nationality": null, "ethnicity": null, "political": 0, "university": null, "major": null, "education": 0, "address": null, "nickname": "清风明月_TzbP", "gender": 3, "age": 3, "introduce": null, "birthday": null, "nativePlace": null, "joinTime": null, "workExperience": null, "workingLifeTime": "0", "createTime": 1530252596 }, "status": { "code": "001", "message": "获取用户成功", "success": true } }},
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/User"
	*   )
	*  )
	* )
	*/
	public function backLogin()
	{
		
		try{

			$this->configValid('required',$this->_configs,['username', 'password']);

			$password = "nciou".md5($this->_configs['password'])."dijdm";
			$password = get_magic_quotes_gpc()? $password:addslashes($password);
			$md5Password = md5($this->_configs['password']);
			$md5Password = get_magic_quotes_gpc()? $md5Password:addslashes($md5Password);

			//获取用户
			$user = \EloquentModel\User::where("user_login", '=', $this->_configs['username'])
										->whereIn("password", [$password, $md5Password])
										->where("is_deleted", '=', 0)
										->take(1)


										->get()
										->toArray();

			/*用户不存在*/
			if( empty($user) ){

				$has_account = \EloquentModel\User::where("user_login", '=', $this->_configs['username'])
										->where("is_deleted", '=', 0)
										->take(1)
										->count();

				/*注册过账号的*/						
				if($has_account){
					throw new \Exception('密码错误', '046');
				} else {
					throw new \Exception('用户不存在', '042');
				}

			}

			/**
			* 鉴权
			*/
			// 用户对象
			$userModel = new \VirgoModel\UserModel;

			$uid = $user[0]['id'];

			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限", '070');
			}

			/**
			* 更新token
			*/
			/*更新用户token*/
			$data['access_token'] = $this->getToken();
			$data['token_expire_time'] = time()+86400*30;

			$rs = \EloquentModel\User::where('id', '=', $user[0]['id'])->update($data);
			unset($data);

			if( !$rs ) {
				throw new \Exception("用户更新失败", '003');
			}


			// 重新获取用户
			$uid = $user[0]['id'];
			unset($user);
			$user  = \EloquentModel\User::find($uid)->toArray();

			$user['nativePlace'] = $user['native_place'];
			$user['joinTime'] = $user['join_time'];
			$user['workExperience'] = $user['work_experience'];
			$user['workingLifeTime'] = $user['working_life_time'];
			$user['createTime'] = $user['create_time'];

			// 获取用户角色
			// $roleToUserModel = new \VirgoModel\RoleToUserModel;
			// $rids = $userModel->getUserIds( $uid );
			// $rids = empty($rids)? null:$rids;
			// $user['roles'] = $rids;
			$roleToUserModel = new \VirgoModel\RoleToUserModel;
			$rids=$userModel->getUserIds( $uid );
			
		if(in_array(7,$rids)){
			$user['youhuashi'] = $uid;
		}else{
			$user['youhuashi'] = null;
		}
			
			// 获取用户部门
			$roleToDepartmentModel = new \VirgoModel\DepartmentModel;
			$departIds = $roleToDepartmentModel->getUserDepartmentId( $uid );
			$departIds = empty($departIds)? null:$departIds;
			$user['departmentIds'] = $departIds;

			unset($user['password']);
			unset($user['is_deleted']);
			// unset($user['id']);
			unset($user['token_expire_time']);
			unset($user['native_place']);
			unset($user['join_time']);
			unset($user['work_experience']);
			unset($user['working_life_time']);
			unset($user['create_time']);
			unset($user['update_time']);

			unset($user['wechat_openid']);
			unset($user['sina_openid']);
			unset($user['qq_openid']);

			$return = $this->functionObj->toAppJson($user, '001', '用户登录成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/user/getuserlists", tags={"User"}, 
	*  summary="查看用户列表",
	*  description="用户鉴权后 列出用户",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="string", required=true, in="query", description="page"),
	*  @SWG\Parameter(name="size", type="string", required=true, in="query", description="size"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "获取用户列表成功", "success": true } } }
	*  )
	* )
	* 创建片区
	* @author 	xww
	* @return 	json
	*/
	public function getuserlists()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs, 1);

			$uid = $user[0]['id'];

			// 实例化对象--用户对象
			$userModel = new \VirgoModel\UserModel;

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

			$params['name'] = $name;
			$params['skip'] = $skip;
			$params['size'] = $size;
			

			$pageObj = $userModel->getListsObject( $params);

			$data = [];

			$data = empty($pageObj->data)? null:$pageObj->data;
			 // $data['totalPage'] = intval( ceil($pageObj->totalCount/$pageObj->size) );
			// $data['currentPage'] = intval( $pageObj->current_page );
			$totalCount = $pageObj->totalCount;

			$return = $this->functionObj->toLayuiJson($data, '001', '获取用户列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}

/**
 * 后台新建用户
 * @SWG\Post(path="/api/v1/user/create", tags={"User"}, 
 *  summary="后台新建用户",
 *  description="鉴权后 必须传入账号,姓名 其他参数可选 账号存在时 新建用户失败",
 *  produces={"application/json"},
 *  @SWG\Parameter(name="username", type="string", required=true, in="formData", description="用户账号"),
 *  @SWG\Parameter(name="password", type="string", required=true, in="formData", description="密码"),	
 *  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
 *  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
 *  @SWG\Parameter(name="departmentId", type="string", required=true, in="formData", description="部门id"),
 *  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="姓名"),
 *  @SWG\Response(
 *   response=200,
 *   description="操作成功",
 *   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "添加用户成功", "success": true } }}
 *  )
 * )
 * @author 	xww
 * @return 	json
 */

	public function create()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			/*必须传入*/
			$this->configValid('required',$this->_configs,['username', 'password', 'name']);

			DB::beginTransaction();

			$isBlock = true;

			$uid = $user[0]['id'];

			// 实例化对象--用户对象
			$userModel = new \VirgoModel\UserModel;

			// 实例化对象--用户角色对象
			$roleUserModel = new \VirgoModel\RoleToUserModel;

			// 实例化对象--用户部门对象
			$departmentUserModel = new \VirgoModel\DepartmentRelUserModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 2]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和增加数据权限", '070');
			}

			$insertData = $this->_configs;
		
		$departmentId = empty($this->_configs['departmentId'])? '':$this->_configs['departmentId'];
			 // $departmentId = $insertData['departmentId'];
			

			
			unset($insertData['/api/v1/user/create']);
			unset($insertData['user_login']);
			unset($insertData['access_token']);
			unset($insertData['departmentId']);

			// 判断账号是否存在
			$record = $userModel->getRecordByAccount( $insertData['username'] );

			if( !empty($record) ) {
				throw new \Exception("账号已存在", "026");
			}
var_dump($insertData);
die;
			// 允许传入字段
			$allows = [
				'username', 'roleid','password','password_','access_token','role_id',
				'avatar', 'phone', 'name', 'departmentId','departments','nationality', 'ethnicity',
				'native_place', 'political', 'join_time', 'university', 'major',
				'education', 'address', 'work_experience', 'working_life_time', 'nickname',
				'gender', 'age', 'introduce', 'birthday', 'roles',
				'departments','nationality_number', 'manager_id', 'job'
			];

			// var_dump( $insertData );
			// die;
			foreach ($insertData as $key => $value) {
				if( !in_array($key, $allows) ) {
					throw new \Exception("含有不允许的参数:" . $key, '014');
					break;
				}
			}
			unset($value);

			$insertData['avatar'] = empty($insertData['avatar'])? "/images/default-avatar.png":$insertData['avatar'];
			$insertData['nickname'] = empty($insertData['nickname'])? $this->functionObj->getNickName('清风明月_'):$insertData['nickname'];

			if( !empty($insertData['birthday']) && strtotime($insertData['birthday'] . " 00:00:00") ) {
				$insertData['birthday'] = strtotime($insertData['birthday'] . " 00:00:00");
			}

			/*规则处理*/
			$pwd = $insertData['password'];
			$insertData['password'] = "nciou".md5( $pwd )."dijdm";
			$insertData['user_login'] = $insertData['username'];
			$insertData['password_'] =$pwd;
			$insertData['create_time'] = time();
			$insertData['update_time'] = time();
			$roleid=$insertData['roleid'];
			// $departments = $insertData['departments'];
			// 抛弃不必要的属性
			unset($insertData['username']);
			unset($insertData['departments']);

			$userId = $userModel->create( $insertData );

			if( !$userId ) {
				throw new \Exception("添加用户失败", '005');
			}

			/*特殊处理--用户角色*/
			$temp = [];
			$temp['role_id'] = $roleid;
			$temp['user_id'] = $userId;

			$rs = $roleUserModel->singleCreate($temp);
			unset( $temp );

			if( !$rs ) {
				throw new \Exception("添加用户角色失败", '005');			
			} 

			if( isset($departmentId) ) {

				$departmentIds = explode(",", $departmentId);
				$data = [];
				for ($i=0; $i < count($departmentIds); $i++) { 

					$departmentId = (int)$departmentIds[$i];
					if( !$departmentId ) {
						continue;
					}

					$temp = [];
					$temp['department_id'] = $departmentId;
					$temp['user_id'] = $userId;
					$temp['is_deleted'] = 0;
					$temp['create_time'] = time();
					$temp['update_time'] = time();
					$data[] = $temp;

				}

			}
			

			


			 $rs = $departmentUserModel->multiCreate($data);

			if( !$rs ) {
				throw new \Exception("添加用户部门失败", '005');			
			} 

			DB::commit();

			$return = $this->functionObj->toAppJson(null, '001', '添加用户成功', true);

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
	* @SWG\Post(path="/api/v1/user/delete", tags={"User"}, 
	*  summary="删除用户",
	*  description="该接口可以删除单个用户或多个用户 用户鉴权后 通过传入当前用户id进行删除, 由于跟三方进行对接 无法支持多个用户删除, 如果用户存在于环信中 且环信删除失败则用户存在失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="要删除的用户id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": null, "status": { "code": "001", "message": "删除用户成功", "success": true } } }
	*  )
	* )
	* 删除用户
	*/
	public function delete()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$model = new \VirgoModel\UserModel;
$DepartmentRelUserModel = new \VirgoModel\DepartmentRelUserModel;
			// 实例化对象--环信对象
			$huanXinUtilObj = new \VirgoUtil\HuanXinUtil;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $model->hasBackCreateOperatePrivilige($uid, [1, 3]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和删除数据权限", '070');
			}

			// 必要验证 id
			$this->configValid('required',$this->_configs,['id']);

			DB::beginTransaction();

			$isBlock = true;

			$id = $this->_configs['id'];

			// $idsArr = $this->_configs['id'];

			// $ids = [];
			// for ($i=0; $i < count($idsArr); $i++) { 
			// 	$singleId = (int)$idsArr[$i];

			// 	if( empty($singleId) ) {
			// 		continue;
			// 	}

			// 	$ids[] = $singleId;
			// }

			// if( empty($ids) ) {
			// 	throw new \Exception("Wrong Param ids", '014');
			// }

			// 判断是否有此用户
			$record = $model->readSingleTon( $id );
			if( empty($record) ) { 
				throw new \Exception("用户不存在或已删除", '006'); 
			}

			$updateData['is_deleted'] = 1;
			$updateData['update_time'] = time();

			// 进行删除操作
			if ($id==1) {
				throw new \Exception("删除用户失败", '003');
			}else{
			$rs = $model->partUpdate( $id , $updateData);
			if( !$rs ) {
				throw new \Exception("删除用户失败", '003');
			}
				}
			$deleteData['is_deleted'] = 1;
			$deleteData['update_time'] = time();
			$rsa = $DepartmentRelUserModel->departmentrelUpdate( $id , $deleteData);
			if( !$rsa ) {
				throw new \Exception("删除用户部门失败", '003');
			}

			/*环信删除用户模块*/
			$huanXinConfigs = $GLOBALS['globalConfigs']['huanXin'];
			$huanXinUtilObj->setProperties($huanXinConfigs);

			// get 环信token
			$token = $huanXinUtilObj->getToken();

			// 获取用户单个
				// 删除用户
			$rs = $huanXinUtilObj->getUser($record['user_login'], $token);

			//解析
			$headersArr = explode("\r\n", $rs['header']);
			$LineOneArr = explode(" ", $headersArr[0]);

			if($LineOneArr[1]!='200' && $LineOneArr[1]!="404"){
				throw new \Exception("环信获取用户失败, code: " . $LineOneArr[1], '095');
			}

			if($LineOneArr[1]=="200") {
				// 删除用户
				$rs = $huanXinUtilObj->deleteUser($record['user_login'], $token);
				$headersArr = explode("\r\n", $rs['header']);
				$LineOneArr = explode(" ", $headersArr[0]);
				if($LineOneArr[1]!='200'){
					throw new \Exception("环信删除用户失败, code: " . $LineOneArr[1], '095');
				}
			}
			/*环信删除用户模块--end*/

			DB::commit();

			$return = $this->functionObj->toAppJson(null, '001', '删除用户成功', true);			

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
	* @SWG\Get(path="/api/v1/user/hasAccount", tags={"User"}, 
	*  summary="判断账号是否存在",
	*  description="用户鉴权后 通过传入账号判断是否存在该账号 返回Boolean true为有false为没有",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="username", type="string", required=true, in="query", description="传入的账号"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": false, "status": { "code": "001", "message": "获取账号信息成功", "success": true } } }
	*  )
	* )
	* 判断账号是否存在
	*/
	public function hasAccount()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$model = new \VirgoModel\UserModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $model->hasBackCreateOperatePrivilige($uid, [1, 4]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和查看数据权限", '070');
			}

			// 必要验证 id
			$this->configValid('required',$this->_configs,['username']);

			$username = $this->_configs['username'];

			// 判断账号是否存在
			$data = $model->getRecordByAccount( $username );
			$result = !empty($data)? true:false;

			$return = $this->functionObj->toAppJson($result, '001', '获取账号信息成功', true);			

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/user/info", tags={"User"}, 
	*  summary="查询用户详情",
	*  description="用户鉴权后 通过传入用户id返回用户信息 并将部门树，角色列表返回 其中有字段标明是否拥有对应的部门或是角色",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="用户id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "UserInfo", "status": { "code": "001", "message": "获取用户详情成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/UserInfo"
	*   )
	*  )
	* )
	* 查询用户详情
	* @author 	xww
	* @return 	json
	*/
	public function info()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$model = new \VirgoModel\UserModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $model->hasBackCreateOperatePrivilige($uid, [1, 4]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和查看数据权限", '070');
			}

			// 必要验证 id
			$this->configValid('required',$this->_configs,['id']);

			$id = $this->_configs['id'];

			// 判断是否有此用户
			$hasRecord = $model->readSingleTon( $id );
			if( empty($hasRecord) ) { 
				throw new \Exception("用户不存在或已删除", '006'); 
			}

			$data = $model->getUserInfo( $id );

			$return = $this->functionObj->toAppJson($data, '001', '获取用户详情成功', true);			

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {

			//输出
			$this->responseResult($return);

		}

	}

	/**
	* @SWG\Post(path="/api/v1/user/back/update", tags={"User"}, 
	*  summary="后台修改用户信息",
	*  description="鉴权后 必须传入记录id, 手机号，姓名 其他参数可选",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="用户id"),
	*  @SWG\Parameter(name="phone", type="string", required=true, in="formData", description="用户手机号"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="用户姓名"),
	*  @SWG\Parameter(name="avatar", type="string", required=false, in="formData", description="头像 相对地址"),
	*  @SWG\Parameter(name="nationality", type="string", required=false, in="formData", description="国籍"),
	*  @SWG\Parameter(name="nationality_number", type="integer", required=false, in="formData", description="国籍--整型1中国2柬埔寨"),
	*  @SWG\Parameter(name="ethnicity", type="string", required=false, in="formData", description="民族"),
	*  @SWG\Parameter(name="native_place", type="string", required=false, in="formData", description="籍贯"),
	*  @SWG\Parameter(name="political", type="integer", required=false, in="formData", description="政治面貌默认0 群众1团员2预备党员3党员"),
	*  @SWG\Parameter(name="join_time", type="string", required=false, in="formData", description="入党(团)时间 群众为空 e.g 2018-07-13"),
	*  @SWG\Parameter(name="university", type="string", required=false, in="formData", description="毕业院校"),
	*  @SWG\Parameter(name="major", type="string", required=false, in="formData", description="所学专业"),
	*  @SWG\Parameter(name="education", type="integer", required=false, in="formData", description="学历默认0无1博士2硕士3本科4专科5高中6初中"),
	*  @SWG\Parameter(name="address", type="string", required=false, in="formData", description="家庭住址"),
	*  @SWG\Parameter(name="work_experience", type="string", required=false, in="formData", description="工作经历"),
	*  @SWG\Parameter(name="working_life_time", type="integer", required=false, in="formData", description="工作年限"),
	*  @SWG\Parameter(name="nickname", type="string", required=false, in="formData", description="昵称"),
	*  @SWG\Parameter(name="gender", type="integer", required=false, in="formData", description="性别1男2女3保密", default=3),
	*  @SWG\Parameter(name="age", type="integer", required=false, in="formData", description="年龄"),
	*  @SWG\Parameter(name="introduce", type="string", required=false, in="formData", description="介绍"),
	*  @SWG\Parameter(name="birthday", type="string", required=false, in="formData", description="生成年月 群众为空 e.g 2018-07-13"),
	*  @SWG\Parameter(name="manager_id", type="integer", required=false, in="formData", description="主管 id"),
	*  @SWG\Parameter(name="job", type="string", required=false, in="formData", description="职务"),
	*  @SWG\Parameter(name="roles", type="string", required=false, in="formData", description="角色id 以,拼接的字符串"),
	*  @SWG\Parameter(name="departments", type="string", required=false, in="formData", description="部门id 以,拼接的字符串"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "修改用户成功", "success": true } }}
	*  )
	* )
	* 修改用户信息
	* @author 	xww
	* @return 	json
	*/
	public function update()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象--用户对象
			$userModel = new \VirgoModel\UserModel;

			// 实例化对象--用户角色对象
			$roleUserModel = new \VirgoModel\RoleToUserModel;

			// 实例化对象--用户部门对象
			$departmentUserModel = new \VirgoModel\DepartmentRelUserModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 5]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和修改数据权限", '070');
			}

			/*必须传入*/
			$this->configValid('required',$this->_configs,['id', 'phone', 'name']);

			DB::beginTransaction();

			$isBlock = true;

			$updateData = $this->_configs;
			$id = $updateData['id'];

			unset($updateData['/api/v1/user/back/update']);
			unset($updateData['user_login']);
			unset($updateData['access_token']);
			unset($updateData['id']);

			// 允许传入字段
			$allows = [
				'avatar', 'phone', 'name', 'nationality', 'ethnicity',
				'native_place', 'political', 'join_time', 'university', 'major',
				'education', 'address', 'work_experience', 'working_life_time', 'nickname',
				'gender', 'age', 'introduce', 'birthday', 'roles',
				'departments', 'nationality_number', 'manager_id', 'job'
			];

			foreach ($updateData as $key => $value) {
				if( !in_array($key, $allows) ) {
					throw new \Exception("含有不允许的参数:" . $key, '014');
					break;
				}
			}
			unset($value);
			
			if( !empty($updateData['roles']) ) {
				$roles = $updateData['roles'];
				unset($updateData['roles']);
			}

			if( !empty($updateData['departments']) ) {
				$departments = $updateData['departments'];
				unset($updateData['departments']);
			}

			/*特殊格式变化*/
			if( !empty($updateData['join_time']) && strtotime($updateData['join_time'] . " 00:00:00") ) {
				$updateData['join_time'] = strtotime($updateData['join_time'] . " 00:00:00");
			}

			if( !empty($updateData['birthday']) && strtotime($updateData['birthday'] . " 00:00:00") ) {
				$updateData['birthday'] = strtotime($updateData['birthday'] . " 00:00:00");
			}

			$updateData['update_time'] = time();

			// 更新
			$rs = $userModel->partUpdate( $id, $updateData );

			if( !$rs ) {
				throw new \Exception("更新用户失败", '003');
			}

			/*特殊处理--用户角色*/
			if( isset($roles) ) {

				// 先判断是否有角色 有就进行删除
				$hasRecord = $roleUserModel->getUserRoleIds( $id );
				if( !empty($hasRecord) ) {
					$rs = $roleUserModel->removeUserRole($id);
					if( !$rs ) {
						throw new \Exception("删除用户角色失败", "012");
					}
				}

				$roleIds = explode(",", $roles);
				$data = [];
				for ($i=0; $i < count($roleIds); $i++) { 

					$roleId = (int)$roleIds[$i];
					if( !$roleId ) {
						continue;
					}

					$temp = [];
					$temp['role_id'] = $roleId;
					$temp['user_id'] = $id;

					$data[] = $temp;

				}

				if( !empty($data) ) {
					$rs = $roleUserModel->multiCreate($data);

					if( !$rs ) {
						throw new \Exception("添加用户角色失败", '005');			
					} 

				}

			}

			/*特殊处理--用户部门*/
			if( isset($departments) ) {

				// 先判断是否有部门 有就进行删除
				$hasRecord = $departmentUserModel->hasUserRel( $id );
				if( $hasRecord ) {
					$rs = $departmentUserModel->removeUserDepartment($id);
					if( !$rs ) {
						throw new \Exception("删除用户部门失败", "012");
					}
				}

				$departmentIds = explode(",", $departments);
				$data = [];
				for ($i=0; $i < count($departmentIds); $i++) { 

					$departmentId = (int)$departmentIds[$i];
					if( !$departmentId ) {
						continue;
					}

					$temp = [];
					$temp['department_id'] = $departmentId;
					$temp['user_id'] = $id;
					$temp['is_deleted'] = 0;
					$temp['create_time'] = time();
					$temp['update_time'] = time();

					$data[] = $temp;

				}

				if( !empty($data) ) {
					$rs = $departmentUserModel->multiCreate($data);

					if( !$rs ) {
						throw new \Exception("添加用户部门失败", '005');			
					} 

				}

			}

			DB::commit();

			$return = $this->functionObj->toAppJson(null, '001', '修改用户成功', true);

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
	* @SWG\Post(path="/api/v1/user/self/update", tags={"User"}, 
	*  summary="用户修改自己信息",
	*  description="鉴权后 其他参数可选 如果有不允许的参数存在就会失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="phone", type="string", required=false, in="formData", description="用户手机号"),
	*  @SWG\Parameter(name="name", type="string", required=false, in="formData", description="用户姓名"),
	*  @SWG\Parameter(name="avatar", type="string", required=false, in="formData", description="头像 相对地址"),
	*  @SWG\Parameter(name="nationality", type="string", required=false, in="formData", description="国籍"),
	*  @SWG\Parameter(name="nationality_number", type="integer", required=false, in="formData", description="国籍--整型1中国2柬埔寨"),
	*  @SWG\Parameter(name="ethnicity", type="string", required=false, in="formData", description="民族"),
	*  @SWG\Parameter(name="native_place", type="string", required=false, in="formData", description="籍贯"),
	*  @SWG\Parameter(name="political", type="integer", required=false, in="formData", description="政治面貌默认0 群众1团员2预备党员3党员"),
	*  @SWG\Parameter(name="join_time", type="string", required=false, in="formData", description="入党(团)时间 群众为空 e.g 2018-07-13"),
	*  @SWG\Parameter(name="university", type="string", required=false, in="formData", description="毕业院校"),
	*  @SWG\Parameter(name="major", type="string", required=false, in="formData", description="所学专业"),
	*  @SWG\Parameter(name="education", type="integer", required=false, in="formData", description="学历默认0无1博士2硕士3本科4专科5高中6初中"),
	*  @SWG\Parameter(name="address", type="string", required=false, in="formData", description="家庭住址"),
	*  @SWG\Parameter(name="work_experience", type="string", required=false, in="formData", description="工作经历"),
	*  @SWG\Parameter(name="working_life_time", type="integer", required=false, in="formData", description="工作年限"),
	*  @SWG\Parameter(name="nickname", type="string", required=false, in="formData", description="昵称"),
	*  @SWG\Parameter(name="gender", type="integer", required=false, in="formData", description="性别1男2女3保密", default=3),
	*  @SWG\Parameter(name="age", type="integer", required=false, in="formData", description="年龄"),
	*  @SWG\Parameter(name="introduce", type="string", required=false, in="formData", description="介绍"),
	*  @SWG\Parameter(name="birthday", type="string", required=false, in="formData", description="生成年月 群众为空 e.g 2018-07-13"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "修改用户成功", "success": true } }}
	*  )
	* )
	* 用户更新自己的信息
	* @author 	xww
	* @return 	json
	*/
	public function selfUpdate()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象--用户对象
			$userModel = new \VirgoModel\UserModel;

			$updateData = $this->_configs;
			$id = $uid;

			unset($updateData['/api/v1/user/self/update']);
			unset($updateData['user_login']);
			unset($updateData['access_token']);

			// 允许传入字段
			$allows = [
				'avatar', 'phone', 'name', 'nationality', 'ethnicity',
				'native_place', 'political', 'join_time', 'university', 'major',
				'education', 'address', 'work_experience', 'working_life_time', 'nickname',
				'gender', 'age', 'introduce', 'birthday', 'nationality_number'
			];

			foreach ($updateData as $key => $value) {
				if( !in_array($key, $allows) ) {
					throw new \Exception("含有不允许的参数:" . $key, '014');
					break;
				}
			}
			unset($value);

			/*特殊格式变化*/
			if( !empty($updateData['join_time']) && strtotime($updateData['join_time'] . " 00:00:00") ) {
				$updateData['join_time'] = strtotime($updateData['join_time'] . " 00:00:00");
			}

			if( !empty($updateData['birthday']) && strtotime($updateData['birthday'] . " 00:00:00") ) {
				$updateData['birthday'] = strtotime($updateData['birthday'] . " 00:00:00");
			}

			$updateData['update_time'] = time();

			// 更新
			$rs = $userModel->partUpdate( $id, $updateData );

			if( !$rs ) {
				throw new \Exception("更新用户失败", '003');
			}


			$return = $this->functionObj->toAppJson(null, '001', '修改用户信息成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {

			//输出
			$this->responseResult($return);

		}

	}

	/**
	* 获取即时聊天用户列表--附带搜索用户
	* @SWG\Get(path="/api/v1/user/all/chat/lists", tags={"User"}, 
	*  summary="获取即时聊天用户列表--附带搜索用户",
	*  description="鉴权后 通过传入页数，条数 可选传入搜索姓名 进行用户搜索",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="search", type="string", required=false, in="query", description="搜索姓名"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": "ChatUserLists", "status": { "code": "001", "message": "获取通讯录列表成功", "success": true } }},
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/ChatUserLists")
	*   )
	*  )
	* )
	* @author 	xww
	* @return 	json
	*/
	public function chatLists()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象--用户对象
			$userModel = new \VirgoModel\UserModel;

			//验证
			$this->configValid('required',$this->_configs,['page', 'size']);

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$search = empty($this->_configs['search'])? null:$this->_configs['search'];
			$data = $userModel->getAllChatUserLists($skip, $size, $search);
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取通讯录列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/user/all", tags={"User"}, 
	*  summary="全部用户列表",
	*  description="鉴权 获取所有用户",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "AllUserLists", "status": { "code": "001", "message": "获取全部用户成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/AllUserLists")
	*   )
	*  )
	* )
	* 获取全部用户列表
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
			$model = new \VirgoModel\UserModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $model->hasBackCreateOperatePrivilige($uid, [1, 4]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和查看数据权限", '070');
			}

			$data = $model->getAllUserLists();

			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取全部用户成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/user/read", tags={"User"}, 
	*  summary="app 用户详情",
	*  description="鉴权 通过传入用户id获取 app 用户详情",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="要查看的用户id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "UserRead", "status": { "code": "001", "message": "获取用户详情成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/UserRead"
	*   )
	*  )
	* )
	* 查看用户详情
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
			$model = new \VirgoModel\UserModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $model->hasBackCreateOperatePrivilige($uid, [1, 4]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和查看数据权限", '070');
			}

			// 必要验证 id
			$this->configValid('required',$this->_configs,['id']);

			$id = $this->_configs['id'];

			// 判断是否有此用户
			$hasRecord = $model->readSingleTon( $id );
			if( empty($hasRecord) ) { 
				throw new \Exception("用户不存在或已删除", '006'); 
			}

			$data = $model->readUser( $id );

			$return = $this->functionObj->toAppJson($data, '001', '获取用户详情成功', true);			

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {

			//输出
			$this->responseResult($return);

		}

	}

	/**
	* @SWG\Get(path="/api/v1/user/backInfo", tags={"User"}, 
	*  summary="查询用户详情",
	*  description="用户鉴权后 通过传入用户id返回用户信息 并将部门树，角色列表返回 其中有字段标明是否拥有对应的部门或是角色",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="string", required=true, in="query", description="id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "UserInfo", "status": { "code": "001", "message": "获取用户详情成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/UserInfo"
	*   )
	*  )
	* )
	* 查询用户详情
	* @author 	xww
	* @return 	json
	*/
	public function backInfo()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$model = new \VirgoModel\UserModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $model->hasBackCreateOperatePrivilige($uid, [1, 4]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和查看数据权限", '070');
			}

			$id = $this->_configs['id'];

			// 判断是否有此用户
			$hasRecord = $model->readSingleTon( $id );
			if( empty($hasRecord) ) { 
				throw new \Exception("用户不存在或已删除", '006'); 
			}
			$data = $model->getaaa( $id );
			

			$return = $this->functionObj->toAppJson($data, '001', '获取用户详情成功', true);			

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {

			//输出
			$this->responseResult($return);

		}

	}

	/**
	* @SWG\Post(path="/api/v1/user/backUpdatePWD", tags={"User"}, 
	*  summary="修改密码",
	*  description="传入账号 旧密码 新密码进行密码更新 如果账号不存在或新老密码相同或环信修改失败 则提示失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="olderPWD", type="string", required=true, in="formData", description="旧密码"),
	*  @SWG\Parameter(name="newerPWD", type="string", required=true, in="formData", description="新密码"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": null, "status": { "code": "001", "message": "修改密码成功", "success": true } } }
	*  )
	* )
	*/
	public function backUpdatePWD()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			$user_login = $user[0]['user_login'];

			//验证
			$this->configValid('required',$this->_configs,['olderPWD', 'newerPWD']);

			DB::beginTransaction();

			$isBlock = true;

			$olderPWD = $this->_configs['olderPWD'];

			$olderPWD = "nciou".md5($olderPWD)."dijdm";
			$olderPWD = get_magic_quotes_gpc()? $olderPWD:addslashes($olderPWD);
			
			$newerPWD = "nciou".md5( $this->_configs['newerPWD'] )."dijdm";

			$model = new \VirgoModel\UserModel;

			// 实例化对象--环信对象
			$huanXinUtilObj = new \VirgoUtil\HuanXinUtil;

			$user = \EloquentModel\User::where("user_login", '=', $user_login)
									   ->where("id", $uid)
									   ->where("is_deleted", '=', 0)
									   ->get()
									   ->toArray();

			if( empty($user) ) {
				throw new \Exception("无法查询到该账号", '006');
			}

			$uid = $user[0]['id'];

			if( $user[0]['password']!=$olderPWD ) {
				throw new \Exception("旧密码不正确", '028');
			}

			if( $user[0]['password']==$newerPWD ) {
				throw new \Exception("密码与原密码相同", '056');
			}

			$data['update_time'] = time();
			$data['password'] = $newerPWD;

			$rs = $model->partUpdate($uid, $data);
			
			if( !$rs){
				throw new \Exception("修改密码失败", '003');
			}

			/*环信注册模块*/
			$huanXinConfigs = $GLOBALS['globalConfigs']['huanXin'];
			$huanXinUtilObj->setProperties($huanXinConfigs);

			// get 环信token
			$token = $huanXinUtilObj->getToken();

			// 获取用户单个
			$rs = $huanXinUtilObj->getUser($user_login, $token);

			//解析
			$headersArr = explode("\r\n", $rs['header']);
			$LineOneArr = explode(" ", $headersArr[0]);

			if($LineOneArr[1]=='200'){

				// 更新密码
				$rs = $huanXinUtilObj->updateUserPwd($user_login, $newerPWD, $token);
				$headersArr = explode("\r\n", $rs['header']);
				$LineOneArr = explode(" ", $headersArr[0]);
				if($LineOneArr[1]!='200'){
					throw new \Exception("环信修改用户密码失败, code: " . $LineOneArr[1], '095');
				}
				
			}

			DB::commit();
			$return = $this->functionObj->toAppJson(null, '001', '修改密码成功', true);

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
	* @SWG\Get(path="/api/v1/user/managers/all", tags={"User"}, 
	*  summary="获取用户角色为主管的用户列表",
	*  description="鉴权 获取所有用户",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "AllUserLists", "status": { "code": "001", "message": "获取主管用户列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/AllUserLists")
	*   )
	*  )
	* )
	* 获取用户角色为主管的用户列表
	* @author 	xww
	* @return 	array
	*/
	public function managersAll()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs, 1);

			$uid = $user[0]['id'];

			$userModel = new \VirgoModel\UserModel;

			// 
			$model = new \VirgoModel\RoleToUserModel;

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
			// $this->configValid('required',$this->_configs,['page', 'size']);

			// // 分页
			// $page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			// $size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			// $page -= 1;
			// $skip = $page*$size;

			$data = $model->getSpecifyUserWithTypeId("1106");

			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取主管用户列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 修改用户信息
	* @SWG\Post(path="/api/v1/user/back/userupdate", tags={"User"}, 
	*  summary="修改用户信息",
	*  description="鉴权后 传入账号 密码 部门id 记录id 进行用户信息更新",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="username", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="password", type="string", required=true, in="formData", description=""),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="departmentId", type="string", required=true, in="formData", description=",分隔"),
	*  @SWG\Parameter(name="id", type="string", required=true, in="formData", description="记录id"),
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": null, "status": { "code": "001", "message": "修改用户成功", "success": true } } }
	*  )
	* )
	* @author 	xww
	* @return 	json
	*/
	public function userupdate()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象--用户对象
			$userModel = new \VirgoModel\UserModel;

			// 实例化对象--用户角色对象
			$roleUserModel = new \VirgoModel\RoleToUserModel;

			// 实例化对象--用户部门对象
			$departmentUserModel = new \VirgoModel\DepartmentRelUserModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 5]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和修改数据权限", '070');
			}

			/*必须传入*/
			$this->configValid('required',$this->_configs,['username', 'password_', 'name', 'departmentId', 'id']);

			DB::beginTransaction();

			$isBlock = true;

			$updateData['user_login'] = $this->_configs['username'];
			$updateData['roleid'] = $this->_configs['roleid'];
			$updateData['password_'] = $this->_configs['password_'];
			$updateData['name'] = $this->_configs['name'];
			$updateData['update_time'] = time();
			$pwd =$updateData['password_'];
			$updateData['password'] = "nciou".md5( $pwd )."dijdm";

			$id = $this->_configs['id'];

			$departmentId = $this->_configs['departmentId'];

			// 更新
			$rs = $userModel->partUpdate( $id, $updateData );

			if( !$rs ) {
				throw new \Exception("更新用户失败", '003');
			}

			/*特殊处理--用户角色*/
			if( isset($roles) ) {

				// 先判断是否有角色 有就进行删除
				$hasRecord = $roleUserModel->getUserRoleIds( $id );
				if( !empty($hasRecord) ) {
					$rs = $roleUserModel->removeUserRole($id);
					if( !$rs ) {
						throw new \Exception("删除用户角色失败", "012");
					}
				}

				$roleIds = explode(",", $roles);
				$data = [];
				for ($i=0; $i < count($roleIds); $i++) { 

					$roleId = (int)$roleIds[$i];
					if( !$roleId ) {
						continue;
					}

					$temp = [];
					$temp['role_id'] = $roleId;
					$temp['user_id'] = $id;

					$data[] = $temp;

				}
		// 判断是否有此用户
			$hasRecord = $model->readSingleTon( $id );
			if( empty($hasRecord) ) { 
				throw new \Exception("用户不存在或已删除", '006'); 
			}

				if( !empty($data) ) {
					$rs = $roleUserModel->multiCreate($data);

					if( !$rs ) {
						throw new \Exception("添加用户角色失败", '005');			
					} 

				}

			}

			/*特殊处理--用户部门*/
			if( isset($departmentId) ) {

				// 先判断是否有部门 有就进行删除
				$hasRecord = $departmentUserModel->hasUserRel( $id );
				if( $hasRecord ) {
					$rs = $departmentUserModel->removeUserDepartment($id);
					if( !$rs ) {
						throw new \Exception("删除用户部门失败", "012");
					}
				}

				$departmentIds = explode(",", $departmentId);
				
				$data = [];
				for ($i=0; $i < count($departmentIds); $i++) { 

					$departmentId = (int)$departmentIds[$i];
					if( !$departmentId ) {
						continue;
					}

					$temp = [];
					$temp['department_id'] = $departmentId;
					$temp['user_id'] = $id;
					$temp['is_deleted'] = 0;
					$temp['create_time'] = time();
					$temp['update_time'] = time();

					$data[] = $temp;

				}

				if( !empty($data) ) {
					$rs = $departmentUserModel->multiCreate($data);

					if( !$rs ) {
						throw new \Exception("添加用户部门失败", '005');			
					} 

				}

			}

			DB::commit();

			$return = $this->functionObj->toAppJson(null, '001', '修改用户成功', true);

		} catch(\Exception $e) {

			if( isset($isBlock) ) {
				DB::rollback();
			}

			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	
	  public function 	lists()
			{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs, 1);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;
			// 实例化对象
			$SetmealModel = new \VirgoModel\SetmealModel;
			$codModel = new \VirgoModel\CodModel;
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

			$params['name'] = $name;
			$params['skip'] = $skip;
			$params['size'] = $size;
			

			$pageObj = $userModel->getuserLists($skip, $size);
			// $data = empty($data)? null:$data;

			// $data = [];

			$data = empty($pageObj->data)? []:$pageObj->data;
			// $data['totalPage'] = intval( ceil($pageObj->totalCount/$pageObj->size) );
			// $data['currentPage'] = intval( $pageObj->current_page );
			$totalCount = $pageObj->totalCount;

			$return = $this->functionObj->toLayuiJson($data, '001', '获取人员列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

}