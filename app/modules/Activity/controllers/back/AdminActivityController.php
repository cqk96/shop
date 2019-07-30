<?php
 /**
 * 控制器
 * @author xww <5648*****@qq.com>
 * @version 1.0.0
 */
 namespace Module\Activity\Controller;
 use Illuminate\Database\Capsule\Manager as DB;
 use VirgoBack;
 class AdminActivityController extends VirgoBack\AdminBaseController{
	 /*
	 * @param  object  reflect this controller's  virgo model object
	 */
	 private $model;

	 public function __construct()
	 {
		$this->model = new \Module\Activity\VirgoModel\ActivityModel;
		parent::isLogin();
	 }

	 // 获取列表
	 public function lists()
	 {
		
		ob_clean();

		 $page_title = '管理';
		 $hidden_status = ['显示', '隐藏'];
		 $pageObj = $this->model->lists();
		 // 赋值数据
		$data = $pageObj->data;
		$currenPage = $pageObj->current_page;
		$size = $pageObj->size;
		$start = ($currenPage-1)*$size+1;
		 require_once dirname(__FILE__).'/../../views/adminActivity/index.php';
	 }

	 // 增加专区分类界面
	 public function create()
	 {
		 $page_title = '增加管理';
		 $hidden_status = ['显示', '隐藏'];

		 // 增加页面
		 require_once dirname(__FILE__).'/../../views/adminActivity/_create.php';
	 }

	 // 处理增加
	 public function doCreate()
	 {
		 $page = $_POST['page'];
		 $rs = $this->model->doCreate();
		 if($rs){$this->showPage(['添加操作成功'],'/admin/activitys?page='.$page); }
		 else {$this->showPage(['添加操作失败'],'/admin/activitys?page='.$page); }
	 }

	 //修改专区分类页面
	 public function update()
	 {
		 $page_title = '修改管理';
		 $hidden_status = ['显示', '隐藏'];
		$id = $_GET['id'];
		$data = $this->model->read($id);

		if(!empty($data['price'])){
			$data['price'] = number_format($data['price']/100, 2, '.', '');
		}

		// 活动开始时间
		if(!empty($data['start_time'])){
			$data['start_time'] = date("Y-m-d",$data['start_time']);
		}

		// 活动结束时间
		if(!empty($data['end_time'])){
			$data['end_time'] = date("Y-m-d",$data['end_time']);
		}

		// 报名开始时间
		if(!empty($data['apply_start_time'])){
			$data['apply_start_time'] = date("Y-m-d",$data['apply_start_time']);
		}

		// 报名结束时间
		if(!empty($data['apply_end_time'])){
			$data['apply_end_time'] = date("Y-m-d",$data['apply_end_time']);
		}

		// 签到开始时间
		if(!empty($data['sign_in_start_time'])){
			$data['sign_in_start_time'] = date("Y-m-d H:i:s",$data['sign_in_start_time']);
		}

		// 签到结束时间
		if(!empty($data['sign_in_end_time'])){
			$data['sign_in_end_time'] = date("Y-m-d H:i:s",$data['sign_in_end_time']);
		}

		// 专区分类修改页面
		require_once dirname(__FILE__).'/../../views/adminActivity/_update.php';
		
	 }

	 // 处理修改
	 public function doUpdate()
	 {
		 $page = $_POST['page'];
		 $rs = $this->model->doUpdate();
		 if($rs['success']){$this->showPage([$rs['message']],'/admin/activitys?page='.$page); }
		 else {$this->showPage([$rs['message']],'/admin/activitys?page='.$page); }
	 }

	 // 处理删除
	 public function doDelete()
	 {
		 $rs =  $this->model->delete();
		 if($_POST){
			 if($rs){echo json_encode(['success'=>true,'message'=>'delete success']);}
			 else{echo json_encode(['success'=>false,'message'=>'delete failture']);}
		 } else {
			 if($rs){$this->showPage(['删除成功'],'/admin/activitys');}
			 else {$this->showPage(['删除失败'],'/admin/activitys');}
		 }
	 }

	/**
	* 查看报名人员信息
	* render the page
	* @author xww
	* @return void
	*/ 
	public function application()
	{
		
		$id = $_GET['id'];
		$data = $this->model->getApplyPerson($id);
		for ($i=0; $i < count($data); $i++) { 
			$data[$i]['genderText'] = $this->getGenderText($data[$i]['gender']);

		}
		$page_title = "报名列表";
		require_once dirname(__FILE__).'/../../views/adminActivity/application.php';
	}

	/**
	* 获取性别文本
	* @author xww
	* @param  [$genderId]    string/int
	* @return string
	*/ 
	public function getGenderText($genderId)
	{
		
		switch ((int)$genderId) {
			case 1:
				return '男';
				break;
			case 2:
				return '女';
				break;
			default:
				return '保密';
				break;
		}

	}

	/**
	* 删除报名人
	* 减少报名人数
	* @author xww
	* @return void
	*/ 
	public function delete()
	{
		
		try {

			if( empty($_GET['aid']) && empty($_GET['id']) ) {
				throw new \Exception("Wrong Param");
			}

			$aid = $_GET['aid'];
			$uid = $_GET['id'];

			DB::beginTransaction();

			$data2 = \Module\Activity\EloquentModel\Activity::lockForUpdate()->find($aid);

			$rs = \Module\Activity\EloquentModel\ApplyActivity::where("user_id", '=', $uid)
										->where("active_id", '=', $aid)
										->update(['is_deleted'=>1]);

			$rs2 = \Module\Activity\EloquentModel\Activity::where("id", $aid)->decrement("apply_people_count", 1);

			if($rs && $rs2){
				DB::commit();
				$this->showPage(['删除成功'],'/admin/activitys/application?id='.$aid);
			} else {
				DB::rollback();
				$this->showPage(['删除失败'],'/admin/activitys/application?id='.$aid);
			}

		} catch(\Exception $e) {
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

	/**
	* 获取活动等级文本
	* @author 	xww
	* @param  	int/string 		$typeId
	* @return 	string
	*/
	public function getActivityLevelText($typeId)
	{
		switch ( (int)$typeId ) {
			case 1:
				return '监狱级';
				break;
			case 2:
				return '非监狱级';
				break;
			default:
				return '未知';
				break;
		}
	}

	/**
	* 查看活动二维码
	* render the page
	* @author 	xww
	* @return 	void
	*/
	public function readQrcode()
	{
		
		try{

			ob_clean();

			if( empty($_GET['id']) ) {
				throw new \Exception("Wrong Param");
			}

			$id = $_GET['id'];

			$data = $this->model->read( $id );

			if( empty($data) ) {
				throw new \Exception("数据不存在");	
			}

			// 签到地址
			$url = "http://" . $_SERVER['HTTP_HOST'] . "/front/api/v1/user/activity/signIn?id=" . $id;

			// 显示二维码
			$qrcodeObj = new \VirgoUtil\QRcode;

			header("Content-Type: image/png");
			$qrcodeObj->png($url, false, "M", 6, 1);

		} catch(\Exception $e) {
			echo $e->getMessage();
		}

	}

 }
 ?>