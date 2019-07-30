<?php
namespace VirgoApi\Category;
use Illuminate\Database\Capsule\Manager as DB;
use VirgoApi;
class ApiCategoryController extends VirgoApi\ApiBaseController{

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
	* @SWG\Post(path="/api/v1/Cod/goods", tags={"Cod"}, 
	*  summary="创建商品",
	*  description="用户鉴权后 通过传入的商品名称，中文名，外文名，价格售价等创建商品如果存在同名，商品新建会失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "创建商品成功", "success": true } } }
	*  )
	* )
	* 创建片区
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

			// 商品对象
			$codModel = new \VirgoModel\CodModel;

			// 菜单属性商品对象
			$GoodsToSetmealToPropertiesModel = new \VirgoModel\GoodsToSetmealToPropertiesModel;

		
			// 商品对象
			$CategoryModel = new \VirgoModel\CategoryModel;
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
			$this->configValid('required',$this->_configs,['name']);


				

			/*事务*/
			DB::beginTransaction();

			$isBlock = true;

			$name = $this->_configs['name'];

	
			
			// var_dump($Fb_Id);
			// die;
			

			// 是否有同名商品创建过
			$record = $CategoryModel->getCategory($name);
			if( !empty($record) ) {
				throw new \Exception("已存在同名品类", '026');
			}
			
			$insertData['name'] = $name;

			$insertData['is_deleted'] = 0;
			$insertData['create_time'] = time();
			$insertData['update_time'] = time();

			// $insertData['setmealJsonArr'] = $setmealJsonArr;

			/*商品id*/
			$categoryId = $CategoryModel->create( $insertData );


			DB::commit();
			$return = $this->functionObj->toAppJson(null, '001', '创建品类成功', true);

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
	* @SWG\Get(path="/api/v1/Cod/lists", tags={"Cod"}, 
	*  summary="查看商品列表",
	*  description="用户鉴权后 列出商品基础属性",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="string", required=true, in="query", description="page"),
	*  @SWG\Parameter(name="size", type="string", required=true, in="query", description="size"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="query", description="搜索商品"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "获取商品列表成功", "success": true } } }
	*  )
	* )
	* 商品列表
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
			// 实例化对象
			$SetmealModel = new \VirgoModel\SetmealModel;
			$codModel = new \VirgoModel\CodModel;
			// 品类对象
			$CategoryModel = new \VirgoModel\CategoryModel;
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
			/*获取全部商品*/
			$pageObj = $CategoryModel->getList($params);
			
			$data = empty($pageObj->data)? []:$pageObj->data;
			$totalCount = $pageObj->totalCount;

			$return = $this->functionObj->toLayuiJson($data, '001', '获取商品列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}


	
 	/**
	* @SWG\Post(path="/api/v1/Cod/goodsupdate", tags={"Cod"}, 
	*  summary="修改商品",
	*  description="用户鉴权后 通过传入的商品名称，中文名，外文名，价格售价等创建商品如果存在同名同属地块的商品新建会失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="品类名称"),
	
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "修改商品成功", "success": true } } }
	*  )
	* )
	* 创建片区
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

			// 商品对象
			$codModel = new \VirgoModel\CodModel;
			// 属性对象
			$propertiesModel = new \VirgoModel\PropertiesModel;
			// 菜单对象
			$setmealModel = new \VirgoModel\SetmealModel;
			// 菜单属性商品对象
			$GoodsToSetmealToPropertiesModel = new \VirgoModel\GoodsToSetmealToPropertiesModel;
			// 品类对象
			$CategoryModel = new \VirgoModel\CategoryModel;

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
			$this->configValid('required',$this->_configs,['name','id']);

			DB::beginTransaction();

			$isBlock = true;
			$id = (int)$this->_configs['id'];
			
			$updateData['name'] = $this->_configs['name'];
		
			$updateData['update_time'] = time();  

			
		
			$rs = $CategoryModel->partUpdate($id, $updateData);
	
			if( !$rs ) {
				throw new \Exception("修改失败", '003');
			}

			DB::commit();

			$return = $this->functionObj->toAppJson(null, '001', '修改商品成功', true);

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
	* @SWG\Post(path="/api/v1/Cod/goodsdelete", tags={"Cod"}, 
	*  summary="删除商品",
	*  description="用户鉴权后 输入ID删除商品",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="string", required=true, in="formData", description="id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "删除商品成功", "success": true } } }
	*  )
	* )
	* 删除商品
	* @author 	xww
	* @return 	json
	*/


	public function categorydelete()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 地块对象
			$model = new \VirgoModel\AcreModel;
			// 商品对象
			$codModel = new \VirgoModel\CodModel;

			// 品类对象
			$CategoryModel = new \VirgoModel\CategoryModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 3]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和删除数据权限", '070');
			}
			$id = $this->_configs['id'];

			$data['category.is_deleted'] = 1;
			$data['category.update_time'] = time();
			$rs = $CategoryModel->deleteProdcutSetmeal($id, $data);
			unset($data);
			
			if( !$rs ) {
				throw new \Exception("删除失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '删除成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

 public function allcategory()
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
			// 品类对象
			$CategoryModel = new \VirgoModel\CategoryModel;
			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 4]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和查看数据权限", '070');
			}

	

			/*获取全部商品*/
			$data = $CategoryModel->getListsObject();
			
		
			

			$return = $this->functionObj->toLayuiJson($data, '001', '获取商品列表成功', count($data ));
			
		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

public function hascategory()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$model = new \VirgoModel\UserModel;
			$codModel = new \VirgoModel\CodModel;
			// 品类对象
			$CategoryModel = new \VirgoModel\CategoryModel;
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
			$this->configValid('required',$this->_configs,['name']);

			$name = $this->_configs['name'];

			// 判断账号是否存在
			$data = $CategoryModel->getcatalog( $name );
			$result = !empty($data)? true:false;

			$return = $this->functionObj->toAppJson($result, '001', '获取品类信息成功', true);			

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

}