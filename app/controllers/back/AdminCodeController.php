<?php
namespace VirgoBack;
class AdminCodeController extends AdminBaseController
{
	
	public function __construct()
	{
		$this->pageObj = new \VirgoUtil\Page;
		parent::isLogin();
	}

	public function index()
	{
		$page_title = '兑换码管理';
		$data = \EloquentModel\Code::all();
		//分页实现
		$pageObj = $this->pageObj->page('\\EloquentModel\\Code','/admin/adminCodes',10);
		
		//var_dump($pageObj);

		//分页数据
		$data = $pageObj->data;
		
		//起始组装
		$page = $pageObj->current_page;
		$per_count = $pageObj->per_record;
		$record_start = ($page-1)*$per_count;
		//起始组装--end
		require_once dirname(__FILE__).'/../../views/admin/adminCodes/index.php';
	}

	public function add()
	{
		$page_title = '添加兑换码';
		$data = null;

		require_once dirname(__FILE__).'/../../views/admin/adminCodes/add.php';
	}
	
	public function create()
	{
		$functionsObj = new \VirgoUtil\Functions;
		$obj = new \EloquentModel\Code;
		$data = $functionsObj->deleteNotNeedDataArray($_POST, array('id'));
		
		$data['expire_datetime'] = strtotime($_POST['expire_datetime']);
		$data['created_at'] = time();
		$data['updated_at'] = time();
		$rs = $obj->insert($data);
		header("Refresh: 2;url=/admin/codes");
		if($rs)
			echo "添加成功";
		else
			echo "添加失败";
	}

	public function read()
	{

	}

	public function update()
	{
		$page_title = '修改用户';
		$gender = array('男','女','保密');
		$identity = array('管理员','主播','用户');
		$id = $_GET['id'];
		$user = User::find($id);

		require_once dirname(__FILE__).'/../views/admin/adminUser/_update.php';
	}

	public function delete()
	{

	}

