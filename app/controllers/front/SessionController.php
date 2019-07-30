<?php
/**
* php version 5.5.12
* @author xww <5648*****@qq.com>
* @copyright xww 2016.12.15
* 用户行为控制器
*/
namespace VirgoFront;
use Illuminate\Database\Capsule\Manager as DB;
class SessionController extends BaseController {

	public function __construct()
	{
		parent::change();
		$this->functionObj = new \VirgoUtil\Functions;
		// date_default_timezone_set('Asia/Shanghai'); 
	}

	/**
	* 注册页面
	* render  the page
	* @author xww
	* @return void
	*/
	public function register()
	{		
		
		// 渠道号(每个公众号唯一)
		if(!empty($_GET['channelNum'])){
			setcookie("channelNum", $_GET['channelNum'], time()+60*60*24*10, '/');
		}

		//注册页面
		require_once dirname(__FILE__).'/../../views/front/shop/register.html';
	}

	/**
	* 个人中心
	* render  the page
	* @author xww
	* @return void
	*/
	public function mine()
	{

		// 渠道号(每个公众号唯一)
		if(!empty($_GET['channelNum'])){
			setcookie("channelNum", $_GET['channelNum'], time()+60*60*24*10, '/');
		}

		//过期或者没有登录时
		if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
			//@todo跳转至登录  带上页面地址
			header("Location:/login?url=/mine");
			exit();
		}

		$user = $this->getUser('/mine');

		//用户正常加载个人中心页面
		//数据处理
		$user[0]['showAccount'] = substr_replace($user[0]['user_login'], '****', 3, 4);

		$orderObj = new \VirgoUtil\WechatOrder;

		//获取待付款数量
		$needPayCount = $orderObj->waitForPayCount($user[0]['id']);

		//获取待发货数量
		$needSendCount = $orderObj->waitForSendCount($user[0]['id']);

		//获取待收货数量
		$needReceiveCount = $orderObj->waitForReceiveCount($user[0]['id']);

		//获取待评价数量
		$needCommentCount = $orderObj->waitForCommentCount($user[0]['id']);

		//获取优惠券数量
		$couponModelObj = new \VirgoModel\CouponModel;
		$couponsCount = $couponModelObj->getUserCouponsCountNotUse($user[0]['id']);

