<?php
namespace VirgoBack;
class AdminOrderController extends AdminBaseController
{
	/**
	* @param  order's virgomodel object
	*/ 
	private $_obj;

	public function __construct()
	{
		parent::__construct();
		$this->_obj = new \VirgoModel\OrderModel;
		parent::isLogin();
	}

	// 获取列表
	public function lists()
	{  
		$page_title = '订单管理';
		$pageObj = $this->_obj->lists();

		// 赋值数据
		$data = $pageObj->data;
		
		//$status = [3=>"待发货", 4=>"已收货",5=>"待收货"];
		foreach ($data as &$singleData) {
			$singleData['name'] = empty($singleData['nickname'])? $singleData['user_login']:$singleData['nickname'];
			// 修改订单状态
			if(!empty($singleData['state'])){
				if($singleData['order_status']==3&&!empty($singleData['express_name']&&!empty($singleData['express_code'])) ) {$singleData['order_status'] = 5;}
			}

		
		}
		require_once dirname(__FILE__).'/../../views/admin/adminOrders/index.php';
	}

	//修改订单页面
	public function update()
	{
		$page_title = '修改订单管理';

		// 数据
		$data = $this->_obj->read($_GET['id']);

		if(!empty($data['state'])){
			if($data['order_status']==3&&!empty($data['express_name']&&!empty($data['express_code'])) ) {$data['order_status'] = 5;}
		}

		$status = [3=>"待发货", 4=>"已收货",5=>"待收货"];

		// 查找对应的货物以及属性
		$data = $this->_obj->orderDetail($data);

		// var_dump($data);
		// die;
		
		foreach ($data['lists'] as &$product) {

			// 查找对应属性名称
			$nameTemp = [];
			$temp = \EloquentModel\Sku::leftJoin("sku_rel_attributes", 'sku_rel_attributes.sku_id', '=', 'skus.id')
							  ->leftJoin("sub_attribute_class", 'sub_attribute_class.id', '=', 'sku_rel_attributes.sub_attribute_id')
							  ->where("skus.id", '=', $product['back_sku_id'])
							  ->select("sub_attribute_class.name")
							  ->get()
							  ->toArray();
							  
			foreach ($temp as $attrName) {
				array_push($nameTemp, $attrName['name']);
			}

			// 无规格
			if(empty($nameTemp[0])) {array_push($nameTemp, '原始规格');}
			$product['attrName'] = implode(',', $nameTemp);

		}

		// 订单修改页面
		require_once dirname(__FILE__).'/../../views/admin/adminOrders/edit.php';
	}

	// 处理修改
	public function doUpdate()
	{
		$page = $_POST['page'];
		$rs = $this->_obj->doUpdate();

		if($rs){$this->showPage(['修改订单成功'],'/admin/orders?page='.$page); }
		else {$this->showPage(['修改订单失败'],'/admin/orders?page='.$page); }
	}

	// 改变订单状态
	public function change()
	{
		
		$id = $_GET['id'];
		switch((int)$_GET['type']){
			case 4:
				$data['order_status'] = 4;
				$rs = \EloquentModel\WechatOrder::where("id", '=', $id)->update($data);
			break;
			case 5:
				// 待收货
				$data['express_name'] = empty($_GET['express_name'])? '未知':$_GET['express_name'];
				$data['express_code'] = empty($_GET['express_code'])? '未知':$_GET['express_code'];
				$rs = \EloquentModel\WechatOrder::where("id", '=', $id)->update($data);
			break;
			default:
				$rs = false;
				$message = "错误请求";
			break;
		}

		if($rs){
			$this->showPage(['ok'],'/admin/orders?page=1'); 
		} else {
			$message = empty($message)? 'fail':$message;
			$this->showPage([$message],'/admin/orders?page=1');
		}
	}

