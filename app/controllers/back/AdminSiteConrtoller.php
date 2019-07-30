<?php
/**
* 
*/
namespace VirgoBack;
class AdminSiteController extends AdminBaseController
{

	public function __construct()
	{
		parent::isLogin();
	}

	public function read()
	{
		$page_title = '站点信息';

		$site = \EloquentModel\Site::first();
		if(empty($site)|| sizeof($site)<=0){
			// 若不存在站点信息记录，创建
			$siteObj = new \EloquentModel\Site;
			
			
			$data['created_at'] = time();
			$data['updated_at'] = time();

			$rs = $siteObj->insert($data);
		}
		$site = \EloquentModel\Site::first();
		require dirname(__FILE__).'/../../views/admin/adminSites/read.php';
	}

	public function doUpdate()
	{
		$functionsObj = new \VirgoUtil\Functions;
		$siteObj = new \EloquentModel\Site;
		$data = $functionsObj->deleteNotNeedDataArray($_POST, array('id','file','logo'));
		if($_FILES['logo']['size']>0){
			$data['logo'] = $this->upload();
		}
		$data['updated_at'] = time();
		$rs = $siteObj->where('id','=',$_POST['id'])->update($data);
		
		
		header("Refresh: 2;url=/admin/site");
		if($rs)
			echo "修改成功";
		else
			echo "修改失败";

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