	public function doCreate()
	{
		$functionObj = new Functions;

		//检测账号唯一性
		$userLogin_is_used = User::where('user_login', '=', $_POST['user_login'])->get();

		if(!count($userLogin_is_used)==0){
			header('Refresh: 5;url=/admin/users');
			echo "该账号已被使用";
			return false;
		}

		//头像
		$uploadAvatarResult = empty($_FILES['avatarCover']['name'])? '/images/defaultAvatar.png':$functionObj->specialUploadFile('avatarCover','/upload/avatarCover/',array('jpg','png'));

		//背景图
		$uploadBackgroundResult = empty($_FILES['backgroundCover']['name'])? '/images/defaultBackground.jpg':$functionObj->specialUploadFile('backgroundCover','/upload/backgroundCover/',array('jpg','png'));

		//头像结果
		if(!empty($_FILES['avatarCover']['name'])){
			if(!$uploadAvatarResult[0]['success']) {
				header('Refresh: 5;url=/admin/users');
				echo "上传头像图片失败";
				if(!$uploadAvatarResult[0]['validate'])
					echo "格式不正确，请上传jpg和png格式的文件";
				return false;
			}
			$uploadAvatarResult = $uploadAvatarResult[0]['picurl'];
		}

		//背景结果
		if(!empty($_FILES['backgroundCover']['name'])){
			if(!$uploadBackgroundResult[0]['success']) {
				header('Refresh: 5;url=/admin/users');
				echo "上传头像背景图片失败";
				if(!$uploadBackgroundResult[0]['validate'])
					echo "格式不正确，请上传jpg和png格式的文件";
				return false;
			}
			$uploadBackgroundResult = $uploadBackgroundResult[0]['picurl'];
		}

		$data['user_login'] = empty($_POST['user_login'])? '':htmlentities(trim($_POST['user_login']));
		$data['password'] = md5(123456);
		$data['access_token'] = $this->getToken();
		$data['token_expire_time'] = date('Y-m-d',(time()+60*60*24*10));
		$data['avatar'] = $uploadAvatarResult;
		$data['background'] = $uploadBackgroundResult;
		$data['nickname'] = empty($_POST['nickname'])? $this->getNickName():$_POST['nickname'];
		$data['address'] = empty($_POST['address'])? '':$_POST['address'];
		$data['score'] = empty($_POST['score'])? 0:(int)$_POST['score'];
		$data['account_money'] = empty($_POST['account_money'])? 0.0:(float)$_POST['account_money'];
		$data['gender'] = empty($_POST['gender'])? 3:(int)$_POST['gender'];
		$data['age'] = empty($_POST['age'])? '':(int)$_POST['age'];
		$data['introduce'] = empty($_POST['introduce'])? '':$_POST['introduce'];
		$data['user_type'] = empty($_POST['user_type'])? 3:(int)$_POST['user_type'];
		$data['user_status'] = 0;
		$data['create_time'] = time();
		$data['user_pass'] = empty($_POST['user_pass'])? 0:(int)$_POST['user_pass'];

		$rs = User::insert($data);

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
		$functionObj = new Functions;
		$userObj = new User;

		if($_FILES['avatarCover']['name']==''){
			$avatarCover = $_POST['avatar'];
		} else {
			$uploadResult1 = $functionObj->specialUploadFile('avatarCover','/upload/avatarCover/',array('jpg','png'));
		
			if(!$uploadResult1[0]['success']){
				header('Refresh: 5;url=/admin/users');
				echo "上传图片失败";
				if(!$uploadResult1[0]['validate'])
					echo "格式不正确，请上传jpg和png格式的文件";
				return false;
			}
			$avatarCover = $uploadResult1[0]['picurl'];
		}

		if($_FILES['backgroundCover']['name']==''){
			$backgroundCover = $_POST['background'];
		} else {
			$uploadResult2 = $functionObj->specialUploadFile('backgroundCover','/upload/backgroundCover/',array('jpg','png'));
		
			if(!$uploadResult2[0]['success']){
				header('Refresh: 5;url=/admin/users');
				echo "上传图片失败";
				if(!$uploadResult[0]['validate'])
					echo "格式不正确，请上传jpg和png格式的文件";
				return false;
			}
			$backgroundCover = $uploadResult2[0]['picurl'];
		}
		
		//判断当前时间
		$userData = User::select('token_expire_time')->find($_POST['id']);
		$currentDate = date('Y-m-d',(time()+60*60*24*10));
		if($userData['token_expire_time']>=$currentDate){
			$data['access_token'] = $this->getToken();
			$data['token_expire_time'] = $currentDate;
		}
		
		$data['user_login'] = empty($_POST['user_login'])? '':htmlentities(trim($_POST['user_login']));
		$data['avatar'] = $avatarCover;
		$data['background'] = $backgroundCover;
		$data['nickname'] = empty($_POST['nickname'])? $this->getNickName():$_POST['nickname'];
		$data['address'] = empty($_POST['address'])? '':$_POST['address'];
		$data['gender'] = empty($_POST['gender'])? 3:(int)$_POST['gender'];
		$data['age'] = empty($_POST['age'])? '':(int)$_POST['age'];
		$data['introduce'] = empty($_POST['introduce'])? '':$_POST['introduce'];
		$data['user_type'] = empty($_POST['user_type'])? 3:(int)$_POST['user_type'];
		$data['user_pass'] = empty($_POST['user_pass'])? 0:(int)$_POST['user_pass'];
		
		$rs = User::where('id', '=', $_POST['id'])->update($data);
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
		$userObj = new User;

		$id = $_GET['id'];
		$user = User::find($id);
		$data['user_status'] = 1;
		$rs = User::where('id', '=', $id)->update($data);
		if($rs){
			header('Refresh: 5;url=/admin/users');
			echo "删除成功";
		} else {
			header('Refresh: 5;url=/admin/users');
			echo "删除失败";
		}
	}

	/*其他函数*/

	//token
	public function getToken()
	{
		$functionObj = new Functions;

		$ok = true;
		$access_token = '';
		while($ok){
			$tokenStr = $functionObj->tokenStr();
			$token_is_used = User::where('access_token','=',$tokenStr)->get();
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
		$functionObj = new Functions;
	
		$ok = true;
		$nickname = '';
		while($ok){
			$nicknameStr = $functionObj->getNickName();
			$nickname_is_used = User::where('nickname','=',$nicknameStr)->get();
			if(count($nickname_is_used)==0){
				$nickname = $nicknameStr;
				$ok = false;
			}
		}

		return $nickname;

	}

}
