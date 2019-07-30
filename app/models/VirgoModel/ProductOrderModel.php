<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent;
class ProductOrderModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	public $result=['result'=>false, 'message'=>'', 'code'=>'', 'data'=>null];

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\ProductOrder; 
		$this->_ProductOrderInfo = new \EloquentModel\ProductOrderInfo; 
		$this->codObj = new \EloquentModel\CodGoods;
	}

	/**
	* 列表
	* @author xww
	*@return object
	*/
	public function lists()
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;
		// set query 
		$query = $this->_model->where("is_deleted", '=', 0)->orderBy("create_time", "desc");

		// 标题过滤
		if(!empty($_GET['title'])){
			$_GET['title'] = trim($_GET['title']);
			$query = $query->where("title", 'like', '%'.$_GET['title'].'%');
			$pageObj->setPageQuery(['title'=>$_GET['title']]);
		}
		// 开始时间过滤
		if(!empty($_GET['startTime'])){
			$_GET['startTime'] = trim($_GET['startTime']);
			$query = $query->where("update_time", '>=', strtotime($_GET['startTime']." 00:00:00"));
			$pageObj->setPageQuery(['startTime'=>$_GET['startTime']]); 
		}
		// 截止时间过滤
		if(!empty($_GET['endTime'])){
			$_GET['endTime'] = trim($_GET['endTime']);
			$query = $query->where("update_time", '<=', strtotime($_GET['endTime']." 23:59:59"));
			$pageObj->setPageQuery(['endTime'=>$_GET['endTime']]);
		}
		// 父菜单总记录数
		$totalCount = count($query->get()->toArray());
		//分页的take,size
		$size = 10;
		if(!empty($_GET['page'])){
			$page = (int)$_GET['page'];
			$skip = ($page-1)*$size;
		} else {
			$skip = 0;
		}
		// 获取记录
		$data = $query->skip($skip)->take($size)->get()->toArray();
		//设置页数跳转地址
		$pageObj->setUrl('/admin/productOrders');
		// 设置分页数据
		$pageObj->setData($data);
		// 设置记录总数
		$pageObj->setTotalCount($totalCount);
		// 设置分页大小
		$pageObj->setSize($size);
		// 进行分页并返回
		return $pageObj->doPage();
	}
	/**
	* 逻辑增加
	* @author xww
	* @return sql result
	*/
	public function doCreate()
	{
		unset($_POST['id']);
		unset($_POST['coverPath']);
		unset($_POST['page']);
		// 上传文件
		if(!empty($_FILES['cover']) && $_FILES['cover']['error']==0){
			$ext = str_replace('image/', '', $_FILES['cover']['type']);
			$fpath = '/upload/product/'.microtime(true).".".$ext;
			$rs = move_uploaded_file($_FILES['cover']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$fpath);
			if($rs){
				$_POST['cover'] = $fpath;
			}
		}
		// 创建时间
		$_POST['create_time'] = time();
		// 修改时间
		$_POST['update_time'] = time();
		return $this->_model->insert($_POST);
	}
	/**
	* 返回对应id数据
	* @param  $id  string/int    会话id
	* @author xww
	* @return object
	*/
	public function read($id)
	{
		return $this->_model->where("is_deleted", '=', 0)->find($id);
	}
	/**
	* 逻辑修改
	* @author xww
	* @return sql result
	*/
	public function doUpdate()
	{
		$id = $_POST['id'];
		unset($_POST['id']);
		unset($_POST['coverPath']);
		unset($_POST['page']);
		// 上传文件
		if(!empty($_FILES['cover']) && $_FILES['cover']['error']==0){
			$ext = str_replace('image/', '', $_FILES['cover']['type']);
			$fpath = '/upload/product/'.microtime(true).".".$ext;
			$rs = move_uploaded_file($_FILES['cover']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$fpath);
			if($rs){
				$_POST['cover'] = $fpath;
			}
		}
		// 修改时间
		$_POST['update_time'] = time();
		// 更新
		return $this->_model->where("id", '=', $id)->update($_POST);
	}
	/**
	* 逻辑删除
	* @author xww
	* @return sql result
	*/
	public function delete()
	{
		$data['is_deleted'] = 1;
		if($_POST){$ids = $_POST['ids'];}
		else{$ids = [$_GET['id']];}
		return $this->_model->whereIn("id", $ids)->update($data);
	}
	public function deleteorder($id)
	{ 
		return  $this->_model->where('id',$id)->update(['is_deleted'=>1]);
		
	}

	/**
	* 获取订单号
	* @author 	xww
	* @param 	int/string 		$encodeData
	* @return 	string
	*/
	public function getOrderNum()
	{
		$str = (int)date("Ymd").(int)microtime(true).mt_rand(100,999);	
		return $str;
	}

	public function create( $data )
	{
		return $this->_model->insertGetId( $data );
	}

	public function hasSameName( $phone)
	{
		
		$count = $this->_model->where("is_deleted", 0)->where("phone", '=', $phone)->count();
		return $count? true:false; 

	}
	public function createProductOrder( $params )
	{

		try{

			DB::beginTransaction();

			$codModel = new \VirgoModel\CodModel;

			$productData = $codModel->readSingleTon( $params['productId'] );

			if( empty($productData) || $productData['status_id']!=0 ) {
				throw new \Exception("无法查询到数据或数据已经下架", '006');
			}

			/*生成唯一订单号--end*/

			$createData = [];
			//过滤rel_id=
 		$params['setmealsIds']=str_replace('rel_id=','',$params['setmealsIds']);
 		
	if( !empty($params['setmealsIds']) ) { 
				$productSetmealPropertyModel = new \VirgoModel\GoodsToSetmealToPropertiesModel;

				$setmealArr = $productSetmealPropertyModel->getSetmealProperyArr( explode(",", $params['setmealsIds']) );

				if( !empty($setmealArr) ) {

					if( $setmealArr[0]['setprice']!=0 ) {
						$createData['payable_price'] = $setmealArr[0]['setprice'] * intval( $params['amounts'] );
						$createData['paid_price'] = $setmealArr[0]['setprice'] * intval( $params['amounts'] );
					}

				}

			}

			if( !isset($createData['payable_price']) ) {

				$createData['payable_price'] = $productData['price'] * 100 * intval( $params['amounts'] );
				$createData['paid_price'] = $productData['price'] * 100 * intval( $params['amounts'] );

			}

			$createData['order_num'] = $this->getOrderNum();
			$createData['user_name'] = $params['userName'];
			$createData['email'] = empty($params['email'])? '':$params['email'];
			// $createData['category_id'] = $params['category_id'];
			$createData['postcode'] = empty($params['postcode'])? '':$params['postcode'];
			$createData['erp_id'] = empty($params['erp_id'])? '':$params['erp_id'];
		    $createData['product_id'] = $params['productId'];
		    $createData['country_id'] = $productData['country'];
			$createData['phone'] =  $params['phone'];
			$createData['province'] = empty($params['province'])? '':$params['province'];
			$createData['city'] = empty($params['city'])? '':$params['city'];
			$createData['district'] = empty($params['district'])? '':$params['district'];
			$createData['street'] = empty($params['street'])? '':$params['street'];
			$createData['amounts'] = $params['amounts'];
			$createData['freight'] = 0;
			$createData['remarks'] = empty($params['remarks'])? '':$params['remarks'];
			$createData['create_time'] = time();
			$createData['update_time'] = time();
			// $createData['phoneState'] = 1;
			// if(strlen($createData['phone'])<10) || (strlen($createData['phone'])>11){
			// 	$createData['phoneState'] = 0 ;
			// }else{
			// 	$createData['phoneState'] = 1;
			// }

			$hasSameNamers = $this->hasSameName( $createData['phone'] );
			if(mb_strlen($createData['street'], "utf8")<12) {
				$createData['state'] = 1 ;

			}elseif($hasSameNamers){
				$createData['state'] = 2;
			}else{
				$createData['state'] = 3;
			};
				

			$recordId = $this->create( $createData );
			
			if( empty($recordId) ) {
				throw new \Exception("创建订单失败", '005');
			}

			if( !empty( $params['amounts'] ) && is_array( explode(",", $params['setmealsIds']) ) ) {

				if( !isset($productSetmealPropertyModel) ) {
					$productSetmealPropertyModel = new \VirgoModel\GoodsToSetmealToPropertiesModel;
				}

				if( !isset($setmealArr) ) {
					$setmealArr = $productSetmealPropertyModel->getSetmealProperyArr( explode(",", $params['setmealsIds']) );
				}

				$setmealJsonStr = '';
				if( !empty($setmealArr) ) {
					$setmealJsonStr = json_encode($setmealArr, JSON_UNESCAPED_UNICODE);
				}

				$infoData['order_id'] = $recordId;
				$infoData['thumbnail'] = $productData['thumbnail'];
				$infoData['country_name'] = \VirgoModel\CountriesModel::getCountryNameWithId( $productData['country_id'] );
				$infoData['currency_name'] = \VirgoModel\CurrencyManagementModel::getCurrencyNameWithId( $productData['currency_id'] );
				$infoData['currency_id'] = $productData['currency_id'];
				$infoData['product_name'] = $productData['title'];
				$infoData['chinese_name'] = $productData['chinese_title'];
				$infoData['foreign_name'] = $productData['foreign_title'];
				$infoData['author_id'] = $productData['author'];
				$infoData['setmeal_json'] = $setmealJsonStr;
				$infoData['create_time'] = time();
				$infoData['update_time'] = time();
			

				$infoModel = new \VirgoModel\ProductOrderInfoModel;

				$rs = $infoModel->create($infoData);
				if( !$rs ) {
					throw new \Exception("新建订单失败", '005');
				}

			}

			DB::commit();
			$this->result = ['result'=>true, 'message'=>'新建订单成功', 'code'=>'001', 'data'=>$createData['order_num']];
// var_dump($createData);
// die;
		} catch(\Exception $e) {
			DB::rollback();
			$this->result = ['result'=>false, 'message'=>$e->getMessage(), 'code'=>str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), 'data'=>null];
		}

	}

	public function getResult()
	{
		return $this->result;
	}

	/**
	* 获取列表对象
	* @author 	xww
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	object
	*/
	public function getListsObject($params=[], $searches=[], $timeOrder=null)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query = $this->_model->select("product_order.id", "order_num as orderNum", "user_name as userName", "users.name as creatorName", "product_order.phone", "product_order_info.product_name as productName","product_order.state", DB::raw(" truncate(`comp_product_order`.payable_price/100, 2) as payablePrice "), "currency_management.front_symbol as frontSymbol", "currency_management.back_symbol as backSymbol" ,DB::raw(" FROM_UNIXTIME( `comp_product_order`.create_time, '%Y-%m-%d %T') as createTime "))
							  ->leftJoin("product_order_info", function($joinClosure){
							  	$joinClosure->where("product_order_info.is_deleted", "=", 0)
							  				->on("product_order_info.order_id", '=', "product_order.id");
							  })
							  ->leftJoin("users", function($joinClosure){
							  	$joinClosure->where("users.is_deleted", "=", 0)
							  				->on("product_order_info.author_id", '=', "users.id");
							  })
							   ->leftJoin("cod_goods", function($joinClosure){
							  	$joinClosure->where("cod_goods.is_deleted", "=", 0)
							  				->on("cod_goods.id", '=', "product_order.product_id");
							  })
							  ->leftJoin("currency_management", function($joinClosure){
							  	$joinClosure->where("currency_management.is_deleted", "=", 0)
							  				->on("currency_management.id", '=', "product_order_info.currency_id");
							  })
							  ->where("product_order.is_deleted", 0);
							  // ->orderBy("product_order.create_time", "desc")
							  // ->orderBy("product_order.id", "desc");

				if ($params['youhuashi']=='null') {
						$query =$query;
					}else{
						$query =$query-> where("cod_goods.youhuashi",$params['youhuashi']);
					}
		if( !is_null($searches) ) {

			foreach ($searches as $key => $search) {

				$name = $search['searchName'];
				$value = $search['searchValue'];

				if( empty($value) ) {
					continue;
				}

				/*只有时间会有数组区间返回*/
				if( is_array( $value ) ) {
					$value = [ strtotime( $value[0] . " 00:00:00" ), strtotime( $value[1] . " 23:59:59" ) ];
				}

				$prifix = "";
				$ok = false;

				if( !$ok ) {
					$searchKeyword = $this->getSearchConditionFirst($name);
					if( $searchKeyword!==false ) {
						$prifix = "product_order.";
						$ok = true;
					}
				}

				if( !$ok ) {
					$searchKeyword = $this->getSearchConditionSecond($name);
					if( $searchKeyword!==false ) {
						$prifix = "product_order_info.";
						$ok = true;
					}
				}

				if( !$ok ) {
					$searchKeyword = $this->getSearchConditionThird($name);
					if( $searchKeyword!==false ) {
						$prifix = "users.";
						$ok = true;
					}
				}

				if( !$searchKeyword ) {
					$searchKeyword = "=";
				}

				// var_dump($query);
				// die;
				$query = $this->getSearchQuery( $query,  $prifix.$name, $searchKeyword,  $value);

			}

		}

		
		if( is_null($timeOrder) ) {
			$timeOrder = "desc";
		}

		$query->orderBy("product_order.create_time", $timeOrder)
			  ->orderBy("product_order.id", "desc");
	

		$totalCountQuery = $query;

		// 父菜单总记录数
		$totalCount = $totalCountQuery->count();

		if( !empty( $params['size'] ) ) {
			$query = $query->skip( $params['skip'] )->take( $params['size'] );	
		}

		// 获取记录
		$data = $query->get()->toArray();

		$url = "";
		if( !empty( $params['url'] ) ) {
			$url = $params['url'];
		}

		//设置页数跳转地址
		$pageObj->setUrl( $url );

		// 设置分页数据
		$pageObj->setData($data);

		// 设置记录总数
		$pageObj->setTotalCount($totalCount);

		// 设置分页大小
		$pageObj->setSize( $params['size'] );

		// 进行分页并返回
		return $pageObj->doPage();

	}

	/**
	* 详情
	* @author 	xww
	* @return 	array
	*/
	public function readDetail($id)
	{
		
		$data = $this->_model->select("product_order.id", 'state',"order_num as orderNum", "product_order.amounts","user_name as userName", "product_order_info.country_name as countryName", "product_order_info.currency_name as currencyName", "product_order_info.currency_id as currencyId ", "product_order.phone", "product_order.postcode", "product_order.province", "product_order.city", "product_order.district", "product_order.street", "product_order.erp_id as erpId", "product_order_info.product_name as productName", "product_order_info.chinese_name as chineseName", "product_order_info.foreign_name as foreignName", "users.name as creatorName", "category.name as categoryName",DB::raw(" truncate(`comp_product_order`.payable_price/100, 2) as payablePrice "), DB::raw(" truncate(`comp_product_order`.paid_price/100, 2) as paidPrice "),  "currency_management.front_symbol as frontSymbol", "currency_management.back_symbol as backSymbol", DB::raw(" FROM_UNIXTIME( `comp_product_order`.create_time, '%Y-%m-%d %T') as createTime "), "product_order.express_code as expressCode", "product_order.express_number as expressNumber", "product_order.order_status as orderStatus", "setmeal_json",'product_order.remarks','product_order.email')
							  ->leftJoin("product_order_info", function($joinClosure){
							  	$joinClosure->where("product_order_info.is_deleted", "=", 0)
							  				->on("product_order_info.order_id", '=', "product_order.id");
							  })
							  ->leftJoin("users", function($joinClosure){
							  	$joinClosure->where("users.is_deleted", "=", 0)
							  				->on("product_order_info.author_id", '=', "users.id");
							  })
							  ->leftJoin("currency_management", function($joinClosure){
							  	$joinClosure->where("currency_management.is_deleted", "=", 0)
							  				->on("currency_management.id", '=', "product_order_info.currency_id");
							  })
							   ->leftJoin("category", function($joinClosure){
							  	$joinClosure->where("category.is_deleted", "=", 0)
							  				->on("product_order.category_id", '=', "category.id");
							  })
							  ->where("product_order.is_deleted", 0)
							  ->where("product_order.id", $id)
							  ->orderBy("product_order.create_time", "desc")
							  ->orderBy("product_order.id", "desc")
							  ->get()
							  ->toArray();

		return empty($data)? null:$data[0];
	}

	public function getSearchQuery(\Illuminate\Database\Eloquent\Builder $query, $column, $symbool, $value)
	{

		switch ( $symbool ) {
			case 'like':
				$query = $query->where($column, "like", "%" . $value . "%");
				break;
			case '=':
				$query = $query->where($column, "=", $value);
				break;
			case 'between':
				$query = $query->whereBetween($column, $value);
				break;
			default:
				
				break;
		}

		return $query;

	}

	public function getSearchConditionFirst($name)
	{
		
		$arr = [
			'order_num' => "like",
			'user_name' => "like",
			'email' => "like",
			'postcode' => "like",
			'erp_id' => "like",
			'phone' => "like",
			'province' => "like",
			'city' => "like",
			'district' => "like",
			'street' => "like",
			'express_name' => "like",
			'express_code' => "like",
			'order_status' => "=",
			'create_time' => "between"
		];

		return !empty( $arr[ $name ] )? $arr[ $name ]:false;

	}

	public function getSearchConditionSecond($name)
	{
		
		$arr = [
			'country_name' => "like",
			'currency_name' => "like",
			'product_name' => "like",
			'chinese_name' => "like",
			'foreign_name' => "like",
			'setmeal_json' => "like"
		];

		return !empty( $arr[ $name ] )? $arr[ $name ]:false;

	}

	public function getSearchConditionThird($name)
	{
		
		$arr = [
			'name' => "like",
		];

		return !empty( $arr[ $name ] )? $arr[ $name ]:false;

	}

	/**
	* 数据更新
	* @author 	xww
	* @param 	int/string 		$id
	* @param 	array 			$data
	* @return 	affect rows
	*/
	public function partUpdate($id, $data)
	{
		return $this->_model->where("id", $id)->update($data);
	}

public function getstate($id)
	{
		// if($id==1){
		// 	$state='异常';
		// }elseif($id==2){
		// 	$state='重复';
		// }else{
		// 	$state='正常';
		// }
		// return $state;
		switch ($id) {
			case 1:
				return '异常';
				break;
			case 2:
				return '重复';
				break;
			case 3:
				return '正常';
				break;
			case 4:
				return '确认';
				break;
			case 5:
				return '待定';	
				break;	
			case 6:
				return '取消';
				break;
				
				break;
		}

	}

	
}
?>