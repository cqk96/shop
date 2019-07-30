<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
class DiaryExaminationModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\DiaryExamination; 
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
		$pageObj->setUrl('/admin/diaryExaminations');
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
	* 创建日志审批工作
	* @author 	xww
	* @param 	int/string 		$uid 				发起日志用户id
	* @param 	int/string 		$itemTypeId			日志类型
	* @param 	int/string 		$itemId 			对应表的id
	* @param 	array 			$approvers 			审批人id数组
	* @param 	int/string 		$approverTypeId  	审批人角色typeId
	* @return 	int
	*/
	public function createWorks($uid, $itemTypeId, $itemId, $approvers, $approverTypeId)
	{

		$data = [];
		for ($i=0; $i < count($approvers); $i++) { 
			$approver = $approvers[$i];
			$temp['user_id'] = $uid;
			$temp['type_id'] = $itemTypeId;
			$temp['item_id'] = $itemId;
			$temp['approver_id'] = $approver;
			$temp['approver_type_id'] = $approverTypeId;
			$temp['status_id'] = 0;
			$temp['create_time'] = time();
			$temp['update_time'] = time();

			$data[] = $temp;
			unset($temp);
		}

		return $this->_model->insert( $data );
		
	}

	/**
	* 是否是由你来进行日志审批
	* @author 	xww
	* @param 	int/string 		$id    	diary id
	* @param 	int/string 		$uid    approver id
	* @return 	bool
	*/
	public function uAreApprover($typeId, $id, $uid)
	{
		
		$count = $this->_model->where("is_deleted", 0)
					 ->where('type_id', $typeId)
					 ->where("item_id", $id)
					 ->where("approver_id", $uid)
					 ->where("status_id", 0)
					 ->count();

		return $count? true:false;
		
	}

	/**
	* 获取用户的日志审批任务
	* @author 	xww
	* @param 	int/string 		$uid
	* @param 	int/string 		$status    默认null不处理 0代表未完成  2已经完成
	* @param 	int/string 		$skip      跳过
	* @param 	int/string 		$size      获取条数
	* @return 	array
	*/
	public function getUserMissions($uid, $status=null, $skip=null, $size=null)
	{
		
		$query = $this->_model->leftJoin("users", "users.id", '=', 'diary_examination.user_id')
							  ->leftJoin("ten_day_diary", "ten_day_diary.id", "=", "diary_examination.item_id")
							  ->leftJoin("departments", "departments.id", "=", "ten_day_diary.department_id")
							  ->where("diary_examination.type_id", 1)
							  ->where("users.is_deleted", 0)
							  ->where("diary_examination.is_deleted", 0)
							  ->where("approver_id", $uid)
							  ->orderBy("status_id", "asc")
							  ->select("users.name", "diary_examination.item_id as diaryId", "diary_examination.type_id as typeId", "diary_examination.approver_type_id as approverTypeId", "ten_day_diary.issue", "departments.name as departmentName");

		if( !is_null($status) && $status!="" ) {
			$query = $query->where("status_id", $status);
		} else {
			$query = $query->addSelect("diary_examination.create_time as unixTimeStamp");
		}

		if( !is_null($status) && $status==0 ) {
			$query = $query->orderBy("diary_examination.create_time", "desc")
						    ->addSelect("diary_examination.create_time as unixTimeStamp");
		}

		if( !is_null($status) && $status==2 ) {
			$query = $query->orderBy("diary_examination.update_time", "desc")
						   ->addSelect("diary_examination.update_time as unixTimeStamp");
		}

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}

		$data = $query->get()->toArray();

		if( !empty($data) ) {
			/*获取日志ids数组*/
			$ids = [];
			for ($i=0; $i < count($data); $i++) { 
				$ids[] = $data[$i]['diaryId'];
			}

			$comment_type_1_arr = \EloquentModel\TenDayDiaryComment::where("is_deleted", 0)
											 ->where("comment_type_id", 1)
											 ->where("approver", $uid)
											 ->whereIn("diary_id", $ids)
											 ->get()
											 ->toArray();

			$comment_type_1 = [];
			for ($i=0; $i < count($comment_type_1_arr); $i++) { 
				$comment_type_1[ $comment_type_1_arr[$i]['diary_id'] ] = $comment_type_1_arr[$i];
			}

			$comment_type_2_arr = \EloquentModel\TenDayDiaryComment::where("is_deleted", 0)
											 ->where("comment_type_id", 2)
											 ->where("approver", $uid)
											 ->whereIn("diary_id", $ids)
											 ->get()
											 ->toArray();

			$comment_type_2 = [];
			for ($i=0; $i < count($comment_type_2_arr); $i++) { 
				$comment_type_2[ $comment_type_2_arr[$i]['diary_id'] ] = $comment_type_2_arr[$i];
			}

			for ($i=0; $i < count($data); $i++) { 

				$data[$i]['commentId'] = 0;
				if( $data[$i]['typeId']==1 && $data[$i]['approverTypeId']==1108 ) { 
					if( !empty( $comment_type_1[ $data[$i]['diaryId'] ] ) ) {
						$data[$i]['commentId'] = $comment_type_1[ $data[$i]['diaryId'] ]['id'];
					}
				}

				if( $data[$i]['typeId']==1 && $data[$i]['approverTypeId']==1109 ) { 
					if( !empty( $comment_type_2[ $data[$i]['diaryId'] ] ) ) {
						$data[$i]['commentId'] = $comment_type_2[ $data[$i]['diaryId'] ]['id'];
					}
				}

			}

		}

		return $data;

	}

	/**
	* 获取用户的日志审批任务
	* @author 	xww
	* @param 	int/string 		$uid
	* @param 	int/string 		$status    0代表未完成  2已经完成
	* @param 	int/string 		$skip      跳过
	* @param 	int/string 		$size      获取条数
	* @return 	array
	*/
	public function getBackUserMissionsObj($uid, $status, $skip=null, $size=null, $userName=null, $departmentName=null)
	{
		
		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query = $this->_model->leftJoin("users", "users.id", '=', 'diary_examination.user_id')
							  ->leftJoin("ten_day_diary", "ten_day_diary.id", '=', 'diary_examination.item_id')
							  ->leftJoin("departments", "departments.id", '=', 'ten_day_diary.department_id')
							  ->where("diary_examination.type_id", 1)
							  ->where("users.is_deleted", 0)
							  ->where("diary_examination.is_deleted", 0)
							  ->where("approver_id", $uid)
							  ->where("status_id", $status)
							  ->select("users.name", "diary_examination.item_id as diaryId", "diary_examination.type_id as typeId", "diary_examination.approver_type_id as approverTypeId", "ten_day_diary.current_work_content", "departments.name as departmentName");

		if( $status==0 ) {
			$query = $query->orderBy("diary_examination.create_time", "desc")
						   ->addSelect("diary_examination.create_time as unixTimeStamp");
		}

		if( $status==2 ) {
			$query = $query->orderBy("diary_examination.update_time", "desc")
						   ->addSelect("diary_examination.update_time as unixTimeStamp");
		}

		if( !is_null($userName) ) {
			$query = $query->where("users.name", "like", "%" . $userName . "%");
		}

		if( !is_null($departmentName) ) {
			$query = $query->where("departments.name", "like", "%" . $departmentName . "%");
		}

		// 父菜单总记录数
		$totalCount = count( $query->get()->toArray() );

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}

		$data = $query->get()->toArray();

		if( !empty($data) ) {
			/*获取日志ids数组*/
			$ids = [];
			for ($i=0; $i < count($data); $i++) { 
				$ids[] = $data[$i]['diaryId'];

				/*如果内容超出进行缩略*/
				if( mb_strlen($data[$i]['current_work_content'], "utf8")>10 ) {
					$data[$i]['current_work_content'] = mb_substr($data[$i]['current_work_content'], 0, 10, "utf8") . '...';
				}
			}

			$comment_type_1_arr = \EloquentModel\TenDayDiaryComment::where("is_deleted", 0)
											 ->where("comment_type_id", 1)
											 ->whereIn("diary_id", $ids)
											 ->get()
											 ->toArray();

			$comment_type_1 = [];
			for ($i=0; $i < count($comment_type_1_arr); $i++) { 
				$comment_type_1[ $comment_type_1_arr[$i]['diary_id'] ] = $comment_type_1_arr[$i];
			}

			$comment_type_2_arr = \EloquentModel\TenDayDiaryComment::where("is_deleted", 0)
											 ->where("comment_type_id", 2)
											 ->whereIn("diary_id", $ids)
											 ->get()
											 ->toArray();

			$comment_type_2 = [];
			for ($i=0; $i < count($comment_type_2_arr); $i++) { 
				$comment_type_2[ $comment_type_2_arr[$i]['diary_id'] ] = $comment_type_2_arr[$i];
			}

			for ($i=0; $i < count($data); $i++) { 

				$data[$i]['commentId'] = 0;
				$data[$i]['commentStr'] = '未审批';
				if( $status==2 && $data[$i]['typeId']==1 && $data[$i]['approverTypeId']==1108 ) { 
					if( !empty( $comment_type_1[ $data[$i]['diaryId'] ] ) ) {
						$data[$i]['commentId'] = $comment_type_1[ $data[$i]['diaryId'] ]['id'];
						$data[$i]['commentStr'] = '已审批';
					}
				}

				if( $status==2 && $data[$i]['typeId']==1 && $data[$i]['approverTypeId']==1109 ) { 
					if( !empty( $comment_type_2[ $data[$i]['diaryId'] ] ) ) {
						$data[$i]['commentId'] = $comment_type_2[ $data[$i]['diaryId'] ]['id'];
						$data[$i]['commentStr'] = '已审批';
					}
				}

			}

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