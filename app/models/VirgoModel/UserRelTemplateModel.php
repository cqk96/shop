<?php
namespace VirgoModel;
class UserRelTemplateModel {

	public function __construct()
	{
		$this->_model = new \EloquentModel\UserRelTemplate;
	}

	/**
	* 通过用户id获取拥有的模板列表
	* @author 	xww
	* @param 	int/string 	user's id
	* @return 	array
	*/ 
	public function getListFromUserId($uid)
	{
		
		return $this->_model->leftJoin("company_template_application", "company_template_application.id", "=", "user_rel_template.application_id")
					 ->leftJoin("company_template_management", "company_template_management.id", "=", "user_rel_template.template_id")
					 ->where("user_rel_template.is_deleted", 0)
					 ->where("user_rel_template.user_id", $uid)
					 ->orderBy("user_rel_template.id", "desc")
					 ->select("company_template_management.name as templateName", "company_template_application.name as applicationName", "user_rel_template.template_id as id")
					 ->get()
					 ->toArray();

	}

	/**
	* 是否是本人数据集
	* @author 	xww
	* @param 	int/string 	$uid
	* @param 	int/string 	$id
	* @return 	bool
	*/ 
	public function isUserRecord($uid, $id)
	{
		return $this->_model->where("id", $id)->where("user_id", $uid)->count()? true:false;
	}

	/**
	* 更新
	* @author 	xww
	* @param 	int/string 	$id
	* @param 	array 	$data
	* @return 	int affect number
	*/ 
	public function update($id, $data)
	{
		return $this->_model->where("id", $id)->update($data);
	}

	/**
	* 获取用户正常使用的用途id
	* @author 	xww
	* @param 	int/string 	$uid
	* @return 	array
	*/ 
	public function getUserApplicationWithNormal($uid)
	{
		$return = [];
		$data = $this->_model->where("user_id", $uid)->where("is_deleted", 0)->get()->toArray();
		if(!empty($data)){
			for ($i=0; $i < count($data); $i++) { 
				array_push($return, $data[$i]['application_id']);
			}	
		}
		
		return $return;

	}

	/**
	* 创建
	* @author 	xww
	* @param 	array 	$data
	* @return 	int affect number
	*/ 
	public function create($data)
	{
		return $this->_model->insert($data);
	}

	/**
	* 发返回id的创建
	* @author 	xww
	* @param 	array 	$data
	* @return 	id
	*/
	public function createSingleTon($data)
	{
		return $this->_model->insertGetId($data);
	} 

}