<?php
namespace VirgoBack;
use Illuminate\Database\Capsule\Manager as DB;
class AdminUserController extends AdminBaseController
{
	
	public function __construct()
	{
		//$this->departmentObj = new \VirgoModel\DepartmentModel;
		$this->pageObj = new \VirgoUtil\Page;
		$this->userObj = new \VirgoModel\UserModel;
		$this->functionObj = new \VirgoUtil\Functions;
		parent::isLogin();
	}

	public function index()
	{
		$page_title = '用户管理';

		$gender = array(1=>'男',2=>'女',3=>'保密');
		$userTypes = array(1=>'后台管理员',2=>'客服',3=>'普通会员');

		// 分页对象
		$pageObj2 = new \VirgoUtil\Page2;
		$userObj = new \EloquentModel\User;

		$query = $userObj->select("users.*")
		                 ->where("users.is_deleted", 0)
						 ->where("users.id", "<>", 1)
						 ->groupBy("users.id")
						 ->orderBy("create_time", 'desc');
		// 用户名过滤
		if(!empty($_GET['username'])){
			$_GET['username'] = trim($_GET['username']);
			$query = $query->where("users.name", 'like', "%" . $_GET['username'] . "%" );
			$pageObj2->setPageQuery(['username'=>$_GET['username']]); 
		}

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
		$data2 = $query->skip($skip)->take($size)->get()->toArray();
		
		//设置页数跳转地址 
		$pageObj2->setUrl('/admin/users');

		// 设置分页数据
		$pageObj2->setData($data2);

		// 设置记录总数
		$pageObj2->setTotalCount($totalCount);

		// 设置分页大小
		$pageObj2->setSize($size);

		// 进行分页并返回
		$pageObj = $pageObj2->doPage();

		$data = $pageObj->data;

		// 起始组装
		$page = $pageObj->current_page;
		$per_count = $size;
		$record_start = ($page-1)*$per_count;
		// 起始组装--end

		require_once dirname(__FILE__).'/../../views/admin/adminUser/index.php';
		
	}

	public function create()
	{
		
		$page_title = '添加用户';
		
		//部门
		//params
		$gender = array(1=>'男',2=>'女',3=>'保密');

		//预处理
		$user['gender'] = 3;

		// 获取所有正常角色
		$sysRoleModelObj = new \VirgoModel\SysRoleModel;
		$roles = $sysRoleModelObj->all();

		$timeIndex = 0;

		require_once dirname(__FILE__).'/../../views/admin/adminUser/_create.php';
	}

	public function read()
	{

	}

	public function update()
	{
		try {

			$page_title = '修改用户';
			
			//params
			$gender = array(1=>'男',2=>'女',3=>'保密');

			//用户身份
			$identifyTemp = \EloquentModel\RoleToUser::where("user_id", '=', $_GET['id'])
															->where("deleted", '=', 0)
															->groupBy('role_id')
															->get(['role_id'])
															->toArray();

			$user = $this->userObj->read();

			$user['join_time'] = empty($user['join_time'])? '':date("Y-m-d", $user['join_time']);
			
			// 获取所有正常角色
			$sysRoleModelObj = new \VirgoModel\SysRoleModel;
			$roles = $sysRoleModelObj->all();

			// 获取用户拥有的角色
			$roleToUserModelObj = new \VirgoModel\RoleToUserModel;
			$userRoles = $roleToUserModelObj->read($_GET['id']);

			require_once dirname(__FILE__).'/../../views/admin/adminUser/_update.php';

		} catch(\Exception $e) {
			echo "<h1>".$e->getMessage()."</h1>";
		}
		
	}

	public function delete()
	{

	}

