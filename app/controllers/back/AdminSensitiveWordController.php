<?php
namespace VirgoBack;
class AdminSensitiveWordController extends AdminBaseController
{
	
	public function __construct()
	{
		$this->pageObj = new \VirgoUtil\Page;
		$this->sensitiveWordObj = new \VirgoModel\SensitiveWordModel;
		$this->functionObj = new \VirgoUtil\Functions;
		parent::isLogin();
	}

	public function index()
	{
		$this->update();
	}

	public function update(){
		$page_title = "修改敏感词";
		$sensitiveWord = $this->sensitiveWordObj->read(1);
		require_once dirname(__FILE__).'/../../views/admin/adminSensitiveWord/_update.php';
	}

	public function doUpdate()
	{
		
		$rs = $this->sensitiveWordObj->doUpdate();
		if($rs){
			header('Refresh: 5;url=/admin/sensitiveWords');
			echo "修改成功";
		} else {
			header('Refresh: 5;url=/admin/sensitiveWords');
			echo "修改失败";
		}

	}

}
