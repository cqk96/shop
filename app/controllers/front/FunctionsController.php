<?php

namespace VirgoFront;
class FunctionsController extends BaseController {

	public function uploadJpegImage()
	{
		//实例化对象
        $this->funcObj = new \VirgoUtil\Functions;

        $fileName = $funcObj->writePic('/upload/avatars/');

        $avatarUrl = '/upload/avatars/'.$fileName;

        echo $funcObj->turnToJson(array('url'=>$avatarUrl),'001','上传头像成功',true);

	}


	//注册的验证码
	public function registerVerify()
	{
		$verifyObj = new VerifyController;
		$verifyObj->set_bg_color(255, 255, 255);
		$verifyObj->set_str_color(69, 228, 221);
		$verifyObj->is_pixel = false;
		//$verifyObj->set_pixel_color(255, 255, 255);
		$verifyObj->verifica();
	}

	//手机号是否已存在
	public function hasPhone()
	{
		
		$phone = $_POST['phone'];
		$has = \EloquentModel\User::where('user_login', '=', $phone)->get();
		if(count($has)==0)
			echo json_encode(['success'=>false]);
		else
			echo json_encode(['success'=>true]);

	}

}