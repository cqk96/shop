<?php
/**
* 
*/
namespace VirgoBack;
class AdminAppController extends AdminBaseController{
	
	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
	}

	/**
	* 列表
	* @todo  lists
	*/
	public function lists()
	{
		parent::isLogin();
		$page_title = "app管理";
		$appIds = \EloquentModel\App::select("app_id")->groupBy("app_id")->get()->toArray();
		if(empty($appIds)){
			$data = [];
		} else {
			$data = array();
			foreach ($appIds as $key => $value) {
				$app = \EloquentModel\App::where("app_id", '=', $value['app_id'])
								  ->orderBy("version_code", "desc")
								  ->take(1)
								  ->get()
								  ->toArray();
				array_push($data, $app[0]);
			}
		}
		require dirname(__FILE__).'/../../views/admin/adminApp/index.php';

	}

	/**
	* 添加
	*/
	public function create()
	{
		parent::isLogin();
		$page_title = "添加app";
		require dirname(__FILE__).'/../../views/admin/adminApp/add.php';
	}

	/**
	* 解析apk
	*/
	public function parseApk()
	{
		
		if(empty($_POST['url']) || !file_exists($_SERVER['DOCUMENT_ROOT'].$_POST['url'])){
			echo json_encode(['data'=>[], "success"=>false, "message"=>"url不爲空或找不到指定文件"]);
			return false;
		}
		try{
			$url = $_POST['url'];
			$apk = new \ApkParser\Parser($_SERVER['DOCUMENT_ROOT'].$url);
			$data['package_name'] = $apk->getManifest()->getPackageName();

			$data['version_code'] = $apk->getManifest()->getVersionCode();

			$data['version_name'] = $apk->getManifest()->getVersionName();

			$labelResourceId = $apk->getManifest()->getApplication()->getLabel();
			$data['name'] = $apk->getResources($labelResourceId)[0];
			$data['create_time'] = time();
			$data['apk_url'] = $url;

			$has_package = \EloquentModel\App::where("package_name", '=', $data['package_name'])
											 ->where('name' , '=' ,$data['name'])
											 ->orderBy('version_code', 'desc')
											 ->get()
											 ->toArray();

			//更新
			if(!empty($_POST['packageName'])){
				if($_POST['packageName']!=$data['package_name']){
					echo json_encode(["success"=>false, "message"=>"解析失败，包名不符"]);
					return false;
				}
			}
			if(empty($has_package)){
				$resourceId = $apk->getManifest()->getApplication()->getIcon();
				$resources = $apk->getResources($resourceId);
				$fileContent = stream_get_contents($apk->getStream($resources[0]));

				//文件夾
				$this->functionObj->mkDir('upload/apks/icons');

				//寫入
				$iconName = time().".png";
				$fp = fopen($_SERVER['DOCUMENT_ROOT']."/upload/apks/icons/".$iconName, "w");
				fwrite($fp, $fileContent);
				fclose($fp);
				$data['icon'] = "/upload/apks/icons/".$iconName;
				$data['app_id'] = $this->getAppId();
				$recordId = \EloquentModel\App::insertGetId($data);
			} else {
				if($data['version_code']<=$has_package[0]['version_code']){
					echo json_encode(['data'=>[], "success"=>false, "message"=>"當前版本<=數據庫最新版本"]);
					return true;
				} else {
					$data['icon'] = $has_package[0]['icon'];
					$data['app_id'] = $has_package[0]['app_id'];
					$recordId = \EloquentModel\App::insertGetId($data);
				}
			}

			$data['id'] = $recordId;
			echo json_encode(['data'=>$data, "success"=>true, "message"=>"解析成功"]);
			return true;
		}catch(\Exception $e){
			echo json_encode(['data'=>[], "success"=>false, "message"=>"解析失敗:".$e->getMessage()]);
			return true;
		}

	}

	/**
	* 更新
	*/
	public function doUpdate()
	{
		
		$id = $_POST['id'];
		unset($_POST['id']);
		$_POST['description'] = empty($_POST['description'])? '暫無描述':$_POST['description'];
		$rs = \EloquentModel\App::where("id", '=', $id)->update($_POST);
		if($rs){
			echo json_encode(['success'=>true, 'message'=>'更新成功']);
		} else {
			echo json_encode(['success'=>false, 'message'=>'更新失敗']);
		}

	}

	/**
	* 取消
	*/
	public function doCancel()
	{

		$rs = \EloquentModel\App::where("id", '=', $_POST['id'])->delete();
		if($rs){
			echo json_encode(['success'=>true, 'message'=>'刪除成功']);
		} else {
			echo json_encode(['success'=>false, 'message'=>'刪除失敗']);
		}

	}

	/**
	* 生成唯一appid
	*/
	public function getAppId()
	{
		
		$time = microtime(true);
		$can = true;
		while ($can) {
			$rand = $this->functionObj->getRandStr($type=4,$length=8);
			$str = $time.$rand;
			$appid = md5($str);
			$has = \EloquentModel\App::where("app_id", '=', $appid)->count();
			if(empty($has)){
				$can = false;
			}
		}
		return $appid;

	}

	/**
	* 显示app详情
	*/
	public function read()
	{
		parent::isLogin();
		if(empty($_GET['appId'])){
			echo "参数不为空";
			return false;
		}

		$lists = \EloquentModel\App::where("app_id", '=', $_GET['appId'])
						  ->orderBy("version_code", 'desc')
						  ->get()
						  ->toArray();

		if(empty($lists)){
			echo "查询不到符合数据";
			return false;
		}
		$page_title = "应用详情";
		$apiWorkOrderObj = new \VirgoApi\ApiWorkOrderController;
		foreach ($lists as $key => $value) {
			$lists[$key]['filesize'] = $apiWorkOrderObj->toSizeString(filesize($_SERVER['DOCUMENT_ROOT'].$value['apk_url']));
		}

		require dirname(__FILE__).'/../../views/admin/adminApp/read.php';
	}

	/**
	* 下載
	*/
	public function download()
	{
		ob_clean();
		if(empty($_GET['id']) || empty($_GET['appId'])){
			echo "id不爲空";
			return false;
		}
		$id = $_GET['id'];
		$data = \EloquentModel\App::where("app_id" , "=", $_GET['appId'])
								  ->where("id", '=', $_GET['id'])
								  ->get()
								  ->toArray();
		if(empty($data)){
			echo "查詢不到數據";
			return false;
		}

		$fname = $_SERVER['DOCUMENT_ROOT'].$data[0]['apk_url'];
		if(!file_exists($fname)){
			echo "文件不存在";
			return false;
		}

		$mime = mime_content_type($fname);
		header("Content-Length: ",filesize($fname));
		header("Content-type: $mime");
		header("Content-Disposition: attachment;filename=".$data[0]['name'].$data[0]['version_code'].".apk");
		echo readfile ( $fname );
	}

	/**
	* 更新描述
	*/
	public function doUpdateDescription()
	{
		$id = $_POST['id'];
		$appId = $_POST['appId'];
		$data['description'] = $_POST['description'];
		$rs = \EloquentModel\App::where("id", '=', $id)
							->where("app_id", '=', $appId)
							->update($data);
		if($rs){
			echo json_encode(['success'=>true, 'index'=>$_POST['index'],'message'=>'更新成功']);
		} else {
			echo json_encode(['success'=>false, 'message'=>'更新失敗']);
		}

	}

	/**
	* 删除老版本
	*/
	public function deleteOlder()
	{
		$appId = $_POST['appId'];
		$id = $_POST['id'];
		$rs = \EloquentModel\App::where("id", '=', $id)
						  ->where("app_id", '=', $appId)
						  ->delete();
		
		if($rs){
			echo json_encode(['success'=>true, 'message'=>'刪除成功']);
		} else {
			echo json_encode(['success'=>false, 'message'=>'刪除失敗']);
		}

	}

	/**
	* 删除整个应用
	*/
	public function doDelete()
	{
		$appId = $_POST['appId'];
		$rs = \EloquentModel\App::where("app_id", '=', $appId)
						  ->delete();
		if($rs){
			echo json_encode(['success'=>true, 'message'=>'刪除成功']);
		} else {
			echo json_encode(['success'=>false, 'message'=>'刪除失敗']);
		}
	}
}