<?php
namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class UserModel {
	protected $userObj = '';

	public function __construct()
	{
		$this->userObj = new \EloquentModel\User;
	}

	public function lists($need=[],$condition=[], $kv=false)
	{
		
		if(!empty($need)){
			foreach ($need as $key => $value) {
				$this->userObj = $this->userObj->addSelect($value);
			}
		}

		if(!empty($condition)){
			foreach ($condition as $k => $v) {
				$this->userObj = $this->userObj->where($k, $v[0], $v[1]);
			}
		}

		$data = $this->userObj->get();//->where('user_status', '=', 1)

	
		if($kv){
			$return = array();
			foreach ($data as $key => $value) {
				$return[$value['id']] = $value;
			}

			unset($data);
			$data = $return;
			
		}

		return $data;
	}

	public function getusers(){
		return $department = $this->userObj->leftJoin("department_rel_user", "department_rel_user.user_id", '=', "users.id")
		->leftJoin("departments", "departments.id", "=", "department_rel_user.department_id as departmentName")
		->where("users.is_deleted",'=', 0)
		->select();
	
	}
	
	public function doCreate()
	{
		unset($_POST['id']);
		unset($_POST['userAvatar']);

		$_POST['password'] = "nciou".md5('123456')."dijdm";
		$_POST['create_time'] = time();
		$_POST['age'] = empty($_POST['age'])? 0:(int)$_POST['age'];

		// 接收角色
		$roles = empty( $_POST['roles'] )? []:$_POST['roles'];
		unset($_POST['roles']);

		DB::beginTransaction();
		
		
		$user = array();
		//$department = array();
		$user['user_login'] = $_POST['user_login'];
		$user['name'] = $_POST['name'];
		$user['phone'] = $_POST['phone'];
		
		$user['ethnicity'] = $_POST['ethnicity'];
		$user['native_place'] = $_POST['native_place'];
		$user['political'] = $_POST['political'];
		$user['join_time'] = $_POST['join_time'];
		$user['birthday'] = $_POST['birthday'];
		$user['university'] = $_POST['university'];
		$user['major'] = $_POST['major'];
		$user['education'] = $_POST['education'];
		$user['address'] = $_POST['address'];
		$user['password'] = $_POST['password'];
		
		$user['nickname'] = $_POST['nickname'];
		$user['age'] = $_POST["age"];
		$user['introduce'] = $_POST["introduce"];

		if( !empty($_POST['avatar']) ) {
			$user['avatar'] = $_POST['avatar'];
		}

		if( !empty($_POST['record_working_time']) ) {
			$user['record_working_time'] = $_POST['record_working_time'];
		}

		// 创建用户
		$rs = $this->userObj->insertGetId($user);


		$createRoleRs = true;
		if($rs) {

			if($roles) {

				$roleInsertData = [];

				// 拼接数组
				for ($i=0; $i < count($roles); $i++) { 
					$temp = [];
					$temp['role_id'] = $roles[$i];
					$temp['user_id'] = $rs;
					array_push($roleInsertData, $temp);
				}

				if(!empty($roleInsertData)) {
					$roleToUserModelObj = new \VirgoModel\RoleToUserModel;
					$createRoleRs = $roleToUserModelObj->multiCreate($roleInsertData);
				}

				unset($temp);
			}
			

		}

		if($rs && $createRoleRs) {
			DB::commit();
			return true;
		} else {
			DB::rollback();
			return false;
		}

	}

	public function read()
	{
		$id  = $_GET['id'];
		return $this->userObj->find($id);
	}

	
	public function doUpdate()
	{
		
		$id = $_POST['id'];

		unset($_POST['id']);
		unset($_POST['userAvatar']);
		$_POST['age'] = empty($_POST['age'])? 0:(int)$_POST['age'];
		$_POST['update_time'] = time();		
		
		$roles = empty( $_POST['roles'] )? []:$_POST['roles'];
		unset($_POST['roles']);

		DB::beginTransaction();

		
		$user = array();

		$user['name'] = $_POST['name'];
		$user['phone'] = $_POST['phone'];
		
		$user['ethnicity'] = $_POST['ethnicity'];
		$user['native_place'] = $_POST['native_place'];
		$user['political'] = $_POST['political'];
		$user['join_time'] = $_POST['join_time'];
		$user['birthday'] = $_POST['birthday'];
		$user['university'] = $_POST['university'];
		$user['major'] = $_POST['major'];
		$user['education'] = $_POST['education'];
		$user['address'] = $_POST['address'];

		// 保证能进行user表修改
		$user['update_time'] = time();

		if( !empty($_POST['avatar']) ) {
			$user['avatar'] = $_POST['avatar'];
		}

		$user['gender'] = $_POST["gender"];

		if( !empty($_POST['record_working_time']) ) {
			$user['record_working_time'] = $_POST['record_working_time'];
		}

		$user['working_life_time'] = $_POST['working_life_time'];
		$user['nickname'] = $_POST['nickname'];
		$user['age'] = $_POST["age"];
		$user['introduce'] = $_POST["introduce"];
		
		$rs = $this->userObj->where('id',$id)->update($user);
		
		$createRoleRs = true;
		if($rs) {

			$roleToUserModelObj = new \VirgoModel\RoleToUserModel;

			$roleToUserModelObj->removeUserRole($id);

			$roleInsertData = [];

			// 拼接数组
			for ($i=0; $i < count($roles); $i++) { 
				$temp = [];
				$temp['role_id'] = $roles[$i];
				$temp['user_id'] = $id;
				array_push($roleInsertData, $temp);
			}

			if(!empty($roleInsertData)) {
				
				$createRoleRs = $roleToUserModelObj->multiCreate($roleInsertData);
				unset($temp);
			}

		}
		
		if($rs && $createRoleRs) {
			DB::commit();
			return true;
		} else {
			DB::rollback();
			return false;
		}

	}

	public function doDelete() 
	{
		$data['is_deleted'] = 1;
		if($_POST)
			$ids = $_POST['ids'];
		else
			$ids = [$_GET['id']];
		return $this->userObj->whereIn('id',$ids)->update($data);
	}

	/**
	* 获取全部用户 账号 名称 id
	* @author 	xww
	* @return 	array
	*/ 
	public function all()
	{
		return $this->userObj->where("is_deleted", 0)
					  ->select("id", "user_login")
					  ->get()
					  ->toArray();
	}

	/**
	* 根据关键字查询用户
	* @author 	xww
	* @param 	string 		$search
	* @return 	array
	*/ 
	public function searchUser($search)
	{
		return $this->userObj->where("is_deleted", 0)
							->where("user_login", 'like', "%".$search."%")
							->where("id", "<>", 1)
					  ->select("id", "user_login", "name")
					  ->get()
					  ->toArray();
	}

	/**
	* 记录查询
	* @author 	xww
	* @param 	int/string 	$id
	* @return 	object 	
	*/
	public function readSingleTon($id)
	{
		return $this->userObj->where("is_deleted", 0)->find($id);
	}

	/**
	* 获取身份为师傅的用户
	* @author 	xww
	* @return 	array
	*/
	public function getMaster()
	{
		
		return $this->userObj->leftJoin("rel_role_to_user", "rel_role_to_user.user_id", "=", "users.id")
					  ->where("rel_role_to_user.role_id", 10)
					  ->where("users.is_deleted", 0)
					  ->where("rel_role_to_user.deleted", 0)
					  ->select("users.id", "user_login", "name")
					  ->get()
					  ->toArray();
	}

	/**
	* 获取当前用户的上级审批人角色
	* 先查询部门 然后 再查询部门里是否具有这种角色的用户
	* 如果没有 就查询上级部门
	* @author 	xww
	* @todo
	* @param 	int/string 	$uid
	* @param 	array 		$roles
	* @return 	int
	*/
	public function getNextApprover($uid, $roles)
	{
		
		// 获取关联的部门
		$record = \EloquentModel\DepartmentRelUser::leftJoin("departments", "departments.id", '=', 'department_rel_user.department_id')
												 ->where("departments.is_deleted", 0)
												 ->where("department_rel_user.is_deleted", 0)
												 ->where("user_id", $uid)
												 ->select("department_id", "p_department_id")
												 ->first();

		if(empty($record)) {
			throw new \Exception("不存在关联部门", '006');
		}

		$record = $record->toArray();

		$ok = true;
		$departmentId = $record['department_id'];
		$parentId = $record['p_department_id'];

		while($ok) {

			$user = $this->getParentRole($departmentId, $roles);	
			
			if(empty($user)) {
				// 查询这个部门的上级部门
				$temp = \EloquentModel\Department::where("id", $parentId)
										 ->where("is_deleted", 0)
										 ->first();

				if(empty($temp)) {
					// 不再循环
					$ok = false;
				} else {

					$departmentId = $temp['id'];
					$parentId = $temp['p_department_id'];

				}

			} else {
				$ok = false;
			}

		}

		return empty($user)? null:$user;

	}

	/**
	* 获取部门中是否具有 给定角色的用户
	* @author 	xww
	* @param 	array 	$roles  用户角色字典
	* @return 	array
	*/ 
	public function getParentRole($departmentId, $roles)
	{
		
		return \EloquentModel\DepartmentRelUser::leftJoin("departments", "departments.id", '=', 'department_rel_user.department_id')
		                                       ->leftJoin("users", "users.id", "=", "department_rel_user.user_id")
										->leftJoin("rel_role_to_user", "rel_role_to_user.user_id", "=", "department_rel_user.user_id")
										->leftJoin("sys_roles", "sys_roles.id", '=', "rel_role_to_user.role_id")
										->where("department_rel_user.is_deleted", '=', 0)
										->where("users.is_deleted", '=', 0)
										->where("departments.is_deleted", 0)
										->where("rel_role_to_user.deleted", 0)
										->where("sys_roles.deleted", 0)
										->where("departments.id", '=', $departmentId)
										->whereIn("sys_roles.type_id", $roles)
										->select("rel_role_to_user.user_id", "sys_roles.type_id", "department_rel_user.department_id")
										->get()
										->toArray();

	}

	/**
	* 根据id获取用户审批身份，角色的字典
	* @author 	xww
	* @param 	int/string 		$uid
	* @return 	array
	*/ 
	public function getUserApprovalRole($uid)
	{
		
	 	return \EloquentModel\RoleToUser::leftJoin("users", "users.id", '=', "rel_role_to_user.user_id")
	 								->leftJoin("sys_roles", "sys_roles.id", "=", "rel_role_to_user.role_id")
	 								->where("users.is_deleted", 0)
	 								->where("sys_roles.deleted", 0)
	 								->where("rel_role_to_user.deleted", 0)
	 								->where("users.id", $uid)
	 								->whereIn("sys_roles.type_id", ['2101', '2102', '2103'])
	 								->orderBy("sys_roles.type_id", "desc")
	 								->select("sys_roles.type_id")
	 								->take(1)
	 								->first();
	}
	
	/**
	* 根据关键字查询尚未录入部门的用户
	* @author 	xww
	* @param 	string 		$search
	* @return 	array
	*/ 
	public function searchNotInDepartmentUser($search)
	{
		
		// 获取已经被部门录入的用户id 支部书记/教导员除外
		$departmentRelUserModelObj = new \VirgoModel\DepartmentRelUserModel;
		$notInIds = $departmentRelUserModelObj->getUsedNormalUserIds();

		$notInIds[] = 1;

		return $this->userObj->where("is_deleted", 0)
							->where("user_login", 'like', "%".$search."%")
							->whereNotIn("id", $notInIds)
					  ->select("id", "user_login", "name")
					  ->get()
					  ->toArray();
	}

	/**
	* 根据id获取用户审批身份，角色的字典
	* @author 	xww
	* @param 	int/string 		$uid
	* @return 	array
	*/ 
	public function getUserApprovalRoles($uid)
	{
		$return = [];
		$data = \EloquentModel\RoleToUser::leftJoin("users", "users.id", '=', "rel_role_to_user.user_id")
	 								->leftJoin("sys_roles", "sys_roles.id", "=", "rel_role_to_user.role_id")
	 								->where("users.is_deleted", 0)
	 								->where("sys_roles.deleted", 0)
	 								->where("rel_role_to_user.deleted", 0)
	 								->where("users.id", $uid)
	 								->whereIn("sys_roles.type_id", ['2101', '2102', '2103', '2105', '2108', '2109'])
	 								->orderBy("sys_roles.type_id", "desc")
	 								->select("sys_roles.type_id")
	 								->get()
	 								->toArray();
	 								
	 	for ($i=0; $i < count($data); $i++) { 
	 		$return[] = $data[$i]['type_id'];
	 	}

	 	return $return;

	}

	/**
	* 获取用户回答问题列表
	* @author 	xww
	* @param 	int/string 		$masterId 	师傅id
	* @param 	int/string 		$start 		开始偏移
	* @param 	int/string 		$size 		获取记录条数
	* @param 	string 			$username 	进行用户搜索的用户名
	* @param 	string 			$statusId 	进行状态搜索的评论状态
	* @return 	obj
	*/
	public function getUserAskQuestionLists($masterId, $skip, $take, $username=null, $statusId=null)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query = \EloquentModel\AskMasterQuestion::leftJoin("users", "users.id", "=", "ask_master_question.user_id")
											    ->leftJoin("comment_ask_master_question", "comment_ask_master_question.ask_question_id", '=', "ask_master_question.id")
											    ->where("ask_master_question.is_deleted", 0)
											    ->where("ask_master_question.master_id", $masterId)
											    ->orderBy("ask_master_question.created_time", "desc");

		// 查询用户
		if(!is_null($username) && is_string($username)) {
			$query = $query->where("users.name", "like", '%'.$username.'%');
			$pageObj->setPageQuery(['username'=>$username]);
		}

		// 查询是否有回答
		if(!is_null($statusId) && is_numeric($statusId)) {
			$pageObj->setPageQuery(['statusId'=>$statusId]);

			if( $statusId == 1 ) {
				$query = $query->whereNotNull("comment_ask_master_question.is_deleted");
			} else if( $statusId == 2 ){
				$query = $query->whereNull("comment_ask_master_question.is_deleted");
			}
		}

		$totalCount = count( $query->get()->toArray() );
		$data = $query->skip($skip)
			  ->take($take)
			  ->select("users.name", "ask_master_question.*", "comment_ask_master_question.is_deleted as statusIsDeleted")
			  ->get()
			  ->toArray();


		//设置页数跳转地址
		$pageObj->setUrl( '/admin/user/askMasterQuestions/lists' );

		// 设置分页数据
		$pageObj->setData( $data );

		// 设置记录总数
		$pageObj->setTotalCount( $totalCount );

		// 设置分页大小
		$pageObj->setSize($take);

		// 进行分页并返回
		return $pageObj->doPage();


	}

	/**
	* 根据名称查询用户
	* @author 	xww
	* @param 	string 		$name 	
	* @return 	array
	*/ 
	public function getUserFromName( $name )
	{
		return $this->userObj->where("is_deleted", 0)->where("name", $name)->first();
	}

	/**
	* 数据更新
	* @author 	xww
	* @param 	int/string 		$uid
	* @param 	array 			$data
	* @return 	affect rows
	*/
	public function partUpdate($uid, $data)
	{
		return $this->userObj->where("id", $uid)->update($data);
	}

	/**
	* 获取监狱专业导师列表  包括他们自己会的课程
	* @author 	xww
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	array
	*/
	public function getMajorUserLists($skip=null, $size=null)
	{
		
		$query = \EloquentModel\RoleToUser::leftJoin("users", "users.id", '=', "rel_role_to_user.user_id")
								 ->where("users.is_deleted", 0)
								 ->where("rel_role_to_user.deleted", 0)
								 ->where("rel_role_to_user.role_id", 11)
								 ->select("users.id", "users.name", "users.avatar", "users.gender")
								 ->groupBy("users.id", "users.name", "users.avatar", "users.gender")
								 ->orderBy("users.id", "desc");

		if( !is_null( $skip ) && !is_null( $size ) ) {
			$query = $query->skip( $skip )->take( $size );
		}

		$data = $query->get()->toArray();

		if( !empty($data) ) {

			$ids = [];
			for ($i=0; $i < count($data); $i++) { 
				$data[$i]['avatar'] = empty( $data[$i]['avatar'] )? '/images/avatar.png':$data[$i]['avatar'];
				$data[$i]['courseStr'] = '';
				$ids[] = $data[$i]['id'];
			}

			// 查询用户的擅长课程
			$courses = \EloquentModel\TeachCourse::leftJoin("course", "course.id", "=", "teach_course.course_id")
						 ->where("course.is_deleted", 0)
						 ->where("teach_course.is_deleted", 0)
						 ->whereIn("teach_course.user_id", $ids)
						 ->select("teach_course.user_id", "course.title")
						 ->orderBy("teach_course.user_id", "asc")
						 ->orderBy("teach_course.create_time", "desc")
						 ->orderBy("teach_course.id", "desc")
						 ->get()
						 ->toArray();

			$newCourses = [];
			for ($i=0; $i < count($courses); $i++) { 

				if( !isset($newCourses[ $courses[$i]['user_id'] ]) ) {
					$newCourses[ $courses[$i]['user_id'] ] = [];
				}
				$newCourses[ $courses[$i]['user_id'] ][] = $courses[$i]['title'];
			}

			for ($i=0; $i < count($data); $i++) { 
				
				if( isset( $newCourses[ $data[$i]['id'] ] ) ) {
					$data[$i]['courseStr'] = implode("|", $newCourses[ $data[$i]['id'] ]);
				}

			}
			
		}// end if

		return $data;

	}

	/**
	* 获取监狱专业导师列表--的数量
	* @author 	xww
	* @return 	array
	*/
	public function getMajorUserListsCount()
	{
		
		return \EloquentModel\RoleToUser::leftJoin("users", "users.id", '=', "rel_role_to_user.user_id")
								 ->where("users.is_deleted", 0)
								 ->where("rel_role_to_user.deleted", 0)
								 ->where("rel_role_to_user.role_id", 11)
								 ->count();

	}

	/**
	* 获取用户所属部门
	* @author 	xww
	* @param 	int/string 		$uid
	* @return 	string
	*/
	public function getUserDepartment($uid)
	{
		
		$data = \EloquentModel\DepartmentRelUser::leftJoin("departments", "departments.id", '=', 'department_rel_user.department_id')
										->where("departments.is_deleted", 0)
										->where("department_rel_user.is_deleted", 0)
										->where("department_rel_user.user_id", $uid)
										->select("departments.name")
										->first();

		return empty( $data )? '':$data['name'];

	}

	/**
	* 根据名称和警号查询用户
	* @author 	xww
	* @param 	string 		$name 	
	* @param 	string 		$policeNum 	
	* @return 	array
	*/ 
	public function getUserFromNameAndPoliceNum( $name , $policeNum)
	{
		return $this->userObj->where("is_deleted", 0)->where("name", $name)->where("police_num", $policeNum)->first();
	}

	/**
	* 获取所在部门上级部门 (指定下属一层) 且拥有指定角色的用户id
	* @author 	xww
	* @param 	int/string 		$departmentId
	* @return 	array
	*/
	public function getFloatLeaderApprover($departmentId, $roleTypeId)
	{

		// 获取该部门id记录
		$curDeparment = \EloquentModel\Department::where("is_deleted", 0)->find($departmentId);

		if( empty($curDeparment) ) {
			return [];
		}

		if( $curDeparment['p_department_id']!=0 ) {
			$departmentId = $curDeparment['p_department_id'];
		}

		$returnIds = [];
		// 获取所有该部门的一层下属部门
		$subDepartments = \EloquentModel\Department::where("is_deleted", 0)
								 ->where("p_department_id", $departmentId)
								 ->select("id")
								 ->get()
								 ->toArray();

		$ids = [];
		for ($i=0; $i < count($subDepartments); $i++) { 
			$ids[] = $subDepartments[$i]['id'];
		}

		if( !empty($ids) ) {

			$users = \EloquentModel\DepartmentRelUser::leftJoin("departments", "departments.id", '=', 'department_rel_user.department_id')	
											->leftJoin("users", "users.id", '=', "department_rel_user.user_id")
											->leftJoin("rel_role_to_user", "rel_role_to_user.user_id", '=', "department_rel_user.user_id")
											->leftJoin("sys_roles", "sys_roles.id", '=', "rel_role_to_user.role_id")
											->where("departments.is_deleted", 0)
											->whereIn("departments.id", $ids)
											->where("department_rel_user.is_deleted", 0)
											->where("users.is_deleted", 0)
									        ->where("rel_role_to_user.deleted", 0)
									        ->where("rel_role_to_user.deleted", 0)
									        ->where("sys_roles.deleted", 0)
									        ->where("sys_roles.type_id", $roleTypeId)
									        ->select("users.id")
									        ->get()
									        ->toArray();

			for ($i=0; $i < count($users); $i++) { 
				array_push($returnIds, $users[$i]['id']);
			}

		}

		return $returnIds;
		
	}
	
	
	/**
	 * 更新
	 * @author 	xww
	 * @param 	int/string    	$id
	 * @param 	array    		$data
	 * @return 	int
	 */
	/* public function departmentUpdate($id, $data)
	{
		return $this->_model->where("id", $id)->update($data);
	} */
	
	/**
	* 根据指定的ids获取用户
	* @author 	xww
	* @param 	array 	$ids
	* @return 	array
	*/
	public function getUserWithIds($ids)
	{
		return $this->userObj->where("is_deleted", 0)
							 ->whereIn("id", $ids)
							 ->get()
							 ->toArray();
	}

	/**
	* 获取所在部门上级部门 (指定下属一层) 且拥有指定角色的用户id
	* @author 	xww
	* @param 	int/string 		$departmentId
	* @return 	array
	*/
	public function getFloatLeaderApproverWithArr($departmentId, $roleTypeId)
	{

		// 获取该部门id记录
		$curDeparment = \EloquentModel\Department::where("is_deleted", 0)->find($departmentId);

		if( empty($curDeparment) ) {
			return [];
		}

		if( $curDeparment['p_department_id']!=0 ) {
			$departmentId = $curDeparment['p_department_id'];
		}

		$returnUsers = [];
		// 获取所有该部门的一层下属部门
		$subDepartments = \EloquentModel\Department::where("is_deleted", 0)
								 ->where("p_department_id", $departmentId)
								 ->select("id")
								 ->get()
								 ->toArray();

		$ids = [];
		for ($i=0; $i < count($subDepartments); $i++) { 
			$ids[] = $subDepartments[$i]['id'];
		}

		if( !empty($ids) ) {

			$users = \EloquentModel\DepartmentRelUser::leftJoin("departments", "departments.id", '=', 'department_rel_user.department_id')	
											->leftJoin("users", "users.id", '=', "department_rel_user.user_id")
											->leftJoin("rel_role_to_user", "rel_role_to_user.user_id", '=', "department_rel_user.user_id")
											->leftJoin("sys_roles", "sys_roles.id", '=', "rel_role_to_user.role_id")
											->where("departments.is_deleted", 0)
											->whereIn("departments.id", $ids)
											->where("department_rel_user.is_deleted", 0)
											->where("users.is_deleted", 0)
									        ->where("rel_role_to_user.deleted", 0)
									        ->where("rel_role_to_user.deleted", 0)
									        ->where("sys_roles.deleted", 0)
									        ->whereIn("sys_roles.type_id", $roleTypeId)
									        ->select("users.id", "sys_roles.type_id")
									        ->get()
									        ->toArray();

			// for ($i=0; $i < count($users); $i++) { 
			// 	array_push($returnIds, $users[$i]['id']);
			// }

			$returnUsers = $users;

		}

		return $returnUsers;
		
	}

	/**
	* 用户是否有给定的后台数据操作权限
	* @author 	xww
	* @param 	int/string 		$uid
	* @param 	array			$operateIds
	* @return 	bool
	*/
	public function hasBackCreateOperatePrivilige($uid, $operateIds=[])
	{
		
		/*id=1的账号为所有权限账号  不进行判断*/
		if( $uid==1 ) {
			return true;
		}
		
		$count = \EloquentModel\RoleToUser::leftJoin("operate_privilege_to_role", "operate_privilege_to_role.role_id", "=", "rel_role_to_user.role_id")
								 ->leftJoin("users", "users.id", '=', 'rel_role_to_user.user_id')
								 ->where("rel_role_to_user.deleted", 0)
								 ->where("operate_privilege_to_role.deleted", 0)
								 ->where("users.is_deleted", 0)
								 ->where("users.id", $uid)
								 ->whereIn("operate_privilege_to_role.operate_id", $operateIds)
								 ->count();

		if( $count< count($operateIds) ) {
			return false;
		}

		return true;

	}

	/**
	* 获取列表对象
	* @author 	xww
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @param 	array 			$searchArr    搜索条件
	* @return 	object
	*/
	public function getListsObject($params=[])
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query =  $this->userObj->leftJoin("department_rel_user", "department_rel_user.user_id", "=", "users.id")

							  ->leftJoin("departments", "departments.id", "=", "department_rel_user.department_id")
							  ->where("users.is_deleted", 0)
							  	// ->where("department_rel_user.is_deleted", "!=", 1)
							 
					  		  ->select('users.id',"users.name", "user_login", "password", DB::raw(" group_concat(`comp_departments`.name separator ',') as departmentName"),DB::raw(" group_concat(`comp_departments`.id separator ',') as departmentid"))
					  		  ->where("users.id", "<>", 1)
					  		  ->groupBy('users.id',"users.name", "user_login", "password");

	if( !empty( $params['name'] ) ) {
			$query = $query->where("users.name", "like", "%" . $params['name'] . "%");
		}





		// 父菜单总记录数
		$totalCount = count( $query->get()->toArray() );

