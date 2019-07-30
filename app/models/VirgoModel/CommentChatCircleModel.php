<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
class CommentChatCircleModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\CommentChatCircle; 
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
		$CommentChatCircleObj = new \EloquentModel\CommentChatCircle;
		$query = $CommentChatCircleObj->select('comment_chat_circle.*','users.name', "chat_circle.content as title")
		->leftJoin('chat_circle','chat_circle.id','=','comment_chat_circle.chat_id')
		->leftJoin('users','users.id','=','comment_chat_circle.user_id')
		->where('comment_chat_circle.is_deleted','=',0)
		->orderBy('id','asc');
		
		//$query = $this->_model->where("is_deleted", '=', 0)->orderBy("created_time", "desc");

		// 标题过滤
		if(!empty($_GET['title'])){
			$_GET['title'] = trim($_GET['title']);
			$query = $query->where("chat_circle.content", 'like', '%'.$_GET['title'].'%');
			$pageObj->setPageQuery(['title'=>$_GET['title']]);
		}

		// 评论过滤
		if(!empty($_GET['commentTitle'])){
			$_GET['commentTitle'] = trim($_GET['commentTitle']);
			$query = $query->where("comment_chat_circle.content", 'like', '%'.$_GET['commentTitle'].'%');
			$pageObj->setPageQuery(['commentTitle'=>$_GET['commentTitle']]);
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
		$pageObj->setUrl('/admin/commentChatCircle');
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
		
		// 创建时间
		$_POST['created_time'] = time();
		// 修改时间
		$_POST['updated_time'] = time();
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
		
		// 修改时间
		$_POST['updated_time'] = time();
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
	* 创建评论
	* @author 	xww
	* @param 	array $data
	* @return 	int
	*/
	public function create($data)
	{
		return $this->_model->insertGetId($data);
	}

}
?>