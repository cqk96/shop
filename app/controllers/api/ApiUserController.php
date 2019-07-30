<?php
namespace VirgoApi;
class ApiUserController extends ApiBaseController
{

	/**
	* api参数数组
	* @var array
	*/
	private $_configs;

	public function __construct()
	{
		$this->userObj = new \VirgoModel\UserModel;
		$this->_configs = parent::change();
		$this->functionObj = new \VirgoUtil\Functions;
	}

	/**
	* 查询用户
	* @author 	xww
	* @return 	void
	*/ 
	public function search()
	{
		
		try{

			//验证 
			$this->configValid('required',$this->_configs,['search']);		

			$data = $this->userObj->searchUser($this->_configs['search']);

			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', "查询成功", true);
			
		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}