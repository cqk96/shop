<?php
namespace VirgoApi\NewsClass;
use Illuminate\Database\Capsule\Manager as DB;
use VirgoApi;
class ApiNewsClassController extends VirgoApi\ApiBaseController
{

	/**
	* api参数数组
	* @var array
	*/
	private $_configs;

	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
		$this->model = new \VirgoModel\NewsModel;
		$this->_configs = parent::change();
	}

	/**
	* 获取指定文章分类的
	* @author 	xww
	* @return 	json
	*/ 
	public function frontReadOne()
	{
		
		try{

			if( empty($_GET['className']) ) {
				throw new \Exception("文章类型不为空");
			}

			$name = $_GET['className'];

			$data = \EloquentModel\NewsClasses::leftJoin("news", "news.class_id", "=", "news_classes.id")
									  ->where("news.status", 0)
									  ->where("news_classes.status", 0)
									  ->where("news.pass", 1)
									  ->where("news_classes.class_name", $name)
									  ->orderBy("news.created_at", "desc")
									  ->orderBy("news.id", "desc")
									  ->first();

			if( empty($data) ) {
				throw new \Exception("没有符合条件内容");
			}

			// 跳转到详情页
			header("Location: /front/showNewsVer2?id=" .$data['id'] );
			exit();

		} catch(\Exception $e) {
			echo $this->showHtmlNotice( $e->getMessage() );
		}

	}

	/**
	* @SWG\Get(path="/api/v1/NewsClass/lists", tags={"NewsClass"}, 
	*  summary="获取文章分类列表",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "NewsClassListsObj", "code": "001", "message": "获取文章分类列表成功", "totalCount": 3 } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/NewsClassListsObj")
	*   )
	*  )
	* )
	* 获取分类类表
	* @author　	xww
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

			// 对象
			$model = new \VirgoModel\NewsClassModel;

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

			$return = $this->functionObj->toLayuiJson($data, '001', '获取文章分类列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/NewsClass/create", tags={"NewsClass"}, 
	*  summary="增加文章分类",
	*  description="用户鉴权后 通过名称 其他根据参数进行分类增加",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="class_name", type="string", required=true, in="formData", description="名称"),
	*  @SWG\Parameter(name="pid", type="integer", required=false, in="formData", description="上级id"),
	*  @SWG\Parameter(name="cids", type="string", required=false, in="formData", description="要修改的下级id 以,分隔的字符串"),
	*  @SWG\Parameter(name="hidden", type="integer", required=false, in="formData", description="是否隐藏，0否1是", default=0),
	*  @SWG\Parameter(name="cover", type="string", required=false, in="formData", description="封面地址"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "recordId", "status": { "code": "001", "message": "创建文章分类成功", "success": true } } }
	*  )
	* )
	* 增加
	* @author　	xww
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

			// 对象
			$model = new \VirgoModel\NewsClassModel;

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
			$this->configValid('required',$this->_configs,["class_name"]);

			DB::beginTransaction();

			$isBlock = true;

			$name = $this->_configs['class_name'];
			$hidden = empty($this->_configs['hidden'])? 0:1;
			$cover = empty($this->_configs['cover'])? '':$this->_configs['cover'];
			$pclass_id = empty($this->_configs['pid'])? 0:$this->_configs['pid'];

			// 下级部门
			$cidArr = empty($this->_configs['cids'])? null:explode(",", $this->_configs['cids']);

			$insertData['pclass_id'] = $pclass_id;
			$insertData['class_name'] = $name;
			$insertData['hidden'] = $hidden;
			$insertData['cover'] = $cover;
			$insertData['update_time'] = time();

			$recordId = $model->create( $insertData );

			if( !$recordId ) {
				throw new \Exception("添加文章分类失败", '005');
			}

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

					$hasRecord = $model->getChildrensClasses( $recordId );

					if( !empty($hasRecord) ) {

						$rs = $model->changeChildrenParentClasses( $recordId );

						if( !$rs ) {
							throw new \Exception("清空下级失败", '003');
						}

					}

					$rs = $model->setChildrenParentClass( $recordId, $cids);
					if( !$rs ) {
					 	throw new \Exception("设置下级失败", '003'); 
					}

				}
				
			}

			DB::commit();

			$return = $this->functionObj->toAppJson($recordId, '001', '创建文章分类成功', true);

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
	* @SWG\Post(path="/api/v1/NewsClass/delete", tags={"NewsClass"}, 
	*  summary="删除文章分类",
	*  description="用户鉴权后 通过传入的文章分类ids 进行文章分类删除",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="ids", type="string", required=true, in="formData", description="文章分类ids 以,分隔组成的字符串"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "删除文章分类成功", "success": true } } }
	*  )
	* )
	* 删除
	* @author 	xww
	* @todo 	如果模板已被使用 则不应该被删除
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

			// 对象
			$model = new \VirgoModel\NewsClassModel;

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

			$updateData['status'] = 1;
			$updateData['update_time'] = time();

			$rs = $model->multiplePartUpdate($ids, $updateData);

			if( !$rs ) {
				throw new \Exception("删除文章分类失败", '003');
			}

			// 进行删除操作
			$model->deleteRelMenu( $id );

			$return = $this->functionObj->toAppJson(null, '001', '删除文章分类成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}	

	}

	/**
	* @SWG\Get(path="/api/v1/NewsClass/read", tags={"NewsClass"}, 
	*  summary="获取文章分类详情",
	*  description="需要鉴权 传入分类id获取分类详情 详情中除了自身数据外还包括两个列表，上级分类列表与下级分类列表通过 checked字段标明是否选中",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=false, in="query", description="分类id 可空"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "NewsClassInfoDetail", "status": { "code": "001", "message": "查询文章分类情况成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/NewsClassInfoDetail"
	*   )
	*  )
	* )
	* 查看详情
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

			// 对象
			$model = new \VirgoModel\NewsClassModel;

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
			$data = $model->getInfoWidthParentAndChildren($id);

			if( empty($data) ) {
				throw new \Exception("文章分类数据可能不存在或已删除", '006');	
			}

			$return = $this->functionObj->toAppJson($data, '001', '查询文章分类情况成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 修改文章分类
	* @SWG\Post(path="/api/v1/NewsClass/update", tags={"NewsClass"}, 
	*  summary="修改文章分类",
	*  description="用户鉴权后 通过传入当前分类id 和名称 进行本身分类修改 其他根据参数进行修改",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="要修改的id"),
	*  @SWG\Parameter(name="class_name", type="string", required=true, in="formData", description="修改后的名称"),
	*  @SWG\Parameter(name="pid", type="integer", required=false, in="formData", description="要修改的上级id"),
	*  @SWG\Parameter(name="parentEmpty", type="integer", required=false, in="formData", description="是否进行清空上级操作，0否1是", default=0),
	*  @SWG\Parameter(name="cids", type="string", required=false, in="formData", description="要修改的下级id 以,分隔的字符串"),
	*  @SWG\Parameter(name="childrenEmpty", type="integer", required=false, in="formData", description="是否进行清空下级操作，0否1是", default=0),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": null, "status": { "code": "001", "message": "修改文章分类情况成功", "success": true } } }
	*  )
	* )
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

			// 对象
			$model = new \VirgoModel\NewsClassModel;

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
			$this->configValid('required',$this->_configs,['id', 'class_name']);

			DB::beginTransaction();

			$isBlock = true;

			$id = $this->_configs['id'];
			$class_name = $this->_configs['class_name'];

			// 上级
			$pid = empty($this->_configs['pid']) || (int)$this->_configs['pid']==0 ? null:(int)$this->_configs['pid'];

			// 用来表示是否清空上级
			$parentEmpty = empty($this->_configs['parentEmpty'])? 0:1;

			// 下级
			$cidArr = empty($this->_configs['cids'])? null:explode(",", $this->_configs['cids']);

			// 用来表示是否清空下级
			$childrenEmpty = empty($this->_configs['childrenEmpty'])? 0:1;

			// 清空上级部门
			if( $parentEmpty ) {
				$data['pclass_id'] = 0;
			} else {

				if( !is_null($pid) ) {
					$hasRecord = $model->readSingelTon( $pid );
					if( empty($hasRecord) ) {
					 	throw new \Exception("上级不存在或已删除", '006'); 
					}
					$data['pclass_id'] = $pid;
				}

			}

			// 判断是否有此部门
			$hasRecord = $model->readSingelTon( $id );
			if( empty($hasRecord) ) {
			 	throw new \Exception("数据不存在或已删除", '006'); 
			}

			if( !empty($this->_configs['cover']) ) {
				$data['cover'] = $this->_configs['cover'];
			}

			$data['update_time'] = time();
			$data['class_name'] = $class_name;
			$data['hidden'] = empty($this->_configs['hidden'])? 0:1;

			// 更新
			$rs = $model->updateParts($id, $data);
			unset($data);

			if( !$rs ) {
				throw new \Exception("修改分类失败", '003');
			}

			// 清空下级部门
			if( $childrenEmpty ) {

				$hasRecord = $model->getChildrensClasses( $id );

				if( !empty($hasRecord) ) {

					$rs = $model->changeChildrenParentClasses( $id );

					if( !$rs ) {
						throw new \Exception("清空下级失败", '003');
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

						$hasRecord = $model->getChildrensClasses( $id );

						if( !empty($hasRecord) ) {

							$rs = $model->changeChildrenParentClasses( $id );

							if( !$rs ) {
								throw new \Exception("清空下级失败", '003');
							}

						}

						$rs = $model->setChildrenParentClass( $id, $cids);
						if( !$rs ) {
						 	throw new \Exception("设置下级失败", '003'); 
						}

					}
					
				}

			}

			DB::commit();
			$return = $this->functionObj->toAppJson(null, '001', '修改文章分类情况成功', true);

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
	* @SWG\Get(path="/api/v1/NewsClass/allList", tags={"NewsClass"}, 
	*  summary="获取全部分类",
	*  description="用户鉴权后 获取全部数据",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "AllNewsClassLists", "status": { "code": "001", "message": "获取全部文章分类成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/AllNewsClassLists")
	*   )
	*  )
	* )
	* 获取全部分类
	* @author 	xww
	* @return 	json
	*/
	public function allList()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\NewsClassModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 4]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和查看数据权限", '070');
			}

			// 查询数据
			$data = $model->getAll();
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取全部文章分类成功', true);

		} catch(\Exception $e) {

			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}