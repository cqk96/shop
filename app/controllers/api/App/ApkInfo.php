<?php
namespace Annotation;

/**
* @SWG\Definition(type="object")
*
*/
class ApkInfo {

	/**
	* @SWG\Property(
	*     type="integer",
	*     description="开发版本号"
	* )
	*/
	public $versionCode;

	/**
	* @SWG\Property(
	*     type="string",
	*     description="用户版本号"
	* )
	*/
	public $versionText;

	/**
	* @SWG\Property(
	*     type="string",
	*     description="描述"
	* )
	*/
	public $description;

	/**
	* @SWG\Property(
	*     type="string",
	*     description="新包地址,相对地址"
	* )
	*/
	public $url;

}