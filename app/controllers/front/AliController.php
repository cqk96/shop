<?php

namespace VirgoFront;
class AliController extends BaseController {
	
	public $currentMicTime;
	
	public $subject;
	
	public $show_url;
	
	public $base_url = 'https://mapi.alipay.com/gateway.do';
	
	public $service = 'alipay.wap.create.direct.pay.by.user';
	public $partner = '2088611303128807';
	public $_input_charset = 'utf-8';
	public $sign_type = 'MD5';
	public $sign  = '';
	public $notify_url = 'http://www.hzhanghuan.com/api/order/analyzeOrder';
	
	//ä¸šåŠ¡å‚æ•°
	public $out_trade_no = '';
	public $payment_type = 1;
	public $seller_id = '2088611303128807';
	public $total_fee = 0.00;//åŽä¸¤ä½
	public $body = '';
	
	//æ­£å¸¸æ—¶é—´æˆ³
	public $timestamp;
	
	//
	public $key = '14bh5mvq3ux45llx5c6xlljhdl50uxao';
	
	public function __construct()
	{
		// $this->weixinUserObj = new \EloquentModel\WeixinUser;
		$this->userObj = new \EloquentModel\User;
	}
	
	public function buyWithAli()
	{
		
		$access_token = $_GET['access_token']; //获取唯一用户
		$ordernum = $_GET['ordernum'];

		$items = $_GET['ids']; //订单号
		$item_amounts=empty($_GET['amount'])?1:$_GET['amount'];
		$item_types=empty($_GET['type'])?0:$_GET['type'];
		//var_dump($item_amounts,$item_types,$_GET);die;
		if(empty($access_token)){
		
			$entry_url = urlencode('/series/buyWithAli');
			header("Location:/login?access_token=".$access_token."&entry=".$entry_url);
			return false;
		}
		
		if(!empty($_GET['isCart'])){
			//æ‹†åˆ†è´­ç‰©è½¦id 
			$cartIdsTemp = explode(',', $items);
			$cartIdsArr = array();
			foreach($cartIdsTemp as $t_key => $t_value){
				array_push($cartIdsArr, $t_value);
			}
			$item_param=array('item_id','amount','type_id');
			$item_id_temp = \EloquentModel\ShopCart::whereIn("id",$cartIdsArr)->get($item_param)->toArray();
			$items_arr = array();
			$items_amount_arr = array();
			$items_type_arr=array();
			foreach($item_id_temp as $id_t_key => $id_t_val){
				array_push($items_arr, $id_t_val['item_id']);
				array_push($items_amount_arr, $id_t_val['amount']);
				array_push($items_type_arr, $id_t_val['type_id']);
			}
			$items = implode(',',$items_arr);
			$item_amounts=implode(',',$items_amount_arr);
			$item_types=implode(',',$items_type_arr);
			//var_dump($items,$item_amounts,$item_types);
		}
		
		//æŸ¥æ‰¾ç”¨æˆ·id
		$userArr = $this->userObj
					    ->select('id')
			 		    ->where('access_token', '=', $access_token)
						//->where('status', '=', 2)
			 		    ->take(1)
			 		    ->get()
			 		    ->toArray();


		if(empty($userArr)){
			echo json_encode(['code'=>'007','success'=>false,'message'=>'ÓÃ»§²»´æÔÚ»òÃ»ÓÐµÇÂ¼','access_token'=>$access_token]);
			return false;
		}
		
		$uid = $userArr[0]['id'];
		
		$this->currentMicTime = microtime ();
		//ÄÚ²¿¶©µ¥ºÅ
		$micTimeArr = explode(" ",$this->currentMicTime);
		$this->timestamp = $micTimeArr[1];
		$userRand = \EloquentModel\User::find($uid);
		$userArr = $userRand->toArray();
		//$data['access_token'] = $userArr['access_token'];
		$this->out_trade_no = $this->create_order_number($this->timestamp,strtotime($userArr['create_time']));
		
		//´´½¨Ö§¸¶±¦¶©µ¥
		$total_fee = $this->createWechatSeriesOrder($uid,$items,$item_amounts,$item_types);
		//var_dump($total_fee);
		$subject = $this->subject;
		
		$out_trade_no = $this->out_trade_no;
		
		$name ="跨知通购买订单";
		
		//À´ÁÙÊ±ºòµÄ½Ó¿ÚµØÖ·
		$this->show_url = "http://".$_SERVER['HTTP_HOST'].$_GET['inUrl'];
		//var_dump($total_fee);die;
		//å‚æ•°
		//$data['service'] = $this->service;
		//$data['partner'] = $this->partner;
		//$data['_input_charset'] = $this->_input_charset;
		//$data['notify_url'] = $this->notify_url;
		$data['WIDsubject'] = $name;
		//$data['payment_type'] = $this->payment_type;
		//$data['seller_id'] = $this->seller_id;
		$data['WIDtotal_fee'] = $total_fee;
		$data['WIDbody'] = $subject;
		$data['WIDout_trade_no'] = $this->out_trade_no;
		$data['WIDshow_url'] = $this->show_url;
		
		//ä»£ç­¾åå­—ç¬¦ä¸²
		$signStr = $this->getSign($data);
		
		$show_url = $this->show_url;
		
		require dirname(__FILE__).'/../../modules/Ali/alipay.wap.create.direct.pay.by.user-PHP-UTF-8/index.php';
	}