	public function doCreate()
	{

		//检测账号唯一性
		$userLogin_is_used = \EloquentModel\User::where('user_login', '=', $_POST['user_login'])->where("is_deleted", 0)->get();

		if(!count($userLogin_is_used)==0){
			header('Refresh: 5;url=/admin/users');
			echo "该账号已被使用";
			return false;
		}

		//头像结果
		if(!empty($_FILES['userAvatar']['name'])){
			$uploadAvatarResult = $this->functionObj->specialUploadFile('userAvatar','/upload/avatarCover/',array('jpg','png'));
			if(!$uploadAvatarResult[0]['success']) {
				header('Refresh: 5;url=/admin/users');
				echo "上传头像图片失败";
				if(!$uploadAvatarResult[0]['validate'])
					echo "格式不正确，请上传jpg和png格式的文件";
				return false;
			}
			$uploadAvatarResult = $uploadAvatarResult[0]['picurl'];
			$_POST['avatar'] = $uploadAvatarResult;
		}

		// 日期转变
		$_POST['join_time'] = empty($_POST['join_time'])? 0:strtotime($_POST['join_time']." 00:00:00");
		$_POST['birthday'] = empty($_POST['birthday'])? 0:strtotime($_POST['birthday']." 00:00:00");

		$rs = $this->userObj->doCreate();

		if($rs){
			header('Refresh: 5;url=/admin/users');
			echo "添加成功";
		} else {
			header('Refresh: 5;url=/admin/users');
			echo "添加失败";
		}

	}

	public function doRead()
	{

	}

	public function doUpdate()
	{

		//检测账号唯一性
		$userLogin_is_used = \EloquentModel\User::where('user_login', '=', $_POST['user_login'])
												->where('id', '<>', $_POST['id'])
		                                        ->get();

		// if(!count($userLogin_is_used)==0){
		// 	header('Refresh: 5;url=/admin/users');
		// 	echo "该账号已被使用";
		// 	return false;
		// }

		if($_FILES['userAvatar']['name']!=''){
			$uploadAvatarResult = $this->functionObj->specialUploadFile('userAvatar','/upload/avatarCover/',array('jpg','png'));
			if(!$uploadAvatarResult[0]['success']) {
				header('Refresh: 5;url=/admin/users');
				echo "上传头像图片失败";
				if(!$uploadAvatarResult[0]['validate'])
					echo "格式不正确，请上传jpg和png格式的文件";
				return false;
			}
			$uploadAvatarResult = $uploadAvatarResult[0]['picurl'];
			$_POST['avatar'] = $uploadAvatarResult;
		}

		// 日期转变
		$_POST['join_time'] = empty($_POST['join_time'])? 0:strtotime($_POST['join_time']." 00:00:00");
		$_POST['birthday'] = empty($_POST['birthday'])? 0:strtotime($_POST['birthday']." 00:00:00");		
		
		$rs = $this->userObj->doUpdate();
		if($rs){
			header('Refresh: 5;url=/admin/users');
			echo "修改成功";
		} else {
			header('Refresh: 5;url=/admin/users');
			echo "修改失败";
		}
	}

	public function doDelete()
	{
		
		$rs = $this->userObj->doDelete();
		if($_POST){
			if($rs) {
				echo json_encode(['success'=>true,'message'=>'delete success', 'code'=>'001']);
			} else {
				echo json_encode(['success'=>false,'message'=>'delete failture','code'=>'012']);
			}
				
		} else {
			if($rs){
				header('Refresh: 5;url=/admin/users');
				echo "删除成功";
			} else {
				header('Refresh: 5;url=/admin/users');
				echo "删除失败";
			}
		}
	}

	/*其他函数*/

	//token
	public function getToken()
	{
		// $functionObj = new Functions;

		$ok = true;
		$access_token = '';
		while($ok){
			$tokenStr = $this->functionObj->tokenStr();
			$token_is_used = \EloquentModel\User::where('access_token','=',$tokenStr)->get();
			if(count($token_is_used)==0){
				$access_token = $tokenStr;
				$ok = false;
			}
		}

		return $access_token;

	}

	//nickname
	public function getNickName()
	{
		// $functionObj = new Functions;
	
		$ok = true;
		$nickname = '';
		while($ok){
			$nicknameStr = $this->functionObj->getNickName();
			$nickname_is_used = \EloquentModel\User::where('nickname','=',$nicknameStr)->get();
			if(count($nickname_is_used)==0){
				$nickname = $nicknameStr;
				$ok = false;
			}
		}

		return $nickname;

	}

