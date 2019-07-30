<?php
namespace VirgoFront;
class HomeController extends BaseController {

	protected $newsObj;

	public function __construct()
	{
	}

	// 首页
	public function index()
	{
		// $site = \EloquentModel\Site::first();
		// $nav_list = \EloquentModel\Nav::where("show",1)->orderBy("order")->get();//导航
		
		// // 暂时取所有片段
		// $pieceModel = new \EloquentModel\Piece;
		// //$pieces = \EloquentModel\Piece::all();
		// //获取部门信息
		// //$deparmentModel= new \EloquentModel\Department;
		// //获取图文信息
		// $sourceModel =new \EloquentModel\FrontSource;
		// $first_lists=$sourceModel->where('category','=','首页介绍')
		// 					->get();
		// $proj=$sourceModel->where('category','=','开发项目')
		// 					->get();
		// $client=$sourceModel->where('category','=','我们的客户')
		// 					->get();
							
		// //获取类别
		// $caseObj = \EloquentModel\NewsClasses::where("pclass_id",3)
		// 								  ->where('news_classes.status', '=', 0)
		// 								  ->where('news_classes.hidden', '=', 0);
		// $casesClasses = $caseObj->orderBy("news_classes.id",'asc')->get();

		// //获取案例
		// $cases = $caseObj->leftJoin('news','news.class_id', '=', 'news_classes.id')
		// 				 ->orderBy("news_classes.id",'asc')
		// 				 ->orderBy("news.created_at",'desc')
		// 				 ->where('pass', '=', 1)
		// 				 ->get();

		// /*获取动态*/
		// //获取类别
		// $dynamicObj = \EloquentModel\NewsClasses::where("pclass_id",2)
		// 								  ->where('news_classes.status', '=', 0)
		// 								  ->where('news_classes.hidden', '=', 0);
		// $dynamicClasses = $dynamicObj->orderBy("news_classes.id",'asc')->get();
		// $dynamic_state = $dynamicObj->leftJoin('news','news.class_id', '=', 'news_classes.id')
		// 							 //->orderBy("news_classes.id",'asc')
		// 							 ->orderBy("news.created_at",'desc')
		// 							 ->take(10)
		// 							 ->where('pass', '=', 1)
		// 							 ->get()
		// 							 ->toArray();
		// /*获取动态结束*/
		// // var_dump($cases,$dynamic_state);die;
		// //微信回调
		// if(!empty($_GET['echostr'])){
		// 	$wechatObj = new \VirgoFront\WechatController;
		// 	$wechatObj->read();
		// }
		// 
header("location: " . $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/COD/src/views/user/login.html");
exit;



		
	}
	
	// 地图
	public function map()
	{
		require dirname(__FILE__).'/../../views/timber_HTML/home/map.php';
	}
// test
	public function test111()
	{
		// require dirname(__FILE__).'/../../../public/style1/index.html';
		$test111 = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/style1/index.html');
		echo $test111;
	}
	/**
	* 显示OA系统页面
	* render the page
	* @author xww
	* @return void
	*/
	public function showOA()
	{
		$nav_list = \EloquentModel\Nav::where("show",1)->orderBy("order")->get();//导航
		require dirname(__FILE__).'/../../views/timber_HTML/home/oa.php';	
	}


	public function showCompany()
	{
		require $_SERVER['DOCUMENT_ROOT']."/template/index.html";	
	}

	/**
	* 显示mac地址申请页面
	* render the page
	* @author 	xww
	* @return 	void
	*/ 
	public function macApplication()
	{
		
		try{

			// 获取所有设备
			$tagManagementModelObj = new  \VirgoModel\TagManagementModel;

			$tags = $tagManagementModelObj->all();

			require dirname(__FILE__).'/../../views/front/macManagement/application.php';
		} catch(\Exception $e) {
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

	/**
	* 显示活动详情
	* render the page
	* @author 	xww
	* @return 	void
	*/
	public function activityShow()
	{
		
		try{

			if( empty( $_GET['id'] ) ) {
				throw new \Exception("Wrong Param ");
			}

			$id = $_GET['id'];

			// 获取所有设备
			$activityModelObj = new  \VirgoModel\ActivityModel;

			$data = $activityModelObj->read($id);

			if( empty($data) ) {
				throw new \Exception("Empty Data");
			}

			require dirname(__FILE__).'/../../views/front/activity/detail.php';

		} catch(\Exception $e) {
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

}