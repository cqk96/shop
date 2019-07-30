<?php
/**
* model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/ 
namespace Module\Activity\VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
use VirgoModel;
class ApplyActivityModel {

	/*
	@param object  reflect this model's  eloquent model object
	*/
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \Module\Activity\EloquentModel\ApplyActivity;
	}

	/**
	* 用户是否已经报名过的
	* @author xww
	* @param  [$aid]    int/string    活动id
	* @param  [$uid]    int/string    用户id
	* @return bool
	*/ 
	public function userIsApply($aid, $uid)
	{
		
		$count = $this->_model->where("active_id", '=', $aid)
			         ->where("user_id", '=', $uid)
			         ->where("is_deleted", '=', 0)
			         ->count();
			         
		return $count? true:false;

	}

	/**
	* 获取用户报名过的活动
	* @author 	xww
	* @param 	int/string 		$uid
	* @return 	array
	*/
	public function getUserApplyActiviyies($uid)
	{
		return $this->_model->where("user_id", '=', $uid)
			         ->where("is_deleted", '=', 0)
			         ->select("active_id")
			         ->get()
			         ->toArray();
	}

	/**
	* 创建记录
	* @author xww
	* @param  array    $data
	* @return int
	*/ 
	public function create($data)
	{
		
		return  $this->_model->insertGetId($data);

	}

	/**
	* 根据年份 获取用户已报名过的活动
	* @author 	xww
	* @param 	int/string 		$uid
	* @param 	int/string 		$year
	* @return 	array
	*/
	public function getUserYearActivityCount($uid, $year)
	{
		
		$unixTime = strtotime( $year . "-12-31 23:59:59" );
		return $this->_model->where("user_id", '=', $uid)
			         ->where("is_deleted", '=', 0)
			         ->where("create_time", "<=", $unixTime)
			         ->whereNotNull("create_time")
			         ->select( DB::raw(" from_unixtime(create_time, '%c') as month, count(*) as monthCount ") )
			         ->groupBy("month")
			         ->get()
			         ->toArray();
	}

}