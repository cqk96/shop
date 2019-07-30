<?php

namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class StatisticsModel extends BaseModel {

	public function __construct()
	{
		$this->_model = new \EloquentModel\Statistics; 
		$this->_ProductOrder = new \EloquentModel\ProductOrder; 
	}
			private $_model;
			/**
			* 获取指定商品名称，中文名称，外文名称
			* @author 	xww
			* @param 	int/string 		
			* @param 	string 			
			*/
	public function getName($name)
	{
		// echo 1;

		// var_dump($name);
		// die;
		// var_dump($chinese_title);
		// var_dump($foreign_title);
		return $this->_model->where("is_deleted", 0)->where("title", $name)->get()->toArray();

		// var_dump($data);
		// die;
		// return $this->_model->where("is_deleted", 0)->where("title", $title)->where("chinese_title", $chinese_title)->where("foreign_title", $foreign_title)->get()->toArray();
	}

	public function create($data)
	{
		return $this->_model->insertGetId($data);
	}

	public function getJSON($array) {
        
        $json = json_encode($array);
        return ($json);
    }


    public function partUpdate($id, $data)
	{
		return $this->_model->where("id", $id)->where("category.is_deleted", 0)->update($data);
	}


	public function getListsObject($params=[])
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query = $this->_model->where("category.is_deleted", 0)
								->select('id',"name");
					  		  
					  		  
	
		if( !empty( $params['name'] ) ) {
			$query = $query->where("name", "like", "%" . $params['name'] . "%");
		}

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
		

		// 进行分页并返回
		return $data;

	}
	

	public function readSingleTon($id)
	{
		return $this->_model->where("is_deleted", 0)->find($id);
	}

	public function getallgoods($uid)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;


		$data = $this->_model
		                     ->select('cod_goods.id')
					  		  ->where("cod_goods.is_deleted", 0);
					  		  
			if ($uid=='null') {
						$data =$data;
					}else{
						$data =$data-> where("cod_goods.youhuashi", $uid);
					}
		$data	= $data->get()->toArray();
			return count($data);
	
		  
	}
	public function allproduct($uid)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query = $this->_ProductOrder
								->leftJoin("cod_goods","cod_goods.id","=","product_order.product_id")
		                     ->select('country_id as country', DB::raw( "count(*) as productordercount" ) )
					  		  ->where("product_order.is_deleted", 0);
					if ($uid=='null') {
						$query =$query;
					}else{
						$query =$query-> where("cod_goods.youhuashi", $uid);
					}  		 
					  		
	if(count(explode('-',$_GET['Time']))==2){
				$query = $query
							->whereRaw(" date_format(from_unixtime(`comp_product_order`.create_time),'%Y-%m') = '" . $_GET['Time']."'" );

			} elseif(count(explode('-',$_GET['Time']))==1)
								{
				$query = $query-> whereRaw(" date_format(from_unixtime(`comp_product_order`.create_time),'%Y') = '" . $_GET['Time']."'" );
			}elseif(count(explode('-',$_GET['Time']))==3)
								{
				$query = $query-> whereRaw(" date_format(from_unixtime(`comp_product_order`.create_time),'%Y-%m-%d') = '" . $_GET['Time']."'" );
			};

			$query = $query->groupBy("country_id");
					  		  

	
			  			$data	= $query->get()
					  ->toArray();


		//var_dump( $data );
		// dd($data );
		
		if( !empty($data) ) {
			return $data;
		}else{
			return 0;
		}
		  
	}
	public function todayproductorder($uid)
	{

		$query = $this->_ProductOrder ->leftJoin("cod_goods","cod_goods.id","=","product_order.product_id")
		                     ->select('cod_goods.id')
					  		  ->where("cod_goods.is_deleted", 0);

	if ($uid=='null') {
						$query =$query;
					}else{
						$query =$query-> where("cod_goods.youhuashi", $uid);
					}

		
		$query = $query->where("product_order.create_time", '>=', strtotime(trim(date('Y-m-d',time()))." 00:00:00"));
		
		$query = $query->where("product_order.create_time", '<=', strtotime(trim(date('Y-m-d',time()))." 23:59:59"));
		

			  			$data	= $query->get()
					  ->toArray();

			return count($data);
		
		  
	}

