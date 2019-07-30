<?php
namespace VirgoApi\Crop;
use Illuminate\Database\Capsule\Manager as DB;
use VirgoApi;
class ApiCropController extends VirgoApi\ApiBaseController{

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
	* @SWG\Get(path="/api/v1/crop/archive/lists", tags={"Crop"}, 
	*  summary="作物档案列表",
	*  description="作物档案列表 用户鉴定后 通过  page,size获取对应列表",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="作物id"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "ArchiveList", "status": { "code": "001", "message": "获取档案列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/ArchiveList")
	*   )  
	*  )
	* )
	* 作物档案列表
	* @author 	xww
	* @return json
	*/
	public function archiveLists()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			//验证
			$this->configValid('required',$this->_configs,['id', "page", "size"]);

			$id = $this->_configs['id'];

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;


			// 片区模板数据对象
			$model = new \VirgoModel\CropTemplateDataModel;

			$data = $model->getLists($id, $skip, $size);
			$data = empty($data)? null:$data;

			// 构造跳转url
			for ($i=0; $i < count($data); $i++) { 
				$data[$i]['url'] = "http://" . $_SERVER['HTTP_HOST'] . "/front/archive/read?id=" . $data[$i]['id'] . "&dataType=" . $data[$i]['type'];
				unset($data[$i]['create_time']);
				unset($data[$i]['type']);
			}

			$return = $this->functionObj->toAppJson($data, '001', '获取作物档案列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/crop/batchUpload", tags={"Crop"}, 
	*  summary="批量创建作物",
	*  description="用户鉴权后 传入统一模板的作物excel进行批量增加 以file作为文件键名",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="file", type="file", required=true, in="formData", description="表单文件 键名"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "批量上传成功", "success": true } } }
	*  )
	* )
	* 批量上传
	* @author 	xww
	* @return 	json
	*/
	public function batchUpload()
	{
		
		try{

			if( empty($_FILES['file']) || $_FILES['file']['error']!=0 ) {
				throw new \Exception("上传文件空或有误 错误代码：" . $_FILES['file']['error'], '017');
			}

			set_time_limit(0);

			$tmpFile = $_FILES['file']['tmp_name'];
 
			// 打开excel
			$inputFileType = \PHPExcel_IOFactory::identify($tmpFile);
			$objReader = \PHPExcel_IOFactory::createReader($inputFileType);
			$objReader->setReadDataOnly(true);

			$objPHPExcel = $objReader->load( $tmpFile );

			// sheet表名数组
			$sheetNames = $objPHPExcel->getSheetNames();
			

			/*获取所有的sheet数量*/
			$sheetCount = $objPHPExcel->getSheetCount();

			// 错误文件名
			$errFileName = "crop_batch_upload_" . microtime(true);

			$errFile = $errFileName . ".txt";

			$errPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $errFile;

			$fp = fopen($errPath, "a+");

			// 相关对象实例化

			// 用户对象
			$userModel = new \VirgoModel\UserModel;

			// 地块对象
			$acreModel = new \VirgoModel\AcreModel;

			// 片区对象
			$areaModel = new \VirgoModel\AreaModel;

			// 品种对象
			$cropTypeModel = new \VirgoModel\CropTypeModel;

			// 作物对象
			$cropModel = new \VirgoModel\CropModel;

			// 农场id
			$farmId = 1;

			/*循环所有sheet表*/
			/*sheet0 是汇总表 所以跳过*/
			for ($i=1; $i < $sheetCount; $i++) { 
				
				$objsheet = $objPHPExcel->setActiveSheetIndex($i);
				$highestRow =  $objsheet->getHighestRow();//总行数 包括列名
				$highestColumn = $objsheet->getHighestColumn();//总列数 以列号结束	

				// 直接将编号作为地块名
				$curSheetName = (int)$sheetNames[$i];

				$areaCount = 0;

				/*事务单元*/
				try{

					DB::beginTransaction();

					fwrite($fp, "index: " . $i . "开始导入\r\n");

					// 获取对应地块id
					$acre = $acreModel->getAcreFromNameAndFarmId( $farmId, $curSheetName );

					// 品种id
					$cropTypeId = null;

					if( empty($acre) ) {
						$acreId = $acreModel->createAcre($farmId, $curSheetName, 1);
						if( !$acreId ) {
							throw new \Exception("index: " . $i . "创建地块失败", '005');
						}
					} else {
						$acreId = $acre[0]['id'];
					}

					try{

						// 前面两行没有意义进行跳过
						$rowStart = 4;
						for ($row=$rowStart;$row<=$highestRow;$row++) {

							$uids = [];

							$j = 0;
							for ($column='A';$column<=$highestColumn;$column++) {

								$dataSet[$row-$rowStart][$j] = trim($objsheet->getCell($column.$row)->getValue());

								$j++;
							}

							if( empty($dataSet[$row-$rowStart][2]) ) {
								continue;
							}

							// 一个或多个账号
							$names = $dataSet[$row-$rowStart][1];

							if( !empty($names) ) {
								// 格式1
								$namesFormatArr_1 = explode("+", $names);

								$namesArr = [];

								for ($j=0; $j < count($namesFormatArr_1); $j++) { 
									$str = $namesFormatArr_1[$j];
									// 格式2
									for ($k=0; $k < strlen($str); $k++) { 
										$namesArr[] = $str[$k];
									}
								}

								/*由于姓名为缩写 所以进行前缀+缩写方式进行账号注册*/ 
								$prefix = "u_";

								/*遍历获取所有用户*/
								for ($j=0; $j < count($namesArr); $j++) { 
									$username = $prefix . $namesArr[$j];

									// 根据账号获取用户
									$user = $userModel->getRecordByAccount( $username );

									if( empty($user) ) {
										// 新建
										$userId = $userModel->createUser( $username, $namesArr[$j]);

										if( !$userId ) {
											throw new \Exception("index: " . $i . ", 行: " . $row . "创建用户失败", '005');
										}

									} else {
										$userId = $user[0]['id'];
									}

									// 放置数组中
									$uids[] = $userId;

								}

							} else {
								$uids[] = 1;								
							}

							// 品种
							$cropTypeName = $dataSet[$row-$rowStart][4];
							if( !empty($cropTypeName) ) {
								// 更换品种
								$cropType = $cropTypeModel->getRecordWithName( $cropTypeName );

								if( empty($cropType) ) {
									// 创建品种
									$cropTypeId = $cropTypeModel->createCropType( $cropTypeName );
									if( !$cropTypeId ) {
										throw new \Exception("index: " . $i . ", 行: " . $row . "创建品种失败", '005');
									}
								} else {
									$cropTypeId = $cropType[0]['id'];
								}

							}

							if( is_null($cropTypeId) ) {
								throw new \Exception("index: " . $i . ", 行: " . $row . "查询不到品种", '014');
							}

							// 作物数量
							$cropAmount = $dataSet[$row-$rowStart][3];

							// 预补种作物数量
							$waitCropAmount = empty($dataSet[$row-$rowStart][5])? 0:$dataSet[$row-$rowStart][5];

							$cropAmount = $cropAmount+$waitCropAmount;

							if( empty($cropAmount) ) {
								throw new \Exception("index: " . $i . ", 行: " . $row . "作物数量为空", '014');	
							}

							// 地块和片区 将AB这种设置为一个片区
							$acreWithAreaStr = $dataSet[$row-$rowStart][2];
							$acreWithAreaStr = preg_replace("/[a-zA-Z]?/", "", $acreWithAreaStr);
							
							$acreWithAreaArr = explode("-", $acreWithAreaStr);

							$areaNum = $acreWithAreaArr[1];
							$acreNum = $acreWithAreaArr[0];

							// 片区名
							$areaName = $acreWithAreaStr;

							// 查找这个片区
							$area = $areaModel->getAreaWithNameAndAcreId($acreId, $areaName);

							if( empty($area) ) {
								$areaCount++;
								$areaId = $areaModel->createArea($acreId, $areaName, $cropTypeId, $cropAmount, 1, $uids);

								if( !$areaId ) {
									throw new \Exception("index: " . $i . ", 行: " . $row . "创建片区失败", '005');
								}

							} else {

								$areaId = $area[0]['id'];
								// 更新数量
								$rs = $areaModel->increCropAmount($areaId, $cropAmount);
								if( !$rs ) {
									throw new \Exception("index: " . $i . ", 行: " . $row . "增加作物数量失败", '003');		
								}

								// 更新关联人员
								$rs = $areaModel->updateManagers($areaId, $uids);
								if( !$rs ) {
									throw new \Exception("index: " . $i . ", 行: " . $row . "更新关联人员失败", '003');		
								}

							}

							// 增加作物
							$rs = $cropModel->multipleCreateCrop($areaId, $acreNum, $areaNum, $cropAmount);
							if( !$rs ) {
								throw new \Exception("index: " . $i . ", 行: " . $row . "增加作物失败, 失败数量:" . $cropAmount, '005');
							}

							// 此时应该是成功完成了一条记录插入
							fwrite($fp, "index: " . $i . ", 行: " . $row . "增加作物成功, 成功数量:" . $cropAmount . "\r\n");	
						}

					} catch(\Exception $e) {
						$message = $e->getMessage();
						fwrite($fp, $message . "\r\n");
					}

					// var_dump($dataSet);
					// die;

					// 此时要更新地块片区数量
					$rs = $acreModel->updateAreaAmount($acreId, $areaCount);

					if( !$rs ) {
						throw new \Exception("index: " . $i . "更新地块片区数量失败", '005');
					}

					DB::commit();
				} catch(\Exception $e) {
					$message = $e->getMessage();
					DB::rollback();
					fwrite($fp, $message . "\r\n");
				}

			}
			
			fclose($fp);

			$message = file_get_contents( $errPath );
			if( file_exists($errPath) ) {
				unlink($errPath);
			}

			$return = $this->functionObj->toAppJson(null, '001', $message, true);

		} catch(\Exception $e) {

			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);

		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/crop/lists", tags={"Crop"}, 
	*  summary="获取作物管理 作物列表",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="statusId", type="integer", required=false, in="query", description="状态id 0正常1病虫害"),
	*  @SWG\Parameter(name="number", type="string", required=false, in="query", description="作物编号"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data":"CropListsObj", "code": "001", "message": "获取作物列表成功", "totalCount": 14 } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/CropListsObj"
	*   )
	*  )
	* )
	* 获取作物种类列表
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

			// 作物种类对象
			$model = new \VirgoModel\CropModel;

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

			/*状态和编号*/
			$statusId = !isset( $this->_configs['statusId'] ) || $this->_configs['statusId']=="" ? null:$this->_configs['statusId'];
			$number = empty( $this->_configs['number'] )? null:$this->_configs['number'];

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$rs = $model->getListsObject($skip, $size, $number, $statusId);

			$data = empty($rs[0])? null:$rs[0];
			$totalCount = $rs[1];

			$return = $this->functionObj->toLayuiJson($data, '001', '获取作物种类列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/crop/delete", tags={"Crop"}, 
	*  summary="删除作物",
	*  description="用户鉴权后 通过传入的作物ids 进行单个或多个作物删除",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="ids", type="string", required=true, in="formData", description="作物id 以,分隔的字符串"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "删除作物成功", "success": true } } }
	*  )
	* )
	* 删除作物
	* @author　		xww
	* @return 		json
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
			$model = new \VirgoModel\CropModel;

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
				$singleIds = (int)$idsArr[$i];

				if( empty($idsArr) ) {
					continue;
				}

				$ids[] = $singleIds;

			}

			if( empty($ids) ) {
				throw new \Exception("Wrong Param Ids", "014");
			}

			$data['is_deleted'] = 1;
			$data['update_time'] = time();
			$rs = $model->multiplePartUpdate($ids, $data);
			unset($data);

			if( !$rs ) {
				throw new \Exception("删除作物失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '删除作物成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/crop/changeStatus", tags={"Crop"}, 
	*  summary="修改作物状态",
	*  description="用户鉴权后 通过传入的作物记录id, 状态id 更新作物状态信息",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="记录id"),
	*  @SWG\Parameter(name="statusId", type="integer", required=true, in="formData", description="状态 1正常2病虫害"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "修改正常状态成功", "success": true } } }
	*  )
	* )
	* 修改启用状态
	* @author 	xww
	* @return 	json
	*/
	public function changeStatus()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 模板对象
			$model = new \VirgoModel\CropModel;

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
			$statusId = $this->_configs['statusId']==1? 0:1;
			$message = $this->_configs['statusId']==1? "正常状态":"病虫害";

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
	* 获取作物有档案的操作时间
	* @SWG\Get(path="/api/v1/crop/operateTime", tags={"Crop"}, 
	*  summary="获取作物 有档案的操作时间",
	*  description="用户鉴权 传入作物id",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="记录id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data":"CropOperateLists", "code": "001", "message": "获取操作时间状态成功", "totalCount": 14 } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/CropOperateLists"
	*   )
	*  )
	* )
	* @author 	xww
	* @return 	json
	*/
	public function operateTime()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 模板对象
			$model = new \VirgoModel\CropModel;

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

			$data = $model->getCropOpereateDataTime( $id );
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', "获取操作时间状态成功", true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 获取作物有档案的操作时间
	* @SWG\Get(path="/api/v1/crop/operateTime/templates", tags={"Crop"}, 
	*  summary="获取操作时间模板数据",
	*  description="用户鉴权 传入作物id 操作时间",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="记录id"),
	*  @SWG\Parameter(name="dateStr", type="string", required=true, in="query", description="模板操作时间 e.g 2018-08-26"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data":"CropOperateTemplateLists", "code": "001", "message": "获取操作时间状态成功", "totalCount": 14 } },
	*   @SWG\Schema(
	*    type="format",
	*    ref="#/definitions/CropOperateTemplateLists"
	*   )
	*  )
	* )
	* @author 	xww
	* @return 	json
	*/
	public function operateTimeTemplates()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 模板对象
			$model = new \VirgoModel\CropModel;

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
			$this->configValid('required',$this->_configs,['id', 'dateStr']);

			$id = $this->_configs['id'];
			$dateStr = $this->_configs['dateStr'];

			if( !strtotime($dateStr) ) {
				throw new \Exception("Wrong Param dateStr", '014');
			}

			$data = $model->getCropOperateDateTimeTemplates( $id, $dateStr);
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', "获取操作时间模板数据成功", true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}


	/**
	* @SWG\Post(path="/api/v1/crop/update", tags={"Crop"}, 
	*  summary="更新作物",
	*  description="用户鉴权后 通过传入的作物记录id等必要参数来更新作物信息",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="记录id"),
	*  @SWG\Parameter(name="area_id", type="integer", required=true, in="formData", description="片区id"),
	*  @SWG\Parameter(name="planting_time", type="string", required=true, in="formData", description="种植时间 e.g 2018-08-28"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "修改作物成功", "success": true } } }
	*  )
	* )
	* 更新作物
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

			// 模板对象
			$model = new \VirgoModel\CropModel;

			// 片区对象
			$areaModel = new \VirgoModel\AreaModel;

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
			$this->configValid('required',$this->_configs,['id', 'area_id', 'planting_time']);

			$id = $this->_configs['id'];
			$area_id = $this->_configs['area_id'];
			$planting_time = $this->_configs['planting_time'];

			// 查询数据
			$data = $model->readSingleTon($id);

			if( empty($data) ) {
				throw new \Exception("数据可能不存在或已删除", '006');	
			}

			$areaData = $areaModel->readSingleTon( $area_id );
			if( empty($areaData) ) {
				throw new \Exception("无法查询到地块", '006');	
			}

			if( !empty($planting_time) && strtotime($planting_time . " 00:00:00") ) {
				$planting_time = strtotime($planting_time . " 00:00:00");
			} else if( !empty($planting_time) ) {
				throw new \Exception("Wrong Param planting_time", '014');					
			}

			$updateData['area_id'] = $area_id;
			$updateData['planting_time'] = $planting_time;
			$updateData['update_time'] = time();

			$rs = $model->partUpdate($id, $updateData);

			if( !$rs ) {
				throw new \Exception("修改作物失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '修改作物成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}