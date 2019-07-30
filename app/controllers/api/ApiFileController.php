<?php
namespace VirgoApi;
class ApiFileController{

	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
	}

	/**
	* 文件上传
	* name string
	*/
	public function upload()
	{
		
		$timeStr = date("YmdHis",time());
		if(empty($_FILES)){
			//输出
			echo $this->functionObj->toAppJson(null, '017', '文件不为空', false);
			return false;
		} else {

			$firstDir = $_SERVER['DOCUMENT_ROOT']."/upload/";
			$secondDir = $_SERVER['DOCUMENT_ROOT']."/upload/workOrderAttachments/";

			if(!file_exists($firstDir))
				mkdir($firstDir);
			if(!file_exists($secondDir))
				mkdir($secondDir);

			$return = array();
			foreach ($_FILES as $key => $value) {
				$microtime = microtime(true);
				$microtimeStr = str_replace('.', '', $microtime);
				$extArr = explode('.', $value['name']);

				//后缀
				$ext = array_pop($extArr);

				//名称
				$nameStr = implode('', $extArr);
				
				$relativeUrl = '/upload/workOrderAttachments/'.md5($nameStr.$microtimeStr).'.'.$ext;
				$destination = $_SERVER['DOCUMENT_ROOT'].$relativeUrl;
				$rs = move_uploaded_file($value["tmp_name"], $destination);
				if($rs){
					$temp['url'] = $relativeUrl;
				} else {
					$temp['url'] = '';
				}
				$temp['name'] = $nameStr.".".$ext;

				array_push($return, $temp);

			}

			//输出
			echo $this->functionObj->toAppJson($return, '001', '上传完毕', true);
			return false;
		}

	}

}