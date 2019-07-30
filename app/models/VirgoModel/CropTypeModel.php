<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class CropTypeModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\CropType; 
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
		$pageObj->setUrl('/admin/cropTypes');
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
	* 获取列表对象
	* @author 	xww
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	object
	*/
	public function getListsObject($skip, $size, $name=null)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query = $this->_model->where("is_deleted", 0)
					  		  ->select("id", "name",  "update_time as updateTime");

		if( !is_null($name) ) {
			$query = $query->where("name", "like", "%" . $name . "%");
		}

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
	* 获取全部作物种类
	* @author 	xww
	* @return   array
	*/
	public function getAll()
	{
		return $this->_model->where("is_deleted", '=', 0)->select("id", "name")->get()->toArray();
	}

	/**
	* 根据作物种类名称获取记录
	* @author 	xww
	* @param 	string 		$name
	* @return 	array
	*/
	public function getRecordWithName($name)
	{
		return $this->_model->where("is_deleted", '=', 0)->where("name", "=", $name)->get()->toArray();
	}

	/*添加记录*/
	public function create($data)
	{
		return $this->_model->insertGetId($data);
	}

	/**
	* 通过ids获取作物种类数组 
	* @author 	xww
	* @param 	array 	$ids
	* @return 	array
	*/
	public function getCropTypeArray($ids)
	{
		return $this->_model->where("is_deleted", '=', 0)->whereIn("id", $ids)->get()->toArray();
	}

	/**
	* 多数据更新
	* @author 	xww
	* @param 	array 		$ids
	* @param 	array 		$data
	* @return 	int
	*/
	public function multipartUpdate($ids, $data)
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
	* @return 	int
	*/
	public function partUpdate($id, $data)
	{

		return $this->_model->where("id", $id)->update($data);

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
	* 创建品种
	* @author 	xww
	* @param 	string 		$name
	* @return 	int
	*/
	public function createCropType($name)
	{

		$data['name'] = $name;
		$data['create_time'] = time();
		$data['update_time'] = time();
		return $this->_model->insertGetId($data);

	}

	/**
	* 查看作物分类详情--包含种类用于的所在地块以及数量
	* @author 	xww
	* @param 	int/string 		$id
	* @return 	array
	*/
	public function readSingleDetail( $id )
	{
		
		$dataObj = $this->_model->where("is_deleted", 0)->find($id);

		if( empty($dataObj) ) {
			return null;
		} else {

			$data = $dataObj->toArray();

			$acreDetailArr = [];

			// 如果是作物种类--连接统计
			// $area_1 = \EloquentModel\Area::leftJoin("acre", "acre.id", "=", "area.acre_id")
			// 				   ->leftJoin("crop", "crop.area_id", "=", "area.id")
			// 				   ->where("area.is_deleted", 0)
			// 				   ->where("crop.is_deleted", 0)
			// 				   ->where("area.type_id", 1)
			// 				   ->where("area.crop_type_id", $id)
			// 				   ->select("acre.id", "acre.name", DB::raw(" count(*) as totalCount ") )
			// 				   ->groupBy("acre.id", "acre.name")
			// 				   ->get()
			// 				   ->toArray();

			// $area_1_Ids = [];
			// for ($i=0; $i < count($area_1); $i++) { 

			// 	$acreDetailArr[] = $area_1[$i];
			// 	$area_1_Ids[] = $area_1[$i]['id'];

			// }

			// 如果是作物种类--循环统计
			$area_1 = \EloquentModel\Area::leftJoin("acre", "acre.id", "=", "area.acre_id")
							   ->where("area.is_deleted", 0)
							   ->where("area.type_id", 1)
							   ->where("area.crop_type_id", $id)
							   ->select("acre.id", "acre.name", DB::raw(" ifnull(group_concat(`comp_area`.id separator ','), '') as areaIds ") )
							   ->groupBy("acre.id", "acre.name")
							   ->get()
							   ->toArray();
			   
			$area_1_Ids = [];
			for ($i=0; $i < count($area_1); $i++) { 

				$count = 0;
				if( $area_1[$i]['areaIds']!="") {
					$count = \EloquentModel\Crop::where("crop.is_deleted", 0)
									->whereIn("crop.area_id", explode(",", $area_1[$i]['areaIds']) )
									->count();
				}

				unset( $area_1[$i]['areaIds'] );
				$area_1[$i]['totalCount'] = $count;
				$acreDetailArr[] = $area_1[$i];
				$area_1_Ids[] = $area_1[$i]['id'];

			}


			// 如果是蔬菜种类
			$area_2 = \EloquentModel\Area::leftJoin("acre", "acre.id", "=", "area.acre_id")
							   ->where("area.type_id", 2)
							   ->where("area.crop_type_id", $id)
							   ->select("acre.id", "acre.name", DB::raw(" sum(crop_amount) as totalCount ") )
							   ->groupBy("acre.id", "acre.name")
							   ->get()
							   ->toArray();

			for ($i=0; $i < count($area_2); $i++) { 

				if( !empty($area_1_Ids) ) {
					$poi = array_keys($area_1_Ids, $area_2[$i]['id']);

					if( !empty($poi) ) {
						$acreDetailArr[ $poi[0] ]['totalCount'] = $acreDetailArr[ $poi[0] ]['totalCount'] + $area_2[$i]['totalCount'];
					}
				} else {
					$acreDetailArr[] = $area_2[$i];
				}
				
			}

			$data['acreDetailArr'] = $acreDetailArr;

			return $data;

		}

	}
	
}
?>