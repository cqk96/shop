<?php
namespace VirgoModel;
class SysOperateModel {
	protected $sysOperate = '';

	public function __construct()
	{
		$this->sysOperate = new \EloquentModel\SysOperate;
	}

	public function lists()
	{
		$operateObj = $this->sysOperate->where('deleted', '=', 0);
		//父菜单总记录数
		$totalCount = $this->sysOperate->where('deleted', '=', 0)->count();
		//分页
		$size = 10;
		if(!empty($_GET['page'])){
			$page = (int)$_GET['page'];
			$skip = ($page-1)*$size;
			$operateObj = $operateObj->skip($skip)->take($size);
		} else {
			$operateObj = $operateObj->skip(0)->take($size);
		}
		$operates = $operateObj->get()->toArray();
		if(empty($operates)){
			$data = [];
		} else {
			$data = $operates;
		}
		$pageObj = new \VirgoUtil\Page2;
		$pageObj->setUrl('/admin/sys/operates');
		$pageObj->setData($data);
		$pageObj->setTotalCount($totalCount);
		$pageObj->setSize($size);
		return $pageObj->doPage();
	}

	public function read()
	{
		$id = $_GET['id'];
		return $this->sysOperate->find($id);
	}

	public function create()
	{
		
		unset($_POST['id']);
		$_POST['type_id'] = empty($this->sysOperate->max('type_id'))? 2001:$this->sysOperate->max('type_id')+1;
		return $this->sysOperate->insert($_POST);

	}

	public function update()
	{
		$id = $_POST['id'];
		unset($_POST['id']);
		return $this->sysOperate->where('id',$id)->update($_POST);
	}

	public function delete()
	{
		
		$data['deleted'] = 1;
		if($_POST)
			$ids = $_POST['ids'];
		else
			$ids = [$_GET['id']];
		return $this->sysOperate->whereIn('id',$ids)->update($data);

	}

	public function getPrivilegeName()
	{
		
		$prilileges = $this->sysOperate->where('deleted', '=', 0)->get();
		foreach ($prilileges as $key => $value) {
			$return[$value['id']] = $value;
		}

		return $return;

	}
	
}