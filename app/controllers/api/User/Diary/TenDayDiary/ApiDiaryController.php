<?php
namespace VirgoApi\User\Diary\TenDayDiary;
class ApiDiaryController extends \VirgoApi\ApiBaseController{

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
	* @SWG\Get(path="/api/v1/user/diary/tenDayDiary/lists", tags={"Diary"}, 
	*  summary="获取我的十日报日志",
	*  description="用户鉴权后 通过传入的page,size获取数据列表",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "UserDiary", "status": { "code": "001", "message": "获取我的十日报列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/UserDiary"
	*   )
	*  )
	* )
	* 获取我的十日报日志
	* @author 	xww
	* @return 	json
	*/
	public function lists()
	{

		try {

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\TenDayDiaryModel;

			//验证
			$this->configValid('required',$this->_configs,['page', 'size']);

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$data = $model->getUserDiaryLists($uid, $skip, $size);
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取我的十日报列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/user/diary/tenDayDiary/backLists", tags={"Diary"}, 
	*  summary="获取我的十日报日志 PC",
	*  description="用户鉴权后 通过传入的page,size获取数据列表",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="userName", type="string", required=false, in="query", description="用户名"),
	*  @SWG\Parameter(name="departmentName", type="string", required=false, in="query", description="部门名"),
	*  @SWG\Parameter(name="acreName", type="string", required=false, in="query", description="地块名"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "BackUserDiary", "code": "001", "message": "获取我的十日报列表成功", "totalCount": 3 } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/BackUserDiary"
	*   )
	*  )
	* )
	* 获取我的十日报日志
	* @author 	xww
	* @return 	json
	*/
	public function backLists()
	{

		try {

			//验证 
			$user = $this->getUserApi($this->_configs, 1);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\TenDayDiaryModel;

			//验证
			$this->configValid('required',$this->_configs,['page', 'size']);

			$acreName = empty( $this->_configs['acreName'] )? null:$this->_configs['acreName'];
			$departmentName = empty( $this->_configs['departmentName'] )? null:$this->_configs['departmentName'];
			$userName = empty( $this->_configs['userName'] )? null:$this->_configs['userName'];

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$pageObj = $model->getBackUserDiaryListsObj($uid, $skip, $size, $acreName, $departmentName, $userName);

			$data = $pageObj->data;
			$data = empty($data)? null:$data;

			$totalCount = $pageObj->totalCount;

			$return = $this->functionObj->toLayuiJson($data, '001', '获取我的十日报列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}

}