<?php
namespace VirgoApi\User\Acre;
use VirgoApi;
class ApiAcreController extends VirgoApi\ApiBaseController{

	/**
	* api参数数组
	* @var array
	*/
	private $_configs;

	public function __construct()
	{
		$this->_configs = parent::change();
		$this->functionObj = new \VirgoUtil\Functions;
	}

	/**
	* @SWG\Get(path="/api/v1/user/acre/works", tags={"Acre"}, 
	*  summary="获取用户工作过的地块列表",
	*  description="用户验证后获取用户工作过的地块列表",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "AllAcre", "status": { "code": "001", "message": "获取用户工作过的地块列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/AllAcre")
	*   )  
	*  )
	* )
	* 获取用户工作过的地块列表
	* @author 	xww
	* @return 	json
	*/
	public function worksAcre()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 对象
			$model = new \VirgoModel\AreaRelManagerModel;

			$data = $model->getUserWorksAcre( $uid );
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取用户工作过的地块列表成功', true);

		} catch(\Exception $e) {

			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}