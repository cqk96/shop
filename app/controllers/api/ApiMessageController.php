<?php
namespace VirgoApi;
class ApiMessageController extends ApiBaseController
{

	/**
	* api参数数组
	* @var array
	*/
	private $_configs;

	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
		$this->_model = new \VirgoModel\PcNoticeMessageModel;
		$this->_configs = parent::change();
	}

	/**
	* 创建pc推送消息
	* @author 	xww
	* @return 	json
	*/ 
	public function createPcForgetPwdNoticeMessage()
	{
		
		try{

			//验证 
			$this->configValid('required',$this->_configs,['userId', "content"]);

			$rs = $this->_model->create($this->_configs['userId'], $this->_configs['content']);

			if(!$rs){throw new \Exception("创建pc推送消息失败", '005'); }

			$return = $this->functionObj->toAppJson(null, '001', '创建pc推送消息成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 获取pc提醒消息--一定时间范围内
	* @author 	xww
	* @return 	json
	*/ 
	public function timeRange()
	{
		
		try{

			//验证 
			$this->configValid('required',$this->_configs,['userId']);

			$data = $this->_model->timeRange($this->_configs['userId']);

			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取pc消息成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}