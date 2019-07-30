<?php
/**
* \AdminController
*/
namespace VirgoBack;
class AdminController
{
	public $root = "";
    public $projectRoot = '';

	public function __construct()
	{
		$this->root = $_SERVER['SCRIPT_NAME'];
        $temp = explode('/', $this->root);
        array_pop($temp);
        $this->projectRoot = implode("/", $temp);

        $this->sysMenuObj = new \VirgoModel\SysMenuModel;
        $this->functionObj = new \VirgoUtil\Functions;

        /*文章分类 对象*/
        $this->newsClassModelObj =  new \VirgoModel\NewsClassModel;
	}

    public function home()
    {
		
        AdminBaseController::isLogin();
        $site = \EloquentModel\Site::first();

        $user = \EloquentModel\User::find($_COOKIE['user_id']);

        //一开始为空  加入默认项
        if(empty($user['avatar']) && empty($user['nickname']) && empty($user['create_time'])){
            $data['avatar'] = '/images/default-avatar.jpg';
            $data['nickname'] = $this->getNickName();
            $data['create_time'] = time();
            // $data['user_status'] = 1;
            \EloquentModel\User::where('id',$user['id'])->update($data);
        }

        $backMenus = $this->sysMenuObj->backMenuLists();
        
    	$page_title = "后台首页";
    	$root = $this->root;
        $projectRoot = $this->projectRoot."/";

        if($user['id']!=1){
            
        }

    	require dirname(__FILE__).'/../../views/admin/index.php';
        
    }

    public function login()
    {
    	
        $page_title = "后台管理员登录";
    	$verify_url = $this->root."/"."verify";
    	$check_url = "/admin/"."readUser";
    	$jump_url = "/admin/home";
		$site = \EloquentModel\Site::first();

        // var_dump($verify_url);
        // var_dump($check_url);
        // var_dump($jump_url);
    	// require dirname(__FILE__).'/../../views/admin/login.php';
        require dirname(__FILE__).'/../../views/admin/login2.php';
        
    }

    //判断用户是否存在
    public function readUser()
    {
		
        ob_clean();
        $user_login = $_POST['user_login'];
		$password = get_magic_quotes_gpc()==1? $_POST['password']:addslashes($_POST['password']);
		$verify = $_POST['verify'];
    	//判断验证码
    	if(!isset($_COOKIE['verify'])){// 
    		echo "验证码过期";
    	}else{
    		if($verify!=$_COOKIE['verify']){//
    			echo "验证码出错";
	    	}else{

                //只验证用户是否存在
	    		$users_model = new \EloquentModel\User;
	    		$user = $users_model->select("user_login",'id')
	    					->where("user_login",$user_login)
	    					->whereIn("password", [md5($password), "nciou".md5($password)."dijdm"])
                            ->where("is_deleted", '=', 0)
	    					->take(1)
                            ->first();

	    		if(!empty($user)){
                    //是否是admin
                    if($user['id']==1){
                        setcookie("user_login",$user['user_login'],time()+3600*5,'/');
                        setcookie("user_id",$user['id'],time()+3600*5,'/');
                        echo json_encode(array("success"=>1));
                    } else {
                        
                        //判断是否有操作权限角色
                        $has_privilege = \EloquentModel\RoleToUser::leftJoin("rel_privilege_to_role",'rel_privilege_to_role.role_id', '=', 'rel_role_to_user.role_id')
                                                 ->leftJoin("sys_privileges", 'sys_privileges.id', '=', 'rel_privilege_to_role.privilege_id')
                                                 ->where("rel_role_to_user.user_id", '=', $user['id'])
                                                 ->where("rel_role_to_user.deleted", '=', 0)
                                                 ->where("sys_privileges.type_id", '=', 2002)
                                                 ->select('rel_privilege_to_role.role_id')
                                                 ->groupBy('rel_privilege_to_role.role_id')
                                                 ->get()
                                                 ->toArray();
                                                
                        if(empty($has_privilege)){
                            echo "没有授权";
                        } else {

                            $privileges = array();
                            foreach ($has_privilege as $key => $value) {
                                array_push($privileges, $value['role_id']);
                            }
                            
                            //是否有登陆权限
                            $can_login = \EloquentModel\SysOperate::leftJoin("operate_privilege_to_role", 'sys_operates.id', '=','operate_privilege_to_role.operate_id')
                                                     ->where("operate_privilege_to_role.deleted", '=', 0)
                                                     ->where("sys_operates.type_id", '=', 3001)
                                                     ->where("sys_operates.deleted", '=', 0)
                                                     ->whereIn("operate_privilege_to_role.role_id", $privileges)
                                                     ->count();

                            if(!empty($can_login)){

                                // 更新token
                                $adminUserControllerObj = new \VirgoBack\AdminUserController;
                                $tokenStr = $adminUserControllerObj->getToken();

                                \EloquentModel\User::where("id", $user['id'])->update(['access_token'=>$tokenStr]);

                                setcookie("user_login",$user['user_login'],time()+3600*5,'/');
                                setcookie("user_id",$user['id'],time()+3600*5,'/');
                                echo json_encode(array("success"=>1));
                            } else {
                               echo "没有登陆授权"; 
                            }

                        }

                    }
	    		}else{
	    			echo "用户名或密码错误";
	    		}
	    	}
    	}
    	

    }

