<?php
namespace VirgoBack;

class AdminSysOperateController extends AdminBaseController{
	protected $operatePrivilegeObj = '';

	public function __construct()
	{
		$this->operatePrivilegeObj = new \VirgoModel\SysOperateModel;
		parent::isLogin();
	}
	
	public function lists()
	{
		$page_title = '权限管理';
		$page_sub_title = '权限管理';
		$pageObj = $this->operatePrivilegeObj->lists();
		$data = $pageObj->data;
		require dirname(__FILE__).'/../../views/admin/adminSysOperate/lists.php';
	}

	public function create()
	{

		$page_sub_title = '添加权限';
		require dirname(__FILE__).'/../../views/admin/adminSysOperate/create.php';

	}

	public function doCreate()
	{
		
		$rs = $this->operatePrivilegeObj->create();
		if($rs)
			$this->showPage(['添加权限成功'],'/admin/sys/operates');
		else 
			$this->showPage(['添加权限失败'],'/admin/sys/operates');

	}

	public function update()
	{
		$page_title  = '修改权限';
		$operatePrivilege = $this->operatePrivilegeObj->read();
		require dirname(__FILE__).'/../../views/admin/adminSysOperate/update.php';
	}

	public function doUpdate()
	{
		$rs = $this->operatePrivilegeObj->update();
		if($rs)
			$this->showPage(['修改权限成功'],'/admin/sys/operates');
		else 
			$this->showPage(['修改权限失败'],'/admin/sys/operates');
	}

	public function doDelete()
	{

		$rs = $this->operatePrivilegeObj->delete();
		
		if($_POST){
			if($rs)
				echo json_encode(['success'=>true,'message'=>'delete success']);
			else 
				echo json_encode(['success'=>false,'message'=>'delete failture']);
		} else {
			if($rs)
				$this->showPage(['删除权限成功'],'/admin/sys/operates');
			else 
				$this->showPage(['删除权限失败'],'/admin/sys/operates');
		}	

	}
}