if( !empty( $params['size'] ) ) {
			$query = $query->skip( $params['skip'] )->take( $params['size'] );	
		}
		// 获取记录
			$data = $query->get()->toArray();


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

	public function getuserLists($params=[])
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query = $this->userObj->leftJoin("department_rel_user", "department_rel_user.user_id", "=", "users.id")

							  ->leftJoin("departments", "departments.id", "=", "department_rel_user.department_id")
							  ->where("users.is_deleted", 0)
							  	// ->where("department_rel_user.is_deleted", 0)
							 
					  		  ->select('users.id',"users.name", "user_login", "password", DB::raw(" group_concat(`comp_departments`.name separator ',') as departmentName"),DB::raw(" group_concat(`comp_departments`.id separator ',') as departmentid"))
					  		  // ->where("users.id", "<>", 1)
					  		  ->groupBy('users.id',"users.name", "user_login", "password");

		if( !empty( $params['name'] ) ) {
			$query = $query->where("users.name", "like", "%" . $params['name'] . "%");
		}

		$totalCountQuery = $query;

		// 父菜单总记录数
		$totalCount = $totalCountQuery->count();

		if( !empty( $params['size'] ) ) {
			$query = $query->skip( $params['skip'] )->take( $params['size'] );	
		}

		// 获取记录
		$data = $query->get()->toArray();

		$url = "";
		if( !empty( $params['url'] ) ) {
			$url = $params['url'];
		}

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
	/*添加记录*/
	public function create($data)
	{
		return $this->userObj->insertGetId($data);
	}

	/**
	* 根据账号获取用户
	* @author 	xww
	* @param 	string 		$username
	* @return 	array
	*/
	public function getRecordByAccount( $username )
	{
		return $this->userObj->where("is_deleted", 0)->where("user_login", $username)->take(1)->get()->toArray();
	}

	/**
	* 获取用户详情
	* @param 	int/string 		$uid
	* @return 	array
	*/
	public function getUserInfo( $uid )
	{

		// 用户对象
		$dataObj = $this->userObj->find($uid);

		if( empty($dataObj) ) {
			return null;
		}

		$data = $dataObj->toArray();

		unset( $data['is_deleted'] );
		unset( $data['password'] );
		unset( $data['access_token'] );
		unset( $data['token_expire_time'] );
		unset( $data['create_time'] );
		unset( $data['update_time'] );

		$data['roles'] = [];

		$data['departments'] = [];

		// 获取角色列表中用户拥有的角色
		$roleUserModel = new \VirgoModel\RoleToUserModel;
		$data['roles'] = $roleUserModel->getUserRolesInAll( $data['id'] );

		// 获取部门列表中用户拥有的部门
		$departmentUserModel = new \VirgoModel\DepartmentRelUserModel;
		$data['departments'] = $departmentUserModel->getUserDepartmentsInAll( $data['id'] );

		$departmentsArr = $departmentUserModel->getUserDepartments( $data['id'] );
		if( empty($departmentsArr) ) {
			$departmentsIds = null;
		} else {
			$departmentsIds = [];
			for ($i=0; $i < count($departmentsArr); $i++) { 
				$departmentsIds[] = $departmentsArr[$i]['id'];
			}
		}
		$data['departmentsIds'] = $departmentsIds;

		// $data['roles'] = empty($data['roles'])? null:$data['roles'];
		$data['departments'] = empty($data['departments'])? null:$data['departments'];
		
		
		
		return $data;
	}

	/**
	* 多数据更新
	* @author 	xww
	* @param 	array			$ids
	* @param 	array 			$data
	* @return 	affect rows
	*/
	public function multiplePartUpdate($ids, $data)
	{
		return $this->userObj->whereIn("id", $ids)->update($data);
	}
	
	/**
	* 获取即时聊天用户列表--附带搜索用户
	* @author　	xww
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @param 	string 			$search
	* @return 	array
	*/
	public function getAllChatUserLists($skip=null, $size=null, $search=null)
	{

		$query =  $this->userObj->leftJoin("department_rel_user", function($q){
							 		$q->on('department_rel_user.user_id', "=", 'users.id')
			                          ->where('department_rel_user.is_deleted', "=", 0);
								})
		                        ->leftJoin("departments", function($q){
		                        	$q->on("departments.id", "=", "department_rel_user.department_id")
		                        	  ->where("departments.is_deleted",  "=", 0);
		                        })
		                        ->select("users.id", "users.name", "users.avatar", "users.user_login as userLogin", DB::raw(' IFNULL( group_concat(comp_departments.name), "") as departmentName') )
		                        ->where("users.id", "<>", 1)
		                        ->where("users.is_deleted", "=", 0)
		                        ->orderBy("users.create_time", 'desc')
		                        ->orderBy("users.id", 'desc')
		                        ->groupBy("users.id");

		 if( !empty( $params['name'] ) ) {
			$query = $query->where("name", "like", "%" . $params['name'] . "%");
		}

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}
		
		// 搜索姓名
		if( !is_null($search) ) {
			$query = $query->where("users.name", "like", "%" . $search . "%");
		}

		return $query->get()->toArray();

	}

	/**
	* 获取全部用户
	* @author 	xww
	* @return 	array
	*/
	public function getAllUserLists()
	{
		return $this->userObj->where("is_deleted", 0)->where("id", "<>", 1)->select("id", "name", "phone")->get()->toArray();
	}

	/**
	* 用户详情
	* @author 	xww
	* @param 	int/string 		$uid
	* @return 	array
	*/
	public function readUser($uid)
	{

		$data = $this->userObj
                ->leftJoin("users as b", "b.id", '=', "users.manager_id")
                ->select("users.*", DB::raw(" IFNULL(`comp_b`.name, '') as managerName"), DB::raw(" IFNULL(`comp_b`.phone, '') as managerPhone") )
                ->where("users.id", $uid)
                ->groupBy("users.id")
                ->take(1)
                ->get()
                ->toArray();

        if( !empty($data) ) {
        	$departmentNames = \EloquentModel\DepartmentRelUser::leftJoin("departments", function($q){
							                	$q->on("departments.id", "=", "department_rel_user.department_id")
							                	  ->where("departments.is_deleted",  "=", 0);
							                })
        									->where("user_id", $uid)
        									->where("department_rel_user.is_deleted", 0)
        									->select( DB::raw(' IFNULL( group_concat(comp_departments.name), "") as departmentName') )
        									->groupBy("user_id")
        									->get()
        									->toArray();
        	$data[0]['departmentName'] = empty($departmentNames)? '':$departmentNames[0]['departmentName'];

        	$roleNames = \EloquentModel\RoleToUser::leftJoin("sys_roles", function($q){
													 		$q->on('sys_roles.id', "=", 'rel_role_to_user.role_id')
										                      ->where('sys_roles.deleted', "=", 0);
														})
        									->where("user_id", $uid)
        									->where("rel_role_to_user.deleted", 0)
        									->select( DB::raw(' IFNULL( group_concat(comp_sys_roles.name), "") as roleName') )
        									->groupBy("user_id")
        									->get()
        									->toArray();
        	$data[0]['roleName'] = empty($roleNames)? '':$roleNames[0]['roleName'];

        }
       	
        // 获取用户角色
		$roleToUserModel = new \VirgoModel\RoleToUserModel;
		$rids = $roleToUserModel->getUserRoleIds( $uid );
		$rids = empty($rids)? null:$rids;
		$data[0]['roles'] = $rids;

		// 获取用户部门
		$roleToDepartmentModel = new \VirgoModel\DepartmentModel;
		$departIds = $roleToDepartmentModel->getUserDepartmentId( $uid );
		$departIds = empty($departIds)? null:$departIds;
		$data[0]['departmentIds'] = $departIds;

        return $data[0];

	}

	/**
	* 新建用户--顺便注册环信等信息
	* @author 	xww
	* @param 	string 		$username
	* @return 	int or false
	*/
	public function createUser( $username, $name)
	{

		try{

			DB::beginTransaction();

			// 应对环信账号注册问题
			$username = strtolower($username);

			$functionObj = new \VirgoUtil\Functions;

			// 实例化对象--用户角色对象
			$roleUserModel = new \VirgoModel\RoleToUserModel;

			// 实例化对象--环信对象
			$huanXinUtilObj = new \VirgoUtil\HuanXinUtil;

			$insertData['avatar'] = "/images/default-avatar.png";
			$insertData['name'] = $name;
			$insertData['nickname'] = $functionObj->getNickName('清风明月_');

			/*规则处理*/
			$insertData['password'] = "nciou".md5('123456')."dijdm";
			$insertData['user_login'] = $username;
			$insertData['create_time'] = time();
			$insertData['update_time'] = time();

			$userId = $this->userObj->insertGetId( $insertData );
			// unset($insertData);

			if( !$userId ) {
				throw new \Exception("添加用户失败", '005');
			}

			/*默认插入负责人(班组组长)角色*/
			$roles = "5";
			$roleIds = explode(",", $roles);
			$data = [];
			for ($i=0; $i < count($roleIds); $i++) { 

				$roleId = (int)$roleIds[$i];
				if( !$roleId ) {
					continue;
				}

				$temp = [];
				$temp['role_id'] = $roleId;
				$temp['user_id'] = $userId;

				$data[] = $temp;

			}

			if( !empty($data) ) {
				$rs = $roleUserModel->multiCreate($data);

				if( !$rs ) {
					throw new \Exception("添加用户角色失败", '005');			
				} 

			}

			// 测试不开启环信
			/*环信注册模块*/
			$huanXinConfigs = $GLOBALS['globalConfigs']['huanXin'];
			$huanXinUtilObj->setProperties($huanXinConfigs);

			// get 环信token
			$token = $huanXinUtilObj->getToken();

			// 获取用户单个
			$rs = $huanXinUtilObj->getUser($insertData['user_login'], $token);

			//解析
			$headersArr = explode("\r\n", $rs['header']);
			$LineOneArr = explode(" ", $headersArr[0]);

			if($LineOneArr[1]!='200' && $LineOneArr[1]!="404"){
				throw new \Exception("环信获取用户失败, code: " . $LineOneArr[1], '095');
			}

			if($LineOneArr[1]=="200") {
				// 删除用户
				$rs = $huanXinUtilObj->deleteUser($insertData['user_login'], $token);
				$headersArr = explode("\r\n", $rs['header']);
				$LineOneArr = explode(" ", $headersArr[0]);
				if($LineOneArr[1]!='200'){
					throw new \Exception("环信删除用户失败, code: " . $LineOneArr[1], '095');
				}
			}

			// 进行用户注册
			$rs = $huanXinUtilObj->registerUser($insertData['user_login'], $insertData['password'], $insertData['nickname']);
			$headersArr = explode("\r\n", $rs['header']);
			$LineOneArr = explode(" ", $headersArr[0]);

			if($LineOneArr[1]!='200'){
				throw new \Exception("环信注册用户失败, code: " . $LineOneArr[1], '095');
			}

			DB::commit();
			return $userId;

		} catch(\Exception $e) {
			DB::rollback();
			return false;
		}
		
	}


	public function getListsuser($skip, $size)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query =  $this->userObj->leftJoin("department_rel_user", function($q){
							 		$q->on('department_rel_user.user_id', "=", 'users.id')
			                          ->where('department_rel_user.is_deleted', "=", 0);
								})
		                        ->leftJoin("users as b", "b.id", '=', 'users.manager_id')
		                        ->leftJoin("departments", function($q){
		                        	$q->on("departments.id", "=", "department_rel_user.department_id")
		                        	  ->where("departments.is_deleted",  "=", 0);
		                        })
		                        ->select("users.id", "users.name", "users.avatar", "users.user_login as userLogin", "users.create_time as createTime", DB::raw(' IFNULL( group_concat(comp_departments.name separator "," ), "") as departmentName'), DB::raw(" ifnull(`comp_users`.phone, '') as phone"),DB::raw(" ifnull(`comp_b`.name, '') as managerName"), DB::raw(" ifnull(`comp_b`.phone, '') as managerPhone")  )
		                        ->where("users.id", "<>", 1)
		                        ->where("users.is_deleted", "=", 0)
		                        ->orderBy("users.create_time", 'desc')
		                        ->groupBy("users.id");


		// 父菜单总记录数
		$totalCount = count( $query->get()->toArray() );
