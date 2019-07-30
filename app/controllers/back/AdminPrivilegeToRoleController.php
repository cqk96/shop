<?php
namespace VirgoBack;

class AdminPrivilegeToRoleController extends AdminBaseController{
	protected $ptrObj = '';

	public function __construct()
	{
		
		$this->ptrObj = new \VirgoModel\PrivilegeToRoleModel;
		$this->roleObj = new \VirgoModel\SysRoleModel;
		$this->privilegeObj = new \VirgoModel\SysPrivilegeModel;
		parent::isLogin();
	}
	
	public function lists()
	{
		$page_title = '角色权限管理';
		$page_sub_title = '角色权限管理';

		$pageObj = $this->ptrObj->lists();
		$data = $pageObj->data;
		$roles = $this->roleObj->getRoleName();
		
		require dirname(__FILE__).'/../../views/admin/adminPrivilegeToRole/lists.php';

	}

	public function create()
	{

		$page_sub_title = '添加角色权限';

		$roles = $this->roleObj->all();
		
		$privileges = $this->privilegeObj->all();

		require dirname(__FILE__).'/../../views/admin/adminPrivilegeToRole/create.php';

	}

	public function doCreate()
	{
		
		$rs = $this->ptrObj->create();
		if($rs)
			$this->showPage(['添加角色权限成功'],'/admin/sys/ptrs');
		else 
			$this->showPage(['添加角色权限失败'],'/admin/sys/ptrs');

	}

	public function update()
	{
		
		$page_title  = '修改角色权限';
		$rid = $_GET['id'];
		$rolePrivileges = $this->ptrObj->read($rid);

		// $privilegeObj = $this->privilegeObj->all();
		
		$privileges = $this->privilegeObj->all();
		//var_dump($privilegeObj);
		//die;
		$roles = $this->roleObj->all();
		
		require dirname(__FILE__).'/../../views/admin/adminPrivilegeToRole/update.php';

	}

	public function doUpdate()
	{
		$rs = $this->ptrObj->update();
		if($rs)
			$this->showPage(['修改角色权限成功'],'/admin/sys/ptrs');
		else 
			$this->showPage(['修改角色权限失败'],'/admin/sys/ptrs');
	}

	public function doDelete()
	{

		$rs = $this->ptrObj->delete();
		
		if($_POST){
			if($rs)
				echo json_encode(['success'=>true,'message'=>'delete success']);
			else 
				echo json_encode(['success'=>false,'message'=>'delete failture']);
		} else {
			if($rs)
				$this->showPage(['删除角色权限成功'],'/admin/sys/ptrs');
			else 
				$this->showPage(['删除角色权限失败'],'/admin/sys/ptrs');
		}	

	}
}