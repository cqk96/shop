<?php

namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class CommentModel extends BaseModel {

	public function __construct()
	{
		$this->_model = new \EloquentModel\Comment; 
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
		return $this->_model->where("id", $id)->where("comments.is_deleted", 0)->update($data);
	}


	public function getListsObject($params=[])
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query = $this->_model->leftJoin("cod_goods", "cod_goods.id", "=", "comments.productId")

								->where("comments.is_deleted", 0)
								->select('comments.id',"cod_goods.title as productName",'starlevel','comments.content','username','is_anonymous','comments.user_id')
								->orderBy('comments.id','desc');
					  		  
				if ($params['youhuashi']=='null') {
						$query =$query;
					}else{
						$query =$query-> where("comments.user_id",$params['youhuashi']);
					}	  		  
	
		if( !empty( $params['productName'] ) ) {
			
			$query = $query->where("cod_goods.title", "like", "%" . $params['productName'] . "%");
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


	public function readSingleTon($id)
	{
		return $this->_model->where("is_deleted", 0)->find($id);
	}

	public function goodsDelete($id)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		return $this->_model
		                      ->leftJoin("rel_to_goods_to_setmeal_to_properties", "rel_to_goods_to_setmeal_to_properties.goods_id", "=", "cod_goods.id")
		                      ->where("cod_goods.is_deleted", 0)
					  		  ->where("cod_goods.id", $id)
					  		  ->update(['cod_goods.is_deleted'=>0, 'rel_to_goods_to_setmeal_to_properties.deleted'=>1]);

					  		  
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

					  		  	var_dump($data);
								die;

					  		
		if( !empty($data) ) {
			return $data;
		}
	}
		public function getdetail($id)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query = $this->_model->leftJoin("cod_goods", "cod_goods.id", "=", "comments.productId")
								->where("comments.id", $id)
								->where("comments.is_deleted", 0)
								->select('comments.id','comments.createtime',"cod_goods.id as productId",'createtime','starlevel','comments.content','pictures','username','user_id','is_anonymous',DB::raw(" if(`comp_comments`.is_anonymous='true', '匿名用户',username)  as anonymous"));
					  		  
				  		  


		// 获取记录
		$data = $query->get()->toArray();

		return $data;

	}
}