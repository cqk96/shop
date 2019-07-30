<?php
namespace VirgoFront\Archive;
use VirgoFront;
class ArchiveController extends VirgoFront\BaseController {

	public function read()
	{

		try{

			// dataType =》 1作物，2片区， id为对应模板数据的记录id
			if( empty($_GET['id']) || empty($_GET['dataType']) ) {
				throw new \Exception("Wrong Param");
			}

			$id = $_GET['id'];
			$dataType = $_GET['dataType'];

			if($dataType==1) {
				$dataModel = new \VirgoModel\CropTemplateDataModel;
			} else {
				$dataModel = new \VirgoModel\AreaTemplateDataModel;
			}

			$data = $dataModel->getTemplateInfo( $id );

			if( empty($data) ) {
				throw new \Exception("数据不存在");	
			}

			// 解码json
			$modelData = json_decode($data['model_data'], true);

			$dataCount = count($modelData);

			// 用户提交的数据
			$userData = json_decode($data['template_data'], true);

			require dirname(__FILE__)."/../../../views/front/archive/read.php";

		} catch(\Exception $e) {
			echo $this->showHtmlNotice( $e->getMessage() );
		}

	}

}