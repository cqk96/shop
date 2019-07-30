<?php

namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class CodModel extends BaseModel {

	public function __construct()
	{
		$this->_model = new \EloquentModel\CodGoods; 
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
		return $this->_model->where("id", $id)->update($data);
	}


	public function getListsObject($params=[])
	{

		$query=[];	
			// 分页对象
		$pageObj = new \VirgoUtil\Page2;
			if($params['youhuashi']=='null'){

			$query = $this->_model
								->leftJoin("currency_management", "currency_management.id", "=", "cod_goods.currency_id")
								->where("cod_goods.is_deleted", 0)
					  		  ->select("cod_goods.id", 'cod_goods.erp_id',"cod_goods.catalog","title", "price",'back_symbol','front_symbol',DB::raw(" FROM_UNIXTIME( `comp_cod_goods`.create_time, '%Y-%m-%d %T') as createTime "), "template")	
					  		->orderBy("cod_goods.id", "desc");
			}else {
			$query = $this->_model
								->leftJoin("currency_management", "currency_management.id", "=", "cod_goods.currency_id")
								->where("cod_goods.is_deleted", 0)
					  		  ->select("cod_goods.id", 'cod_goods.erp_id',"cod_goods.catalog","title", "price",'back_symbol','front_symbol',DB::raw(" FROM_UNIXTIME( `comp_cod_goods`.create_time, '%Y-%m-%d %T') as createTime "), "template")
						  -> where("cod_goods.youhuashi",$params['youhuashi'])
					  		->orderBy("cod_goods.id", "desc");
			};

			// $query = $this->_model
		// 						->leftJoin("currency_management", "currency_management.id", "=", "cod_goods.currency_id")
		// 						->where("cod_goods.is_deleted", 0)

					  		  
		// 			  		  ->select("cod_goods.id", 'cod_goods.erp_id',"cod_goods.catalog","title", "price",'back_symbol','front_symbol',DB::raw(" FROM_UNIXTIME( `comp_cod_goods`.create_time, '%Y-%m-%d %T') as createTime "), "template");

		// 	if (!empty($params['youhuashi'])) {
		// 		$query =$query-> where("cod_goods.youhuashi",$params['youhuashi']);
		// 	}
		// 			  		  $query =$query->orderBy("cod_goods.id", "desc");
					  		  
	// var_dump(date('Y-m-d', 1502204401));
	// die;
		if( !empty( $params['title'] ) ) {
			$query = $query->where("title", "like", "%" . $params['title'] . "%");
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
	public function getDetail($id)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$data = $this->_model
		                      ->leftJoin("rel_to_goods_to_setmeal_to_properties", "rel_to_goods_to_setmeal_to_properties.goods_id", "=", "cod_goods.id")

							  ->leftJoin("setmeal", "setmeal.id", "=", "rel_to_goods_to_setmeal_to_properties.setmeal_id")

							  ->leftJoin("properties", "properties.id", "=", "rel_to_goods_to_setmeal_to_properties.properties_id")
							   ->leftJoin("currency_management", "currency_management.id", "=", "cod_goods.currency_id")
							   // ->leftJoin("comments", "comments.productId", "=", "cod_goods.id")
		                      ->where("cod_goods.is_deleted", 0)
		                     ->where("rel_to_goods_to_setmeal_to_properties.deleted",0)
					  		  ->where("cod_goods.id", $id)
					  		  ->select("cod_goods.id", "title",'template','erp_id',"price",'chinese_title','foreign_title','discount','Original_Price','Inventory','sales','rate','labels','Purchaseurl','author','currency_id','category_id','logo','thumbnail','images','updated_at','readme','rush_time','catalog','domain_name','email','language','LINE','pop800id','cod_goods.content','Fb_Id','country', "setmeal.id as setmealId", DB::raw(" ifnull(`comp_setmeal`.setname, '')  as setname"),DB::raw(" ifnull(`comp_setmeal`.skus, '')  as skus"),DB::raw(" ifnull(`comp_setmeal`.setprice, '')  as setprice"),DB::raw(" ifnull(`comp_properties`.name, '')  as propertiesname"),DB::raw(" ifnull(`comp_properties`.chinese_name, '')  as chinese_name"),DB::raw(" ifnull(`comp_properties`.foreign_name, '')  as foreign_name"),DB::raw(" ifnull(`comp_properties`.image, '')  as image"),DB::raw(" ifnull(`comp_properties`.group_id, '')  as group_id"),DB::raw(" ifnull(`comp_rel_to_goods_to_setmeal_to_properties`.id, '')  as rel_id"),DB::raw(" ifnull(`comp_properties`.id, '')  as propertiesid"),DB::raw(" ifnull(`comp_setmeal`.id, '')  as setmealid"),DB::raw(" ifnull(`comp_currency_management`.name, '')  as currency_name"),DB::raw(" ifnull(`comp_currency_management`.abbreviation, '')  as currency_abbreviation"),DB::raw(" ifnull(`comp_currency_management`.front_symbol, '')  as currency_front_symbol"),DB::raw(" ifnull(`comp_currency_management`.back_symbol, '')  as currency_back_symbol"),"cod_goods.title as productName",'rel_to_goods_to_setmeal_to_properties.setmeal_id as setmeal_id','rel_to_goods_to_setmeal_to_properties.properties_id as properties_id')
					  		  ->orderBy("setmeal.id", "asc")
					  		  ->orderBy("properties.id", "asc")
					  		  ->orderBy("properties.group_id", "asc")
					  		  ->get()
					  		  ->toArray();
					// var_dump($data);
					// die;  
			$comment = $this->_model	
								->leftJoin("comments", "comments.productId", "=", "cod_goods.id")	
								->where("cod_goods.id", $id)
								->select('comments.id as comments_id','language','is_anonymous','comments.createtime as createtime','comments.starlevel as comments_starlevel','comments.pictures as comments_pic','comments.content as contents',DB::raw(" if(`comp_comments`.is_anonymous='true', 'Anonymous User',username)  as username"))

						->where("comments.is_deleted", 0)
								->get()
					  		  ->toArray();
					
 				for ($i=0; $i <count($comment)  ; $i++) { 
					$languageid=$comment[$i]['language'];
					$is_anonymous =$comment[$i]['is_anonymous'];
					
					if($languageid==1 && $is_anonymous=='true'){
							$comment[$i]['username']=='匿名用戶';
					}elseif($languageid==2 && $is_anonymous=='true'){
							$comment[$i]['username']= '匿名用戶';
					}elseif($languageid==3 && $is_anonymous=='true'){
							$comment[$i]['username']= 'Anonymous tài';
					}elseif($languageid==4 && $is_anonymous=='true'){
							$comment[$i]['username']= 'ผู้ใช้ที่ไม่ประสงค์ออกนาม';
					}elseif($languageid==6 && $is_anonymous=='true'){
							$comment[$i]['username']= 'Pengguna anonim';
					}
				}	
		if( empty($data) ) {
			return null;
		}

		/*重构返回data*/
		$returnData = [];

		$returnData['id'] = $data[0]['id'];
		$returnData['title'] = $data[0]['title'];
		$returnData['price'] = $data[0]['price'];
		$returnData['chinese_title'] = $data[0]['chinese_title'];
		$returnData['foreign_title'] = $data[0]['foreign_title'];
		$returnData['discount'] = $data[0]['discount'];
		$returnData['Original_Price'] = $data[0]['Original_Price'];
		$returnData['Inventory'] = $data[0]['Inventory'];
		$returnData['sales'] = $data[0]['sales'];
		$returnData['rate'] = $data[0]['rate'];
		$returnData['labels'] = $data[0]['labels'];
		$returnData['logo'] = $data[0]['logo'];
		$returnData['country'] = $data[0]['country'];
		$returnData['author'] = $data[0]['author'];
		$returnData['Fb_Id'] = $data[0]['Fb_Id'];
		$returnData['erp_id'] = $data[0]['erp_id'];
		$returnData['catalog'] = $data[0]['catalog'];
		$returnData['rel_id'] = $data[0]['rel_id'];
		$returnData['template'] = $data[0]['template'];
		$returnData['Purchaseurl'] = $data[0]['Purchaseurl'];
		$returnData['thumbnail'] = $data[0]['thumbnail'];
		$returnData['images'] = $data[0]['images'];
		$returnData['updated_at'] = $data[0]['updated_at'];
		$returnData['readme'] = $data[0]['readme'];
		$returnData['rush_time'] = $data[0]['rush_time'];
		$returnData['domain_name'] = $data[0]['domain_name'];
		$returnData['email'] = $data[0]['email'];
		$returnData['language'] = $data[0]['language'];
		
		$returnData['LINE'] = $data[0]['LINE'];
		$returnData['pop800id'] = $data[0]['pop800id'];
		$returnData['content'] = $data[0]['content'];
		$returnData['category_id'] = $data[0]['category_id'];
		// $returnData['currency']['currency_id'] = $data[0]['currency_id'];
		// $returnData['currency']['currency_name'] = $data[0]['currency_name'];
		// $returnData['currency']['currency_abbreviation'] = $data[0]['currency_abbreviation'];
		// $returnData['currency']['currency_front_symbol'] = $data[0]['currency_front_symbol'];
		// $returnData['currency']['currency_back_symbol'] = $data[0]['currency_back_symbol'];
		$returnData['currency_id'] = $data[0]['currency_id'];
		$returnData['currency_name'] = $data[0]['currency_name'];
		$returnData['currency_abbreviation'] = $data[0]['currency_abbreviation'];
		$returnData['currency_front_symbol'] = html_entity_decode($data[0]['currency_front_symbol']);
		$returnData['currency_back_symbol'] = html_entity_decode($data[0]['currency_back_symbol']);
		$returnData['comment'] =$comment;
		// 	$i=0;
		// 	$returnData['comments']=[];
		// 	for ($i=0; $i < count($data); $i++) { 
		// $temp['comments_id'] = $data[$i]['comments_id'];
		//  $temp['comments_name'] = $data[$i]['username'];
		// $temp['comments']['comments_productName'] = $data[$i]['productName'];
		// $temp['comments']['comments_starlevel'] = $data[$i]['comments_starlevel'];
		// $temp['comments']['comments_contents'] = $data[$i]['contents'];
		//  $temp['comments']['comments_pic'] = $data[$i]['comments_pic'];

		// 		$returnData['comments'][] = $temp;
		// 		}
		// 		
		
		


	

		// 记录已经取出的套餐id
		$setmealIds = [];
		$groupIds = [];
		$groupTag = null;
		$setmealstr = [];
$pid='';
$cid='';
		for ($c=0; $c <count($data) ; $c++) { 
			$pid.= $data[$c]['setmeal_id'];

			$cid.= $data[$c]['properties_id'];
			// $returnData['ppwwwwwwssssssssssswwwwp'] ='(pid:'. $pid.'cid:'. $cid.')';
		}


	$returnData['setmeals'] = [];
		for ($i=0; $i < count($data); $i++) { 

			$setmealId = $data[$i]['setmealId'];

			$curGroupTag = $data[$i]['group_id'];

			if( !is_null( $curGroupTag ) && !is_null($groupTag) ) {
				$groupTag = $curGroupTag;
			}

			if(  !is_null( $curGroupTag ) &&  !in_array($curGroupTag, $groupIds) ){
				$groupIds[] = $curGroupTag;
			}

			if(  !is_null( $curGroupTag ) ){
				$tagPos = array_keys($groupIds, $curGroupTag);
				$groupIndex = $tagPos[0];
			}

			if( is_null($setmealId) || empty($setmealId) ) {
				$setmealId = 0;
			}

			if( !in_array($setmealId, $setmealIds) ) {
				$setmealIds[] = $setmealId;
			}

			// var_dump($setmealId);
			$pos = array_keys($setmealIds, $setmealId);

			$index = $pos[0];
			
// var_dump($data);
// 			die;
			/*判断套餐是否已经放置于重构数组中*/
			if( empty( $returnData['setmeals'][ $index ] ) ) {

				$returnData['setmealJsonStr'][ $index ]['pid'] = $setmealId;
				$returnData['setmealJsonStr'][ $index ]['cid'] = [];

				// 存储套餐对应数据1111
				
				$returnData['setmeals'][ $index ]['setname'] = empty($data[$i]['setname'])? '':$data[$i]['setname'];
				$returnData['setmeals'][ $index ]['skus'] = empty($data[$i]['skus'])? '':$data[$i]['skus'];
				$returnData['setmeals'][ $index ]['setprice'] = empty($data[$i]['setprice'])? '':$data[$i]['setprice'];
				$returnData['setmeals'][ $index ]['setmealid'] = empty($data[$i]['setmealid'])? '':$data[$i]['setmealid'];
			
				if( empty($data[$i]['propertiesname']) ) {
					$returnData['setmeals'][ $index ]['rel_id'] = $data[$i]['rel_id'];
				}
				

				// 构造套餐中属性数组
				$returnData['setmeals'][ $index ]['properties'] = [];
			}
			
		// if( $groupTag == $data[$i]['group_id'] ){
			if( empty($data[$i]['setname']) ) {
				// 只有属性
				$returnData['setmealJsonStr'][ $index ]['cid'][] = $data[$i]['propertiesid'];
				if(  !is_null( $curGroupTag ) ){

					if( !isset( $returnData['setmeals'][ $index ]['properties'][ $groupIndex ] ) ) {
						$propertyArr['propertiesname'] = $data[$i]['propertiesname'];
						$propertyArr['groupProperty'] = [];
						$returnData['setmeals'][ $index ]['properties'][ $groupIndex ] = $propertyArr;
					}

					$temp['chinese_name'] = $data[$i]['chinese_name'];
					$temp['foreign_name'] = $data[$i]['foreign_name'];
					$temp['propertiesid'] = $data[$i]['propertiesid'];
					$temp['image'] = $data[$i]['image'];
					$temp['rel_id'] = $data[$i]['rel_id'];
					$temp['group_id'] = $data[$i]['group_id'];

					$returnData['setmeals'][ $index ]['properties'][ $groupIndex ]['groupProperty'][] = $temp;
					unset($temp);
					
				}

			} else if( !empty($data[$i]['setname']) && !empty($data[$i]['propertiesname']) ){
				
				// 套餐 + 属性
				if(  !is_null( $curGroupTag ) ){

					$returnData['setmealJsonStr'][ $index ]['cid'][] = $data[$i]['propertiesid'];

					$propertyIndex = null;

					/*内部循环这个套餐的属性集  判断是否有相同的group id存在*/
					/*如果存在就更新 没有就新建*/
					for ($j=0; $j < count( $returnData['setmeals'][ $index ]['properties'] ); $j++) { 

						// 如果记录的属性名 与 套餐中的属性名相同时
						// 循环内部 集合 判断是否group id相同 相同则记录
						if( $data[$i]['propertiesname']==$returnData['setmeals'][ $index ]['properties'][$j]['propertiesname'] ){
							$groupSet = $returnData['setmeals'][ $index ]['properties'][$j]['groupProperty'];
							for ($k=0; $k < count( $groupSet ); $k++) { 						
								if( $curGroupTag==$groupSet[$k]['group_id'] ) {

									/*记录此时的属性集合 index*/
									$propertyIndex = $j;
									break 2;

								}
							}
						}

					}

					if( is_null($propertyIndex) ) {
						/*新建属性集合*/
						$propertyArr['propertiesname'] = $data[$i]['propertiesname'];
						$propertyArr['groupProperty'] = [];
						$returnData['setmeals'][ $index ]['properties'][] = $propertyArr;

						$propertyIndex = count( $returnData['setmeals'][ $index ]['properties'] )-1;
					}

					$temp['chinese_name'] = $data[$i]['chinese_name'];
					$temp['foreign_name'] = $data[$i]['foreign_name'];
					$temp['propertiesid'] = $data[$i]['propertiesid'];
					$temp['image'] = $data[$i]['image'];
					$temp['rel_id'] = $data[$i]['rel_id'];
					$temp['group_id'] = $data[$i]['group_id'];


					$returnData['setmeals'][ $index ]['properties'][ $propertyIndex ]['groupProperty'][] = $temp;
					unset($temp);
					
				}
					
				

				// $temp['propertiesname'] = $data[$i]['propertiesname'];
				// $temp['chinese_name'] = $data[$i]['chinese_name'];
				// $temp['foreign_name'] = $data[$i]['foreign_name'];
				// $temp['image'] = $data[$i]['image'];
				// $temp['rel_id'] = $data[$i]['rel_id'];
				// $temp['group_id'] = $data[$i]['group_id'];
				// $returnData['setmeals'][ $index ]['properties'][] = $temp;
				// unset($temp);
			} else {
				// 套餐
				continue;
			}
			
			$groupTag = $curGroupTag;

		}
// var_dump(array_unique($cid));
// die;
		$returnData['setmealJsonStr'] = json_encode( $returnData['setmealJsonStr'] );
		return $returnData;


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
					  		  ->update(['rel_to_goods_to_setmeal_to_properties.deleted'=>1]);

					  		  
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


	public function getListsComment($params=[])
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$data = $this->_model 
								
								->where("cod_goods.is_deleted", 0)
    				  		  ->select("cod_goods.id", "chinese_title",'youhuashi');

		if ($params['youhuashi']=='null') {
				$data =$data;
			}else{
				$data =$data-> where("cod_goods.youhuashi",$params['youhuashi']);
			}	 
					  		  $data = $data->get()->toArray();

				return $data;
	
					}
	public function getsetmeal($id)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$data = $this->_model
		                      ->leftJoin("rel_to_goods_to_setmeal_to_properties", "rel_to_goods_to_setmeal_to_properties.goods_id", "=", "cod_goods.id")

							  ->leftJoin("setmeal", "setmeal.id", "=", "rel_to_goods_to_setmeal_to_properties.setmeal_id")

							  ->leftJoin("properties", "properties.id", "=", "rel_to_goods_to_setmeal_to_properties.properties_id")
							   ->leftJoin("currency_management", "currency_management.id", "=", "cod_goods.currency_id")
							   ->leftJoin("comments", "comments.productId", "=", "cod_goods.id")
		                      ->where("cod_goods.is_deleted", 0)
		                     
					  		  ->where("rel_to_goods_to_setmeal_to_properties.setmeal_id", $id)
					  		  ->select( "setmeal.id as setmealId", DB::raw(" ifnull(`comp_setmeal`.setname, '')  as setname"),DB::raw(" ifnull(`comp_setmeal`.skus, '')  as skus"),DB::raw(" ifnull(`comp_setmeal`.setprice, '')  as setprice"),DB::raw(" ifnull(`comp_properties`.name, '')  as propertiesname"),DB::raw(" ifnull(`comp_properties`.chinese_name, '')  as chinese_name"),DB::raw(" ifnull(`comp_properties`.foreign_name, '')  as foreign_name"),DB::raw(" ifnull(`comp_properties`.image, '')  as image"),DB::raw(" ifnull(`comp_properties`.group_id, '')  as group_id"),DB::raw(" ifnull(`comp_rel_to_goods_to_setmeal_to_properties`.id, '')  as rel_id"),DB::raw(" ifnull(`comp_properties`.id, '')  as propertiesid"),DB::raw(" ifnull(`comp_setmeal`.id, '')  as setmealid"),DB::raw(" ifnull(`comp_currency_management`.name, '')  as currency_name"),DB::raw(" ifnull(`comp_currency_management`.abbreviation, '')  as currency_abbreviation"),DB::raw(" ifnull(`comp_currency_management`.front_symbol, '')  as currency_front_symbol"),DB::raw(" ifnull(`comp_currency_management`.back_symbol, '')  as currency_back_symbol"),'comments.id as comments_id',"cod_goods.title as productName",'comments.starlevel as comments_starlevel','comments.pictures as comments_pic','comments.content as contents',DB::raw(" if(`comp_comments`.is_anonymous='true', '匿名用户',username)  as username"))
					  		  ->orderBy("group_id", "asc")

					  		  ->get()
					  		  ->toArray();
					// var_dump($data);
					// die;  		
		if( empty($data) ) {
			return null;
		}

		/*重构返回data*/
		$returnData = [];

	

		$returnData['setmeals'] = [];


	

		// 记录已经取出的套餐id
		$setmealIds = [];
		$groupIds = [];
		$groupTag = null;
		for ($i=0; $i < count($data); $i++) { 

			$setmealId = $data[$i]['setmealId'];

			$curGroupTag = $data[$i]['group_id'];

			if( !is_null( $curGroupTag ) && !is_null($groupTag) ) {
				$groupTag = $curGroupTag;
			}

			if(  !is_null( $curGroupTag ) &&  !in_array($curGroupTag, $groupIds) ){
				$groupIds[] = $curGroupTag;
			}

			if(  !is_null( $curGroupTag ) ){
				$tagPos = array_keys($groupIds, $curGroupTag);
				$groupIndex = $tagPos[0];
			}

			if( is_null($setmealId) || empty($setmealId) ) {
				$setmealId = 0;
			}

			if( !in_array($setmealId, $setmealIds) ) {
				$setmealIds[] = $setmealId;
			}

			// var_dump($setmealId);
			$pos = array_keys($setmealIds, $setmealId);

			$index = $pos[0];
			
			/*判断套餐是否已经放置于重构数组中*/
			if( empty( $returnData['setmeals'][ $index ] ) ) {

				// 存储套餐对应数据
				
				$returnData['setmeals'][ $index ]['setname'] = empty($data[$i]['setname'])? '':$data[$i]['setname'];
				$returnData['setmeals'][ $index ]['skus'] = empty($data[$i]['skus'])? '':$data[$i]['skus'];
				$returnData['setmeals'][ $index ]['setprice'] = empty($data[$i]['setprice'])? '':$data[$i]['setprice'];
				$returnData['setmeals'][ $index ]['setmealid'] = empty($data[$i]['setmealid'])? '':$data[$i]['setmealid'];
			
				if( empty($data[$i]['propertiesname']) ) {
					$returnData['setmeals'][ $index ]['rel_id'] = $data[$i]['rel_id'];
				}
				

				// 构造套餐中属性数组
				$returnData['setmeals'][ $index ]['properties'] = [];
			}
		// if( $groupTag == $data[$i]['group_id'] ){
			if( empty($data[$i]['setname']) ) {
				// 只有属性

				if(  !is_null( $curGroupTag ) ){

					if( !isset( $returnData['setmeals'][ $index ]['properties'][ $groupIndex ] ) ) {
						$propertyArr['propertiesname'] = $data[$i]['propertiesname'];
						$propertyArr['groupProperty'] = [];
						$returnData['setmeals'][ $index ]['properties'][ $groupIndex ] = $propertyArr;
					}

					$temp['chinese_name'] = $data[$i]['chinese_name'];
					$temp['foreign_name'] = $data[$i]['foreign_name'];
					$temp['propertiesid'] = $data[$i]['propertiesid'];
					$temp['image'] = $data[$i]['image'];
					$temp['rel_id'] = $data[$i]['rel_id'];
					$temp['group_id'] = $data[$i]['group_id'];

					$returnData['setmeals'][ $index ]['properties'][ $groupIndex ]['groupProperty'][] = $temp;
					unset($temp);
					
				}

			} else if( !empty($data[$i]['setname']) && !empty($data[$i]['propertiesname']) ){
				// 套餐 + 属性
				// 
				if(  !is_null( $curGroupTag ) ){

					if( !isset( $returnData['setmeals'][ $index ]['properties'][ $groupIndex ] ) ) {
						$propertyArr['propertiesname'] = $data[$i]['propertiesname'];
						$propertyArr['groupProperty'] = [];
						$returnData['setmeals'][ $index ]['properties'][ $groupIndex ] = $propertyArr;
					}

					$temp['chinese_name'] = $data[$i]['chinese_name'];
					$temp['foreign_name'] = $data[$i]['foreign_name'];
					$temp['propertiesid'] = $data[$i]['propertiesid'];
					$temp['image'] = $data[$i]['image'];
					$temp['rel_id'] = $data[$i]['rel_id'];
					$temp['group_id'] = $data[$i]['group_id'];

					$returnData['setmeals'][ $index ]['properties'][ $groupIndex ]['groupProperty'][] = $temp;
					unset($temp);
					
				}

				// $temp['propertiesname'] = $data[$i]['propertiesname'];
				// $temp['chinese_name'] = $data[$i]['chinese_name'];
				// $temp['foreign_name'] = $data[$i]['foreign_name'];
				// $temp['image'] = $data[$i]['image'];
				// $temp['rel_id'] = $data[$i]['rel_id'];
				// $temp['group_id'] = $data[$i]['group_id'];
				// $returnData['setmeals'][ $index ]['properties'][] = $temp;
				// unset($temp);
			} else {
				// 套餐
				continue;
			}
			
			$groupTag = $curGroupTag;

		}
		
		return $returnData;

	}


	public function getcatalog( $catalog )
	{
		return $this->_model->where("is_deleted", 0)->where("catalog", $catalog)->take(1)->get()->toArray();
	}
	/**
	* 根据二级目录查询商品
	*
	*/
	public function searchCatalog( $catalog )
	{
		$data = $this->_model->where("is_deleted", 0)->where("catalog", $catalog)->take(1)->get()->toArray();
		return empty( $data )? null:$data[0];
	}

}