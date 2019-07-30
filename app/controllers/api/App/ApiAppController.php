<?php
namespace VirgoApi\App;
class ApiAppController extends \VirgoApi\ApiBaseController{

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
	* @SWG\Get(path="/api/v1/app/lastest", tags={"Apk"}, 
	*     summary="获取最新安装包信息",
	*     produces={"application/json"},
	*     @SWG\Response(
	*          response=200,
	*          description="操作成功",
	*          @SWG\Schema(
	*              type="object",
	*              ref="#/definitions/ApkInfo"
	*          )
	*     )
	* )
	* 获取最新版安装包信息
	* @author 	xww
	* @return 	json
	*/
	public function lastest()
	{

		try{

			$model = new \VirgoModel\ManageAppModel;

			$data = $model->getLastestInfo();
			$data = empty($data)? null:$data;

			if(!empty($data)) {
				$data['description'] = empty($data['description'])? "":$data['description'];
			}

			$return = $this->functionObj->toAppJson($data, '001', '获取最新版安装包信息成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/apk/lists", tags={"Apk"}, 
	*  summary="获取包管理列表",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "ApkListsObj", "code": "001", "message": "获取包管理列表成功", "totalCount": 3 } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/ApkListsObj"
	*   )
	*  )
	* )
	* 获取包管理列表
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

			// 安卓包管理对象
			$model = new \VirgoModel\ManageAppModel;

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

			$return = $this->functionObj->toLayuiJson($data, '001', '获取包管理列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/apk/create", tags={"Apk"}, 
	*  summary="创建包管理",
	*  description="用户鉴权后 通过传入的应用名、开发版本、用户版本、apk地址来创建包管理版本",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="应用名"),
	*  @SWG\Parameter(name="versionCode", type="integer", required=true, in="formData", description="开发版本"),
	*  @SWG\Parameter(name="versionText", type="string", required=true, in="formData", description="用户版本"),
	*  @SWG\Parameter(name="apkUrl", type="string", required=true, in="formData", description="包地址 通过上传附件接口获得"),
	*  @SWG\Parameter(name="description", type="string", required=false, in="formData", description="描述"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "增加包管理版本成功", "success": true } } }
	*  )
	* )
	* 增加包管理版本
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

			// 包管理对象
			$model = new \VirgoModel\ManageAppModel;

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
			$this->configValid('required',$this->_configs,['name', 'versionCode', 'versionText', 'apkUrl']);

			$name = $this->_configs['name'];
			$version_code = $this->_configs['versionCode'];
			$version_text = $this->_configs['versionText'];
			$apk_url = $this->_configs['apkUrl'];
			$description = empty($this->_configs['description'])? '':$this->_configs['description'];

			// 获取指定名称 指定开发版本号应用记录
			$record = $model->getRecordWithNameVersionCode($name, $version_code);

			if( !empty($record) ) {
				throw new \Exception("已经同开发版本号的该应用记录", '026');	
			}

			$data['name'] = $name;
			$data['version_code'] = $version_code;
			$data['version_text'] = $version_text;
			$data['apk_url'] = $apk_url;
			$data['description'] = $description;
			$data['create_time'] = time();
			$data['update_time'] = time();

			$rs = $model->create( $data );
			unset($data);

			if( !$rs ) {
				throw new \Exception("添加包管理版本失败", '005');
			}

			$return = $this->functionObj->toAppJson(null, '001', '增加包管理版本成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/apk/delete", tags={"Apk"}, 
	*  summary="删除包管理版本",
	*  description="用户鉴权后 通过传入的包版本管理ids 进行包版本管理删除",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="ids", type="string", required=true, in="formData", description="包版本管理id 以,分隔组成的字符串"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "删除包版本管理成功", "success": true } } }
	*  )
	* )
	* 删除包管理版本
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

			// 安卓包管理对象
			$model = new \VirgoModel\ManageAppModel;

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

			$updateData['is_deleted'] = 1;
			$updateData['update_time'] = time();

			$rs = $model->multiplePartUpdate($ids, $updateData);

			if( !$rs ) {
				throw new \Exception("删除包版本管理失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '删除包版本管理成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/apk/read", tags={"Apk"}, 
	*  summary="包版本管理记录详情",
	*  description="用户鉴权后 通过传入的id获取包版本管理记录对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="记录id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "Apk", "status": { "code": "001", "message": "获取记录详情成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/Apk"
	*   )
	*  )
	* )
	* 包版本管理记录详情
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

			// 安卓包管理对象
			$model = new \VirgoModel\ManageAppModel;

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
				throw new \Exception("数据可能不存在或已删除", '006');	
			}

			$return = $this->functionObj->toAppJson($data, '001', '获取记录详情成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/apk/update", tags={"Apk"}, 
	*  summary="更新包管理",
	*  description="用户鉴权后 通过传入的指定参数进行包管理更新",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="记录id"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="应用名"),
	*  @SWG\Parameter(name="versionCode", type="integer", required=true, in="formData", description="开发版本"),
	*  @SWG\Parameter(name="versionText", type="string", required=true, in="formData", description="用户版本"),
	*  @SWG\Parameter(name="apkUrl", type="string", required=false, in="formData", description="包地址 通过上传附件接口获得"),
	*  @SWG\Parameter(name="description", type="string", required=false, in="formData", description="描述"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "更新记录成功", "success": true } } }
	*  )
	* )
	* 修改详情
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

			// 安卓包管理对象
			$model = new \VirgoModel\ManageAppModel;

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
			$this->configValid('required',$this->_configs,["id", 'name', 'versionCode', 'versionText']);

			$id = (int)$this->_configs['id'];
			$name = $this->_configs['name'];
			$version_code = $this->_configs['versionCode'];
			$version_text = $this->_configs['versionText'];

			if( !empty($this->_configs['apkUrl']) ) {
				$data['apk_url'] = $this->_configs['apkUrl'];
			}

			if( !empty($this->_configs['description']) ) {
				$data['description'] = $this->_configs['description'];
			}

			// 获取指定名称 指定开发版本号应用记录
			$record = $model->getRecordWithNameVersionCode($name, $version_code);

			if( !empty($record) ) {
				throw new \Exception("已经同开发版本号的该应用记录", '026');	
			}

			$data['name'] = $name;
			$data['version_code'] = $version_code;
			$data['version_text'] = $version_text;
			$data['update_time'] = time();

			$rs = $model->partUpdate($id, $data);
			unset($data);

			if( !$rs ) {
				throw new \Exception("修改记录失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '更新记录成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}