		//个人中心页面
		require_once dirname(__FILE__).'/../../views/front/shop/mine.html';	

	}

	/**
	* 个人资料
	* render  the page
	* @author xww
	* @return void
	*/
	public function data()
	{
		
		// 渠道号(每个公众号唯一)
		if(!empty($_GET['channelNum'])){
			setcookie("channelNum", $_GET['channelNum'], time()+60*60*24*10, '/');
		}

		//过期或者没有登录时
		if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
			//@todo跳转至登录 带上页面地址
			//个人中心页面
			header("Location:/login?url=/data");
			exit();
		}

		$user = $this->getUser('/data');

		$gender = [1=>'男', 2=>'女', 3=>'保密'];

		//用户正常加载个人资料页面
		//数据处理
		$user[0]['showAccount'] = substr_replace($user[0]['user_login'], '****', 3, 4);
		$user[0]['showNickname'] = empty($user[0]['nickname'])? 'pmb_'.$user[0]['user_login']:$user[0]['nickname'];
		
		//年份截至
		$yearEnd = date("Y", time());
		$monthEnd = 12;
		$dayEnd = 31;

		$year = '';
		$month = '';
		$day = '';
		if(!empty($user[0]['birthday'])){
			$year = date("Y", $user[0]['birthday']);
			$month = date("m", $user[0]['birthday']);
			$day = date("d", $user[0]['birthday']);
		}

		//头像
		$user[0]['avatar'] = empty($user[0]['avatar'])? '/images/sucai/avatar.png':$user[0]['avatar'];

		//个人资料页面
		require_once dirname(__FILE__).'/../../views/front/shop/data.html';	

	}

	/**
	* 修改密码
	* render the page
	* @author xww
	* @return void
	*/
	public function editPassword()
	{

		// 渠道号(每个公众号唯一)
		if(!empty($_GET['channelNum'])){
			setcookie("channelNum", $_GET['channelNum'], time()+60*60*24*10, '/');
		}

		//过期或者没有登录时
		if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
			//@todo跳转至登录 带上页面地址
			header("Location:/login?url=/edit-password");
			exit();
		}

		$user = $this->getUser('/edit-password');

		//用户正常加载个人资料页面
		//数据处理
		$user[0]['showAccount'] = substr_replace($user[0]['user_login'], '****', 3, 4);

		//修改密码页面
		require_once dirname(__FILE__).'/../../views/front/shop/edit-password.html';	

	}

	/**
	* 登录页面
	* render the page
	* @author xww
	* @return void
	*/
	public function login()
	{
		
		// 渠道号(每个公众号唯一)
		if(!empty($_GET['channelNum'])){
			setcookie("channelNum", $_GET['channelNum'], time()+60*60*24*10, '/');
		}

		//过期或者没有登录时
		if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
			//@todo跳转至登录 带上页面地址
			require_once dirname(__FILE__).'/../../views/front/shop/login.html';
			exit();
		} else {
			$user = $this->getUser('');

			//个人中心页面
			header("Location:/mine");
			//require_once dirname(__FILE__).'/../../views/front/shop/login.html';
		}

	}

	/**
	* 收货地址管理
	* render the page
	* @author xww
	* @return void
	*/
	public function changeAddress()
	{
		
		// 渠道号(每个公众号唯一)
		if(!empty($_GET['channelNum'])){
			setcookie("channelNum", $_GET['channelNum'], time()+60*60*24*10, '/');
		}

		//过期或者没有登录时
		if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
			//@todo跳转至登录 带上页面地址
			header("Location:/login?url=/change-address");
			exit();
		}

		$user = $this->getUser('/change-address');

		//地址列表
		$addressManagerObj = new \VirgoModel\AddressManager;
		$addresses = $addressManagerObj->getUserAddressList($user[0]['id']);

		//当前收获地址页面
		require_once dirname(__FILE__).'/../../views/front/shop/change-address.html';

	}

	/**
	* 创建收货地址
	* render the page
	* @author xww
	* @return void
	*/
	public function createAddress()
	{
		
		// 渠道号(每个公众号唯一)
		if(!empty($_GET['channelNum'])){
			setcookie("channelNum", $_GET['channelNum'], time()+60*60*24*10, '/');
		}

		//过期或者没有登录时
		if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
			//@todo跳转至登录 带上页面地址
			header("Location:/login?url=/create-address");
			exit();
		}

		$user = $this->getUser('/create-address');

		//新建收货地址页面
		require_once dirname(__FILE__).'/../../views/front/shop/create-address.html';

	}

	/**
	* 用户修改地址
	* render the page
	* @author xww
	* @return void
	*/
	public function editAddress()
	{

		// 渠道号(每个公众号唯一)
		if(!empty($_GET['channelNum'])){
			setcookie("channelNum", $_GET['channelNum'], time()+60*60*24*10, '/');
		}

		//过期或者没有登录时
		if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
			//@todo跳转至登录 带上页面地址
			header("Location:/login?url=/edit-address");
			exit();
		}

		if(empty($_GET['id'])){
			echo "<h1>id不为空</h1>";
			return false;
		}

		$id = $_GET['id'];

		$user = $this->getUser('/edit-address');

		//判断是否是自己的记录
		$address = \EloquentModel\AddressManager::find($id);

		if(empty($address)){
			echo "<h1>记录不存在</h1>";
			return false;
		}

		$address = $address->toArray();

		if($address['user_id']!=$user[0]['id']){
			echo "<h1>非法操作</h1>";
			return false;	
		}

		$address['total_address'] = empty($address['other'])? $address['province'].','.$address['city']:$address['province'].','.$address['city'].','.$address['other'];


		//修改收货地址页面
		require_once dirname(__FILE__).'/../../views/front/shop/edit-address.html';

	}

	/**
	* 更换绑定手机号
	* render the page
	* @author xww
	* @return void
	*/
	public function editBindPhone()
	{
		
		// 渠道号(每个公众号唯一)
		if(!empty($_GET['channelNum'])){
			setcookie("channelNum", $_GET['channelNum'], time()+60*60*24*10, '/');
		}

		//过期或者没有登录时
		if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
			//@todo跳转至登录 带上页面地址
			header("Location:/login?url=/edit-bind-phone");
			exit();
		}

		$user = $this->getUser('/edit-bind-phone');

		//新建收货地址页面
		require_once dirname(__FILE__).'/../../views/front/shop/edit-bind-phone.html';
		
	}

	/**
	* 立即购买
	* render the page
	* @author xww
	* @return void
	*/ 
	public function buyRightNow()
	{

		// 渠道号(每个公众号唯一)
		if(!empty($_GET['channelNum'])){
			setcookie("channelNum", $_GET['channelNum'], time()+60*60*24*10, '/');
		}
		
		if(empty($_GET['id'])){
			echo "<h1>没有此种商品</h1>";
			return false;
		}

		//shop_product's virgo model
		$data = \EloquentModel\ShopProduct::where("is_hidden", '=', 0)
										  ->where("is_deleted", '=', 0)
										  ->find($_GET['id']);

		if(empty($data)) { echo "<h1>Empty</h1>"; return false;}
		
		$data = $data->toArray();

		//商品下架
		if(empty($data)){
			echo "<h1>商品已下架</h1>";
			return false;
		}

		//过期或者没有登录时
		if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
			//@todo跳转至登录 带上页面地址
			header("Location:/login?url=".$_SERVER['REQUEST_URI']);
			exit();
		}

		$user = $this->getUser($_SERVER['REQUEST_URI']);

		//获取用户地址
		$address = \EloquentModel\AddressManager::where("user_id", '=', $user[0]['id'])
												->where("is_deleted", '=', 0)
												->where("default_use", '=', 1)
												->get()
												->toArray();

		if(!empty($address)){
			$addressId = $address[0]['id'];
		}

		// 配置微信支付
		$appid = 'wx606608300bf798ff';
		$secret = 'c98bc93f13de7414239ae5f14ee1ad1e';
		$wechatObj = new \VirgoUtil\Wechat($appid, $secret);

		// 调用ticket需要
        $ticket = $wechatObj->getJsapiTicket();
        $time = time();
        $random = '123456';
		
        $str1 = "jsapi_ticket=".$ticket;
        $str2 = "&noncestr=".$random;
        $str3 = "&timestamp=".$time;
        $str4 = "&url=http://www.hzhanghuan.com/buy-right-now";
        $strTotal = $str1.$str2.$str3.$str4;
        $signature = sha1($strTotal);
		// 微信支付--end

		// 默认属性

		// 数量
		$amounts = 1;

		//价格
		$totalPrice = 0;//$data['sale_price'];

		// 商品子属性字符串
		$subNameStr = '';

		// 默认属性--end

		// 解析立即购买cookie
		if(!empty($_COOKIE['products'])){
			$productsArr = unserialize($_COOKIE['products']);
			foreach ($productsArr as $pid => $idsArr) {
				if($pid==$_GET['id']){

					// 更新数量
					$amounts = $idsArr['amounts'];

					// 附加属性价格
					// $extraMoney = \EloquentModel\ProductRelAttribute::whereIn("id", $idsArr['attributes'])->sum("price");

					// 更新总价
					// $totalPrice = $totalPrice + $extraMoney;

					if(!empty($idsArr['sku_id'])){
						$curSku = \EloquentModel\Sku::find($idsArr['sku_id']);
						if(!empty($curSku)){
							$totalPrice = $curSku['price'];
							$data['inventory'] = $curSku['inventory'];
							$data['price'] = $curSku['price'];
						}
					}
					
					$subNameArr = '';
					if(!empty($idsArr['sku_id'])){
						//获取商品属性小分类
						$subNameArr = \EloquentModel\Sku::leftJoin("sku_rel_attributes", 'skus.id', '=', 'sku_rel_attributes.sku_id')
														->leftJoin("sub_attribute_class", 'sub_attribute_class.id', '=', 'sku_rel_attributes.sub_attribute_id')
											            ->where("skus.product_id", '=', $_GET['id'])
											            ->where("skus.id", '=', $idsArr['sku_id'])
											            ->select('sub_attribute_class.name')
											            ->distinct()
											            ->get()
											            ->toArray();
					}
					
					if(!empty($subNameArr)){
						$subNameStr = '';
						for ($i=0; $i <count($subNameArr) ; $i++) { 
							$subNameStr = $subNameStr.' '.$subNameArr[$i]['name'];
						}
					}

				}
			}
		}

		// 适合此时的最大减免优惠券
		// $coupon = $this->getMaxDecreaseCoupon($_GET['id'], $user[0]['id'], $totalPrice);
		//$coupon = [];
		$coupon = $this->getMaxDecreaseCouponForBuyRightNowOne($idsArr['sku_id'], $user[0]['id']);

		require_once dirname(__FILE__).'/../../views/front/shop/buy-right-now.html';

	}


	/**
	* 获取用户
	* @author xww
	* @param  url 用户未注册时跳转地址
	* @return array or void(render the page)
	*/
	public function getUser($url)
	{

		//获取用户信息
		$user = \EloquentModel\User::where("user_login", '=', $_COOKIE['user_login'])
									->where("access_token", '=', $_COOKIE['access_token'])
									->where("is_deleted", '=', 0)
									->take(1)
									->get()
									->toArray();
		
		//用户被删除
		if(empty($user)){
			//进入注册
			setcookie("user_login", '', time()-1, '/');
			setcookie("access_token", '', time()-1, '/');
			header("Location:/register?url=".$url);
			exit();
		}

		// 额外判断过期 过期则重新登录
		if($user[0]['token_expire_time']<=time()){
			if(!empty($_COOKIE['user_login']) && !empty($_COOKIE['access_token'])){
				setcookie("user_login", '', time()-1, '/');
				setcookie("access_token", '', time()-1, '/');
				//验证码
				setcookie("restTime", '', time()-1, '/');
				header("Location:/login?url=".$url);
			}
		}

		return $user;

	}

	/**
	* 商城购物车页
	* render the page
	* @author xww
	* @return void
	*/ 
	public function shopCart()
	{
		
		// 渠道号(每个公众号唯一)
		if(!empty($_GET['channelNum'])){
			setcookie("channelNum", $_GET['channelNum'], time()+60*60*24*10, '/');
		}

		//过期或者没有登录时
		if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
			//@todo跳转至登录 带上页面地址
			header("Location:/login?url=/shop-cart");
			exit();
		}

		$user = $this->getUser('/shop-cart');

		$shopCartModleObj = new \VirgoModel\ShopCartModel;

		$carts = $shopCartModleObj->getUserShopCarts($user[0]['id']);

		// 初始展现的首次统计金额
		$totalPrice = 0;
		// var_dump($carts);
		// die;
		if(!empty($carts)){
			// 根据ids获取属性name
			foreach ($carts as $key => &$singleCart) {

				$singleCart['attributeStr'] = '无';
				if(!empty($singleCart['pra_ids'])){
					$nameArr = \EloquentModel\Sku::leftJoin("sku_rel_attributes", 'sku_rel_attributes.sku_id', '=','skus.id')
												  ->leftJoin("sub_attribute_class", 'sub_attribute_class.id', '=', 'sku_rel_attributes.sub_attribute_id')
												  ->whereIn("skus.id", explode(',', $singleCart['pra_ids']))
												  ->where("skus.is_deleted", '=', 0)
												  ->select("sub_attribute_class.name")
												  ->get()
											      ->toArray();
					
					$nameStr = '';
					// $extraMoney =  \EloquentModel\ProductRelAttribute::whereIn("id", explode(',', $singleCart['pra_ids']))
					// 							  ->sum("price");

					foreach ($nameArr as $name_key => $singleName) {
						$nameStr = $nameStr.' '.$singleName['name'];
					}

					$productTemp = \EloquentModel\ShopProduct::find($singleCart['product_id']);

					$skuTemp = \EloquentModel\Sku::find($singleCart['pra_ids']);

					$singleCart['attributeStr'] = $nameStr;
					$singleCart['cover'] = $productTemp['cover'];
					$singleCart['title'] = $productTemp['title'];
					$singleCart['price'] = empty($skuTemp['price'])? 0:$skuTemp['price']; //$productTemp['sale_price'];
					$singleCart['inventory'] = empty($skuTemp['inventory'])? 0:$skuTemp['inventory'];
					$singleCart['extra_pirce'] = 0;
					
					// 计算有库存的购物车的商品总价
					if(!empty($singleCart['inventory'])){
						$totalPrice = $totalPrice+($singleCart['price']+$singleCart['extra_pirce'])*$singleCart['amounts'];
					}
					
				}

			}
			
		}

		// 配置微信支付
		$appid = 'wx606608300bf798ff';
		$secret = 'c98bc93f13de7414239ae5f14ee1ad1e';
		$wechatObj = new \VirgoUtil\Wechat($appid, $secret);

		// 调用ticket需要
        $ticket = $wechatObj->getJsapiTicket();
        $time = time();
        $random = '123456';
		
        $str1 = "jsapi_ticket=".$ticket;
        $str2 = "&noncestr=".$random;
        $str3 = "&timestamp=".$time;
        $str4 = "&url=http://www.hzhanghuan.com/shop-cart";
        $strTotal = $str1.$str2.$str3.$str4;
        $signature = sha1($strTotal);
		// 微信支付--end

		//获取用户地址
		$address = \EloquentModel\AddressManager::where("user_id", '=', $user[0]['id'])
												->where("is_deleted", '=', 0)
												->where("default_use", '=', 1)
												->get()
												->toArray();

		if(!empty($address)){
			$addressId = $address[0]['id'];
		}

		$coupon = [];

		//购物车页
		require_once dirname(__FILE__).'/../../views/front/shop/shop-cart.html';

	}

	/**
	* 商城我的订单页
	* render the page
	* @author xww
	* @return void
	*/ 
	public function orders()
	{
		
		// 渠道号(每个公众号唯一)
		if(!empty($_GET['channelNum'])){
			setcookie("channelNum", $_GET['channelNum'], time()+60*60*24*10, '/');
		}

		//过期或者没有登录时
		if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
			//@todo跳转至登录 带上页面地址
			header("Location:/login?url=/orders");
			exit();
		}

		$user = $this->getUser('/orders');

		// 订单对象
		$ordersObj = new \VirgoUtil\WechatOrder;

		// 获取所有待付款订单
		$needPayOrders = $ordersObj->getWaitForPayOrders($user[0]['id']);
		
		// 获取所有待发货订单
		$needSendOrders = $ordersObj->getWaitForSendOrders($user[0]['id']);

		// 获取所有待收货订单
		$needReceiveOrders = $ordersObj->getWaitForReceiveOrders($user[0]['id']);

		// 获取所有待评价订单
		$needCommentOrders = $ordersObj->getWaitForCommentOrders($user[0]['id']);
		
		// 获取所有已付款已评价订单
		$doneOrders = $ordersObj->getDoneOrders($user[0]['id']);
		
		// 配置微信支付
		$appid = 'wx606608300bf798ff';
		$secret = 'c98bc93f13de7414239ae5f14ee1ad1e';
		$wechatObj = new \VirgoUtil\Wechat($appid, $secret);

		// 调用ticket需要
        $ticket = $wechatObj->getJsapiTicket();
        $time = time();
        $random = '123456';
		
        $str1 = "jsapi_ticket=".$ticket;
        $str2 = "&noncestr=".$random;
        $str3 = "&timestamp=".$time;
        $str4 = "&url=http://www.hzhanghuan.com/orders";
        $strTotal = $str1.$str2.$str3.$str4;
        $signature = sha1($strTotal);
		// 微信支付--end
		
		//我的订单页
		require_once dirname(__FILE__).'/../../views/front/shop/orders.html';

	}

	/**
	* 商城我的优惠券页
	* render the page
	* @author xww
	* @return void
	*/ 
	public function coupons()
	{
		
		// 渠道号(每个公众号唯一)
		if(!empty($_GET['channelNum'])){
			setcookie("channelNum", $_GET['channelNum'], time()+60*60*24*10, '/');
		}

		//过期或者没有登录时
		if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
			//@todo跳转至登录 带上页面地址
			header("Location:/login?url=/coupons");
			exit();
		}

		$user = $this->getUser('/coupons');

		
		$couponModelObj = new \VirgoModel\CouponModel;

		//获取未使用优惠券
		$couponsNotUse = $couponModelObj->getUserNotUseCoupons($user[0]['id']);
		
		//获取已过期优惠券
		$couponsOverdue = $couponModelObj->getUserOverdueCoupons($user[0]['id']);

		//获取已使用优惠券
		$couponsUse = $couponModelObj->getUserUseCoupons($user[0]['id']);

		//我的优惠券页
		require_once dirname(__FILE__).'/../../views/front/shop/coupons.html';
	}

	/**
	* 商城我的收藏页
	* render the page
	* @author xww
	* @return void
	*/ 
	public function collect()
	{
		
		// 渠道号(每个公众号唯一)
		if(!empty($_GET['channelNum'])){
			setcookie("channelNum", $_GET['channelNum'], time()+60*60*24*10, '/');
		}

		//过期或者没有登录时
		if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
			//@todo跳转至登录 带上页面地址
			header("Location:/login?url=/collect");
			exit();
		}

		$user = $this->getUser('/collect');

		$collectProductModelObj = new \VirgoModel\CollectProductModel;
		$products = $collectProductModelObj->getUserCollectProducts($user[0]['id']);

		//我的收藏页
		require_once dirname(__FILE__).'/../../views/front/shop/collect.html';

	}

	/**
	* 商城评价页--评价订单  商品分开评价
	* render the page
	* @author xww
	* @return void
	*/ 
	public function comment()
	{
		
		// 渠道号(每个公众号唯一)
		if(!empty($_GET['channelNum'])){
			setcookie("channelNum", $_GET['channelNum'], time()+60*60*24*10, '/');
		}

		//过期或者没有登录时
		if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
			//@todo跳转至登录 带上页面地址
			header("Location:/login?url=/comment");
			exit();
		}

		$user = $this->getUser('/comment');

		$id = empty($_GET['id'])? 0:$_GET['id'];
		$order = \EloquentModel\WechatOrder::where("id", '=', $id)
								  ->where("userid", '=', $user[0]['id'])
								  ->get()
								  ->toArray();

		// 非匹配订单
		if(empty($order)) {echo "<h1>Empty</h1>"; return false;}

		// 获取订单中用户所购买的对应商品
		$orderModel = new \VirgoModel\OrderModel;
		$products = $orderModel->getOrderProducts($order[0]['order_num'],$id);

		//商城评价页
		require_once dirname(__FILE__).'/../../views/front/shop/comment.html';
		
	}

	/**
	* 商城联系客服页
	* render the page
	* @author xww
	* @return void
	*/ 
	public function customServer()
	{
		
		// 渠道号(每个公众号唯一)
		if(!empty($_GET['channelNum'])){
			setcookie("channelNum", $_GET['channelNum'], time()+60*60*24*10, '/');
		}

		//过期或者没有登录时
		if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
			//@todo跳转至登录 带上页面地址
			header("Location:/login?url=/custom-server");
			exit();
		}

		$user = $this->getUser('/custom-server');

		// 客服对象
		$customObj = new \VirgoModel\CustomServerModel;

		// 是否今日有过对话
		$has_talk = false;

		// 消息列表
		$messages = $customObj->getUserChatMessages($user[0]['id'],$has_talk);
		
		// 客服id 写死管理员
		$cid = 1;

		// 客服信息
		$customer = \EloquentModel\User::find($cid);
		//客服头像
		$cAvatar = empty($customer['avatar'])? '/images/sucai/kf-avatar.png':$customer['avatar'];

		// 用户头像
		$uAvatar = empty($user[0]['avatar'])? '/images/sucai/user-avatar.png':$user[0]['avatar'];

		//商城联系客服页
		require_once dirname(__FILE__).'/../../views/front/shop/custom-server.html';

	}

	/**
	* 商城物流详情页
	* render the page
	* @author xww
	* @return void
	*/ 
	public function expressDetail()
	{
		// 渠道号(每个公众号唯一)
		if(!empty($_GET['channelNum'])){
			setcookie("channelNum", $_GET['channelNum'], time()+60*60*24*10, '/');
		}

		try {

			// 判断用户是否登录
			if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
				//@todo跳转至登录 带上页面地址
				header("Location:/login?url=/express-detail");
				exit();
			}

			if(empty($_GET['orderNum'])){throw new \Exception("Wrong Request"); }

			// 获取用户
			$user = $this->getUser('/express-detail');

			// 订单对象
			$orderObj = new \EloquentModel\WechatOrder;

			$orderDetail = $orderObj->where("order_num", '=', $_GET['orderNum'])
					 ->where("userid", '=', $user[0]['id'])
					 ->take(1)
					 ->get()
					 ->toArray();

			if(empty($orderDetail)){throw new \Exception("Invalid Order num"); }

			// 赋值数据
			$data = $orderDetail[0];

			// 获取商品件数
			$count = \EloquentModel\ProductToOrder::leftJoin("wechat_orders", 'product_to_orders.order_id', '=', 'wechat_orders.id')
			                                      ->leftJoin("skus", 'skus.id', '=','product_to_orders.product_id')
												  ->where("wechat_orders.id", '=', $data['id'])
												  ->select("product_to_orders.product_id")
												  ->groupBy('product_to_orders.product_id')
												  ->get()
												  ->toArray();
			$count = count($count);

			// 获取首个商品
			$productArr = \EloquentModel\WechatOrder::leftJoin("product_to_orders", 'product_to_orders.order_id', '=', 'wechat_orders.id')
												  ->leftJoin("skus", 'skus.id', '=','product_to_orders.product_id')
												  ->leftJoin("shop_products", 'shop_products.id', '=', 'skus.product_id')
												  ->where("wechat_orders.id", '=', $data['id'])
												  ->take(1)
												  ->get()
												  ->toArray();
			
			// 获取第一个组图 
			$pic = '';
			if(!empty($productArr)){
				if(!empty($productArr[0]['images'])){
					$serializeStr = unserialize($productArr[0]['images']);
					$pic = $serializeStr[0];
				}
			}

			$expressCode = '';
			if(!empty($data['express_name'])){
				$expressCode = $this->getExpressCompanyCode($data['express_name']);
			}

			// 物流页
			require_once dirname(__FILE__).'/../../views/front/shop/express-detail.html';

		} catch(\Exception $e) {
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

	/**
	* 获取此时能用最大减免优惠券数组应只有一条记录
	* 且该优惠券不能过期
	* @author xww
	* @param  $pid   		int/string   product's id
	* @param  $uid   		int/string   user's id
	* @param  $totalPrice   int/string   total price
	* @return array
	*/ 
	public function getMaxDecreaseCoupon($pid, $uid, $totalPrice)
	{
		
		// 获取在此类与全场分类下该用户拥有的尚未使用的优惠券
		$couponsQuery = \EloquentModel\UserRelCoupons::leftJoin("coupons_rel_class", 'coupons_rel_class.coupon_id', '=', 'user_rel_coupons.coupon_id')
												->where("user_rel_coupons.user_id", '=', $uid)
												->where("is_used", '=', 0)
												->where("coupons_rel_class.is_deleted", '=', 0)
												->select("coupons_rel_class.*");

		// 此时专区id数组
		$zoneArr = \EloquentModel\ShopProduct::leftJoin("shop_product_class_rel_shop_product", 'shop_product_class_rel_shop_product.product_id', '=', 'shop_products.id')
								  ->leftJoin("shop_product_class", 'shop_product_class.id', '=', 'shop_product_class_rel_shop_product.spc_id')
								  ->where("shop_products.id", '=', $pid)
								  ->take(1)
								  ->get(['shop_product_class_rel_shop_product.spc_id'])
								  ->toArray();

		// 该商品有分类
		if(!empty($zoneArr)){
			$zoneId = $zoneArr[0]['spc_id'];

			$couponsQuery = $couponsQuery->where(function($query)use($zoneId){
				$query->orWhere("class_id", '=', 0)
					  ->orWhere("class_id", '=', $zoneId);	
			});
		} else {
			$couponsQuery = $couponsQuery->where("class_id", '=', 0);
		}

		// 所有的优惠券
		$coupons = $couponsQuery->get()->toArray();

		// 当前能减免的额度
		$curDescreasePrice = 0;

		// 当前数组键值
		$curKey = 0;

		if(!empty($coupons)){
			foreach ($coupons as $key => $coupon) {

				// 判断优惠券是否过期
				if(($coupon['useful_time_end']-$coupon['useful_time_start'])<=0 || $coupon['useful_time_end']<time()){continue;}

				// 判断当前额度是否满足最小上限
				if($coupon['upper_limit']>$totalPrice) {continue;}

				// 判断是否比当前减免额度大
				if($coupon['decrease_price']>$curDescreasePrice){$curDescreasePrice=$coupon['decrease_price'];$curKey=$key;}

			}

			// 是否有可以使用的优惠券
			return empty($curDescreasePrice)? []:$coupons[$curKey];

		} else {
			return [];
		}

	}

	/**
	* 退货页面
	* render the page
	* @author xww
	* @return void
	*/ 
	public function salesReturn()
	{
		
		try {

			if(empty($_GET['orderId'])) { throw new \Exception("Wrong Param"); }

			if(empty($_GET['skuId'])) { throw new \Exception("Wrong Param"); }

			// 退款页面
			require_once dirname(__FILE__).'/../../views/front/shop/salesReturn.html';
		} catch(\Exception $e) {
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

	// 通过物流公司获取公司代码
	public function getExpressCompanyCode($name)
	{
		
		$nameArr = ['顺丰速运','申通快递', '圆通速递', '中通快递', '百世快递', '韵达速递', '天天快递','邮政平邮/小包', 'EMS'];
		$codeArr = ['SF','STO', 'YTO', 'ZTO', 'BTWL', 'YD', 'HHTT', 'YZPY', 'EMS'];
		$code = '';
		for ($i=0; $i < count($nameArr); $i++) { 
			if(stripos($nameArr[$i], $name)!==false){
				$code = $codeArr[$i];
				break;
			}	
		}

		return $code;

	}

	// 活动函数（发放优惠券）
	public function activity()
	{
		
		date_default_timezone_set("PRC");

		// 判断是否登录 无则登录
		if(empty($_COOKIE['user_login']) || empty($_COOKIE['access_token'])){
			//@todo跳转至登录 带上页面地址
			header("Location:/login");
			exit();
		}

		// 获取用户
		$user = $this->getUser('/index');

		// 用cookie判断优惠券活动
		$activeFlag = empty($_COOKIE['activeFlag'])? 1:$_COOKIE['activeFlag'];

		$rs = false;
		$messages = "领取失败";
		if(!empty($activeFlag)){
			$globalConfigs = $GLOBALS['globalConfigs'];
			// 满(分)
			$upperLimit = $globalConfigs['activeFirst']['upperLimit'];

			// 减少(分)
			$decreasePrice = $globalConfigs['activeFirst']['decreasePrice'];

			// 活动时间具体商量好后进行替换
			$startTime = $globalConfigs['activeFirst']['startTime'];
			$endTime = $globalConfigs['activeFirst']['endTime'];

			// 优惠券有效时间
			$usefulTimeStart = $globalConfigs['activeFirst']['usefulTimeStart'];
			$usefulTimeEnd = $globalConfigs['activeFirst']['usefulTimeEnd'];
			// 判断是否是当前要做的活动
			if($activeFlag==1){
				// 此时还要判断是否是在活动中
				if(strtotime($startTime) <= time() && time() <=strtotime($endTime)){
					// 判断该用户是否已经领取改活动的优惠券
					// 当前为第一次活动 所以相应字段为1
					DB::beginTransaction();
					$hasTake = \EloquentModel\UserRelCoupons::where("active_flag", 1)
												 ->where("user_id", $user[0]['id'])
												 ->count();
					$curTime = time();
					if($hasTake==0){
						// 锁表
						\EloquentModel\Coupons::sharedLock()->get();
						// 生成优惠券code
						$ok = true;
						// 设定长度16位
						$length = 16;
						while ($ok) {
							$code = $this->functionObj->getRandStr(4,$length);
							if(!\EloquentModel\Coupons::where("code", '=', $code)->count()){
								$ok = false;
							}
						}

						// 生成优惠券
						$couponId = \EloquentModel\Coupons::insertGetId(['code'=>$code, "create_time"=>$curTime, 'update_time'=>$curTime]);
						if(!$couponId){
							error_log("rs:1-".((int)$couponId), 3, $_SERVER['DOCUMENT_ROOT'].'/'.microtime(true).'.txt');
							DB::rollback();
						} else {

							// 优惠券关联
							$crcData['coupon_id'] = $couponId;
							$crcData['class_id'] = 0;
							$crcData['upper_limit'] = $upperLimit;
							$crcData['decrease_price'] = $decreasePrice;
							$crcData['is_used'] = 0;
							$crcData['useful_time_start'] = strtotime($usefulTimeStart);
							$crcData['useful_time_end'] = strtotime($usefulTimeEnd);
							$crcData['create_time'] = $curTime;
							$crcData['update_time'] = $curTime;
							$crcRs = \EloquentModel\CouponsRelClass::insert($crcData);
							error_log($usefulTimeStart, 3, $_SERVER['DOCUMENT_ROOT'].'/'.microtime(true).'.txt');
							if(!$crcRs){
								error_log($crcData['useful_time_start'], 3, $_SERVER['DOCUMENT_ROOT'].'/'.microtime(true).'.txt');
								DB::rollback();
							} else {
								// 优惠券用户关联
								$urcData['coupon_id'] = $couponId;
								$urcData['user_id'] = $user[0]['id'];
								$urcData['active_flag'] = 1;
								$urcData['create_time'] = $curTime;
								$urcRs = \EloquentModel\UserRelCoupons::insert($urcData);
								if(!$urcRs){
									error_log("rs:3-".((int)$crcRs), 3, $_SERVER['DOCUMENT_ROOT'].'/'.microtime(true).'.txt');
									DB::rollback();
								} else {
									// error_log("rs:4-ok", 3, $_SERVER['DOCUMENT_ROOT'].'/'.microtime(true).'.txt');
									DB::commit();
									$rs = true;
									$messages = "领取成功";
								}
							}

						}

					} else {
						$messages = "已领取";
					}

				} else {
					$messages = "活动已过期或未开始";
				}

			}
		}

		// 用cookie判断优惠券活动--end
		header("Refresh:3;url=/coupons");
		echo "<!DOCTYPE><html><meta name='viewport' content='user-scalable=no, width=device-width, initial-scale=1.0' /><body style='background-color:#64a4fd;position:relative'>";
        echo $messages;
        echo "</body></html>";

	}

	/**
	* 第一个10元活动 立即购买优惠券   金额范围于50-100之间
	*/ 
	public function getMaxDecreaseCouponForBuyRightNowOne($skuId, $uid)
	{

		// 获取全场分类下该用户拥有的尚未使用的优惠券
		$couponsQuery = \EloquentModel\UserRelCoupons::leftJoin("coupons_rel_class", 'coupons_rel_class.coupon_id', '=', 'user_rel_coupons.coupon_id')
												->where("user_rel_coupons.user_id", '=', $uid)
												->where("is_used", '=', 0)
												->where("active_flag", 1)
												->where("coupons_rel_class.is_deleted", '=', 0)
												->where("coupons_rel_class.class_id", 0)
												->select("coupons_rel_class.*");

		$curSku = \EloquentModel\Sku::find($skuId);

		// 所有的优惠券
		$coupons = $couponsQuery->get()->toArray();

		// 当前能减免的额度
		$curDescreasePrice = 0;

		// 当前数组键值
		$curKey = 0;

		if(!empty($coupons)){

			if(empty($curSku)){
				return [];
			}

			// 判断金额范围
			if($curSku['price']<6600){
				return [];	
			}

			$totalPrice = $curSku['price'];

			foreach ($coupons as $key => $coupon) {

				// 判断优惠券是否过期
				if(($coupon['useful_time_end']-$coupon['useful_time_start'])<=0 || $coupon['useful_time_end']<time()){continue;}

				// 判断当前额度是否满足最小上限
				if($coupon['upper_limit']>$totalPrice) {continue;}

				// 判断是否比当前减免额度大
				if($coupon['decrease_price']>$curDescreasePrice){$curDescreasePrice=$coupon['decrease_price'];$curKey=$key;}

			}

			// 是否有可以使用的优惠券
			return empty($curDescreasePrice)? []:$coupons[$curKey];

		} else {
			return [];
		}

	}

}