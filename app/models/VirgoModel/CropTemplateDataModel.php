<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class CropTemplateDataModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\CropTemplateData; 
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
		$pageObj->setUrl('/admin/cropTemplateDatas');
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
	* 批量增加
	* @author 	xww
	* @param 	array 	$data
	* @return 	int
	*/
	public function multipleCreate($data)
	{
		return $this->_model->insert($data);
	}
	
	/**
	* 模板数据详情
	* @author 	xww
	* @param 	int/string 		$id
	* @return 	array
	*/
	public function getTemplateInfo($id)
	{
		return $this->_model->leftJoin("archive_template", "archive_template.id", "=", "crop_template_data.archive_template_id")
							->leftJoin("users", "users.id", '=', "crop_template_data.user_id")
							->leftJoin("crop", "crop.id", "=", "crop_template_data.crop_id")
							->where("archive_template.is_deleted", 0)
							->where("users.is_deleted", 0)
							->where("crop_template_data.is_deleted", 0)
							->where("crop.is_deleted", 0)
							->where("crop_template_data.id", $id)
							->select("crop.number as showName", "users.avatar", "users.name as userName", "archive_template.model_data", "crop_template_data.*")
							->first();
	}

	/**
	* 获取列表
	* @author 	xww
	* @param 	int/string 		$id    crop ’s id
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	array
	*/
	public function getLists($id, $skip=null, $size=null)
	{
		
		/*由于作物的档案中包含了部分的片区操作 所以作物的档案要包括片区档案*/

		/*获取片区消息*/
		$cropModel = new \VirgoModel\CropModel;
		$crop = $cropModel->readSingleTon($id);
		$areaId = empty($crop)? -1:$crop['area_id'];

		// 查询作物档案
		$sql = " (select `comp_archive_template`.name, `comp_crop_template_data`.id, 1 as type, `comp_crop_template_data`.create_time, ifnull( date_format(from_unixtime(`comp_crop_template_data`.create_time), '%Y-%m-%e'), '') as createTime
				from `comp_crop_template_data` 
				left join `comp_archive_template` 
				on `comp_crop_template_data`.archive_template_id=`comp_archive_template`.id 
				where `comp_crop_template_data`.is_deleted=0 
				and `comp_archive_template`.is_deleted=0
				and `comp_crop_template_data`.crop_id = " . $id . "
				) union ";
	
		// 查询片区档案
		$sql2 = " (select `comp_archive_template`.name, `comp_area_template_data`.id, 2 as type,  `comp_area_template_data`.create_time, ifnull( date_format(from_unixtime(`comp_area_template_data`.create_time), '%Y-%m-%e'), '') as createTime
				from `comp_area_template_data` 
				left join `comp_archive_template` 
				on `comp_area_template_data`.archive_template_id=`comp_archive_template`.id 
				where `comp_area_template_data`.is_deleted=0 
				and `comp_archive_template`.is_deleted=0
				and `comp_area_template_data`.area_id = " . $areaId . "
				) ";

		$query = $sql . $sql2;

		$query = " select * from (" . $query . ")temp order by create_time desc";

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query . " limit " . $skip . "," . $size;
		}

		$data = DB::select( $query );

		$data = array_map(array($this, "toArray"), $data);		
		
		return $data;

	}

	/*std obj to array*/
	public static function toArray($std)
	{
		return (array)$std;
	}

	/**
	* 是否有该模板数据
	* @author 	xww
	* @param 	int/string 		$tid
	* @return  	boolean
	*/
	public function hasUseTemplate($tid)
	{
		
		$count = $this->_model->where("is_deleted", 0)
					 ->where("archive_template_id", $tid)
					 ->count();

		return $count? true:false;
	}
	
}
?>