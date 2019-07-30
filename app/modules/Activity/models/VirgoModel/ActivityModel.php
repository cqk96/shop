<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace Module\Activity\VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
use VirgoModel;
class ActivityModel extends VirgoModel\BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \Module\Activity\EloquentModel\Activity; 
	}

	/**
	* 获取记录前台分页活动列表
	* @author xww
	* @return array
	*/ 
	public function getActivityList($skip, $size)
	{
		
		$query = $this->_model->where("is_deleted", '=', 0)
							  ->where("is_hidden", '=', 0)
							  ->orderBy("create_time", 'desc')
							  ->orderBy("id", 'desc')
							  ->select("id", "cover", 'title', 'description', 'total_people_count', 'apply_people_count', "start_time", "end_time")
							  ->skip($skip)
							  ->take($size);

		return  $query->get() ->toArray();
	}

	/**
	* 获取记录前台分页活动列表--数量
	* @author xww
	* @return int
	*/ 
	public function getActivityListCount()
	{
		
		return $this->_model->where("is_deleted", '=', 0)
							  ->where("is_hidden", '=', 0)
							  ->count();
							  
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
		$query = $this->_model->where("is_deleted", '=', 0)->orderBy("create_time", 'desc');

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
		$pageObj->setUrl('/admin/activitys');
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

		// 金额转化为分
		if(!empty($_POST['price'])){
			$_POST['price'] = $_POST['price']*100;
		}

		// 活动开始时间
		if(!empty($_POST['start_time'])){
			$_POST['start_time'] = strtotime($_POST['start_time']." 00:00:00");
		}

		// 活动结束时间
		if(!empty($_POST['end_time'])){
			$_POST['end_time'] = strtotime($_POST['end_time']." 23:59:59");
		}

		// 报名开始时间
		if(!empty($_POST['apply_start_time'])){
			$_POST['apply_start_time'] = strtotime($_POST['apply_start_time']." 00:00:00");
		}

		// 报名结束时间
		if(!empty($_POST['apply_end_time'])){
			$_POST['apply_end_time'] = strtotime($_POST['apply_end_time']." 23:59:59");
		}

		// 签到开始时间
		if(!empty($_POST['sign_in_start_time'])){
			$_POST['sign_in_start_time'] = strtotime($_POST['sign_in_start_time']);
		}

		// 签到结束时间
		if(!empty($_POST['sign_in_end_time'])){
			$_POST['sign_in_end_time'] = strtotime($_POST['sign_in_end_time']);
		}

		// 创建时间
		$_POST['create_time'] = time();
		// 修改时间
		$_POST['update_time'] = time();

		// 可报名人数
		// $_POST['apply_people_count'] = $_POST['total_people_count'];

		$recordId = $this->_model->insertGetId($_POST);

		return $recordId;

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
	* 返回对应id数据--前端数据
	* @param  $id  string/int    会话id
	* @author xww
	* @return object
	*/
	public function frontRead($id)
	{
		return $this->_model->where("is_deleted", '=', 0)
							->where("is_hidden", '=', 0)
		                    ->find($id);
	}

	/**
	* 逻辑修改
	* @author xww
	* @return array
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

		// 活动开始时间
		if(!empty($_POST['start_time'])){
			$_POST['start_time'] = strtotime($_POST['start_time']." 00:00:00");
		}

		// 活动结束时间
		if(!empty($_POST['end_time'])){
			$_POST['end_time'] = strtotime($_POST['end_time']." 23:59:59");
		}

		// 报名开始时间
		if(!empty($_POST['apply_start_time'])){
			$_POST['apply_start_time'] = strtotime($_POST['apply_start_time']." 00:00:00");
		}

		// 报名结束时间
		if(!empty($_POST['apply_end_time'])){
			$_POST['apply_end_time'] = strtotime($_POST['apply_end_time']." 23:59:59");
		}

		// 签到开始时间
		if(!empty($_POST['sign_in_start_time'])){
			$_POST['sign_in_start_time'] = strtotime($_POST['sign_in_start_time']);
		}

		// 签到结束时间
		if(!empty($_POST['sign_in_end_time'])){
			$_POST['sign_in_end_time'] = strtotime($_POST['sign_in_end_time']);
		}

		// 修改时间
		$_POST['update_time'] = time();

		// 记录
		$recordData = $this->_model->find($id);

		// 名额
		$success = true;

		if( !empty($recordData['apply_people_count']) ) {
			unset( $_POST['total_people_count'] );
		}

		$rs2 = $this->_model->where("id", '=', $id)->update($_POST);

		if($rs2){
			// 更新
			if($success){
				$message = '更新成功';
			}
			return ['success'=>$success, 'message'=>$message];
		} else {
			$success = false;
			$message = '更新失败';
			return ['success'=>$success, 'message'=>$message];
		}

	}
	/**
	* 逻辑删除
	* @author xww
	* @return sql result
	*/
	public function delete()
	{
		$data['is_deleted'] = 1;
		if($_POST){
			$ids = $_POST['ids'];
		}else{
			$ids = [$_GET['id']];
		}

		DB::beginTransaction();

		$rs1 = true;
		$rs2 = true;

		for ($i=0; $i < count($ids); $i++) { 
			$temp = $this->_model->where("id", $ids[$i])->update($data);
			if(!$temp){
				$rs1 = false;
				break;	
			} else {
				$rs2 = true;
			}
		}

		if($rs1 && $rs2){
			DB::commit();
			return true;
		} else {
			DB::rollback();
			return false;
		}

	}

	/**
	* 获取活动报名人数
	* @author xww
	* @param  [$id]    activity id
	* @return int
	*/ 
	public function getActivityPersonCount($id){
		return \Module\Activity\EloquentModel\ApplyActivity::where('active_id', '=', $id)
									->where("is_deleted", '=', 0)
									->count();
	}

	/**
	* 获取报名列表
	* @author xww
	* @param  [$id]    int/string    活动id
	* @return array
	*/ 
	public function getApplyPerson($id)
	{
		return \Module\Activity\EloquentModel\ApplyActivity::leftJoin("users", 'users.id', '=', 'apply_activity.user_id')
									->where('active_id', '=', $id)
									->where("apply_activity.is_deleted", '=', 0)
									->select("apply_activity.create_time as apply_time", 'users.*','apply_activity.active_id as aid')
									->get()
									->toArray();
	}

}
?>