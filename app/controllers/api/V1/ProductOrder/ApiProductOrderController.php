<?php
namespace VirgoApi\V1\ProductOrder;
use Illuminate\Database\Capsule\Manager as DB;
use VirgoApi;
class ApiProductOrderController extends VirgoApi\ApiBaseController
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
		$this->_model = new \EloquentModel\ProductOrder;

		$this->ProductOrderModel = new  \VirgoModel\ProductOrderModel;
	}

	/**
	* @SWG\Get(path="/api/v1/productOrder/lists", tags={"ProductOrder"}, 
	*  summary="获取订单列表",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="search", type="array", required=false, in="query", description="查询是一个二维数组", @SWG\Items(ref="#/definitions/ProductOrderSearchList") ),
	*  @SWG\Parameter(name="createTimeOrder", type="string", required=false, in="query", description="下单时间 排序 desc 降序asc 升序"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "ProductOrderListsObj",  "code": "001", "message": "获取订单列表成功", "totalCount": 3 } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/ProductOrderListsObj"
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
			$model = new \VirgoModel\ProductOrderModel;

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

			$searches = empty($this->_configs['search'])? null:$this->_configs['search'];
			$createTimeOrder = empty($this->_configs['createTimeOrder'])? null:$this->_configs['createTimeOrder'];
			$youhuashi = empty($this->_configs['youhuashi'])? null:$this->_configs['youhuashi'];
			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$params['searches'] = $searches;
			$params['skip'] = $skip;
			$params['size'] = $size;
			$params['youhuashi'] = $youhuashi;

			$pageObj = $model->getListsObject($params, $searches, $createTimeOrder);

			$data = empty($pageObj->data)? null:$pageObj->data;
			$totalCount = $pageObj->totalCount;

			$return = $this->functionObj->toLayuiJson($data, '001', '获取订单列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}


	/**
	* 增加
	* @SWG\Post(path="/api/v1/productOrder/create", tags={"ProductOrder"}, 
	*  summary="创建订单",
	*  description=" 通过传入的对应参数来创建订单",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="userName", type="string", required=true, in="formData", description="下单人姓名"),
	*  @SWG\Parameter(name="email", type="string", required=true, in="formData", description="邮箱地址 长度最大30", maxLength=30),
	*  @SWG\Parameter(name="productId", type="integer", required=true, in="formData", description="购买的商品id"),
	*  @SWG\Parameter(name="phone", type="string", required=true, in="formData", description="下单人手机号 长度上限20", maxLength=20),
	*  @SWG\Parameter(name="street", type="string", required=true, in="formData", description="详细地址"),
	*  @SWG\Parameter(name="amounts", type="integer", required=true, in="formData", description="购买总件数"),
	*  @SWG\Parameter(name="setmealsIds", type="string", required=true, in="formData", description="对应套餐or属性 关联表中id 以,分隔的字符串"),
	*  @SWG\Parameter(name="postcode", type="string", required=false, in="formData", description="邮编 长度上限20", maxLength=20),
	*  @SWG\Parameter(name="erp_id", type="string", required=false, in="formData", description="erp_id"),
	*  @SWG\Parameter(name="province", type="string", required=false, in="formData", description="省 长度上限10", maxLength=10),
	*  @SWG\Parameter(name="city", type="string", required=false, in="formData", description="市 长度上限10", maxLength=10),
	*  @SWG\Parameter(name="district", type="string", required=false, in="formData", description="次级市/区 长度上限10", maxLength=10),
	*  @SWG\Parameter(name="remarks", type="string", required=false, in="formData", description="留言"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "新建订单成功", "success": true } } }
	*  )
	* )
	* 创建订单
	* @author 	xww
	* @return 	json
	*/
	public function create()
	{

		try {

			//验证 
			// $user = $this->getUserApi($this->_configs);

			// $uid = $user[0]['id'];

			// 实例化对象
			// $userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\ProductOrderModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			// $hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 2]);

			// if( !$hasPrivilige ) {
			// 	// 没有权限提示
			// 	throw new \Exception("没有登录权限和增加数据权限", '070');
			// }

			//验证
			$this->configValid('required',$this->_configs,['userName', 'productId', 'phone', 'street', 'amounts']);

			$model->createProductOrder( $this->_configs );

			$resultArr = $model->getResult();

			$return = $this->functionObj->toAppJson($resultArr['data'], $resultArr['code'], $resultArr['message'], $resultArr['result']);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson($createData, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}
			
	}

	/**
	* 详情
	* @SWG\Get(path="/api/v1/productOrder/read", tags={"ProductOrder"}, 
	*  summary="详情",
	*  description="用户鉴权后 通过传入的id获取记录详情",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="记录id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "ProductOrderDetail", "status": { "code": "001", "message": "订单数据详情查询成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/ProductOrderDetail"
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
			$model = new \VirgoModel\ProductOrderModel;

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

			$data = $model->readDetail($id);

			if( empty($data) ) {
				throw new \Exception("订单数据不存在或已删除", '006');
			}

			$data['setmeal_json'] = json_decode($data['setmeal_json'], true);

			$return = $this->functionObj->toAppJson($data, '001', '订单详情查询成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/productOrder/updateStatus", tags={"ProductOrder"}, 
	*  summary="更新订单状态",
	*  description="用户鉴权后 通过传入的对应参数来创建订单",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="记录id"),
	*  @SWG\Parameter(name="statusId", type="integer", required=true, in="formData", description="订单状态id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "修改订单状态成功", "success": true } } }
	*  )
	* )
	* 更新订单状态
	* @author 	xww
	* @return 	json
	*/
	public function updateStatus()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\ProductOrderModel;

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
			$this->configValid('required',$this->_configs,['id', 'state']);

			$id = $this->_configs['id'];
			$state = $this->_configs['state'];

			$data = $model->read( $id );

			if( empty($data) ) {
				throw new \Exception("订单数据不存在或已删除", '006');
			}

			$updateData['state'] = $state;
			$updateData['update_time'] = time();

			$rs = $model->partUpdate($id, $updateData);

			if( !$rs ) {
				throw new \Exception("修改订单状态失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '修改订单状态成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/productOrder/updateExpressInfo", tags={"ProductOrder"}, 
	*  summary="更新订单物流信息",
	*  description="用户鉴权后 通过传入的对应参数来创建订单",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="记录id"),
	*  @SWG\Parameter(name="expressName", type="string", required=true, in="formData", description="快递名称"),
	*  @SWG\Parameter(name="expressCode", type="string", required=true, in="formData", description="快递编码"),
	*  @SWG\Parameter(name="expressNumber", type="string", required=true, in="formData", description="快递单号"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "修改订单物流信息成功", "success": true } } }
	*  )
	* )
	* 更新订单物流信息
	* @author 	xww
	* @return 	json
	*/
	public function updateExpressInfo()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\ProductOrderModel;

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
			$this->configValid('required',$this->_configs,['id', 'expressName', 'expressCode', "expressNumber"]);

			$id = $this->_configs['id'];
			$expressName = $this->_configs['expressName'];
			$expressCode = $this->_configs['expressCode'];
			$expressNumber = $this->_configs['expressNumber'];

			$data = $model->read( $id );

			if( empty($data) ) {
				throw new \Exception("订单数据不存在或已删除", '006');
			}

			$updateData['express_name'] = $expressName;
			$updateData['express_code'] = $expressCode;
			$updateData['express_number'] = $expressNumber;
			$updateData['update_time'] = time();

			$rs = $model->partUpdate($id, $updateData);

			if( !$rs ) {
				throw new \Exception("修改订单物流信息失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '修改订单物流信息成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 获取订单物流信息
	* @SWG\Get(path="/api/v1/productOrder/expressTraceInfo", tags={"ProductOrder"}, 
	*  summary="跟踪订单物流信息",
	*  description="用户鉴权后 通过传入的id获取记录详情",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="记录id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "ProductOrderExpressLists", "status": { "code": "001", "message": "订单物流详情查询成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/ProductOrderExpressLists")
	*   )
	*  )
	* )
	* @author 	xww
	* @return 	json
	*/
	public function expressTraceInfo()
	{

		try {

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\ProductOrderModel;

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
				throw new \Exception("订单数据不存在或已删除", '006');
			}

			if( empty( $data['express_name'] ) || empty( $data['express_code'] ) || empty( $data['express_number'] ) ) {
				throw new \Exception("没有对应的物流数据", '006');	
			}

			$globalConfigs = $GLOBALS['globalConfigs'];

			if( empty($globalConfigs['expressBird']) ) {
				throw new \Exception("服务端尚未配置对应配置文件", '006');
			}

			if( empty($globalConfigs['expressBird']['EBusinessID']) || empty($globalConfigs['expressBird']['AppKey']) ) {
				throw new \Exception("服务端尚未配置对应配置文件", '006');
			}

			$config['EBusinessID'] = $globalConfigs['expressBird']['EBusinessID'];
			$config['AppKey'] = $globalConfigs['expressBird']['AppKey'];

			/*查询物流详情*/
			$traceModel = new \VirgoModel\ExpressBirdExpress($config['EBusinessID'], $config['AppKey']);
			$traceModel->setSearchTraceParam($data['express_code'], $data['express_number']);

			$traceDataStr = $traceModel->getTraceInfo();

			$traceData = json_decode($traceDataStr, true);

			// var_dump($traceData);
			// die;

			if( $traceData['Success']===false ) {
				throw new \Exception("没有对应的物流数据", '006');		
			}

			$data = $traceData['Traces'];

			$data = array_reverse( $data );

			$return = $this->functionObj->toAppJson($data, '001', '订单物流详情查询成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	
	public function exportOrderExcel()
	{
			// Create new PHPExcel object
			$objPHPExcel = new \PHPExcel();

	// $query = \EloquentModel\ProductOrder::leftJoin("product_order_info", 'product_order_info.order_id', '=', 'product_order.id')
	// 						  ->orderBy("create_time", "desc");
				
			$objPHPExcel->getProperties()->setCreator("ctos")
			        ->setLastModifiedBy("ctos")
			        ->setTitle("Office 2007 XLSX Test Document")
			        ->setSubject("Office 2007 XLSX Test Document")
			        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
			        ->setKeywords("office 2007 openxml php")
			        ->setCategory("Test result file");

		

		// $data = $query->get()->toArray();




					  
			//set width  
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);

			$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
		
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
			
			//设置行高度  
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);

			$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
			 $objPHPExcel->getActiveSheet()->getStyle('A2:Z2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			//set font size bold  
			$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
			$objPHPExcel->getActiveSheet()->getStyle('A2:Z2')->getFont()->setBold(true);

			$objPHPExcel->getActiveSheet()->getStyle('A2:Z2')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A2:Z2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

			//设置水平居中  
			$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('A:Z')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			//  
			$objPHPExcel->getActiveSheet()->mergeCells('A1:Z1');

			// set table header content  
			$objPHPExcel->setActiveSheetIndex(0)
			        ->setCellValue('A1', '订单数据汇总  时间:' . date('Y-m-d H:i:s'))
			        ->setCellValue('A2', '创建时间')
			        ->setCellValue('B2', '订单号')
			        ->setCellValue('C2', '商品编号')
			       	->setCellValue('D2', '优化师')
			       	->setCellValue('E2', '域名')
			        ->setCellValue('F2', '商品名称')
			        ->setCellValue('G2', '内部名称')
			        ->setCellValue('H2', '属性')
			        ->setCellValue('I2', '属性(中文)')
			        ->setCellValue('J2', '属性(外文)')
			        ->setCellValue('K2', '姓名')
			        ->setCellValue('L2', '电话')
			        ->setCellValue('M2', '省份')
			        ->setCellValue('N2', '城市')	
			        ->setCellValue('O2', '区域')
			        ->setCellValue('P2', '客户地址')
			        ->setCellValue('Q2', '详细地址')				        
			        ->setCellValue('R2', '邮编')
			        ->setCellValue('S2', '购买数量')
			        ->setCellValue('T2', '货币单位')
			        ->setCellValue('U2', '总价')
			        ->setCellValue('V2', '留言')	
			        ->setCellValue('W2', '邮箱')	
			        ->setCellValue('X2', '品类')
					->setCellValue('Y2', '订单状态')
					->setCellValue('Z2', '采购URL');

		// 执行查询
		$query = $this->_model
							->leftJoin("product_order_info", "product_order_info.order_id", "=", "product_order.id")
							->leftJoin("cod_goods", "cod_goods.id", "=", "product_order.product_id")
							->leftJoin("category", "category.id", "=", "cod_goods.category_id")
							->leftJoin("currency_management", "product_order_info.currency_id", "=", "currency_management.id")
						
							->leftJoin("users", "users.id", "=", "product_order_info.author_id")
							->select("product_order.id", 'cod_goods.Purchaseurl','cod_goods.title','cod_goods.catalog','cod_goods.catalog','cod_goods.chinese_title','cod_goods.foreign_title','product_order.remarks','cod_goods.domain_name',"product_order.order_num",DB::raw(" FROM_UNIXTIME( `comp_product_order`.create_time,'%Y-%m-%d %T') as createTime "),'user_name','product_order.email','postcode','cod_goods.erp_id','product_id','product_order.phone','province','city','district','street',DB::raw(" truncate(`comp_product_order`.payable_price/100, 2) as payable_price "),DB::raw(" truncate(`comp_product_order`.paid_price/100, 2) as paid_price "),'amounts','freight','express_name','express_code','express_number','remarks','order_status','order_type','refuse_reason','state','product_order_info.author_id','country_name','currency_management.front_symbol as front_symbol','currency_management.back_symbol as back_symbol','currency_name','chinese_name','foreign_name','setmeal_json','product_name',"category.name as categoryname",'users.name as authorname');

			if ($_GET['youhuashi']=='null') {
				$query =$query;
			}else{
				$query =$query-> where("cod_goods.youhuashi",$_GET['youhuashi']);
			}	 		

			// 开始时间
		if(!empty($_GET['StartTime'])){
			$query = $query->where("product_order.create_time", '>=', strtotime(trim($_GET['StartTime'])));
		}	

		// 截止时间
		if(!empty($_GET['EndTime'])){
			$query = $query->where("product_order.create_time", '<=', strtotime(trim($_GET['EndTime'])));
		}
				
		$data	= $query->get()
					  ->toArray();
					  
		// var_dump($data);
		// die;
			// Miscellaneous glyphs, UTF-8  
		for ($i = 0; $i < count($data) ; $i++) {
					$id=$data[$i]['state'];
				$state = $this->ProductOrderModel ->getstate($id);

				$objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 3), $data[$i]['createTime']);
			    $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 3), $data[$i]['order_num']);
			    $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 3), $data[$i]['erp_id']);
				$objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($i + 3), $data[$i]['authorname']);
			  	$objPHPExcel->getActiveSheet(0)->setCellValue('E' . ($i + 3), $data[$i]['domain_name'].'/'.$data[$i]['catalog']);
			    $objPHPExcel->getActiveSheet(0)->setCellValue('F' . ($i + 3), $data[$i]['foreign_title']);
				$objPHPExcel->getActiveSheet(0)->setCellValue('G' . ($i + 3), $data[$i]['chinese_title']);		
			 //    $objPHPExcel->getActiveSheet(0)->setCellValue('H' . ($i + 3), $data[$i]['street']);
				// $objPHPExcel->getActiveSheet(0)->setCellValue('I' . ($i + 3), $data[$i]['front_symbol'].$data[$i]['payable_price'].$data[$i]['back_symbol']);
				// $objPHPExcel->getActiveSheet(0)->setCellValue('J' . ($i + 3), $data[$i]['front_symbol'].$data[$i]['paid_price'].$data[$i]['back_symbol']);
			    $objPHPExcel->getActiveSheet(0)->setCellValue('K' . ($i + 3), $data[$i]['user_name']);
				$objPHPExcel->getActiveSheet(0)->setCellValue('L' . ($i + 3), $data[$i]['phone']);
				$objPHPExcel->getActiveSheet(0)->setCellValue('M' . ($i + 3),$data[$i]['province'] );				
				$objPHPExcel->getActiveSheet(0)->setCellValue('N' . ($i + 3), $data[$i]['city']);
			    $objPHPExcel->getActiveSheet(0)->setCellValue('O' . ($i + 3), $data[$i]['district']);
				$objPHPExcel->getActiveSheet(0)->setCellValue('P' . ($i + 3), $data[$i]['street']);
				$objPHPExcel->getActiveSheet(0)->setCellValue('Q' . ($i + 3), $data[$i]['province'].$data[$i]['city'].$data[$i]['district'].$data[$i]['street']);
			    $objPHPExcel->getActiveSheet(0)->setCellValue('R' . ($i + 3), $data[$i]['postcode']);
				$objPHPExcel->getActiveSheet(0)->setCellValue('S' . ($i + 3), $data[$i]['amounts']);
				$objPHPExcel->getActiveSheet(0)->setCellValue('T' . ($i + 3), $data[$i]['front_symbol']);
				$objPHPExcel->getActiveSheet(0)->setCellValue('U' . ($i + 3), $data[$i]['paid_price']);
				$objPHPExcel->getActiveSheet(0)->setCellValue('V' . ($i + 3), $data[$i]['remarks']);
				$objPHPExcel->getActiveSheet(0)->setCellValue('W' . ($i + 3), $data[$i]['email']);
				$objPHPExcel->getActiveSheet(0)->setCellValue('X' . ($i + 3), $data[$i]['categoryname']);
				$objPHPExcel->getActiveSheet(0)->setCellValue('Y' . ($i + 3), $state);
				$objPHPExcel->getActiveSheet(0)->setCellValue('Z' . ($i + 3), $data[$i]['Purchaseurl']);
				$setmealjson = $data[$i]['setmeal_json'];

				// if( $setmealjson!="" ) {
				// 	var_dump($setmealjson);
				// 	die;
				// }
				
				$setmealjsonArr = json_decode($setmealjson, true);

				// echo $setmealjson;
				// var_dump( $data );
				$str2 = '';
				$str3 = '';
				$str1 = '';
				 for ($j=0; $j < count( $setmealjsonArr ); $j++) { 

					

					for ($k=0; $k < count( $setmealjsonArr[$j]['properties'] ); $k++) { 
					$str1 .=  $setmealjsonArr[$j]['properties'][$k]['propertiesname'] . "    ";
						
						for ($l=0; $l <count( $setmealjsonArr[$j]['properties'][$k]['groupProperty']) ; $l++) {
						
								
							$str2 .= $setmealjsonArr[$j]['properties'][$k]['groupProperty'][$l]['chinese_name'] ."    ";
						$str3 .=  $setmealjsonArr[$j]['properties'][$k]['groupProperty'][$l]['foreign_name'] . "    ";
							// }
						}
						
					}
					$objPHPExcel->getActiveSheet(0)->setCellValue('H' . ($i + 3), $str1);
					$objPHPExcel->getActiveSheet(0)->setCellValue('I' . ($i + 3), $str2);
					$objPHPExcel->getActiveSheet(0)->setCellValue('J' . ($i + 3), $str3);
				}
			    $objPHPExcel->getActiveSheet()->getRowDimension($i + 3)->setRowHeight(16);
			}
			// Rename sheet  
			$objPHPExcel->getActiveSheet()->setTitle('订单汇总表');


			// Set active sheet index to the first sheet, so Excel opens this as the first sheet  
			$objPHPExcel->setActiveSheetIndex(0);


			// Redirect output to a client’s web browser (Excel5)  
			ob_end_clean();//清除缓冲区,避免乱码
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="订单汇总表(' . date('Ymd-His') . ').xls"');
			header('Cache-Control: max-age=0');

			$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
				}

	public function delete()
	{
		try{
			 //验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\ProductOrderModel;

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

			/*删除订单*/
			$rs = $model->deleteorder($id);

			
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