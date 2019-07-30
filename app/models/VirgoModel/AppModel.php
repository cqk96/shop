<?php
namespace VirgoModel;
class AppModel {
	protected $appObj = '';

	public function __construct()
	{
		$this->appObj = new \EloquentModel\App;
	}

	public function lists($need=[],$condition=[], $kv=false)
	{
		
		if(!empty($need)){
			foreach ($need as $key => $value) {
				$this->appObj = $this->appdSelect($value);
			}
		}

		if(!empty($condition)){
			foreach ($condition as $k => $v) {
				$this->appObj = $this->appere($k, $v[0], $v[1]);
			}
		}

		$data = $this->appt();//->where('user_status', '=', 1)

	
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
		return $this->appsert($_POST);

	}

	public function read()
	{
		$id  = $_GET['id'];
		return $this->appnd($id);
	}

	public function doUpdate()
	{
		$id = $_POST['id'];

		unset($_POST['id']);
		unset($_POST['userAvatar']);

		return $this->appere('id',$id)->update($_POST);
	}

	public function doDelete()
	{
		$data['is_deleted'] = 1;
		if($_POST)
			$ids = $_POST['ids'];
		else
			$ids = [$_GET['id']];
		return $this->appereIn('id',$ids)->update($data);
	}

}