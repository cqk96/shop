<?php
namespace VirgoApi\User\Department;
use VirgoApi;
class ApiDepartmentController extends VirgoApi\ApiBaseController{

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
	* @SWG\Get(path="/api/v1/user/department/groupMembers", tags={"Department"}, 
	*  summary="获取用户所在班组列表",
	*  description="用户验证后获取用户所在班组列表",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "DepartmentList", "status": { "code": "001", "message": "获取用户所在班组成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/DepartmentList")
	*   )  
	*  )
	* )
	* 用户所在班组
	* @author 	xww
	* @return 	json
	*/
	public function groupMembers()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 对象
			$model = new \VirgoModel\DepartmentRelUserModel;

			$data = $model->getUserDepartments($uid, "%班组");
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取用户所在班组成功', true);

		} catch(\Exception $e) {

			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}