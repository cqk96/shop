<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class CropModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\Crop; 
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
		$pageObj->setUrl('/admin/crops');
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
	* 获取指定片区拥有的作物
	* @author 	xww
	* @param 	array 			$ids 		片区ids
	* @param 	int 			$skip
	* @param 	int 			$size
	* @return 	array
	*/
	public function getMultipleCropsByAreaId($ids, $skip=0, $size=0)
	{
		
		$query = $this->_model->whereIn("area_id", $ids)->where("is_deleted", 0);

		if( ( is_numeric($skip) && $skip!=0 ) && ( is_numeric($size) && $size!=0 ) ) {
			$query = $query->skip($skip)->take($size);
		}

		return $query->get()->toArray();

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
	* 大量增加作物
	* @author 	xww
	* @param 	int/string 		$areaId
	* @param 	int/string 		$amount
	* @return 	bool
	*/
	public function multipleCreateCrop($areaId, $acreNum, $areaNum, $amount)
	{
		
		try{

			$prefix = "D-";

			// 地块三位
			$acreNumStr = str_pad($acreNum, 3, 0, STR_PAD_LEFT);

			// 片区三位
			$areaNumStr = str_pad($areaNum, 3, 0, STR_PAD_LEFT);

			// 起始编号--默认1长度五位
			$startIndex = 1;

			// 获取此时最大的该片区作物编号
			$lastest = $this->_model->where("is_deleted", 0)->where("area_id", $areaId)->orderBy("number", "desc")->orderBy("id", "desc")->take(1)->get()->toArray();

			if( !empty($lastest) ) {
				$number = $lastest[0]['number'];
				if( !empty($number) ) {
					$numIndexStr = substr($number, 8);
					$numIndex = (int)$numIndexStr;
					$startIndex = ++$numIndex;
				}

			}

			$data = [];
			for ($i=0; $i <$amount; $i++) {

				$numberStr = $prefix . $acreNumStr . $areaNumStr . str_pad($startIndex, 5, 0, STR_PAD_LEFT);
				$temp['area_id'] = $areaId; 
				$temp['number'] = $numberStr;
				$temp['planting_time'] = 0;
				$temp['create_time'] = time();
				$temp['update_time'] = time();
				$data[] = $temp;
				++$startIndex;
			}

			DB::beginTransaction();

			$rs = $this->_model->insert( $data );

			if( !$rs ) {
				throw new \Exception("批量插入作物数据失败");
			}

			DB::commit();
			return true;
		} catch(\Exception $e) {
			DB::rollback();
			return false;
		}

	}

	/**
	* 获取地区作物列表 
	* @author 	xww
	* @param 	int/string 		$areaId
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	array
	*/
	public function getAreaCropLists($areaId, $skip=null, $size=null, $search=null)
	{
		
		$query = $this->_model->where("is_deleted", 0)->where("area_id", $areaId)->select("id", "number", 'status_id as statusId', 'planting_time as plantingTime');

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}

		if( !is_null($skip) ) {
			$query = $query->where("number", "like", '%' . $search . '%');
		}

		return $query->get()->toArray(); 
	}

	/**
	* 获取列表对象
	* @author 	xww
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	object
	*/
	public function getListsObject($skip, $size, $number=null, $statusId=null)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query = $this->_model
                  ->leftJoin("area", "area.id", '=', "crop.area_id")						
				  ->leftJoin("crop_type", "crop_type.id", '=', "area.crop_type_id")
                  ->where("area.is_deleted", 0)
                  ->where("crop.is_deleted", 0)
		  		  ->select("crop.id", "crop.number", "crop_type.name as cropTypeName", "area.name as areaName", "crop.status_id", "crop.planting_time");

		  		  // ->where("is_deleted", 0)->select("id", "number", "area_id", "status_id", "planting_time", "area_id");

		if( !is_null( $number ) ) {
			$query = $query->where("number", "like", "%" . $number . "%");
		}

		if( !is_null( $statusId ) ) {
			$query = $query->where("crop.status_id", "=", $statusId);
		}

		// 父菜单总记录数
		// $totalCount = $this->_model->count();
		$totalCount = $query->count();

		// 获取记录
		$data = $query->skip($skip)->take($size)->get()->toArray();

		// $dataArr = [];
		// $areaIds = [];
		// for ($i=0; $i < count($data); $i++) { 
		// 	// $dataArr[ $data[$i]['id'] ] = $data[$i];
		// 	$areaIds[] = $data[$i]['area_id'];
		// }

		// if( !empty($areaIds) ) {
		// 	$areasTemp = \EloquentModel\Area::leftJoin("crop_type", "crop_type.id", '=', "area.crop_type_id")
		// 					   ->where("area.is_deleted", 0)
		// 					   ->whereIn("area.id", $areaIds)
		// 					   ->select("area.id", "crop_type.name as cropTypeName", "area.name as areaName")
		// 					   ->get()
		// 					   ->toArray();

		// 	$areaArr = [];				   
		// 	for ($i=0; $i < count($areasTemp); $i++) { 
		// 		$areaArr[ $areasTemp[$i]['id'] ] = $areasTemp[$i];
		// 	}

			for ($i=0; $i < count($data); $i++) { 
				$data[$i]['statusStr'] = $data[$i]['status_id']==0? '正常':'虫害';
				$data[$i]['plantingTime'] = empty($data[$i]['planting_time'])? '':date("Y-m-d", $data[$i]['planting_time']);
				// $data[$i]['cropTypeName'] = !empty($areaArr) && !empty($areaArr[ $data[$i]['area_id'] ])? $areaArr[ $data[$i]['area_id'] ]['cropTypeName']:'';
				// $data[$i]['areaName'] = !empty($areaArr) && !empty($areaArr[ $data[$i]['area_id'] ])? $areaArr[ $data[$i]['area_id'] ]['areaName']:'';
				unset($data[$i]['planting_time']);
				// unset($data[$i]['area_id']);
			}

		// }
		
		return [$data, $totalCount];
		
		// $url = "";

		// //设置页数跳转地址
		// $pageObj->setUrl( $url );

		// // 设置分页数据
		// $pageObj->setData($data);

		// // 设置记录总数
		// $pageObj->setTotalCount($totalCount);

		// // 设置分页大小
		// $pageObj->setSize($size);

		// // 进行分页并返回
		// return $pageObj->doPage();

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

	public function getCropOpereateDataTime( $id )
	{

		/*获取片区消息*/
		$cropModel = new \VirgoModel\CropModel;
		$crop = $cropModel->readSingleTon($id);
		$areaId = empty($crop)? -1:$crop['area_id'];

		// 查询作物档案
		$sql = " (select ifnull( date_format(from_unixtime(`comp_crop_template_data`.create_time), '%Y-%m-%e'), '') as createTime
				from `comp_crop_template_data` 
				left join `comp_archive_template` 
				on `comp_crop_template_data`.archive_template_id=`comp_archive_template`.id 
				where `comp_crop_template_data`.is_deleted=0 
				and `comp_archive_template`.is_deleted=0
				and `comp_crop_template_data`.crop_id = " . $id . "
				) union ";
	
		// 查询片区档案
		$sql2 = " (select ifnull( date_format(from_unixtime(`comp_area_template_data`.create_time), '%Y-%m-%e'), '') as createTime
				from `comp_area_template_data` 
				left join `comp_archive_template` 
				on `comp_area_template_data`.archive_template_id=`comp_archive_template`.id 
				where `comp_area_template_data`.is_deleted=0 
				and `comp_archive_template`.is_deleted=0
				and `comp_area_template_data`.area_id = " . $areaId . "
				) ";

		$query = $sql . $sql2;

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

		/*获取片区消息*/
		$cropModel = new \VirgoModel\CropModel;
		$crop = $cropModel->readSingleTon($id);
		$areaId = empty($crop)? -1:$crop['area_id'];

		$beginTime = strtotime( $dateStr . " 00:00:00" );
		$endTime = strtotime( $dateStr . " 23:59:59" );

		// 查询作物档案
		$sql = " (select `comp_users`.name as userName, `comp_archive_template`.name, `comp_archive_template`.model_data, `comp_crop_template_data`.template_data
				from `comp_crop_template_data` 
				left join `comp_archive_template` 
				on `comp_crop_template_data`.archive_template_id=`comp_archive_template`.id 
				left join `comp_users` 
				on `comp_crop_template_data`.user_id=`comp_users`.id 
				where `comp_crop_template_data`.is_deleted=0 
				and `comp_archive_template`.is_deleted=0
				and `comp_crop_template_data`.create_time between " . $beginTime . " and " . $endTime . "
				and `comp_crop_template_data`.crop_id = " . $id . "
				) union ";
	
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
				and `comp_area_template_data`.area_id = " . $areaId . "
				) ";

		$query = $sql . $sql2;

		$query = " select * from (" . $query . ")temp";

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query . " limit " . $skip . "," . $size;
		}

		$data = DB::select( $query );

		$data = array_map(array($this, "toArray"), $data);		
		
		return $data;

	}

}
?>