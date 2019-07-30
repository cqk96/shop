<?php
/**
* 
*/
namespace VirgoBack;

use \EloquentModel;
class AdminNewsClassesController extends AdminBaseController
{

	public function __construct()
	{
		parent::isLogin();
	}

	public function create()
	{
		$newsClassesObj = new \EloquentModel\NewsClasses;

		$page_title = '添加新闻栏目';
		$newsClasses = $newsClassesObj->lists();
		$newsClass['class_name'] = '';
		$newsClass['pclass_id'] = '';
		$newsClass['hidden'] = '';
		$newsClass['id'] = '';

		require dirname(__FILE__).'/../../views/admin/adminNewsClasses/add.php';
	}

	public function read()
	{
		$newsClassesObj = new \EloquentModel\NewsClasses;

		if($_POST)
			$id = $_POST['id'];
		else
			$id = $_GET['id'];
		$page_title = '';

		$newsClasses = $newsClassesObj->lists();
		$newsClass = $newsClassesObj->find($id);
		$newsClass['hidden'] = $newsClass->hidden;
		
		require dirname(__FILE__).'/../../views/admin/adminNewsClasses/read.php';
	}

	public function update()
	{
		$newsClassesObj = new \EloquentModel\NewsClasses;

		if($_POST)
			$id = $_POST['id'];
		else
			$id = $_GET['id'];
		$page_title = '修改新闻栏目';

		$newsClasses = $newsClassesObj->lists();
		$newsClass = $newsClassesObj->find($id);
		$newsClass['hidden'] = $newsClass->hidden;

		require dirname(__FILE__).'/../../views/admin/adminNewsClasses/edit.php';

	}

	public function delete()
	{
		$newsClassesObj = new \EloquentModel\NewsClasses;
		$functionsObj = new \VirgoUtil\Functions;

		if($_POST)
			$ids = implode(',', $_POST['ids']);
		else 
			$ids = $_GET['id'];

		$rs = $newsClassesObj->whereRaw('id in('.$ids.')')->update(array('status'=>1));
		
		//循环删除子选项
		$deleteItems = explode(',', $ids);
		$this->softDeleteSubItems($deleteItems);

		if($rs){
			if(!$_POST){
				header("Refresh: 5;url=/admin/newsClasses");
				echo "删除成功";
			} else {
				echo $functionsObj->turnToJson(array(),'001','删除成功',true);
			}
		} else {
			if(!$_POST){
				header("Refresh: 5;url=/admin/newsClasses");
				echo "删除失败";
			} else {
				echo $functionsObj->turnToJson(array(),'012','删除失败',false);
			}
		}

	}

	public function lists()
	{
		
		$newsClassesObj = new \EloquentModel\NewsClasses;

		$page_title = '新闻栏目管理';

		$pageObj = $newsClassesObj->getAllNodes();

		require dirname(__FILE__).'/../../views/admin/adminNewsClasses/index.php';
	}

	//todo
	public function doCreate()
	{
		
		$newsClassesObj = new \EloquentModel\NewsClasses;
		$functionsObj = new \VirgoUtil\Functions;
		$configs = $functionsObj->deleteNotNeedDataArray($_POST, array('p_first','id','cover'));
		if($_POST['p_first']==0)
 			$configs['pclass_id'] = 0;

 		$configs['cover'] = '';

 		if(!empty($_FILES['coverPic']['name'])) {
 			$prs = $functionsObj->uploadFile('/upload/cover');
 			
 			if(!$prs[0]['success']) {
 				header("Refresh: 5;url=/admin/newsClasses");
 				echo "上传失败,可能格式不正确";
 				return false;
 			}
 			$configs['cover'] = $prs[0]['picurl'];
 		}
 		
		$rs = $newsClassesObj->insert($configs);
		header("Refresh: 5;url=/admin/newsClasses");
		if($rs){
			echo "添加成功";
		} else {
			echo "添加成功";
		}

	}

	public function doUpdate()
	{
		
		$newsClassesObj = new \EloquentModel\NewsClasses;
		$functionsObj = new \VirgoUtil\Functions;
		$configs = $functionsObj->deleteNotNeedDataArray($_POST, array('p_first','id'));
		if($_POST['p_first']==0)
 			$configs['pclass_id'] = 0;

 		if(!empty($_FILES['coverPic']['name'])) {
 			$prs = $functionsObj->uploadFile('/upload/cover');
 			
 			if(!$prs[0]['success']) {
 				header("Refresh: 5;url=/admin/newsClasses");
 				echo "上传失败,可能格式不正确";
 				return false;
 			}
 			$configs['cover'] = $prs[0]['picurl'];
 		}

		$rs = $newsClassesObj->where('id','=',$_POST['id'])->update($configs);
		header("Refresh: 5;url=/admin/newsClasses");
		if($rs){
			echo "修改成功";
		} else {
			echo "修改成功";
		}

	}

	public function getAllClasses()
	{
		
		$newsClassesObj = new \EloquentModel\NewsClasses;

		$page_title = '新闻栏目管理';
		$newsClasses = $newsClassesObj->getNewsClasses();
		require dirname(__FILE__).'/../../views/admin/adminNewsClasses/index.php';
		
	}


	//删除所有自选项
	public function softDeleteSubItems($idArr)
	{
		$newsClassesObj = new \EloquentModel\NewsClasses;
		foreach ($idArr as $key => $value) {
			$currentItems = $newsClassesObj->where('pclass_id', '=', $value)
										  ->get()
										  ->toArray();
			
			$newsClassesObj->where('id',$value)->update(['status'=>1]);							  
			if(!empty($currentItems)){
				$ids = array();
				foreach ($currentItems as $key2 => $value2) {
					array_push($ids, $value2['id']);
				}
				$this->softDeleteSubItems($ids);
			}

		}
	}

}