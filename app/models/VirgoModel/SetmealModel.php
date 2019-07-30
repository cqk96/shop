<?php

namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class SetmealModel extends BaseModel {

	public function __construct()
	{
		$this->_model = new \EloquentModel\Setmeal; 
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
				return $this->_model->where("is_deleted", 0)->where("setname", $name)->get()->toArray();
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