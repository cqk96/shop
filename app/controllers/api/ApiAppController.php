<?php
namespace VirgoApi;
class ApiAppController extends ApiBaseController{

	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
	}

	public function latest()
	{
		
		ob_clean();
		$validateRs = $this->functionObj->validateApiParams('required',$_POST,['appId']);
		if(!$validateRs['success']){
			$messages = implode(',', $validateRs['message']);
			echo $this->functionObj->toAppJson(null, '014', $messages, false);
			return false;
		}

		$apk = \EloquentModel\App::where("app_id", "=", $_POST['appId'])
						  ->orderBy("version_code", 'desc')
						  ->take(1)
						  ->get()
						  ->toArray();

		if(empty($apk)){
			echo $this->functionObj->toAppJson(null, '006', '沒有符合條件數據', false);
			return false;
		} else {
			if(!file_exists($_SERVER['DOCUMENT_ROOT'].$apk[0]['apk_url'])){
				echo $this->functionObj->toAppJson(null, '038', '文件不存在', false);
				return false;
			} else {
				$data['version_code'] = $apk[0]['version_code'];
				$data['version_name'] = $apk[0]['version_name'];
				$data['url'] = "http://".$_SERVER['HTTP_HOST'].$apk[0]['apk_url'];
				$data['description'] = $apk[0]['description'];
				echo $this->functionObj->toAppJson($data, '001', '獲取成功', true);
				return false;
			}
		}

	}

}