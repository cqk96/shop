<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
class ChatCircleModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\ChatCircle; 
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
		$ChatCircleObj = new \EloquentModel\ChatCircle;
		$query = $ChatCircleObj->select('chat_circle.*','users.name')
		->leftJoin('users','users.id','=','chat_circle.user_id')
		->where('chat_circle.is_deleted','=',0)
		
		->orderBy('id','asc');
		//$query = $this->_model->where("is_deleted", '=', 0)->orderBy("create_time", "desc");

		// 标题过滤
		if(!empty($_GET['title'])){
			$_GET['title'] = trim($_GET['title']);
			$query = $query->where("title", 'like', '%'.$_GET['title'].'%');
			$pageObj->setPageQuery(['title'=>$_GET['title']]);
		}
		// 开始时间过滤
		if(!empty($_GET['startTime'])){
			$_GET['startTime'] = trim($_GET['startTime']);
			$query = $query->where("chat_circle.create_time", '>=', strtotime($_GET['startTime']." 00:00:00"));
			$pageObj->setPageQuery(['startTime'=>$_GET['startTime']]); 
		}
		// 截止时间过滤
		if(!empty($_GET['endTime'])){
			$_GET['endTime'] = trim($_GET['endTime']);
			$query = $query->where("chat_circle.update_time", '<=', strtotime($_GET['endTime']." 23:59:59"));
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
		$pageObj->setUrl('/admin/chatCircle');
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
	* 获取说说列表
	* @author 	xww
	* @param 	int/string 	$skip
	* @param 	int/string 	$take
	* @param 	int/string 	$userId    不为null则会获取用户喜欢用的chat
	* @return 	array
	*/
	public function getChatCircleLists($skip=null, $take=null, $userId=null)
	{

		$query = $this->_model->leftJoin("users", "users.id", '=', "chat_circle.user_id")
					 ->where("chat_circle.is_deleted", 0)
					 ->where("users.is_deleted", 0)
					 ->select("users.name", "users.avatar", "chat_circle.id", "chat_circle.imgs", "chat_circle.content", "chat_circle.create_time", "chat_circle.like_count as likeCount")
					 ->orderBy("chat_circle.create_time", "desc")
					 ->orderBy("chat_circle.id", "desc");

		if( !is_null($skip) && !is_null($take) ) {
			$query = $query->skip($skip)->take($take);
		}

		$data = $query->get()->toArray();

		// 获取对应评论
		if( !empty($data) ) {
			
			for ($i=0; $i < count($data); $i++) { 
				$data[$i]['comments'] = [];
				$chatIds[] = $data[$i]['id'];	
			}

			// 获取对应评论
			$comments = \EloquentModel\CommentChatCircle::leftJoin("users as a", "a.id", '=', "comment_chat_circle.user_id")
											->leftJoin("users as b", "b.id", '=', "comment_chat_circle.to_id")
											->where("comment_chat_circle.is_deleted", 0)
											->whereIn("comment_chat_circle.chat_id", $chatIds)
											->select("a.name as fromName", "b.name as toName", "comment_chat_circle.content", "comment_chat_circle.chat_id", "comment_chat_circle.user_id")
											->orderBy("comment_chat_circle.chat_id", "asc")
											->orderBy("comment_chat_circle.created_time", "asc")
											->get()
											->toArray();

			if( !empty( $comments ) ) {

				for ($i=0; $i < count($comments); $i++) { 

					$comments[$i]['userId'] = $comments[$i]['user_id'];
					
					// 获取索引
					$poiArr = array_keys($chatIds, $comments[$i]['chat_id']);

					// 索引
					$index = $poiArr[0];

					unset($comments[$i]['chat_id']);
					unset($comments[$i]['user_id']);
					array_push($data[$index]['comments'], $comments[$i]);

				}

			}

		}

		if( !is_null($userId) ) {

			// 获取用户喜欢过的评论
			$likeChatCircleModelObj = new \VirgoModel\LikeChatCircleModel;
			$likedChats = $likeChatCircleModelObj->getUserLikeChats($userId);

			$newLikedChats = [];
			for ($i=0; $i < count($likedChats); $i++) { 
				$newLikedChats[ $likedChats[$i]['chat_id'] ] = $likedChats[$i];
			}

		}

		for ($i=0; $i < count($data); $i++) { 
			$data[$i]['comments'] = empty($data[$i]['comments'])? null:$data[$i]['comments'];
			$data[$i]['avatar'] = empty($data[$i]['avatar'])? '/images/avatar.png':$data[$i]['avatar'];

			if( !is_null( $data[$i]['imgs'] ) ) {
				$data[$i]['imgs'] = explode(",", $data[$i]['imgs']);
			}

			if( isset($newLikedChats) ) {
				// 判断用户是否喜欢过这个说说

				if( !empty( $newLikedChats[ $data[$i]['id'] ] ) ) {
					// 喜欢
					$data[$i]['like'] = 0;
				} else {
					// 不喜欢
					$data[$i]['like'] = 1;
				}

			}

		}

		return $data;

	}

	/**
	* 获取说说列表--数量
	* @author 	xww
	* @return 	int
	*/
	public function getChatCircleListsCount()
	{

		return $this->_model->leftJoin("users", "users.id", '=', "chat_circle.user_id")
					 ->where("chat_circle.is_deleted", 0)
					 ->where("users.is_deleted", 0)
					 ->count();

	}

	/**
	* 创建说说
	* @author 	xww
	* @param 	array $data
	* @return 	int
	*/
	public function create($data)
	{
		return $this->_model->insertGetId($data);
	}

	/**
	* 获取指定用户的说说列表
	* @author 	xww
	* @param 	int/string 	$userId    用户id
	* @param 	int/string 	$skip
	* @param 	int/string 	$take
	* @param 	int/string 	$userId    不为null则会获取用户喜欢用的chat
	* @return 	array
	*/
	public function getUserChatCircleLists($belongUserId, $skip=null, $take=null, $userId=null)
	{

		$query = $this->_model->leftJoin("users", "users.id", '=', "chat_circle.user_id")
					 ->where("chat_circle.is_deleted", 0)
					 ->where("users.is_deleted", 0)
					 ->select("users.name", "users.avatar", "chat_circle.id", "chat_circle.imgs", "chat_circle.content", "chat_circle.create_time", "chat_circle.like_count as likeCount")
					 ->where("chat_circle.user_id", $belongUserId)
					 ->orderBy("chat_circle.create_time", "desc")
					 ->orderBy("chat_circle.id", "desc");

		if( !is_null($skip) && !is_null($take) ) {
			$query = $query->skip($skip)->take($take);
		}

		$data = $query->get()->toArray();

		// 获取对应评论
		if( !empty($data) ) {
			
			for ($i=0; $i < count($data); $i++) { 
				$data[$i]['comments'] = [];
				$chatIds[] = $data[$i]['id'];	
			}

			// 获取对应评论
			$comments = \EloquentModel\CommentChatCircle::leftJoin("users as a", "a.id", '=', "comment_chat_circle.user_id")
											->leftJoin("users as b", "b.id", '=', "comment_chat_circle.to_id")
											->where("comment_chat_circle.is_deleted", 0)
											->whereIn("comment_chat_circle.chat_id", $chatIds)
											->select("a.name as fromName", "b.name as toName", "comment_chat_circle.content", "comment_chat_circle.chat_id", "comment_chat_circle.user_id")
											->orderBy("comment_chat_circle.chat_id", "asc")
											->orderBy("comment_chat_circle.created_time", "asc")
											->get()
											->toArray();

			if( !empty( $comments ) ) {

				for ($i=0; $i < count($comments); $i++) { 

					$comments[$i]['userId'] = $comments[$i]['user_id'];
					
					// 获取索引
					$poiArr = array_keys($chatIds, $comments[$i]['chat_id']);

					// 索引
					$index = $poiArr[0];

					unset($comments[$i]['chat_id']);
					unset($comments[$i]['user_id']);
					array_push($data[$index]['comments'], $comments[$i]);

				}

			}

		}

		if( !is_null($userId) ) {

			// 获取用户喜欢过的评论
			$likeChatCircleModelObj = new \VirgoModel\LikeChatCircleModel;
			$likedChats = $likeChatCircleModelObj->getUserLikeChats($userId);

			$newLikedChats = [];
			for ($i=0; $i < count($likedChats); $i++) { 
				$newLikedChats[ $likedChats[$i]['chat_id'] ] = $likedChats[$i];
			}

		}

		for ($i=0; $i < count($data); $i++) { 
			$data[$i]['comments'] = empty($data[$i]['comments'])? null:$data[$i]['comments'];
			$data[$i]['avatar'] = empty($data[$i]['avatar'])? '/images/avatar.png':$data[$i]['avatar'];

			if( !is_null( $data[$i]['imgs'] ) ) {
				$data[$i]['imgs'] = explode(",", $data[$i]['imgs']);
			}

			if( isset($newLikedChats) ) {
				// 判断用户是否喜欢过这个说说

				if( !empty( $newLikedChats[ $data[$i]['id'] ] ) ) {
					// 喜欢
					$data[$i]['like'] = 0;
				} else {
					// 不喜欢
					$data[$i]['like'] = 1;
				}

			}

		}

		return $data;

	}

	/**
	* 获取说说列表--数量
	* @author 	xww
	* @param 	int/string 	$userId
	* @return 	int
	*/
	public function getUserChatCircleListsCount($userId)
	{

		return $this->_model->leftJoin("users", "users.id", '=', "chat_circle.user_id")
					 ->where("chat_circle.is_deleted", 0)
					 ->where("users.is_deleted", 0)
					 ->where("chat_circle.user_id", $userId)
					 ->count();

	}

	/**
	* 获取用户的十万个为什么 
	* @author 	xww
	* @param 	int/string  	$uid
	* @param 	int/string  	$id
	* @return 	object
	*/
	public function getUserRecord($uid, $id)
	{
		return $this->_model->where("is_deleted", 0)
							->where("user_id", $uid)
							->where("id", $id)
							->first();

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
	
}
?>