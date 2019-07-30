<?php
namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class DepartmentModel {
	protected $departmentObj = '';

	/*判断已经获取的菜单的分页数量*/
	private $readyTake = 0;

	/*判断已经跳过的菜单的分页数量*/
	private $readySkip = 0;

	/*要跳过的菜单的分页数量*/
	private $skip = 0;

	/*分页数量*/
	private $data = [];

	public function __construct()
	{
		$this->departmentObj = new \EloquentModel\Department;
	}

	public function lists($need=[],$condition=[], $kv=false)
	{
		if(!empty($need)){
			foreach ($need as $key => $value) {
				$this->departmentObj = $this->departmentObj->addSelect($value);
			}
		}

		if(!empty($condition)){
			foreach ($condition as $k => $v) {
				$this->departmentObj = $this->departmentObj->where($k, $v[0], $v[1]);
			}
		}

		$data = $this->departmentObj->get();
	
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

	public function doCreate()
	{
		unset($_POST['id']);
		unset($_POST['logo']);

		$_POST['create_time'] = time();
		return $this->departmentObj->insert($_POST);

	}

	public function read()
	{
		$id  = $_GET['id'];
		return $this->departmentObj->find($id);
	}

	public function doUpdate()
	{
		$id = $_POST['id'];

		unset($_POST['id']);
		unset($_POST['logo']);

		return $this->departmentObj->where('id',$id)->update($_POST);
	}

	public function doDelete()
	{
		$data['is_deleted'] = 1;
		if($_POST)
			$ids = $_POST['ids'];
		else
			$ids = [$_GET['id']];
		return $this->departmentObj->whereIn('id',$ids)->update($data);
	}

	/**
	* 获取全部部门 名称 id
	* @author 	xww
	* @return 	array
	*/ 
	public function all()
	{
		
		return $this->departmentObj->select("id", "name")
					  ->get()
					  ->toArray();

	}

	/**
	* 分页获取全部部门
	* @author 	xww
	* @return 	array
	*/
	public function getAllMenu($page=1, $size=5)
	{
		
		$page = (int)$page<1? 1:(int)$page;
		$size = (int)$size<0? 0:(int)$size;

		if(empty($page) || empty($size)) {
			throw new \Exception("Wrong Param");
		}

		$query = $this->departmentObj->select("id", 'name', 'update_time')->where("is_deleted", 0);

		// 全部数据
		$totalCount = $query->count();

		// 计算跳过的数据
		// $totalCount = ($totalCount-($page-1)*$size)<=0? 0:($totalCount-($page-1)*$size);

		// 全部页数
		$totalPage = ceil($totalCount/$size);

		// 获取分页数据
		$this->getPageData((int)$page, (int)$size, $totalCount);

		return ['totalPage'=>$totalPage, 'data'=>$this->data, 'curPage'=>$page];

	}

	/**
	* 获取分页数据
	* @author 	xww
	* @param 	int 	$page
	* @param 	int 	$size
	* @return 	array
	*/
	public function getPageData($page, $size, $total)
	{
		
		$page -= 1;
		$skip = $page*$size;
		$this->readySkip = $skip;

		$parents = $this->departmentObj->select("id", 'name', 'update_time')->where("is_deleted", 0)->where("p_department_id", 0)->get()->toArray();

		for ($i=0; $i < count($parents); $i++) { 

			if($this->readyTake<$size && ($this->readyTake+$this->readySkip)<$total) {

				$parents[$i]['level'] = 1;

				if($this->readySkip==0) {
					$this->readyTake += 1;
					$this->data[] = $parents[$i];
				} else {
					$this->readySkip -= 1;
				}

				$this->getInnerData($parents[$i]['id'], $skip, $size, $total);

			}


		}

	}

	/**
	* 获取内部数据
	* @author 	xww
	* @return 	array
	*/
	public function getInnerData($pid, $skip, $take, $total)
	{
		
		while($this->readyTake<$take && ($this->readyTake+$this->readySkip)<$total) {
			$rs =$this->getInnerMenuData($pid, 1, $skip, $take, $total);
			
			if($rs) {
				break;
			}
		}

	}

	/**
	* 获取所属单层子菜单
	*/
	public function getInnerMenuData($pid, $level=0, $skip, $take, $total)
	{
		
		$data = $this->departmentObj->select("id", 'name', 'update_time')->where("is_deleted", 0)->where("p_department_id", $pid)->get()->toArray();

		// var_dump($pid);

		if(empty($data)) {
			return true;
		}

		$level += 1;
		
		while($this->readyTake<$take && ($this->readyTake+$this->readySkip)<$total) {

			for ($i=0; $i < count($data); $i++) { 

				// 持续拿取
				if($this->readySkip>0) {
					$this->readySkip -= 1;
					continue;
				}

				if($this->readyTake<$take && ($this->readyTake+$this->readySkip)<$total) {
					$data[$i]['level'] = $level;

					// var_dump($data[$i]);
					// var_dump($level);

					array_push($this->data, $data[$i]);

					$this->readyTake += 1;
					
					$rs = $this->getInnerMenuData($data[$i]['id'], $level, $skip, $take, $total);
				}

			}

			break;

		}
		
		return true;

	}

	/**
	* 是否有重名部门
	* @author 	xww
	* @param 	string 	$name
	* @return 	bool
	*/
	public function hasExistsName($name)
	{
		$count = $this->departmentObj->where("is_deleted", '=', 0)->where("name", '=', $name)->count();
		return $count? true:false;
	}

	/**
	* 插入数据
	* @author 	xww
	* @param 	array 	$data
	* @return 	int 	insert id
	*/
	public function create($data)
	{
		return $this->departmentObj->insertGetId($data);
	}

	/**
	* 根据id获取记录
	* @author 	xww
	* @param 	int/string 		$id
	* @return 	object
	*/
	public function readSingelTon($id)
	{
		return $this->departmentObj->where("is_deleted", 0)->find($id);
	}

	/**
	* 删除相关菜单
	* @author 	xww
	* @param 	int/string 		$id
	* @return 	void
	*/
	public function deleteRelMenu($id)
	{

		$this->doDeleteRElMenu([$id]);

		// 进行删除
		$updateData['update_time'] = time();
		$updateData['is_deleted'] = 1;

		$this->departmentObj->whereIn("id", [$id])->update($updateData);	

	}

	/**
	* 递归删除
	* @author 	xww
	* @return   void 	
	*/ 
	public function doDeleteRElMenu($ids)
	{
		
		$data = $this->departmentObj->where("is_deleted", 0)->whereIn("p_department_id", $ids)->select("id")->get()->toArray();

		$ids = [];
		for ($i=0; $i < count($data); $i++) { 
			array_push($ids, $data[$i]['id']);
		}

		if(empty($ids)) {
			return true;
		} else {
			$this->doDeleteRElMenu($ids);
		}

		// 进行删除
		$updateData['update_time'] = time();
		$updateData['is_deleted'] = 1;

		$this->departmentObj->whereIn("id", $ids)->update($updateData);		

	}

	/**
	* 获取顶层id下属所有菜单
	* @author 	xww
	* @todo
	* @param 	int/string  	$id
	* @return  	array
	*/
	public function getTopMenu($id, $skip=0, $size=1000)
	{

		if( empty($id) ) {
			return null;
		}

		/*判断已经获取的菜单的分页数量*/
		$this->readyTake = 0;

		/*判断已经跳过的菜单的分页数量*/
		$this->readySkip = 0;

		/*要跳过的菜单的分页数量*/
		$this->skip = $skip;

		/*分页数量*/
		$this->data = [];

		$query = $this->departmentObj->select("id", 'name', 'update_time')->where("is_deleted", 0);

		// 全部数据
		$totalCount = $query->count();

		$singleData = $this->departmentObj->select("id", 'name', 'update_time')->where("is_deleted", 0)->find($id);

		if(!empty($singleData)) {
			$singleData = $singleData->toArray();
			$singleData['level'] = 1;
			$this->readyTake += 1;
			$this->data[] = $singleData;
		} else {
			return null;
		}

		$this->getInnerData($id, $skip, $size, $totalCount);

		$data = $this->data;

		return $data;

	}
	
	/**
	* 更新
	* @author 	xww
	* @param 	int/string 	$id
	* @param 	array 	$data
	* @return 	int 	affect rows
	*/
	public function updateParts($id, $data)
	{
		return $this->departmentObj->where("id", $id)->update($data);
	}

	
	
	/**
	* 查询该部门拥有的用户
	* @author 	xww
	* @param 	int/string 	$id
	* @return 	array
	*/
	public function getDepartmentUsers($id)
	{
		$departmentRelUserModelObj = new \VirgoModel\DepartmentRelUserModel;
		return $departmentRelUserModelObj->getDepartmentUsers($id);
	}

	/**
	* 获取用户所在部门id
	* @author 	xww
	* @param 	int/string 		$uid
	* @return 	array
	*/
	public function getUserDepartment($uid)
	{
		
		$data = \EloquentModel\Department::leftJoin("department_rel_user", "department_rel_user.department_id", '=', "departments.id")
										->where("departments.is_deleted", 0)
										->where("department_rel_user.is_deleted", 0)
										->where("department_rel_user.user_id", $uid)
										->select("department_rel_user.department_id","departments.name")
										->get()
										->toArray();
		/* if(empty($data)) {
			return null;
		}

		for ($i=0; $i < count($data); $i++) { 
			$ids[] = $data[$i]['department_id'];
		} */

		return $data;
		
	}
	
	/**
	 * 更新
	 * @author 	bgl
	 * @param 	int/string    	$id
	 * @param 	array    		$data
	 * @return 	int
	 */
	/* public function departmentUpdate($id, $data)
	{
		return $this->departmentObj->where("id", $id)->update($data);
	} */
	
	/**
	* 获取当前菜单下属一层所有菜单
	* @author 	xww
	* @param 	int/string 		$pid
	* @return 	array
	*/
	public function getChildrensDepartments($pid)
	{

		return $this->departmentObj->where("is_deleted", 0)
									 ->where("p_department_id", $pid)
									 ->select("id", "name")
									->get()
									->toArray();

	}
	
	/**
	* 获取用户所在部门id
	* @author 	xww
	* @param 	int/string 		$uid
	* @return 	array
	*/
	public function getUserDepartmentId($uid)
	{
		
		$data = \EloquentModel\DepartmentRelUser::where("is_deleted", 0)
 										->where("user_id", $uid)
 										->select("department_id")
 										->get()
 										->toArray();
		 if(empty($data)) {
			return null;
		}

		for ($i=0; $i < count($data); $i++) { 
			$ids[] = $data[$i]['department_id'];
		} 

		return $ids;
		
	}

	/**
	* 获取这个部门下属所有部门
	* @author 	xww
	* @return 	int/string 		$pid
	* @return 	array
	*/
	public function getDirectLineDepartments($pid)
	{

		$ids = [];

		$data = $this->departmentObj->where("is_deleted", 0)
									 ->where("p_department_id", $pid)
									 ->select("id", "name")
									 ->get()
									 ->toArray();

		if( !empty($data) ) {

			for ($i=0; $i < count($data); $i++) { 
				$returnArr = $this->getDirectLineDepartments( $data[$i]['id'] );

				for ($j=0; $j < count($returnArr); $j++) { 
					$ids[] = $returnArr[$j];		
				}

				if( !in_array($data[$i]['id'], $ids) ) {
					$ids[] = $data[$i]['id'];
				}

			}

			// if( !in_array($pid, $ids) ) {
			// 	$ids[] = $pid;
			// }

		} else {

			// $temp = $this->departmentObj->find( $data[0]['id'] );

			// if( $temp['p_department_id']!=0 ) {
				$ids[] = $pid;
			// }

		}

		return $ids;

	}

	/**
	*
	* 后台部门列表 
	* @author 	xww
	* @return 	array
	*/
	public function getListsObject($skip, $size, $name=null)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query = $this->departmentObj->leftJoin("departments as b", function($q){
										$q->on("b.p_department_id", "=", "departments.id")
										  ->where('b.is_deleted', '=', 0);
									})
							->where('departments.is_deleted', '=', 0)
							->groupBy('departments.id')
							->orderBy('departments.p_department_id', 'asc')
						    ->orderBy('departments.id', 'asc')
							->select('departments.id', "departments.name", DB::raw(" IFNULL( group_concat(`comp_b`.name separator '、' ), '无') as childrenDepartment ") ); //

		if( !is_null($name) ) {
			$query = $query->where("departments.name", "like", "%" . $name . "%");
		}

		// 父菜单总记录数
		$totalCount = count( $query->get()->toArray() );

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

	/**
	* 递归返回用户拥有的菜单数组
	* @author 	xww
	* @param  	array  			$data     		数组
	* @param  	int/string  	$parentId     	父级id
	* @param  	array  			$selfMenu     	单个菜单自身数组
	* @return 	array
	*/
	public function getDepartmentsLists($data, $parentId, $selfMenu=[])
	{

		$retunData = [];

		if( !empty($selfMenu) ) {

			if( !isset($selfMenu['children'] ) ){
	   			$selfMenu['children'] = [];
	   		}

			$retunData = $selfMenu;
		}
		
		foreach($data as $k => $v)
		{

		   if($v['parentid'] == $parentId)
		   {    

		   		if( empty($selfMenu)  ) {
		   			$selfMenu = $v;
		   			$selfMenu['children'] = [];	
		   		} else if( !isset($selfMenu['children'] ) ){
		   			$selfMenu['children'] = [];
		   		}

		   		unset($v['parentid']);
		   		// unset($v['order']);

		   		//第一个一级菜单 寻找子菜单
		    	$returnArr = $this->getDepartmentsLists($data, $v['id'],$v);

		    	if( $parentId==0 ) {
		    		$retunData[] = $returnArr;
		    	} else {
		    		$selfMenu['children'][] = $returnArr;
		    		$retunData = $selfMenu;
		    	}

		   }

		}

		return $retunData;

	}

	/**
	*
	* 获取未删除的指定名成指定父级的部门
	* @author 	xww
	* @param 	string  		$name
	* @param 	int/string  	$pid
	* @return 	array
	*/
	public function getSameSituationDepartment($name, $pid)
	{
		return $this->departmentObj->where("is_deleted", 0)
								   ->where("name", $name)
								   ->where("p_department_id", $pid)
								   ->get()
								   ->toArray();
	}

	/**
	* 获取部门详情
	* @author 	xww
	* @param 	int/string 		$id 	部门id
	* @return 	array
	*/
	public function getDepartmentInfo($id)
	{
		return  $this->departmentObj->leftJoin("departments as b", "departments.p_department_id", "=", "b.id")
								   	->where("departments.is_deleted", 0)
									->select("departments.id", "departments.name", "b.name as parentDepartmentName")
									->where("departments.id", $id)
									->take(1)
									->get()
									->toArray();
	}

	/**
	* 获取部门详情 --包括两个列表  一个上级部门列表，一个下级部门列表
	* @author 	xww
	* @param 	int/string 		$id  default null
	* @return 	array
	*/
	public function getDepartmentInfoWidthParentAndChildren( $id=null )
	{
		
		$all = $this->departmentObj->where("is_deleted", 0)
								   ->select("id", "name", "p_department_id")
								   ->orderBy("p_department_id", "asc")
								   ->orderBy("id", "asc")
								   ->get()
								   ->toArray();

		if( !is_null($id) ) {
			$single = $this->departmentObj->find($id);
		}

		// 上级部门
		$parents = [];

		for ($i=0; $i < count($all); $i++) { 
			
			$all[$i]['checked'] = false;

			if( !empty($single) && $single['id']!=$all[$i]['id'] && $single['p_department_id']==$all[$i]['id'] ) {
				$all[$i]['checked'] = true;				
			}

			$temp = $all[$i];
			unset($temp['p_department_id']);
			$parents[] = $temp;

		}

		// 下级部门
		$children = [];

		for ($i=0; $i < count($all); $i++) { 
			
			$all[$i]['checked'] = false;

			if( !empty($single) && $single['id']!=$all[$i]['id'] && $single['id']==$all[$i]['p_department_id'] ) {
				$all[$i]['checked'] = true;				
			}

			$temp = $all[$i];
			unset($temp['p_department_id']);
			$children[] = $temp;

		}

		if( empty($single) ) {
			$selfData = null;	
		} else {

			unset($single['is_deleted']);
			unset($single['p_department_id']);
			unset($single['level']);
			unset($single['create_time']);
			unset($single['update_time']);

			$selfData = $single;

		}

		$data['parents'] = empty($parents)? null:$parents;
		$data['data'] = $selfData;
		$data['children'] = empty($children)? null:$children;

		return $data;

	}

	/**
	* 获取所有部门
	* @author 		xww
	* @return 		array
	*/
	public function getAll()
	{
		
		return $this->departmentObj->where("is_deleted", 0)
								   ->select("id", "name")
								   ->orderBy("p_department_id", "asc")
								   ->orderBy("id", "asc")
								   ->get()
								   ->toArray();

	}

	/**
	* 改变新上级部门 当当前上级部门 是该部门的所有部门
	* @author 	xww
	* @param 	int/string 	$id
	* @return 	int 	affect rows
	*/
	public function changeChildrenParentDeparment($id, $pid=0)
	{
		$data['p_department_id'] = $pid;
		$data['update_time'] = time();
		return $this->departmentObj->where("p_department_id", $id)->update($data);
	}

	/**
	* 设定新下级部门 当当前上级部门 是该部门的所有部门
	* @author 	xww
	* @param 	int/string 	$id
	* @return 	int 	affect rows
	*/
	public function setChildrenParentDeparment($id, $cids)
	{
		$data['p_department_id'] = $id;
		$data['update_time'] = time();
		return $this->departmentObj->whereIn("id", $cids)->update($data);
	}

	/**
	* 获取指定部门的用户列表
	* @author 	xww
	* @todo
	* @param 	int/string 		$pids
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @param 	int/string 		$search    用户姓名搜索
	*/
	public function getDepartmentsUserLists($pids=null, $skip=null, $size=null, $search=null, $notInUser=[])
	{

		$query =  \EloquentModel\User::leftJoin("department_rel_user", function($q){
							 		$q->on('department_rel_user.user_id', "=", 'users.id')
			                          ->where('department_rel_user.is_deleted', "=", 0);
								})
		                        ->leftJoin("departments", function($q) use($pids){
		                        	$q->on("departments.id", "=", "department_rel_user.department_id")
		                        	  ->where("departments.is_deleted",  "=", 0);
		                        })
		                        ->select("users.id", "users.name", "users.avatar", DB::raw(' IFNULL( group_concat(comp_departments.name), "") as departmentName'), "users.gender", "users.phone", "users.nationality_number as nationalityNumber")
		                        // ->where("users.id", "<>", 1)
		                        ->where("users.is_deleted", "=", 0)
		                        ->orderBy("users.create_time", 'desc')
		                        ->orderBy("users.id", 'desc')
		                        ->groupBy("users.id");

		if( !is_null($pids) ) {
    		$query = $query->whereIn("departments.id", $pids);
    	}

    	if( !empty($notInUser) ) {
    		$query = $query->whereNotIn("users.id", $notInUser);	
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
	* 获取指定ids的部门列表
	* @author 	xww
	* @param 	array 			$ids
	* @return 	array
	*/
	public function getNamedDepartments($ids=null)
	{

		$query = $this->departmentObj->where("is_deleted", 0)->select("id", "name")->orderBy("p_department_id", "asc")->orderBy("create_time", "desc")->orderBy("id", "desc");

		if( !is_null($ids) && is_array($ids) ) {
			$query = $query->whereIn("id", $ids);			
		}

		return $query->get()->toArray();

	}

	/**
	* 获取指定部门的用户列表--格式 部门包裹用户
	* @author 	xww
	* @todo
	* @param 	int/string 		$pids
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @param 	int/string 		$search    用户姓名搜索
	*/
	public function getPackageDepartmentsUserLists($pids=null, $skip=null, $size=null, $search=null, $notInUser=[])
	{

		$query =  \EloquentModel\User::leftJoin("department_rel_user", function($q){
							 		$q->on('department_rel_user.user_id', "=", 'users.id')
			                          ->where('department_rel_user.is_deleted', "=", 0);
								})
		                        ->leftJoin("departments", function($q) use($pids){
		                        	$q->on("departments.id", "=", "department_rel_user.department_id")
		                        	  ->where("departments.is_deleted",  "=", 0);
		                        })
		                        ->select("users.id", "users.name", "users.avatar", "users.gender", DB::raw( " IFNULL(`comp_users`.phone, '') as phone " ), "departments.id as departId", "departments.name as departmentName")
		                        // ->where("users.id", "<>", 1)
		                        ->where("users.is_deleted", "=", 0)
		                        ->orderBy("departments.id", 'asc')
		                        ->orderBy("users.create_time", 'desc')
		                        ->orderBy("users.id", 'desc');

		if( !is_null($pids) ) {
    		$query = $query->whereIn("departments.id", $pids);
    	}

    	if( !empty($notInUser) ) {
    		$query = $query->whereNotIn("users.id", $notInUser);	
    	}

		if( !is_null($skip) && !is_null($size) ) {
			$query = $query->skip($skip)->take($size);
		}
		
		// 搜索姓名
		if( !is_null($search) ) {
			$query = $query->where("users.name", "like", "%" . $search . "%");
		}

		$data = $query->get()->toArray();

		if( !empty($data) ) {
			/*存储部门id*/
			$pids = [];
			$returnData = [];
			for ($i=0; $i < count($data); $i++) { 
				$departmentId = $data[$i]['departId'];

				/*判断是否在数组中 不存在就加入*/
				if(!in_array($departmentId, $pids) ) {
					$pids[] = $departmentId;

					$temp['departmentName'] = empty($data[$i]['departmentName'])? '未知':$data[$i]['departmentName'];
					$temp['users'] = [];

					$returnData[] = $temp;
					unset($temp);
				}

				$pos = array_keys($pids, $departmentId);

				$keyPos = $pos[0];

				$user['id'] = $data[$i]['id'];
				$user['name'] = $data[$i]['name'];
				$user['avatar'] = $data[$i]['avatar'];
				$user['gender'] = $data[$i]['gender'];
				$user['phone'] = $data[$i]['phone'];

				$returnData[ $keyPos ]['users'][] = $user;
				unset($user);
			}

			for ($i=0; $i < count($returnData); $i++) { 
				$returnData[$i]['users'] = empty($returnData[$i]['users'])? null:$returnData[$i]['users'];
			}

			return $returnData;

		} else {
			return null;
		}

	}

}
