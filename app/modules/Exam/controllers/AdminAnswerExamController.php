<?php
 /**
 * 控制器
 * @author xww <5648*****@qq.com>
 * @version 1.0.0
 */
 namespace Module\Exam\Controller;
 use VirgoBack;
 class AdminAnswerExamController extends VirgoBack\AdminBaseController{
	 /*
	 * @param  object  reflect this controller's  virgo model object
	 */
	 private $model;

	 public function __construct()
	 {
		$this->model = new \Module\Exam\VirgoModel\AnswerExamModel;
		parent::isLogin();
	 }

	 // 获取列表
	 public function lists()
	 {

	 	// 显示考试列表 

		$page_title = '管理';
		$pageObj = $this->model->getExamLists();

		// 题目搜索
		$title = null;
		if( !empty($_GET['title']) ) {
			$title = trim( $_GET['title'] );
		}

		$totalCount = $pageObj->totalCount;

		$size = $pageObj->size;

		$totalPage = $totalCount % $size == 0? $totalCount / $size:ceil($totalCount / $size);

		$data = $pageObj->data;

		 // 赋值数据
		$data = $pageObj->data;

		require_once dirname(__FILE__).'/../views/adminAnswerExam/exam-lists.php';

	 }

	 // 增加专区分类界面
	 public function create()
	 {
		 $page_title = '增加管理';
		 // 增加页面
		 require_once dirname(__FILE__).'/../views/adminAnswerExam/_create.php';
	 }

	 // 处理增加
	 public function doCreate()
	 {
		 $page = $_POST['page'];
		 $rs = $this->model->doCreate();
		 if($rs){$this->showPage(['添加成功'],'/admin/answerExams?page='.$page); }
		 else {$this->showPage(['添加失败'],'/admin/answerExams?page='.$page); }
	 }

	 //修改专区分类页面
	 public function update()
	 {
		 $page_title = '修改管理';
		$id = $_GET['id'];
		$data = $this->model->read($id);
		// 专区分类修改页面
		 require_once dirname(__FILE__).'/../../views/adminAnswerExam/_update.php';
	 }

	 // 处理修改
	 public function doUpdate()
	 {
		 $page = $_POST['page'];
		 $rs = $this->model->doUpdate();
		 if($rs){$this->showPage(['修改成功'],'/admin/answerExams?page='.$page); }
		 else {$this->showPage(['修改失败'],'/admin/answerExams?page='.$page); }
	 }

	 // 处理删除
	 public function doDelete()
	 {
		 $rs =  $this->model->delete();
		 if($_POST){
			 if($rs){echo json_encode(['success'=>true,'message'=>'delete success']);}
			 else{echo json_encode(['success'=>false,'message'=>'delete failture']);}
		 } else {
			 if($rs){$this->showPage(['删除成功'],'/admin/answerExams');}
			 else {$this->showPage(['删除失败'],'/admin/answerExams');}
		 }
	 }

	/**
	* 查看考试人数详情
	* @author 	xww
	* @return 	void
	*/
	public function info()
	{
		
		try{

			if( empty($_GET['id']) ) {
				throw new \Exception("Wrong Param");
			}

			$id = $_GET['id'];

			$action = '/admin/answerExams/info?id=' . $id;

			// 用户角色
			$roleToUserModelObj = new \VirgoModel\RoleToUserModel;

		 	$uid = $_COOKIE['user_id'];

		 	if( $uid==1 ) {
		 		$userIds = null;
		 	} else {
		 		$userIds = [ $uid ];
		 	}

			$page_title = '管理';
			$pageObj = $this->model->answerExamLists($id, $userIds);

			// 题目搜索
			$username = null;
			if( !empty($_GET['username']) ) {
				$username = trim( $_GET['username'] );
			}

			$totalCount = $pageObj->totalCount;

			$size = $pageObj->size;

			$totalPage = $totalCount % $size == 0? $totalCount / $size:ceil($totalCount / $size);

			$data = $pageObj->data;

			require_once dirname(__FILE__).'/../views/adminAnswerExam/info.php';

		} catch(\Exception $e) {
			echo $e->getMessage();
		}

	}

 }
 ?>