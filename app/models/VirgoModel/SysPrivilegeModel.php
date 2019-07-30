<?php
namespace VirgoModel;
class SysPrivilegeModel {
	protected $sysPrivilegeObj = '';

	public function __construct()
	{
		$this->sysPrivilegeObj = new \EloquentModel\SysPrivilege;
	}

	public function lists()
	{
		
		$privilegeObj = $this->sysPrivilegeObj->where('deleted', '=', 0);
		//父菜单总记录数
		$totalCount = $this->sysPrivilegeObj->where('deleted','=',0)->count();
		//分页
		$size = 10;
		if(!empty($_GET['page'])){
			$page = (int)$_GET['page'];
			$skip = ($page-1)*$size;
			$privilegeObj = $privilegeObj->skip($skip)->take($size);
		} else {
			$privilegeObj = $privilegeObj->skip(0)->take($size);
		}
		$privilege = $privilegeObj->get()->toArray();

		if(empty($privilege)) {
			$data = [];
		} else {
			$data = $privilege;
		}
		$pageObj = new \VirgoUtil\Page2;
		$pageObj->setUrl('/admin/sys/privileges');
		$pageObj->setData($data);
		$pageObj->setTotalCount($totalCount);
		$pageObj->setSize($size);
		return $pageObj->doPage();
	}

	public function all()
	{
		
		$privilegeObj = $this->sysPrivilegeObj->where('deleted', '=', 0);
		
		$privilege = $privilegeObj->get()->toArray();

		if(empty($privilege)) {
			$data = [];
		} else {
			$data = $privilege;
		}

		return $data;
		
	}

	public function read()
	{
		$id = $_GET['id'];
		return $this->sysPrivilegeObj->find($id);
	}

	public function create()
	{
		
		unset($_POST['id']);
		$_POST['type_id'] = empty($this->sysPrivilegeObj->max('type_id'))? 2001:$this->sysPrivilegeObj->max('type_id')+1;
		return $this->sysPrivilegeObj->insert($_POST);

	}

	public function update()
	{
		$id = $_POST['id'];
		unset($_POST['id']);
		return $this->sysPrivilegeObj->where('id',$id)->update($_POST);
	}

	public function delete()
	{
		
		$data['deleted'] = 1;
		if($_POST)
			$ids = $_POST['ids'];
		else
			$ids = [$_GET['id']];
		return $this->sysPrivilegeObj->whereIn('id',$ids)->update($data);

	}

	public function getPrivilegeName()
	{
		
		$prilileges = $this->sysPrivilegeObj->where('deleted', '=', 0)->get();
		foreach ($prilileges as $key => $value) {
			$return[$value['id']] = $value;
		}

		return $return;

	}
	
}