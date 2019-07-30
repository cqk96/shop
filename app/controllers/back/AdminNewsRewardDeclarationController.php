<?php
 /**
 * 控制器
 * @author xww <5648*****@qq.com>
 * @version 1.0.0
 */
 namespace VirgoBack;
 class AdminNewsRewardDeclarationController extends AdminBaseController{
	 /*
	 * @param  object  reflect this controller's  virgo model object
	 */
	 private $model;

	 public function __construct()
	 {
		$this->model = new \VirgoModel\NewsRewardDeclarationModel;
		parent::isLogin();
	 }

	 // 获取列表
	 public function lists()
	 {
		 $page_title = '管理';
		 $pageObj = $this->model->lists();
		 // 赋值数据
		$data = $pageObj->data;
		 require_once dirname(__FILE__).'/../../views/admin/adminNewsRewardDeclaration/index.php';
	 }

	 // 增加专区分类界面
	 public function create()
	 {
		
		try{

			$page_title = '增加管理';

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 部门
			$departmentModelObj = new \VirgoModel\DepartmentModel;

			$userId = $_COOKIE['user_id'];

			$user = $userModel->readSingleTon($userId);

			if( empty($user) ) {
				throw new \Exception("用户不存在");
			}

			// test
			// $userId = 286;

			// 获取用户所在部门
			$departments = $departmentModelObj->getUserDepartment($userId);

			if( count($departments)!=1 ) {
				throw new \Exception("数据异常:当前用户存在多个部门");
			}


			// 增加页面
			require_once dirname(__FILE__).'/../../views/admin/adminNewsRewardDeclaration/_create.php';

		} catch(\Exception $e) {
			echo $e->getMessage();
		}
	 }

	 // 处理增加
	 public function doCreate()
	 {
		 $page = $_POST['page'];
		 $rs = $this->model->doCreate();
		 if($rs){$this->showPage(['添加专区分类成功'],'/admin/newsRewardDeclarations?page='.$page); }
		 else {$this->showPage(['添加专区分类失败'],'/admin/newsRewardDeclarations?page='.$page); }
	 }

	/**
	* 详情
	* @author　	xww
	* @return 	void
	*/
	public function read()
	{
		
		try{

			if( empty($_GET['id']) ) {
				throw new \Exception("Wrong Param");
			}

			$id = $_GET['id'];

			// 获取对应数据详情
			$data = $this->model->readData($id);

			if( empty($data) ) {
				throw new \Exception("无法查询到数据");
			}

			// 详情页面
			require_once dirname(__FILE__).'/../../views/admin/adminNewsRewardDeclaration/_read.php';

		} catch(\Exception $e) {
			echo $e->getMessage();
		}

	}

	 //修改专区分类页面
	 public function update()
	 {
		 $page_title = '修改管理';
		$id = $_GET['id'];
		$data = $this->model->read($id);
		// 专区分类修改页面
		 require_once dirname(__FILE__).'/../../views/admin/adminNewsRewardDeclaration/_update.php';
	 }

	 // 处理修改
	 public function doUpdate()
	 {
		 $page = $_POST['page'];
		 $rs = $this->model->doUpdate();
		 if($rs){$this->showPage(['修改成功'],'/admin/newsRewardDeclarations?page='.$page); }
		 else {$this->showPage(['修改失败'],'/admin/newsRewardDeclarations?page='.$page); }
	 }

	 // 处理删除
	 public function doDelete()
	 {
		 $rs =  $this->model->delete();
		 if($_POST){
			 if($rs){echo json_encode(['success'=>true,'message'=>'delete success']);}
			 else{echo json_encode(['success'=>false,'message'=>'delete failture']);}
		 } else {
			 if($rs){$this->showPage(['删除成功'],'/admin/newsRewardDeclarations');}
			 else {$this->showPage(['删除失败'],'/admin/newsRewardDeclarations');}
		 }
	 }
 }
 ?>