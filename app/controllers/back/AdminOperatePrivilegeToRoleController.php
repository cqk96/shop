<?php
namespace VirgoBack;

class AdminOperatePrivilegeToRoleController extends AdminBaseController{
	protected $optrObj = '';

	public function __construct()
	{
		
		$this->optrObj = new \VirgoModel\OperatePrivilegeToRoleModel;
		$this->roleObj = new \VirgoModel\SysRoleModel;
		$this->operatePrivilegeObj = new \VirgoModel\SysOperateModel;
		parent::isLogin();
	}
	
	public function lists()
	{
		
		$page_title = '角色权限管理';
		$page_sub_title = '角色权限管理';

		$pageObj = $this->optrObj->lists();
		$data = $pageObj->data;
		$roles = $this->roleObj->getRoleName();
		
		require dirname(__FILE__).'/../../views/admin/adminOperatePrivilegeToRole/lists.php';

	}

	public function create()
	{

		$page_sub_title = '添加角色权限';
		
		$roles = $this->roleObj->MenuOperatePrivilegeLists();
		$pageObj = $this->operatePrivilegeObj->lists();
		$privileges = $pageObj->data;
		
		require dirname(__FILE__).'/../../views/admin/adminOperatePrivilegeToRole/create.php';

	}

	public function doCreate()
	{
		
		$rs = $this->optrObj->create();
		if($rs)
			$this->showPage(['添加角色权限成功'],'/admin/sys/opms');
		else 
			$this->showPage(['添加角色权限失败'],'/admin/sys/opms');

	}

	public function update()
	{
		
		$page_title  = '修改角色权限';
		$rid = $_GET['id'];
		$rolePrivileges = $this->optrObj->read($rid);

		$pageObj = $this->operatePrivilegeObj->lists();

		$privileges = $pageObj->data;

		$roles = $this->roleObj->MenuOperatePrivilegeLists();
		
		require dirname(__FILE__).'/../../views/admin/adminOperatePrivilegeToRole/update.php';

	}

	public function doUpdate()
	{
		$rs = $this->optrObj->update();
		if($rs)
			$this->showPage(['修改角色权限成功'],'/admin/sys/opms');
		else 
			$this->showPage(['修改角色权限失败'],'/admin/sys/opms');
	}

	public function doDelete()
	{

		$rs = $this->optrObj->delete();
		
		if($_POST){
			if($rs)
				echo json_encode(['success'=>true,'message'=>'delete success']);
			else 
				echo json_encode(['success'=>false,'message'=>'delete failture']);
		} else {
			if($rs)
				$this->showPage(['删除角色权限成功'],'/admin/sys/opms');
			else 
				$this->showPage(['删除角色权限失败'],'/admin/sys/opms');
		}	

	}
}