    //显示菜单
    public function menus()
    {
        $page_title = "菜单";
        require dirname(__FILE__).'/../../views/admin/menu.php';
    }

    //nickname
    public function getNickName()
    {
    
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
    * 新版登录
    * render the page
    * @author   xww
    * @return   void
    */ 
    public function coolLogin()
    {
        
        try{

            require dirname(__FILE__).'/../../views/admin/cool-login.htm';

        } catch(\Exception $e) {

        }

    }

    /**
    * 新版判断用户登录
    * @author   xww
    * @return   json 
    */ 
    public function readUserVer2()
    {
        
        ob_clean();
        $apiObj = new \VirgoApi\ApiBaseController;

        try {

            $configs = $apiObj->change();

            // 验证
            $apiObj->configValid("required", $configs, ['userLogin', 'password']);

            $user_login = $configs['userLogin'];
            $password = get_magic_quotes_gpc()==1? $configs['password']:addslashes($configs['password']);

            $users_model = new \EloquentModel\User;
            $user = $users_model->select("user_login",'id')
                        ->where("user_login",$user_login)
                        ->whereIn("password", [md5($password), "nciou".md5($password)."dijdm"])
                        ->where("is_deleted", '=', 0)
                        ->take(1)
                        ->first();

            if(empty($user)) { throw new \Exception("用户名或密码错误", '006'); }

            if($user['id']==1){
                setcookie("user_login",$user['user_login'],time()+3600*5,'/');
                setcookie("user_id",$user['id'],time()+3600*5,'/');
            } else {

                //判断是否有操作权限角色
                $has_privilege = \EloquentModel\RoleToUser::leftJoin("rel_privilege_to_role",'rel_privilege_to_role.role_id', '=', 'rel_role_to_user.role_id')
                                         ->leftJoin("sys_privileges", 'sys_privileges.id', '=', 'rel_privilege_to_role.privilege_id')
                                         ->where("rel_role_to_user.user_id", '=', $user['id'])
                                         ->where("rel_role_to_user.deleted", '=', 0)
                                         ->where("sys_privileges.type_id", '=', 2002)
                                         ->select('rel_privilege_to_role.role_id')
                                         ->groupBy('rel_privilege_to_role.role_id')
                                         ->get()
                                         ->toArray();

                if(empty($has_privilege)) { throw new \Exception("没有权限", '058'); }

                $privileges = array();
                foreach ($has_privilege as $key => $value) {
                    array_push($privileges, $value['role_id']);
                }
                
                //是否有登陆权限
                $can_login = \EloquentModel\SysOperate::leftJoin("operate_privilege_to_role", 'sys_operates.id', '=','operate_privilege_to_role.operate_id')
                                         ->where("operate_privilege_to_role.deleted", '=', 0)
                                         ->where("sys_operates.type_id", '=', 3001)
                                         ->where("sys_operates.deleted", '=', 0)
                                         ->whereIn("operate_privilege_to_role.role_id", $privileges)
                                         ->count();

                if(empty($can_login)) { throw new \Exception("没有登陆授权", '058'); }

                setcookie("user_login",$user['user_login'],time()+3600*5,'/');
                setcookie("user_id",$user['id'],time()+3600*5,'/');

            }

            // 更新token
            $tokenStr = $this->getNewToken();

            $users_model->where("id", $user['id'])->update(['access_token'=>$tokenStr]);

            $return = $this->functionObj->toAppJson(null, '001', '登录成功', true);

        } catch(\Exception $e) {
            $return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
        } finally {
            $apiObj->responseResult($return);
        }

    }

    /**
    * 新版判断用户登录
    * @author   xww
    * @return   json 
    */ 
    public function readUserVer3()
    {
        
        ob_clean();
        $apiObj = new \VirgoApi\ApiBaseController;

        try {

            $configs = $apiObj->change();

            // 验证
            $apiObj->configValid("required", $configs, ['user_login', 'password']);

            // $verify = $configs['verify'];

            // if(!isset($_COOKIE['verify'])){// 
            //     throw new \Exception("验证码过期", '006');
            // }

            // 
            // if($verify!=$_COOKIE['verify']){//
            //     throw new \Exception("验证码出错", '006');
            // }
                

            $user_login = $configs['user_login'];
            $password = get_magic_quotes_gpc()==1? $configs['password']:addslashes($configs['password']);

            $users_model = new \EloquentModel\User;
            $user = $users_model->select("user_login",'id')
                        ->where("user_login",$user_login)
                        ->whereIn("password", [md5($password), "nciou".md5($password)."dijdm"])
                        ->where("is_deleted", '=', 0)
                        ->take(1)
                        ->first();

            if(empty($user)) { throw new \Exception("用户名或密码错误", '006'); }

            if($user['id']==1){
                setcookie("user_login",$user['user_login'],time()+3600*5,'/');
                setcookie("user_id",$user['id'],time()+3600*5,'/');
            } else {

                //判断是否有操作权限角色
                $has_privilege = \EloquentModel\RoleToUser::leftJoin("rel_privilege_to_role",'rel_privilege_to_role.role_id', '=', 'rel_role_to_user.role_id')
                                         ->leftJoin("sys_privileges", 'sys_privileges.id', '=', 'rel_privilege_to_role.privilege_id')
                                         ->where("rel_role_to_user.user_id", '=', $user['id'])
                                         ->where("rel_role_to_user.deleted", '=', 0)
                                         ->where("sys_privileges.type_id", '=', 2002)
                                         ->select('rel_privilege_to_role.role_id')
                                         ->groupBy('rel_privilege_to_role.role_id')
                                         ->get()
                                         ->toArray();

                if(empty($has_privilege)) { throw new \Exception("没有权限", '058'); }

                $privileges = array();
                foreach ($has_privilege as $key => $value) {
                    array_push($privileges, $value['role_id']);
                }
                
                //是否有登陆权限
                $can_login = \EloquentModel\SysOperate::leftJoin("operate_privilege_to_role", 'sys_operates.id', '=','operate_privilege_to_role.operate_id')
                                         ->where("operate_privilege_to_role.deleted", '=', 0)
                                         ->where("sys_operates.type_id", '=', 3001)
                                         ->where("sys_operates.deleted", '=', 0)
                                         ->whereIn("operate_privilege_to_role.role_id", $privileges)
                                         ->count();

                if(empty($can_login)) { throw new \Exception("没有登陆授权", '058'); }

                setcookie("user_login",$user['user_login'],time()+3600*5,'/');
                setcookie("user_id",$user['id'],time()+3600*5,'/');

            }

            // 更新token
            $tokenStr = $this->getNewToken();

            $users_model->where("id", $user['id'])->update(['access_token'=>$tokenStr]);

            $return = $this->functionObj->toAppJson(null, '001', '登录成功', true);

        } catch(\Exception $e) {
            $return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
        } finally {
            $apiObj->responseResult($return);
        }

    }

    //token
    public function getNewToken()
    {
        $functionObj = $this->functionObj;

        $ok = true;
        $access_token = '';
        while($ok){
            $tokenStr = $functionObj->tokenStr();
            $token_is_used = \EloquentModel\User::where('access_token','=',$tokenStr)->get();
            if(count($token_is_used)==0){
                $access_token = $tokenStr;
                $ok = false;
            }
        }

        return $access_token;

    }

}
