<?php
/**
* \AdminController
*/
namespace VirgoBack;
class AdminSessionController extends AdminBaseController
{
	
	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
	}

	// 注册界面
    public function signup()
    {
		$site = \EloquentModel\Site::first();

        //判断是否开放了注册
        if($site['register_type']==0){
            $this->showPage(['当前不允许注册'], '/');
            return false;
        }

    	$page_title = "注册";
    	$verify_url = $_SERVER['SCRIPT_NAME']."/registerVerify";

    	require dirname(__FILE__).'/../../views/admin/adminSessions/signup.php';
    }

	// 注册
    public function register()
    {

		$obj = new \EloquentModel\User;
		$data = $this->functionsObj->deleteNotNeedDataArray($_POST, array('id'));
		
		$data['created_at'] = time();
		$data['updated_at'] = time();

		$rs = $obj->insert($data);

		header("Refresh: 5;url=/admin");
		if($rs){
			echo "注册成功";
			setcookie("user_login",$data['user_login'],time()+3600*5,'/');
		}else{
			echo "注册失败";
		}
			
    }


    //用户注册
    public function userRegister()
    {

    	$apiObj = new \VirgoApi\ApiBaseController;
    	$validateRs = $this->functionObj->validateApiParams('required',$_POST,['phone','password','verify']);

    	if(!$validateRs['success']){
			echo $this->functionObj->toAppJson(null, '014', $validateRs['message'], false);
			return false;
		}

		$apiObj->VerifyCaptchaThroughMD5File($_POST['phone'],$_POST['verify']);

		$pwd = get_magic_quotes_gpc()==1? $_POST['password']:addslashes($_POST['password']);
		$data['user_login'] = $_POST['phone'];
		$data['password'] = "nciou".md5($pwd)."dijdm";

		$rs = \EloquentModel\User::insertGetId($data);

		if($rs){

            // 加入到员工表中
            // 获取最大工号
            $workNum = \EloquentModel\Staff::where("is_deleted", 0)->max("work_num")+1;
            $user = \EloquentModel\User::find($rs);
            // 新建记录
            $temp = [];
            $temp['name'] = empty($user['nickname'])? '':$user['nickname'];
            $temp['phone'] = $user['user_login'];
            $temp['gender'] = $user['gender'];
            $temp['create_time'] = time();
            $temp['update_time'] = time();
            $temp['work_num'] = $workNum;
            $temp['address'] = '';
            $temp['status'] = 1;
            $temp['user_id'] = $user['id'];
            $rs2 = \EloquentModel\Staff::insert($temp);

			unlink($_SERVER['DOCUMENT_ROOT'].'/tempCache/'.md5($_POST['phone']).'.txt');

			\EloquentModel\RoleToUser::insert(['role_id'=>4, 'user_id'=>$rs]);
			setcookie("user_login",$data['user_login'],time()+3600*5,'/');
			setcookie("user_id",$rs,time()+3600*5,'/');
			echo $this->functionObj->toAppJson(null, '001', '注册成功', true);

		}
		else
			echo $this->functionObj->toAppJson(null, '014', '注册失败', false);

    }

    //修改用户信息
    public function updateUserInfo()
    {
    	if(empty($_COOKIE['user_id'])){
            $this->data = array();
            $this->code = '002';
            $this->message = '修改操作失败';
            $this->success = false;
            echo $this->functionObj->turnToJson($this->data,$this->code,$this->message,$this->success);
            return false;
        }

        $rs = $this->functionObj->editColumnsValueById('\\EloquentModel\\User', [$_POST['name']=>$_POST['value'], 'update_time'=>time() ],['id'=>$_COOKIE['user_id']]);
        $this->data = array();
        $this->code = '000';
        $this->message = $_POST['name'] . '修改操作' . $_POST['value'];
        $this->success = $rs;
        echo $this->functionObj->turnToJson($this->data,$this->code,$this->message,$this->success);
        return false;

    }

    //用户资料
    public function read()
    {	
    	
        parent::isLogin();
    	$user = \EloquentModel\User::find($_COOKIE['user_id']);

    	//一开始为空  加入默认项
    	if(empty($user['avatar']) && empty($user['nickname']) && empty($user['create_time'])){
    		$data['avatar'] = '/images/default-avatar.jpg';
    		$data['nickname'] = $this->getNickName();
    		$data['create_time'] = time();
    		\EloquentModel\User::where('id',$user['id'])->update($data);
    	}

    	require dirname(__FILE__).'/../../views/admin/adminSessions/read.php';

    }

    //单独修改头像
   	public function updateUserAvatar()
	{

        if(empty($_COOKIE['user_id'])){
            $this->data = array();
            $this->code = '002';
            $this->message = '修改操作失败';
            $this->success = false;
            echo $this->functionObj->turnToJson($this->data,$this->code,$this->message,$this->success);
            return false;
        }
		//实例化对象
        $userObj = new \EloquentModel\User;

        //逻辑
        $fileName = $this->functionObj->writePic('/upload/avatars/');

        $data['avatar'] = '/upload/avatars/'.$fileName;
        
        $rs = $userObj->where("id",'=',$_COOKIE['user_id'])->update($data);

        if($rs){
            $this->data = array();
            $this->code = '001';
            $this->message = '修改成功';
            $this->success = true;
        } else {
            $this->data = array();
            $this->code = '003';
            $this->message = '修改操作失败';
            $this->success = false;
        }


        echo $this->functionObj->turnToJson($this->data,$this->code,$this->message,$this->success);

	}

    /**
    * 修改后台登陆用户密码
    */
    public function updateAdminPwd()
    {
        parent::isLogin();
        if(empty($_COOKIE['user_id'])){
            echo json_encode(['success'=>false, 'code'=>'002', 'message'=>'需要登录']);
            return false;
        } else {
            $id = $_COOKIE['user_id'];
            $user = \EloquentModel\User::find($id);
            if($user['password']!=md5($_POST['orderPwd']) && $user['password']!=("nciou".md5($_POST['orderPwd'])."dijdm")){
                echo json_encode(['success'=>false, 'code'=>'028', 'message'=>'密码不正确']);
                return false;
            }

            $rs = \EloquentModel\User::where("id",'=',$id)->update(['password'=>("nciou".md5($_POST['newerPwd'])."dijdm")]);
            if($rs){
                setcookie("user_id", '',time()-3600*5, '/');
                setcookie("user_login", '',time()-3600*5, '/');
                setcookie("kf_admin", '',time()-3600*5, '/');
                echo json_encode(['success'=>true, 'code'=>'001', 'message'=>'更新成功']);
            } else {
                echo json_encode(['success'=>false, 'code'=>'003', 'message'=>'更新失败']);
            }
        }

    }

    /**
    * 登出
    */
    public function logOut()
    {
        setcookie("user_id", '',time()-3600*5, '/');
        setcookie("user_login", '',time()-3600*5, '/');
        setcookie("kf_admin", '',time()-3600*5, '/');
        echo json_encode(['success'=>true]);
    }

	//评论(工单)
	public function advice()
	{
		require dirname(__FILE__).'/../../views/admin/adminSessions/advice.php';
	}

    /**
    * 个人中心
    * render the page
    * @author   xww
    * @return   void
    */ 
    public function mine()
    {
        
        try{

            parent::isLogin();

            header("Location: /admin/user/read");
            exit();

            // if($_COOKIE['user_id']==1) {
            //     header("Location: /admin/user/read");
            //     exit();
            // }

            // 判断用户是否为员工如果是 增加菜单
            $data = \EloquentModel\Staff::where("staff.user_id", $_COOKIE['user_id'])
                                        ->leftJoin("departments", "departments.id", "=", "staff.department_id")
                                        ->leftJoin("jobs", "jobs.id", "=", "staff.job_id")
                                        ->leftJoin("users", "users.id", "=", "staff.user_id")
                                    ->where("staff.is_deleted", 0)
                                    ->select("users.avatar", "users.gender", "users.nickname", "staff.motto", "departments.name", "departments.section", "jobs.name as jobName", "departments.content")
                                    ->take(1)
                                    ->first();

            if(empty($data)) { throw new \Exception("员工不存在"); }

            // 通知公告
            $notices = [];

            $newsClassModelObj =  new \VirgoModel\NewsClassModel;
            $classRecord = $newsClassModelObj->getClassFromName("通知公告");
            if(!empty($classRecord)){
                $newsClassObj = new \VirgoModel\NewsModel;
                $notices = $newsClassObj->getListFromClassId($classRecord[0]['id'],  5, 0);
            }

            // 
            $genderImg = $this->getGenderUrl($data['gender']);

            // 获取条款--当前所有条款可见
            $contractProvisionModelObj = new \VirgoModel\ContractProvisionModel;
            $contractProvisions = $contractProvisionModelObj->all(5, 0);

            // 获取所有动态
            $userDynamicModelObj = new \VirgoModel\UserDynamicModel;
            $dynamics = $userDynamicModelObj->all();

            $noNameCount = '用户';
            for ($i=0; $i < count($dynamics); $i++) { 
                if($dynamics[$i]["user_id"]==0) {
                    $dynamics[$i]["show_name"] = "匿名".$noNameCount;
                    $dynamics[$i]["avatar"] = "/images/no-track.png";
                    // $noNameCount++;
                } else {
                    $dynamics[$i]["show_name"] = empty($dynamics[$i]["name"])? $dynamics[$i]["name"]:$dynamics[$i]["nickname"];
                    $dynamics[$i]["avatar"] = empty($dynamics[$i]["avatar"])? "/images/avatar.png":$dynamics[$i]["avatar"];
                }                
            }

            require dirname(__FILE__).'/../../views/admin/adminSessions/mine.php';

        } catch(\Exception $e){
            echo "<h1>".$e->getMessage()."</h1>";
        }

    }

    /**
    * 性别图标
    * @author   xww
    * @param    int/string      gender num
    * @return   string
    */ 
    public function getGenderUrl($gender)
    {
        switch ((int)$gender) {
            case 2:
                return "/images/female.png";
                break;
            
            default:
                return "/images/male.png";
                break;
        }
    }

}
