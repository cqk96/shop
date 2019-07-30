<?php
namespace VirgoBack;

class AdminPrivilegeController extends AdminBaseController{
	protected $privilegeObj = '';

	public function __construct()
	{
		parent::isLogin();
		$this->privilegeObj = new \VirgoModel\SysPrivilegeModel;
	}
	
	public function lists()
	{
		$page_title = '权限管理';
		$page_sub_title = '权限管理';
		$pageObj = $this->privilegeObj->lists();
		$data = $pageObj->data;
		require dirname(__FILE__).'/../../views/admin/adminPrivilege/lists.php';
	}

	public function create()
	{

		$page_sub_title = '添加权限';
		require dirname(__FILE__).'/../../views/admin/adminPrivilege/create.php';

	}

	public function doCreate()
	{
		
		$rs = $this->privilegeObj->create();
		if($rs)
			$this->showPage(['添加权限成功'],'/admin/sys/privileges');
		else 
			$this->showPage(['添加权限失败'],'/admin/sys/privileges');

	}

	public function update()
	{
		$page_title  = '修改权限';
		$privilege = $this->privilegeObj->read();
		require dirname(__FILE__).'/../../views/admin/adminPrivilege/update.php';
	}

	public function doUpdate()
	{
		$rs = $this->privilegeObj->update();
		if($rs)
			$this->showPage(['修改权限成功'],'/admin/sys/privileges');
		else 
			$this->showPage(['修改权限失败'],'/admin/sys/privileges');
	}

	public function doDelete()
	{

		$rs = $this->privilegeObj->delete();
		
		if($_POST){
			if($rs)
				echo json_encode(['success'=>true,'message'=>'delete success']);
			else 
				echo json_encode(['success'=>false,'message'=>'delete failture']);
		} else {
			if($rs)
				$this->showPage(['删除权限成功'],'/admin/sys/privileges');
			else 
				$this->showPage(['删除权限失败'],'/admin/sys/privileges');
		}	

	}
}