<?php

// 加载所有文件
$rootPath = dirname(__FILE__);
$dirPath = "/module_routes";
$dirObj = dir( $rootPath.$dirPath );

while ( ( $fileName=$dirObj->read() )!==FALSE) {

	if( is_file( $rootPath . $dirPath . '/' . $fileName) && stripos($fileName, "php")!==false ) {
		require_once $rootPath . $dirPath . '/' . $fileName;
	}

}

$dirObj->close();