	/**
	* 有订单号的前提下购买
	* render the page
	* @author xww
	* @return void
	*/
	public function buyWithAli2()
	{
		
		ob_clean();
		/*
		传递的参数 inUrl, access_token, orderNum
		*/
		$access_token = empty($_GET['access_token'])? '':$_GET['access_token'];
		$inUrl = empty($_GET['inUrl'])? '':$_GET['inUrl'];
		$orderNum = empty($_GET['orderNum'])? '':$_GET['orderNum'];

		if(empty($access_token)){
			header("Location:/login");
			return false;
		}

		//获取用户
		$userArr = $this->userObj
					    ->select('id')
			 		    ->where('access_token', '=', $access_token)
			 		    ->take(1)
			 		    ->get()
			 		    ->toArray();

		if(empty($userArr)){
			header("Location:/login");
			return false;
		}			
			//直接购买
			$data['WIDsubject'] = '商城购买订单';
			$order = \EloquentModel\WechatOrder::where("order_num", '=',  $_GET['orderNum'])->take(1)->get()->toArray();
			$data['WIDtotal_fee'] = empty($order[0]['price'])? 0:number_format((float)($order[0]['price']/100), 2, '.', '');
			$data['WIDbody'] = '商城购买订单';
			$data['WIDout_trade_no'] = $_GET['orderNum'];
			$data['WIDshow_url'] = "http://".$_SERVER['HTTP_HOST'].$_GET['inUrl'];
			$signStr = $this->getSign($data);
			$show_url = "http://".$_SERVER['HTTP_HOST'].$_GET['inUrl'];

			//extra
			$out_trade_no = $data['WIDout_trade_no'];
			$total_fee = $data['WIDtotal_fee'];
			$name = $data['WIDsubject'];
			$subject = $data['WIDsubject'];

			require_once dirname(__FILE__).'/../../modules/Ali/alipay.wap.create.direct.pay.by.user-PHP-UTF-8/index2.php';
		
	}

	//´´½¨¶©µ¥ºÅ
	public function create_order_number($time,$userRand)
	{
		$functionsObj = new \VirgoUtil\Functions;
		$randStr = $functionsObj->getRandStr(1,5);
		
		$str = '';
		
		$dateStr = date('Ymd', $time);
		
		$secondStr = date('His', $time);
		
		$micTimeArr = explode ( " ",  $this->currentMicTime);  
		$msTime = $micTimeArr[0]*1000;
		$msTimeArr = explode('.', $msTime);
		
		$str = $str.$dateStr.$secondStr.$msTimeArr[0].$randStr.$userRand;
		
		return $str;
		
	}
	
