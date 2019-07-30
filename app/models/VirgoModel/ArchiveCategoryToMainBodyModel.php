<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
class ArchiveCategoryToMainBodyModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\ArchiveCategoryToMainBody; 
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
		$pageObj->setUrl('/admin/archiveCategoryToMainBodys');
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
	public function setArchiveMainBodyEmpty( $cid )
	{
		$data['update_time'] = time();
		$data['is_deleted'] = 1;
		return $this->_model->where("is_deleted", 0)->where("archive_template_category_id", $cid)->update($data);
	}

	/**
	* 获取所有主体--可通过传入分类id判定这个分类是否包含了这个主体
	* @author 	xww
	* @param 	int/string 		$cid   (category id )
	* @return 	array
	*/
	public function getArchiveMainBodyInAll($cid=null)
	{

		$lists = [];
		if( !is_null($cid) ) {
			$temp = $this->_model->where("is_deleted", 0)
							   ->where("archive_template_category_id", $cid)
							   ->get()
							   ->toArray();

			if( !empty($temp) ) {

				for ($i=0; $i < count($temp); $i++) { 
					$lists[] = $temp[$i]['main_body_type_id'];
				}

			}

		}

		$data = [
			['id'=>1, 'name'=>'农场'],
			['id'=>2, 'name'=>'地块'],
			['id'=>3, 'name'=>'片区'],
			['id'=>4, 'name'=>'作物'],
			['id'=>5, 'name'=>'蔬菜'],
			['id'=>6, 'name'=>'部门'],
			['id'=>7, 'name'=>'班组织']
		];

		for ($i=0; $i < count($data); $i++) { 
			$data[$i]['checked'] = false;

			if( in_array($data[$i]['id'], $lists) ) {
				$data[$i]['checked'] = true;				
			}

		}

		return $data;

	}

	/**
	* 获取主体对应的分类列表
	* @author 	xww
	* @param 	int/string 		$mainBodyId
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	array
	*/
	public function getMainbodyCategoryLists($mainBodyId, $skip=null, $size=null)
	{
		
		$query = $this->_model->leftJoin("archive_template_category", "archive_template_category.id", '=', 'archive_category_to_main_body.archive_template_category_id')
							  ->where("archive_template_category.is_deleted", 0)
							  ->where("archive_category_to_main_body.is_deleted", 0)
							  ->where("archive_category_to_main_body.main_body_type_id", $mainBodyId)
							  ->select("archive_template_category.id", "archive_template_category.name", 'archive_template_category.cover', 'archive_template_category.resume')
							  ->orderBy("archive_template_category.order_index", "asc")
							  ->orderBy("archive_template_category.id", "asc");

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}

		return $query->get()->toArray();

	}

}
?>