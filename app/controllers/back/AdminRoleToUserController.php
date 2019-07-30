<?php
namespace VirgoBack;

class AdminRoleToUserController extends AdminBaseController{
	protected $rtuObj = '';

	public function __construct()
	{
		$this->rtuObj = new \VirgoModel\RoleToUserModel;
		$this->userObj = new \VirgoModel\UserModel;
		$this->roleObj = new \VirgoModel\SysRoleModel;
		parent::isLogin();
	}
	
	public function lists()
	{
		$page_title = '用户角色管理';
		$page_sub_title = '用户角色管理';

		$pageObj = $this->rtuObj->lists();
		$data = $pageObj->data;

		$users = $this->userObj->lists(['id','user_login'],'',true);
		
		require dirname(__FILE__).'/../../views/admin/adminRoleToUser/lists.php';

	}

	public function create()
	{

		$page_sub_title = '添加用户角色';
		
		$users = $this->userObj->lists(['id','user_login'],['is_deleted'=>['=',0]]);

		$pageObj = $this->roleObj->lists();
		$roles = $pageObj->data;
		require dirname(__FILE__).'/../../views/admin/adminRoleToUser/create.php';

	}

	public function doCreate()
	{
		
		$rs = $this->rtuObj->create();
		if($rs)
			$this->showPage(['添加用户角色成功'],'/admin/sys/rtus');
		else 
			$this->showPage(['添加用户角色失败'],'/admin/sys/rtus');

	}

	public function update()
	{
		
		$page_title  = '修改用户角色';
		$uid = $_GET['id'];
		$userRoles = $this->rtuObj->read($uid);

		$users = $this->userObj->lists(['id','user_login'],['is_deleted'=>['=',0]]);

		$pageObj = $this->roleObj->lists();
		$roles = $pageObj->data;
		
		require dirname(__FILE__).'/../../views/admin/adminRoleToUser/update.php';

	}

	public function doUpdate()
	{
		$rs = $this->rtuObj->update();
		if($rs)
			$this->showPage(['修改用户角色成功'],'/admin/sys/rtus');
		else 
			$this->showPage(['修改用户角色失败'],'/admin/sys/rtus');
	}

	public function doDelete()
	{

		$rs = $this->rtuObj->delete();
		
		if($_POST){
			if($rs)
				echo json_encode(['success'=>true,'message'=>'delete success']);
			else 
				echo json_encode(['success'=>false,'message'=>'delete failture']);
		} else {
			if($rs)
				$this->showPage(['删除用户角色成功'],'/admin/sys/rtus');
			else 
				$this->showPage(['删除用户角色失败'],'/admin/sys/rtus');
		}	

	}
}