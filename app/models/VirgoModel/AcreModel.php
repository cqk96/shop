<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class AcreModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\Acre; 
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
		$query = $this->_model->where("is_deleted", '=', 0)->orderBy("create_time", "desc");

		// 标题过滤
		if(!empty($_GET['title'])){
			$_GET['title'] = trim($_GET['title']);
			$query = $query->where("title", 'like', '%'.$_GET['title'].'%');
			$pageObj->setPageQuery(['title'=>$_GET['title']]);
		}
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
		$pageObj->setUrl('/admin/acres');
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
		// 上传文件
		if(!empty($_FILES['cover']) && $_FILES['cover']['error']==0){
			$ext = str_replace('image/', '', $_FILES['cover']['type']);
			$fpath = '/upload/product/'.microtime(true).".".$ext;
			$rs = move_uploaded_file($_FILES['cover']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$fpath);
			if($rs){
				$_POST['cover'] = $fpath;
			}
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
		// 上传文件
		if(!empty($_FILES['cover']) && $_FILES['cover']['error']==0){
			$ext = str_replace('image/', '', $_FILES['cover']['type']);
			$fpath = '/upload/product/'.microtime(true).".".$ext;
			$rs = move_uploaded_file($_FILES['cover']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$fpath);
			if($rs){
				$_POST['cover'] = $fpath;
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
	* 根据农场id获取相应地块
	* @author 	xww
	* @param 	int/string 		$id    	fram id
	* @return 	array
	*/
	public function getFarmAcres($id)
	{
		return $this->_model->leftJoin("users", "users.id", '=', "acre.manager_id")
							->where("users.is_deleted", 0)
							->where("acre.is_deleted", 0)
							->where("farm_id", $id)
							->select("acre.*", "users.name as managerName")
							->get()
							->toArray();
	}

	/**
	* 根据农场ids获取相应地块
	* @author 	xww
	* @param 	array 		$ids    	fram ids
	* @return 	array
	*/
	public function getFarmsAcres($ids)
	{
		return $this->_model->leftJoin("users", "users.id", '=', "acre.manager_id")
							->where("users.is_deleted", 0)
							->where("acre.is_deleted", 0)
							->whereIn("farm_id", $ids)
							->select("acre.*", "users.name as managerName")
							->get()
							->toArray();
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

		/*片区类型*/
		$areaModel = new \VirgoModel\AreaModel;

		$query = $this->_model->leftJoin("users", "users.id", "=", "acre.manager_id")
							  ->leftJoin("farm", "farm.id", "=", "acre.farm_id")
					  		  ->where("acre.is_deleted", 0)
					  		  ->where("farm.is_deleted", 0)
					  		  ->where("users.is_deleted", 0)
					  		  ->select("acre.id", "acre.name", "acre.acreage", DB::raw(" ifnull(`comp_users`.name, '')  as managerName"), DB::raw(" ifnull(`comp_users`.phone, '')  as phone"), "farm.name as farmName");

		// 父菜单总记录数
		$totalCount = count( $query->get()->toArray() );

		// 获取记录
		$data = $query->skip($skip)->take($size)->get()->toArray();

		/*要获取不同类型片区数量*/
		for ($i=0; $i < count($data); $i++) { 
			$fruitsAmount = $areaModel->getAcreTypeAreaCount( $data[$i]['id'], 1);
			$vagetableAmount = $areaModel->getAcreTypeAreaCount( $data[$i]['id'], 2);
			$data[$i]['areaTypeStr'] = "果树片区" . $fruitsAmount . "块;" . "蔬菜片区" . $vagetableAmount . "块";
		}

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
	* 获取指定农场 指定地块名数据
	* @author 	xww
	* @param 	int/string 		$farmId
	* @param 	string 			$name
	*/
	public function getFarmAcreWithName($farmId, $name)
	{
		return $this->_model->where("is_deleted", 0)->where("farm_id", $farmId)->where("name", $name)->get()->toArray();
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
	
	/**
	* 获取所有正常使用地块
	* @author 	xww
	* @return 	array
	*/	
	public function getAll()
	{
		return $this->_model->where("is_deleted", 0)->select("id", "name")->get()->toArray();
	}

	/**
	* 根据农场id和地块名 获取记录
	* @author 	xww
 	* @param 	int/string 		$farmId
 	* @param 	string 			$name
 	* @return 	array
	*/
	public function getAcreFromNameAndFarmId($farmId, $name)
	{
		return $this->_model->where("is_deleted", 0)->where("farm_id", $farmId)->where("name", $name)->get()->toArray();
	}

	/**
	* 创建农场
	* @author 	xww
 	* @param 	int/string 			$farmId
 	* @param 	string 				$name
 	* @param 	int/string 			$uid
 	* @return 	int
	*/
	public function createAcre($farmId, $name, $uid)
	{
		
		$insertData['name'] = $name;
		$insertData['farm_id'] = $farmId;
		$insertData['manager_id'] = $uid;
		$insertData['acreage'] = 10000;
		$insertData['area_amount'] = 0;
		$insertData['is_deleted'] = 0;
		$insertData['create_time'] = time();
		$insertData['update_time'] = time();

		return $this->_model->insertGetId( $insertData );

	}

	/**
	* 更新片区
	* @author 	xww
	* @param 	int/string 		$id
	* @param    int  			$amount
	* @return 	int
	*/
	public function updateAreaAmount($id, $amount)
	{
		$data['area_amount'] = $amount;
		$data['update_time'] = time();
		return $this->_model->where("id", $id)->update($data);
	}

	/**
	* 获取指定农场下我负责的地块列表
	* @author 	xww
	* @param 	int/string 		$uid
	* @return 	array    ids 
	*/
	public function getMyAcres( $uid, $farmId=null)
	{
		
		$query = $this->_model->where("is_deleted", 0)->where("manager_id", $uid);

		if( !is_null($farmId) ) {
			$query = $query->where("farm_id", $farmId);
		}

		return $query->get()->toArray();

	}

}
?>