<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class NewsModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\News; 
	}

	/**
	* 获取前端显示lists
	*/ 
	public function lists($url, $parentId='', $skip='', $take='', $title='', $timeStart='', $timeEnd='')
	{
		
		$query = $this->_model->where('news.status','=',0)->orderBy('top','desc')->orderBy('id','asc');

		if(!empty($parentId)) {
			$query = $query->where("class_id", $parentId);
		}

		if(!empty($title)) {
			$query = $query->where("title", "like", "%".$title."%" );
		}

		if(!empty($timeStart)) {
			$query = $query->where("updated_at", '>=', $timeStart);
		}

		if(!empty($timeEnd)) {
			$query = $query->where("updated_at", '<=', $timeEnd);
		}

		//父菜单总记录数
		$totalCount = $query->count();

		// 分页
		if(!empty($take)) {
			$query = $query->skip($skip)->take($take);	
		}

		// 获取数据
		$data = $query->get()->toArray();

		$pageObj = new \VirgoUtil\Page2;
		$pageObj->setUrl($url);
		$pageObj->setData($data);
		$pageObj->setTotalCount($totalCount);
		$pageObj->setSize($take);

		return $pageObj->doPage();

	}

	/**
	* 根据分类id获取列表
	* @author 	xww
	* @param 	int/string 	 class id
	* @param 	int/string 	 take
	* @param 	int/string 	 skip
	* @return 	array
	*/ 
	public function getListFromClassId($id, $take=0, $skip=0)
	{
		
		$query = $this->_model->where('news.status','=',0)->orderBy('top','desc')->orderBy('id','asc')->where("class_id", $id);

		if(!empty($take)) {
			$query = $query->skip($skip)->take($take);
		}

		return $query->get()->toArray();

	}

	/**
	* 获取前端使用的列表对象
	* @author 	xww
	* @param 	int/string 		class's id
	* @param 	int/string 		take
	* @param 	int/string 		skip
	* @return 	array
	*/ 
	public function getFrontLists($cid, $take, $skip)
	{
		
		$query = $this->_model->where('news.status','=',0)->orderBy('top','desc')->orderBy('id','asc')->where("class_id", $cid);

		$totalCount = $query->count();
		$data = $query->skip($skip)->take($take)->get()->toArray();
		$pageCount = ceil($totalCount/$take);

		$temp['totalCount'] = $totalCount;
		$temp['data'] = empty($data)? null:$data;
		$temp['pageCount'] = $pageCount;

		return $temp;		

	}

	/**
	* 根据分类id获取列表 ver2
	* @author 	xww
	* @param 	int/string 	 $class id
	* @param 	int/string 	 $take
	* @param 	int/string 	 $skip
	* @param 	string 	 	 $search
	* @return 	array
	*/ 
	public function getListFromClassIdVer2($id, $skip=0, $take=0, $order='asc', $needs=null, $search=null )
	{
		
		$query = $this->_model->where('news.status','=',0)->where("pass", 1)->where("class_id", $id)->orderBy('top','desc')->orderBy('id', $order)->orderBy("created_at", $order);

		if(!empty($take)) {
			$query = $query->skip($skip)->take($take);
		}

		if(!empty($needs) && is_array($needs)) {
			$query = $query->select($needs);
		}

		if( !is_null( $search ) ) {
			$query = $query->where("title", 'like', "%". $search ."%");
		}

		return $query->get()->toArray();

	}

	/**
	* 根据分类id获取列表 ver2--的总数量
	* @author 	xww
	* @param 	int/string 	 	$class id
	* @param 	string 	 		$search
	* @return 	array
	*/ 
	public function getListFromClassIdVer2Count($id, $search=null)
	{

		$query = $this->_model->where('news.status','=',0)->where("pass", 1)->where("class_id", $id);

		if( !is_null( $search ) ) {
			$query = $query->where("title", 'like', "%". $search ."%");
		}
		
		return $query->count();

	}

	/**
	* 文章显示
	* render the page
	* 存储缓存文件  未失效时采用老文件  失效时重新生成
	* @author 	xww
	* @param 	string 				$fname 		生成的静态文件名
	* @param 	array 				$param 		符合模板的数据数组
	* @param 	string 				$template 	模板地址(相对于模板文件夹而言)
	* @param 	string 				$saveUrl 	存储地址(相对于根目录而言 要带上/符号)
	* @param 	string/int 			$saveUrl 	有效时间
	* @return 	void
	*/
	public function show($fname, $params=null, $template='newsTemplate.php', $saveUrl='/tempNews', $time=432000)
	{

		// 实例化 function 对象
		$functionsObj = new \VirgoUtil\Functions;

		if(!$functionsObj->fileExists('../../app/templates/'.$template)){
			throw new \Exception("模板文件".$template."不存在");
		}

		// 判断文件是否存在
		$createRs = true;
		if(!$functionsObj->fileExists($saveUrl."/".$fname)){
			// 生成文件并显示
			$createRs = $this->createCache($fname, $params,$template, $saveUrl);
		} else {
			// 判断文件更新时间 如果超出时间则进行更新

			if($saveUrl[0]!="/") {
				$saveUrl = "/".$saveUrl;
			}

			// 获取文件更新时间
			$updateTime = filemtime($_SERVER['DOCUMENT_ROOT'].$saveUrl."/".$fname);
			clearstatcache();

			// if(($updateTime+$time)<time()) {
				// 生成文件并显示				
				$createRs = $this->createCache($fname, $params,$template, $saveUrl);
			// }

		}

		if($createRs===false) {
			throw new \Exception("文件生成失败");			
		}

		// 显示视图
		require $_SERVER['DOCUMENT_ROOT'].$saveUrl."/".$fname;

	}

	/**
	* 生成缓存文件
	* @author 	xww
	* @param 	string 		$name 			文件名
	* @param 	array 		$data 			数据数组
	* @param 	string 		$templdate 		模板(存储于模板文件夹中)
	* @param 	string 		$saveUrl        缓存文件存储地址(相对于根目录)
	*/
	public function createCache($name, $data,$template, $saveUrl)
	{
		
		ob_clean();

		// 重新生成的缓存文件目录
		$functionsObj = new \VirgoUtil\Functions;
		$functionsObj->mkDir($saveUrl);

		// if(!empty($data)){
		// 	foreach ($data as $key => $value) {
		// 		$$key = $value;
		// 	}
		// }

		require_once(dirname(__FILE__).'/../../templates/'.$template);

		$content = ob_get_clean();
		
		return file_put_contents($_SERVER['DOCUMENT_ROOT'].$saveUrl."/".$name, $content);

	}

	/**
	* 获取数据列表 用于资料库列表
	* @author 	xww
	* @param 	int/string    	$classId
	* @param 	int/string    	$skip
	* @param 	int/string    	$size
	* @param 	string    		$url
	* @param 	array 			$search   		[['name', 'like', "'%123%'"]] and条件
	* @param 	array 			$needs   		['id', 'title']
	* @param 	array    		$orderBy 		[['create_at'=>'desc']]
	* @param 	array    		$pageParams 	[['name'=>'123']]
	* @param 	Object
	*/
	public function getDatabaseLists($classId, $skip=null, $size=null, $url="", $search=null, $needs=null, $orders=null, $pageParams=null )
	{
		
		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query = $this->_model->where("class_id", $classId)
							  ->where("status", 0)
							  ->where("pass", 1)
							  ->select("updated_at");

		// 查询条件
		if( !is_null($search) && is_array($search) ) {
			for($j=0; $j<count($search); $j++) {
				$query = $query->where($search[$j][0], $search[$j][1], $search[$j][2]);
			}
		}

		// 数据总数
		$totalCount = $query->count();

		// 字段选择
		if( !is_null($needs) && is_array($needs) ) {
			for ($i=0; $i < count($needs); $i++) { 
				$query = $query->addSelect($needs[$i]);
			}
		}

		// 排序选择
		if( !is_null($orders) && is_array($orders) ) {
			foreach ($orders as $orderKey => $orderValue) {
				$query = $query->orderBy($orderValue[0], $orderValue[1]);
			}
		}

		if( !is_null($size) && is_numeric($size) ) {
			$query = $query->skip($skip) ->take($size);
		}

		if( !is_null($pageParams) && is_array($pageParams) ) {
			foreach ($pageParams as $pageParamIndex => $pageParamValue) {
				$temp = array();
				$temp[ $pageParamValue[0] ] = $pageParamValue[1];
				$pageObj->setPageQuery($temp);
			}
		}

		// 数据数
		$data = $query->get()->toArray();

		//设置页数跳转地址
		$pageObj->setUrl( $url );

		// 设置分页数据
		$pageObj->setData( $data );

		// 设置记录总数
		$pageObj->setTotalCount( $totalCount );

		// 设置分页大小
		$pageObj->setSize($size);

		// 进行分页并返回
		return $pageObj->doPage();


	}

	/**
	* 增加搜索次数
	* @author 	xww
	* @param 	string 		$search
	* @return 	bool
	*/
	public function createSearchCount($search, $classId)
	{

		DB::beginTransaction();

		$this->_model->where("title", 'like', "%".$search."%")->where("class_id", $classId)->lockForUpdate();

		$rs = $this->_model->where("title", 'like', "%".$search."%")->increment("search_count", 1);

		if($rs) {
			DB::commit();
			return true;
		} else {
			DB::rollback();
			return false;
		}

	}

	/**
	* 判断是否有特定id数据
	* @author 	xww
	* @param 	int/string 		$id
	* @return 	array
	*/
	public function read($id)
	{
		return $this->_model->where("status", 0)->find($id);
	}

	/**
	* 获取所有新闻
	* @author 	xww
	* @return 	array
	*/
	public function all()
	{
		return $this->_model->where("status", 0)->get()->toArray();
	}

	/**
	* 获取数据列表 用于资料库列表中的 类似课程体系的列表
	* @author 	xww
	* @param 	array    		$classIds
	* @param 	int/string    	$skip
	* @param 	int/string    	$size
	* @param 	string    		$url
	* @param 	array 			$search   		[['name', 'like', "'%123%'"]] and条件
	* @param 	array 			$needs   		['id', 'title']
	* @param 	array    		$orderBy 		[['create_at'=>'desc']]
	* @param 	array    		$pageParams 	[['name'=>'123']]
	* @param 	Object
	*/
	public function getDatabaseListsFromClasses($classIds, $skip=null, $size=null, $url="", $search=null, $needs=null, $orders=null, $pageParams=null )
	{
		
		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query = $this->_model->whereIn("class_id", $classIds)
							  ->where("status", 0)
							  ->where("pass", 1)
							  ->select("updated_at");

		// 查询条件
		if( !is_null($search) && is_array($search) ) {
			for($j=0; $j<count($search); $j++) {
				$query = $query->where($search[$j][0], $search[$j][1], $search[$j][2]);
			}
		}

		// 数据总数
		$totalCount = $query->count();

		// 字段选择
		if( !is_null($needs) && is_array($needs) ) {
			for ($i=0; $i < count($needs); $i++) { 
				$query = $query->addSelect($needs[$i]);
			}
		}

		// 排序选择
		if( !is_null($orders) && is_array($orders) ) {
			foreach ($orders as $orderKey => $orderValue) {
				$query = $query->orderBy($orderValue[0], $orderValue[1]);
			}
		}

		if( !is_null($size) && is_numeric($size) ) {
			$query = $query->skip($skip) ->take($size);
		}

		if( !is_null($pageParams) && is_array($pageParams) ) {
			foreach ($pageParams as $pageParamIndex => $pageParamValue) {
				$temp = array();
				$temp[ $pageParamValue[0] ] = $pageParamValue[1];
				$pageObj->setPageQuery($temp);
			}
		}

		// 数据数
		$data = $query->get()->toArray();

		//设置页数跳转地址
		$pageObj->setUrl( $url );

		// 设置分页数据
		$pageObj->setData( $data );

		// 设置记录总数
		$pageObj->setTotalCount( $totalCount );

		// 设置分页大小
		$pageObj->setSize($size);

		// 进行分页并返回
		return $pageObj->doPage();


	}
	
	/**
	* 获取列表对象
	* @author 	xww
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	object
	*/
	public function getListsObject($skip, $size)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query = $this->_model->leftJoin("news_classes", "news_classes.id", "=", "news.class_id")
					  		  ->where("news.status", 0)
					  		  ->select("news_classes.class_name", "news.id", "news.title", "news.cover");

		// 父菜单总记录数
		$totalCount = count( $query->get()->toArray() );

		// 获取记录
		$data = $query->skip($skip)->take($size)->get()->toArray();

		$url = "";

		//设置页数跳转地址
		$pageObj->setUrl( $url );

		// 设置分页数据
		$pageObj->setData($data);

		// 设置记录总数
		$pageObj->setTotalCount($totalCount);

		// 设置分页大小
		$pageObj->setSize($size);

		// 进行分页并返回
		return $pageObj->doPage();

	}

	/*添加记录*/
	public function create($data)
	{
		return $this->_model->insertGetId($data);
	}

	/**
	* 多数据更新
	* @author 	xww
	* @param 	array			$ids
	* @param 	array 			$data
	* @return 	affect rows
	*/
	public function multiplePartUpdate($ids, $data)
	{
		return $this->_model->whereIn("id", $ids)->update($data);
	}

	/**
	* 记录查询
	* @author 	xww
	* @param 	int/string 	$id
	* @return 	object 	
	*/
	public function readSingleTon($id)
	{
		return $this->_model->where("status", 0)->find($id);
	}

	/**
	* 数据更新
	* @author 	xww
	* @param 	int/string 		$id
	* @param 	array 			$data
	* @return 	affect rows
	*/
	public function partUpdate($id, $data)
	{
		return $this->_model->where("id", $id)->update($data);
	}
	
	/**
	* 根据分类名 获取最新文章详情
	* @author 	xww
	* @param 	string 		$className
	* @return 	array
	*/
	public function getLatestInfoByClassName($className)
	{
		
		return $this->_model->leftJoin("news_classes", "news_classes.id", "=", "news.class_id")
					  		  ->where("news.status", 0)
					  		  ->where("news_classes.status", 0)
					  		  ->where("news.pass", 1)
					  		  ->where("news_classes.class_name", $className)
					  		  ->select("news.id", "news.title", "news.cover", "updated_at", "content")
					  		  ->orderBy("news.created_at", "desc")
					  		  ->orderBy("news.id", "desc")
					  		  ->take(1)
					  		  ->get()
					  		  ->toArray();

	}

	/**
	* 根据分类名 获取文章列表
	* @author 	xww
	* @param 	string 		$className
	* @return 	array
	*/
	public function getListsByClassName($className, $skip=null, $size=null)
	{
		
		$query  = $this->_model->leftJoin("news_classes", "news_classes.id", "=", "news.class_id")
					  		  ->where("news.status", 0)
					  		  ->where("news_classes.status", 0)
					  		  ->where("news.pass", 1)
					  		  ->where("news_classes.class_name", $className)
					  		  ->select("news.id", "news.title", "news.description")
					  		  ->orderBy("news.created_at", "desc")
					  		  ->orderBy("news.id", "desc");

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}

		return $query->get()->toArray();

	}

}