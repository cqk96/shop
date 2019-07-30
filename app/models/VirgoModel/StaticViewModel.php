<?php
/**
* model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/ 
namespace VirgoModel;
class StaticViewModel {

	/*
	@param object  reflect this model's  eloquent model object
	*/
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->productObj = new \VirgoModel\ShopProductModel;
	}


	/**
	* 获取静态视图--关于产品详情
	* render the page
	* @author xww
	* @param  [$id]       int/string   shop product id
	* @param  [$force]    bool   	   false
	* @return void
	*/ 
	public function getStaticView($id, $force=false)
	{
		
		// 路劲
		$fPath = $_SERVER['DOCUMENT_ROOT']."/../app/views/runtime/".md5($id).".html";

		// 过期时间/s
		$defaultTime = 1*60*60;

		if(!file_exists($fPath) || $force || $defaultTime<(time()-fileatime($fPath))){
			$this->createStaticView($id, $fPath, '/detail?id='.$id);
		}

		require $fPath;

		// 清除缓存
		clearstatcache();

		return true;

	}

	/**
	* 生成缓存
	* @author xww
	* @return [$fpath]    文件路径
	*/ 
	public function createStaticView($id, $fPath, $goUrl)
	{
		ob_start();
		$product = 1;

		$data = \EloquentModel\ShopProduct::where("is_deleted", '=', 0)
										  ->where("is_hidden", '=', 0)
										  ->where("status_id", '=', 1)
										  ->find($id);

		// 空产品
		if(empty($data)) {echo "<h1>Empty</h1>";return true;}

		// 如果内容有图片则将内容的图片按延迟加载方式进行替换
		// @todo
		if(!empty($data['detail'])){
			preg_match_all("/<img.*?src=\"(.*?)\".*?\/>/", $data['detail'], $matches);
			if(!empty($matches)){
				for ($i=0; $i < count($matches[0]); $i++) { 
					// lazy组合
					$relpaceStr = " class='lazy' data-original='".$matches[1][$i]."' src=\"/images/sucai/grey.gif\" ";
					$data['detail'] = str_replace("src=\"".$matches[1][$i]."\"", $relpaceStr, $data['detail']);
				}
			}

			// 由于用户会加入文本  所以仍然需要先用正则方式取代，然后给所有p加上字体
			$textFormat = "/(.*?)px;/i";
			preg_match_all($textFormat, $data['detail'], $textMatches);
			if(!empty($textMatches)){
				for ($i=0; $i < count($textMatches[1]); $i++) {
					$nameValueArr = explode(':', $textMatches[1][$i]);
					
					// 有可能value值对应多个
					$manyValue = explode('px', $nameValueArr[count($nameValueArr)-1]);

					for ($j=0; $j < count($manyValue); $j++) { 
						$removeEmpty = trim($manyValue[$j]);
						$intValue = (int)$removeEmpty;
						$divideOneHundred = number_format($intValue/100, 2, '.', '');
						$manyValue[$j] = $divideOneHundred;
					}

					// 重组value
					$valueStr = implode('rem ', $manyValue);

					$originStr = $textMatches[0][$i];
					$nameValueArr[count($nameValueArr)-1] = $valueStr."rem;";
					$replaceStr = implode(':', $nameValueArr);
					$data['detail'] = str_replace($originStr, $replaceStr, $data['detail']);
				}
			}
			// 替换所有p 增加字号
			$data['detail'] = str_replace("<p>", "<p style='font-size: 0.24rem;'>", $data['detail']);
		}

		$images = empty($data['images'])? [$data['cover']]:unserialize($data['images']);

		//表连接
		// $queryObj = \EloquentModel\ProductRelAttribute::leftJoin("shop_products", 'shop_products.id', '=', 'product_rel_attribute.product_id')
		// 								  ->leftJoin("sub_attribute_class", 'sub_attribute_class.id', '=', 'product_rel_attribute.sub_attribute_id')
		// 								  ->leftJoin("attribute_class", 'attribute_class.id', '=', 'sub_attribute_class.attribute_id')
		// 								  ->where("shop_products.id", '=', $id);
		$queryObj = \EloquentModel\Sku::leftJoin("sku_rel_attributes", 'sku_rel_attributes.sku_id', '=', 'skus.id')
										  ->leftJoin("sub_attribute_class", 'sub_attribute_class.id', '=', 'sku_rel_attributes.sub_attribute_id')
										  ->leftJoin("attribute_class", 'attribute_class.id', '=', 'sub_attribute_class.attribute_id')
										  ->where("skus.product_id", '=', $id)
										  ->where("skus.is_deleted", '=',0);

		//获取商品属性的大分类
		$classNames = $queryObj->select(['attribute_class.name', 'attribute_class.id'])->orderBy("attribute_class.create_time", 'asc')->groupBy("attribute_class.name", 'attribute_class.id')->get(['attribute_class.name'])->toArray();

		// 包含了大属性分类与子分类
		if(!empty($classNames)){

			// 存储大类名
			$attributeArr = [];
			for ($i=0; $i < count($classNames); $i++) { 
				if($i<4){
					$i<3? array_push($attributeArr, $classNames[$i]['name']):array_push($attributeArr, $classNames[$i]['name'].'等');
				}
				$classNames[$i]['subAttributes'] = [];
				$rs = \EloquentModel\Sku::leftJoin("sku_rel_attributes", 'sku_rel_attributes.sku_id', '=', 'skus.id')
										  ->leftJoin("sub_attribute_class", 'sub_attribute_class.id', '=', 'sku_rel_attributes.sub_attribute_id')
										  ->leftJoin("attribute_class", 'attribute_class.id', '=', 'sub_attribute_class.attribute_id')
										  ->where("skus.product_id", '=', $id)
										  ->where("skus.is_deleted", '=',0)
										  ->where("attribute_class.id", '=', $classNames[$i]['id'])
										  ->select(['skus.*', 'sub_attribute_class.name', 'sub_attribute_class.id as subId'])
										  ->get()->toArray();
				
				// 记录分类名和id是否已经存在
				$temp = [];
				// 只需要id,和分类名
				foreach ($rs as $key => $value) {
					if(!in_array($value['subId'], $temp)){
						array_push($classNames[$i]['subAttributes'], $value);
						array_push($temp, $value['subId']);
					}
				}

				unset($key);
				unset($value);
			}
			
		}

		//有登录情况

		// 购物车数量
		$cartCount = 0;
		// 是否已经收藏
		$isCollected = false;
		if(!empty($_COOKIE['user_login']) && !empty($_COOKIE['access_token'])){
			$user = \EloquentModel\User::where("user_login", '=', $_COOKIE['user_login'])
									->where("access_token", '=', $_COOKIE['access_token'])
									->where("is_deleted", '=', 0)
									->take(1)
									->get()
									->toArray();

			if(!empty($user)){
				// 查询购物车
				$shopCartModelObj = new \VirgoModel\ShopCartModel;
				$cartCount = $shopCartModelObj->getUserShopCartCount($user[0]['id']);

				// 检测是否已经收藏
				$has_collect = \EloquentModel\CollectProduct::where("user_id", '=', $user[0]['id'])
										  ->where("product_id", '=', $id)
										  ->where("is_deleted", '=', 0)
										  ->count();
				$isCollected = empty($has_collect)? false:true;
			}


		}

		// 计算多少人已购买--
		$peopleArr = \EloquentModel\WechatOrder::leftJoin("product_to_orders", 'product_to_orders.order_id', '=', 'wechat_orders.id')
									->leftJoin("skus", 'skus.id', '=', 'product_to_orders.product_id')
									->where("state", '=', 1)
									->where("skus.product_id", '=', $id)
									->select("userid")
									->groupBy("userid")
									->get()
									->toArray();

		$extraPeopleCount = empty($data['inveracious_people_count'])? 0:$data['inveracious_people_count'];
		$data['people_count'] = count($peopleArr)+$extraPeopleCount;

		// 产品的售价为第一个sku的售价
		$firstPriceArr = \EloquentModel\Sku::where("product_id", '=', $id)
											->where("is_deleted", '=',0)
											->orderBy("id", 'asc')
											->take(1)
											->get()
											->toArray();
		$data['sale_price'] = empty($firstPriceArr)? '':number_format($firstPriceArr[0]['price']/100,2,'.','');

		//产品的库存为第一个sku的库存
		$data['inventory'] = empty($firstPriceArr)? '':$firstPriceArr[0]['inventory'];

		// 记录第一个关联属性的sku
		$skuFunc = function()use($id,$firstPriceArr){

			// 没有第一条
			if(empty($firstPriceArr)) {return [];}

			// 查询sku
			$attrs = \EloquentModel\Sku::leftJoin("sku_rel_attributes", 'sku_rel_attributes.sku_id', '=', 'skus.id')
						  ->where("product_id", '=', $id)
						  ->where("skus.id", '=', $firstPriceArr[0]['id'])
						  ->select("sub_attribute_id", 'price', 'inventory', 'sku_id')
						  ->get()
						  ->toArray();

			$positionArr = [];
			$ids = [];
			foreach ($attrs as $attr) {
				// 是否已有此键值数组
				if(!in_array($attr['sku_id'], $positionArr)){
					// 没有
					array_push($positionArr, $attr['sku_id']);
					$curPosArr = array_keys($positionArr, $attr['sku_id']);
					$ids[] = $attr['sub_attribute_id'];
				} else {
					// 已有
					$curPosArr = array_keys($positionArr, $attr['sku_id']);
					$ids[] = $attr['sub_attribute_id'];
				}

			}

			// 查询sku end
			return $ids;

		};

		$data['skus'] = $skuFunc();

		// 显示评论列表
		$comments = $this->productObj->getProductComments($id);
		for ($i=0; $i < count($comments); $i++) {
			$comments[$i]['showName'] = empty($comments[$i]['nickname'])? substr_replace($comments[$i]['user_login'], '****', 3,4):$comments[$i]['nickname'];
			$comments[$i]['avatar'] = empty($comments[$i]['avatar'])? '/images/sucai/avatar.png':$comments[$i]['avatar'];
			$comments[$i]['pics'] = [];
			if(!empty($comments[$i]['images'])){
				$tempImages = unserialize($comments[$i]['images']);
				for ($j=0; $j < count($tempImages); $j++) { 
					array_push($comments[$i]['pics'], $tempImages[$j]);
				}
			}
		}
		// end 评论

		// @todo 获取与当前商品所含推荐类似的商品
		$recommends = $this->productObj->getRecommendProducts($id);
		// end;

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
        $str4 = "&url=http://www.hzhanghuan.com/detail?id=".$id;
        
        $strTotal = $str1.$str2.$str3.$str4;
        $signature = sha1($strTotal);
		// 微信支付--end

		// 封面
		$cover = empty($data['cover'])? '':"http://".$_SERVER['HTTP_HOST'].$data['cover'];

		require_once dirname(__FILE__).'/../../views/front/shop/detail.html';
		$fileStr = ob_get_clean();
		file_put_contents($fPath, $fileStr);
	}

}