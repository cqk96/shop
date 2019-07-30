<?php
namespace VirgoModel;
class PcNoticeMessageModel {

	public function __construct()
	{
		$this->_model = new \EloquentModel\PcNoticeMessage;
	}

	/**
	* 创建pc消息通知
	* @author 	xww
	* @param 	int/string 		$userId
	* @param 	string 			$content
	* @return   row
	*/ 
	public function create($userId, $content)
	{
		
		$data['user_id'] = $userId;
		$data['content'] = $content;
		$data['create_time'] = time();
		$data['update_time'] = time();
		return $this->_model->insert($data);

	}

	/**
	* 获取时间范围内所属用户的消息
	* @author 	xww
	* @return 	int/string 		$userId
	* @return 	array
	*/ 
	public function timeRange($userId, $time=1)
	{
		return $this->_model
					->where("user_id", $userId)
					->where("is_deleted", 0)
					->where("create_time", ">=", time()-$time)
					->get()->toArray();
	}

}