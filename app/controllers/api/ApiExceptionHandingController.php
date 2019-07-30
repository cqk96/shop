<?php
/**
* php version 5.5.12
* app 异常奔溃接口处理
* @author  xww<5648*****@qq.com>
* @copyright  xww  20161214
* @version 1.0.0
*/
namespace VirgoApi;
class ApiExceptionHandingController extends ApiBaseController
{
	/**
	* 自定义函数对象
	* @var object
	*/
	private $_functionObj;

	/**
	* api参数数组
	* @var array
	*/
	private $_configs;

	public function __construct()
	{
		$this->_configs = parent::change();
		$this->_functionObj = new \VirgoUtil\Functions;
		parent::__construct();
	}

	/**
	* 异常状态上传
	* @author xww
	* @return void
	*/ 
	public function pushError()
	{
		
		$needs = [
			'list'
		];

		//验证
		$this->configValid('required',$this->_configs,$needs);

		$listArr = json_decode(html_entity_decode($this->_configs['list']), true);
		
		foreach ($listArr as $singleList) {
			$singleList['create_time'] = time();
			\EloquentModel\ExceptionHandling::insert($singleList);
		}

		$return = $this->functionObj->toAppJson(null, '001', 'ok', true);

		// 输出
		$this->responseResult($return);

	}

}