	//ÄÚ²¿´´½¨¶©µ¥
	public function createWechatSeriesOrder($uid,$items,$item_amounts,$item_types)
	{
		
		$count = 0.00;
		$subject = array();
		
		//²éÕÒÉÌÆ·¼Û¸ñ
		$productsObj = new \EloquentModel\Product;
		$continue = false;
		$itemsArr = explode(',',$items);
		$item_amountsArr=explode(',',$item_amounts);
		$item_typesArr=explode(',',$item_types);
		//var_dump($itemsArr,$item_amountsArr,$item_typesArr);die;
		foreach($itemsArr as $key => $val){
			$products = $productsObj->where('is_show', '=', 1)
						    ->where('is_deleted', '=', 0)
						    ->find($val);
			$productOption= \EloquentModel\ProductOption::where('is_deleted', '=', 0)
								->find($item_typesArr[$key]);				
			
			if(!empty($products)) {
				$continue = true;
				$amount=$item_amountsArr[$key];
				array_push($subject,$products['pName']);
				$count = $count+(float)$products['price']*$amount+(float)$productOption['price']*$amount;
				$original_count=$count;
				if(!empty($_GET['code'])){
					
					//½«×´Ì¬¸ÄÎªÒÑÓÃ
					$codeObj=new \VirgoModel\CouponCodeModel;
					$ti=$codeObj->expireCode();//ÊÇ·ñ¹ýÆÚ
					if($ti){
						$rs = $codeObj->verifyCode();//»ñÈ¡ÓÅ»Ý¼Û¸ñ
						if($rs){
							$coupon=0-(float)$rs[0]['price'];
							$count=$count+$coupon;//¸üÐÂ×îÖÕ¼Û¸ñ
							$codeObj->updateCodeState();
						}
					}	
					//·ÀÖ¹ÓÅ»Ý¼õµ½¸ºÊý
					if($count<0){
						$count=$original_count;
					}
				}
				
			}
		}
		
		if(!$continue){
			echo json_encode(['success'=>false, 'message'=>'Ã»ÓÐÖ§¸¶µÄ¶ÔÏó']);
			exit();
		}
		
		//
		
		$count = number_format($count, 2, '.', '');
		$original_count = number_format($original_count, 2, '.', '');
		$order['ordernum'] = $this->out_trade_no;
		$order['userid'] = $uid;
		$order['price'] =  (int)($count*100);//²»ÄÜ´øÓÐÐ¡Êý  µ¥Î»Îª·Ö
		$order['original_price'] = (int)($original_count*100);////²»ÄÜ´øÓÐÐ¡Êý  µ¥Î»Îª·Ö Ô­Ê¼¼Û¸ñ
		$order['created_at'] =  $this->timestamp;
		
		$orderId = \EloquentModel\WechatOrder::insertGetId($order);
		
		foreach($itemsArr as $key_2 => $val_2){
			$products = $productsObj->where('is_show', '=', 1)
						    ->where('is_deleted', '=', 0)
						    ->find($val_2);
							
			if(!empty($products)) {
				//0¿Î³Ì  1ÏµÁÐ
				$ptoData['product_id'] = $val_2;
				$ptoData['type'] = $item_typesArr[$key_2];
				$ptoData['amount'] =$item_amountsArr[$key_2];
				$ptoData['orderid'] = $orderId;
				$ptoData['created_at'] =  $this->timestamp;

				\EloquentModel\ProductToOrder::insert($ptoData);
			}
		}
		
		$this->subject = implode(',',$subject);
		
		//·µ»Ø¶àÉÙÇ®
		return $count;
		
	}
	
	/**
	* 支付宝同步通知地址
	*/ 
	public function returnUrl()
	{
		$alipay_config = $_GET;
		require dirname(__FILE__).'/../../modules/Ali/alipay.wap.create.direct.pay.by.user-PHP-UTF-8/return_url.php';
	}
	
	//签名
	public function getSign($data)
	{
		
		asort($data);
		$newData = array();
		foreach($data as $k =>$v){
			$newData[$k] = $k."=".$v;
		}
		ksort($newData);
		$sign = implode("&", $newData);
		
		$sign = $sign.$this->key;
		
		
		return md5($sign);
		
	}
	
	public function alipayapi()
	{
		require dirname(__FILE__).'/../../modules/Ali/alipay.wap.create.direct.pay.by.user-PHP-UTF-8/alipayapi.php';
	}
	
}