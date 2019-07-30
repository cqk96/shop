<?php
namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class OperatePrivilegeToRoleModel {
	protected $optrObj = '';

	public function __construct()
	{
		$this->optrObj = new \EloquentModel\OperatePrivilegeToRole;
		$this->functionObj = new \VirgoUtil\Functions;
		$this->operatePrivilegeObj = new \VirgoModel\SysOperateModel;
		//$this->roleObj = new \VirgoModel\SysRoleModel;
	}

	public function lists()
	{
		
		$roles = $this->optrObj
					  ->select('role_id')
					  ->where('deleted', '=', 0)
					  ->groupBy('role_id')
					  ->get();
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

			$privileges = $this->operatePrivilegeObj->getPrivilegeName();
			foreach ($roles as $key => $value) {
				$string = '';
				$string_arr = array();
				$temp = $this->optrObj
							  ->select('operate_id')
							  ->where('deleted', '=', 0)
							  ->where('role_id', '=', $value['role_id'])
							  ->groupBy('operate_id')
							  ->get();

				foreach ($temp as $temp_key => $temp_value) {
					if(!empty($privileges[$temp_value['operate_id']])) {
					array_push($string_arr, $privileges[$temp_value['operate_id']]['name']);
					}
				}

				if (!empty($string_arr)) {
					$string = implode(',', $string_arr);
				}

				$roles[$key]['user_privileges'] = $string;

			}
			$data = $roles;
		}
		$pageObj = new \VirgoUtil\Page2;
		$pageObj->setUrl('/admin/sys/opms');
		$pageObj->setData($data);
		$pageObj->setTotalCount($totalCount);
		$pageObj->setSize($size);
		return $pageObj->doPage();
	}

	public function read($rid)
	{
		
		$privileges = array();
		$temp = $this->optrObj
			 ->select('operate_id')
			 ->where('role_id', '=', $rid)
			 ->get();

		$temp_twoDemen = $temp->toArray();
		
		foreach ($temp_twoDemen as $key => $value) {
			array_push($privileges, $value['operate_id']);
		}

		return $privileges;

	}

	public function create()
	{
		
		$privilege_ids =$this->functionObj->decrementDemension($_POST['operate_ids']);
		$role_id = $_POST['role_id'];
		
		foreach ($privilege_ids as $key => $value) {
			$this->optrObj->insert(['role_id'=>$role_id, 'operate_id'=>$value]);
		}

		return true;

	}

	public function update()
	{
		

		$privilege_ids =$this->functionObj->decrementDemension($_POST['operate_ids']);
		$role_id = $_POST['role_id'];
		
		$this->optrObj->where('role_id', $role_id)->delete();

		foreach ($privilege_ids as $key => $value) {
			$this->optrObj->insert(['role_id'=>$role_id, 'operate_id'=>$value]);
		}

		return true;

	}

	public function delete()
	{
		
		if($_POST)
			$ids = $_POST['ids'];
		else
			$ids = [$_GET['id']];
		return $this->optrObj->whereIn('role_id',$ids)->delete();

	}

	/**
	* 更新角色操作权限
	* @author 	xww
	* @param 	int/string 		$roleId
	* @param 	array 			$operationIds
	* @param 	bool 			$doEmpty
	* @return 	void
	*/
	public function saveRoleOperations($roleId, $operationIds, $doEmpty=false)
	{

		try{

			DB::beginTransaction();

			// 判断角色是否有操作权限  有则进行删除
			$hasData = $this->optrObj->where("role_id", $roleId)->where("deleted", 0)->count();

			if( $hasData ) {
				// 进行删除
				$rs = $this->optrObj->where("role_id", $roleId)->where("deleted", 0)->delete();

				if( !$rs ) {
					// 删除失败 回滚
					throw new \Exception("删除失败");
				}

			}

			// 如果并非只是清空 则进行加入操作
			if( !$doEmpty ) {
				$insertData = [];
				for ($i=0; $i < count($operationIds); $i++) { 

					if( empty($operationIds[$i]) ) {
						continue;
					}

					// 构建插入数组
					$temp['role_id'] = $roleId;
					$temp['operate_id'] = $operationIds[$i];
					$temp['deleted'] = 0;

					$insertData[] = $temp;
				}

				// 有正常数据 进行插入
				if( !empty($insertData) ) {
					$rs = $this->optrObj->insert( $insertData );

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
	
}