var_dump($totalCount);
die;
		// 获取记录
		$data = $query->skip($skip)->take($size)->get()->toArray();

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

	public function getaaa($id)
	{

	
		$data = $this->userObj
		                      ->leftJoin("department_rel_user", "department_rel_user.user_id", "=", "users.id")
		                       ->leftJoin("departments", "departments.id", "=", "department_rel_user.department_id")
		                      ->where("users.is_deleted", 0)
		                      ->where("department_rel_user.is_deleted", 0)
					  		  ->where("users.id", $id)
					  		  ->select("users.name", "users.user_login", "users.password","users.password_",'roleid','users.create_time',
					  		  	DB::raw(" group_concat(`comp_departments`.name separator ',') as departmentName")
					  		  	,DB::raw(" group_concat(`comp_departments`.id separator ',') as departmentIds"))
					  		  ->groupBy("users.id" )

					  		  ->get()
					  		  ->toArray();
					  return $data;
		
		}

		/**
	* 获取用户具备的角色id
	* @author 	xww
	* @param 	int/string  	$uid
	* @return 	array
	*/
	public function getUserIds( $uid )
	{

		$ids = [];
		$data = $this->userObj->where("is_deleted", 0)->where('id',$uid)->get()->toArray();
		for ($i=0; $i < count( $data ); $i++) { 
			$ids[] = $data[$i]['roleid'];
		}

		return 	$ids;
		
	}

}