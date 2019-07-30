<?php
/**
* 
*/
namespace VirgoBack;

use \EloquentModel;
use \VirgoUtil;
class AdminNewsController extends AdminBaseController
{
	public function __construct()
	{
		// if(empty($_COOKIE['user_login'])){
		// 	header("Refresh: 5;url=/admin");
		// 	echo "请重新登陆";
		// }
		parent::isLogin();
	}
	public function create()
	{
		$newsClassesObj = new \EloquentModel\NewsClasses;
		
		$page_title = "文章管理列表";
		$page_sub_title = "文章管理列表";
		$newsClasses = $newsClassesObj->lists();

		$news['id'] = '';
		$news['class_id'] = '';
		$news['pass'] = 1;
		$news['cover'] = '';
		//$news['user_login'] = empty($_COOKIE['admin'])? 1:$_COOKIE['admin'];
		$news['showCover'] = '';

		require dirname(__FILE__).'/../../views/admin/adminNews/add.php';

	}

	public function read()
	{
		$newsClassesObj = new \EloquentModel\NewsClasses;
		$newsObj = new \EloquentModel\News;

		$page_title = "文章详情";
		$page_sub_title = "文章详情";
		$newsClasses = $newsClassesObj->lists();
		$news = $newsObj->leftJoin('users','users.id','=','news.author')
						->select('news.*','users.user_login')
						->find($_GET['id']);

		//封面解析
		// $cover = '';
		// $tempCoverData = json_decode($news['cover']);
		// if(!empty($tempCoverData)){
		// 	if($tempCoverData[0]!=''){
		// 		$cover = $tempCoverData[0];
		// 	}
		// }

		//$news['showCover'] = $cover;
		$news['showCover'] = $news['cover'];

		require dirname(__FILE__).'/../../views/admin/adminNews/read.php';
	}

	public function update()
	{
		$newsClassesObj = new \EloquentModel\NewsClasses;
		$newsObj = new \EloquentModel\News;

		$page_title = "文章修改";
		$page_sub_title = "文章修改";
		$newsClasses = $newsClassesObj->lists();
		$news = $newsObj->leftJoin('users','users.id','=','news.author')
						->select('news.*','users.user_login')
						->find($_GET['id']);
		//封面解析
		// $cover = '';
		// $tempCoverData = json_decode($news['cover']);
		// if(!empty($tempCoverData)){
		// 	if($tempCoverData[0]!=''){
		// 		$cover = $tempCoverData[0];
		// 	}
		// }

		// $news['showCover'] = $cover;
		$news['showCover'] = empty($news['cover'])? '':'http://'.$_SERVER['HTTP_HOST'].'/'.$news['cover'];
		
		require dirname(__FILE__).'/../../views/admin/adminNews/edit.php';

	}

	public function delete()
	{

		$newsObj = new \EloquentModel\News;
		$functionsObj = new \VirgoUtil\Functions;

		if($_POST)
			$ids = implode(',', $_POST['ids']);
		else 
			$ids = $_GET['id'];

		$rs = $newsObj->whereRaw('id in('.$ids.')')->update(array('status'=>1,'updated_at'=>time()));

		if($rs){
			if(!$_POST){
				header("Refresh: 5;url=/admin/news");
				echo "删除成功";
			} else {
				echo $functionsObj->turnToJson(array(),'001','删除成功',true);
			}
		} else {
			if(!$_POST){
				header("Refresh: 5;url=/admin/news");
				echo "删除失败";
			} else {
				echo $functionsObj->turnToJson(array(),'012','删除失败',false);
			}
		}
	}

