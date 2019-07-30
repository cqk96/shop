<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
class WebStationWaitForPushModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\WebStationWaitForPush; 
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
		$pageObj->setUrl('/admin/webStationWaitForPushs');
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
	* 添加记录
	* @author 	xww
	* @param 	array 	$data
	* @return 	id
	*/ 
	public function create($data)
	{
		return $this->_model->insertGetId($data);
	}

	/**
	* 添加多条记录
	* @author 	xww
	* @param 	array 	$data
	* @return 	affect rows
	*/ 
	public function multiCreate($data)
	{
		return $this->_model->insert($data);
	}

	/**
	* 获取待推送消息的所有用户
	* @author 	xww
	* @param 	int/string  	$msgId
	* @return 	array
	*/
	public function getMsgUsers($msgId)
	{
		return $this->_model->where("msg_id", $msgId)
							->where("is_deleted", 0)
							->get()
							->toArray();
	}

	/**
	* 删除指定消息的待推送用户
	* @author 	xww
	* @param 	int/string  	$msgId
	* @return 	affect rows
	*/
	public function hardDeleteMsg($msgId)
	{
		return $this->_model->where("msg_id", $msgId)->delete();
	}

	/**
	* 获取消息接收列表详情
	* @author 	xww
	* @param 	int/string 		$msgId
	* @return 	array
	*/
	public function getUnpushedDetail($msgId)
	{
		
		return $this->_model->leftJoin("web_station_message", "web_station_message.id", '=', "web_station_wait_for_push.msg_id")
							  ->leftJoin("users", "users.id", '=', "web_station_message.author_id")
							  ->leftJoin("users as b", "b.id", "=", "web_station_wait_for_push.user_id")
		                      ->where("web_station_message.type_id", 1)
		                      ->where("web_station_message.id", $msgId)
		                      // ->where("web_station_wait_for_push.type_id", 1)
		                      ->where("web_station_wait_for_push.is_deleted", 0)
		                      ->where("web_station_message.is_deleted", 0)
							  ->select("web_station_message.id", "web_station_message.content", "users.name as authorName", "web_station_message.create_time", "b.name as receiverName", "web_station_wait_for_push.user_id")
							  ->get()
							  ->toArray();

	}

}
?>