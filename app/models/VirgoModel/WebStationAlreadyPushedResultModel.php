<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class WebStationAlreadyPushedResultModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\WebStationAlreadyPushedResult; 
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
		$pageObj->setUrl('/admin/webStationAlreadyPushedResults');
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
	* 获取最大推送次数
	* @author 	xww
	* @param 	int/string 		$msgId
	* @return 	int
	*/
	public function getMaxTimes($msgId)
	{
		
		return $this->_model->where("msg_id", $msgId)
					 ->where("is_deleted", 0)
					 ->max("times");

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
	
	/*添加记录*/
	public function create($data)
	{
		return $this->_model->insertGetId($data);
	}

	/**
	* 获取指派给我的指令列表 (推送是要成功的)
	* @author 	xww
	* @param 	int/string 		$uid
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	array
	*/
	public function getInstructionLists($uid, $statusId=null, $isDone=null, $skip=null, $size=null)
	{
		
		$query = $this->_model->leftJoin("instructions_message", "instructions_message.id", '=', 'web_station_already_pushed_result.msg_id')
							  ->leftJoin("users", "users.id", '=', 'instructions_message.author_id')
						  	  ->where("instructions_message.is_deleted", 0)
							  ->where("web_station_already_pushed_result.is_deleted", 0)
							  ->where("web_station_already_pushed_result.type_id", 2)
							  ->where("web_station_already_pushed_result.user_id", $uid)
							  ->where("web_station_already_pushed_result.pushed_result", 1)
							  ->orderBy("web_station_already_pushed_result.is_read", "asc")
							  ->orderBy("web_station_already_pushed_result.is_done", "asc")
							  ->orderBy("web_station_already_pushed_result.create_time", "desc")
							  ->select("web_station_already_pushed_result.id", "instructions_message.content", "web_station_already_pushed_result.create_time as createTime", "users.name as authorName", "web_station_already_pushed_result.is_read as isRead", "web_station_already_pushed_result.is_done as isDone");

		if( !is_null($statusId) ) {
			$query = $statusId->where("web_station_already_pushed_result.is_read", $statusId);
		}

		if( !is_null($isDone) ) {
			$query = $query->where("web_station_already_pushed_result.is_done", $isDone);
		}

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}

		return $query->get()->toArray();

	}

	/**
	* 获取用户的某一条指令
	* @author 	xww
	* @param 	int/string 		$uid	
	* @param 	int/string 		$id	
	* @return 	array
	*/
	public function getUserInstructionWithId($uid, $id)
	{
		return $this->_model->where("is_deleted", 0)->where("user_id", $uid)->where("type_id", 2)->find($id);
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
	* 获取指令推送的人员列表
	* @author 	xww
	* @param 	int/string 		$id
	* @param 	int/string  	$skip
	* @param 	int/string  	$size
	* @return 	array
	*/	
	public function getInstructionUserLists($id, $skip=null, $size=null)
	{

		$query = $this->_model->leftJoin("users", "users.id", '=', 'web_station_already_pushed_result.user_id')
							  ->where("users.is_deleted", 0)
							  ->where("web_station_already_pushed_result.is_deleted", 0)
							  ->where("web_station_already_pushed_result.type_id", 2)
							  ->where("web_station_already_pushed_result.msg_id", $id)
							  ->orderBy("web_station_already_pushed_result.create_time", "desc")
							  ->select("web_station_already_pushed_result.msg_id as id", "users.name", "is_read as isRead", "is_done as isDone", "pushed_result as pushedResult");

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}

		return $query->get()->toArray();

	}

	/**
	* 获取用户消息列表
	* @author 	xww
	* @param 	int/string  	$uid
	* @param 	int/string  	$skip
	* @param 	int/string  	$size
	* @return 	array
	*/
	public function getUserMessageLists($uid, $skip=null, $size=null)
	{
		
		$query = $this->_model->leftJoin("web_station_message", "web_station_message.id", '=', 'web_station_already_pushed_result.msg_id')
							  ->where("web_station_already_pushed_result.is_deleted", 0)
							  ->where("web_station_already_pushed_result.type_id", 1)
							  ->where("pushed_result", 1)
							  ->where("web_station_already_pushed_result.user_id", $uid)
							  ->orderBy("is_read", "asc")
							  ->orderBy("web_station_already_pushed_result.create_time", "desc")
							  ->orderBy("web_station_already_pushed_result.id", "desc")
							  ->select("web_station_already_pushed_result.id", "web_station_already_pushed_result.extra_content", "web_station_message.content", "is_read as isRead", "web_station_already_pushed_result.create_time as createTime");



		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}

		$data = $query->get()->toArray();

		for ($i=0; $i < count($data); $i++) { 
			$data[$i]['content'] = empty($data[$i]['content'])? $data[$i]['extra_content']:$data[$i]['content'];
			unset($data[$i]['extra_content']);
		}

		return $data;

	}

	/**
	* 获取用户 指定ids的数据集合
	* @author 	xww
	* @param 	int/string  	$uid
	* @param 	array  			$ids
	* @return 	int
	*/
	public function getUserMultipleMessageWithIds($uid, $ids)
	{
		return $this->_model->where("type_id", 1)
							->where("is_deleted", 0)
							->where("user_id", $uid)
							->whereIn("id", $ids)
							->get()
							->toArray();
	}

	/**
	* 标记用户部分消息已读
	* @author 	xww
	* @param 	int/string  	$uid
	* @param 	array  			$ids
	* @return 	int
	*/
	public function updateUserMultipleMessageReadWithIds($uid, $ids)
	{
		
		$data['is_read'] = 1;
		$data['update_time'] = time();

		return $this->_model->where("type_id", 1)
							->where("is_deleted", 0)
							->where("user_id", $uid)
							->whereIn("id", $ids)
							->update($data);

	}

	/**
	* 用户是否有未读消息
	* @author 	xww
	* @param 	int/string  	$uid
	* @return 	int
	*/
	public function hasUserUnreadMessage($uid)
	{
		
		$count = $this->_model->where("type_id", 1)
							->where("is_read", 0)
							->where("is_deleted", 0)
							->where("user_id", $uid)
							->count();

		return $count? true:false;

	}

	/**
	* 标记用户全部消息已读
	* @author 	xww
	* @param 	int/string  	$uid
	* @param 	array  			$ids
	* @return 	int
	*/
	public function updateUserUnreadMessageRead($uid)
	{
		
		$data['is_read'] = 1;
		$data['update_time'] = time();

		return $this->_model->where("type_id", 1)
							->where("is_read", 0)
							->where("is_deleted", 0)
							->where("user_id", $uid)
							->update($data);

	}
	
	/**
	* 获取用户 指定ids的数据集合
	* @author 	xww
	* @param 	int/string  	$uid
	* @param 	array  			$ids
	* @return 	int
	*/
	public function getUserMultipleInstructionWithIds($uid, $ids)
	{
		return $this->_model->where("type_id", 2)
							->where("is_deleted", 0)
							->where("user_id", $uid)
							->whereIn("id", $ids)
							->get()
							->toArray();
	}

	/**
	* 标记用户部分指令已读
	* @author 	xww
	* @param 	int/string  	$uid
	* @param 	array  			$ids
	* @return 	int
	*/
	public function updateUserMultipleInstructionReadWithIds($uid, $ids)
	{
		
		$data['is_read'] = 1;
		$data['update_time'] = time();

		return $this->_model->where("type_id", 2)
							->where("is_deleted", 0)
							->where("user_id", $uid)
							->whereIn("id", $ids)
							->update($data);

	}

	/**
	* 用户是否有未读指令
	* @author 	xww
	* @param 	int/string  	$uid
	* @return 	int
	*/
	public function hasUserUnreadInstruction($uid)
	{
		
		$count = $this->_model->where("type_id", 2)
							->where("is_read", 0)
							->where("is_deleted", 0)
							->where("user_id", $uid)
							->count();

		return $count? true:false;

	}

	/**
	* 标记用户全部指令已读
	* @author 	xww
	* @param 	int/string  	$uid
	* @param 	array  			$ids
	* @return 	int
	*/
	public function updateUserUnreadInstructionRead($uid)
	{
		
		$data['is_read'] = 1;
		$data['update_time'] = time();

		return $this->_model->where("type_id", 2)
							->where("is_read", 0)
							->where("is_deleted", 0)
							->where("user_id", $uid)
							->update($data);

	}

	/**
	* 获取所有消息列表
	* @author 	xww
	* @param 	int/string 		$authorId
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	array
	*/
	public function getMessageListsObject($authorId=null, $statusId=0,$skip=null, $size=null)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		if( $statusId==1 ) {
			
			$query = $this->_model->leftJoin("web_station_message", "web_station_message.id", '=', "web_station_already_pushed_result.msg_id")
							  ->leftJoin("users", "users.id", '=', "web_station_message.author_id")
							  ->leftJoin("users as b", "b.id", "=", "web_station_already_pushed_result.user_id")
		                      ->where("web_station_message.type_id", 1)
		                      ->where("web_station_already_pushed_result.type_id", 1)
		                      ->where("web_station_already_pushed_result.is_deleted", 0)
		                      ->where("web_station_message.is_deleted", 0)
							  ->select("web_station_message.id", "web_station_message.content", "web_station_message.create_time", DB::raw(" group_concat(`comp_b`.name separator ';' ) as reveiverName ") )
							  ->orderBy("web_station_message.create_time", "desc")
							  ->groupBy("web_station_message.id");

			
		} else {

			$query = \EloquentModel\WebStationMessage::leftJoin("web_station_wait_for_push", "web_station_wait_for_push.msg_id", '=', "web_station_message.id")
							  ->leftJoin("users", "users.id", '=', "web_station_message.author_id")
							  ->leftJoin("users as b", "b.id", "=", "web_station_wait_for_push.user_id")
		                      ->where("web_station_message.type_id", 1)
		                      ->where("web_station_wait_for_push.is_deleted", 0)
							  ->select("web_station_message.id", "web_station_message.content", "web_station_message.create_time", DB::raw(" group_concat(`comp_b`.name separator ';' ) as reveiverName ") )
							  ->orderBy("web_station_message.create_time", "desc")
							  ->groupBy("web_station_message.id");

		}

		if( !is_null($authorId) ) {
			$query = $query->where("web_station_message.author_id", $authorId);
		}

		$query1 = $query;

		// 父菜单总记录数
		$totalCount = count( $query1->get()->toArray() );

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}
		// 获取记录
		$data = $query->get()->toArray();

		for ($i=0; $i < count($data); $i++) { 
			$data[$i]['statusStr'] = $statusId==0? '未发送':'已发送';
			$data[$i]['statusId'] = $statusId;
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
	* 获取消息推送结果
	* @author 	xww
	* @param 	int/string 	$msgId
	* @return 	array
	*/
	public function getMessagePushResult( $msgId )
	{

		$data = $this->_model->leftJoin("web_station_message", "web_station_message.id", '=', "web_station_already_pushed_result.msg_id")
							  ->leftJoin("users", "users.id", '=', "web_station_message.author_id")
							  ->leftJoin("users as b", "b.id", "=", "web_station_already_pushed_result.user_id")
		                      ->where("web_station_message.type_id", 1)
		                      ->where("web_station_message.id", $msgId)
		                      ->where("web_station_already_pushed_result.type_id", 1)
		                      ->where("web_station_already_pushed_result.is_deleted", 0)
		                      ->where("web_station_message.is_deleted", 0)
							  ->select("web_station_message.id", "web_station_message.content", "users.name as authorName", "web_station_message.create_time", "b.name as receiverName", "web_station_already_pushed_result.pushed_result as pushedResult")
							  ->get()
							  ->toArray();

		return empty($data)? null:$data;
	}

	/**
	* 获取所有指令列表
	* @author 	xww
	* @param 	int/string 		$authorId
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	array
	*/
	public function getInstructionListsObject($authorId=null, $skip=null, $size=null)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		//
		$query = $this->_model->leftJoin("instructions_message", "instructions_message.id", '=', "web_station_already_pushed_result.msg_id")
						  ->leftJoin("users", "users.id", '=', "instructions_message.author_id")
						  ->leftJoin("users as b", "b.id", "=", "web_station_already_pushed_result.user_id")
	                      ->where("web_station_already_pushed_result.type_id", 2)
	                      ->where("web_station_already_pushed_result.is_deleted", 0)
	                      ->where("instructions_message.is_deleted", 0)
						  ->select("instructions_message.id", "instructions_message.content", "instructions_message.create_time", DB::raw(" group_concat(`comp_b`.name separator ';' ) as reveiverName ") )
						  ->orderBy("instructions_message.create_time", "desc")
						  ->groupBy("instructions_message.id");

		if( !is_null($authorId) ) {
			$query = $query->where("instructions_message.author_id", $authorId);
		}

		$query1 = $query;

		// 父菜单总记录数
		$totalCount = count( $query1->get()->toArray() );

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}
		// 获取记录
		$data = $query->get()->toArray();

		for ($i=0; $i < count($data); $i++) { 
			$data[$i]['createTimeStr'] = empty($data[$i]['create_time'])? '':date("Y-m-d", $data[$i]['create_time']);
			// $data[$i]['statusId'] = $statusId;
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

}
?>