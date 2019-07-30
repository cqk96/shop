<?php
namespace VirgoFront\ArchiveTemplate;
use VirgoFront;
class ArchiveTemplateController extends VirgoFront\BaseController {

	/**
	* 显示档案
	* @author 	xww
	* @return 	void
	*/
	public function show($value='')
	{
		
		try{

			if( empty($_REQUEST['id']) || empty($_REQUEST['userLogin'])  || empty($_REQUEST['accessToken']) || empty($_REQUEST['dataType']) || empty($_REQUEST['ids']) ) {
				throw new \Exception("Wrong Param");
			}

			$id = $_REQUEST['id'];
			$userLogin = $_REQUEST['userLogin'];
			$accessToken = $_REQUEST['accessToken'];
			$dataType = $_REQUEST['dataType'];
			$ids = $_REQUEST['ids'];

			// 获取用户
			$user = \EloquentModel\User::where("is_deleted", 0)->where("user_login", $userLogin)->where("access_token", $accessToken)->first();

			if( empty($user) ) {
				throw new \Exception("用户不存在", '006');
			}

			$model = new \VirgoModel\ArchiveTemplateModel;

			// 判断数据
			$data = $model->readSingleTon( $id );

			if( empty($data) ) {
				throw new \Exception("无法查询到模板数据");
			}

			// 解码json
			$modelData = json_decode($data['model_data'], true);

			if( empty($modelData) ) {
				throw new \Exception("模板数据为空");	
			}

			$dataCount = count($modelData);
			// var_dump($modelData);

			require dirname(__FILE__)."/../../../views/front/archiveTemplate/show.php";

		} catch(\Exception $e) {
			echo $this->showHtmlNotice( $e->getMessage() );
		}

	}

	public function showContent()
	{

		try{

			if( empty($_REQUEST['id']) || empty($_REQUEST['userLogin'])  || empty($_REQUEST['accessToken']) || empty($_REQUEST['dataType']) || empty($_REQUEST['ids']) ) {
				throw new \Exception("Wrong Param");
			}

			$id = $_REQUEST['id'];
			$userLogin = $_REQUEST['userLogin'];
			$accessToken = $_REQUEST['accessToken'];
			$dataType = $_REQUEST['dataType'];
			$ids = $_REQUEST['ids'];

			// 获取用户
			$user = \EloquentModel\User::where("is_deleted", 0)->where("user_login", $userLogin)->where("access_token", $accessToken)->first();

			if( empty($user) ) {
				throw new \Exception("用户不存在", '006');
			}

			$model = new \VirgoModel\ArchiveTemplateModel;

			// 判断数据
			$data = $model->readSingleTon( $id );

			if( empty($data) ) {
				throw new \Exception("无法查询到模板数据");
			}

			// 解码json
			$modelData = json_decode($data['model_data'], true);

			if( empty($modelData) ) {
				throw new \Exception("模板数据为空");	
			}

			$dataCount = count($modelData);
			// var_dump($modelData);

			require_once dirname(__FILE__)."/../../../views/front/archiveTemplate/show.php";

			// $str = ob_get_contents();

			// ob_clean();

			// $model = new \VirgoUtil\Functions;

			// $apiBase = new \VirgoApi\ApiBaseController;

			// $response = $model->toAppJson($str, "001", "ok", true);

			// echo $apiBase->responseResult( $response );

		} catch(\Exception $e) {
			echo $this->showHtmlNotice( $e->getMessage() );
		}

	}

}