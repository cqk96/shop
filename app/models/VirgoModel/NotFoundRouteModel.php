<?php
namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class NotFoundRouteModel {

	/**
	*
	*
	*/
	public static function parseRoute()
	{

		$queryString = $_SERVER['QUERY_STRING'];

		$str_0 = $queryString[0];

		$searchQuery = $str_0=="/"? substr($queryString, 1):$queryString;
		
		/*查询商品二级目录是否有此条商品记录 无则另外处理*/
		$codModel = new \VirgoModel\CodModel;
		$cod = $codModel->searchCatalog( $searchQuery );

		if( !empty( $cod ) ) {
			/*是定义了二级目录的商品*/
			$url = "http://" . $_SERVER['HTTP_HOST'] . "/style" . $cod['template'] . "/index.html?id=" . $cod['id'] ;
			$title = $cod['title'];

			/*加载通用页面*/
			require_once $_SERVER['DOCUMENT_ROOT'] . '/commonEntry.php';
		} else { 
			echo "404";
		}

	}

}