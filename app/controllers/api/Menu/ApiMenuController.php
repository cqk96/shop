<?php
namespace VirgoApi\Menu;
class ApiMenuController extends \VirgoApi\ApiBaseController
{

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
	* @SWG\Post(path="/api/v1/menu/create", tags={"Menu"}, 
	*  summary="后台增加菜单",
	*  description="要有登录权限和增加权限的用户才能调用次接口 要传递令牌，账号，菜单名称，跳转地址[, 父级菜单id]",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="菜单名称"),
	*  @SWG\Parameter(name="url", type="string", required=false, in="formData", description="跳转地址"),
	*  @SWG\Parameter(name="parentid", type="integer", required=false, in="formData", description="父级菜单id"),
	*  @SWG\Response(
	*   response=200,
	*   description="获取成功",
	*   examples={ "application/json": { "data": { "id": 34, "name": "测试菜单3", "url": "/test" }, "status": { "code": "001", "message": "增加菜单成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/CreateMenu"
	*   )
	*  )
	* )
	* 
	* 增加后台菜单 
	* 先根据用户账号 令牌获取用户，然后获取用户的角色权限  如果没有权限返回失败
	* @author 	xww
	* @return 	json
	*/
	public function create()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			//验证
			$this->configValid('required',$this->_configs,['name']);

			$name = $this->_configs['name'];
			$url = empty($this->_configs['url'])? '':$this->_configs['url'];
			$parentid = empty($this->_configs['parentid'])? 0:$this->_configs['parentid'];

			$uid = $user[0]['id'];

			// 用户对象
			$userModel = new \VirgoModel\UserModel;

			// 后台逻辑基类对象
			$baseModel = new \VirgoModel\BaseModel;

			// 菜单对象
			$model = new \VirgoModel\SysMenuModel;

			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1,2]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和增加数据权限", '070');
			}

			$level = 1;
			if($parentid!=0) {
				$parentData = $model->readSingleTon( $parentid );
				if(!empty($parentData)) {
					$level = $parentData['level']+1;
				}
			}

			$dbParam = $GLOBALS['database_config']['database'];
			$tableParam = $GLOBALS['database_config']['prefix'] . "menus";

			$insertData['name'] = $name;
			$insertData['url'] = $url;
			$insertData['order'] = $baseModel->getNextIncrement_ver_2($dbParam, $tableParam);
			$insertData['show'] = 1;
			$insertData['level'] = $level;
			$insertData['parentid'] = $parentid;
			$insertData['created_at'] = time();
			$insertData['updated_at'] = time();
			$insertData['status'] = 0;

			$rs = $model->doCreate($insertData);

			if( !$rs ) {
				throw new \Exception("新增菜单失败", '005');
			}

			$data = $model->readSingleTon( $rs );

			if( empty($data) ) {
				$data = null;
			} else {
				unset($data['order']);
				unset($data['show']);
				unset($data['parentid']);
				unset($data['created_at']);
				unset($data['updated_at']);
				unset($data['status']);
			}

			$return = $this->functionObj->toAppJson($data, '001', '增加菜单成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/menu/read", tags={"Menu"}, 
	*  summary="获取后台某个菜单详情",
	*  description="根据传入的菜单id获取菜单详情，传入令牌和账号鉴权",
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="菜单id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": { "id": 1, "name": "文章管理", "url": "", "show": 1, "parentid": 0, "updated_at": "2016-08-03 11:26:32" }, "status": { "code": "001", "message": "获取菜单成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/InfoMenu",
	*   )
	*  )
	* )
	*
	* 查看菜单详情
	* @author 	xww
	* @return 	json
	*/
	public function read()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			//验证
			$this->configValid('required',$this->_configs,['id']);

			$uid = $user[0]['id'];

			$id = $this->_configs['id'];

			// 用户对象
			$userModel = new \VirgoModel\UserModel;

			// 菜单对象
			$model = new \VirgoModel\SysMenuModel;

			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1,4]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和查看数据权限", '070');
			}

			$data = $model->readSingleTon( $id );

			if( empty($data) ) {
				$data = null;
			} else {

				$data = $data->toArray();

				unset($data['order']);
				unset($data['created_at']);
				unset($data['status']);
				unset($data['level']);

				$updateObj = date_create($data['updated_at']);
				$updateStr = $updateObj->format("Y-m-d H:i:s");
				unset($data['updated_at']);
				$data['updated_at'] = strtotime($updateStr);
			}

			$return = $this->functionObj->toAppJson($data, '001', '获取菜单详情成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/menu/update", tags={"Menu"}, 
	*  summary="更新菜单",
	*  description="用户鉴权后 传入菜单id,和其他表单数据进行更新",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="菜单id"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="菜单名称"),
	*  @SWG\Parameter(name="url", type="string", required=false, in="formData", description="菜单跳转地址"),
	*  @SWG\Parameter(name="show", type="integer", required=false, in="formData", description="是否显示 0否1是"),
	*  @SWG\Parameter(name="parentid", type="integer", required=false, in="formData", description="菜单父级id 顶级菜单可以不传"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": { "id": 33, "name": "测试菜单的子集菜单-1", "url": "", "show": 1, "parentid": 32, "updated_at": 1530848886 }, "status": { "code": "001", "message": "更新菜单成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/InfoMenu"
	*   )
	*  )
	* )
	* 更新菜单
	* @author 	xww
	* @return 	void
	*/
	public function update()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 菜单对象
			$model = new \VirgoModel\SysMenuModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1,5]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和修改数据权限", '070');
			}


			//验证
			$this->configValid('required',$this->_configs,['id', 'name']);

			$id = $this->_configs['id'];
			$name = $this->_configs['name'];
			$url = empty($this->_configs['url'])? '':$this->_configs['url'];
			$show = empty($this->_configs['show'])? 0:1;
			$parentid = empty($this->_configs['parentid']) || (int)$this->_configs['parentid']<=0? 0:(int)$this->_configs['parentid'];

			$data = $model->readSingleTon( $id );
			if( empty($data) ) {
				throw new \Exception("没有符合条件的数据", '006');
			}

			$level = 1;
			if($parentid!=0) {
				$parentData = $model->readSingleTon( $parentid );
				if(!empty($parentData)) {
					$level = $parentData['level']+1;
				}
			}

			$updateData['name'] = $name;
			$updateData['url'] = $url;
			$updateData['show'] = $show;
			$updateData['level'] = $level;
			$updateData['parentid'] = $parentid;
			$updateData['updated_at'] = time();

			$rs = $model->partUpdate( $id, $updateData);

			if( !$rs ) {
				throw new \Exception("更新菜单操作失败", '003');	
			}

			unset($data);
			$data = $model->readSingleTon( $id )->toArray();

			unset($data['order']);
			unset($data['created_at']);
			unset($data['status']);
			unset($data['level']);

			$updateObj = date_create($data['updated_at']);
			$updateStr = $updateObj->format("Y-m-d H:i:s");
			unset($data['updated_at']);
			$data['updated_at'] = strtotime($updateStr);

			$return = $this->functionObj->toAppJson($data, '001', '更新菜单成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/menu/parentLists", tags={"Menu"}, 
	*  summary="获取菜单列表",
	*  description="用户鉴权后 通过是否传入菜单id来获取菜单列表，由于菜单修改时如果选择父级菜单不能选比自己等级低的菜单",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=false, in="query", description="修改时 传入菜单id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "MenuList", "status": { "code": "001", "message": "获取菜单成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/MenuList",
	*   )
	*  )
	* )
	* 获取可以变更的父级菜单同别与上级菜单列表
	* 如果传入id 则获取修改的
	* @author 	xww
	* @return 	json
	*/
	public function parentLists()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			$id = empty($this->_configs['id']) || (int)$this->_configs['id']<=0? null:(int)$this->_configs['id'];

			// 用户对象
			$userModel = new \VirgoModel\UserModel;

			// 菜单对象
			$model = new \VirgoModel\SysMenuModel;

			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1,4]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和查看数据权限", '070');
			}

			$data = $model->getLevelMenu( $id );

			$return = $this->functionObj->toAppJson($data, '001', '获取菜单成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/menu/delete", tags={"Menu"}, 
	*  summary="菜单删除",
	*  description="鉴权后 通过用户传入的菜单ids字符串 以,分隔每个id 进行软删",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="ids", type="string", required=false, in="formData", description="要删除的菜单id以,分割的字符串"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": null, "status": { "code": "001", "message": "删除菜单成功", "success": true } } }
	*  )
	* )
	* 
	* 进行菜单删除
	* 鉴权后 通过用户传入的菜单ids 以,分隔 进行软删
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

			// 菜单对象
			$model = new \VirgoModel\SysMenuModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1,3]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和删除数据权限", '070');
			}

			//验证
			$this->configValid('required',$this->_configs,['ids']);

			$idsArr = explode(",", $this->_configs['ids'] );

			$ids = [];
			for ($i=0; $i < count($idsArr); $i++) { 
				
				$singleId = (int)$idsArr[$i];

				if( empty($singleId) ) {
					continue;
				}

				$ids[] = $singleId;

			}

			if( empty($ids) ) {
				throw new \Exception("Wrong Param ids", '014');
			}

			$updateData['status'] = 1;
			$updateData['updated_at'] = time();

			$rs = $model->multipartPartUpdate($ids, $updateData);

			if( !$rs ) {
				throw new \Exception("删除菜单失败", '003');
			}

			/*内部删除子菜单*/
			$model->doDeleteRElMenu( $ids );

			$return = $this->functionObj->toAppJson(null, '001', '删除菜单成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/menu/lists", tags={"Menu"}, 
	*  summary="获取菜单管理 菜单列表 只获取一级菜单",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="name", type="string", required=false, in="query", description="菜单名称"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "MenuListsObj", "code": "001", "message": "获取菜单列表成功", "totalCount": 3 } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/MenuListsObj"
	*   )
	*  )
	* )
	* 获取菜单管理列表的菜单
	* 需要有数据列表，总共页数，当前页数
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

			// 菜单对象
			$model = new \VirgoModel\SysMenuModel;

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

			$pageObj = $model->getParentListsObject($skip, $size, $name);

			$data = [];

			$data = empty($pageObj->data)? null:$pageObj->data;
			// $data['totalPage'] = intval( ceil($pageObj->totalCount/$pageObj->size) );
			// $data['currentPage'] = intval( $pageObj->current_page );
			$totalCount = $pageObj->totalCount;

			$return = $this->functionObj->toLayuiJson($data, '001', '获取菜单列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/menu/all/create", tags={"Menu"}, 
	*  summary="一次性建立所有菜单",
	*  description="鉴权后 传入约定json字符串",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="menus", type="string", required=true, in="formData", description="含有name,url,children的单个对象组成的 数组对象json字符串"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": null, "status": { "code": "001", "message": "创建全部菜单表成功", "success": true } } }
	*  )
	* )
	* 
	* 一次性建立所有菜单
	* @author 	xww
	* @return 	json
	*/
	public function allCreate()
	{
		
		try{
			
			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 用户对象
			$userModel = new \VirgoModel\UserModel;

			// 后台逻辑基类对象
			$baseModel = new \VirgoModel\BaseModel;

			// 菜单对象
			$model = new \VirgoModel\SysMenuModel;

			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1,2]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和增加数据权限", '070');
			}

			//验证
			$this->configValid('required',$this->_configs,['menus']);

			$this->_configs['menus'] = html_entity_decode($this->_configs['menus']);
			$menusArr = json_decode( $this->_configs['menus'], true);

			if( !$menusArr ) {
				throw new \Exception("menus is not valid json string", '014');
			}

			$rs = $model->createAll( $menusArr );

			if( !$rs ){
				throw new \Exception("创建全部菜单失败", '005');
			}

			$return = $this->functionObj->toAppJson(null, '001', '创建全部菜单表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/menu/detail", tags={"Menu"}, 
	*  summary="获取后台一级菜单详情",
	*  description="根据传入的菜单id获取菜单详情，传入令牌和账号鉴权 包括有子菜单的嵌套",
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="菜单id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "MenuChildrenDetail", "status": { "code": "001", "message": "获取菜单成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/MenuChildrenDetail",
	*   )
	*  )
	* )
	*
	* 获取菜单详情 包括子菜单
	* @author 	xww
	* @return 	json
	*/
	public function detail()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			//验证
			$this->configValid('required',$this->_configs,['id']);

			$uid = $user[0]['id'];

			$id = $this->_configs['id'];

			// 用户对象
			$userModel = new \VirgoModel\UserModel;

			// 菜单对象
			$model = new \VirgoModel\SysMenuModel;

			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1,4]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和查看数据权限", '070');
			}

			$data = $model->readSingleTon( $id );

			if( empty($data) ) {
				$data = null;
			} else {

				$menus = $model->getMenuDetailWithChildren();

				$temp = null;
				for ($i=0; $i < count($menus); $i++) { 
					if( $menus[$i]['id']==$data['id']) {
						$temp = $menus[$i];
					}
				}

				$data = $temp;

				// $data = $data->toArray();

				// unset($data['order']);
				// unset($data['created_at']);
				// unset($data['status']);
				// unset($data['level']);

				// $updateObj = date_create($data['updated_at']);
				// $updateStr = $updateObj->format("Y-m-d H:i:s");
				// unset($data['updated_at']);
				// $data['updated_at'] = strtotime($updateStr);
			}

			$return = $this->functionObj->toAppJson($data, '001', '获取菜单详情成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}