	public function lists()
	{
		
		$pageObj = new \VirgoUtil\Page2;

		$page_title = "文章管理列表";
		$page_sub_title = "文章管理列表";
		$newsObj = new \EloquentModel\News;
		$newsObj = $newsObj->select('news.*','news_classes.class_name','users.user_login')
						->leftJoin('news_classes','news_classes.id','=','news.class_id')
						->leftJoin('users','users.id','=','news.author')						
						->where('news.status','=',0)
						->orderBy('top','desc')
						->orderBy('id','asc');

		// 标题过滤
		if(!empty($_GET['title'])){
			$_GET['title'] = trim($_GET['title']);
			$newsObj = $newsObj->where("news.title", 'like', "%" . $_GET['title'] . "%" );
			$pageObj->setPageQuery(['title'=>$_GET['title']]); 
		}

		// 分类过滤
		if(!empty($_GET['classId'])){
			$_GET['classId'] = trim($_GET['classId']);
			$newsObj = $newsObj->where("news.class_id", $_GET['classId']);
			$pageObj->setPageQuery(['classId'=>$_GET['classId']]); 
		}

		//父菜单总记录数
		$totalCount = count( $newsObj->get()->toArray() );
		//分页
		$size = 10;
		if(!empty($_GET['page'])){
			$page = (int)$_GET['page'];
			$skip = ($page-1)*$size;
			$newsObj = $newsObj->skip($skip)->take($size);
		} else {
			$newsObj = $newsObj->skip(0)->take($size);
		}

		$news = $newsObj->get()->toArray();

		if(empty($news)){
			$data = [];
		} else {
			foreach ($news as $key => $value) {
				$likeCount = \EloquentModel\FavorNews::where("news_id", '=', $value['id'])->count();
				$news[$key]['like_count'] = $likeCount;
			}
			$data = $news;
		}

		
		$pageObj->setUrl('/admin/news');
		$pageObj->setData($data);
		$pageObj->setTotalCount($totalCount);
		$pageObj->setSize($size);
		$dataObj = $pageObj->doPage();
		unset($news);
		$news = $dataObj->data;

		// 可用文章分类
		$newsClassesObj = new \VirgoModel\NewsClassModel;
		$newsClasses = $newsClassesObj->all();

		require dirname(__FILE__).'/../../views/admin/adminNews/index.php';

	}

	//todo
	public function doCreate()
	{

		$functionsObj = new \VirgoUtil\Functions;
		$newsObj = new \EloquentModel\News;
		$configs = $functionsObj->deleteNotNeedDataArray($_POST, array('id','file'));
		$user_login = empty($_COOKIE['admin'])? 1:$_COOKIE['admin'];

		if( !empty($configs['class_id']) ) {
			
			// 如果是课程体系 就要判断是否已经存在分类文章    存在就不允许
			if( $configs['class_id']==12 ) {
				
				$hasCount = $newsObj->where("class_id", 12)->where("status", 0)->count();				
				if( $hasCount ) {
					
					header("Refresh: 2;url=/admin/news");
					echo "该类文章只能存在一条";		
					exit();
					
				}

			}

		}
		//$userData = User::select('id')->where('user_login','=',$user_login)->get();
		
		$configs['author'] = empty($_COOKIE['admin'])? 1:$_COOKIE['admin'];//$userData[0]['id'];
		$configs['cover'] = $this->upload();
		$configs['pics'] = '';
		$configs['content'] = empty($_POST['content'])? "":$_POST['content'];
		$configs['created_at'] = time();
		$configs['updated_at'] = time();
		$configs['keywords'] = '';

		$rs = $newsObj->insertGetId($configs);

		if($rs) {

			// 生成缓存文件
			$data = $newsObj->find($rs)->toArray();

			$user = \EloquentModel\User::find($data['author']);
			$data['avatar'] = empty($user)? '':$user['avatar'];
			$data['nickname'] = empty($user)? '':$user['nickname'];

			// 其他处理
			$data['created_at'] = substr($data['created_at'], 0, 10);

			$fname = md5($data['id']).".html";

			// 显示
			$obj = new \VirgoModel\NewsModel;
			// $obj->createCache($fname, $data, 'newsTemplate.php', '/tempNews');

			header("Refresh: 2;url=/admin/news");
			echo "添加文章成功";
		} else {
			header("Refresh: 2;url=/admin/news");
			echo "添加文章失败";
		}
			

	}

	public function doUpdate()
	{
		
		$functionsObj = new \VirgoUtil\Functions;
		$newsObj = new \EloquentModel\News;
		$configs = $functionsObj->deleteNotNeedDataArray($_POST, array('id','file'));
		//$user_login = empty($_COOKIE['user_login'])? 'admin':$_COOKIE['user_login'];
		//$userData = User::select('id')->where('user_login','=',$user_login)->get();

		//$configs['author'] = $userData[0]['id'];
		$configs['cover'] = !empty($_FILES['file']['name'])? $this->upload():$configs['cover'];
		$configs['pics'] = '';
		$configs['updated_at'] = time();
		$configs['content'] = empty($_POST['content'])? "":$_POST['content'];
		$configs['keywords'] = '';

		$rs = $newsObj->where('id','=',$_POST['id'])->update($configs);

		
		if($rs) {

			// 生成缓存文件
			$data = $newsObj->find($_POST['id'])->toArray();

			$user = \EloquentModel\User::find($data['author']);
			$data['avatar'] = empty($user)? '':$user['avatar'];
			$data['nickname'] = empty($user)? '':$user['nickname'];

			// 其他处理
			$data['created_at'] = substr($data['created_at'], 0, 10);

			$fname = md5($data['id']).".html";

			// 显示
			$obj = new \VirgoModel\NewsModel;
			// $obj->createCache($fname, $data, 'newsTemplate.php', '/tempNews');

			header("Refresh: 5;url=/admin/news");
			echo "修改文章成功";
		} else {
			header("Refresh: 5;url=/admin/news");
			echo "修改文章失败";
		}

	}

