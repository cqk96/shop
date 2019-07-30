<?php

namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class IntegralsModel extends BaseModel {

	public function __construct()
	{
		$this->_model = new \EloquentModel\Integrals; 
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
								->select('comments.id',"cod_goods.title as productName",'starlevel','comments.content','username','is_anonymous','comments.user_id');
					  		  
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


	
}