<?php
namespace VirgoFront\NewsClass\News;
use VirgoFront;
class NewsController extends VirgoFront\BaseController {

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
	
	/**
	* @SWG\Get(path="/front/api/v1/NewsClass/news/className/latest", tags={"NewsClass"}, 
	*  summary="根据分类名获取该分类最新一条文章详情详情",
	*  description="此接口返回html",
	*  produces={"text/html"},
	*  @SWG\Parameter(name="className", type="string", required=true, in="query", description="分类名称"),
	*  @SWG\Response(
	*   response=200,
	*   description="获取成功"
	*  )
	* )
	* 根据分类名 获取最新文章详情
	* @author 	xww
	* @return 	void
	*/
	public function classNameLatest()
	{
		
		try{

			if( empty($_GET['className']) ) {
				throw new \Exception("Wrong Param");
			}

			$model = new \VirgoModel\NewsModel;

			$className = $_GET['className'];

			$data = $model->getLatestInfoByClassName( $className );

			if( empty($data) ) {
				throw new \Exception("数据不存在");
			}

			$data = $data[0];

			require_once( dirname(__FILE__) . "/../../../../views/front/news/classNameLatest.php" );

		} catch(\Exception $e) {
			echo $this->showHtmlNotice( $e->getMessage() );
		}

	}

	/**
	* 显示文章详情
	* @author 	xww
	* @return 	void
	* @todo
	*/
	public function show()
	{
		
		try{

			if( empty($_GET['id']) ) {
				throw new \Exception("Wrong Param");
			}

			$id = $_GET['id'];

			$model = new \VirgoModel\NewsModel;

			$data = $model->readSingleTon($id);

			if( empty($data) ) {
				throw new \Exception("数据不存在");
			}

			require_once( dirname(__FILE__) . "/../../../../views/front/news/show.php" );

		} catch(\Exception $e) {
			echo $this->showHtmlNotice( $e->getMessage() );
		}	

	}

}