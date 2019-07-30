<?php

namespace VirgoFront;
class ApplicationController extends BaseController {

	public function create()
	{
		$functionsObj = new \VirgoUtil\Functions;
		$obj = new \EloquentModel\Application;
		$data = $functionsObj->deleteNotNeedDataArray($_POST, array('id'));
		
		$data['created_at'] = time();
		$data['updated_at'] = time();

		// $rs = $obj->insert($data);

		// $result = array("success"=>0,"message"=>"发送失败，请稍候尝试或直接联系我们");
		// if($rs){
			$result = array("success"=>1,"message"=>"您的反馈已收到，我们会尽快联系您！"); 

		// 	$body = "咨询时间: ".date("Y-m-d H:i:s", time())."<br />";
		// 	$body = $body."姓名: ".$_POST['name']."<br />";
		// 	$body = $body."邮箱: ".$_POST['email']."<br />";
		// 	$body = $body."咨询主题: ".$_POST['subject']."<br />";
		// 	$body = $body."咨询内容: ".$_POST['content']."<br />";
		// 	// 发送邮件
		// 	$mail = \Mail::to('chengduo@hzhanghuan.com')->from('info@hzhanghuan.com')->title('您收到一封咨询邮件')
		// 		         ->content($body);
	 //        $mailer = new \Nette\Mail\SmtpMailer($mail->config);
	 //        $rs = $mailer->send($mail);
		// }
		echo json_encode($result);
	}
	
}