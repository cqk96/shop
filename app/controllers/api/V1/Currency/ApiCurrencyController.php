<?php
namespace VirgoApi\V1\Currency;
use VirgoApi;
class ApiCurrencyController  extends VirgoApi\ApiBaseController
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
	* @SWG\Get(path="/api/v1/currency/lists", tags={"Currency"}, 
	*  summary="获取货币列表",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="name", type="string", required=false, in="query", description="查询的货币名称"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "CurrencyListsObj",  "code": "001", "message": "获取货币列表成功", "totalCount": 3 } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/CurrencyListsObj"
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
			$model = new \VirgoModel\CurrencyManagementModel;

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

			$return = $this->functionObj->toLayuiJson($data, '001', '获取货币列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 是否有相同名称的货币
	* @SWG\Get(path="/api/v1/currency/hasCurrency", tags={"Currency"}, 
	*  summary="获取货币名称情况",
	*  description="用户鉴权后 通过传入的name获取是否数据库中已存在该名称记录 如果返回为true表示存在 false为不存在",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="query", description="查询的名称"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": {"data": "true or false", "status": {"code": "001", "message": "查询货币名称情况成功", "success": true } } }
	*  )
	* )
	* @author 	xww
	* @return   json
	*/
	public function hasCurrency()
	{

		try {

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\CurrencyManagementModel;

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
			$this->configValid('required',$this->_configs,['name']);

			$name = $this->_configs['name'];

			$data = $model->hasSameName($name);

			$return = $this->functionObj->toAppJson($data, '001', '查询货币名称情况成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 增加
	* @SWG\Post(path="/api/v1/currency/create", tags={"Currency"}, 
	*  summary="创建货币",
	*  description="用户鉴权后 通过传入的货币名，货币前置符号，货币后置符号，货币简称来创建货币",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="countryId", type="integer", required=true, in="formData", description="国家id", maxLength=30),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="货币名 长度上限30", maxLength=30),
	*  @SWG\Parameter(name="frontSymbol", type="string", required=true, in="formData", description="前置符号 长度上限5", maxLength=5),
	*  @SWG\Parameter(name="abbreviation", type="string", required=true, in="formData", description="货币简称 长度上限10", maxLength=10),
	*  @SWG\Parameter(name="backSymbol", type="string", required=true, in="formData", description="后置符号 长度上限5", maxLength=5),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "货币数据添加成功", "success": true } } }
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
			$model = new \VirgoModel\CurrencyManagementModel;

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
			$this->configValid('required',$this->_configs,['name', 'countryId']);

			$name = $this->_configs['name'];
			$frontSymbol = empty($this->_configs['frontSymbol'])? '':$this->_configs['frontSymbol'];
			$backSymbol = empty($this->_configs['backSymbol'])? '':$this->_configs['backSymbol'];
			$abbreviation = empty($this->_configs['abbreviation'])? '':$this->_configs['abbreviation'];
			$countryId = $this->_configs['countryId'];

			$hasRecord = $model->hasSameName($name);

			if( $hasRecord ) {
				throw new \Exception("记录已经存在", '026');
			}

			$data['name'] = $name;
			$data['country_id'] = $countryId;
			$data['front_symbol'] = $frontSymbol;
			$data['back_symbol'] = $backSymbol;
			$data['abbreviation'] = $abbreviation;
			$data['create_time'] = time();
			$data['update_time'] = time();

			$record = $model->create( $data );

			if( !$record ) {
				throw new \Exception("货币数据添加失败", '005');
			}

			$return = $this->functionObj->toAppJson(null, '001', '货币数据添加成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 详情
	* @SWG\Get(path="/api/v1/currency/read", tags={"Currency"}, 
	*  summary="详情",
	*  description="用户鉴权后 通过传入的id获取记录详情",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="记录id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "Currency", "status": { "code": "001", "message": "货币数据详情查询成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/Currency"
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
			$model = new \VirgoModel\CurrencyManagementModel;

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

			$return = $this->functionObj->toAppJson($data, '001', '货币数据详情查询成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/currency/delete", tags={"Currency"}, 
	*  summary="删除货币",
	*  description="用户鉴权后 通过传入的货币ids 进行货币删除",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="ids", type="string", required=true, in="formData", description="货币ids 以,分隔的记录id字符串"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "删除货币成功", "success": true } } }
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
			$model = new \VirgoModel\CurrencyManagementModel;

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
				throw new \Exception("删除货币失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '删除货币成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}


	/**
	* @SWG\Post(path="/api/v1/currency/update", tags={"Currency"}, 
	*  summary="修改货币",
	*  description="用户鉴权后 通过传入的记录id, 货币名,货币前置符号,货币后置符号,来更新记录",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="记录id"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="货币名 长度上限30", maxLength=30),
	*  @SWG\Parameter(name="countryId", type="integer", required=true, in="formData", description="国家id"),
	*  @SWG\Parameter(name="frontSymbol", type="string", required=true, in="formData", description="前置符号 长度上限5", maxLength=5),
	*  @SWG\Parameter(name="abbreviation", type="string", required=true, in="formData", description="货币简称 长度上限10", maxLength=10),
	*  @SWG\Parameter(name="backSymbol", type="string", required=true, in="formData", description="后置符号 长度上限5", maxLength=5),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "修改货币成功", "success": true } } }
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
			$model = new \VirgoModel\CurrencyManagementModel;

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
			$this->configValid('required',$this->_configs,['id', 'name',  'countryId']);

			$id = $this->_configs['id'];
			$name = $this->_configs['name'];
			$frontSymbol =  empty($this->_configs['frontSymbol'])? '':$this->_configs['frontSymbol'];
			$countryId = $this->_configs['countryId'];
			$backSymbol = empty($this->_configs['backSymbol'])? '':$this->_configs['backSymbol'];
			$abbreviation = empty($this->_configs['abbreviation'])? '':$this->_configs['abbreviation'];

			$updateData['name'] = $name;
			$updateData['country_id'] = $countryId;
			$updateData['front_symbol'] = $frontSymbol;
			$updateData['back_symbol'] = $backSymbol;
			$updateData['abbreviation'] = $abbreviation;
			$updateData['update_time'] = time();

			// 查询数据
			$data = $model->read($id);

			if( empty($data) ) {
				throw new \Exception("数据可能不存在或已删除", '006');	
			}

			/*查询该名称的记录*/
			$recordArr = $model->getRecordWithName($name);

			if( !empty($recordArr) && $recordArr[0]['id']!=$id ) {
				throw new \Exception("存在同名货币记录", '026');
			}

			$rs = $model->partUpdate($id, $updateData);

			if( !$rs ) {
				throw new \Exception("修改货币失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '修改货币成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/currency/all", tags={"Currency"}, 
	*  summary="获取全部国家",
	*  description="用户鉴权",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "CurrencyAllLists",  "code": "001", "message": "获取全部货币数据成功", "totalCount": 3 } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/CurrencyAllLists")
	*   )
	*  )
	* )
	* 获取全部国家
	*/
	public function all()
	{

		try {

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\CurrencyManagementModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 4]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和查看数据权限", '070');
			}

			$data = $model->getAllCurrency();

			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取全部货币数据成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}
