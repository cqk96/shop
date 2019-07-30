<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class CurrencyManagementModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	private static $selfModel;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\CurrencyManagement;
	}

	/**
	* 列表
	* @author xww
	*@return object
	*/
	// public function lists()
	// {

	// 	// 分页对象
	// 	$pageObj = new \VirgoUtil\Page2;
	// 	// set query 
	// 	$query = $this->_model->where("is_deleted", '=', 0)->orderBy("create_time", "desc");

	// 	// 标题过滤
	// 	if(!empty($_GET['title'])){
	// 		$_GET['title'] = trim($_GET['title']);
	// 		$query = $query->where("title", 'like', '%'.$_GET['title'].'%');
	// 		$pageObj->setPageQuery(['title'=>$_GET['title']]);
	// 	}
	// 	// 开始时间过滤
	// 	if(!empty($_GET['startTime'])){
	// 		$_GET['startTime'] = trim($_GET['startTime']);
	// 		$query = $query->where("update_time", '>=', strtotime($_GET['startTime']." 00:00:00"));
	// 		$pageObj->setPageQuery(['startTime'=>$_GET['startTime']]); 
	// 	}
	// 	// 截止时间过滤
	// 	if(!empty($_GET['endTime'])){
	// 		$_GET['endTime'] = trim($_GET['endTime']);
	// 		$query = $query->where("update_time", '<=', strtotime($_GET['endTime']." 23:59:59"));
	// 		$pageObj->setPageQuery(['endTime'=>$_GET['endTime']]);
	// 	}
	// 	// 父菜单总记录数
	// 	$totalCount = count($query->get()->toArray());
	// 	//分页的take,size
	// 	$size = 10;
	// 	if(!empty($_GET['page'])){
	// 		$page = (int)$_GET['page'];
	// 		$skip = ($page-1)*$size;
	// 	} else {
	// 		$skip = 0;
	// 	}
	// 	// 获取记录
	// 	$data = $query->skip($skip)->take($size)->get()->toArray();
	// 	//设置页数跳转地址
	// 	$pageObj->setUrl('/admin/currencyManagements');
	// 	// 设置分页数据
	// 	$pageObj->setData($data);
	// 	// 设置记录总数
	// 	$pageObj->setTotalCount($totalCount);
	// 	// 设置分页大小
	// 	$pageObj->setSize($size);
	// 	// 进行分页并返回
	// 	return $pageObj->doPage();
	// }
	// /**
	// * 逻辑增加
	// * @author xww
	// * @return sql result
	// */
	// public function doCreate()
	// {
	// 	unset($_POST['id']);
	// 	unset($_POST['coverPath']);
	// 	unset($_POST['page']);
	// 	// 上传文件
	// 	if(!empty($_FILES['cover']) && $_FILES['cover']['error']==0){
	// 		$ext = str_replace('image/', '', $_FILES['cover']['type']);
	// 		$fpath = '/upload/product/'.microtime(true).".".$ext;
	// 		$rs = move_uploaded_file($_FILES['cover']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$fpath);
	// 		if($rs){
	// 			$_POST['cover'] = $fpath;
	// 		}
	// 	}
	// 	// 创建时间
	// 	$_POST['create_time'] = time();
	// 	// 修改时间
	// 	$_POST['update_time'] = time();
	// 	return $this->_model->insert($_POST);
	// }
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
	// public function doUpdate()
	// {
	// 	$id = $_POST['id'];
	// 	unset($_POST['id']);
	// 	unset($_POST['coverPath']);
	// 	unset($_POST['page']);
	// 	// 上传文件
	// 	if(!empty($_FILES['cover']) && $_FILES['cover']['error']==0){
	// 		$ext = str_replace('image/', '', $_FILES['cover']['type']);
	// 		$fpath = '/upload/product/'.microtime(true).".".$ext;
	// 		$rs = move_uploaded_file($_FILES['cover']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$fpath);
	// 		if($rs){
	// 			$_POST['cover'] = $fpath;
	// 		}
	// 	}
	// 	// 修改时间
	// 	$_POST['update_time'] = time();
	// 	// 更新
	// 	return $this->_model->where("id", '=', $id)->update($_POST);
	// }
	/**
	* 逻辑删除
	* @author xww
	* @return sql result
	*/
	// public function delete()
	// {
	// 	$data['is_deleted'] = 1;
	// 	if($_POST){$ids = $_POST['ids'];}
	// 	else{$ids = [$_GET['id']];}
	// 	return $this->_model->whereIn("id", $ids)->update($data);
	// }

	/**
	* 获取列表对象
	* @author 	xww
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	object
	*/
	public function getListsObject($params=[])
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query = $this->_model->select("id", "name", DB::raw(" ifnull(`comp_currency_management`.abbreviation, '') as abbreviation "), "front_symbol as frontSymbol", DB::raw(" ifnull(`comp_currency_management`.back_symbol, '') as backSymbol "),  "update_time as updateTime")
							  ->where("is_deleted", 0)
							  ->orderBy("id", "desc");

		if( !empty( $params['name'] ) ) {
			$query = $query->where("name", "like", "%" . $params['name'] . "%");
		}

		$totalCountQuery = $query;

		// 父菜单总记录数
		$totalCount = $totalCountQuery->count();

		if( !empty( $params['size'] ) ) {
			$query = $query->skip( $params['skip'] )->take( $params['size'] );	
		}

		// 获取记录
		$data = $query->get()->toArray();

		$url = "";
		if( !empty( $params['url'] ) ) {
			$url = $params['url'];
		}

		//设置页数跳转地址
		$pageObj->setUrl( $url );

		// 设置分页数据
		$pageObj->setData($data);

		// 设置记录总数
		$pageObj->setTotalCount($totalCount);

		// 设置分页大小
		$pageObj->setSize( $params['size'] );

		// 进行分页并返回
		return $pageObj->doPage();

	}

	/**
	* 是否有同名记录
	* @author 	xww
	* @return 	boolean
	*/
	public function hasSameName( $name)
	{
		
		$count = $this->_model->where("is_deleted", 0)->where("name", '=', $name)->count();
		return $count? true:false; 

	}

	/**
	* 创建记录
	* @author 	xww
	* @param 	array 		$data
	* @return 	int 		record id
	*/
	public function create($data)
	{
		return $this->_model->insertGetId( $data );
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
	* 根据名称查询数据
	* @author 	xww
	* @param 	string 		$name
	* @return 	array
	*/
	public function getRecordWithName($name)
	{
		return $this->_model->where("is_deleted", 0)->where("name", $name)->get()->toArray();
	}

	/**
	* 获取全部数据
	* @author 	xww
	* @return 	array
	*/
	public function getAllCurrency()
	{
		return $this->_model->select("id", "name")->where("is_deleted", 0)->orderBy("id", "desc")->get()->toArray();
	}

	/**
	* 根据币种id获取国家名称 
	* @author 	xww
	* @param 	int/string 		$countryId
	* @return 	string
	*/
	public static function getCurrencyNameWithId( $currencyId ) 
	{

		$data = \EloquentModel\CurrencyManagement::find($currencyId);

		return empty($data)? '未知':$data['name'];

	}

}
?>