<?php
namespace VirgoModel;
class SysRoleModel {
	protected $sysRoleObj = '';

	public function __construct()
	{
		$this->sysRoleObj = new \EloquentModel\SysRole;
	}

	public function lists()
	{
		$roleObj = $this->sysRoleObj->where('deleted', '=', 0);
		//父菜单总记录数
		$totalCount = $this->sysRoleObj->where('deleted', '=', 0)->count();
		//分页
		$size = 10;
		if(!empty($_GET['page'])){
			$page = (int)$_GET['page'];
			$skip = ($page-1)*$size;
			$roleObj = $roleObj->skip($skip)->take($size);
		} else {
			$roleObj = $roleObj->skip(0)->take($size);
		}
		$role = $roleObj->get()->toArray();
		if(empty($role)){
			$data = [];
		} else {
			$data = $role;
		}
		$pageObj = new \VirgoUtil\Page2;
		$pageObj->setUrl('/admin/sys/roles');
		$pageObj->setData($data);
		$pageObj->setTotalCount($totalCount);
		$pageObj->setSize($size);
		return $pageObj->doPage();
	}

	public function all()
	{
		$roleObj = $this->sysRoleObj->where('deleted', '=', 0);
		
		$role = $roleObj->get()->toArray();
		if(empty($role)){
			$data = [];
		} else {
			$data = $role;
		}

		return $data;
		
	}

	public function MenuPrivilegeLists($kv=false)
	{
		
		$data =  $this->sysRoleObj
		    		  ->select('sys_roles.*')
					  ->join("rel_privilege_to_role", "sys_roles.id", '=', 'rel_privilege_to_role.role_id')
					  ->join("sys_privileges", "sys_privileges.id", '=', 'rel_privilege_to_role.privilege_id')
					  ->where('sys_roles.deleted', '=', 0)
					  ->where('sys_privileges.type_id', '=', 2001)
					  ->get();


		if($kv){
			
			foreach ($data as $key => $value) {
				$return[$value['id']] = $value;
			}

			unset($data);
			
			$data = $return;

		}

		return $data;

	}

	public function MenuOperatePrivilegeLists($kv=false)
	{
		
		$data =  $this->sysRoleObj
		    		  ->select('sys_roles.*')
					  ->join("rel_privilege_to_role", "sys_roles.id", '=', 'rel_privilege_to_role.role_id')
					  ->join("sys_privileges", "sys_privileges.id", '=', 'rel_privilege_to_role.privilege_id')
					  ->where('sys_roles.deleted', '=', 0)
					  ->where('sys_privileges.type_id', '=', 2002)
					  ->get();


		if($kv){
			
			foreach ($data as $key => $value) {
				$return[$value['id']] = $value;
			}

			unset($data);
			
			$data = $return;

		}

		return $data;

	}

	public function getRoleName()
	{
		
		$data = $this->sysRoleObj->where('deleted', '=', 0)->get();
		$return = array();
		foreach ($data as $key => $value) {
			$return[$value['id']] = $value;
		}
		
		return $return;

	}

	public function read()
	{
		$id = $_GET['id'];
		return $this->sysRoleObj->find($id);
	}

	public function create()
	{
		
		unset($_POST['id']);
		$_POST['type_id'] = empty($this->sysRoleObj->max('type_id'))? 1101:$this->sysRoleObj->max('type_id')+1;
		return $this->sysRoleObj->insert($_POST);

	}

	public function update()
	{
		$id = $_POST['id'];
		unset($_POST['id']);
		return $this->sysRoleObj->where('id',$id)->update($_POST);
	}

	public function delete()
	{
		
		$data['deleted'] = 1;
		if($_POST)
			$ids = $_POST['ids'];
		else
			$ids = [$_GET['id']];
		return $this->sysRoleObj->whereIn('id',$ids)->update($data);

	}
	
	/**
	* 获取列表对象--顺便获取角色所是否具有菜单权限，操作权限
	* @author 	xww
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	object
	*/
	public function getListsObject($skip, $size)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$privilegeModel = new \VirgoModel\PrivilegeToRoleModel;

		$query = $this->sysRoleObj->where("deleted", 0)->select("id", "name");

		// 父菜单总记录数
		$totalCount = count( $query->get()->toArray() );

		// 获取记录
		$data = $query->skip($skip)->take($size)->get()->toArray();

		for ($i=0; $i < count($data); $i++) { 
			
			$hasMenu = $privilegeModel->hasRolePrivilegeType($data[$i]['id'], 1);
			$hasOperate = $privilegeModel->hasRolePrivilegeType($data[$i]['id'], 2);

			$data[$i]['menuPrivilege'] = (int)$hasMenu;
			$data[$i]['operatePrivilege'] = (int)$hasOperate;

		}

		$url = "";

		//设置页数跳转地址
		$pageObj->setUrl( $url );

		// 设置分页数据
		$pageObj->setData($data);

		// 设置记录总数
		$pageObj->setTotalCount($totalCount);

		// 设置分页大小
		$pageObj->setSize($size);

