<?php
namespace VirgoApi\User\Menu;
class ApiMenuController extends \VirgoApi\ApiBaseController
{

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
	* @SWG\Get(path="/api/v1/user/menu/lists", tags={"Menu"},
	*  summary="获取后台用户所能看见的菜单(登录后显示的菜单)",
	*  description="通过传入账号和令牌 获取该名用户能看见的后台菜单",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples= {"application/json": {"data": "Menu", "status": {"code": "001", "message": "获取菜单成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/Menu"
	*   )
	*  )
	* )
	*
	* 获取用户拥有的后台菜单--所有菜单
	* @author 	xww
	* @return 	json
	*/
	public function lists()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			// 实例化对象
			$model = new \VirgoModel\SysMenuModel;

			$data = $model->getUserBackMenu( $user[0]['id'] );

			$return = $this->functionObj->toAppJson($data, '001', '获取菜单成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}