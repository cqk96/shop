<?php

namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class GoodsToSetmealToPropertiesModel extends BaseModel {

	public function __construct()
	{
		$this->_model = new \EloquentModel\GoodsToSetmealToProperties; 
	}
			private $_model;
			/**
			* 获取指定商品名称，中文名称，外文名称
			* @author 	xww
			* @param 	int/string 		
			* @param 	string 			
			*/
			public function getName($title, $chinese_title,$foreign_title)
			{
				return $this->_model->where("is_deleted", 0)->where("title", $title)->where("chinese_title", $chinese_title)->where("foreign_title", $foreign_title)->get()->toArray();
			}

			public function create($data)
	{
		return $this->_model->insertGetId($data);
	}

	public function multiCreate($data)
	{
		return $this->_model->insert($data);
	}

	/**
	* 根据对应id获取包裹对象
	* @author 	xww
	* @param 	array 		$ids 	rel_to_goods_to_setmeal_to_properties’s id
	* @return 	array
	*/
	public function getSetmealProperyArr( $ids )
	{
		

		$data = $this->_model->select("setmeal.id as setmealid", "setname", "skus", "setprice","properties.id as propertyId", "name as propertiesname", "chinese_name", "foreign_name", "image",'group_id',DB::raw(" ifnull(`comp_rel_to_goods_to_setmeal_to_properties`.id, '')  as rel_id"))
					->leftJoin("setmeal",function($joinClosure){
						$joinClosure->on("setmeal.id", "=", "rel_to_goods_to_setmeal_to_properties.setmeal_id")
								   ->where("setmeal.is_deleted", "=", 0);
					})
					->leftJoin("properties", function($joinClosure){
						$joinClosure->on("properties.id", "=", "rel_to_goods_to_setmeal_to_properties.properties_id")
						            ->where("properties.is_deleted", "=", 0);	
					})
					// ->where("rel_to_goods_to_setmeal_to_properties.deleted", 0)
					->whereIn("rel_to_goods_to_setmeal_to_properties.id", $ids)
					->get()
					->toArray();


		if( empty($data) ) {
			return null;
		}

		$setmealIds = [];
		$returnData = [];
		$groupIds = [];
		$groupTag = null;
		for ($i=0; $i < count($data); $i++) { 
			
			$setmealId = empty($data[$i]['id'])? 0:$data[$i]['id'];

			$propertyId = empty($data[$i]['propertyId'])? 0:$data[$i]['propertyId'];
			$curGroupTag = is_null($data[$i]['group_id'])? 0:$data[$i]['group_id'];

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
			

			if( !in_array($setmealId, $setmealIds) ) {
				$setmealIds[] = $setmealId;
			}	

			$pos = array_keys($setmealIds, $setmealId);
			$index = $pos[0];
		

			if( !isset( $returnData[ $index ] ) ) {
				// $temp['id'] = $setmealId;
				$temp['setname'] = empty($data[$i]['setname'])? '':$data[$i]['setname'];
				$temp['skus'] = empty($data[$i]['skus'])? '':$data[$i]['skus'];
				$temp['setprice'] = empty($data[$i]['setprice'])? 0:$data[$i]['setprice'] * 100;
				$temp['setmealid'] = empty($data[$i]['setmealid'])? '':$data[$i]['setmealid'];
				$temp['properties'] = null;

				$returnData[ $index ] = $temp;
				unset($temp);
			}

			if( $propertyId ) {

				if( !isset( $returnData[ $index ]['properties'][ $groupIndex ] ) ) {
						$propertyArr['propertiesname'] = $data[$i]['propertiesname'];
						$propertyArr['groupProperty'] = [];
						$returnData[ $index ]['properties'][ $groupIndex ] = $propertyArr;
					}

				
				$property['chinese_name'] = $data[$i]['chinese_name'];
				$property['foreign_name'] = $data[$i]['foreign_name'];
				$property['image'] = $data[$i]['image'];
				$property['rel_id'] = $data[$i]['rel_id'];
				$property['group_id'] = $data[$i]['group_id'];
				$returnData[ $index ]['properties'][ $groupIndex ]['groupProperty'][] = $property;
				unset($property);

			}

		}


		return $returnData;

	}

	/**
	* 根据goods setmeal properyies条件获取记录
	*
	*/
	public function getConditionRecords( $params )
	{
		
		$query = $this->_model;

		if( isset( $params['goods_id'] ) ) {
			$query = $query->where("goods_id", $params['goods_id']);
		}

		if( isset( $params['setmeal_id'] ) ) {
			$query = $query->where("setmeal_id", $params['setmeal_id']);
		}

		if( isset( $params['properties_id'] ) ) {
			$query = $query->where("properties_id", $params['properties_id']);
		}

		if( isset( $params['deleted'] ) ) {
			$query = $query->where("deleted", $params['deleted']);
		}

		return $query->get()->toArray();

	}

	/**
	* 将记录从删除状态改为不删除
	*
	*/
	public function setRecordNormal($id)
	{
		return $this->_model->where("id", $id)->update( [ 'deleted'=>0 ] );
	}

}