<?php

namespace EloquentModel;
class News extends \Illuminate\Database\Eloquent\Model
{
	//变量
	protected $newsClassesObj;
	protected $table = 'news';
	public $timestamps = false;
	

	public function __construct()
	{
		$this->newsClassesObj = new \EloquentModel\NewsClasses;
	}

	/*获取文章列表*/
	/*
	* param1 string/int class_id 上级栏目id
	* param2 array parameter 需要的字段参数
	*/
	public function getNewsLists($cid='',$params=[])
	{
		$newsObj = new \EloquentModel\News;
		
		if(!empty($cid)){
			$newsObj = $newsObj->where('class_id','=',$cid);
		}

		if(!empty($params)){
			foreach ($params as $key => $value) {
				$newsObj = $newsObj->addSelect($value);
			}	
		}

		return $newsObj->where('pass','=',1)
					   ->where('status','=',0)
					   ->orderBy('top','desc')
					   ->orderBy('updated_at','desc')
					   ->get();

	}

	/*文章显示*/
	public function showNews($id,$params=[],$template='newsTemplate.php',$saveUrl='')
	{
		if (empty($id)) {
			echo "id不能为空";
			return false;
		}

		$functionsObj = new \VirgoUtil\Functions;
		//$navObj = new Nav;

		$saveUrl = empty($saveUrl)? '/tempNews':$saveUrl;
		$functionsObj->mkDir($saveUrl);

		ob_start();

        //$navs = $navObj->getAllNavs();

        $site = \EloquentModel\Site::first();
		$nav_list = \EloquentModel\Nav::where("show",1)->orderBy("order")->get();

		if(!empty($params)){
			$temp = '';
			foreach ($params as $key => $value) {
				$$key = $value;
			}
		}
		
		if(!$functionsObj->fileExists('../../app/templates/'.$template)){
			echo "模板文件".$template."不存在";
			return false;
		}

		require dirname(__FILE__).'/../templates/'.$template;
		$newsStr = ob_get_clean();
		$fname = md5($id);
		if(!$functionsObj->fileExists($saveUrl."/".$fname)){
			//文件不存在
			file_put_contents($_SERVER['DOCUMENT_ROOT'].$saveUrl."/".$fname, $newsStr);
		} else {
			$fileModifyDate = filectime($_SERVER['DOCUMENT_ROOT'].$saveUrl."/".$fname);
			if($fileModifyDate<strtotime($newsData['updated_at']))
				file_put_contents($_SERVER['DOCUMENT_ROOT'].$saveUrl."/".$fname, $newsStr);
		}

		require $_SERVER['DOCUMENT_ROOT'].$saveUrl."/".$fname;

	}

	/*文章显示*/
	public function showApiNews($id,$params=[],$template='apiNewsTemplate.php',$saveUrl='')
	{
		if (empty($id)) {
			echo "id不能为空";
			return false;
		}

		$functionsObj = new \VirgoUtil\Functions;
		//$navObj = new Nav;

		$saveUrl = empty($saveUrl)? '/tempNews':$saveUrl;
		$functionsObj->mkDir($saveUrl);

		ob_start();

        //$navs = $navObj->getAllNavs();

		if(!empty($params)){
			$temp = '';
			foreach ($params as $key => $value) {
				$$key = $value;
			}
		}
		
		if(!$functionsObj->fileExists('/../app/templates/'.$template)){
			echo "模板文件".$template."不存在";
			return false;
		}

		require dirname(__FILE__).'/../templates/'.$template;
		$newsStr = ob_get_clean();
		$fname = md5($id);
		if(!$functionsObj->fileExists($saveUrl."/".$fname)){
			//文件不存在
			file_put_contents($_SERVER['DOCUMENT_ROOT'].$saveUrl."/".$fname, $newsStr);
		} else {
			$fileModifyDate = filectime($_SERVER['DOCUMENT_ROOT'].$saveUrl."/".$fname);
			if($fileModifyDate<strtotime($newsData['updated_at']))
				file_put_contents($_SERVER['DOCUMENT_ROOT'].$saveUrl."/".$fname, $newsStr);
		}

		require $_SERVER['DOCUMENT_ROOT'].$saveUrl."/".$fname;

	}

	//获取所有文章
	public function getAllPosts($fields=[],$needKV=0)
	{
		
		$fieldArr = ['*'];
		$newsObj = new \EloquentModel\News;

		if(!empty($fields) && is_array($fields))
			$fieldArr = $fields;

		$terms = $newsObj->where('pass','=',1)
						 ->where('status','=',0)
						 ->orderBy('top','desc')
						 ->orderBy('updated_at','desc')
						 ->get($fieldArr);

		if(!empty($needKV)){
			$result = array();
			foreach ($terms as $key => $value) {
				$result[$value['id']] = $value;
			}
			unset($terms);
			$terms = $result;
		}

		return $terms;

	}

}