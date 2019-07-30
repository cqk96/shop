<?php
namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class RoleToMenuModel {
	protected $rtmObj = '';

	public function __construct()
	{
		$this->rtmObj = new \EloquentModel\RoleToMenu;
		$this->functionObj = new \VirgoUtil\Functions;
		//$this->roleObj = new \VirgoModel\SysRoleModel;
		$this->menuObj = new \VirgoModel\SysMenuModel;
	}

	public function lists()
	{
		
		$roles = $this->rtmObj
					  ->select('role_id')
					  ->where('deleted', '=', 0)
					  ->groupBy('role_id')
					  ->get()->toArray();
		//父菜单总记录数
		$totalCount = count($roles);
		//分页
		$size = 10;
		if(!empty($_GET['page'])){
			$page = (int)$_GET['page'];
			$skip = ($page-1)*$size;
		} else {
			$skip = 0;
		}
		if(empty($roles)){
			$data = [];
		} else {
			$tempRoles = array();
			foreach ($roles as $key_1 => $val_1) {
				if($key_1<$skip || $key_1>($skip+$size-1)){
					unset($roles[$key_1]);
				} else {
					array_push($tempRoles, $val_1);
				}
			}
			unset($roles);
			$roles = $tempRoles;

			$menus = $this->menuObj->getKVMenus();
			foreach ($roles as $key => $value) {
				$string = '';
				$string_arr = array();
				$temp = $this->rtmObj
							  ->select('menu_id')
							  ->where('deleted', '=', 0)
							  ->where('role_id', '=', $value['role_id'])
							  ->groupBy('menu_id')
							  ->get();

				foreach ($temp as $temp_key => $temp_value) {
					if(!empty($menus[$temp_value['menu_id']])) {
					array_push($string_arr, $menus[$temp_value['menu_id']]['name']);
					}
				}

				if (!empty($string_arr)) {
					$string = implode(',', $string_arr);
				}

				$roles[$key]['role_menus'] = $string;

			}
			$data = $roles;
		}
		
		$pageObj = new \VirgoUtil\Page2;
		$pageObj->setUrl('/admin/sys/rtms');
		$pageObj->setData($data);
		$pageObj->setTotalCount($totalCount);
		$pageObj->setSize($size);
		return $pageObj->doPage();

	}

	public function read($rid)
	{
		
		$menus = array();
		$temp = $this->rtmObj
			 ->select('menu_id')
			 ->where('role_id', '=', $rid)
			 ->get();

		$temp_twoDemen = $temp->toArray();
		
		foreach ($temp_twoDemen as $key => $value) {
			array_push($menus, $value['menu_id']);
		}

		return $menus;

	}

	public function create()
	{
		
		$menu_ids =$this->functionObj->decrementDemension($_POST['menu_ids']);
		$role_id = $_POST['role_id'];
		
		foreach ($menu_ids as $key => $value) {
			$this->rtmObj->insert(['menu_id'=>$value, 'role_id'=>$role_id]);
		}

		return true;

	}

	public function update()
	{
		

		$menu_ids =$this->functionObj->decrementDemension($_POST['menu_ids']);
		$role_id = $_POST['role_id'];

		$this->rtmObj->where('role_id', $role_id)->delete();
		
		foreach ($menu_ids as $key => $value) {
			$this->rtmObj->insert(['menu_id'=>$value, 'role_id'=>$role_id]);
		}

		return true;

	}

	public function delete()
	{
		
		if($_POST)
			$ids = $_POST['ids'];
		else
			$ids = [$_GET['id']];
		return $this->rtmObj->whereIn('role_id',$ids)->delete();

	}
	
	/**
	* 更新角色菜单
	* @author 	xww
	* @param 	int/string 		$roleId
	* @param 	array 			$menuIds
	* @param 	bool 			$doEmpty
	* @return 	void
	*/
	public function saveRoleMenus($roleId, $menuIds, $doEmpty=false)
	{

		try{

			DB::beginTransaction();

			// 判断角色是否有菜单  有则进行删除
			$hasMenu = $this->rtmObj->where("role_id", $roleId)->where("deleted", 0)->count();

			if( $hasMenu ) {
				// 进行删除
				$rs = $this->rtmObj->where("role_id", $roleId)->where("deleted", 0)->delete();

				if( !$rs ) {
					// 删除失败 回滚
					throw new \Exception("删除失败");
				}

			}

			// 如果并非只是清空 则进行加入操作
			if( !$doEmpty ) {
				$insertData = [];
				for ($i=0; $i < count($menuIds); $i++) { 

					if( empty($menuIds[$i]) ) {
						continue;
					}

					// 构建插入数组
					$temp['role_id'] = $roleId;
					$temp['menu_id'] = $menuIds[$i];
					$temp['deleted'] = 0;

					$insertData[] = $temp;
				}

				// 有正常数据 进行插入
				if( !empty($insertData) ) {
					$rs = $this->rtmObj->insert( $insertData );

					if( !$rs ) {
						throw new \Exception("添加失败");		
					}

				}

			}

			DB::commit();
			return true;

		} catch(\Exception $e) {
			DB::rollback();
			return false;
		}

	}

	/**
	* 获取角色拥有的菜单
	* @author 	xww
	* @param 	int/string 		$id
	* @return 	array
	*/
	public function getRoleParentTreeMenus($id)
	{
		
		$menus = $this->menuObj->getParentMenusAll();

		if( empty($menus) ) {
			return null;
		}

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

			$childrenMenus = $this->menuObj->getTopMenu( $menus[ $i ]['id'] );
			array_shift($childrenMenus);

			for ($j=0; $j < count($childrenMenus); $j++) { 
				$childrenMenus[$j]['checked'] = false;

				if( isset( $roleMenu[ $childrenMenus[$j]['id'] ] ) ) {
					$childrenMenus[$j]['checked'] = true;
				}

			}

			if( !empty($childrenMenus) ) {
				$newChildrenMenus = array_values( $childrenMenus );
			}

			$menus[ $i ]['childrenMenus'] = empty($childrenMenus)? null:$newChildrenMenus;

		}

		return $menus;

	}

}