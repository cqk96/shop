<?php
namespace VirgoBack;
class AdminDepartmentRelUserController extends AdminBaseController
{
	
	public function __construct()
	{
		$this->pageObj = new \VirgoUtil\Page;
		$this->departmentObj = new \VirgoModel\DepartmentRelUserModel;
		$this->functionObj = new \VirgoUtil\Functions;
		parent::isLogin();
	}

	public function index()
	{
		
		$page_title = '部门管理';
		
		$page_title = '管理';
		$pageObj = $this->departmentObj->lists();
		// 赋值数据
		$data = $pageObj->data;

		require_once dirname(__FILE__).'/../../views/admin/adminDepartmentRelUser/index.php';
		
	}

	public function create()
	{
		
		$page_title = '添加部门';

		

		require_once dirname(__FILE__).'/../../views/admin/adminDepartmentRelUser/_create.php';

	}

	public function read()
	{

	}

	/**
	* render the page
	* @author 	xww
	* @return 	void
	*/
	public function update()
	{
		
		$id = $_GET['id'];
		$page_title = "修改部门";
		//$data = $this->departmentObj->find($id);

		require_once dirname(__FILE__).'/../../views/admin/adminDepartmentRelUser/_update.php';
		
	}

	public function delete()
	{

	}

	public function doCreate()
	{

		/*  //检测部门名称唯一性
		$name_is_used = \EloquentModel\Department::where('name', '=', $_POST['name'])->get();

		if(!count($name_is_used)==0){
			header('Refresh: 2;url=/admin/departments');
			echo "该部门名已存在";
			return false;
		}

		//logo结果
		if(!empty($_FILES['logo']['name'])){
			$uploadAvatarResult = $this->functionObj->specialUploadFile('logo','/upload/departmentLogo/',array('jpg','png'));
			if(!$uploadAvatarResult[0]['success']) {
				header('Refresh: 2;url=/admin/departments');
				echo "上传Logo图片失败";
				if(!$uploadAvatarResult[0]['validate'])
					echo "格式不正确，请上传jpg和png格式的文件";
				return false;
			}
			$uploadAvatarResult = $uploadAvatarResult[0]['picurl'];
			$_POST['logo'] = $uploadAvatarResult;
		}  */
		$obj = new \EloquentModel\DepartmentRelUser;
		/* $params['logo'] = $_POST['logo'];
		$params['name'] = $_POST['name'];
		$params['content'] = $_POST['content'];
		$params['description'] = $_POST['description'];
		$params['upper_id'] = $_POST['upper_id'];
		$params['staff_id'] = $_POST['staff_id']; */

		$rs = $obj->insert($params);

		if($rs){
			header('Refresh: 2;url=/admin/departments');
			echo "添加成功";
		} else {
			header('Refresh: 2;url=/admin/departments');
			echo "添加失败";
		}

	}

	public function doRead()
	{

	}

	public function doUpdate()
	{

		//检测部门名称唯一性
		$name_is_used = \EloquentModel\Department::where('name', '=', $_POST['name'])
												->where('id', '<>', $_POST['id'])
		                                        ->get();

		if(!count($name_is_used)==0){
			header('Refresh: 2;url=/admin/departments');
			echo "该部门名称已存在";
			return false;
		}

		if($_FILES['logo']['name']!=''){
			$uploadAvatarResult = $this->functionObj->specialUploadFile('userAvatar','/upload/departmentLogo/logo',array('jpg','png'));
			if(!$uploadAvatarResult[0]['success']) {
				header('Refresh: 2;url=/admin/departments');
				echo "上传Logo图片失败";
				if(!$uploadAvatarResult[0]['validate'])
					echo "格式不正确，请上传jpg和png格式的文件";
				return false;
			}
			$uploadAvatarResult = $uploadAvatarResult[0]['picurl'];
			$_POST['logo'] = $uploadAvatarResult;
		}
		
		$rs = $this->departmentObj->doUpdate();
		if($rs){
			header('Refresh: 2;url=/admin/departments');
			echo "修改成功";
		} else {
			header('Refresh: 2;url=/admin/departments');
			echo "修改失败";
		}
	}

	public function doDelete()
	{
		
		$rs = $this->departmentObj->doDelete();
		if($_POST){
			if($rs)
				echo json_encode(['success'=>true,'message'=>'delete success', 'code'=>'001']);
			else 
				echo json_encode(['success'=>false,'message'=>'delete failture','code'=>'012']);
		} else {
			if($rs){
				header('Refresh: 2;url=/admin/departments');
				echo "删除成功";
			} else {
				header('Refresh: 2;url=/admin/departments');
				echo "删除失败";
			}
		}
	}

	/**
	* 查看该部门下所有用户
	* render the page
	* @author 	xww
	* @return 	void
	*/
	public function users()
	{
		
		try {

			if(empty($_GET['id'])) {
				throw new \Exception("Wrong Param");
			}



			$id = $_GET['id'];

			$data = $this->departmentObj->read($id);

			if(empty($data)) {
				throw new \Exception("部门不存在 或 已被删除");
			}

			// 标题
			$page_title = $data['name'];

			/*查看该部门下的所有用户*/
			$users = $this->departmentObj->getDepartmentUsers($id);

			require_once dirname(__FILE__).'/../../views/admin/adminDepartmentRelUser/users.php';

		} catch(\Exception $e) {
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

}
