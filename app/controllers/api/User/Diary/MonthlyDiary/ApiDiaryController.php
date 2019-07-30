<?php
namespace VirgoApi\User\Diary\Monthly;
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
	* @SWG\Get(path="/api/v1/user/diary/monthly/lists", tags={"MonthlyDiary"}, 
	*  summary="获取我的月报",
	*  description="用户鉴定后 通过传入的page,size获取分页数据",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "UserMonthlyDiary", "status": { "code": "001", "message": "获取我的月报列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/UserMonthlyDiary")
	*   )
	*  )
	* )
	* 我的月报
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
			$model = new \VirgoModel\MonthlyDiaryModel;

			//验证
			$this->configValid('required',$this->_configs,['page', 'size']);

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$data = $model->getUserDiaryLists($uid, $skip, $size);

			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取我的月报列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 我的审阅月报--已审阅、未审阅列表
	* @SWG\Get(path="/api/v1/user/diary/monthly/read/lists", tags={"MonthlyDiary"}, 
	*  summary="获取我的已阅/未阅月报",
	*  description="用户鉴定后 通过传入的page,size获取分页数据",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="statusId", type="integer", required=false, in="query", description="是否已经阅读过 0否1是", default=0),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "UserReadMonthlyDiary", "status": { "code": "001", "message": "获取我的阅读月报列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/UserReadMonthlyDiary")
	*   )
	*  )
	* )
	* @author 	xww
	* @return 	json
	*/
	public function readLists()
	{

		try {

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\DiaryReadModel;

			//验证
			$this->configValid('required',$this->_configs,['page', 'size']);

			// 0未读 1已读
			$statusId = !isset($this->_configs['statusId'])? null:$this->_configs['statusId'];

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$data = $model->getUserReadMonthlyDiaryLists($uid, $statusId, $skip, $size);

			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', '获取我的月报列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/user/diary/monthly/backLists", tags={"MonthlyDiary"}, 
	*  summary="获取我的月报 pc使用",
	*  description="用户鉴定后 通过传入的page,size获取分页数据",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="name", type="string", required=false, in="query", description="用户名"),
	*  @SWG\Parameter(name="year", type="string", required=false, in="query", description="年份"),
	*  @SWG\Parameter(name="month", type="string", required=false, in="query", description="月份"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "BackUserMonthlyDiary", "code": "001", "message": "获取我的月报列表成功", "totalCount": 2 } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/BackUserMonthlyDiary")
	*   )
	*  )
	* )
	* 我的月报
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
			$model = new \VirgoModel\MonthlyDiaryModel;

			//验证
			$this->configValid('required',$this->_configs,['page', 'size']);

			$name = empty( $this->_configs['name'] )? null:$this->_configs['name'];
			$year = empty( $this->_configs['year'] )? null:$this->_configs['year'];
			$month = empty( $this->_configs['month'] )? null:$this->_configs['month'];

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$pageObj = $model->getBackUserDiaryListsObj($uid, $skip, $size, $name, $year, $month);

			$data = $pageObj->data;
			$data = empty($data)? []:$data;

			$totalCount = $pageObj->totalCount;

			$return = $this->functionObj->toLayuiJson($data, '001', '获取我的月报列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* 我的审阅月报--已审阅、未审阅列表
	* @SWG\Get(path="/api/v1/user/diary/monthly/read/backReadLists", tags={"MonthlyDiary"}, 
	*  summary="获取我的已阅/未阅月报 pc使用",
	*  description="用户鉴定后 通过传入的page,size获取分页数据",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="statusId", type="integer", required=false, in="query", description="是否已经阅读过 0否1是", default=0),
	*  @SWG\Parameter(name="name", type="string", required=false, in="query", description="用户名"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "BackUserReadMonthlyDiary", "code": "001", "message": "获取我的阅读月报列表成功", "totalCount": 3 } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/BackUserReadMonthlyDiary")
	*   )
	*  )
	* )
	* @author 	xww
	* @return 	json
	*/
	public function backReadLists()
	{

		try {

			//验证 
			$user = $this->getUserApi($this->_configs, 1);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\DiaryReadModel;

			//验证
			$this->configValid('required',$this->_configs,['page', 'size']);

			$name = empty($this->_configs['name'])? null:$this->_configs['name'];

			// 0未读 1已读
			$statusId = empty($this->_configs['statusId'])? 0:1;

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$pageObj = $model->getUserReadMonthlyDiaryListsObj($uid, $statusId, $skip, $size, $name);

			$data = $pageObj->data;
			$data = empty($data)? null:$data;
			$totalCount = $pageObj->totalCount;

			$return = $this->functionObj->toLayuiJson($data, '001', '获取我的月报列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}

}