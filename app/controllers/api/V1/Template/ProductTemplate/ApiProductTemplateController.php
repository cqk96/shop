<?php
namespace VirgoApi\V1\Template\ProductTemplate;
use VirgoApi;
class ApiProductTemplateController  extends VirgoApi\ApiBaseController
{
	/**
	* api参数数组
	* @var array
	*/
	private $_configs;
	public function __construct()
	{
		
		$this->functionObj = new \VirgoUtil\Functions;
		$this->_configs = parent::change();
	}
	/**
	* @SWG\Get(path="/api/v1/template/productTemplate/lists", tags={"Template", "ProductTemplate"}, 
	*  summary="获取模板-列表",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="name", type="string", required=false, in="query", description="查询的模板名称"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "ProductTemplateListsObj",  "code": "001", "message": "获取模板列表成功", "totalCount": 3 } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/ProductTemplateListsObj"
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
			// 对象
			$model = new \VirgoModel\ProductTemplateManagementModel;
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
			$name = empty($this->_configs['name'])? null:$this->_configs['name'];
			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;
			$params['name'] = $name;
			$params['skip'] = $skip;
			$params['size'] = $size;
			$pageObj = $model->getListsObject($params);
			$data = empty($pageObj->data)? null:$pageObj->data;
			$totalCount = $pageObj->totalCount;
			$return = $this->functionObj->toLayuiJson($data, '001', '获取模板列表成功', $totalCount);
		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}
	}
	/**
	* 增加
	* @SWG\Post(path="/api/v1/template/productTemplate/create", tags={"Template", "ProductTemplate"}, 
	*  summary="创建模板",
	*  description="用户鉴权后 通过传入的国家id, 语言id, 模板地址,模板封面 来创建模板",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="countryId", type="integer", required=true, in="formData", description="国家id"),
	*  @SWG\Parameter(name="languageId", type="integer", required=true, in="formData", description="语言id"),
	*  @SWG\Parameter(name="templateUrl", type="string", required=true, in="formData", description="模板地址"),
	*  @SWG\Parameter(name="cover", type="string", required=true, in="formData", description="模板封面"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": "id", "status": { "code": "001", "message": "模板添加成功", "success": true } } }
	*  )
	* )
	*/
	public function create()
	{
		try {
			//验证 
			$user = $this->getUserApi($this->_configs);
			$uid = $user[0]['id'];
			// 实例化对象
			$userModel = new \VirgoModel\UserModel;
			// 对象
			$model = new \VirgoModel\ProductTemplateManagementModel;
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
			$this->configValid('required',$this->_configs,['countryId', 'languageId', 'templateUrl', 'cover']);
			$data['country_id'] = $this->_configs['countryId'];
			$data['language_id'] = $this->_configs['languageId'];
			$data['template_url'] = $this->_configs['templateUrl'];
			$data['cover'] = $this->_configs['cover'];
			$data['name'] = "style" . ( $model->getMaxId() + 1 );
			$data['create_time'] = time();
			$data['update_time'] = time();
			$record = $model->create( $data );
			if( !$record ) {
				throw new \Exception("模板数据添加失败", '005');
			}
			$return = $this->functionObj->toAppJson($record, '001', '模板添加成功', true);
		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}
	}
	/**
	* 详情
	* @SWG\Get(path="/api/v1/template/productTemplate/read", tags={"Template", "ProductTemplate"}, 
	*  summary="详情",
	*  description="用户鉴权后 通过传入的id获取记录详情",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="记录id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "ProductTemplate", "status": { "code": "001", "message": "模板数据详情查询成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/ProductTemplate"
	*   )
	*  )
	* )
	*/
	public function read()
	{
		try {
			//验证 
			$user = $this->getUserApi($this->_configs);
			$uid = $user[0]['id'];
			// 实例化对象
			$userModel = new \VirgoModel\UserModel;
			// 对象
			$model = new \VirgoModel\ProductTemplateManagementModel;
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
			$data = $model->read($id);
			if( empty($data) ) {
				throw new \Exception("数据不存在或已删除", '006');
			}
			unset($data['is_deleted']);
			unset($data['create_time']);
			$return = $this->functionObj->toAppJson($data, '001', '模板数据详情查询成功', true);
		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}
	}
	/**
	* @SWG\Post(path="/api/v1/template/productTemplate/delete", tags={"Template", "ProductTemplate"}, 
	*  summary="删除国家",
	*  description="用户鉴权后 通过传入的模板ids 进行模板删除",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="ids", type="string", required=true, in="formData", description="模板ids 以,分隔的记录id字符串"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "删除模板成功", "success": true } } }
	*  )
	* )
	*/
	public function delete()
	{
		try{
			//验证 
			$user = $this->getUserApi($this->_configs);
			$uid = $user[0]['id'];
			// 实例化对象
			$userModel = new \VirgoModel\UserModel;
			// 对象
			$model = new \VirgoModel\ProductTemplateManagementModel;
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
				throw new \Exception("Wrong Param ids", '014');
			}
			$updateData['is_deleted'] = 1;
			$updateData['update_time'] = time();
			$rs = $model->multiplePartUpdate($ids, $updateData);
			if( !$rs ) {
				throw new \Exception("删除模板失败", '003');
			}
			$return = $this->functionObj->toAppJson(null, '001', '删除模板成功', true);
		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}
	}
	/**
	* @SWG\Post(path="/api/v1/template/productTemplate/update", tags={"Template", "ProductTemplate"}, 
	*  summary="修改国家",
	*  description="用户鉴权后 通过传入的记录id, 国家名来更新记录",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="记录id"),
	*  @SWG\Parameter(name="countryId", type="integer", required=true, in="formData", description="国家id"),
	*  @SWG\Parameter(name="languageId", type="integer", required=true, in="formData", description="语言id"),
	*  @SWG\Parameter(name="templateUrl", type="string", required=true, in="formData", description="模板地址"),
	*  @SWG\Parameter(name="cover", type="string", required=true, in="formData", description="模板封面"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "修改模板成功", "success": true } } }
	*  )
	* )
	* 修改
	*/
	public function update()
	{
		try{
			//验证 
			$user = $this->getUserApi($this->_configs);
			$uid = $user[0]['id'];
			// 实例化对象
			$userModel = new \VirgoModel\UserModel;
			// 对象
			$model = new \VirgoModel\ProductTemplateManagementModel;
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
			$this->configValid('required',$this->_configs,['id', 'countryId', 'languageId', 'templateUrl', 'cover']);
			$id = $this->_configs['id'];
			$updateData['country_id'] = $this->_configs['countryId'];
			$updateData['language_id'] = $this->_configs['languageId'];
			$updateData['template_url'] = $this->_configs['templateUrl'];
			$updateData['cover'] = $this->_configs['cover'];
			$updateData['update_time'] = time();
			// 查询数据
			$data = $model->read($id);
			if( empty($data) ) {
				throw new \Exception("数据可能不存在或已删除", '006');	
			}
			$rs = $model->partUpdate($id, $updateData);
			if( !$rs ) {
				throw new \Exception("修改模板失败", '003');
			}
			$return = $this->functionObj->toAppJson(null, '001', '修改模板成功', true);
		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}
	}
	/**
	* 根据国家和语言查询对应lists
	* @SWG\Get(path="/api/v1/template/productTemplate/search", tags={"Template", "ProductTemplate"}, 
	*  summary="根据查询条件 搜索模板",
	*  description="用户鉴权后 通过传入的国家id,语言id获取模板",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="name", type="string", required=false, in="query", description="查询的模板名称"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "ProductTemplateSearchListsObj",  "code": "001", "message": "获取模板列表成功", "totalCount": 3 } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/ProductTemplateSearchListsObj"
	*   )
	*  )
	* )
	*/
	public function search()
	{
		try {
			//验证 
			$user = $this->getUserApi($this->_configs, 1);
			$uid = $user[0]['id'];
			// 实例化对象
			$userModel = new \VirgoModel\UserModel;
			// 对象
			$model = new \VirgoModel\ProductTemplateManagementModel;
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
			$this->configValid('required',$this->_configs,['countryId', 'languageId', 'page', 'size']);
			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;
			$searchs['country_id'] = $this->_configs['countryId'];
			$searchs['language_id'] = $this->_configs['languageId'];
			$searchs['skip'] = $skip;
			$searchs['size'] = $size;
			$pageObj = $model->searchTemplateObjWithParams($searchs);
			$data = empty($pageObj->data)? null:$pageObj->data;
			$totalCount = $pageObj->totalCount;
			$return = $this->functionObj->toLayuiJson($data, '001', '获取模板列表成功', $totalCount);
		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}
	}
}