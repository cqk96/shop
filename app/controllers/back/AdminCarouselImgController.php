<?php
 /**
 * 控制器
 * @author xww <5648*****@qq.com>
 * @version 1.0.0
 */
 namespace VirgoBack;
 class AdminCarouselImgController extends AdminBaseController{
	 /*
	 * @param  object  reflect this controller's  virgo model object
	 */
	 private $model;

	 public function __construct()
	 {
		$this->model = new \VirgoModel\CarouselImgModel;
		parent::isLogin();
	 }

	 // 获取列表
	 public function lists()
	 {
		 $page_title = '管理';
		 $pageObj = $this->model->lists();

		 $hidden_status = ['显示', '隐藏'];

		 // 赋值数据
		$data = $pageObj->data;
		 require_once dirname(__FILE__).'/../../views/admin/adminCarouselImg/index.php';
	 }

	 // 增加界面
	 public function create()
	 {
		 $page_title = '增加管理';

		 $hidden_status = ['显示', '隐藏'];

		 $isCreate = true;

		 // 获取所有新闻
		$newsObj = new \VirgoModel\NewsModel;
		$news = $newsObj->all();

		// var_dump($news);

		 // 增加页面
		 require_once dirname(__FILE__).'/../../views/admin/adminCarouselImg/_create.php';

	 }

	 // 处理增加
	 public function doCreate()
	 {
		 $page = $_POST['page'];
		 $rs = $this->model->doCreate();
		 if($rs){$this->showPage(['添加成功'],'/admin/carouselImgs?page='.$page); }
		 else {$this->showPage(['添加失败'],'/admin/carouselImgs?page='.$page); }
	 }

	 //修改页面
	 public function update()
	 {
		 $page_title = '修改管理';
		$id = $_GET['id'];
		$data = $this->model->read($id);

		 $hidden_status = ['显示', '隐藏'];

		 // 获取所有新闻
		$newsObj = new \VirgoModel\NewsModel;
		$news = $newsObj->all();
		 
		// 修改页面
		 require_once dirname(__FILE__).'/../../views/admin/adminCarouselImg/_update.php';
	 }

	 // 处理修改
	 public function doUpdate()
	 {
		 $page = $_POST['page'];
		 $rs = $this->model->doUpdate();
		 if($rs){$this->showPage(['修改成功'],'/admin/carouselImgs?page='.$page); }
		 else {$this->showPage(['修改失败'],'/admin/carouselImgs?page='.$page); }
	 }

	 // 处理删除
	 public function doDelete()
	 {
		 $rs =  $this->model->delete();
		 if($_POST){
			 if($rs){echo json_encode(['success'=>true,'message'=>'delete success']);}
			 else{echo json_encode(['success'=>false,'message'=>'delete failture']);}
		 } else {
			 if($rs){$this->showPage(['删除成功'],'/admin/carouselImgs');}
			 else {$this->showPage(['删除失败'],'/admin/carouselImgs');}
		 }
	 }
 }
 ?>