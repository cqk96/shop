<?php
namespace VirgoFront;
class NewsController extends BaseController {

	protected $newsObj;

	public function __construct()
	{
		$this->newsObj = new \EloquentModel\News;
	}

	public function lists()
	{
		$needColumns = array('title','updated_at','keywords');
		$rs = $this->newsObj->getNewsLists('',$needColumns);
	}

	public function read()
	{
		
		try {

			if(empty($_GET['id'])) {
				throw new \Exception("id can not be null");
			}

			$id = $_GET['id'];

			$dataObj = \EloquentModel\News::find($id);

			if(empty($dataObj)) {
				throw new \Exception("文章不存在或已删除");	
			}

			$data = $dataObj->toArray();

			// 外部链接
			if(!empty($data['url'])) {
				header("Location: ".$data['url']);
				exit();
			}

			// 获取用户相关
			$user = \EloquentModel\User::find($data['author']);
			$data['avatar'] = empty($user['avatar'])? '':$user['avatar'];
			$data['nickname'] = empty($user['nickname'])? '':$user['nickname'];

			// 其他处理
			$data['created_at'] = substr($data['created_at'], 0, 10);

			$fname = md5($id).".html";

			// 显示
			$obj = new \VirgoModel\NewsModel;
			$obj->show($fname,$data);

		} catch(\Exception $e) {
			echo "<h1>".$e->getMessage()."<h1>";
		}

	}

	public function read2()
	{
		try {

			if(empty($_GET['id'])) {
				throw new \Exception("id can not be null");
			}

			$id = $_GET['id'];

			$dataObj = \EloquentModel\News::find($id);

			if(empty($dataObj)) {
				throw new \Exception("文章不存在或已删除");	
			}

			$data = $dataObj->toArray();

			// 外部链接
			if(!empty($data['url'])) {
				header("Location: ".$data['url']);
				exit();
			}

			require_once( dirname(__FILE__) . "/../../views/front/news/readOne.php" );

		} catch(\Exception $e) {
			echo $this->showHtmlNotice( $e->getMessage() );
		}

	}
	
}