	/**
	* 重置密码
	* @author 	xww
	* @return 	void
	*/ 
	public function resetPwd()
	{
		
		try{

			if(empty($_GET['id'])){ throw new \Exception("Invalid param"); }

			// 初始密码
			$default = '123456';

			$data['password'] = "nciou".md5($default)."dijdm";
			$data['update_time'] = time();
			
			$rs = \EloquentModel\User::where("id", $_GET['id'])->update($data);

			if($rs){
				$message = "重置密码成功";
			} else {
				$message = "重置密码失败";
			}
			header('Refresh: 5;url=/admin/users');
			echo $message;
		} catch(\Exception $e){
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

	/**
	* 显示日常工作页面
	* render the page
	* @author 	xww
	* @return 	void
	*/
	public function works()
	{
		
		try {

			// 获取属于该用户的未完成任务重量
			$userId = $_COOKIE['user_id'];
			$todoWorksModelObj = new \VirgoModel\TodoWorksModel;

			// 
			// $undoWorkCount = $todoWorksModelObj->getUndoWorks($userId);

			// 获取用户 对应的活动
			$lists = $todoWorksModelObj->getUndoWorksLists($userId);

			// 工作列表页面
			// require_once dirname(__FILE__).'/../../views/admin/adminUser/works.php';

		} catch(\Exception $e){
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

	/**
	* 用户阶段工作未完成日志审批页面
	* render the page
	* @author 	xww
	* @return 	void
	*/
	public function showProcessTypeWorks()
	{
		
		try {

			// 获取属于该用户的未完成各阶段任务数量
			$userId = $_COOKIE['user_id'];

			$todoWorksModelObj = new \VirgoModel\TodoWorksModel;

			// 领航未完成日志数量
			$undoProcessOneWorkCount = $todoWorksModelObj->getUndoWorks($userId, 1);

			// 启航未完成日志数量
			$undoProcessTwoWorkCount = $todoWorksModelObj->getUndoWorks($userId, 2);

			// 远航未完成日志数量
			$undoProcessThreeWorkCount = $todoWorksModelObj->getUndoWorks($userId, 3);

			// 日志阶段工作审批页面
			require_once dirname(__FILE__).'/../../views/admin/adminUser/process_works.php';

		} catch(\Exception $e){
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

	/**
	* 显示指定阶段的不同种类的未完成日志审批数量
	* render the page
	* @author 	xww
	* @return 	void
	*/
	public function showProcessWorks()
	{
		
		try {

			// 阶段类型
			if(empty($_GET['typeId'])) {
				throw new \Exception("Wrong Param");
			}
			
			// 获取属于该用户的未完成各阶段任务数量
			$userId = $_COOKIE['user_id'];

			$todoWorksModelObj = new \VirgoModel\TodoWorksModel;

			// 阶段未完成日记数量
			$undoDayWorkCount = $todoWorksModelObj->getUndoWorks($userId, $_GET['typeId'], 1);

			// 阶段未完成周记数量
			$undoWeeklyWorkCount = $todoWorksModelObj->getUndoWorks($userId, $_GET['typeId'], 2);

			// 阶段未完成月记数量
			$undoMonthlyWorkCount = $todoWorksModelObj->getUndoWorks($userId, $_GET['typeId'], 3);

			// 阶段未完成季记数量
			$undoQuarterlyWorkCount = $todoWorksModelObj->getUndoWorks($userId, $_GET['typeId'], 4);

			// 阶段未完成年记数量
			$undoYearlyWorkCount = $todoWorksModelObj->getUndoWorks($userId, $_GET['typeId'], 5);

			// 日志阶段工作审批页面
			require_once dirname(__FILE__).'/../../views/admin/adminUser/process_diary_works.php';

		} catch(\Exception $e){
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

	/**
	* 具体显示特定阶段特定类型未完成列表
	* render the page
	* @author 	xww
	* @return 	void
	*/
	public function showDairyWorksList()
	{
		try{

			// 阶段类型
			if(empty($_GET['typeId'])) {
				throw new \Exception("Wrong Param");
			}

			// 日志类型
			if(empty($_GET['dairyType'])) {
				throw new \Exception("Wrong Param");
			}

			$userId = $_COOKIE['user_id'];

			$page = empty($_GET['page']) || (int)$_GET['page']<1? 1:(int)$_GET['page'];
			$size = 10;
			$page -= 1;
			$skip = $page*$size;

			// 根据传递的阶段类型 和 日志类型 获取对应的列表 
			// 需要分页对象
			// 需要有标题 作者,创建时间倒叙

			$todoWorksModelObj = new \VirgoModel\TodoWorksModel;
			$pageObj = $todoWorksModelObj->frontLists($userId, $_GET['typeId'], $_GET['dairyType'], $skip, $size);
			$data = $pageObj->data;

			// 获取用户审批身份标志 （只能用于一个用户 没有多个能审批角色情况）
			$userRoleArr = $this->userObj->getUserApprovalRole($userId);
			$userRole = empty($userRoleArr)? '':$userRoleArr['type_id'];

			require_once dirname(__FILE__).'/../../views/admin/adminUser/diary_works.php';

		} catch(\Exception $e){
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

	/**
	* 显示用户已经处理的工作页面
	* render the page
	* @author 	xww
	* @return 	void
	*/
	public function doneWorks()
	{
		
		try{

			// 获取属于该用户的已完成任务重量
			$userId = $_COOKIE['user_id'];
			$todoWorksModelObj = new \VirgoModel\TodoWorksModel;

			// 获取已完成工作数量
			$doneWorkCount = $todoWorksModelObj->getDoneWorks($userId);

			// 工作页面
			require_once dirname(__FILE__).'/../../views/admin/adminUser/doneWorks.php';

		} catch(\Exception $e){
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

	/**
	* 用户阶段工作已完成日志审批页面
	* render the page
	* @author 	xww
	* @return 	void
	*/
	public function showDoneProcessTypeWorks()
	{
		
		try {

			// 获取属于该用户的未完成各阶段任务数量
			$userId = $_COOKIE['user_id'];

			$todoWorksModelObj = new \VirgoModel\TodoWorksModel;

			// 领航已完成日志数量
			$doneProcessOneWorkCount = $todoWorksModelObj->getDoneWorks($userId, 1);

			// 启航已完成日志数量
			$doneProcessTwoWorkCount = $todoWorksModelObj->getDoneWorks($userId, 2);

			// 远航已完成日志数量
			$doneProcessThreeWorkCount = $todoWorksModelObj->getDoneWorks($userId, 3);

			// 日志阶段工作审批页面
			require_once dirname(__FILE__).'/../../views/admin/adminUser/done_process_works.php';

		} catch(\Exception $e){
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

	/**
	* 显示指定阶段的不同种类的已完成日志审批数量
	* render the page
	* @author 	xww
	* @return 	void
	*/
	public function showDoneProcessWorks()
	{
		
		try {

			// 阶段类型
			if(empty($_GET['typeId'])) {
				throw new \Exception("Wrong Param");
			}
			
			// 获取属于该用户的未完成各阶段任务数量
			$userId = $_COOKIE['user_id'];

			$todoWorksModelObj = new \VirgoModel\TodoWorksModel;

			// 阶段已完成日记数量
			$doneDayWorkCount = $todoWorksModelObj->getDoneWorks($userId, $_GET['typeId'], 1);

			// 阶段已完成周记数量
			$doneWeeklyWorkCount = $todoWorksModelObj->getDoneWorks($userId, $_GET['typeId'], 2);

			// 阶段已完成月记数量
			$doneMonthlyWorkCount = $todoWorksModelObj->getDoneWorks($userId, $_GET['typeId'], 3);

			// 阶段已完成季记数量
			$doneQuarterlyWorkCount = $todoWorksModelObj->getDoneWorks($userId, $_GET['typeId'], 4);

			// 阶段已完成年记数量
			$doneYearlyWorkCount = $todoWorksModelObj->getDoneWorks($userId, $_GET['typeId'], 5);

			// 日志阶段工作审批页面
			require_once dirname(__FILE__).'/../../views/admin/adminUser/done_process_diary_works.php';

		} catch(\Exception $e){
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

	/**
	* 具体显示特定阶段特定类型已完成列表
	* render the page
	* @author 	xww
	* @return 	void
	*/
	public function showDoneDairyWorksList()
	{
		try{

			// 阶段类型
			if(empty($_GET['typeId'])) {
				throw new \Exception("Wrong Param");
			}

			// 日志类型
			if(empty($_GET['dairyType'])) {
				throw new \Exception("Wrong Param");
			}

			$userId = $_COOKIE['user_id'];

			$page = empty($_GET['page']) || (int)$_GET['page']<1? 1:(int)$_GET['page'];
			$size = 10;
			$page -= 1;
			$skip = $page*$size;

			// 根据传递的阶段类型 和 日志类型 获取对应的已经完成列表 
			// 需要分页对象
			// 需要有标题 作者,创建时间倒叙

			$todoWorksModelObj = new \VirgoModel\TodoWorksModel;
			$pageObj = $todoWorksModelObj->frontLists($userId, $_GET['typeId'], $_GET['dairyType'], $skip, $size, 2);
			$data = $pageObj->data;

			// 获取用户审批身份标志 （只能用于一个用户 没有多个能审批角色情况）
			$userRoleArr = $this->userObj->getUserApprovalRole($userId);
			$userRole = empty($userRoleArr)? '':$userRoleArr['type_id'];

			require_once dirname(__FILE__).'/../../views/admin/adminUser/diary_works.php';

		} catch(\Exception $e){
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

	/**
	* 获取用户未完成工作列表
	* render the page
	* @author 	xww
	* @return 	void
	*/
	public function workLists()
	{
		
		try {
			
			$userId = $_COOKIE['user_id'];
			$todoWorksModelObj = new \VirgoModel\TodoWorksModel;

			$page = empty($_GET['page']) || (int)$_GET['page']<1? 1:(int)$_GET['page'];
			$size = 8;
			$page -= 1;
			$skip = $page*$size;

			$search = null;
			if(!empty($_GET['username'])) {
				$search = $_GET['username'];
			}

			// 替换成对应的userId
			$pageObj = $todoWorksModelObj->getWorksLists($userId, $skip, $size, $search);

			$totalCount = $pageObj->totalCount;
			$size = $pageObj->size;

			$totalPage = $totalCount % $size == 0? $totalCount / $size:ceil($totalCount / $size);

			$data = $pageObj->data;

			// 获取用户审批身份标志 （只能用于一个用户 没有多个能审批角色情况）
			$userRoleArr = $this->userObj->getUserApprovalRole($userId);
			$userRole = empty($userRoleArr)? '':$userRoleArr['type_id'];

			require_once dirname(__FILE__).'/../../views/admin/adminUser/undo_work_lists.php';

		} catch(\Exception $e){
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

	/**
	* 显示用户的审批日志详情
	* render the page
	* @author 	xww
	* @return 	void
	*/
	public function showDatail()
	{
		
		try {

			if(empty($_GET['id'])) {
				throw new \Exception("Wrong Param");
			}

			$userId = $_COOKIE['user_id'];
			$recordId = $_GET['id'];

			$todoWorksModelObj = new \VirgoModel\TodoWorksModel;

			// 验证数据有效性
			$recordData = $todoWorksModelObj->getRercordWithUser($recordId, $userId);

			if(empty($recordData)) {
				throw new \Exception("数据不存在");
			}

			$userRoleArr = $this->userObj->getUserApprovalRoles($userId);

			$userRole = '';

			// 转化当前审批角色  对应的等级
			$showLevel = $this->getShowLevel($recordData['approval_type_id']);
			// 当前记录的审批角色
			//$workRoleTypeId = empty($recordData['approval_type_id'])? 0:$recordData['approval_type_id'];

			// 先判断是否已经完成
			if( $recordData['status_id']==0 ) {

				// 此时根据记录的评论数量 来判断此时处理的应该是哪种用户角色
				// 没有则是 师傅角色
				// 一条就是 指导员/支部书记
				// 两条就是 监狱领导

				$dairyCommentModelObj = new \VirgoModel\DairyCommentModel;
				$commentCount = $dairyCommentModelObj->getCommentCount($recordData['item_id'], $recordData['type_id']);

				if( $commentCount==0 ) {
					// 师傅评论
					if( in_array("2105", $userRoleArr) ) {
						$userRole = '2105';						
					}
				} else if( $commentCount==1 ){

					// 指导员/科长评论 （同个部门下）
					// $userObj = new \VirgoModel\UserModel;
					// $approverUserArr = $userObj->getNextApprover($userId, ['2102']);

					// 指导员
					if( in_array("2101", $userRoleArr) ){
						$userRole = '2101';
					}

					// 科长
					if( in_array("2109", $userRoleArr) ){
						$userRole = '2109';
					}

				} else if( $commentCount==2 ){

					// 可能是教导员/支部书记

					// 可能是教导员
					if( in_array("2108", $userRoleArr) ){
						$userRole = '2108';
					}

					// 可能是支部书记
					if( in_array("2102", $userRoleArr) ){
						$userRole = '2102';
					}

				} else if( $commentCount==3 ){ 
					// 必然是监狱领导
					if( in_array("2103", $userRoleArr) ){
						$userRole = '2103';
					}
				}

			}

			// 获取工作记录中 对应的各种评价 以及内容
			$data = $todoWorksModelObj->getDiaryCommentData($recordData['item_id'], $recordData['type_id']);
			 // = empty($userRoleArr)? '':$userRoleArr['type_id'];

			require_once dirname(__FILE__).'/../../views/admin/adminUser/diary_detail.php';

		} catch(\Exception $e){
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

	/**
	* 向用户提问的 问题列表
	* render the page
	* @author 	xww
	* @return 	void
	*/
	public function questionLists()
	{
		
		try{

			$masterId = $_COOKIE['user_id'];

			// 分页
			$page = empty($_GET['page']) || (int)$_GET['page']<1? 1:(int)$_GET['page'];
			$size = 8;
			$page -= 1;
			$skip = $page*$size;

			$username = null;
			if( !empty($_GET['username']) ) {
				$username = trim( $_GET['username'] );
			}

			$statusId = null;
			if( !empty($_GET['statusId']) ) {
				if($_GET['statusId']==1) {
					$statusId = 1;
				} else if($_GET['statusId']==2){
					$statusId = 2;
				}
			}

			$pageObj = $this->userObj->getUserAskQuestionLists($masterId, $skip, $size, $username, $statusId);

			$totalCount = $pageObj->totalCount;

			$size = $pageObj->size;

			$totalPage = $totalCount % $size == 0? $totalCount / $size:ceil($totalCount / $size);

			$data = $pageObj->data;

			require_once dirname(__FILE__).'/../../views/admin/adminUser/question_lists.php';

		} catch(\Exception $e){
			echo "<h1>".$e->getMessage()."</h1>";
		}	

	}

	/**
	* 查看详情
	* render the page
	* @author 	xww
	* @return 	void
	*/
	public function questionDetail()
	{
		
		try {

			$userId = $_COOKIE['user_id'];

			if( empty( $_GET['id'] ) ) {
				throw new \Exception("Wrong Param");
			}

			$recordId = (int)$_GET['id'];

			// 实例化对象
			$obj = new \VirgoModel\AskMasterQuestionModel;

			// 验证数据有效性
			$data = $obj->getRercordWithUser($recordId, $userId);

			if(empty($data)) {
				throw new \Exception("数据不存在");
			}

			require_once dirname(__FILE__).'/../../views/admin/adminUser/question_detail.php';

		} catch(\Exception $e){
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

	/**
	* 获取日志类型文本
	* @author 	xww
	* @param 	int/string 		$id
	* @return 	string
	*/
	public function getDiaryTypeText($id)
	{
		switch (intval($id)) {
			case 1:
				return "日记";
				break;
			case 2:
				return "周记";
				break;
			case 3:
				return "月记";
				break;
			case 4:
				return "季记";
				break;
			case 5:
				return "年记";
				break;
			default:
				return "未知类型";
				break;
		}
	}

	/**
	* 将用户文本进行缩减 并返回
	* @author 	xww
	* @param 	stirng 		$string 内容
	* @param 	int 		$length 长度
	* @return 	string
	**/
	public function getDiarySampleText($string, $length)
	{
		
		if(mb_strlen($string, "utf-8")>$length) {
			return mb_substr($string, 0, $length, "utf-8")."...";
		} else {
			return $string;
		}

	}

	/**
	* 获取记录处理角色能看到的层级
	* @author 	xww
	* @param 	int/string 		$roleTypeId
	* @return 	int
	*/
	public function getShowLevel($roleTypeId)
	{
		switch (intval($roleTypeId)) {
			case 2105:
				return 1;
				break;
			case 2101:
				return 2;
				break;
			case 2109:
				return 2;
				break;
			case 2108:
				return 3;
				break;
			case 2102:
				return 3;
				break;
			case 2103:
				return 4;
				break;
			default:
				return 0;
				break;
		}
	}

	/**
	* 师傅详情 
	* @author 		xww
	* @return 		void
	*/
	public function masterInfo()
	{

		try {

			if( empty($_GET['uid']) ) {
				throw new \Exception("Wrong Param");
			}

			$userId = $_GET['uid'];

			$userObj = new \VirgoModel\UserModel;
			$roleToUserModelObj = new \VirgoModel\RoleToUserModel;

			$data = $userObj->readSingleTon( $userId );

			if( empty($data) ) {
				throw new \Exception("用户不存在");
			}

			$data = $data->toArray();

			// 鉴权
			$userRoleArr = $roleToUserModelObj->read($userId);

			if( !in_array(10, $userRoleArr) ) {
				throw new \Exception("非一对一导师");
			}

			// 获取必要信息
			$data['avatar'] = empty( $data['avatar'] )? '/images/avatar.png?1':$data['avatar'];
			$data['nativePlace'] = empty( $data['native_place'] )? '':$data['native_place'];
			$data['politicalText'] = $this->getPoliticalText( $data['political'] );
			$data['joinTime'] = empty( $data['join_time'] )? '':date("Y.m.d", $data['join_time']);
			$data['educationText'] = $this->getEducationText( $data['education'] );

			// 获取用户的工作经历
			$userWorkingExperienceModelObj = new \VirgoModel\UserWorkingExperienceModel;
			$experience = $userWorkingExperienceModelObj->getUserWorkExperienceLists( $data['id'] ) ;

			if( empty($experience) ) {
				$experienceData = null;
			} else {

				for ($i=0; $i < count($experience) ; $i++) { 
					$temp['start_time'] = $experience[$i]['start_time'];
					$temp['end_time'] = $experience[$i]['end_time'];
					$temp['job'] = $experience[$i]['job'];
					$temp['company'] = $experience[$i]['company'];
					$experienceData[] = $temp;
				}

			}

			// // 获取用户擅长课程
			// $teachCourseModelObj = new \VirgoModel\TeachCourseModel;
			// $course = $teachCourseModelObj->getUserTeachCourses( $data['id'] );

			// $courseArr = [];
			// if( !empty($course) ) {
			// 	for ($i=0; $i < count($course); $i++) { 
			// 		$courseArr[] = $course[$i]['title'];
			// 	}
			// }

			$data['workExperiences'] = $experienceData;
			$data['birthdayText'] = empty( $data['birthday'] )? '':date("Y.m.d", $data['birthday']);

			// 获取用户所属部门
			$userModel = new \VirgoModel\UserModel;
			$data['department'] = $userModel->getUserDepartment( $data['id'] );

			// 获取对应徒弟列表
			$masterPupilModelObj = new \VirgoModel\MasterPupilModel;

			$pupilLists = $masterPupilModelObj->getPupilDetailLists( $data['id'] );
			for ($i=0; $i < count($pupilLists); $i++) { 
				$pupilLists[$i]['avatar'] = empty($pupilLists[$i]['avatar'])? '/images/default-avatar.png':$pupilLists[$i]['avatar'];
			}

			$data['pupilLists'] = $pupilLists;

			// var_dump($data);

			// 专区分类修改页面
			require_once dirname(__FILE__).'/../../views/admin/adminUser/masterInfo.php';

		} catch(\Exception $e) {
			echo "<h1>" . $e->getMessage() . "</h1>";
		}	

	}

	/**
	* 根据学历id获取学历
	* @author 	xww
	* @param 	int/string 		$typeId
	* @return 	string
	*/
	public function getEducationText($typeId)
	{
		switch ((int)$typeId) {
			case 1:
				return '博士';
				break;
			case 2:
				return '硕士';
				break;
			case 3:
				return '本科';
				break;
			case 4:
				return '专科';
				break;
			case 5:
				return '高中';
				break;
			case 6:
				return '初中';
				break;
			default:
				return '';
				break;
		}
	}

	/**
	* 根据面貌id获取政治面貌
	* @author 	xww
	* @param 	int/string 		$typeId
	* @return 	string
	*/
	public function getPoliticalText($typeId)
	{
		switch ((int)$typeId) {
			case 1:
				return '团员';
				break;
			case 2:
				return '预备党员';
				break;
			case 3:
				return '党员';
				break;
			default:
				return '群众';
				break;
		}
	}

	/**
	* 我的十万个为什么
	* render    the     page
	* @author 	xww
	* @return 	void
	*/
	public function massiveQuestions()
	{
		
		try {

			// 获取热门问题
			$index = empty($_GET['index']) || (int)$_GET['index']<1? 1:(int)$_GET['index'];

			$uid = $_COOKIE['user_id'];

			if( $index >3 ) {
				throw new \Exception("Wrong Param");
			}

			// 实例化对象
			$model = new \VirgoModel\MassiveQuestionModel;
			$answerModel = new \VirgoModel\CommentMassiveQuestionModel;

			// 列表分页
			$page = empty($_GET['page']) || (int)$_GET['page']<1? 1:$_GET['page'];
			$size = 10;

			$page -= 1;
			$skip = $page*$size;

			// 获取发现对象
			$discoveryObj = $model->getPcMassiveQuestionLists( $skip, $size, "/admin/user/massiveQuestions", $index);

			$discoveryData = $discoveryObj->data;

			$totalCount = $discoveryObj->totalCount;

			$dataSize = $discoveryObj->size;

			$discoveryTotalPage = $totalCount % $dataSize == 0? $totalCount / $dataSize:ceil($totalCount / $dataSize);
			// 获取发现对象--end

			// 我的问题
			$myQuestionObj = $model->getPcMyMassiveQuestionLists($uid, $skip, $size, "/admin/user/massiveQuestions", $index);

			$myQuestionData = $myQuestionObj->data;

			$totalCount = $myQuestionObj->totalCount;

			$dataSize = $myQuestionObj->size;

			$myQuestionTotalPage = $totalCount % $dataSize == 0? $totalCount / $dataSize:ceil($totalCount / $dataSize);
			// 获取发现对象--end

			// 我的回答
			$myAnswerObj = $answerModel->getPcMyAnswerMassiveQuestionLists($uid, $skip, $size, "/admin/user/massiveQuestions", $index);

			$myAnswerData = $myAnswerObj->data;

			$totalCount = $myAnswerObj->totalCount;

			$dataSize = $myAnswerObj->size;

			$myAnswerTotalPage = $totalCount % $dataSize == 0? $totalCount / $dataSize:ceil($totalCount / $dataSize);
			// 获取发现对象--end

			// var_dump( $myAnswerData );


			// 专区分类修改页面
			require_once dirname(__FILE__).'/../../views/admin/adminUser/massiveQuestions.php';

		} catch(\Exception $e) {
			echo $e->getMessage();
		}

	}

	/**
	* 我的实训成绩
	* @author 	xww
	* @return 	void
	*/
	public function trainingResult()
	{
		
		try{

			$model = new \VirgoModel\TrainingResultModel;

			$uid = $_COOKIE['user_id'];

			$userIds = [ $uid ];

			$pageObj = $model->lists($userIds, '/admin/user/trainingResult');

			// 赋值数据
			$data = $pageObj->data;

			$totalCount = $pageObj->totalCount;

			$size = $pageObj->size;

			$totalPage = $totalCount % $size == 0? $totalCount / $size:ceil($totalCount / $size);

			// 页面
			require_once dirname(__FILE__).'/../../views/admin/adminUser/trainingResult.php';

		} catch(\Exception $e) {
			echo $e->getMessage();
		}

	}


}
