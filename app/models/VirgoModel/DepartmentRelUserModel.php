<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
class DepartmentRelUserModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\DepartmentRelUser; 
	}

	/**
	* 列表
	* @author xww
	*@return object
	*/
	public function lists()
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;
		// set query 
		$query = $this->_model->where("is_deleted", '=', 0)->orderBy("create_time", "desc");

		
		// 父菜单总记录数
		$totalCount = count($query->get()->toArray());
		//分页的take,size
		$size = 10;
		if(!empty($_GET['page'])){
			$page = (int)$_GET['page'];
			$skip = ($page-1)*$size;
		} else {
			$skip = 0;
		}
		// 获取记录
		$data = $query->skip($skip)->take($size)->get()->toArray();
		//设置页数跳转地址
		$pageObj->setUrl('/admin/departmentRelUsers');
		// 设置分页数据
		$pageObj->setData($data);
		// 设置记录总数
		$pageObj->setTotalCount($totalCount);
		// 设置分页大小
		$pageObj->setSize($size);
		// 进行分页并返回
		return $pageObj->doPage();
	}
	
	
	
	
	
	/**全部部门
	 * 
	 * bgl
	 *  部门
	 */
	public function departmentAllOne($id)
	{
		return $this->_model->where("user_id", $id)
						    ->where("is_deleted", 0)
						    ->get()
						    ->toArray();
	}
	
	
	/**
	* 逻辑增加
	* @author xww
	* @return sql result
	*/
	public function doCreate()
	{
		unset($_POST['id']);
		unset($_POST['coverPath']);
		unset($_POST['page']);
		// 上传文件
		if(!empty($_FILES['cover']) && $_FILES['cover']['error']==0){
			$ext = str_replace('image/', '', $_FILES['cover']['type']);
			$fpath = '/upload/product/'.microtime(true).".".$ext;
			$rs = move_uploaded_file($_FILES['cover']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$fpath);
			if($rs){
				$_POST['cover'] = $fpath;
			}
		}
		// 创建时间
		$_POST['create_time'] = time();
		// 修改时间
		$_POST['update_time'] = time();
		return $this->_model->insert($_POST);
	}
	/**
	* 返回对应id数据
	* @param  $id  string/int    会话id
	* @author xww
	* @return object
	*/
	public function read($id)
	{
		return $this->_model->where("is_deleted", '=', 0)->find($id);
	}
	/**
	* 逻辑修改
	* @author xww
	* @return sql result
	*/
	public function doUpdate()
	{
		$id = $_POST['id'];
		unset($_POST['id']);
		unset($_POST['coverPath']);
		unset($_POST['page']);
		// 上传文件
		if(!empty($_FILES['cover']) && $_FILES['cover']['error']==0){
			$ext = str_replace('image/', '', $_FILES['cover']['type']);
			$fpath = '/upload/product/'.microtime(true).".".$ext;
			$rs = move_uploaded_file($_FILES['cover']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$fpath);
			if($rs){
				$_POST['cover'] = $fpath;
			}
		}
		// 修改时间
		$_POST['update_time'] = time();
		// 更新
		return $this->_model->where("id", '=', $id)->update($_POST);
	}
	/**
	* 逻辑删除
	* @author xww
	* @return sql result
	*/
	public function delete()
	{
		$data['is_deleted'] = 1;
		if($_POST){$ids = $_POST['ids'];}
		else{$ids = [$_GET['id']];}
		return $this->_model->whereIn("id", $ids)->update($data);
	}

	
	/**
	* 查询该部门拥有的用户
	* @author 	xww
	* @param 	int/string 	$id
	* @return 	array
	*/
	public function getDepartmentUsers($id)
	{
		return $this->_model->leftJoin("departments", "department_rel_user.department_id", '=', "departments.id")
							->leftJoin("users", "department_rel_user.user_id", '=', "users.id")
							->where("department_rel_user.is_deleted", 0)
							->where("departments.is_deleted", 0)
							->where("users.is_deleted", 0)
							->where("department_rel_user.department_id", $id)
							->select("users.name", "department_rel_user.is_leader", "department_rel_user.update_time", "department_rel_user.id")
							->get()
							->toArray();
	}

	/**
	* 是否已经存在关联记录
	* @author 	xww
	* @param 	int/string 	$departmentId
	* @param 	int/string 	$userId
	* @return 	bool
	*/
	public function hasRel($departmentId, $userId)
	{
		$count = $this->_model->where("department_id", $departmentId)
				     ->where("user_id", $userId)
				     ->where("is_deleted", 0)
				     ->count();

		return $count? true:false;

	}

	/**
	* 是否已经存在用户关联记录
	* @author 	xww
	* @param 	int/string 	$departmentId
	* @param 	int/string 	$userId
	* @return 	bool
	*/
	public function hasUserRel($userId)
	{
		$count = $this->_model->where("user_id", $userId)
				     ->where("is_deleted", 0)
				     ->count();

		return $count? true:false;
		
	}

	/**
	* 创建记录
	* @author 	xww
	* @param 	array 	$data
	* @return 	insert id
	*/
	public function create($data)
	{
		return $this->_model->insertGetId($data);
	}

	/**
	* 部门用户更新记录
	* @author 	bgl
	* @param 	int/string 	$id
	* @param 	array 	$data
	* @return 	insert id
	*/
	public function updatePart($id, $data)
	{
		return $this->_model->where("id", $id)->update($data);
	}

	/**
	* 获取关联部门用户
	* @author 	xww
	* @param 	string/int 		$id
	* @return 	array
	*/
	public function getRelUser($id)
	{
		return $this->_model->leftJoin("users", "department_rel_user.user_id", '=', "users.id")
							->where("department_rel_user.is_deleted", 0)
							->where("users.is_deleted", 0)
							->select("users.name", "department_rel_user.*")
							->where("department_rel_user.id", "=", $id)
							->first();
	}

	/**
	* 获取已经被使用的用户id数组
	* @author 	xww
	* @return 	array
	*/
	public function getUsedUserIds()
	{
		$return = [];
		$data = $this->_model->where("is_deleted", 0)
					 ->select("user_id")
					 ->get()
					 ->toArray();

		for ($i=0; $i < count($data); $i++) { 
			$return[] = $data[$i]['user_id'];
		}

		return $return;
		
	}

	/**
	* 获取已经被使用的民警用户id数组
	* @author 	xww
	* @return 	array
	*/
	public function getUsedNormalUserIds()
	{
		
		// 由于教导员和支部书记能多次出现 所以过滤的时候要剔除有该身份角色用户
		$users = \EloquentModel\RoleToUser::where("deleted", 0)
								 ->whereIn("role_id", [7, 13])
								 ->select("user_id")
								 ->groupBy("user_id")
								 ->get()
								 ->toArray();

		$notInIds = [];
		if( !empty($users) ) {			
			for ($i=0; $i < count($users); $i++) { 
				$notInIds[] = $users[$i]['user_id'];
			}
		}

		$return = [];
		$data = $this->_model->where("is_deleted", 0)
					 ->select("user_id")
					 ->get()
					 ->toArray();

		for ($i=0; $i < count($data); $i++) { 
			if( in_array($data[$i]['user_id'], $notInIds) ) {
				continue;
			}
			$return[] = $data[$i]['user_id'];
		}

		return $return;
		
	}
	
	
	/**
	 * 更新
	 * @author 	bgl
	 * @param 	int/string    	$id
	 * @param 	array    		$data
	 * @return 	int
	 */
	public function departmentUpdate($id, $data)
	{
		return $this->_model->where("id", $id)->update($data);
	}
	
	/**
	 * 移除部门
	 * @author 	bgl
	 * @param 	int/string    $uid
	 * @return 	int
	 */
	public function removeUserDepartment($uid)
	{
		
		return $this->_model->where("user_id", $uid)
		->delete(['is_deleted'=>1]);
		
	}
	
	/**
	* 查询指定部门拥有的用户
	* @author 	xww
	* @param 	array 	$ids
	* @return 	array
	*/
	public function getDepartmentsUsers($ids)
	{
		return $this->_model->leftJoin("departments", "department_rel_user.department_id", '=', "departments.id")
							->leftJoin("users", "department_rel_user.user_id", '=', "users.id")
							->where("department_rel_user.is_deleted", 0)
							->where("departments.is_deleted", 0)
							->where("users.is_deleted", 0)
							->whereIn("department_rel_user.department_id", $ids)
							->select("users.name", "users.id")
							->get()
							->toArray();
	}

	/**
	* 获取指定部门ids的用户ids
	* @author 		xww
	* @param 		array     $pids
	* @return 		array
	*/
	public function getUserIdsFromDepartmentIds( $pids )
	{
		
		$ids = [];

		$data = $this->_model->whereIn("department_id", $pids)
							->where("is_deleted", 0)
							->select("user_id")
							->get()
							->toArray();

		for ($i=0; $i < count($data); $i++) { 
			$ids[] = $data[$i]['user_id'];
		}

		return $ids;

	}
	
	/**
	*
	* 获取指定部门的用户数量
	* @author 	xww
	* @param 	array 		$pids   	部门ids
	* @return 	int
	*/
	public function getDepartmentUsersCount($pids)
	{
		return $this->_model->whereIn("department_id", $pids)
				     		->where("is_deleted", 0)
				     		->count();
	}

	/**
	* 获取列表对象
	* @author 	xww
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	object
	*/
	public function getListsObject($departmentId, $skip=null, $size=null)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query = $this->_model->leftJoin("users", "users.id", '=', "department_rel_user.user_id")
						 	  ->where("department_rel_user.is_deleted", 0)
						 	  ->where("department_rel_user.department_id", $departmentId)
						 	  ->where("users.is_deleted", 0)
						 	  ->select("department_rel_user.id as relId", "users.name");

		// 父菜单总记录数
		$totalCount = count( $query->get()->toArray() );

		// 获取记录

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}
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

	/**
	* 批量插入
	* @author 	xww
	* @param 	array 	$data
	* @return 	affect rows
	*/
	public function multiCreate($data)
	{
		return $this->_model->insert($data);
	}

	/**
	* 获取所有部门中 用户拥有的部门
	* @param 	int/string 		$uid
	* @return 	array
	*/
	public function getUserDepartmentsInAll($uid)
	{
		
		$lists = \EloquentModel\Department::select('id', "name", "p_department_id as parentid")
						    ->where('is_deleted', '=', 0)
						    ->groupBy('id')
							->orderBy('p_department_id', 'asc')
						    ->orderBy('id', 'asc')
							->get()
							->toArray();

		

		if( empty($lists) ) {
			return null;
		}

		$temp = $this->_model->where("is_deleted", 0)
		            ->where("user_id", $uid)
		            ->select("department_id")
		            ->get()
		            ->toArray();

		for ($i=0; $i < count($temp); $i++) { 
			$data[ $temp[$i]['department_id'] ] = $temp[$i];
		}

		for ($i=0; $i < count($lists); $i++) { 
			$lists[$i]['checked'] = false;

			if( isset( $data[ $lists[$i]['id'] ] ) ) {
				$lists[$i]['checked'] = true;
			}

		}

		$model = new \VirgoModel\DepartmentModel;

		return $model->getDepartmentsLists($lists, 0);

	}
 
 public function departmentrelUpdate($uid, $data)
	{
		return $this->_model->where("user_id", $uid)->update($data);
	}
	/**
	* 查询用户的部门列表
	* @author 	xww
	* @param 	int/string 		$uid
	* @param 	string 			$name
	* @return 	array
	*/
	public function getUserDepartments($uid, $name=null)
	{

		$query = $this->_model->leftJoin("departments", "department_rel_user.department_id", '=', "departments.id")
							->where("department_rel_user.is_deleted", 0)
							->where("departments.is_deleted", 0)
							->where("department_rel_user.user_id", $uid)
							->select("departments.id", "departments.name");

		if( !is_null($name) ) {
			$query = $query->where("departments.name", "like", $name);
		}

		return $query->groupBy("departments.id", "departments.name")
					 ->get()
					 ->toArray();

	}

}
?>