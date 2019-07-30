<?php
namespace VirgoApi\ArchiveTemplateCategory;
use VirgoApi;
use Illuminate\Database\Capsule\Manager as DB;
class ApiArchiveTemplateCategoryController extends VirgoApi\ApiBaseController{

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
	* @SWG\Get(path="/api/v1/archiveTemplateCategory/lists", tags={"ArchiveTemplateCategory"}, 
	*  summary="获取档案分类管理 档案分类列表",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="name", type="string", required=false, in="query", description="模板分类名称"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "BackArchiveTemplateCategoryListsObj", "code": "001", "message": "获取模板分类列表成功", "totalCount": 3 } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/BackArchiveTemplateCategoryListsObj"
	*   )
	*  )
	* )
	* 分类列表对象
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

			// 模板分类对象
			$model = new \VirgoModel\ArchiveTemplateCategoryModel;

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

			$pageObj = $model->getListsObject($skip, $size, $name);

			$data = [];

			$data = empty($pageObj->data)? null:$pageObj->data;
			// $data['totalPage'] = intval( ceil($pageObj->totalCount/$pageObj->size) );
			// $data['currentPage'] = intval( $pageObj->current_page );
			$totalCount = $pageObj->totalCount;

			$return = $this->functionObj->toLayuiJson($data, '001', '获取模板分类列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/archiveTemplateCategory/create", tags={"ArchiveTemplateCategory"}, 
	*  summary="增加模板分类",
	*  description="用户鉴权后 通过传入分类名 和其他可选参数来新增模板分类",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="分类名称"),
	*  @SWG\Parameter(name="cover", type="string", required=false, in="formData", description="封面地址 相对地址"),
	*  @SWG\Parameter(name="order_index", type="integer", required=false, in="formData", description="排序"),
	*  @SWG\Parameter(name="resume", type="string", required=false, in="formData", description="简述 10个字以内 e.g 土地耕翻"),
	*  @SWG\Parameter(name="tids", type="string", required=false, in="formData", description="模板id 以,分隔组合成的字符串"),
	*  @SWG\Parameter(name="mainBodys", type="string", required=false, in="formData", description="主体id 以,分隔组合成的字符串 具体id看文档"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "增加模板分类成功", "success": true } } }
	*  )
	* )
	* 增加
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

			// 模板分类对象
			$model = new \VirgoModel\ArchiveTemplateCategoryModel;

			// 基础对象
			$baseModel = new \VirgoModel\BaseModel;

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

			DB::beginTransaction();
			$isBlock = true;

			$name = $this->_configs['name'];
			$cover = empty($this->_configs['cover'])? '':$this->_configs['cover'];
			$resume = empty($this->_configs['resume'])? '':$this->_configs['resume'];
			$order_index = empty($this->_configs['order_index'])  || (int)$this->_configs['order_index']<=0 ? $baseModel->getNextIncrement_ver_2("mango-management", "comp_archive_template_category"):(int)$this->_configs['order_index'];

			// 模板id
			$tidArr = empty($this->_configs['tids'])? null:explode(",", $this->_configs['tids']);

			// 主体id
			$mainArr = empty($this->_configs['mainBodys'])? null:explode(",", $this->_configs['mainBodys']);

			$insertData['name'] = $name;
			$insertData['cover'] = $cover;
			$insertData['resume'] = $resume;
			$insertData['order_index'] = $order_index;
			$insertData['create_time'] = time();
			$insertData['update_time'] = time();

			$recordId = $model->create( $insertData );

			if( !$recordId ) {
				throw new \Exception("添加模板分类失败", '005');
			}

			/*模板关联添加*/
			if( !is_null($tidArr) ) {
				$tids = [];
				for ($i=0; $i < count($tidArr); $i++) { 

					$tid = (int)$tidArr[$i];
					if( !$tid ) {
						continue;
					}

					$temp['archive_template_id'] = $tid;
					$temp['archive_template_category_id'] = $recordId;
					$temp['create_time'] = time();
					$temp['update_time'] = time();

					$tids[] = $temp;
					unset($temp);

				}

				if( !empty($tids) ) {

					$relModel = new \VirgoModel\ArchiveCategoryToArchiveModel;
					$rs = $relModel->multipleCreate($tids);
					if( !$rs ) {
					 	throw new \Exception("设置归属模板失败", '005'); 
					}

				}

			}

			/*主体关联*/
			if( !is_null($mainArr) ) {
				$mainBodys = [];
				for ($i=0; $i < count($mainArr); $i++) { 

					$mainBody = (int)$mainArr[$i];
					if( !$mainBody ) {
						continue;
					}

					$temp['main_body_type_id'] = $mainBody;
					$temp['archive_template_category_id'] = $recordId;
					$temp['create_time'] = time();
					$temp['update_time'] = time();

					$mainBodys[] = $temp;
					unset($temp);

				}

				if( !empty($mainBodys) ) {

					$relModel = new \VirgoModel\ArchiveCategoryToMainBodyModel;
					$rs = $relModel->multipleCreate($mainBodys);
					if( !$rs ) {
					 	throw new \Exception("设置主体关联失败", '005'); 
					}

				}

			}

			DB::commit();
			$return = $this->functionObj->toAppJson(null, '001', '增加模板分类成功', true);

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
	* @SWG\Post(path="/api/v1/archiveTemplateCategory/delete", tags={"ArchiveTemplateCategory"}, 
	*  summary="删除模板分类",
	*  description="用户鉴权后 通过传入的模板分类ids 进行模板分类删除",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="ids", type="string", required=true, in="formData", description="模板分类ids 以,分隔组成的字符串"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "删除模板分类成功", "success": true } } }
	*  )
	* )
	* 删除
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

			// 模板分类对象
			$model = new \VirgoModel\ArchiveTemplateCategoryModel;

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
				throw new \Exception("删除模板分类失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '删除模板分类成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}	

	}

	/**
	* @SWG\Get(path="/api/v1/archiveTemplateCategory/read", tags={"ArchiveTemplateCategory"}, 
	*  summary="模板分类详情",
	*  description="用户鉴权后 通过传入的id获取模板分类对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="模板分类记录id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "ArchiveTemplateCategory", "status": { "code": "001", "message": "获取模板分类详情成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/ArchiveTemplateCategory"
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

			// 模板分类对象
			$model = new \VirgoModel\ArchiveTemplateCategoryModel;

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
			$data = $model->readDetail($id);

			if( empty($data) ) {
				throw new \Exception("模板分类数据可能不存在或已删除", '006');	
			}

			$return = $this->functionObj->toAppJson($data, '001', '获取模板分类详情成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/archiveTemplateCategory/update", tags={"ArchiveTemplateCategory"}, 
	*  summary="更新模板分类",
	*  description="用户鉴权后 通过传入的模板分类记录id, 模板分类名 可选其他参数来更新模板分类信息",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="模板分类id"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="模板分类名称"),
	*  @SWG\Parameter(name="cover", type="string", required=false, in="formData", description="封面地址 相对地址"),
	*  @SWG\Parameter(name="order_index", type="integer", required=false, in="formData", description="排序"),
	*  @SWG\Parameter(name="resume", type="string", required=false, in="formData", description="简述 10个字以内 e.g 土地耕翻"),
	*  @SWG\Parameter(name="templateEmpty", type="integer", required=false, in="formData", description="是否清空下属模板 0否1是", default="0"),
	*  @SWG\Parameter(name="tids", type="string", required=false, in="formData", description="模板id 以,分隔组合成的字符串"),
	*  @SWG\Parameter(name="mainBodyEmpty", type="integer", required=false, in="formData", description="是否清空主体 0否1是", default="0"),
	*  @SWG\Parameter(name="mainBodys", type="string", required=false, in="formData", description="主体id 以,分隔组合成的字符串 具体id看文档"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "更新模板分类成功", "success": true } } }
	*  )
	* )
	* 更新
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

			// 模板分类对象
			$model = new \VirgoModel\ArchiveTemplateCategoryModel;

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
			$this->configValid('required',$this->_configs,['id', 'name']);

			DB::beginTransaction();

			$isBlock = true;

			$id = $this->_configs['id'];
			$name = $this->_configs['name'];

			// 模板id
			$tidArr = empty($this->_configs['tids'])? null:explode(",", $this->_configs['tids']);

			// 主体id
			$mainArr = empty($this->_configs['mainBodys'])? null:explode(",", $this->_configs['mainBodys']);

			// 用来表示是否清空所属模板
			$templateEmpty = empty($this->_configs['templateEmpty'])? 0:1;

			// 用来表示是否清空关联主体
			$mainBodyEmpty = empty($this->_configs['mainBodyEmpty'])? 0:1;

			// 查询数据
			$data = $model->readSingleTon($id);

			if( empty($data) ) {
				throw new \Exception("模板分类数据可能不存在或已删除", '006');	
			}

			$updateData['name'] = $name;

			if( !empty($this->_configs['cover']) ) {
				$updateData['cover'] = $this->_configs['cover'];	
			}

			if( !empty($this->_configs['resume']) ) {
				$updateData['resume'] = $this->_configs['resume'];	
			}

			if( !empty($this->_configs['order_index']) ) {
				$updateData['order_index'] = $this->_configs['order_index'];	
			}
			
			$updateData['update_time'] = time();

			$rs = $model->partUpdate($id, $updateData);

			if( !$rs ) {
				throw new \Exception("修改模板分类失败", '003');
			}

			// 清空下属模板
			if( $templateEmpty ) {

				$hasRecord = $model->getCategoryTemplates( $id );

				if( !empty($hasRecord) ) {

					$relModel = new \VirgoModel\ArchiveCategoryToArchiveModel;

					$rs = $relModel->setArchiveTemplateEmpty( $id );

					if( !$rs ) {
						throw new \Exception("清空归属模板失败", '003');
					}

				}

			} else {

				if( !is_null($tidArr) ) {
					$tids = [];
					for ($i=0; $i < count($tidArr); $i++) { 

						$tid = (int)$tidArr[$i];
						if( !$tid ) {
							continue;
						}

						$temp['archive_template_id'] = $tid;
						$temp['archive_template_category_id'] = $id;
						$temp['create_time'] = time();
						$temp['update_time'] = time();

						$tids[] = $temp;
						unset($temp);

					}

					if( !empty($tids) ) {

						$relModel = new \VirgoModel\ArchiveCategoryToArchiveModel;

						$hasRecord = $model->getCategoryTemplates( $id );

						if( !empty($hasRecord) ) {

							$rs = $relModel->setArchiveTemplateEmpty( $id );

							if( !$rs ) {
								throw new \Exception("清空归属模板失败", '003');
							}

						}

						$rs = $relModel->multipleCreate($tids);
						if( !$rs ) {
						 	throw new \Exception("设置归属模板失败", '005'); 
						}

					}

				}

			}

			/*主体关联设置*/
			// 清空主体关联
			if( $mainBodyEmpty ) {

				$hasRecord = $model->getCategoryMainBodys( $id );

				if( !empty($hasRecord) ) {

					$relModel = new \VirgoModel\ArchiveCategoryToMainBodyModel;

					$rs = $relModel->setArchiveMainBodyEmpty( $id );

					if( !$rs ) {
						throw new \Exception("清空关联主体失败", '003');
					}

				}

			} else {

				if( !is_null($mainArr) ) {
					$mainBodys = [];
					for ($i=0; $i < count($mainArr); $i++) { 

						$mainBody = (int)$mainArr[$i];
						if( !$mainBody ) {
							continue;
						}

						$temp['main_body_type_id'] = $mainBody;
						$temp['archive_template_category_id'] = $id;
						$temp['create_time'] = time();
						$temp['update_time'] = time();

						$mainBodys[] = $temp;
						unset($temp);

					}

					if( !empty($mainBodys) ) {

						$relModel = new \VirgoModel\ArchiveCategoryToMainBodyModel;

						$hasRecord = $model->getCategoryMainBodys( $id );

						if( !empty($hasRecord) ) {

							$rs = $relModel->setArchiveMainBodyEmpty( $id );

							if( !$rs ) {
								throw new \Exception("清空关联主体失败", '003');
							}

						}

						$rs = $relModel->multipleCreate($mainBodys);
						if( !$rs ) {
						 	throw new \Exception("设置主体关联失败", '005'); 
						}

					}

				}

			}
			/*主体关联设置--end*/
			DB::commit();
			$return = $this->functionObj->toAppJson(null, '001', '更新模板分类成功', true);

		} catch(\Exception $e) {

			if( isset($isBlock) ) {
				DB::rollback();
			}
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}
