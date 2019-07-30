<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class AreaModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\Area; 
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
		$pageObj->setUrl('/admin/areas');
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
	* 获取指定地块拥有的片区
	* @author 	xww
	* @param 	array 		$ids
	* @return 	array
	*/
	public function getMultipleAreasByAcreId( $ids )
	{
		return $this->_model->whereIn("acre_id", $ids)->where("is_deleted", 0)->get()->toArray();
	}

	/**
	* 获取列表对象
	* @author 	xww
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	object
	*/
	public function getListsObject($skip, $size, $nameSearch=null, $cropTypeNameSearch=null)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$managerModel = new \VirgoModel\AreaRelManagerModel;

		$query = $this->_model->leftJoin("acre", "acre.id", "=", "area.acre_id")
							  ->leftJoin("crop_type", "crop_type.id", "=", "area.crop_type_id")
					  		  ->where("acre.is_deleted", 0)
					  		  ->where("area.is_deleted", 0)
					  		  ->where("crop_type.is_deleted", 0)
					  		  ->select("area.id", "area.name", "area.acreage", "area.status_id as statusId" , "acre.name as acreName", "crop_type.name as cropTypeName", DB::raw(" ifnull(`comp_area`.remarks, '')  as remarks"));

		/*搜索片区名*/
		if( !is_null( $nameSearch ) ) {
			$query = $query->where("area.name", "like", "%" . $nameSearch . "%");
		}

		/*搜索作物种类名*/
		if( !is_null( $cropTypeNameSearch ) ) {
			$query = $query->where("crop_type.name", "like", "%" . $cropTypeNameSearch . "%");
		}

		// 父菜单总记录数
		$totalCount = count( $query->get()->toArray() );

		// 获取记录
		$data = $query->skip($skip)->take($size)->get()->toArray();

		for ($i=0; $i < count($data); $i++) { 

			$managers = $managerModel->getAreaManagersInfo( $data[$i]['id'] );

			$data[$i]['managersName'] = '';
			$data[$i]['managersPhone'] = '';
			if( !empty($managers) ) {
				$tempNames = [];
				$tempPhones = [];
				for ($j=0; $j < count($managers); $j++) { 
					$tempNames[] = empty($managers[$j]['name'])? '未知':$managers[$j]['name'];
					$tempPhones[] = empty($managers[$j]['phone'])? '未知':$managers[$j]['phone'];
				}
				$data[$i]['managersName'] = implode(";", $tempNames);
				$data[$i]['managersPhone'] = implode(";", $tempPhones);
			}

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

	/**
	* 获取指定地块 指定片区名数据
	* @author 	xww
	* @param 	int/string 		$farmId
	* @param 	string 			$name
	*/
	public function getAcreAreaWithName($acreId, $name)
	{
		return $this->_model->where("is_deleted", 0)->where("acre_id", $acreId)->where("name", $name)->get()->toArray();
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

	/**
	* 根据地块id和片区名 获取记录
	* @author 	xww
 	* @param 	int/string 		$acreId
 	* @param 	string 			$name
 	* @return 	array
	*/
	public function getAreaWithNameAndAcreId($acreId, $name)
	{
		return $this->_model->where("is_deleted", 0)->where("acre_id", $acreId)->where("name", $name)->get()->toArray();
	}

	/**
	* 创建地块
	* @author 	xww
	* @param 	int/string 		$acreId
	* @param 	string 			$name
	* @param 	array 			$uids
	* @return 	int or bool
	*/
	public function createArea($acreId, $name, $crop_type_id, $crop_amount, $type_id=1, $uids=[])
	{
		
		try{

			DB::beginTransaction();

			// 片区管理员关联对象
			$relModel = new \VirgoModel\AreaRelManagerModel;

			$insertData['name'] = $name;
			$insertData['acre_id'] = $acreId;
			$insertData['type_id'] = $type_id;
			$insertData['crop_type_id'] = $crop_type_id;
			// $insertData['manager_id'] = $manager_id;
			$insertData['status_id'] = 0;
			$insertData['acreage'] = 10000;
			$insertData['crop_amount'] = $crop_amount;
			$insertData['is_deleted'] = 0;
			$insertData['create_time'] = time();
			$insertData['update_time'] = time();

			$recordId = $this->_model->insertGetId( $insertData );

			if( !$recordId ) {
				throw new \Exception("插入片区失败");
			}

			if( !empty($uids) && is_array($uids) ) {

				$relData = [];
				for ($i=0; $i < count($uids); $i++) { 
					
					$temp['user_id'] = (int)$uids[$i];
					$temp['area_id'] = $recordId;
					$temp['create_time'] = time();
					$temp['update_time'] = time();

					$relData[] = $temp;
					unset($temp);
				}

				$rs = $relModel->multipleCreate( $relData );

				if( !$rs ) {
					throw new \Exception("添加片区管理人员失败", '005');
				}

			}

			DB::commit();
			return $recordId;

		} catch(\Exception $e) {
			DB::rollback();
			return false;
		}

	}

	/**
	* 增加作物数量
	* @author 	xww
	* @param 	int/string 		$id
	* @param 	int/string 		$amount
	* @return 	int
	*/
	public function increCropAmount($id, $amount)
	{
		return $this->_model->where("id", $id)->increment("crop_amount", $amount);
	}

	/**
	* 更新管理人员
	* @author 	xww
	* @param 	int/string 		$areaId
	* @param 	array 			$uids
	* @return 	bool
	*/
	public function updateManagers($areaId, $uids)
	{
		
		try{

			DB::beginTransaction();

			// 片区管理员关联对象
			$relModel = new \VirgoModel\AreaRelManagerModel;

			$relData = [];
			for ($i=0; $i < count($uids); $i++) { 

				$uid = $uids[$i];
				
				// 判断是否有该关联关系
				$rs = \EloquentModel\AreaRelManager::where("is_deleted", 0)->where("area_id", $areaId)->where("user_id", $uid)->take(1)->get()->toArray();
				if( empty($rs) ) {
					$temp['user_id'] = $uid;
					$temp['area_id'] = $areaId;
					$temp['create_time'] = time();
					$temp['update_time'] = time();

					$relData[] = $temp;
					unset($temp);
				}

			}

			if( !empty($relData) ) {

				$rs = $relModel->multipleCreate( $relData );

				if( !$rs ) {
					throw new \Exception("添加片区管理人员失败", '005');
				}

			}

			DB::commit();
			return true;

		} catch(\Exception $e) {

			DB::rollback();
			return false;

		}

	}

	/**
	* 获取指定地块和类别的片区列表
	* @author 	xww
	* @param 	int/string 		$typeId
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @param 	string 			$search
	* @return 	array
	*/
	public function getAreaListsWithTypeAndAcreId($acreId, $typeId, $skip=null, $size=null, $uid=null, $search=null)
	{
		
		$query = $this->_model->leftJoin("area_rel_manager",function($q){
						$q->on("area_rel_manager.area_id", "=", "area.id")
						  ->where("area_rel_manager.is_deleted", "=", 0);

					 })
					 ->leftJoin("users",function($q){
						$q->on("area_rel_manager.user_id", "=", "users.id")
						  ->where("users.is_deleted", "=", 0);

					 })
					 ->leftJoin("crop_type",function($q){
						$q->on("crop_type.id", "=", "area.crop_type_id")
						  ->where("crop_type.is_deleted", "=", 0);

					 })
					 ->where("area.is_deleted", 0)
					 ->where("area.acre_id", $acreId);

		if( !is_null($search) ) {
			$query = $query->where("area.name", "like", "%" . $search . "%");
		}
		
		if( !is_null($uid) ) {
			$query = $query->where("area_rel_manager.user_id", "=", $uid);
		}

		if( $typeId==1 ) {
			$query = $query->where("area.type_id", $typeId)
						   ->select("area.id", "area.name", "area.acreage", DB::raw(" IFNULL(GROUP_CONCAT( `comp_users`.name SEPARATOR ',' ), '') as managerName "), "area.crop_amount as cropAmount", "area.expected_maturity as expectedMaturity");
		} else {
			$query = $query->where("area.type_id", $typeId)
						   ->select("area.id", "area.name", "area.acreage", DB::raw(" IFNULL(GROUP_CONCAT( `comp_users`.name SEPARATOR ',' ), '') as managerName "), "crop_type.name as cropTypeName", "area.status_id as statusId");
		}

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}

		return $query->groupBy("area.id")->get()->toArray();
				
	}

	/**
	* 单条记录详情 
	* @author 	xww
	* @param 	int/string 		$id
	* @return 	array or null
	*/
	public function singleTonDetail($id)
	{
		$data = $this->_model->leftJoin("crop_type", "crop_type.id", "=", "area.crop_type_id")
 					 ->where("area.is_deleted", 0)
 					 ->where("crop_type.is_deleted", 0)
 					 ->where("area.id", $id)
 					 ->select("area.*", "crop_type.name as cropTypeName")
 					 ->get()
 					 ->toArray();

		return empty($data)? null:$data[0];
	}

	/**
	* 获取地块的不同类型的片区数量
	* @author 	xww
	* @param 	int/string 		$acreId
	* @param 	int/string 		$typeId
	* @return 	int
	*/
	public function getAcreTypeAreaCount($acreId, $typeId)
	{
		return $this->_model->where("area.is_deleted", 0)
 					 ->where("type_id", $typeId)
 					 ->where("acre_id", $acreId)
 					 ->count();
	}

	public function getCropOpereateDataTime( $id )
	{
	
		// 查询片区档案
		$sql2 = " (select ifnull( date_format(from_unixtime(`comp_area_template_data`.create_time), '%Y-%m-%e'), '') as createTime
				from `comp_area_template_data` 
				left join `comp_archive_template` 
				on `comp_area_template_data`.archive_template_id=`comp_archive_template`.id 
				where `comp_area_template_data`.is_deleted=0 
				and `comp_archive_template`.is_deleted=0
				and `comp_area_template_data`.area_id = " . $id . "
				) ";

		$query = $sql2;

		$query = " select createTime from (" . $query . ")temp group by createTime";

		$data = DB::select( $query );

		$data = array_map(array($this, "toArray"), $data);		
		
		return $data;

	}	

	/*std obj to array*/
	public static function toArray($std)
	{
		return (array)$std;
	}

	public function getCropOperateDateTimeTemplates($id, $dateStr)
	{

		/*由于作物的档案中包含了部分的片区操作 所以作物的档案要包括片区档案*/

		$beginTime = strtotime( $dateStr . " 00:00:00" );
		$endTime = strtotime( $dateStr . " 23:59:59" );
	
		// 查询片区档案
		$sql2 = " (select `comp_users`.name as userName, `comp_archive_template`.name, `comp_archive_template`.model_data, `comp_area_template_data`.template_data
				from `comp_area_template_data` 
				left join `comp_archive_template` 
				on `comp_area_template_data`.archive_template_id=`comp_archive_template`.id
				left join `comp_users` 
				on `comp_area_template_data`.user_id=`comp_users`.id  
				where `comp_area_template_data`.is_deleted=0 
				and `comp_archive_template`.is_deleted=0
				and `comp_area_template_data`.create_time between " . $beginTime . " and " . $endTime . "
				and `comp_area_template_data`.area_id = " . $id . "
				) ";

		$query = $sql2;

		$query = " select * from (" . $query . ")temp";

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query . " limit " . $skip . "," . $size;
		}

		$data = DB::select( $query );

		$data = array_map(array($this, "toArray"), $data);		
		
		return $data;

	}

	/**
	* 获取指定地块和类别的片区列表--可以精确到负责人
	* @author 	xww
	* @param 	int/string 		$typeId
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @param 	string 			$search
	* @return 	array
	*/
	public function getAreaManagerListsWithTypeAndAcreId($acreId, $typeId, $skip=null, $size=null, $uid=null, $search=null)
	{
		
		$query = $this->_model
		             ->leftJoin("acre", "area.acre_id", "=", "acre.id")
		             ->leftJoin("area_rel_manager",function($q){
						$q->on("area_rel_manager.area_id", "=", "area.id")
						  ->where("area_rel_manager.is_deleted", "=", 0);

					 })
					 ->leftJoin("users",function($q){
						$q->on("area_rel_manager.user_id", "=", "users.id")
						  ->where("users.is_deleted", "=", 0);

					 })
					 ->leftJoin("crop_type",function($q){
						$q->on("crop_type.id", "=", "area.crop_type_id")
						  ->where("crop_type.is_deleted", "=", 0);

					 })
					 ->where("area.is_deleted", 0)
					 ->where("area.acre_id", $acreId);

		if( !is_null($search) ) {
			$query = $query->where("area.name", "like", "%" . $search . "%");
		}
		
		if( !is_null($uid) ) {
			$query = $query->where("acre.manager_id", "=", $uid);
		}

		if( $typeId==1 ) {
			$query = $query->where("area.type_id", $typeId)
						   ->select("area.id", "area.name", "area.acreage", DB::raw(" IFNULL(GROUP_CONCAT( `comp_users`.name SEPARATOR ',' ), '') as managerName "), "area.crop_amount as cropAmount", "area.expected_maturity as expectedMaturity");
		} else {
			$query = $query->where("area.type_id", $typeId)
						   ->select("area.id", "area.name", "area.acreage", DB::raw(" IFNULL(GROUP_CONCAT( `comp_users`.name SEPARATOR ',' ), '') as managerName "), "crop_type.name as cropTypeName", "area.status_id as statusId");
		}

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}

		return $query->groupBy("area.id")->get()->toArray();
				
	}

}
?>