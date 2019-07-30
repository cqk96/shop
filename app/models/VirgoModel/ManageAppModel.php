<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
class ManageAppModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\ManageApp; 
	}

	/**
	* 列表
	* @author xww
	*@return object
	*/
	public function lists()
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;
		// set query 
		$query = $this->_model->where("is_deleted", '=', 0)->orderBy("version_code", "desc")->orderBy("create_time", "desc");

		// 版本号过滤
		if(!empty($_GET['version_code'])){
			$_GET['version_code'] = trim($_GET['version_code']);
			$query = $query->where("version_code", '=', $_GET['version_code']);
			$pageObj->setPageQuery(['version_code'=>$_GET['version_code']]);
		}

		// // 标题过滤
		// if(!empty($_GET['title'])){
		// 	$_GET['title'] = trim($_GET['title']);
		// 	$query = $query->where("title", 'like', '%'.$_GET['title'].'%');
		// 	$pageObj->setPageQuery(['title'=>$_GET['title']]);
		// }
		// 开始时间过滤
		if(!empty($_GET['startTime'])){
			$_GET['startTime'] = trim($_GET['startTime']);
			$query = $query->where("update_time", '>=', strtotime($_GET['startTime']." 00:00:00"));
			$pageObj->setPageQuery(['startTime'=>$_GET['startTime']]); 
		}
		// 截止时间过滤
		if(!empty($_GET['endTime'])){
			$_GET['endTime'] = trim($_GET['endTime']);
			$query = $query->where("update_time", '<=', strtotime($_GET['endTime']." 23:59:59"));
			$pageObj->setPageQuery(['endTime'=>$_GET['endTime']]);
		}
		// 父菜单总记录数
		$totalCount = count($query->get()->toArray());
		//分页的take,size
		$size = 10;
		if(!empty($_GET['page'])){
			$page = (int)$_GET['page'];
			$skip = ($page-1)*$size;
		} else {
			$skip = 0;
		}
		// 获取记录
		$data = $query->skip($skip)->take($size)->get()->toArray();
		//设置页数跳转地址
		$pageObj->setUrl('/admin/manageApps');
		// 设置分页数据
		$pageObj->setData($data);
		// 设置记录总数
		$pageObj->setTotalCount($totalCount);
		// 设置分页大小
		$pageObj->setSize($size);
		// 进行分页并返回
		return $pageObj->doPage();
	}
	/**
	* 逻辑增加
	* @author xww
	* @return sql result
	*/
	public function doCreate()
	{
		unset($_POST['id']);
		unset($_POST['coverPath']);
		unset($_POST['page']);

		$apkDir = "/apks";

		$uploadApkPath = "/upload" . $apkDir; 

		// 新建文件夹
		if( !is_dir( $_SERVER['DOCUMENT_ROOT'].$uploadApkPath ) ) {
			$functionsObj = new \VirgoUtil\Functions;
			$functionsObj->mkDir($uploadApkPath);
		}

		// 上传文件
		if(!empty($_FILES['file']) && $_FILES['file']['error']==0 && stripos($_FILES['file']['name'], ".apk") ){
			$fpath = $uploadApkPath.'/'.microtime(true).".apk";
			$rs = move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$fpath);
			if($rs){
				$_POST['apk_url'] = $fpath;
			}
		} else {
			$_POST['apk_url'] = "";
		}

		// 创建时间
		$_POST['create_time'] = time();
		// 修改时间
		$_POST['update_time'] = time();
		return $this->_model->insert($_POST);
	}
	/**
	* 返回对应id数据
	* @param  $id  string/int    会话id
	* @author xww
	* @return object
	*/
	public function read($id)
	{
		return $this->_model->where("is_deleted", '=', 0)->find($id);
	}
	/**
	* 逻辑修改
	* @author xww
	* @return sql result
	*/
	public function doUpdate()
	{
		$id = $_POST['id'];
		unset($_POST['id']);
		unset($_POST['coverPath']);
		unset($_POST['page']);
		
		$apkDir = "/apks";

		$uploadApkPath = "/upload" . $apkDir; 

		// 新建文件夹
		if( !is_dir( $_SERVER['DOCUMENT_ROOT'].$uploadApkPath ) ) {
			$functionsObj = new \VirgoUtil\Functions;
			$functionsObj->mkDir($uploadApkPath);
		}

		// 上传文件
		if(!empty($_FILES['file']) && $_FILES['file']['error']==0 && stripos($_FILES['file']['name'], ".apk") ){
			$fpath = $uploadApkPath.'/'.microtime(true).".apk";
			$rs = move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$fpath);
			if($rs){
				$_POST['apk_url'] = $fpath;
			}
		}

		// 修改时间
		$_POST['update_time'] = time();
		// 更新
		return $this->_model->where("id", '=', $id)->update($_POST);
	}
	/**
	* 逻辑删除
	* @author xww
	* @return sql result
	*/
	public function delete()
	{
		$data['is_deleted'] = 1;
		if($_POST){$ids = $_POST['ids'];}
		else{$ids = [$_GET['id']];}
		return $this->_model->whereIn("id", $ids)->update($data);
	}

	/**
	* 获取此时能用的最大版本号
	* @author 	xww
	* @return 	int
	*/
	public function getMaxVersion()
	{
		return $this->_model->where("is_deleted", 0)->max("version_code");
	}

	/**
	* 获取最新安装包信息
	* @author 	xww
	* @return 	array
	*/
	public function getLastestInfo()
	{
		return $this->_model->where("is_deleted", 0)
							->orderBy("version_code", "desc")
							->orderBy("create_time", "desc")
							->orderBy("id", "desc")
							->select("version_code as versionCode", "version_text as versionText", "description", "apk_url as url")
							->first();
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

		$query = $this->_model->where("is_deleted", 0)
							 ->orderBy("name", "desc")
							->orderBy("version_code", "desc")
							->orderBy("create_time", "desc")
							->orderBy("id", "desc")
							->select("id", "name", "version_code as versionCode", "apk_url as url", "update_time as updateTime");

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

	/**
	* 获取指定名称 指定开发版本号应用记录
	* @author 	xww
	* @param 	string 			$name
	* @param 	int/string 		$version_code
	* @return 	array
	*/
	public function getRecordWithNameVersionCode($name, $version_code)
	{
		return $this->_model->where("is_deleted", 0)->where("name", $name)->where("version_code", $version_code)->get()->toArray();
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
		return $this->_model->where("is_deleted", 0)->find($id);
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
		
}
?>