	//清空文件封面
	public function deleteNewsCover()
	{
		$functionsObj = new \VirgoUtil\Functions;

		$rs = \EloquentModel\News::where('id','=',$_POST['id'])->update(array('cover'=>''));
		if($rs)
			echo $functionsObj->turnToJson(array(),'001','删除成功',true);
		else
			echo $functionsObj->turnToJson(array(),'012','删除失败',false);
	}

	//文件上传
    public function upload()
    {
        $picUrl = '';

        //上传根目录是否存在
        if(!file_exists("./upload")){
            mkdir("./upload");
        }
        if(!file_exists("./upload/newsCover/"))
            mkdir("./upload/newsCover");
        $dir_array = array();
        //存在上传文件
        if($_FILES){
            foreach ($_FILES as $key => $value) {
                if($value['error']===0){
                    $ext_array = explode(".", $value['name']);
                    $ext = array_pop($ext_array);
                    $name = time().".".$ext;
                    $rs = move_uploaded_file($value['tmp_name'], "./upload/newsCover/".$name);
                    $picUrl = "/upload/newsCover/".$name;
                    array_push($dir_array, "/upload/newsCover/".$name);
                }
            }
        }else {
            $dir_array = array();
        }

        return $picUrl;
        //return json_encode($dir_array);
    }

    //置顶
    public function doTop()
    {
    	
    	$newsObj = new \EloquentModel\News;
		$functionsObj = new \VirgoUtil\Functions;

		if($_POST)
			$ids = implode(',', $_POST['ids']);
		else 
			$ids = $_GET['id'];

		$rs = $newsObj->whereRaw('id in('.$ids.')')->update(array('top'=>1,'updated_at'=>time()));

		if($rs){
			if(!$_POST){
				header("Refresh: 5;url=/admin/news");
				echo "置顶成功";
			} else {
				echo $functionsObj->turnToJson(array(),'001','置顶成功',true);
			}
		} else {
			if(!$_POST){
				header("Refresh: 5;url=/admin/news");
				echo "置顶失败";
			} else {
				echo $functionsObj->turnToJson(array(),'012','置顶失败',false);
			}
		}

    }

    /**
	* 后台显示新闻详情
	* render the page
	* @author 	xww
	* @return 	void
    */ 
    public function show()
    {
    	
    	try{

    		if(empty($_GET['id'])) { throw new \Exception("Wrong Param"); }

    		$data = \EloquentModel\News::find($_GET['id']);

    		if(empty($data)) { throw new \Exception("Wrong Param"); }

    		// 加载详情页面
    		require dirname(__FILE__).'/../../views/admin/adminNews/detail.php';
    	} catch(\Exception $e) {
    		echo "<h1>".$e->getMessage()."</h1>";
    	}

    }

    /**
    * 资料库
    * render the page
    * @author 	xww
    * @return 	void
    */
    public function database()
    {
    	
    	try{

    		$newsModelObj = new \VirgoModel\NewsModel;

    		// 获取题库列表
    		$questionBankPageObj = $newsModelObj->getDatabaseLists(11, 0, 4, '', null, ['id', 'title'], [['created_at', 'desc']]);
    		$questionBankData = $questionBankPageObj->data;

    		// 获取制度列表
    		$institutionPageObj = $newsModelObj->getDatabaseLists(7, 0, 4, '', null, ['id', 'title'], [['created_at', 'desc']]);
    		$institutionData = $institutionPageObj->data;

    		// 课程体系列表
			$courseSystemPageObj = $newsModelObj->getDatabaseListsFromClasses([13, 14], 0, 4, '', null, ['id', 'title', 'cover'], [['created_at', 'desc']]);
    		$courseSystemData = $courseSystemPageObj->data;

    		// 获取案例列表
    		$casePageObj = $newsModelObj->getDatabaseLists(8, 0, 6, '', null, ['id', 'title', 'cover'], [['created_at', 'desc']]);
    		$caseData = $casePageObj->data;

			// 拓展阅读列表
			$readNewsPageObj = $newsModelObj->getDatabaseLists(9, 0, 6, '', null, ['id', 'title', 'cover'], [['created_at', 'desc']]);
    		$readNewsData = $readNewsPageObj->data;	

    		// 加载详情页面
    		require dirname(__FILE__).'/../../views/admin/adminNews/database.php';

    	} catch(\Exception $e) {
    		echo "<h1>".$e->getMessage()."</h1>";
    	}

    }

