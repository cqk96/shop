<?php
/**
* 
*/
namespace VirgoBack;

use \EloquentModel;
class AdminNavController extends AdminBaseController
{
	public function __construct()
	{
		$this->pageObj = new \VirgoUtil\Page;
		parent::isLogin();
	}
	
	public function lists()
	{
		$page_title = '前台导航';

		// 分页对象
		$pageObj2 = new \VirgoUtil\Page2;

		// 父菜单总记录数
		$totalCount = \EloquentModel\Nav::count();

		//分页的take,size
		$size = 10;
		if(!empty($_GET['page'])){
			$page = (int)$_GET['page'];
			$skip = ($page-1)*$size;
		} else {
			$skip = 0;
		}

		$data2 = \EloquentModel\Nav::skip($skip)->take($size)->get()->toArray();

		//设置页数跳转地址 
		$pageObj2->setUrl('/admin/nav');

		// 设置分页数据
		$pageObj2->setData($data2);

		// 设置记录总数
		$pageObj2->setTotalCount($totalCount);

		// 设置分页大小
		$pageObj2->setSize($size);

		// 进行分页并返回
		$pageObj = $pageObj2->doPage();

		$nav_list = $pageObj->data;

		// 起始组装
		$page = $pageObj->current_page;
		$per_count = $size;
		$record_start = ($page-1)*$per_count;
		// 起始组装--end

		require dirname(__FILE__).'/../../views/admin/adminNavs/index.php';
		
	}
	
	public function create()
	{
		$page_title = "新增前台导航";
	    $nav = new \EloquentModel\Nav;
		require dirname(__FILE__).'/../../views/admin/adminNavs/add.php';

	}
	
	public function update()
	{
		$page_title = "编辑前台导航";
		$id = $_GET['id'];
	    $nav =\EloquentModel\Nav::find($id);
		require dirname(__FILE__).'/../../views/admin/adminNavs/edit.php';
		
	}
	
	public function doCreate()
	{
		$functionsObj = new \VirgoUtil\Functions;
		$obj = new \EloquentModel\Nav;
		$data = $functionsObj->deleteNotNeedDataArray($_POST, array('id'));
		
		$data['created_at'] = time();
		$data['updated_at'] = time();

		$rs = $obj->insert($data);

		header("Refresh: 5;url=/admin/nav");
		if($rs)
			echo "添加前台导航成功";
		else
			echo "添加前台导航失败";

	}

	public function doUpdate()
	{
		/* $functionsObj = new Functions;
		$siteObj = new Site;
		$data = $functionsObj->deleteNotNeedDataArray($_POST, array('id','file'));

		$data['logo'] = !empty($_FILES['logo'])? $this->upload():$data['logo'];
		$data['updated_at'] = time();
		$rs = $siteObj->where('id','=',$_POST['id'])->update($data);
		
		
		header("Refresh: 5;url=/admin/site");
		if($rs)
			echo "修改成功";
		else
			echo "修改失败"; */
		$functionsObj = new \VirgoUtil\Functions;
		$obj = new \EloquentModel\Nav;
		$data = $functionsObj->deleteNotNeedDataArray($_POST, array('id'));
		
		$data['updated_at'] = time();

		$rs = $obj->where('id','=',$_POST['id'])->update($data);

		header("Refresh: 5;url=/admin/nav");
		if($rs)
			echo "修改前台导航成功";
		else
			echo "修改前台导航失败";

	}
	
	//文件上传
    public function upload()
    {
        $picUrl = '';

        //上传根目录是否存在
        if(!file_exists("./upload")){
            mkdir("./upload");
        }
        if(!file_exists("./upload/logo/"))
            mkdir("./upload/logo");
        $dir_array = array();
        //存在上传文件
        if($_FILES){
            foreach ($_FILES as $key => $value) {
                if($value['error']===0){
                    $ext_array = explode(".", $value['name']);
                    $ext = array_pop($ext_array);
                    $name = time().".".$ext;
                    $rs = move_uploaded_file($value['tmp_name'], "./upload/logo/".$name);
                    $picUrl = "/upload/logo/".$name;
                    array_push($dir_array, "/upload/logo/".$name);
                }
            }
        }else {
            $dir_array = array();
        }

        return $picUrl;
        //return json_encode($dir_array);
    }


}