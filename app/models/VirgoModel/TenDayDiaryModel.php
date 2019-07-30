<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
class TenDayDiaryModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\TenDayDiary; 
	}

	/**
	* 列表
	* @author xww
	*@return object
	*/
	public function lists()
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;
		// set query 
		$query = $this->_model->where("is_deleted", '=', 0)->orderBy("create_time", "desc");

		// 标题过滤
		if(!empty($_GET['title'])){
			$_GET['title'] = trim($_GET['title']);
			$query = $query->where("title", 'like', '%'.$_GET['title'].'%');
			$pageObj->setPageQuery(['title'=>$_GET['title']]);
		}
		// 开始时间过滤
		if(!empty($_GET['startTime'])){
			$_GET['startTime'] = trim($_GET['startTime']);
			$query = $query->where("update_time", '>=', strtotime($_GET['startTime']." 00:00:00"));
			$pageObj->setPageQuery(['startTime'=>$_GET['startTime']]); 
		}
		// 截止时间过滤
		if(!empty($_GET['endTime'])){
			$_GET['endTime'] = trim($_GET['endTime']);
			$query = $query->where("update_time", '<=', strtotime($_GET['endTime']." 23:59:59"));
			$pageObj->setPageQuery(['endTime'=>$_GET['endTime']]);
		}
		// 父菜单总记录数
		$totalCount = count($query->get()->toArray());
		//分页的take,size
		$size = 10;
		if(!empty($_GET['page'])){
			$page = (int)$_GET['page'];
			$skip = ($page-1)*$size;
		} else {
			$skip = 0;
		}
		// 获取记录
		$data = $query->skip($skip)->take($size)->get()->toArray();
		//设置页数跳转地址
		$pageObj->setUrl('/admin/tenDayDiarys');
		// 设置分页数据
		$pageObj->setData($data);
		// 设置记录总数
		$pageObj->setTotalCount($totalCount);
		// 设置分页大小
		$pageObj->setSize($size);
		// 进行分页并返回
		return $pageObj->doPage();
	}
	/**
	* 逻辑增加
	* @author xww
	* @return sql result
	*/
	public function doCreate()
	{
		unset($_POST['id']);
		unset($_POST['coverPath']);
		unset($_POST['page']);
		// 上传文件
		if(!empty($_FILES['cover']) && $_FILES['cover']['error']==0){
			$ext = str_replace('image/', '', $_FILES['cover']['type']);
			$fpath = '/upload/product/'.microtime(true).".".$ext;
			$rs = move_uploaded_file($_FILES['cover']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$fpath);
			if($rs){
				$_POST['cover'] = $fpath;
			}
		}
		// 创建时间
		$_POST['create_time'] = time();
		// 修改时间
		$_POST['update_time'] = time();
		return $this->_model->insert($_POST);
	}
	/**
	* 返回对应id数据
	* @param  $id  string/int    会话id
	* @author xww
	* @return object
	*/
	public function read($id)
	{
		return $this->_model->where("is_deleted", '=', 0)->find($id);
	}
	/**
	* 逻辑修改
	* @author xww
	* @return sql result
	*/
	public function doUpdate()
	{
		$id = $_POST['id'];
		unset($_POST['id']);
		unset($_POST['coverPath']);
		unset($_POST['page']);
		// 上传文件
		if(!empty($_FILES['cover']) && $_FILES['cover']['error']==0){
			$ext = str_replace('image/', '', $_FILES['cover']['type']);
			$fpath = '/upload/product/'.microtime(true).".".$ext;
			$rs = move_uploaded_file($_FILES['cover']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$fpath);
			if($rs){
				$_POST['cover'] = $fpath;
			}
		}
		// 修改时间
		$_POST['update_time'] = time();
		// 更新
		return $this->_model->where("id", '=', $id)->update($_POST);
	}
	/**
	* 逻辑删除
	* @author xww
	* @return sql result
	*/
	public function delete()
	{
		$data['is_deleted'] = 1;
		if($_POST){$ids = $_POST['ids'];}
		else{$ids = [$_GET['id']];}
		return $this->_model->whereIn("id", $ids)->update($data);
	}

	/**
	* 获取指定用户的指定年份指定期数的日志
	* @author 	xww
	* @param 	int/string 		$uid
	* @param 	int/string 		$year
	* @param 	int/string 		$issue
	* @return 	array
	*/
	public function getUserDiaryWithYearAndIssue($uid, $year, $issue)
	{
		return $this->_model->where("is_deleted", 0)
							->where("user_id", $uid)
							->where("year", $year)
							->where("issue", $issue)
							->take(1)
							->get()
							->toArray();
	}

	/*添加记录*/
	public function create($data)
	{
		return $this->_model->insertGetId($data);
	}

	/**
	* 详情
	* @author 	xww
	* @param 	int/string 		$id
	* @return 	array
	*/
	public function readDetail( $id )
	{

		$data = $this->_model->leftJoin("departments", "departments.id", "=", "ten_day_diary.department_id")
				   	 ->leftJoin("acre", "acre.id", "=", "ten_day_diary.acre_id")
				   	 ->leftJoin("users", "users.id", "=", "ten_day_diary.user_id")
				   	 ->select("ten_day_diary.*", "departments.name as departmentName", "acre.name as acreName", "users.name as userName")
				   	 ->where("ten_day_diary.id", $id)
				   	 ->take(1)
				   	 ->get()
				   	 ->toArray();

		return empty($data)? null:$data[0];

	}
	
	/**
	* 获取用户指定年份的最大期号
	* @author 	xww
	* @param 	int/string 		$uid
	* @param 	int/string 		$year
	* @return 	int
	*/
	public function getUserMaxIssueWithYear($uid, $year)
	{
		return $this->_model->where("is_deleted", 0)->where("user_id", $uid)->where("year", $year)->max("issue");
	}

	/**
	* 数据更新
	* @author 	xww
	* @param 	int/string 		$id
	* @param 	array 			$data
	* @return 	affect rows
	*/
	public function partUpdate($id, $data)
	{
		return $this->_model->where("id", $id)->update($data);
	}
	
	/**
	* 获取用户的日志列表
	* @author 	xww
	* @param 	int/string 		$uid
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	array
	*/
	public function getUserDiaryLists($uid, $skip=null, $size=null)
	{
		
		// 获取已经评论的十日报日志id
		$waitReviewedArr = \EloquentModel\DiaryExamination::where("is_deleted", 0)
										->where("user_id", $uid)
										->where("type_id", 1)
										->where("status_id", 2)
										->select("item_id")
										->groupBy("item_id")
										->get()
										->toArray();

		$waitIds = [];
		for ($i=0; $i < count($waitReviewedArr); $i++) { 
			$waitIds[] = $waitReviewedArr[$i]['item_id'];
		}

		$query =  $this->_model->leftJoin('acre', "acre.id", '=', 'ten_day_diary.acre_id')
							  ->leftJoin('departments', "departments.id", '=', 'ten_day_diary.department_id')
							  ->where("ten_day_diary.is_deleted", 0)
							  ->where("ten_day_diary.user_id", $uid)
							  ->select("ten_day_diary.id", "ten_day_diary.issue", "ten_day_diary.create_time as createTime")
							  ->orderBy("ten_day_diary.create_time", "desc");

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}
		
		$data = $query->get()->toArray();

		for ($i=0; $i < count($data); $i++) { 
			$data[$i]['status'] = 0;
			if( in_array( $data[$i]['id'], $waitIds) ){
				$data[$i]['status'] = 1;
			}
		}

		return $data;

	}

	/**
	* 获取用户的日志列表--之后台使用
	* @author 	xww
	* @param 	int/string 		$uid
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	array
	*/
	public function getBackUserDiaryListsObj($uid, $skip=null, $size=null, $acreName=null, $departmentName=null, $userName=null)
	{
		
		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		/*评论对象*/
		$commentModel = new \VirgoModel\TenDayDiaryCommentModel;

		// 获取已经评论过的十日报日志id
		$waitReviewedArr = \EloquentModel\DiaryExamination::where("is_deleted", 0)
										->where("user_id", $uid)
										->where("type_id", 1)
										->where("status_id", 2)
										->select("item_id")
										->groupBy("item_id")
										->get()
										->toArray();

		$waitIds = [];
		for ($i=0; $i < count($waitReviewedArr); $i++) { 
			$waitIds[] = $waitReviewedArr[$i]['item_id'];
		}

		$query =  $this->_model->leftJoin('acre', "acre.id", '=', 'ten_day_diary.acre_id')
							  ->leftJoin('departments', "departments.id", '=', 'ten_day_diary.department_id')
							  ->leftJoin('users', "users.id", '=', 'ten_day_diary.user_id')
							  ->where("ten_day_diary.is_deleted", 0)
							  ->where("ten_day_diary.user_id", $uid)
							  ->select("ten_day_diary.id", "ten_day_diary.issue", "ten_day_diary.create_time as createTime", "acre.name as acreName", "departments.name as departmentName", "users.name", "ten_day_diary.start_time", "ten_day_diary.end_time")
							  ->orderBy("ten_day_diary.year", "desc")
							  ->orderBy("ten_day_diary.issue", "desc")
							  ->orderBy("ten_day_diary.create_time", "desc");

		if( !is_null($acreName) ) {
			$query = $query->where("acre.name", "like", "%" . $acreName . "%");
		}

		if( !is_null($departmentName) ) {
			$query = $query->where("departments.name", "like", "%" . $departmentName . "%");
		}

		if( !is_null($userName) ) {
			$query = $query->where("users.name", "like", "%" . $userName . "%");
		}

		// 父菜单总记录数
		$totalCount = count( $query->get()->toArray() );

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}
		
		$data = $query->get()->toArray();

		for ($i=0; $i < count($data); $i++) { 

			$id = $data[$i]['id'];

			/*获取场长评论*/
			$record_1 = $commentModel->getDiaryCommentContentWithTypeId($id, 1);

			/*获取公司高管评论*/
			$record_2 = $commentModel->getDiaryCommentContentWithTypeId($id, 2);

			$data[$i]['farmLeader'] = empty($record_1)? false:true;
			$data[$i]['companyExecutives'] = empty($record_2)? false:true;

			$data[$i]['farmLeaderStr'] = empty($record_1)? '未添加':'已添加';
			$data[$i]['companyExecutivesStr'] = empty($record_2)? '未添加':'已添加';

			$data[$i]['createTime'] = date("Y-m-d", $data[$i]['createTime']);

			/*默认没有评论过*/
			$data[$i]['status'] = 0;
			$data[$i]['diaryName'] = "第".$data[$i]['acreName']."地块的日志";
			$data[$i]['timeRange'] = date("Y-m-d", $data[$i]['start_time']) . "至" . date("Y-m-d", $data[$i]['end_time']);
			
			if( in_array( $data[$i]['id'], $waitIds) ){
				$data[$i]['status'] = 1;
			}

			unset($data[$i]['acreName']);
			unset($data[$i]['start_time']);
			unset($data[$i]['end_time']);
		}

		//设置页数跳转地址
		$pageObj->setUrl( $url );

		// 设置分页数据
		$pageObj->setData($data);

		// 设置记录总数
		$pageObj->setTotalCount($totalCount);

		// 设置分页大小
		$pageObj->setSize($size);

		// 进行分页并返回
		return $pageObj->doPage();

	}

}
?>