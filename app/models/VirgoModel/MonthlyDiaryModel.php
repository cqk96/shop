<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
class MonthlyDiaryModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\MonthlyDiary; 
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
		$pageObj->setUrl('/admin/monthlyDiarys');
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
	* 获取用户指定年份月份日志
	* @author 	xww
	* @param 	int/string 		$uid
	* @param 	int/string 		$year
	* @param 	int/string 		$month
	* @return 	array
	*/
	public function getUserDiaryWithDate($uid, $year, $month)
	{
		return $this->_model->where("is_deleted", 0)
					 ->where("user_id", $uid)
					 ->where("year", $year)
					 ->where("month", $month)
					 ->get()
					 ->toArray();
	}

	/*添加记录*/
	public function create($data)
	{
		return $this->_model->insertGetId($data);
	}
	
	/**
	* 获取用户指定id日志
	* @author 	xw
	* @param 	int/string 		$uid
	* @param 	int/string 		$id
	* @return 	array
	*/
	public function getUserDiaryWithId($uid, $id)
	{
		return $this->_model->where("is_deleted", 0)
					 ->where("user_id", $uid)
					 ->where("id", $id)
					 ->take(1)
					 ->get()
					 ->toArray();
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
	* 我的月报
	* @author 	xww
	* @param 	int/string 		$uid
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	array
	*/
	public function getUserDiaryLists($uid, $skip, $size)
	{

		$query = $this->_model->where("is_deleted", 0)
							 ->where("user_id", $uid)
							 ->select("id", "create_time as createTime");

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}

		return $query->get()->toArray();
		
	}

	/**
	* 我的月报--是否已阅读
	* @author 	xww
	* @param 	int/string 		$uid
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	array
	*/
	public function getBackUserDiaryListsObj($uid, $skip=null, $size=null, $name=null, $year=null, $month=null)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$read = new \VirgoModel\DiaryReadModel;

		$query = $this->_model->leftJoin("users", "users.id", '=', "monthly_diary.user_id")
		                     ->where("users.is_deleted", 0)
		                     ->where("monthly_diary.is_deleted", 0)
							 ->where("monthly_diary.user_id", $uid)
							 ->orderBy("monthly_diary.create_time", "desc")
							 ->select("monthly_diary.id", "monthly_diary.create_time as createTime", "users.name", "year", "month");

		if( !is_null($name) ) {
			$query = $query->where("users.name", "like", "%" . $name . "%");
		}

		if( !is_null($year) ) {
			$query = $query->where("year", "=", $year);
		}

		if( !is_null($month) ) {
			$query = $query->where("month", "=", $month);
		}

		$totalCount = count( $query->get()->toArray() );
		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}

		$url = '';

		$data = $query->get()->toArray();

		for ($i=0; $i < count($data); $i++) { 
			$id = $data[$i]['id'];
			$isRead = $read->readTheDiary($id);
			$data[$i]['status'] = $isRead? 1:0;

			$data[$i]['diaryStr'] = $data[$i]['year'] . "-" . $data[$i]['month'] . "的月报";
			$data[$i]['createTimeStr'] = date("Y-m-d", $data[$i]['createTime']);
			unset( $data[$i]['year'] );
			unset( $data[$i]['month'] );
			unset( $data[$i]['createTime'] );
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