		// 进行分页并返回
		return $pageObj->doPage();

	}

	/**
	* 获取角色拥有的菜单
	* @author 	xww
	* @param 	int/string 		$id
	* @return 	array
	*/
	public function getRoleMenus($id)
	{
		
		$data = [];

		$menus = \EloquentModel\SysMenu::where("status", 0)
										->select("id", "name")
										->where("show", 1)
										->where("status", 0)
										->orderBy("parentid", "asc")
										->orderBy("order", "asc")
										->orderBy("id", "asc")
										->get()
										->toArray();

		$roleMenu = [];
		$temp = \EloquentModel\RoleToMenu::where("deleted", 0)
									 ->where("role_id", $id)
									 ->select("menu_id")
									 ->get()
									 ->toArray();

		for ($i=0; $i < count($temp); $i++) { 
			$roleMenu[ $temp[$i]['menu_id'] ] = $temp[$i];
		}

		for ($i=0; $i < count($menus); $i++) { 
			$menus[$i]['checked'] = false;

			if( isset( $roleMenu[ $menus[$i]['id'] ] ) ) {
				$menus[$i]['checked'] = true;
			}

		}

		return $menus;

	}

	/**
	* 获取下一个的typeId
	* @author　	xww
	* @return 	int
	*/
	public function getNextTypeId()
	{
		return $this->sysRoleObj->where("deleted", 0)->max('type_id')+1;
	}

	/**
	* 是否有此typeId的数据
	* @author 	xww
	* @param 	int/string 		$typeId
	* @return ` bool
	*/
	public function hasTypeId($typeId)
	{
		
		$count = $this->sysRoleObj->where("deleted", 0)->where("type_id", $typeId)->count();

		return $count? true:false;

	}

	/**
	* 创建记录
	*
	*/
	public function createSingleTon($data)
	{
		
		
		return $this->sysRoleObj->insertGetId($data);

	}

	/**
	* 是否有指定角色的数据存在
	* @author 	xww
	* @param 	array 		$ids
	* @return 	arary
	*/
	public function getRoleArray($ids)
	{

		return $this->sysRoleObj->whereIn("id", $ids)->where("deleted", 0)->get()->toArray();

	}

	/**
	* 多数据更新
	* @author 	xww
	* @param 	array 		$ids
	* @param 	array 		$data
	* @return 	int
	*/
	public function multipartUpdate($ids, $data)
	{

		return $this->sysRoleObj->whereIn("id", $ids)->update($data);

	}

	/**
	* 查看记录
	*
	*/
	public function readSingleTon($id)
	{
		
		
		return $this->sysRoleObj->find($id);

	}

	/**
	* 数据更新
	* @author 	xww
	* @param 	int/string 		$id
	* @param 	array 			$data
	* @return 	int
	*/
	public function partUpdate($id, $data)
	{

		return $this->sysRoleObj->where("id", $id)->update($data);

	}

	/**
	* 获取角色拥有的操作权限
	* @author 	xww
	* @param 	int/string 		$id
	* @return 	array
	*/
	public function getRoleOperations($id)
	{
		
		$data = [];

		$operates = \EloquentModel\SysOperate::where("deleted", 0)
										->select("id", "name")
										->orderBy("id", "asc")
										->get()
										->toArray();

		$roleOperates = [];
		$temp = \EloquentModel\OperatePrivilegeToRole::where("deleted", 0)
									 ->where("role_id", $id)
									 ->select("operate_id")
									 ->get()
									 ->toArray();

		for ($i=0; $i < count($temp); $i++) { 
			$roleOperates[ $temp[$i]['operate_id'] ] = $temp[$i];
		}

		for ($i=0; $i < count($operates); $i++) { 
			$operates[$i]['checked'] = false;

			if( isset( $roleOperates[ $operates[$i]['id'] ] ) ) {
				$operates[$i]['checked'] = true;
			}

		}

		return $operates;

	}

	/**
	* 获取角色拥有的权限
	* @author 	xww
	* @param 	int/string 		$id
	* @return 	array
	*/
	public function getRolePrivileges($id)
	{
		
		$data = [];

		$privileges = \EloquentModel\SysPrivilege::where("deleted", 0)
										->select("id", "name")
										->orderBy("id", "asc")
										->get()
										->toArray();

		$rolePrivileges = [];
		$temp = \EloquentModel\PrivilegeToRole::where("deleted", 0)
									 ->where("role_id", $id)
									 ->select("privilege_id")
									 ->get()
									 ->toArray();

		for ($i=0; $i < count($temp); $i++) { 
			$rolePrivileges[ $temp[$i]['privilege_id'] ] = $temp[$i];
		}

		for ($i=0; $i < count($privileges); $i++) { 
			$privileges[$i]['checked'] = false;

			if( isset( $rolePrivileges[ $privileges[$i]['id'] ] ) ) {
				$privileges[$i]['checked'] = true;
			}

		}

		return $privileges;

	}

	/**
	* 获取全部角色
	* @author 	xww
	* @return 	array
	*/
	public function getAll()
	{
		return $this->sysRoleObj->where("deleted", 0)->select("id", "name")->get()->toArray();
	}

}