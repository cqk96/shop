<?php
namespace VirgoApi\ArchiveTemplate;
use VirgoApi;
use Illuminate\Database\Capsule\Manager as DB;
class ApiArchiveTemplateController extends VirgoApi\ApiBaseController{

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
	* @SWG\Get(path="/api/v1/archiveTemplate/lists", tags={"ArchiveTemplate"}, 
	*  summary="获取档案管理 档案列表",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="name", type="string", required=false, in="query", description="模板名称"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "ArchiveTemplateListsObj", "code": "001", "message": "获取模板列表成功", "totalCount": 3 } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/ArchiveTemplateListsObj")
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

			// 模板对象
			$model = new \VirgoModel\ArchiveTemplateModel;

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

			$pageObj = $model->getListsObject($skip, $size, $name);

			$data = [];

			$data = empty($pageObj->data)? null:$pageObj->data;
			// $data['totalPage'] = intval( ceil($pageObj->totalCount/$pageObj->size) );
			// $data['currentPage'] = intval( $pageObj->current_page );
			$totalCount = $pageObj->totalCount;

			$return = $this->functionObj->toLayuiJson($data, '001', '获取模板列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/archiveTemplate/create", tags={"ArchiveTemplate"}, 
	*  summary="增加模板",
	*  description="用户鉴权后 通过传入模板名称、代码、模板数据模型来新增模板",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="模板名称"),
	*  @SWG\Parameter(name="code", type="string", required=true, in="formData", description="pc端编辑代码字符串"),
	*  @SWG\Parameter(name="modelData", type="string", required=true, in="formData", description="结构json字符串"),
	*  @SWG\Parameter(name="mainBodys", type="string", required=false, in="formData", description="关联的分类id以,分隔"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "创建模板成功", "success": true } } }
	*  )
	* )
	* 增加模板
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

			// 模板对象
			$model = new \VirgoModel\ArchiveTemplateModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 2]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和增加数据权限", '070');
			}

			//验证 code用于后台管理编辑 modelData用于h5内容显示 前端直接传递封装好的单点json对象
			$this->configValid('required',$this->_configs,["name", 'code', "modelData"]);

			DB::beginTransaction();

			$isBlock = true;

			$name = $this->_configs['name'];
			$code = $this->_configs['code'];
			$modelData = html_entity_decode($this->_configs['modelData']);

			$mainBodys = empty($this->_configs['mainBodys'])? null:$this->_configs['mainBodys'];

			$jsonArr = json_decode($modelData, true);

			if( !$jsonArr ) {
				throw new \Exception("modelData is not a valid json string", '014');
			} 

			$types = range(1, 7);
			for ($i=0; $i < count($jsonArr); $i++) { 

				if( empty( $jsonArr[$i]['type'] ) || !in_array($jsonArr[$i]['type'], $types) ) {
					throw new \Exception( $i . " type is not exists", "014");
				}

			}

			$insertData['name'] = $name;
			$insertData['code'] = $code;
			$insertData['model_data'] = $modelData;
			$insertData['create_time'] = time();
			$insertData['update_time'] = time();

			$recordId = $model->create( $insertData );

			if( !$recordId ) {
				throw new \Exception("添加模板失败", '005');
			}

			if( !is_null($mainBodys) ) {

				$relModel = new \VirgoModel\ArchiveCategoryToArchiveModel;

				$idsArr = explode(",", $mainBodys);

				$ids = [];
				for ($i=0; $i < count($idsArr); $i++) { 
					$tid = (int)$idsArr[$i];
					if( !$tid ) {
						continue;
					}

					$temp['archive_template_id'] = $recordId;
					$temp['archive_template_category_id'] = $tid;
					$temp['create_time'] = time();
					$temp['update_time'] = time();

					$ids[] = $temp;
				}

				if( empty($ids) ) {
					throw new \Exception("Wrong Param mainBodys", '014');
				}

				$rs = $relModel->multipleCreate( $ids );
				if( !$rs ) {
					throw new \Exception("创建关联分类失败", '005');
				}
			}

			DB::commit();
			$return = $this->functionObj->toAppJson(null, '001', '创建模板成功', true);

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
	* @SWG\Post(path="/api/v1/archiveTemplate/delete", tags={"ArchiveTemplate"}, 
	*  summary="删除模板",
	*  description="用户鉴权后 通过传入的模板ids 进行模板删除",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="ids", type="string", required=true, in="formData", description="模板ids 以,分隔组成的字符串"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "删除模板成功", "success": true } } }
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

			// 模板对象
			$model = new \VirgoModel\ArchiveTemplateModel;

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
	* @SWG\Get(path="/api/v1/archiveTemplate/read", tags={"ArchiveTemplate"}, 
	*  summary="模板详情",
	*  description="用户鉴权后 通过传入的id获取模板对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="模板分类记录id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "ArchiveTemplate", "status": { "code": "001", "message": "获取模板分类详情成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/ArchiveTemplate"
	*   )
	*  )
	* )
	* 查看
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

			// 模板对象
			$model = new \VirgoModel\ArchiveTemplateModel;

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
				throw new \Exception("模板数据可能不存在或已删除", '006');	
			}

			// if( !empty($data['code']) ) {
			// 	$data['code'] = html_entity_decode($data['code']);
			// }

			$return = $this->functionObj->toAppJson($data, '001', '获取模板详情成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/archiveTemplate/update", tags={"ArchiveTemplate"}, 
	*  summary="更新模板",
	*  description="用户鉴权后 通过传入的模板记录id, 模板名,pc修改字符串,对应的json字符串来更新模板信息",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="模板id"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="模板名称"),
	*  @SWG\Parameter(name="code", type="string", required=true, in="formData", description="pc端编辑代码字符串"),
	*  @SWG\Parameter(name="modelData", type="string", required=true, in="formData", description="结构json字符串"),
	*  @SWG\Parameter(name="mainBodys", type="string", required=false, in="formData", description="关联的分类id以,分隔"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "更新模板成功", "success": true } } }
	*  )
	* )
	* 修改
	* @author 	xww
	* @return   json
	*/
	public function update()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 模板对象
			$model = new \VirgoModel\ArchiveTemplateModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 5]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和修改数据权限", '070');
			}

			//验证 code用于后台管理编辑 modelData用于h5内容显示 前端直接传递封装好的单点json对象
			$this->configValid('required',$this->_configs,["id", "name", 'code', "modelData"]);

			DB::beginTransaction();

			$isBlock = true;

			$id = $this->_configs['id'];
			$name = $this->_configs['name'];
			$code = $this->_configs['code'];
			$modelData = html_entity_decode($this->_configs['modelData']);

			$mainBodys = empty($this->_configs['mainBodys'])? null:$this->_configs['mainBodys'];
			$doEmpty = empty($this->_configs['doEmpty'])? 0:1;

			$jsonArr = json_decode($modelData, true);

			if( !$jsonArr ) {
				throw new \Exception("modelData is not a valid json string", '014');
			} 

			$types = range(1, 7);
			for ($i=0; $i < count($jsonArr); $i++) { 

				if( empty( $jsonArr[$i]['type'] ) || !in_array($jsonArr[$i]['type'], $types) ) {
					throw new \Exception( $i . " type is not exists", "014");
				}

			}

			$updateData['name'] = $name;
			$updateData['code'] = $code;
			$updateData['model_data'] = $modelData;
			$updateData['update_time'] = time();

			$rs = $model->partUpdate($id, $updateData);

			if( !$rs ) {
				throw new \Exception("修改模板失败", '003');
			}

			$relModel = new \VirgoModel\ArchiveCategoryToArchiveModel;
			if( $doEmpty ) {
				$hasRecord = $relModel->getTemplateClasses($id);
				if( !empty($hasRecord) ) {
					$rs = $relModel->hardDeleteTemplateCatoryWithTid($id);
					if( !$rs ) {
						throw new \Exception("删除关联失败", '012');
					}
				}
			} else {
				if( !is_null($mainBodys) ) {

					$hasRecord = $relModel->getTemplateClasses($id);
					if( !empty($hasRecord) ) {
						$rs = $relModel->hardDeleteTemplateCatoryWithTid($id);
						if( !$rs ) {
							throw new \Exception("删除关联失败", '012');
						}
					}

					$idsArr = explode(",", $mainBodys);

					$ids = [];
					for ($i=0; $i < count($idsArr); $i++) { 
						$tid = (int)$idsArr[$i];
						if( !$tid ) {
							continue;
						}

						$temp['archive_template_id'] = $id;
						$temp['archive_template_category_id'] = $tid;
						$temp['create_time'] = time();
						$temp['update_time'] = time();

						$ids[] = $temp;
					}

					if( empty($ids) ) {
						throw new \Exception("Wrong Param mainBodys", '014');
					}

					$rs = $relModel->multipleCreate( $ids );
					if( !$rs ) {
						throw new \Exception("创建关联分类失败", '005');
					}
				}
			}
			

			DB::commit();

			$return = $this->functionObj->toAppJson(null, '001', '更新模板成功', true);

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
	* @SWG\Post(path="/api/v1/archiveTemplate/status/update", tags={"ArchiveTemplate"}, 
	*  summary="修改模板启用状态",
	*  description="用户鉴权后 通过传入的模板记录id, 状态id 更新模板启用信息",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="模板id"),
	*  @SWG\Parameter(name="statusId", type="integer", required=true, in="formData", description="启用状态 1启用2关闭"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "修改关闭状态成功", "success": true } } }
	*  )
	* )
	* 修改启用状态
	* @author 	xww
	* @return 	json
	*/
	public function statusUpdate()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 模板对象
			$model = new \VirgoModel\ArchiveTemplateModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 5]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和修改数据权限", '070');
			}

			//验证 statusId 1启用 2不启用
			$this->configValid('required',$this->_configs,["id", "statusId"]);

			$id = $this->_configs['id'];
			$statusId = $this->_configs['statusId']==1? 1:0;
			$message = $this->_configs['statusId']==1? "启用":"关闭";

			$updateData['status_id'] = $statusId;
			$updateData['update_time'] = time();

			$rs = $model->partUpdate($id, $updateData);

			if( !$rs ) {
				throw new \Exception("修改" . $message . "状态失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', "修改" . $message . "状态成功", true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/archiveTemplate/uploadData", tags={"ArchiveTemplate"}, 
	*  summary="上传档案数据",
	*  description="根据必须要传入的数据 上传模板数据",
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="templateId", type="integer", required=true, in="formData", description="模板id"),
	*  @SWG\Parameter(name="dataType", type="integer", required=true, in="formData", description="类型 1作物2片区"),  
	*  @SWG\Parameter(name="ids", type="string", required=true, in="formData", description="操作对象对应id 以,分隔字符串"), 
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "提交数据成功", "success": true } } }
	*  )
	* )
	* 上传模板数据
	* @author 	xww
	* @return 	json
	*/
	public function uploadData()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 模板对象
			$model = new \VirgoModel\ArchiveTemplateModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 2]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和增加数据权限", '070');
			}

			//验证 模板id， 数据类型 1=>果树/作物 2=>片区
			$this->configValid('required',$this->_configs,["templateId", "dataType", "ids"]);

			// 模板id
			$id = $this->_configs['templateId'];

			$dataType = $this->_configs['dataType'];

			if($dataType==1) {
				$createModel = new \VirgoModel\CropTemplateDataModel;
				$createColumnName = 'crop_id';
			} else {
				$createModel = new \VirgoModel\AreaTemplateDataModel;
				$createColumnName = 'area_id';
			}

			$idsArr = explode(",", $this->_configs['ids']);

			// 查询数据
			$data = $model->readSingleTon($id);

			if( empty($data) ) {
				throw new \Exception("模板数据可能不存在或已删除", '006');	
			}

			$modelData = $data['model_data'];
			$jsonArr = json_decode($modelData, true);

			// 符合格式
			$valueArr = [];
			if( $jsonArr && is_array($jsonArr) ) {

				for ($i=0; $i < count($jsonArr); $i++) { 

					$itemType = $jsonArr[$i]['type'];
					
					$keyName = 'data' . ($i+1);
					if( !empty( $_POST[$keyName] ) ) {

						if( $itemType==3 ) {
							// 图片 重新在服务器上存储 

							$tempArr = [];
							for ($j=0; $j < count($_POST[$keyName]); $j++) { 
								$relativePath = $this->functionObj->saveBase64Img( $_POST[$keyName][$j] );

								if( $relativePath===false ) {
									continue;
								}

								$tempArr[] = $relativePath;

							}

							if( empty($tempArr) ) {
								$valueArr[] = null;		
							} else {
								$valueArr[] = implode("*,*",  $tempArr);
							}

						} else {
							$valueArr[] = implode("*,*", $_POST[$keyName]);	
						}
						
					} else {
						$valueArr[] = null;
					}

				}

			}

			$valueJson = json_encode( $valueArr, JSON_UNESCAPED_UNICODE);

			$createData = [];
			for ($i=0; $i < count($idsArr); $i++) { 
				
				$singleId = (int)$idsArr[$i];
				if( empty($singleId) ) {
					continue;
				}

				$temp[ $createColumnName ] = $singleId;
				$temp['archive_template_id'] = $id;
				$temp['template_data'] = $valueJson;
				$temp['user_id'] = $uid;
				$temp['is_deleted'] = 0;
				$temp['create_time'] = time();
				$temp['update_time'] = time();
				$createData[] = $temp;

			}

			if( empty($createData) ) {
				throw new \Exception("Wrong Param ids", '014');
			}

			$rs = $createModel->multipleCreate($createData);

			if( !$rs ) {
				throw new \Exception("提交数据失败", '005');
			}

			$return = $this->functionObj->toAppJson(null, '001', "提交数据成功", true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/archiveTemplate/inAll", tags={"ArchiveTemplate"}, 
	*  summary="获取所有模板",
	*  description="用户鉴权后 可传入模板分类id来获取 该分类是否拥有这些模板",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=false, in="query", description="模板分类id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "ArchiveTemplateCategoryToArchiveCheckedObj", "status": { "code": "001", "message": "获取所有模板成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/ArchiveTemplateCategoryToArchiveCheckedObj"
	*   )
	*  )
	* )
	* 获取所有模板
	* @author 	xww
	* @return 	json
	*/
	public function inAll()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 模板对象
			$model = new \VirgoModel\ArchiveTemplateModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 4]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和查看数据权限", '070');
			}

			// 分类id
			$id = empty($this->_configs['id'])? null:$this->_configs['id'];

			$data = $model->getArchiveTemplatesInAll($id);
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', "获取所有模板成功", true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}