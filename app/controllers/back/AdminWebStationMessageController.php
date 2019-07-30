<?php
 /**
 * 控制器
 * @author xww <5648*****@qq.com>
 * @version 1.0.0
 */
 namespace VirgoBack;
 class AdminWebStationMessageController extends AdminBaseController{
	 /*
	 * @param  object  reflect this controller's  virgo model object
	 */
	 private $model;

	 public function __construct()
	 {
		$this->model = new \VirgoModel\WebStationMessageModel;
		parent::isLogin();
	 }

	 // 获取列表
	 public function lists()
	 {
		 $page_title = '管理';
		 $pageObj = $this->model->lists();
		 // 赋值数据
		$data = $pageObj->data;

		$totalCount = $pageObj->totalCount;

		$size = $pageObj->size;

		$totalPage = $totalCount % $size == 0? $totalCount / $size:ceil($totalCount / $size);

		 require_once dirname(__FILE__).'/../../views/admin/adminWebStationMessage/index.php';
	 }

	 // 增加专区分类界面
	 public function create()
	 {
		
		try{

			$page_title = '增加管理';

			// 用户
			$userObj = new \VirgoModel\UserModel;

			// 部门
			$departmentModelObj = new \VirgoModel\DepartmentModel;

			// 部门关联用户
			$departmentRelUserModelObj  = new \VirgoModel\DepartmentRelUserModel;

			$uid = $_COOKIE['user_id'];

			$user = $userObj->readSingleTon( $uid );

			if( empty($user) ) {
				throw new \Exception("用户不存在");
			}

			// 获取顶级部门
			$departments = $departmentModelObj->getChildrensDepartments(0);

			$users = [];
			if( !empty($departments) ) {
				for ($i=0; $i < count($departments); $i++) { 
					$subDepartmens = $departmentModelObj->getChildrensDepartments( $departments[$i]['id'] );
					$pids[] = $departments[$i]['id'];
					$departments[$i]['subDepartmens'] = empty($subDepartmens)? null:$subDepartmens;
				}

				if( isset($pids) ) {
					$users = $departmentRelUserModelObj->getDepartmentsUsers($pids);
				}

			}

			// 转json
			$departmentsJson = empty($departments)? null:json_encode($departments, JSON_UNESCAPED_UNICODE);

			$usersJson = empty($users)? null:json_encode($users, JSON_UNESCAPED_UNICODE);
			
			// 增加页面
			require_once dirname(__FILE__).'/../../views/admin/adminWebStationMessage/_create.php';

		} catch(\Exception $e) {
			echo $e->getMessage();
		}

	 }

	/**
	* 消息详情
	* @author 	xww
	* @return 	void
	*/ 
	public function read()
	{
		
		try{

			if( empty($_GET['id']) ) {
				throw new \Exception("Wrong Param");
			}

			$id = $_GET['id'];
			$data = $this->model->read($id);
			if( empty($data) ) {
				throw new \Exception("数据不存在");
			}

			// 用户
			$userObj = new \VirgoModel\UserModel;

			$user = $userObj->readSingleTon( $data['user_id'] );

			// 增加页面
			require_once dirname(__FILE__).'/../../views/admin/adminWebStationMessage/_read.php';

		} catch(\Exception $e) {
			echo $e->getMessage();
		}

	}

	 // 处理增加
	 public function doCreate()
	 {
		 $page = $_POST['page'];
		 $rs = $this->model->doCreate();
		 if($rs){$this->showPage(['添加成功'],'/admin/webStationMessages?page='.$page); }
		 else {$this->showPage(['添加失败'],'/admin/webStationMessages?page='.$page); }
	 }

	 //修改专区分类页面
	 public function update()
	 {
		 $page_title = '修改管理';
		$id = $_GET['id'];
		$data = $this->model->read($id);
		// 专区分类修改页面
		 require_once dirname(__FILE__).'/../../views/admin/adminWebStationMessage/_update.php';
	 }

	 // 处理修改
	 public function doUpdate()
	 {
		 $page = $_POST['page'];
		 $rs = $this->model->doUpdate();
		 if($rs){$this->showPage(['修改成功'],'/admin/webStationMessages?page='.$page); }
		 else {$this->showPage(['修改失败'],'/admin/webStationMessages?page='.$page); }
	 }

	 // 处理删除
	 public function doDelete()
	 {
		 $rs =  $this->model->delete();
		 if($_POST){
			 if($rs){echo json_encode(['success'=>true,'message'=>'delete success']);}
			 else{echo json_encode(['success'=>false,'message'=>'delete failture']);}
		 } else {
			 if($rs){$this->showPage(['删除成功'],'/admin/webStationMessages');}
			 else {$this->showPage(['删除失败'],'/admin/webStationMessages');}
		 }
	 }
 }
 ?>