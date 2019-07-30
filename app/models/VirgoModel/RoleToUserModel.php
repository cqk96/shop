<?php
namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class RoleToUserModel {
	protected $rtuObj = '';

	public function __construct()
	{
		$this->rtuObj = new \EloquentModel\RoleToUser;
		$this->functionObj = new \VirgoUtil\Functions;
		$this->roleObj = new \VirgoModel\SysRoleModel;
	}

	public function lists()
	{
		
		$query = $this->rtuObj->leftJoin("users", "users.id", "=", "rel_role_to_user.user_id")
		                      ->leftJoin("sys_roles", "sys_roles.id", "=", "rel_role_to_user.role_id")
					  ->where('rel_role_to_user.deleted', '=', 0)
					  ->where('users.is_deleted', '=', 0)
					  ->where('sys_roles.deleted', '=', 0)
					  ->select('users.id as user_id', 'users.user_login', DB::raw(" group_concat(`comp_sys_roles`.name separator ',') as user_roles ") )
					  ->groupBy('users.id');

		//父菜单总记录数
		// $totalCountTemp = $this->rtuObj->select("user_id")->where('deleted', '=', 0)->groupBy("user_id")->get()->toArray();
		$totalCount = count( $query->get()->toArray() );

		//分页
		$size = 10;
		if(!empty($_GET['page'])){
			$page = (int)$_GET['page'];
			$skip = ($page-1)*$size;
		} else {
			$skip = 0;
		}

		$data = $query->skip( $skip )->take( $size )->get()->toArray();

		// if(empty($users)){
		// 	$data = [];
		// } else {
		// 	$tempUser = array();
		// 	foreach ($users as $key_1 => $val_1) {
		// 		if($key_1<$skip || $key_1>($skip+$size-1)){
		// 			unset($users[$key_1]);
		// 		} else {
		// 			array_push($tempUser, $val_1);
		// 		}
		// 	}

		// 	unset($users);
		// 	$users = $tempUser;
		// 	$roles = $this->roleObj->getRoleName();
		// 	foreach ($users as $key => $value) {
		// 		$string = '';
		// 		$string_arr = array();
		// 		$temp = $this->rtuObj
		// 					  ->select('role_id')
		// 					  ->where('deleted', '=', 0)
		// 					  ->where('user_id', '=', $value['user_id'])
		// 					  ->groupBy('role_id')
		// 					  ->get();

		// 		foreach ($temp as $temp_key => $temp_value) {
		// 			if(!empty($roles[$temp_value['role_id']])) {
		// 			array_push($string_arr, $roles[$temp_value['role_id']]['name']);
		// 			}
		// 		}

		// 		if (!empty($string_arr)) {
		// 			$string = implode(',', $string_arr);
		// 		}

		// 		$users[$key]['user_roles'] = $string;

		// 	}
		// 	$data = $users;
		// }
		
		$pageObj = new \VirgoUtil\Page2;
		$pageObj->setUrl('/admin/sys/rtus');
		$pageObj->setData($data);
		$pageObj->setTotalCount($totalCount);
		$pageObj->setSize($size);
		return $pageObj->doPage();

	}

	public function read($uid)
	{
		
		$roles = array();
		$temp = $this->rtuObj
			 ->select('role_id')
			 ->where('user_id', '=', $uid)
			 ->get();

		$temp_twoDemen = $temp->toArray();
		
		foreach ($temp_twoDemen as $key => $value) {
			array_push($roles, $value['role_id']);
		}

		return $roles;

	}

	public function create()
	{
		
		$role_ids =$this->functionObj->decrementDemension($_POST['role_ids']);
		$user_id = $_POST['user_id'];
		
		foreach ($role_ids as $key => $value) {
			$this->rtuObj->insert(['role_id'=>$value, 'user_id'=>$user_id]);
		}

		//环信注册用户--客服
		//如果是客服角色进行注册
		if($is_kf){
			$user = \EloquentModel\User::find($user_id)->toArray();
			$huanXinUtilObj = new \VirgoUtil\HuanXinUtil;
			//get token
			$token = $huanXinUtilObj->getToken();

			//判断用户是否存在
			$rs = $huanXinUtilObj->getUser($user['user_login'],$token);
			$headersArr = explode("\r\n", $rs['header']);
			$LineOneArr = explode(" ", $headersArr[0]);
			$code = $LineOneAr[1];
			if($code=="404") {
				//用户注册
				$huanXinUtilObj->registerUser($user['user_login'], $user['password']);		
			}
		}
		//end

		return true;

	}

	public function update()
	{		

		$role_ids =$this->functionObj->decrementDemension($_POST['role_ids']);
		$user_id = $_POST['user_id'];

		$this->rtuObj->where('user_id', $user_id)->delete();
		$is_kf = false;
		foreach ($role_ids as $key => $value) {
			if($value==6){
				$is_kf = true;
			}
			$this->rtuObj->insert(['role_id'=>$value, 'user_id'=>$user_id]);
		}

		//环信注册用户--客服
		//如果是客服角色进行注册
		if($is_kf){
			$user = \EloquentModel\User::find($user_id)->toArray();
			$huanXinUtilObj = new \VirgoUtil\HuanXinUtil;
			//get token
			$token = $huanXinUtilObj->getToken();

			//判断用户是否存在
			$rs = $huanXinUtilObj->getUser($user['user_login'],$token);
			//var_dump($rs);
			//exit();
			$headersArr = explode("\r\n", $rs['header']);
			$LineOneArr = explode(" ", $headersArr[0]);
			$code = $LineOneArr[1];
			if($code=="404") {
				//用户注册
				$rs = $huanXinUtilObj->registerUser($user['user_login'], $user['password']);		
			}
		}
		//end

		return true;

	}

	public function delete()
	{
		
		if($_POST)
			$ids = $_POST['ids'];
		else
			$ids = [$_GET['id']];
		return $this->rtuObj->whereIn('user_id',$ids)->delete();

	}
	
	/**
	* 批量插入
	* @author 	xww
	* @param 	array 	$data
	* @return 	affect rows
	*/
	public function multiCreate($data)
	{
		return $this->rtuObj->insert($data);
	}

	/**
	* 移除用户角色
	* @author 	xww
	* @param 	int/string 	$uid
	* @return 	affect  rows
	*/
	public function removeUserRole($uid)
	{
		return $this->rtuObj->where('user_id',$uid)->delete();
	}

	/**
	* 获取用户具备的角色id
	* @author 	xww
	* @param 	int/string  	$uid
	* @return 	array
	*/
	public function getUserRoleIds( $uid )
	{

		$ids = [];
		$data = $this->rtuObj->where("deleted", 0)->where('user_id',$uid)->get()->toArray();
		for ($i=0; $i < count( $data ); $i++) { 
			$ids[] = $data[$i]['role_id'];
		}

		return 	$ids;
		
	}

	/**
	* 查询特定用户 特定角色id记录
	* @author 	xww
	* @param 	int/string 		$uid
	* @param 	int/string 		$rid
	* @return 	array
	*/
	public function getUserRoleRecord($uid, $rid)
	{
		return $this->rtuObj->where("deleted", 0)->where("user_id", $uid)->where("role_id", $rid)->first();
	}

	/**
	* 获取特定角色的用户
	* @author 	xww
	* @param 	array 			$roleIds
	* @return 	array
	*/
	public function getRoleUsers($roleIds)
	{
		return $this->rtuObj->leftJoin("sys_roles", "sys_roles.id", "=", "rel_role_to_user.role_id")
		            ->where("rel_role_to_user.deleted", 0)
		            ->where("sys_roles.deleted", 0)
		            ->whereIn("sys_roles.type_id", $roleIds)
		            ->select("rel_role_to_user.user_id")
		            ->groupBy("user_id")
		            ->get()
		            ->toArray();
	}

	/**
	* 获取所有角色中 用户拥有的角色
	* @author 	xww
	* @param 	int 			$uid
	* @return 	array
	*/
	public function getUserRolesInAll($uid)
	{
		
		$roles = \EloquentModel\SysRole::where('deleted', '=', 0)->select("id", "name")->get()->toArray();

		if( empty($roles) ) {
			return null;
		}

		$temp = $this->rtuObj->where("deleted", 0)
		            ->where("user_id", $uid)
		            ->select("role_id")
		            ->get()
		            ->toArray();

		for ($i=0; $i < count($temp); $i++) { 
			$data[ $temp[$i]['role_id'] ] = $temp[$i];
		}

		for ($i=0; $i < count($roles); $i++) { 
			$roles[$i]['checked'] = false;

			if( isset( $data[ $roles[$i]['id'] ] ) ) {
				$roles[$i]['checked'] = true;
			}

		}


		return $roles;
		
	}

	/**
	* @author 	xww
	* @param 	int/string  $typeId
	* @return   array
	*/
	public function getSpecifyUserWithTypeId( $typeId)
	{
		return $this->rtuObj->leftJoin("users", "users.id", "=", "rel_role_to_user.user_id")
		                    ->leftJoin("sys_roles", "sys_roles.id", "=", "rel_role_to_user.role_id")
						    ->where('rel_role_to_user.deleted', '=', 0)
						    ->where("sys_roles.type_id", $typeId)
						    ->where('users.is_deleted', '=', 0)
						    ->where('sys_roles.deleted', '=', 0)
						    ->select('users.id', 'users.name')
						    ->groupBy('users.id', 'users.name')
						    ->get()
						    ->toArray();
	}

	/**
	* 批量插入
	* @author 	xww
	* @param 	array 	$data
	* @return 	affect rows
	*/
	public function singleCreate($data)
	{
		return $this->rtuObj->insertGetId($data);
	}

}