public function todaygoods($uid)
	{

	
	$query = $this->_model
		                     ->select('id')
					  		  ->where("is_deleted", 0);

				if ($uid=='null') {
						$query =$query;
					}else{
						$query =$query-> where("cod_goods.youhuashi", $uid);
					}

		
		$query = $query->where("cod_goods.create_time", '>=', strtotime(trim(date('Y-m-d',time()))." 00:00:00"));
		
		$query = $query->where("cod_goods.create_time", '<=', strtotime(trim(date('Y-m-d',time()))." 23:59:59"));
		

			  			$data	= $query->get()
					  ->toArray();

				return count($data);
	}

		public function Turnover($uid)
	{

		$query = $this->_ProductOrder
								->leftJoin("cod_goods","cod_goods.id","=","product_order.product_id")
		                     ->select( 'country_id as country',DB::raw('truncate(sum( paid_price  )/100 ,0 )as totalPrice'))
		                     ->where("product_order.is_deleted", 0);

				if ($uid=='null') {
						$query =$query;
					}else{
						$query =$query-> where("cod_goods.youhuashi", $uid);
					}
					  		  
					  		  
			if(count(explode('-',$_GET['Time']))==2){
				$query = $query
							->whereRaw(" date_format(from_unixtime(`comp_product_order`.create_time),'%Y-%m') = '" . $_GET['Time']."'" );

			} elseif(count(explode('-',$_GET['Time']))==1)
								{
				$query = $query-> whereRaw(" date_format(from_unixtime(`comp_product_order`.create_time),'%Y') = '" . $_GET['Time']."'" );
			}elseif(count(explode('-',$_GET['Time']))==3)
								{
				$query = $query-> whereRaw(" date_format(from_unixtime(`comp_product_order`.create_time),'%Y-%m-%d') = '" . $_GET['Time']."'" );
			};

			$query = $query->groupBy("country_id");

			  			$data	= $query->get()
					  ->toArray();

		if( !empty($data) ) {
			return $data;
		} else {
			return null;
		}
		  
	}



	public function deleteProdcutSetmeal($id, $data)
	{
		return $this->_model->where("id", $id)->update($data);
	}

	public function getproperties($setmealId)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$data = $this->_model
		                      ->leftJoin("rel_to_goods_to_setmeal_to_properties", "rel_to_goods_to_setmeal_to_properties.goods_id", "=", "cod_goods.id")

							  ->leftJoin("setmeal", "setmeal.id", "=", "rel_to_goods_to_setmeal_to_properties.setmeal_id")

							  ->leftJoin("properties", "properties.id", "=", "rel_to_goods_to_setmeal_to_properties.properties_id")
							  
		                      ->where("properties.is_deleted", 0)
		                      ->where("setmeal.id", $setmealId)
					  		  // ->where("cod_goods.id", $id)
					  		  ->select(DB::raw(" ifnull(`comp_properties`.name, '')  as propertiesname"),DB::raw(" ifnull(`comp_properties`.chinese_name, '')  as chinese_name"),DB::raw(" ifnull(`comp_properties`.foreign_name, '')  as foreign_name"),DB::raw(" ifnull(`comp_properties`.image, '')  as image"),DB::raw(" ifnull(`comp_properties`.group_id, '')  as group_id"),DB::raw(" ifnull(`comp_rel_to_goods_to_setmeal_to_properties`.id, '')  as rel_id"))
					  		  ->orderBy("group_id", "asc")

					  		  ->get()
					  		  ->toArray();

					  		  	

					  		
		if( !empty($data) ) {
			return $data;
		}
	}

	public function getList($params=[])
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query = $this->_model->where("category.is_deleted", 0)
								->select('id',"name");
					  		  
					  		  
	
		if( !empty( $params['name'] ) ) {
			$query = $query->where("name", "like", "%" . $params['name'] . "%");
		}

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
}