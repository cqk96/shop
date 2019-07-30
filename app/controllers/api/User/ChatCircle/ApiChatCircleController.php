<?php
namespace VirgoApi\User\ChatCircle;
use Illuminate\Database\Capsule\Manager as DB;
class ApiChatCircleController extends \VirgoApi\ApiBaseController
{

	/**
	* api参数数组
	* @var array
	*/
	private $_configs;

	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
		$this->_configs = parent::change();
	}

	/**
	* 获取朋友圈列表
	* @author 	xww
	* @return 	json
	*/
	public function lists()
	{
		
		try{

			//获取用户
			$user = $this->getUserApi($this->_configs);	

			// 必要验证 id
			$this->configValid('required',$this->_configs,['page', 'size']);

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			// 实例化对象
			$chatCircleModelObj = new \VirgoModel\ChatCircleModel;

			$data = $chatCircleModelObj->getUserChatCircleLists( $user[0]['id'], $skip, $size, $user[0]['id'] );
			$data = empty($data)? null:$data;

			$dataCount = $chatCircleModelObj->getUserChatCircleListsCount( $user[0]['id'] );
			$totalPage = is_null($size)? 1:ceil( $dataCount / $size );
			$totalPage = is_null($data)? 0:$totalPage;

			$return = $this->functionObj->toAppJson(['data'=>$data, 'totalPage'=>$totalPage], '001', '获取说说列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

}