    /**
    * 资料库中  指定的    可以进行搜索的分类
    * @author 	xww
    * @return 	void
    */
    public function classNewsSearch()
    {

    	try{

    		if( empty($_GET['classId']) ) {
    			throw new \Exception("Wrong Param");
    		}

    		$classId = (int)$_GET['classId'];

    		// 增加分类数组
    		$canSearch = [8, 9];

    		// 验证分类
    		if( !in_array($classId, $canSearch) ) {
    			throw new \Exception("Wrong Param");
    		}

    		// 分类按钮判断
    		$btnImgSrc = $this->getButtonImg($classId);

    		$newsModelObj = new \VirgoModel\NewsModel;

    		// 处理查询
    		$search = null;
    		$searchArr = null;
    		$hasData = false;

    		if( !empty($_GET['title']) ) {
    			$data = [];
    			$searchArr = [['title', 'like', "%". (trim($_GET['title'])) ."%"]];
    			$casePageObj = $newsModelObj->getDatabaseLists($classId, null, null, '', $searchArr, ['id', 'title', 'cover', 'description'], [['created_at', 'desc']]);
    			$data = $casePageObj->data;
    			$hasData = empty($data)? false:true;
    			$search = $_GET['title'];
    		} else {
    			
    			// 热门搜索
    			$hotSearchDataObj = $newsModelObj->getDatabaseLists($classId, 0, 6, '', [['search_count', '<>', 0]], ['id', 'title', 'cover'], [['search_count', 'desc'],['created_at', 'desc']]);
    			$hotSearchData = $hotSearchDataObj->data;

    			// 吐血推荐
    			$recommendDataObj = $newsModelObj->getDatabaseLists($classId, 0, 6, '', [['top', '<>', 0]], ['id', 'title', 'cover'], [['top', 'desc'],['created_at', 'desc']]);
    			$recommendData = $recommendDataObj->data;

    		}

    		// var_dump($hasData);

    		// 加载详情页面
    		require dirname(__FILE__).'/../../views/admin/adminNews/classSearch.php';

    	} catch(\Exception $e) {
    		echo "<h1>".$e->getMessage()."</h1>";
    	}

    }

    /**
    * 列表更多
    * render the page
    * @author  	xww
    * @return 	void
    */
    public function listsMore()
    {
    	try{

    		if( empty($_GET['classId']) ) {
    			throw new \Exception("Wrong Param");
    		}

    		$newsModelObj = new \VirgoModel\NewsModel;

    		$classId = (int)$_GET['classId'];

    		// 列表标题
    		$title = $this->getDatabaseClassTitle($classId);

    		// 处理分页
    		$page = empty( $_GET['page'] ) || (int)$_GET['page']<1 ? 1:(int)$_GET['page'];
    		$size = 10;
    		$page -= 1;
    		$skip = $page*$size;

    		$params = [];

    		$params[] = ['classId', $classId];

    		// 处理查询
    		$search = null;
    		if( !empty($_GET['title']) ) {
    			$search = trim( $_GET['title'] );
    			$searchArr = [ ['title', 'like', "%".$search."%"] ];
    			$params[] = ['title', $search];
    		} else {
    			$searchArr = null;
    		}

    		// 根据分类id查询数据列表
    		$paginationObj = $newsModelObj->getDatabaseLists($classId, $skip, $size, '/admin/news/database/lists/more?classId=' . $classId, $searchArr, ['id', 'title', 'created_at'], [['created_at', 'desc']], $params);
    		$data = $paginationObj->data;

    		$totalCount = $paginationObj->totalCount;
    		$totalPage = ceil( $totalCount / $size );

    		// 加载详情页面
    		require dirname(__FILE__).'/../../views/admin/adminNews/lists-more.php';

    	} catch(\Exception $e) {
    		echo "<h1>".$e->getMessage()."</h1>";
    	}
    }

