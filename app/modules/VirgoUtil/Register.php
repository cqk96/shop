<?php
namespace VirgoUtil;

class Register {

	public static $model;
	protected $objects = [];

	private function __construct()
	{

	}

	/**
	* 获取实例化对象
	* @author 	xww
	* @return 	instance
	*/ 
	public static function getInstance()
	{
		
		if(self::$model){
			return self::$model;
		} else {
			self::$model = new self();
			return self::$model;
		}

	}

	/**
	* 注册变量
	* @author 	xww
	* @param 	string 		$alias    	别名
	* @param 	object 		$obj    	实例化的变量
	* @return 	void
	*/ 
	public function _set($alias, $obj)
	{
		$this->objects[$alias] = $obj;
	}

	/**
	* 获取变量
	* @author 	xww
	* @param 	string 		$alias    	别名
	* @return 	object
	*/ 
	public function _get($alias)
	{
		return $this->objects[$alias];
	}

}