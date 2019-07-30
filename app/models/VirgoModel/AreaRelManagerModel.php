<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
class AreaRelManagerModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\AreaRelManager; 
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
		$pageObj->setUrl('/admin/areaRelManagers');
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

	/*添加多条记录*/
	public function multipleCreate($data)
	{
		return $this->_model->insert($data);
	}

	/**
	* 获取对应片区的管理人员列表
	* @author 	xww	
	* @param  	int/string 		$areaId
	* @return 	array
	*/
	public function getAreaManagers($areaId)
	{

		$data = $this->_model->where("is_deleted", 0)->where("area_id", $areaId)->select("user_id")->get()->toArray();

		$ids = [];

		for ($i=0; $i < count($data); $i++) { 
			$ids[] = $data[$i]['user_id'];
		}

		return $ids;
	}

	/**
	* 软删指定地块管理人员
	* @author 	xww
	* @param 	int/string 		$areaId
	* @return 	int
	*/
	public function softDeleteAreaManagers($areaId)
	{
		$data['update_time'] = time();
		$data['is_deleted'] = 1;
		return $this->_model->where("area_id", $areaId)->update($data);
	}

	/**
	* 获取用户工作过的地块
	* @param 	int/string 		$uid
	* @author  	xww 
	* @return 	array
	*/
	public function getUserWorksAcre( $uid )
	{
		
		return  $this->_model->leftJoin("area", function($q){
						$q->on("area.id", "=", "area_rel_manager.area_id")
						  ->where("area_rel_manager.is_deleted", '=', 0);
					})
					->leftJoin("acre", "acre.id", "=", "area.acre_id")
					->where("acre.is_deleted", '=', 0)
					->where("area_rel_manager.is_deleted", '=', 0)
					->where("area_rel_manager.user_id", $uid)
					->select("acre.id", "acre.name")
					->groupBy("acre.id", "acre.name")
					->get()
					->toArray();
		
	}

	/**
	* 获取片区管理人员信息
	* @author 	xww	
	* @param  	int/string 		$areaId
	* @return 	array
	*/
	public function getAreaManagersInfo($areaId)
	{

		return $this->_model->leftJoin("users", "users.id", "=", "area_rel_manager.user_id")
							 ->where("users.is_deleted", 0)		
							 ->where("area_rel_manager.is_deleted", 0)
							 ->where("area_rel_manager.area_id", $areaId)
							 ->get()
							 ->toArray();

	}

}
?>