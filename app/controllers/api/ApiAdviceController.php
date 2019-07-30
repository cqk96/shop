<?php
namespace VirgoApi;
class ApiAdviceController extends ApiBaseController{

	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
	}

	/**
	* 添加建议
	*/
	public function advice()
	{

		ob_clean();
		$validateRs = $this->functionObj->validateApiParams('required',$_POST,['user_login', 'access_token', 'content']);
		if(!$validateRs['success']){
			$messages = implode(',', $validateRs['message']);
			echo $this->functionObj->toAppJson(null, '014', $messages, false);
			return false;
		}

		$this->judgeUser($_POST['access_token'], $_POST['user_login'], 60*60*24*10);

		$user = \EloquentModel\User::where("user_login", '=', $_POST['user_login'])
								   ->where("access_token", '=', $_POST['access_token'])
								   ->where("is_deleted", '=', 0)
								   ->get()
								   ->toArray();

		$data['user_id'] = $user[0]['id'];
		$data['content'] = $_POST['content'];
		if(!empty($_POST['relation'])){
			$data['relation'] = $_POST['relation'];
		}
		$data['create_time'] = time();

		//时长 60S  10条
		$distanceTime = 60;
		$sendCount = 10;
		$has_send_count = \EloquentModel\UserAdvice::whereBetween('create_time', [$data['create_time']-$distanceTime, $data['create_time']])->count();

		if($has_send_count>=$sendCount){
			//输出
			echo $this->functionObj->toAppJson(null, '030', '发送频率频繁', false);
			return true;
		}

		$rs = \EloquentModel\UserAdvice::insert($data);
		if($rs){
			$message = "添加建议成功";
			$code = '001';
			$success = true;
		} else {
			$message = "添加建议失败";
			$code = '005';
			$success = false;
		}

		//输出
		echo $this->functionObj->toAppJson(null, $code, $message, $success);

	}

}