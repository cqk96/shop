<?php
namespace VirgoBack;

class AdminSysMenuController extends AdminBaseController{
	protected $sysMenuObj = '';

	public function __construct()
	{
		$this->sysMenuObj = new \VirgoModel\SysMenuModel;
		parent::isLogin();
	}
	
	public function lists()
	{
		$page_title = '后台菜单管理';
		$page_sub_title = '后台菜单管理';
		$pageObj = $this->sysMenuObj->treeLists();
		require dirname(__FILE__).'/../../views/admin/adminSysMenu/lists.php';
	}

	public function create()
	{

		$page_sub_title = '添加后台菜单';

		$menus = $this->sysMenuObj->lists();

		require dirname(__FILE__).'/../../views/admin/adminSysMenu/create.php';

	}

	public function doCreate()
	{
		
		$rs = $this->sysMenuObj->create();

		if($rs)
			$this->showPage(['添加后台菜单成功'],'/admin/sys/menus');
		else 
			$this->showPage(['添加后台菜单失败'],'/admin/sys/menus');

	}

	public function update()
	{
		
		$page_title  = '修改后台菜单';
		$menus = $this->sysMenuObj->lists();
		$menu = $this->sysMenuObj->read();
		require dirname(__FILE__).'/../../views/admin/adminSysMenu/update.php';

	}

	public function doUpdate()
	{
		$rs = $this->sysMenuObj->update();
		if($rs)
			$this->showPage(['修改后台菜单成功'],'/admin/sys/menus');
		else 
			$this->showPage(['修改后台菜单失败'],'/admin/sys/menus');
	}

	public function doDelete()
	{

		$rs = $this->sysMenuObj->delete();
		
		if($_POST){
			if($rs)
				echo json_encode(['success'=>true,'message'=>'delete success']);
			else 
				echo json_encode(['success'=>false,'message'=>'delete failture']);
		} else {
			if($rs)
				$this->showPage(['删除后台菜单成功'],'/admin/sys/menus');
			else 
				$this->showPage(['删除后台菜单失败'],'/admin/sys/menus');
		}	

	}

	public function updateColumn()
	{
		$this->sysMenuObj->updateColumn();
	}
	
}