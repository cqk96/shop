<?php
namespace VirgoApi\Cod;
use Illuminate\Database\Capsule\Manager as DB;
use GuzzleHttp\Client;
use VirgoApi;
class ApiCodController extends VirgoApi\ApiBaseController{

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
	* @SWG\Post(path="/api/v1/Cod/goods", tags={"Cod"}, 
	*  summary="创建商品",
	*  description="用户鉴权后 通过传入的商品名称，中文名，外文名，价格售价等创建商品如果存在同名，商品新建会失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="title", type="string", required=true, in="formData", description="商品名称"),
	*  @SWG\Parameter(name="chinese_title", type="string", required=true, in="formData", description="中文名"),
	*  @SWG\Parameter(name="foreign_title", type="string", required=true, in="formData", description="外文名"),
	*  @SWG\Parameter(name="price", type="string", required=true, in="formData", description="价格"),
	*  @SWG\Parameter(name="Original_Price", type="integer", required=true, in="formData", description="原价"),
	*  @SWG\Parameter(name="discount", type="integer", required=true, in="formData", description="折扣"),
	*  @SWG\Parameter(name="rush_time", type="string", required=false, in="formData", description="剩余抢购时间"),
	*  @SWG\Parameter(name="Purchaseurl", type="string", required=false, in="formData", description="采购URL"),
	*  @SWG\Parameter(name="rate", type="string", required=false, in="formData", description="好评率"),
	*  @SWG\Parameter(name="language", type="integer", required=true, in="formData", description="语言 1简体中文2繁体中文3越南语4泰语5菲律宾语6印尼语7柬埔寨语8英语"),
	*  @SWG\Parameter(name="country", type="integer", required=true, in="formData", description="国家 1中国台湾2越南3新加坡4泰国5柬埔寨6马来西亚7印度尼西亚8菲律宾"),
	*  @SWG\Parameter(name="domain_name", type="string", required=false, in="formData", description="域名"),
	*  @SWG\Parameter(name="sales", type="string", required=false, in="formData", description="销量"),
	*  @SWG\Parameter(name="Inventory", type="string", required=false, in="formData", description="库存"),
	*  @SWG\Parameter(name="email", type="string", required=false, in="formData", description="联系邮箱"),
	*  @SWG\Parameter(name="LINE", type="string", required=false, in="formData", description="LINE"),
	*  @SWG\Parameter(name="pop800id", type="string", required=false, in="formData", description="pop800id"),
	*  @SWG\Parameter(name="Fb_Id", type="string", required=false, in="formData", description="FB通用像素id"),
	*  @SWG\Parameter(name="readme", type="string", required=false, in="formData", description="购买提示"),
	*  @SWG\Parameter(name="content", type="string", required=false, in="formData", description="内容"),
	*  @SWG\Parameter(name="logo", type="string", required=false, in="formData", description="logo"),
	*  @SWG\Parameter(name="thumbnail", type="string", required=false, in="formData", description="缩略图"),
	*  @SWG\Parameter(name="images", type="string", required=false, in="formData", description="图集"),
	*  @SWG\Parameter(name="properties", type="string", required=false, in="formData", description="属性"),
	*  @SWG\Parameter(name="setmeal", type="string", required=false, in="formData", description="套餐"),
	*  @SWG\Parameter(name="labels", type="string", required=false, in="formData", description="标签"),
	*  @SWG\Parameter(name="currency_id", type="string", required=false, in="formData", description="币种"),
	*  @SWG\Parameter(name="content", type="string", required=false, in="formData", description="内容"),
	*  @SWG\Parameter(name="setmealJsonStr", type="string", required=false, in="formData", description="套餐属性"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "创建商品成功", "success": true } } }
	*  )
	* )
	* 创建片区
	* @author 	xww
	* @return 	json
	*/
	public function goods()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 商品对象
			$codModel = new \VirgoModel\CodModel;
			// 属性对象
			$propertiesModel = new \VirgoModel\PropertiesModel;
			// 菜单对象
			$setmealModel = new \VirgoModel\SetmealModel;
			// 菜单属性商品对象
			$GoodsToSetmealToPropertiesModel = new \VirgoModel\GoodsToSetmealToPropertiesModel;

		
			
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
			$this->configValid('required',$this->_configs,['title',	"foreign_title", "chinese_title", "price", "Original_Price",'discount','language','country','content']);


				

			/*事务*/
			DB::beginTransaction();

			$isBlock = true;

			$title = $this->_configs['title'];
			$chinese_title = $this->_configs['chinese_title'];
			$foreign_title = $this->_configs['foreign_title'];
			$price = $this->_configs['price'];
			$Original_Price = $this->_configs['Original_Price'];
			$discount = $this->_configs['discount'];
			$setmealJsonStr = $this->_configs['setmealJsonStr'];
			$language = $this->_configs['language'];
			$country = $this->_configs['country'];
			$content = $this->_configs['content'];
			$currency_id = empty($this->_configs['currency_id'])? '':$this->_configs['currency_id'];	
			$category_id = empty($this->_configs['category_id'])? '':$this->_configs['category_id'];	
			$remarks = empty($this->_configs['remarks'])? '':$this->_configs['remarks'];
			$thumbnail = empty($this->_configs['thumbnail'])? '':$this->_configs['thumbnail'];
			$Purchaseurl = empty($this->_configs['Purchaseurl'])? '':$this->_configs['Purchaseurl'];
			$rate = empty($this->_configs['rate'])? 0:$this->_configs['rate'];
			$youhuashi = empty($this->_configs['youhuashi'])? 0:$this->_configs['youhuashi'];
			$logo = empty($this->_configs['logo'])? '':$this->_configs['logo'];
			$email = empty($this->_configs['email'])? '':$this->_configs['email'];
			$domain_name = empty($this->_configs['domain_name'])? '':$this->_configs['domain_name'];
			$name = empty($this->_configs['name'])? '':$this->_configs['name'];			
			$readme = empty($this->_configs['readme'])? '':$this->_configs['readme'];
			$Inventory = empty($this->_configs['Inventory'])? 0:$this->_configs['Inventory'];
			$sales = empty($this->_configs['sales'])? 0:$this->_configs['sales'];
			$erp_id = empty($this->_configs['erp_id'])? 0:$this->_configs['erp_id'];
			$LINE = empty($this->_configs['LINE'])? '':$this->_configs['LINE'];
			$pop800id = empty($this->_configs['pop800id'])? '':$this->_configs['pop800id'];
			$Fb_Id = empty($this->_configs['Fb_Id'])? '':$this->_configs['Fb_Id'];
			$template = empty($this->_configs['template'])? '':$this->_configs['template'];
			$catalog = empty($this->_configs['catalog'])? '':$this->_configs['catalog'];
			$status_id = empty($this->_configs['statusId']) || (int)$this->_configs['statusId']<=0? '':(int)$this->_configs['statusId'];

			if( !empty( $this->_configs['isCopy'] ) ) {
				$labels = $_POST['labels'];
				$images = $_POST['images'];

			} else {
				$labels = empty($this->_configs['labels'])? '':$this->_configs['labels'];
				$images = empty($this->_configs['images'])? '':$this->_configs['images'];
			}
				
			// 剩余抢购时间rush_time 格式e.g 2018-08-06
			$rush_time = empty($this->_configs['rush_time'])? 0:$this->_configs['rush_time'];

			// var_dump($Fb_Id);
			// die;
			
			// 名称,价格 0报错
			if( empty($title) || empty($price) ) {
				throw new \Exception("title or price can not be null", '014');
			}
			// // 是否有同名商品创建过
			// $record = $codModel->getName($title,$foreign_title,$chinese_title);
			// if( !empty($record) ) {
			// 	throw new \Exception("已存在同名商品", '026');
			// }
			
			$insertData['title'] = $title;
			$insertData['chinese_title'] = $chinese_title;
			$insertData['foreign_title'] = $foreign_title;
			$insertData['price'] = $price;
			$insertData['Original_Price'] = $Original_Price;
			$insertData['discount'] = $discount;
			$insertData['sales'] = $sales;
			$insertData['Inventory'] = $Inventory;
			$insertData['rush_time'] = $rush_time;
			$insertData['Purchaseurl'] = $Purchaseurl;
			$insertData['readme'] = $readme;
			$insertData['logo'] = $logo;
			$insertData['LINE'] = $LINE;
			$insertData['rate'] = $rate;
			$insertData['youhuashi'] = $uid;
			$insertData['author'] = $uid;
			// 缩略图上传到OSS
			$insertData['thumbnail'] = $thumbnail;
			$insertData['images'] = $images;			
			$insertData['language'] = $language;
			$insertData['country'] = $country;
			$insertData['pop800id'] = $pop800id;
			$insertData['email'] = $email;
			$insertData['Fb_Id'] = $Fb_Id;
			$insertData['erp_id'] = $erp_id;
			$insertData['labels'] = $labels;
			$insertData['content'] = $content;
			$insertData['currency_id'] = $currency_id;
			$insertData['category_id'] = $category_id;
			$insertData['domain_name'] = $domain_name;
			$insertData['catalog'] = $catalog;


		
			$insertData['is_deleted'] = 0;
			$insertData['create_time'] = time();
			$insertData['update_time'] = time();

			$insertData['template'] = $template;

			// $insertData['setmealJsonArr'] = $setmealJsonArr;

			/*商品id*/
			$productId = $codModel->create( $insertData );

				
			

			// 解析json
			$setmealJsonStr = html_entity_decode($setmealJsonStr);
			$setmealJsonArr  = json_decode($setmealJsonStr, true);

			// if( empty( $setmealJsonArr ) || is_null($setmealJsonArr) ) {
			// 	throw new \Exception("Wrong Param setmealJsonStr", '014');			
			// }

			// 遍历套餐对象
			
			$relSetmeal = [];
			for($i=0; $i<count($setmealJsonArr); $i++ ){
				$setmeal = $setmealJsonArr[ $i ];

				/*属性数组*/
				$propertiesArr = $setmeal['cid'];

				// 套餐
				$setmeal = $setmeal['pid'];

				
				// 套餐和属性皆为空
				if( empty($propertiesArr) && empty($setmeal) ) {
					continue;
				}

				$scene = 0;

				if( !empty($propertiesArr) && !empty($setmeal) ) {
					$scene = 1;
				} else if( !empty($propertiesArr) && empty($setmeal) ) {
					$scene = 2;
				} else {
					$scene = 3;
				}


				switch ($scene) {
					case 1:
					case 2:
						// for ($j=0; $j < count($propertiesArr); $j++) { 
						// 	$temp = [];
						// 	$temp['goods_id'] = $productId;
						// 	$temp['setmeal_id'] = $setmeal;
						// 	$temp['properties_id'] = $propertiesArr[$j];

						// 	$relSetmeal[] = $temp;
						// }
						for ($j=0; $j < count($propertiesArr); $j++) { 
							$temp = [];
							$temp['goods_id'] = $productId;
							$temp['setmeal_id'] = $setmeal;
							$temp['properties_id'] = $propertiesArr[$j];

							$relSetmeal[] = $temp;
						}
						break;
					case 3:
						$temp = [];
						$temp['goods_id'] = $productId;
						$temp['setmeal_id'] = $setmeal;
						$temp['properties_id'] = 0;

						$relSetmeal[] = $temp;
						break;
					default:	
						break;
				}

				
			}

			if( !empty($relSetmeal) ) {
				$rs = $GoodsToSetmealToPropertiesModel->multiCreate( $relSetmeal );
				if( !$rs ) {
					throw new \Exception("商品套餐关联表插入失败", '005');
				}
				
			}
			
		
			DB::commit();
			$return = $this->functionObj->toAppJson(null, '001', '创建商品成功', true);

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
	* @SWG\Post(path="/api/v1/Cod/setmeal", tags={"Cod"}, 
	*  summary="创建套餐",
	*  description="用户鉴权后 传递套餐名称，库存，套餐价格来创建套餐 ",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="setname", type="string", required=true, in="formData", description="套餐名称"),
	*  @SWG\Parameter(name="skus", type="integer", required=true, in="formData", description="库存"),
	*  @SWG\Parameter(name="setprice", type="string", required=true, in="formData", description="套餐价格"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "创建套餐成功", "success": true } } }
	*  )
	* )
	* 创建片区
	* @author 	xww
	* @return 	json
	*/

		/*套餐属性*/
	public function setmeal()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 商品对象
			$CodModel = new \VirgoModel\CodModel;
			// 属性对象
			$propertiesModel = new \VirgoModel\PropertiesModel;
			// 菜单对象
			$setmealModel = new \VirgoModel\SetmealModel;
			// 菜单属性商品对象
			$GoodsToSetmealToPropertiesModel = new \VirgoModel\GoodsToSetmealToPropertiesModel;

		
			
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
			$this->configValid('required',$this->_configs,['setname',"skus", "setprice"]);

			/*事务*/
			DB::beginTransaction();

			$isBlock = true;

			$setname = $this->_configs['setname'];
			$skus = $this->_configs['skus'];
			$setprice = $this->_configs['setprice'];
			
			// var_dump($name);
			// die;
			
			// 库存,价格 0报错
			if( empty($skus) || empty($setname) ) {
				throw new \Exception("title or price can not be null", '014');
			}
			// 是否有同名商品创建过
			// $record = $setmealModel->getName($setname);
			// if( !empty($record) ) {
			// 	throw new \Exception("已存在同名套餐", '026');
			// }

			unset($insertData);
			$insertData['setname'] = $setname;
			$insertData['skus'] = $skus;
			$insertData['setprice'] = $setprice;

			/*套餐id*/
			$setmealId = $setmealModel->create( $insertData );
			if( !$setmealId ) {
				throw new \Exception("添加套餐失败", '005');
			}

			//获取属性
		// 	$data = $CodModel->getproperties($setmealId);
		// var_dump($data);
		// die;

			$temp = [];
			$temp['setmealId'] = $setmealId;
			$temp['setname'] = $setname;
			$temp['skus'] = $skus;
			$temp['setprice'] = $setprice;
			
			

			DB::commit();
			$return = $this->functionObj->toAppJson($setmealId, '001', '创建套餐成功', true);

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
	* @SWG\Post(path="/api/v1/Cod/properties", tags={"Cod"}, 
	*  summary="创建属性",
	*  description="用户鉴权后 传递属性名称，中文名称，外文名称，图片来创建属性",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="属性名称"),
	*  @SWG\Parameter(name="chinese_name", type="string", required=true, in="formData", description="中文名称"),
	*  @SWG\Parameter(name="foreign_name", type="string", required=true, in="formData", description="外文名称"),
	*  @SWG\Parameter(name="image", type="string", required=true, in="formData", description="图片"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "创建属性成功", "success": true } } }
	*  )
	* )
	* 创建属性
	* @author 	xww
	* @return 	json
	*/
	  /*商品属性*/
	  public function properties()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 商品对象
			$codModel = new \VirgoModel\CodModel;
			// 属性对象
			$propertiesModel = new \VirgoModel\PropertiesModel;
			// 菜单对象
			$setmealModel = new \VirgoModel\SetmealModel;
			// 菜单属性商品对象
			$GoodsToSetmealToPropertiesModel = new \VirgoModel\GoodsToSetmealToPropertiesModel;

		
			
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
			$this->configValid('required',$this->_configs,['name',	"chinese_name", "foreign_name"]);

			/*事务*/
			DB::beginTransaction();

			$isBlock = true;

			$name = $this->_configs['name'];
			$chinese_name = $this->_configs['chinese_name'];
			$foreign_name = $this->_configs['foreign_name'];
			$image = $this->_configs['image'];
			$group_id = $this->_configs['group_id'];
	
			
			// 名称,价格 0报错
			if( empty($name)  ) {
				throw new \Exception("name  can not be null", '014');
			}
			// // 是否有同名商品创建过
			// $record = $propertiesModel->getName($name);
			// if( !empty($record) ) {
			// 	throw new \Exception("已存在同名商品", '026');
			// }

			unset($insertData);
			$insertData['name'] = $name;
			$insertData['chinese_name'] = $chinese_name;
			$insertData['foreign_name'] = $foreign_name;
			$insertData['image'] = $image;
			$insertData['group_id'] = $group_id;
			
			/*属性id*/
			$propertyId = $propertiesModel->create( $insertData );
			if( !$propertyId ) {
				throw new \Exception("添加属性失败", '005');
			}


			// $temp = [];
			// $temp['name'] = $name;
			// $temp['chinese_name'] = $chinese_name;
			// $temp['foreign_name'] = $foreign_name;
			// $temp['image'] = $image;
			// $temp['group_id'] = $group_id;
			// $temp['Properties_id'] = $propertyId;
			
			// $rs = $GoodsToSetmealToPropertiesModel->create($propertyId);

			// if( !$rs ) {
			// 	throw new \Exception("添加商品套餐属性失败", '005');			
			// } 
			DB::commit();
			$return = $this->functionObj->toAppJson($propertyId, '001', '创建属性成功', true);

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
	* @SWG\Get(path="/api/v1/Cod/lists", tags={"Cod"}, 
	*  summary="查看商品列表",
	*  description="用户鉴权后 列出商品基础属性",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="string", required=true, in="query", description="page"),
	*  @SWG\Parameter(name="size", type="string", required=true, in="query", description="size"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="query", description="搜索商品"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "获取商品列表成功", "success": true } } }
	*  )
	* )
	* 商品列表
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
			// 实例化对象
			$SetmealModel = new \VirgoModel\SetmealModel;
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

			$title = empty( $this->_configs['title'] )? null:$this->_configs['title'];
			$youhuashi = empty( $this->_configs['youhuashi'] )? null:$this->_configs['youhuashi'];
			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$params['title'] = $title;
			$params['skip'] = $skip;
			$params['size'] = $size;
			$params['youhuashi'] = $youhuashi;
			/*获取全部商品*/
			
			$pageObj = $codModel->getListsObject($params);
			
			$data = empty($pageObj->data)? []:$pageObj->data;
				for ($i=0; $i < count($data); $i++) { 
				// $data[$i]['url'] = "http://" . $_SERVER['HTTP_HOST'] . "/style" . $data[$i]['catalog'] . "/index.html?id=" . $data[$i]['id'] ;
				$data[$i]['url'] = "http://" . $_SERVER['HTTP_HOST'] . '/'.$data[$i]['catalog'];
			}
			// die;
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
	* @SWG\Get(path="/api/v1/Cod/goodsdetail", tags={"Cod"}, 
	*  summary="查看详细属性",
	*  description="用户鉴权后 查看商品属性",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="id", type="string", required=true, in="query", description="id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "获取商品详细成功", "success": true } } }
	*  )
	* )
	* 商品详情
	* @author 	xww
	* @return 	json
	*/



		public function goodsdetail()
			{
		
		try{

			// //验证 
			// $user = $this->getUserApi($this->_configs, 1);

			// $uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;
			// 实例化对象
			$SetmealModel = new \VirgoModel\SetmealModel;
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
			$data = $codModel->getDetail($id);

			if( !is_null($data) ) {
				$data['content'] = empty($data['content'])? '':html_entity_decode($data['content']);
			}
			
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
	* @SWG\Post(path="/api/v1/Cod/goodsupdate", tags={"Cod"}, 
	*  summary="修改商品",
	*  description="用户鉴权后 通过传入的商品名称，中文名，外文名，价格售价等创建商品如果存在同名同属地块的商品新建会失败",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="title", type="string", required=true, in="formData", description="商品名称"),
	*  @SWG\Parameter(name="chinese_title", type="string", required=true, in="formData", description="中文名"),
	*  @SWG\Parameter(name="foreign_title", type="string", required=true, in="formData", description="外文名"),
	*  @SWG\Parameter(name="price", type="string", required=true, in="formData", description="价格"),
	*  @SWG\Parameter(name="Original_Price", type="integer", required=true, in="formData", description="原价"),
	*  @SWG\Parameter(name="discount", type="integer", required=true, in="formData", description="折扣"),
	*  @SWG\Parameter(name="rush_time", type="string", required=false, in="formData", description="剩余抢购时间"),
	*  @SWG\Parameter(name="Purchaseurl", type="string", required=false, in="formData", description="采购URL"),
	*  @SWG\Parameter(name="rate", type="string", required=false, in="formData", description="好评率"),
	*  @SWG\Parameter(name="language", type="integer", required=true, in="formData", description="语言 1简体中文2繁体中文3越南语4泰语5菲律宾语6印尼语7柬埔寨语8英语"),
	*  @SWG\Parameter(name="country", type="integer", required=true, in="formData", description="国家 1中国台湾2越南3新加坡4泰国5柬埔寨6马来西亚7印度尼西亚8菲律宾"),
	*  @SWG\Parameter(name="author", type="string", required=false, in="formData", description="投放师"),
	*  @SWG\Parameter(name="domain_name", type="string", required=false, in="formData", description="域名"),
	*  @SWG\Parameter(name="sales", type="string", required=false, in="formData", description="销量"),
	*  @SWG\Parameter(name="Inventory", type="string", required=false, in="formData", description="库存"),
	*  @SWG\Parameter(name="email", type="string", required=false, in="formData", description="联系邮箱"),
	*  @SWG\Parameter(name="LINE", type="string", required=false, in="formData", description="LINE"),
	*  @SWG\Parameter(name="pop800id", type="string", required=false, in="formData", description="pop800id"),
	*  @SWG\Parameter(name="Fb_Id", type="string", required=false, in="formData", description="FB通用像素id"),
	*  @SWG\Parameter(name="readme", type="string", required=false, in="formData", description="购买提示"),
	*  @SWG\Parameter(name="content", type="string", required=false, in="formData", description="内容"),
	*  @SWG\Parameter(name="logo", type="string", required=false, in="formData", description="logo"),
	*  @SWG\Parameter(name="thumbnail", type="string", required=false, in="formData", description="缩略图"),
	*  @SWG\Parameter(name="images", type="string", required=false, in="formData", description="图集"),
	*  @SWG\Parameter(name="properties", type="string", required=false, in="formData", description="属性"),
	*  @SWG\Parameter(name="setmeal", type="string", required=false, in="formData", description="套餐"),
	*  @SWG\Parameter(name="labels", type="string", required=false, in="formData", description="标签"),
	*  @SWG\Parameter(name="currency_id", type="string", required=false, in="formData", description="币种"),
	*  @SWG\Parameter(name="content", type="string", required=false, in="formData", description="内容"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "修改商品成功", "success": true } } }
	*  )
	* )
	* 创建片区
	* @author 	xww
	* @return 	json
	*/

	public function goodsupdate()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

				// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 商品对象
			$codModel = new \VirgoModel\CodModel;
			// 属性对象
			$propertiesModel = new \VirgoModel\PropertiesModel;
			// 菜单对象
			$setmealModel = new \VirgoModel\SetmealModel;
			// 菜单属性商品对象
			$GoodsToSetmealToPropertiesModel = new \VirgoModel\GoodsToSetmealToPropertiesModel;
			

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
			$this->configValid('required',$this->_configs,['title',	"foreign_title", "chinese_title", "price", "Original_Price",'discount','setmealJsonStr','language','country','content']);



			DB::beginTransaction();

			$isBlock = true;
			$id = (int)$this->_configs['id'];

			

			$setmealJsonStr = $this->_configs['setmealJsonStr'];
			$updateData['title'] = $this->_configs['title'];
			$updateData['chinese_title'] = $this->_configs['chinese_title'];
			$updateData['foreign_title'] = $this->_configs['foreign_title'];
			// $updateData['price'] = $this->_configs['price'];
			$updateData['discount'] = $this->_configs['discount'];
			$updateData['Original_Price'] = $this->_configs['Original_Price'];
			$updateData['Inventory'] = empty($this->_configs['Inventory'])? 0:$this->_configs['Inventory'];
			$updateData['sales'] = empty($this->_configs['sales'])? 0:$this->_configs['sales'];
			$updateData['price'] = $this->_configs['price'];
			$updateData['currency_id'] = empty($this->_configs['currency_id'])? '':$this->_configs['currency_id'];
			$updateData['rush_time'] = empty($this->_configs['rush_time'])? 0:$this->_configs['rush_time'];
			$updateData['domain_name'] = empty($this->_configs['domain_name'])? '':$this->_configs['domain_name'];
			$updateData['country'] = $this->_configs['country'];
			$updateData['LINE'] = empty($this->_configs['LINE'])? '':$this->_configs['LINE'];
			$updateData['content'] = $this->_configs['content'];
			$updateData['rate'] = empty($this->_configs['rate'])? 0:$this->_configs['rate'];
			$updateData['template'] = empty($this->_configs['template'])? '':$this->_configs['template'];
			$updateData['labels'] = empty($this->_configs['labels'])? '':$this->_configs['labels'];
			$updateData['erp_id'] = empty($this->_configs['erp_id'])? '':$this->_configs['erp_id'];
			$updateData['catalog'] = empty($this->_configs['catalog'])? '':$this->_configs['catalog'];
			if( !empty( $this->_configs['logo'] ) ) {
				$updateData['logo'] = $this->_configs['logo'];	
			}
			
			$updateData['thumbnail'] = empty($this->_configs['thumbnail'])? '':$this->_configs['thumbnail'];
			$updateData['readme'] = empty($this->_configs['readme'])? '':$this->_configs['readme'];
			// $updateData['author'] = $this->_configs['author'];
			$updateData['pop800id'] =empty($this->_configs['pop800id'])? '':$this->_configs['pop800id'];
			$updateData['language'] = $this->_configs['language'];
			$updateData['email'] = empty($this->_configs['email'])? '':$this->_configs['email'];
			$updateData['images'] = empty($this->_configs['images'])? '':$this->_configs['images'];
			$updateData['Fb_Id'] =empty($this->_configs['Fb_Id'])? '':$this->_configs['Fb_Id'];	
			// $updateData['setmealJsonStr'] = $this->_configs['setmealJsonStr'];
			$updateData['update_time'] = time();  
			
			$updateData['language'] = $this->_configs['language'];
			
		
			$rs = $codModel->partUpdate($id, $updateData);
	


			// todo 删除商品对应套餐
			$rs = $codModel->goodsDelete( $id);
			
			// 解析json
			$setmealJsonStr = html_entity_decode($setmealJsonStr);
			$setmealJsonArr  = json_decode($setmealJsonStr, true);

			if( empty( $setmealJsonArr ) || is_null($setmealJsonArr) ) {
				throw new \Exception("Wrong Param setmealJsonStr", '014');			
			}

			// 遍历套餐对象
			
			$relSetmeal = [];
			$goodsRelModel = new \VirgoModel\GoodsToSetmealToPropertiesModel;
			for($i=0; $i<count($setmealJsonArr); $i++ ){
				$setmeal = $setmealJsonArr[ $i ];

				/*属性数组*/
				$propertiesArr = $setmeal['cid'];

				// 套餐
				$setmeal = $setmeal['pid'];

				// 套餐和属性皆为空
				if( empty($propertiesArr) && empty($setmeal) ) {
					continue;
				}

				
				$scene = 0;

				if( !empty($propertiesArr) && !empty($setmeal) ) {
					$scene = 1;
				} else if( !empty($propertiesArr) && empty($setmeal) ) {
					$scene = 2;
				} else {
					$scene = 3;
				}


				switch ($scene) {
					case 1:
					case 2:
						// for ($j=0; $j < count($propertiesArr); $j++) { 
						// 	$temp = [];
						// 	$temp['goods_id'] = $productId;
						// 	$temp['setmeal_id'] = $setmeal;
						// 	$temp['properties_id'] = $propertiesArr[$j];

						// 	$relSetmeal[] = $temp;
						// }

						/*遍历判断是否有对应关联表数据存在 如果存在 将删除状态置为未删除 否则为新增数据数组*/
						
						for ($j=0; $j < count($propertiesArr); $j++) { 
							$temp = [];
							$temp['goods_id'] = $id;
							$temp['setmeal_id'] = $setmeal;
							$temp['properties_id'] = $propertiesArr[$j];

							$recordData = $goodsRelModel->getConditionRecords( $temp );
							
							if( !empty( $recordData ) ) {

								/*应该将记录置为非删除状态*/
								$goodsRelId = $recordData[0]['id'];
								$rs = $goodsRelModel->setRecordNormal( $goodsRelId );

								if( !$rs ) {
									throw new \Exception("更新套餐失败", '003');
								}

								continue;

							}

							$relSetmeal[] = $temp;
						}
						break;
					case 3:
						$temp = [];
						$temp['goods_id'] = $id;
						$temp['setmeal_id'] = $setmeal;
						$temp['properties_id'] = 0;

						$goodsRelModel = new \VirgoModel\GoodsToSetmealToPropertiesModel;
						$recordData = $goodsRelModel->getConditionRecords( $temp );
						if( !empty( $recordData ) ) {

							/*应该将记录置为非删除状态*/
							$goodsRelId = $recordData[0]['id'];
							$rs = $goodsRelModel->setRecordNormal( $goodsRelId );

							if( !$rs ) {
								throw new \Exception("更新套餐失败", '003');
							}

							continue;

						}

						$relSetmeal[] = $temp;
						break;
					default:	
						break;
				}

				
			}

			if( !empty($relSetmeal) ) {
				$rs = $GoodsToSetmealToPropertiesModel->multiCreate( $relSetmeal );
				if( !$rs ) {
					throw new \Exception("商品套餐关联表插入失败", '005');
				}
				
			}

			DB::commit();

			$return = $this->functionObj->toAppJson(null, '001', '修改商品成功', true);

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
	* @SWG\Post(path="/api/v1/Cod/goodsdelete", tags={"Cod"}, 
	*  summary="删除商品",
	*  description="用户鉴权后 输入ID删除商品",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="string", required=true, in="formData", description="id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "删除商品成功", "success": true } } }
	*  )
	* )
	* 删除商品
	* @author 	xww
	* @return 	json
	*/


	public function goodsdelete()
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
			// 属性对象
			$propertiesModel = new \VirgoModel\PropertiesModel;
			// 菜单对象
			$setmealModel = new \VirgoModel\SetmealModel;
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

			$data['cod_goods.is_deleted'] = 1;
			$data['cod_goods.update_time'] = time();
			$rs = $codModel->deleteProdcutSetmeal($id, $data);
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