	// 批量导出
	public function batchDownload()
	{
		// var_dump($_GET);
		// var_dump($_POST);

		set_time_limit(0);

		$query = \EloquentModel\WechatOrder::leftJoin("users", 'users.id', '=', 'wechat_orders.userid')
										   ->leftJoin("address_managers", "address_managers.id", '=', "wechat_orders.address_id")
							  ->where('order_status', '<>', 1)
							  ->where('order_status', '<>', 2)
							  ->orderBy("created_at", "desc");

		// 用户过滤
		if(!empty($_POST['userLogin'])){
			$query = $query->where("users.user_login", 'like', '%'.$_POST['userLogin'].'%');
		}

		// 开始时间过滤
		if(!empty($_POST['recordStartTime'])){
			$query = $query->where("wechat_orders.created_at", '>=', strtotime(trim($_POST['recordStartTime'])." 00:00:00"));
		}

		// 截止时间过滤
		if(!empty($_POST['recordEndTime'])){
			$query = $query->where("wechat_orders.created_at", '<=', strtotime(trim($_POST['recordEndTime'])." 23:59:59"));
		}

		// 是否指定
		if(!empty($_GET['ids'])){
			$idsArr = explode(',', $_GET['ids']);
			$query = $query->whereIn("wechat_orders.id", $idsArr);
		}


		// 执行查询
		$data = $query->select("wechat_orders.id", "wechat_orders.order_num", "wechat_orders.state", "users.user_login", "wechat_orders.price", "wechat_orders.remind", "wechat_orders.crc_id", "address_managers.name", "address_managers.phone", "address_managers.province", "address_managers.city", "address_managers.other", "address_managers.address", "order_receive_address", "order_receive_user_name", "order_receive_phone")
					  ->get()
					  ->toArray();
        
		// 调用生成xls

		// Create new PHPExcel object
		$objPHPExcel = new \PHPExcel();

		$objPHPExcel->setActiveSheetIndex(0)
				    ->setCellValue('A1', '订单号')
					->setCellValue('B1', '优惠券')
					->setCellValue('C1', '支付')
					->setCellValue('D1', '金额(元)')
					->setCellValue('E1', '留言')
					->setCellValue('F1', '收货人姓名')
					->setCellValue('G1', '收货人手机')
					->setCellValue('H1', '收货地址')
					->setCellValue('I1', '下单人手机')
					->setCellValue('J1', '产品名称与规格')
					->setCellValue('K1', '产品数量');

		// 循环订单列表
		$j = 0;
		for ($i=0; $i < count($data); $i++) { 
			$data[$i] = $this->_obj->orderDetail($data[$i]);

			// 使用优惠券
			$useCoupons = empty($data[$i]['crc_id'])? '否':'是';

			// 是否支付
			$isPay = empty($data[$i]['state'])? '否':'是';

			// 金额
			$money = empty((int)$data[$i]['price'])? 0:number_format(((int)$data[$i]['price'])/100, 2, '.', '');

			// 留言
			$remind = empty($data[$i]['remind'])? '无':$data[$i]['remind'];

			// 收货人名
			$receiverName = empty($data[$i]['order_receive_user_name'])? $data[$i]['name']:$data[$i]['order_receive_user_name'];

			// 收货人电话
			$receiverPhone = empty($data[$i]['order_receive_phone'])? $data[$i]['phone']:$data[$i]['order_receive_phone'];

			// 收货人地址
			$receiverAddress = empty($data[$i]['order_receive_address'])? $data[$i]['province'].$data[$i]['city'].$data[$i]['other'].$data[$i]['address']:$data[$i]['order_receive_address'];

			// 普通数据填入
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.($i+2+$j), $data[$i]['order_num'])
					->setCellValue('B'.($i+2+$j), $useCoupons)
					->setCellValue('C'.($i+2+$j), $isPay)
					->setCellValue('D'.($i+2+$j), $money)
					->setCellValue('E'.($i+2+$j), $remind)
					->setCellValue('F'.($i+2+$j), $receiverName)
					->setCellValue('G'.($i+2+$j), $receiverPhone)
					->setCellValue('H'.($i+2+$j), $receiverAddress)
					->setCellValue('I'.($i+2+$j), $data[$i]['user_login']);

			foreach ($data[$i]['lists'] as $listKey => &$product) {

				if($listKey!=0){
					$j++;
				}

				// 查找对应属性名称
				$nameTemp = [];
				$temp = \EloquentModel\Sku::leftJoin("sku_rel_attributes", 'sku_rel_attributes.sku_id', '=', 'skus.id')
								  ->leftJoin("sub_attribute_class", 'sub_attribute_class.id', '=', 'sku_rel_attributes.sub_attribute_id')
								  ->where("skus.id", '=', $product['back_sku_id'])
								  ->select("sub_attribute_class.name")
								  ->get()
								  ->toArray();
								  
				foreach ($temp as $attrName) {
					array_push($nameTemp, $attrName['name']);
				}

				// 无规格
				if(empty($nameTemp[0])) {array_push($nameTemp, '原始规格');}
				$product['attrName'] = implode(',', $nameTemp);
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('J'.($i+2+$j), $product['title']." ".$product['attrName'])
							->setCellValue('K'.($i+2+$j), $product['amounts']);
			}

		}// end for orders data

		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(12);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(60);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('I')->setAutoSize(true);
		$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('J')->setAutoSize(true);

		ob_end_clean();
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="订单导出.xls"');
		header('Cache-Control: max-age=0');
		header("charset:utf-8");
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}

}