    /**
    * 后台文章详情
    * render the page
    * @author 	xww
    * @return 	void
    */
    public function readVer2()
    {

    	try{

    		if( empty($_GET['id']) ) {
    			throw new \Exception("Wrong Param");
    		}

    		// 文章id获取
    		$id = (int)$_GET['id'];
    		$newsModel = new \VirgoModel\NewsModel;
    		$data = $newsModel->read($id);

    		if( empty($data) ) {
    			throw new \Exception("数据不存在");
    		}

    		// 加载详情页面
    		require dirname(__FILE__).'/../../views/admin/adminNews/readVer2.php';

    	} catch(\Exception $e) {
    		echo "<h1>".$e->getMessage()."</h1>";
    	}

    }

    /**
    * 根据分类获取对应搜索按钮
    * @author 	xww
    * @param 	int/string  	$classId
    * @return 	string
    */
    public function getButtonImg($classId)
    {
    
    	switch ((int)$classId) {
    		case 8:
    			return "/images/search-case-btn.png";
    			break;
    		case 9:
    			return "/images/search-book-btn.png";
    			break;
    		
    		default:
    			return "/images/search-book-btn.png";
    			break;
    	}

    }

    /**
    * 根据分类获取列表
    * @author 	xww
    * @param 	int/string 	 	$id
    * @return 	string
    */
    public function getDatabaseClassTitle($id)
    {
    	switch ((int)$id) {
    		case 7:
    			return "制度列表";
    			break;
    		case 11:
    			return "题库列表";
    			break;	
    		default:
    			$newsClassModelObj = \VirgoModel\NewsClassModel;
    			$newsClassData = $newsClassModelObj->read($id);
    			return empty($newsClassData)? '未知':$newsClassData['title'] . '列表';
    			break;
    	}
    }

    /**
    * 课程体系
    * @author 	xww
    * @return 	void
    */
    public function courseSystem()
    {
    	
    	try{

    		// 课程体系页面
    		// url中包含 index 1=>课程理论    2=>实训课程    加载对应的列表页面
    		// 如果没有或传入了其他则是课程体系详情页

    		if( !empty($_GET['index']) && ($_GET['index'] == 1 || $_GET['index'] == 2) ) {
    			// 列表页

    			$newsModelObj = new \VirgoModel\NewsModel;

    			$index = $_GET['index'];

    			$classId = $index==1? 13:14;

    			// 处理分页
	    		$page = empty( $_GET['page'] ) || (int)$_GET['page']<1 ? 1:(int)$_GET['page'];
	    		$size = 10;
	    		$page -= 1;
	    		$skip = $page*$size;

	    		$params = [];

	    		$params[] = ['index', $index];

	    		// 处理查询
	    		$search = null;
	    		if( !empty($_GET['title']) ) {
	    			$search = trim( $_GET['title'] );
	    			$searchArr = [ ['title', 'like', "%".$search."%"] ];
	    			$params[] = ['title', $search];
	    		} else {
	    			$searchArr = null;
	    		}

	    		// 根据分类id查询数据列表
	    		$paginationObj = $newsModelObj->getDatabaseLists($classId, $skip, $size, '/admin/news/database/lists/courseSystem?index=1' . $index, $searchArr, ['id', 'title', 'created_at'], [['created_at', 'desc']], $params);
	    		$data = $paginationObj->data;

	    		$totalCount = $paginationObj->totalCount;
	    		$totalPage = ceil( $totalCount / $size );

	    		// 加载详情页面
	    		require dirname(__FILE__).'/../../views/admin/adminNews/course-system-lists.php';

    		} else {
    			// 详情页

    			$data = \EloquentModel\NewsClasses::leftJoin("news", "news.class_id", "=", "news_classes.id")
									  ->where("news.status", 0)
									  ->where("news_classes.status", 0)
									  ->where("news.pass", 1)
									  ->where("news_classes.class_name", "课程体系")
									  ->orderBy("news.created_at", "desc")
									  ->orderBy("news.id", "desc")
									  ->first();

				if( empty($data) ) {
					throw new \Exception("没有符合条件内容");
				}

				// 加载课程体系详情页面
    			require dirname(__FILE__).'/../../views/admin/adminNews/course-system-info.php';

    		}
    	} catch(\Exception $e) {
    		echo $e->getMessage();
    	}

    }

}