/**
	* @SWG\Post(path="/api/v1/Cod/setmealupdate", tags={"Cod"}, 
	*  summary="修改套餐",
	*  description="用户鉴权后 通过传入的套餐名称，库存，套餐价格来修改商品",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="string", required=true, in="formData", description="id"),
	*  @SWG\Parameter(name="setname", type="string", required=true, in="formData", description="套餐名称"),
	*  @SWG\Parameter(name="skus", type="string", required=true, in="formData", description="库存"),
	*  @SWG\Parameter(name="setprice", type="string", required=true, in="formData", description="套餐价格"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "修改套餐成功", "success": true } } }
	*  )
	* )
	* 修改属性
	* @author 	xww
	* @return 	json
	*/

	public function setmealupdate()
	{
			
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

				// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 商品对象
			$codModel = new \VirgoModel\CodModel;
			// 属性对象
			$propertiesModel = new \VirgoModel\PropertiesModel;
			// 菜单对象
			$setmealModel = new \VirgoModel\SetmealModel;
			// 菜单属性商品对象
			$GoodsToSetmealToPropertiesModel = new \VirgoModel\GoodsToSetmealToPropertiesModel;
			

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
			$this->configValid('required',$this->_configs,['setname',"skus", "setprice"]);


			DB::beginTransaction();

			$isBlock = true;
			$id = (int)$this->_configs['id'];
			
			$updateData['setname'] = $this->_configs['setname'];
			$updateData['skus'] = $this->_configs['skus'];
			$updateData['setprice'] = $this->_configs['setprice'];

			
			$rs = $setmealModel->partUpdate($id, $updateData);
			

			DB::commit();

			$return = $this->functionObj->toAppJson(null, '001', '修改套餐成功', true);

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
	* @SWG\Post(path="/api/v1/Cod/propertiesupdate", tags={"Cod"}, 
	*  summary="修改属性",
	*  description="用户鉴权后 通过传入的属性名，中文名，外文名，图片来修改属性",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="string", required=true, in="formData", description="id"),
	*  @SWG\Parameter(name="name", type="string", required=true, in="formData", description="属性名"),
	*  @SWG\Parameter(name="chinese_name", type="string", required=true, in="formData", description="中文名"),
	*  @SWG\Parameter(name="foreign_name", type="string", required=true, in="formData", description="外文名"),
	*  @SWG\Parameter(name="image", type="string", required=true, in="formData", description="图片"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "修改属性成功", "success": true } } }
	*  )
	* )
	* 修改属性
	* @author 	xww
	* @return 	json
	*/

	public function propertiesupdate()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

				// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 商品对象
			$codModel = new \VirgoModel\CodModel;
			// 属性对象
			$propertiesModel = new \VirgoModel\PropertiesModel;
			// 菜单对象
			$setmealModel = new \VirgoModel\SetmealModel;
			// 菜单属性商品对象
			$GoodsToSetmealToPropertiesModel = new \VirgoModel\GoodsToSetmealToPropertiesModel;
			

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
			$this->configValid('required',$this->_configs,['name',	"chinese_name", "foreign_name"]);



			DB::beginTransaction();

			$isBlock = true;
			$id = (int)$this->_configs['id'];
			
			$updateData['name'] = $this->_configs['name'];
			$updateData['chinese_name'] = $this->_configs['chinese_name'];
			$updateData['foreign_name'] = $this->_configs['foreign_name'];
			$updateData['image'] = $this->_configs['image'];
			
			$rs = $propertiesModel->partUpdate($id, $updateData);
			

			DB::commit();

			$return = $this->functionObj->toAppJson(null, '001', '修改属性成功', true);

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
	* @SWG\Get(path="/api/v1/Cod/listscomment", tags={"Cod"}, 
	*  summary="查看商品列表(评论管理使用)",
	*  description="用户鉴权后 列出商品基础属性",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "获取商品列表成功", "success": true } } }
	*  )
	* )
	* 商品列表
	* @author 	xww
	* @return 	json
	*/

	  public function listscomment()
			{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs, 1);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;
			// 实例化对象
			$SetmealModel = new \VirgoModel\SetmealModel;
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
	$youhuashi = empty( $this->_configs['youhuashi'] )? null:$this->_configs['youhuashi'];

	$params['youhuashi'] = $youhuashi;
			/*获取全部商品*/
			$data = $codModel->getListsComment($params);


			$return = $this->functionObj->toLayuiJson($data, '001', '获取商品列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}


	 /**
	* @SWG\Get(path="/api/v1/Cod/getsetmeal", tags={"Cod"}, 
	*  summary="查看套餐属性属性",
	*  description="用户输入ID 查看套餐属性属性",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="id", type="string", required=true, in="query", description="id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "获取商品详细成功", "success": true } } }
	*  )
	* )
	* 商品详情
	* @author 	xww
	* @return 	json
	*/



		public function getsetmeal()
			{
		
		try{

			// //验证 
			// $user = $this->getUserApi($this->_configs, 1);

			// $uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;
			// 实例化对象
			$SetmealModel = new \VirgoModel\SetmealModel;
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
			$data = $codModel->getsetmeal($id);

			$data['content'] = empty($data['content'])? '':html_entity_decode($data['content']);
			// $data['images'] = $data['images']=="[]"? "[]":html_entity_decode($data['images']);

			$return = $this->functionObj->toAppJson($data, '001', '获取套餐属性成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}


	public function hascatalog()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$model = new \VirgoModel\UserModel;
				$codModel = new \VirgoModel\CodModel;
			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $model->hasBackCreateOperatePrivilige($uid, [1, 4]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和查看数据权限", '070');
			}

			// 必要验证 id
			$this->configValid('required',$this->_configs,['catalog']);

			$catalog = $this->_configs['catalog'];

			// 判断账号是否存在
			$data = $codModel->getcatalog( $catalog );
			$result = !empty($data)? true:false;

			$return = $this->functionObj->toAppJson($result, '001', '获取账号信息成功', true);			

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}
}
