<?php
 /**
 * 控制器
 * @author xww <5648*****@qq.com>
 * @version 1.0.0
 */
 namespace VirgoBack;
 class AdminManageAppController extends AdminBaseController{
	 /*
	 * @param  object  reflect this controller's  virgo model object
	 */
	 private $model;

	 public function __construct()
	 {
		$this->model = new \VirgoModel\ManageAppModel;
		parent::isLogin();
	 }

	 // 获取列表
	 public function lists()
	 {
		 $page_title = '管理';
		 $pageObj = $this->model->lists();
		 // 赋值数据
		$data = $pageObj->data;
		 require_once dirname(__FILE__).'/../../views/admin/adminManageApp/index.php';
	 }

	 // 增加专区分类界面
	 public function create()
	 {
		
		$page_title = '增加管理';

		// 获取最大开发版本号 并+1
		$maxVersion = $this->model->getMaxVersion()+1;

		 // 增加页面
		 require_once dirname(__FILE__).'/../../views/admin/adminManageApp/_create.php';
	 }

	 // 处理增加
	 public function doCreate()
	 {
		 $page = $_POST['page'];
		 $rs = $this->model->doCreate();
		 if($rs){$this->showPage(['添加成功'],'/admin/manageApps?page='.$page); }
		 else {$this->showPage(['添加失败'],'/admin/manageApps?page='.$page); }
	 }

	 //修改专区分类页面
	 public function update()
	 {
		 $page_title = '修改管理';
		$id = $_GET['id'];
		$data = $this->model->read($id);
		// 专区分类修改页面
		 require_once dirname(__FILE__).'/../../views/admin/adminManageApp/_update.php';
	 }

	 // 处理修改
	 public function doUpdate()
	 {
		 $page = $_POST['page'];
		 $rs = $this->model->doUpdate();
		 if($rs){$this->showPage(['修改成功'],'/admin/manageApps?page='.$page); }
		 else {$this->showPage(['修改失败'],'/admin/manageApps?page='.$page); }
	 }

	 // 处理删除
	 public function doDelete()
	 {
		 $rs =  $this->model->delete();
		 if($_POST){
			 if($rs){echo json_encode(['success'=>true,'message'=>'delete success']);}
			 else{echo json_encode(['success'=>false,'message'=>'delete failture']);}
		 } else {
			 if($rs){$this->showPage(['删除成功'],'/admin/manageApps');}
			 else {$this->showPage(['删除失败'],'/admin/manageApps');}
		 }
	 }

	 /**
	 * 下载
	 * @author 	xww
	 * @return 	void
	 */
	public function download()
	{
	 	try {

	 		if( empty($_GET['id'])) {
	 			throw new \Exception("Wrong Param");
	 		}

	 		$id = $_GET['id'];

	 		$data = $this->model->read($id);

	 		if(empty($data)) {
	 			throw new \Exception("数据不存在");
	 		}

	 		$file = $_SERVER['DOCUMENT_ROOT'].$data['apk_url'];
			if(!file_exists($file)){
				$this->showPage(['很抱歉，资源不存在'], '/admin/manageApps');	
				exit();
			}

	 		ob_clean();
			header("Content-Type: application/vnd.android.package-archive; charset=utf-8");
			header("Content-Disposition: attachment; filename='" . urlencode("传带帮") . ".apk'");
			header("Content-Length: ".strlen(file_get_contents($file)));
			readfile($file);

	 	} catch(\Exception $e) {
	 		echo $e->getMessage();
	 	}

	}

 }
 ?>