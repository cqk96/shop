<?php
namespace VirgoApi\Comment;
use Illuminate\Database\Capsule\Manager as DB;
use VirgoApi;
class ApiCommentController extends VirgoApi\ApiBaseController{

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
	* @SWG\Post(path="/api/v1/Comment/create", tags={"Comment"}, 
	*  summary="创建评论",
	*  description="用户鉴权后 通过传入的商品名称，中文名，外文名，价格售价等创建商品如果存在同名，商品新建会失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="productId", type="string", required=true, in="formData", description="商品ID"),
	*  @SWG\Parameter(name="username", type="string", required=true, in="formData", description="用户名"),
	*  @SWG\Parameter(name="starlevel", type="integer", required=true, in="formData", description="星级1-5"),
	*  @SWG\Parameter(name="pictures", type="string", required=false, in="formData", description="评论图片"),
	*  @SWG\Parameter(name="content", type="string", required=true, in="formData", description="评论内容"),
	*  @SWG\Parameter(name="is_anonymous", type="string", required=true, in="formData", description="是否匿名"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "创建商品成功", "success": true } } }
	*  )
	* )
	* 创建评论
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

		
			// 评论对象
			$CommentModel = new \VirgoModel\CommentModel;
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
			$this->configValid('required',$this->_configs,['productId','username','starlevel']);


				

			/*事务*/
			DB::beginTransaction();

			$isBlock = true;

			$productId = $this->_configs['productId'];
			$username = $this->_configs['username'];
			$starlevel = $this->_configs['starlevel'];
			$is_anonymous = $this->_configs['is_anonymous'];
			$pictures = $this->_configs['pictures'];
			$content = $this->_configs['content'];	
			$user_id = $uid	;
			$createtime = $this->_configs['createtime'];

			$insertData['productId'] = $productId;
			$insertData['username'] = $username;
			$insertData['starlevel'] = $starlevel;
			$insertData['content'] = $content;
			$insertData['pictures'] = $pictures;
			$insertData['is_anonymous'] = $is_anonymous;
			$insertData['is_deleted'] = 0;
			$insertData['user_id'] = $user_id;
			$insertData['createtime'] = $createtime;
			$insertData['create_time'] = time();
			$insertData['update_time'] = time();

			// $insertData['setmealJsonArr'] = $setmealJsonArr;

			/*评论id*/
			$commentId = $CommentModel->create( $insertData );


			DB::commit();
			$return = $this->functionObj->toAppJson(null, '001', '添加评论成功', true);

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
	* @SWG\Get(path="/api/v1/Comment/lists", tags={"Comment"}, 
	*  summary="查看商品列表",
	*  description="用户鉴权后 列出商品基础属性",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="string", required=true, in="query", description="page"),
	*  @SWG\Parameter(name="size", type="string", required=true, in="query", description="size"),
	*  @SWG\Parameter(name="productName", type="string", required=false, in="query", description="搜索商品"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "获取评论列表成功", "success": true } } }
	*  )
	* )
	* 评论列表
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
			// 评论对象
			$CommentModel = new \VirgoModel\CommentModel;

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

			$productName = empty( $this->_configs['productName'] )? null:$this->_configs['productName'];
			$youhuashi = empty( $this->_configs['youhuashi'] )? null:$this->_configs['youhuashi'];
			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$params['youhuashi'] = $youhuashi;
			$params['productName'] = $productName;
			$params['skip'] = $skip;
			$params['size'] = $size;

			/*获取全部商品*/
			$pageObj = $CommentModel->getListsObject($params);
			
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
	* @SWG\Get(path="/api/v1/Comment/detail", tags={"Comment"}, 
	*  summary="查看详细属性",
	*  description="用户鉴权后 查看商品属性",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="id", type="string", required=true, in="query", description="id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "获取评论详细成功", "success": true } } }
	*  )
	* )
	* 评论详情
	* @author 	xww
	* @return 	json
	*/



		public function detail()
			{
		
		try{

			// //验证 
			// $user = $this->getUserApi($this->_configs, 1);

			// $uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;
			// 评论对象
			$CommentModel = new \VirgoModel\CommentModel;

			$codModel = new \VirgoModel\CodModel;
			/**
			* 鉴权
			*/
			// // 是否有权限
			// $hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 4]);

			// if( !$hasPrivilige ) {
			// 	// 没有权限提示
			// 	throw new \Exception("没有登录权限和查看数据权限", '070');
			// }

			//验证
			$this->configValid('required',$this->_configs,['id']);

			$id = $this->_configs['id'];

			/*获取全部商品*/
			$data = $CommentModel->getdetail($id);

			$data['content'] = empty($data['content'])? '':html_entity_decode($data['content']);
			// $data['images'] = $data['images']=="[]"? "[]":html_entity_decode($data['images']);

			$return = $this->functionObj->toAppJson($data, '001', '获取商品详情成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

 
	/**
	* @SWG\Post(path="/api/v1/Comment/update", tags={"Comment"}, 
	*  summary="修改评论",
	*  description="用户鉴权后 通过传入的评论ID，商品ID，用户名，等修改评论",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="string", required=true, in="formData", description="评论ID"),
	*  @SWG\Parameter(name="productId", type="string", required=true, in="formData", description="商品ID"),
	*  @SWG\Parameter(name="username", type="string", required=true, in="formData", description="用户名"),
	*  @SWG\Parameter(name="starlevel", type="integer", required=true, in="formData", description="星级1-5"),
	*  @SWG\Parameter(name="pictures", type="string", required=true, in="formData", description="评论图片"),
	*  @SWG\Parameter(name="content", type="string", required=true, in="formData", description="评论内容"),
	*  @SWG\Parameter(name="is_anonymous", type="string", required=true, in="formData", description="是否匿名"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "修改评论成功", "success": true } } }
	*  )
	* )
	* 修改评论
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
			// 评论对象
			$CommentModel = new \VirgoModel\CommentModel;

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
			$this->configValid('required',$this->_configs,['productId','id']);

			DB::beginTransaction();

			$isBlock = true;
			$id = (int)$this->_configs['id'];
			$starlevel = (int)$this->_configs['starlevel'];
			
			$updateData['productId'] = $this->_configs['productId'];
			$updateData['username'] = $this->_configs['username'];
			$updateData['starlevel'] = $this->_configs['starlevel'];
			$updateData['content'] = $this->_configs['content'];
			$updateData['pictures'] = $this->_configs['pictures'];
		    $updateData['is_anonymous'] = $this->_configs['is_anonymous'];
			$updateData['update_time'] = time();  

			
		
			$rs = $CommentModel->partUpdate($id, $updateData);
	
			if( !$rs ) {
				throw new \Exception("修改失败", '003');
			}

			DB::commit();

			$return = $this->functionObj->toAppJson(null, '001', '修改评论成功', true);

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
	* @SWG\Post(path="/api/v1/Comment/commentdelete", tags={"Comment"}, 
	*  summary="删除评论",
	*  description="用户鉴权后 输入ID删除评论",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="string", required=true, in="formData", description="id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "删除评论成功", "success": true } } }
	*  )
	* )
	* 删除评论
	* @author 	xww
	* @return 	json
	*/


	public function commentdelete()
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

			// 评论对象
			$CommentModel = new \VirgoModel\CommentModel;

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

			$data['comments.is_deleted'] = 1;
			$data['comments.update_time'] = time();
			$rs = $CommentModel->deleteProdcutSetmeal($id, $data);
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

	
}