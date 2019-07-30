<?php
namespace VirgoModel;
class CommentNewsModel {
	protected $commentNewsObj = '';

	public function __construct()
	{
		$this->commentNewsObj = new \EloquentModel\CommentNews;
	}

	public function lists($need=[],$condition=[], $kv=false)
	{
		
		if(!empty($need)){
			foreach ($need as $key => $value) {
				$this->commentNewsObj = $this->commentNewsObj->addSelect($value);
			}
		}

		if(!empty($condition)){
			foreach ($condition as $k => $v) {
				$this->commentNewsObj = $this->commentNewsObj->where($k, $v[0], $v[1]);
			}
		}

		$data = $this->commentNewsObj->get();//->where('user_status', '=', 1)

	
		if($kv){
			$return = array();
			foreach ($data as $key => $value) {
				$return[$value['id']] = $value;
			}

			unset($data);
			$data = $return;
			
		}

		return $data;
	}

	public function doCreate()
	{
		unset($_POST['id']);
		unset($_POST['userAvatar']);

		$_POST['password'] = md5('123456');
		$_POST['create_time'] = time();
		return $this->commentNewsObj->insert($_POST);

	}

	public function read()
	{
		$id  = $_GET['id'];
		return $this->commentNewsObj->find($id);
	}

	public function doUpdate()
	{
		$id = $_POST['id'];

		unset($_POST['id']);
		unset($_POST['userAvatar']);

		return $this->commentNewsObj->where('id',$id)->update($_POST);
	}

	public function doDelete()
	{
		$data['is_deleted'] = 1;
		if($_POST)
			$ids = $_POST['ids'];
		else
			$ids = [$_GET['id']];
		return $this->commentNewsObj->whereIn('id',$ids)->update($data);
	}
}