<?php
/**
* 文章控制器
*/
namespace VirgoBack\NewsClass\News;
class AdminNewsController extends \VirgoBack\AdminBaseController
{
	public function __construct()
	{
		parent::isLogin();
		$this->model = new \VirgoModel\NewsModel;
	}

	/**
	* 根据分类id获取下属文章列表
	* render the page
	* @author 	xww
	* @return 	void
	*/ 
	public function lists()
	{
		
		try {

			if(empty($_GET['id'])) { throw new \Exception("Wrong Param"); }

			$page_sub_title  = '列表';

			//分页
			$size = 10;
			$skip = 0;
			if(!empty($_GET['page'])){
				$page = (int)$_GET['page'];
				$skip = ($page-1)*$size;
			}

			$title = '';
			if(!empty($_GET['title'])){
				$title = trim($_GET['title']);
			}

			$startTime = '';
			if(!empty($_GET['startTime'])){
				$startTime = trim($_GET['startTime']);
				$startTime .= " 00:00:00";
				$startTime = strtotime($startTime);
			}

			$endTime = '';
			if(!empty($_GET['endTime'])){
				$endTime = trim($_GET['endTime']);
				$endTime .= " 23:59:59";
				$endTime = strtotime($endTime);
			}

			$pageObj = $this->model->lists('/admin/newsClass/news/lists?id='.$_GET['id'], $_GET['id'], $skip, $size, $startTime, $endTime);

			$data = $pageObj->data;

			// 数据列表页面
			require dirname(__FILE__).'/../../../../views/admin/adminNewsClasses/_newsLists.php';

		} catch (\Exception $e) {
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

	/**
	* 更多文章
	* @render the page
	* @author 	xww
	* @return 	void
	*/ 
	public function more()
	{
		
		try{

			if(empty($_GET['cid'])) { throw new \Exception("Wrong Param"); }

			$newsClassModelObj = new \VirgoModel\NewsClassModel;
			$data = $newsClassModelObj->read($_GET['cid']);

			if(empty($data)) { throw new \Exception("Wrong Param"); }

			// 数据列表页面
			require dirname(__FILE__).'/../../../../views/admin/adminNewsClasses/_newsMoreLists.php';

		} catch (\Exception $e) {
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

}