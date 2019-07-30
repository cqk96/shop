<?php
namespace VirgoBack;

class AdminRoleController extends AdminBaseController{
	protected $roleObj = '';

	public function __construct()
	{
		parent::isLogin();
		$this->roleObj = new \VirgoModel\SysRoleModel;
	}
	
	public function lists()
	{
		$page_title = '角色管理';
		$page_sub_title = '角色管理';
		$pageObj = $this->roleObj->lists();
		$data = $pageObj->data;
		require dirname(__FILE__).'/../../views/admin/adminRole/lists.php';
	}

	public function create()
	{

		$page_sub_title = '添加角色';
		require dirname(__FILE__).'/../../views/admin/adminRole/create.php';

	}

	public function doCreate()
	{
		
		$rs = $this->roleObj->create();
		if($rs)
			$this->showPage(['添加角色成功'],'/admin/sys/roles');
		else 
			$this->showPage(['添加角色失败'],'/admin/sys/roles');

	}

	public function update()
	{
		$page_title  = '修改角色';
		$role = $this->roleObj->read();
		require dirname(__FILE__).'/../../views/admin/adminRole/update.php';
	}

	public function doUpdate()
	{
		$rs = $this->roleObj->update();
		if($rs)
			$this->showPage(['修改角色成功'],'/admin/sys/roles');
		else 
			$this->showPage(['修改角色失败'],'/admin/sys/roles');
	}

	public function doDelete()
	{

		$rs = $this->roleObj->delete();
		
		if($_POST){
			if($rs)
				echo json_encode(['success'=>true,'message'=>'delete success']);
			else 
				echo json_encode(['success'=>false,'message'=>'delete failture']);
		} else {
			if($rs)
				$this->showPage(['删除角色成功'],'/admin/sys/roles');
			else 
				$this->showPage(['删除角色失败'],'/admin/sys/roles');
		}	

	}
}