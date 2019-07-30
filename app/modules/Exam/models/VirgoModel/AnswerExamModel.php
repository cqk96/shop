<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace Module\Exam\VirgoModel;
use VirgoModel;
class AnswerExamModel extends VirgoModel\BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \Module\Exam\EloquentModel\AnswerExam; 
	}

	/**
	* 列表
	* @author xww
	*@return object
	*/
	public function lists($userIds=null, $url=null)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		// set query 
		$query = $this->_model->leftJoin("exam", "exam.id", '=', 'answer_exam.exam_id')
							  ->leftJoin("users", "users.id", '=', 'answer_exam.user_id')
					  ->where("exam.is_deleted", '=', 0)
					  ->where("users.is_deleted", '=', 0)
					  ->where("answer_exam.is_deleted", '=', 0)
					  ->orderBy("answer_exam.create_time", "desc")
					  ->orderBy("answer_exam.id", "desc")
					  ->select("exam.title", "answer_exam.*", "users.name");

		if( !is_null( $userIds ) && is_array($userIds) ) {
			$query = $query->whereIn("answer_exam.user_id", $userIds);
		}

		// 标题过滤
		if(!empty($_GET['title'])){
			$_GET['title'] = trim($_GET['title']);
			$query = $query->where("exam.title", 'like', '%'.$_GET['title'].'%');
			$pageObj->setPageQuery(['title'=>$_GET['title']]);
		}
		// // 开始时间过滤
		// if(!empty($_GET['startTime'])){
		// 	$_GET['startTime'] = trim($_GET['startTime']);
		// 	$query = $query->where("update_time", '>=', strtotime($_GET['startTime']." 00:00:00"));
		// 	$pageObj->setPageQuery(['startTime'=>$_GET['startTime']]); 
		// }
		// // 截止时间过滤
		// if(!empty($_GET['endTime'])){
		// 	$_GET['endTime'] = trim($_GET['endTime']);
		// 	$query = $query->where("update_time", '<=', strtotime($_GET['endTime']." 23:59:59"));
		// 	$pageObj->setPageQuery(['endTime'=>$_GET['endTime']]);
		// }
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

		if( is_null($url) ) {
			$url = '/admin/answerExams';
		}

		//设置页数跳转地址
		$pageObj->setUrl($url);
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
	* 创建记录
	* @author 	xww
	* @param 	array    $data
	* @return 	int
	*/
	public function create($data)
	{
		return $this->_model->insertGetId($data);
	}
	
	/**
	* 数据更新
	* @author 	xww
	* @param 	int/string 		$uid
	* @param 	array 			$data
	* @return 	affect rows
	*/
	public function partUpdate($uid, $data)
	{
		return $this->_model->where("id", $uid)->update($data);
	}
	
	/**
	* 获取用户的考试结果
	* @param 	int/string    	$uid
	* @param 	int/string    	$skip
	* @param 	int/string    	$size
	* @param 	int/string    	$classId
	* @return 	array
	*/
	public function getUserExamResultLists($uid, $skip=null, $size=null, $classId=1)
	{
		
		$query = $this->_model->leftJoin("exam", "exam.id", '=', "answer_exam.exam_id")
					 ->where("exam.is_deleted", 0)
					 ->where("answer_exam.is_deleted", 0)
					 ->where("exam.type_id", $classId)
					 ->where("answer_exam.user_id", $uid)
					 ->select("answer_exam.id", "exam.title", 'answer_exam.get_score as getScore', "answer_exam.create_time as createTime")
					 ->orderBy("answer_exam.create_time", "desc");

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}

		return $query->get()->toArray();

	}

	/**
	* 获取用户的考试结果的数量
	* @param 	int/string    	$classId
	* @return 	int
	*/
	public function getUserExamResultListsCount($uid, $classId=1)
	{
		
		return $this->_model->leftJoin("exam", "exam.id", '=', "answer_exam.exam_id")
					 ->where("exam.is_deleted", 0)
					 ->where("answer_exam.is_deleted", 0)
					 ->where("exam.type_id", $classId)
					 ->where("answer_exam.user_id", $uid)
					 ->count();

	}

	/**
	* 是否已经有人进行这个考试测评
	* @author 	xww
	* @param 	int/string  	$examId
	* @return 	bool
	*/
	public function hasTestingTheExam($examId)
	{
		$count = $this->_model->where("is_deleted", 0)
							 ->where("exam_id", $examId)
							 ->count();

		return $count? true:false;
	}

	/**
	* 试题列表
	* @author 	xww
	* @param 	string 			$url
	* @return 	object
	*/
	public function getExamLists($url=null)
	{
		
		$model = new \Module\Exam\EloquentModel\Exam;

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;
		
		// set query 
		$query = $model->where("is_deleted", '=', 0)->where("type_id", 1)->orderBy("create_time", "desc");

		// 标题过滤
		if(!empty($_GET['title'])){
			$_GET['title'] = trim($_GET['title']);
			$query = $query->where("title", 'like', '%'.$_GET['title'].'%');
			$pageObj->setPageQuery(['title'=>$_GET['title']]);
		}
		
		// // 开始时间过滤
		// if(!empty($_GET['startTime'])){
		// 	$_GET['startTime'] = trim($_GET['startTime']);
		// 	$query = $query->where("update_time", '>=', strtotime($_GET['startTime']." 00:00:00"));
		// 	$pageObj->setPageQuery(['startTime'=>$_GET['startTime']]); 
		// }

		// // 截止时间过滤
		// if(!empty($_GET['endTime'])){
		// 	$_GET['endTime'] = trim($_GET['endTime']);
		// 	$query = $query->where("update_time", '<=', strtotime($_GET['endTime']." 23:59:59"));
		// 	$pageObj->setPageQuery(['endTime'=>$_GET['endTime']]);
		// }

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

		if( is_null($url) ) {
			$url = '/admin/answerExams';
		}

		// 获取记录
		$data = $query->skip($skip)->take($size)->get()->toArray();

		for ($i=0; $i < count($data); $i++) { 
			
			$count = $this->_model->where("is_deleted", 0)
							 ->where("exam_id", $data[$i]['id'])
							 ->count();

			$data[$i]['hasAnswered'] = $count? true:false;

		}

		//设置页数跳转地址
		$pageObj->setUrl($url);
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
	* 列表
	* @author xww
	* @param 	int/string  	$examId
	* @param 	array  			$userIds
	* @author xww
	* @return object
	*/
	public function answerExamLists($id, $userIds=null)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		// set query 
		$query = $this->_model->leftJoin("exam", "exam.id", '=', 'answer_exam.exam_id')
							  ->leftJoin("users", "users.id", '=', 'answer_exam.user_id')
					  ->where("exam.is_deleted", '=', 0)
					  ->where("users.is_deleted", '=', 0)
					  ->where("answer_exam.is_deleted", '=', 0)
					  ->where("answer_exam.exam_id", $id)
					  ->orderBy("answer_exam.create_time", "desc")
					  ->orderBy("answer_exam.id", "desc")
					  ->select("exam.title", "answer_exam.*", "users.name");

		if( !is_null( $userIds ) && is_array($userIds) ) {
			$query = $query->whereIn("answer_exam.user_id", $userIds);
		}

		// 用户过滤
		if(!empty($_GET['username'])){
			$_GET['username'] = trim($_GET['username']);
			$query = $query->where("users.name", 'like', '%'.$_GET['username'].'%');
			$pageObj->setPageQuery(['username'=>$_GET['username']]);
		}

		$pageObj->setPageQuery(['id'=>$id]);

		// // 开始时间过滤
		// if(!empty($_GET['startTime'])){
		// 	$_GET['startTime'] = trim($_GET['startTime']);
		// 	$query = $query->where("update_time", '>=', strtotime($_GET['startTime']." 00:00:00"));
		// 	$pageObj->setPageQuery(['startTime'=>$_GET['startTime']]); 
		// }
		// // 截止时间过滤
		// if(!empty($_GET['endTime'])){
		// 	$_GET['endTime'] = trim($_GET['endTime']);
		// 	$query = $query->where("update_time", '<=', strtotime($_GET['endTime']." 23:59:59"));
		// 	$pageObj->setPageQuery(['endTime'=>$_GET['endTime']]);
		// }
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
		$pageObj->setUrl( '/admin/answerExams/info?id=' . $id);
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