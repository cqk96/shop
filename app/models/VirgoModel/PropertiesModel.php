<?php

namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class PropertiesModel extends BaseModel {

	public function __construct()
	{
		$this->_model = new \EloquentModel\Properties; 
	}
			private $_model;
			/**
			* 获取套餐名称
			* @author 	xww
			* @param 	int/string 		
			* @param 	string 			
			*/
			public function getName($title)
			{
				return $this->_model->where("is_deleted", 0)->where("name", $title)->get()->toArray();
			}

			public function create($data)
	{
		return $this->_model->insertGetId($data);
	}
	  public function partUpdate($id, $data)
	{
		return $this->_model->where("id", $id)->update($data);
	}
}