<?php
namespace VirgoApi\Department\User\Leader;
class ApiLeaderController extends \VirgoApi\ApiBaseController
{

	/**
	* api参数数组
	* @var array
	*/
	private $_configs;

	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
		$this->model = new \VirgoModel\DepartmentRelUserModel;
		$this->_configs = parent::change();
	}

	/**
	* 更新部门领导人
	* @author 	xww
	* @return 	string/object 	json 	
	*/
	public function update()
	{
		
		try{

			// 必要验证 id
			$this->configValid('required',$this->_configs,['id']);

			if(empty($_COOKIE['user_id'])) { throw new \Exception("重新登录", '002'); }

			// 判断是否有此菜单
			$hasRecord = $this->model->read($this->_configs['id']);
			if(empty($hasRecord)) { throw new \Exception("部门用户不存在或已删除", '006'); }

			$updateData['update_time'] = time();
			$updateData['is_leader'] = 1;

			// 进行删除操作
			$rs = $this->model->updatePart($this->_configs['id'], $updateData);
			if(!$rs) {
				throw new \Exception("更新部门领导失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '更新部门领导成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

}