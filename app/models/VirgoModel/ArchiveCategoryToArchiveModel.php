<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
class ArchiveCategoryToArchiveModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\ArchiveCategoryToArchive; 
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
		$pageObj->setUrl('/admin/archiveCategoryToArchives');
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
	* 添加多记录
	* @author 	xww
	* @param 	array 		$data
	* @return 	int 
	*/
	public function multipleCreate($data)
	{
		return $this->_model->insert($data);
	}

	/**
	* 清空下属模板
	* @author 	xww
	* @param 	int/string 		$cid  categpry's id
	* @return 	int 	affect rows
	*/
	public function setArchiveTemplateEmpty( $cid )
	{
		$data['update_time'] = time();
		$data['is_deleted'] = 1;
		return $this->_model->where("is_deleted", 0)->where("archive_template_category_id", $cid)->update($data);
	}

	/**
	* 获取指定分类的模板列表
	* @todo
	* @author 	xww
	* @param 	int/string 		$cid
	* @return 	array
	*/
	public function getCategoryArchiveTemplateLists($cid, $skip=null, $size=null)
	{
		
		$query = $this->_model->leftJoin("archive_template", "archive_category_to_archive.archive_template_id", '=', 'archive_template.id')
							  ->where("archive_category_to_archive.is_deleted", 0)
							  ->where("archive_template.is_deleted", 0)
							  ->where("archive_template.status_id", 1)
							  ->where("archive_category_to_archive.archive_template_category_id", $cid)
							  ->orderBy("archive_template.create_time", "desc")
							  ->orderBy("archive_template.id", "desc")
							  ->select("archive_template.name", "archive_template.id");

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}

		return $query->get()->toArray();

	}

	/**
	* 获取模板拥有的分类 
	* @author 	xww
	* @param 	int/string 	$tid
	* @return 	array
	*/
	public function getTemplateClasses($tid)
	{
		return $this->_model->where("is_deleted", 0)
							->where("archive_template_id", $tid)
							->select("archive_template_category_id as cid")
							->get()
							->toArray();
	}

	/**
	* 清空模板对应分类
	* @author 	xww
	* @param 	int/string 		$tid  template's id
	* @return 	int 	affect rows
	*/
	public function hardDeleteTemplateCatoryWithTid( $tid )
	{
		// $data['update_time'] = time();
		// $data['is_deleted'] = 1;
		return $this->_model->where("is_deleted", 0)->where("archive_template_id", $tid)->delete();
	}

}
?>