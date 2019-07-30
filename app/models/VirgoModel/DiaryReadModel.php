<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class DiaryReadModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\DiaryRead; 
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
		$pageObj->setUrl('/admin/diaryReads');
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

	/*添加记录*/
	public function create($data)
	{
		return $this->_model->insertGetId($data);
	}

	/*添加多记录*/
	public function multipleCreate($data)
	{
		return $this->_model->insert($data);
	}

	/**
	* 是否阅读了该月报
	* @author 	xww
	* @param 	int/string 		$id
	* @return 	bool
	*/
	public function readTheDiary($id, $typeId=1)
	{
		
		$count = $this->_model->where("is_deleted", 0)
							->where("type_id", $typeId)
							->where("item_id", $id)
							->where("status_id", 1)
							->count();

		return $count? true:false;

	}	

	/**
	* 等待让我查看/我已查看的月报
	* @author 	xww
	* @param 	int/string 		$uid
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	array
	*/
	public function getUserReadMonthlyDiaryLists($uid, $statusId=null, $skip=null, $size=null)
	{

		$query = $this->_model->leftJoin("monthly_diary", 'monthly_diary.id', '=', 'diary_read.item_id')
							 ->leftJoin("users", "users.id", '=', 'diary_read.user_id')
							 ->where("users.is_deleted", 0)
							 ->where("diary_read.is_deleted", 0)
							 ->where("monthly_diary.is_deleted", 0)
							 ->where("diary_read.to_user_id", $uid)
							 ->where("diary_read.type_id", 1)
							 ->select("monthly_diary.id", "monthly_diary.year", "monthly_diary.month", "users.name", "diary_read.create_time as createTime", "monthly_diary.user_id", "diary_read.status_id")
							 ->groupBy("monthly_diary.id", "monthly_diary.year", "monthly_diary.month", "users.name", "createTime", "user_id", "status_id")
							 ->orderBy("status_id", "asc")
							 ->orderBy("createTime", "desc");


		if( !is_null($statusId) ) {
			$query = $query->where("diary_read.status_id", $statusId);
		}

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}

		$data = $query->get()->toArray();

		$departmentRelModel = new \VirgoModel\DepartmentRelUserModel;
		for ($i=0; $i < count($data); $i++) { 
			$uid = $data[$i]['user_id'];
			$departmentNameArr = $departmentRelModel->getUserDepartments( $uid );

			$data[$i]['departmentName'] = '';
			if( !empty($departmentNameArr) ) {
				$names = [];
				for ($j=0; $j < count($departmentNameArr); $j++) { 
					$names[] = $departmentNameArr[$j]['name'];
				}

				$data[$i]['departmentName'] = implode(",", $names);

			}

			unset($data[$i]['status_id']);
			unset($data[$i]['user_id']);

		}

		return $data;


	}

	/**
	* 判断是否有该用户该日志的未查看情况
	* @author 	xww
	* @param 	int/string 		$typeId
	* @param 	int/string 		$itemId
	* @param 	int/string 		$uid
	* @return 	array
	*/
	public function getUserMonthlyDiaryWaitReadWithItemId($typeId, $itemId, $uid)
	{
		return $this->_model->where("is_deleted", 0)
						    ->where("type_id", $typeId)
						    ->where("item_id", $itemId)
						    ->where("to_user_id", $uid)
						    ->where("status_id", 0)
						    ->get()
						    ->toArray();
	}

	/**
	* 更新已读
	* @author 	xww
	* @param 	int/string 		$typeId
	* @param 	int/string 		$itemId
	* @param 	int/string 		$uid
	* @return 	int
	*/
	public function setReadForUserRead($typeId, $itemId, $uid)
	{

		$data['update_time'] = time();
		$data['status_id'] = 1;

		return $this->_model->where("is_deleted", 0)
						    ->where("type_id", $typeId)
						    ->where("item_id", $itemId)
						    ->where("to_user_id", $uid)
						    ->where("status_id", 0)
						    ->update( $data );
	}

	/**
	* 等待让我查看/我已查看的月报
	* @author 	xww
	* @param 	int/string 		$uid
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	object 
	*/
	public function getUserReadMonthlyDiaryListsObj($uid, $statusId, $skip=null, $size=null, $name=null)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query = $this->_model->leftJoin("monthly_diary", 'monthly_diary.id', '=', 'diary_read.item_id')
							 ->leftJoin("users", "users.id", '=', 'diary_read.user_id')
							 ->where("users.is_deleted", 0)
							 ->where("diary_read.is_deleted", 0)
							 ->where("monthly_diary.is_deleted", 0)
							 ->where("diary_read.to_user_id", $uid)
							 ->where("diary_read.type_id", 1)
							 ->where("diary_read.status_id", $statusId)
							 ->select("monthly_diary.id", "monthly_diary.year", "monthly_diary.month", "users.name", DB::raw(" FROM_UNIXTIME (`comp_diary_read`.create_time, '%Y-%m-%d') as createTime") )
							 ->groupBy("monthly_diary.id", "monthly_diary.year", "monthly_diary.month", "users.name", "createTime")
							 ->orderBy("createTime", "desc");

		if( !is_null($name) ) {
			$query = $query->where("users.name", "like", "%" . $name . "%");
		}

		// 父菜单总记录数
		$totalCount = count( $query->get()->toArray() );

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}

		$data = $query->get()->toArray();

		for ($i=0; $i < count($data); $i++) { 
			$data[$i]['diaryStr'] = $data[$i]['year'] . "-" . $data[$i]['month'] . "的月报";
			$data[$i]['statusStr'] = $statusId==0? '未读':"已读";
			// $data[$i]['status'] = $statusId;
			unset( $data[$i]['year'] );
			unset( $data[$i]['month'] );
		}

		$url = "";

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