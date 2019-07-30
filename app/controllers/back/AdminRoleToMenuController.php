<?php
namespace VirgoBack;

class AdminRoleToMenuController extends AdminBaseController{
	protected $rtmObj = '';

	public function __construct()
	{
		$this->rtmObj = new \VirgoModel\RoleToMenuModel;
		$this->menuObj = new \VirgoModel\SysMenuModel;
		$this->roleObj = new \VirgoModel\SysRoleModel;
		parent::isLogin();
	}
	
	public function lists()
	{
		$page_title = '角色菜单管理';
		$page_sub_title = '角色菜单管理';

		$pageObj = $this->rtmObj->lists();
		$data = $pageObj->data;

		$menus = $this->menuObj->getKVMenus();

		$roles = $this->roleObj->MenuPrivilegeLists(true);
		require dirname(__FILE__).'/../../views/admin/adminRoleToMenu/lists.php';

	}

	public function create()
	{

		$page_sub_title = '添加角色菜单';
		
		$menus = $this->menuObj->lists();

		$roles = $this->roleObj->MenuPrivilegeLists();

		require dirname(__FILE__).'/../../views/admin/adminRoleToMenu/create.php';

	}

	public function doCreate()
	{
		
		$rs = $this->rtmObj->create();
		if($rs)
			$this->showPage(['添加角色菜单成功'],'/admin/sys/rtms');
		else 
			$this->showPage(['添加角色菜单失败'],'/admin/sys/rtms');

	}

	public function update()
	{
		
		$page_title  = '修改角色菜单';
		$rid = $_GET['id'];
		$roleMenus = $this->rtmObj->read($rid);

		$menus = $this->menuObj->lists();

		$roles = $this->roleObj->MenuPrivilegeLists();
		
		require dirname(__FILE__).'/../../views/admin/adminRoleToMenu/update.php';

	}

	public function doUpdate()
	{
		$rs = $this->rtmObj->update();
		if($rs)
			$this->showPage(['修改角色菜单成功'],'/admin/sys/rtms');
		else 
			$this->showPage(['修改角色菜单失败'],'/admin/sys/rtms');
	}

	public function doDelete()
	{

		$rs = $this->rtmObj->delete();
		
		if($_POST){
			if($rs)
				echo json_encode(['success'=>true,'message'=>'delete success']);
			else 
				echo json_encode(['success'=>false,'message'=>'delete failture']);
		} else {
			if($rs)
				$this->showPage(['删除角色菜单成功'],'/admin/sys/rtms');
			else 
				$this->showPage(['删除角色菜单失败'],'/admin/sys/rtms');
		}	

	}
}