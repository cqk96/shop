<?php
namespace VirgoApi;
class ApiUploadController extends ApiBaseController{
	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
	}

	/**
	* 上傳apk single file
	*/
	public function uploadApk()
	{
		ob_clean();
		if(empty($_FILES)){
			echo $this->functionObj->toAppJson(null, '018', 'apk文件不爲空', false);
			return false;
		}

		foreach ($_FILES as $name => $valArr) {
			$mime = mime_content_type($valArr['tmp_name']);
			
			if($mime!="application/jar" && $mime!="application/java-archive"){
				echo $this->functionObj->toAppJson(null, '037', '文件類型錯誤 ,apk文件', false);
				return false;		
			}

			//文件夾
			$this->functionObj->mkDir('upload/apks');

			//上傳
			$fname = "/upload/apks/".time().".apk";
			$rs = move_uploaded_file($valArr['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$fname);
			if($rs){
				echo $this->functionObj->toAppJson($fname, '001', '文件上傳成功', true);
			} else {
				echo $this->functionObj->toAppJson(null, '017', '文件上傳失敗', false);
			}
			return true;
		}

	}

}