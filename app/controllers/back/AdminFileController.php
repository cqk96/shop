<?php
namespace VirgoBack;
class AdminFileController extends AdminBaseController
{
	
	public function __construct()
	{
		$this->pageObj= new \VirgoUtil\Page;
	}

	public function index()
	{
		parent::isLogin();
		$page_title = '文件管理';
		$data = \EloquentModel\File::all();
		
		//分页实现
		$pageObj = $this->pageObj->page('\\EloquentModel\\File','/admin/adminFiles',10);
		
		//var_dump($pageObj);

		//分页数据
		$data = $pageObj->data;
		
		//起始组装
		$page = $pageObj->current_page;
		$per_count = $pageObj->per_record;
		$record_start = ($page-1)*$per_count;
		//起始组装--end
		
		require_once dirname(__FILE__).'/../../views/admin/adminFiles/index.php';
	}
	
	public function show()
	{
		 
	}
	
	public function add()
	{
		parent::isLogin();
		$page_title='上传文件';
		$data = null;
		require_once dirname(__FILE__).'/../../views/admin/adminFiles/add.php';
	}
	
	public function create()
	{
		$functionsObj = new \VirgoUtil\Functions;
		$obj = new \EloquentModel\File;
		$data = $functionsObj->deleteNotNeedDataArray($_POST, array('id','file','pictures'));
		//判断文件大小是否超过5M
		
		if ((($_FILES["file"]["type"][0] == "image/gif")
		|| ($_FILES["file"]["type"][0] == "image/jpeg")
		|| ($_FILES["file"]["type"][0] == "image/png")
		|| ($_FILES["file"]["type"][0] == "image/bmp")
		|| ($_FILES["file"]["type"][0] == "text/xml")
		|| ($_FILES["file"]["type"][0] == "text/plain")
		|| ($_FILES["file"]["type"][0] == "audio/mp3") //mp3
		|| ($_FILES["file"]["type"][0] == "video/mpeg")
		|| ($_FILES["file"]["type"][0] == "video/mp4")
		|| ($_FILES["file"]["type"][0] == "application/pdf")
		|| ($_FILES["file"]["type"][0] == "application/msword")
		|| ($_FILES["file"]["type"][0] == "application/vnd.ms-excel")
		|| ($_FILES["file"]["type"][0] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")
		|| ($_FILES["file"]["type"][0] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
		|| ($_FILES["file"]["type"][0] == "application/octet-stream")
		|| ($_FILES["file"]["type"][0] == "application/zip")
		|| ($_FILES["file"]["type"][0] == "image/pjpeg"))
		&& ($_FILES["file"]["size"][0] < 8000000))
		  {
		  if ($_FILES["file"]["error"][0] > 0)
			{
			echo "Error: " . $_FILES["file"]["error"][0] . "<br />";
			}
		  else
			{	
				//操作数据表
				$fileUploader = new \VirgoUtil\UploadEverything;
				$fileUploader->setSaveUrl('/upload/files');
				$fileUploader->doUpload();
				
				if(!empty($fileUploader->getErrorMessage())){
					header("Refresh: 5;url=/admin/file/edit?id=".$_POST['id']);
					echo "<script>alert('修改失败');</script>";
					return true;
				}
				$messages = $fileUploader->getSuccessMessage();
				$data['content'] = $messages[0]['url'];
				
				$data['create_time'] = time();
				
				$data['name']=$_FILES["file"]["name"][0];
				$data['type'] = $fileUploader->typeValueRewrite($_FILES["file"]["type"][0]);
				
				//$data['resource_name']=$_FILES["file"]["name"]
				
				$rs = $obj->insert($data);
				if($rs){
					echo "添加成功<br>";
					echo "上传文件成功！<br>";
					echo "Upload: " . $_FILES["file"]["name"][0] . "<br />";
					echo "Type: " . $_FILES["file"]["type"][0] . "<br />";
					if ($_FILES["file"]["size"][0]>1000000) 
					{
						echo "Size: " . ($_FILES["file"]["size"][0] / 1024/1024) ."Mb<br />";
					} 
					else 
					{
						echo "Size: " . ($_FILES["file"]["size"][0] / 1024) . " Kb<br />";
					}  
					echo "temp file: " . $_FILES["file"]["tmp_name"][0]."<br />";
					  if (file_exists("upload/files/" . $_FILES["file"]["name"][0]))
					  {
						echo $_FILES["file"]["name"][0] . " already exists. ";
					  }
					  else
					  {
						echo "Stored in: " . "upload/files/" . $_FILES["file"]["name"][0];
					  }		
				}
				else
					echo "添加失败";
				
				
				 
			}
		  }
		else
		  {
		  echo "无效文件，请注意上传文件是否存在以下问题：<br>1.文件大小超过8M<br>2.所上传文件不是常见格式<br>若不存在以上问题，请及时与管理员取得联系";
		  		  
		  }
		  header("Refresh: 3;url=/admin/files");
//
		
		
		// 判断是否图片类型
		

	}
	
	public function edit()
	{
		parent::isLogin();
		$page_title = '重新上传文件';
		$id = $_GET['id'];
		$data = \EloquentModel\File::find($id);
		require_once dirname(__FILE__).'/../../views/admin/adminFiles/edit.php';
	}
	
	public function download($data)
	{	
		
		for($i=0; $i<count($data); $i++){
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=data[$i]['name']"); 
			echo readfile("data[$i]['content']");
		}
	}
	
	public function downloadFiles()
	{
		$id = $_GET['id'];
		$data = \EloquentModel\File::find($id);
		/*$data['down_count']=$data['down_count']+2;
		$data->down_count = $data['down_count'];
		$data->save();*/
		$data2['down_count']=$data['down_count']+1;
		
		$obj = new \EloquentModel\File;
		
		$rs = $obj->where('id','=',$_GET['id'])->increment('down_count',1);
		
		$ob = ob_get_clean();
		/*if ($data['type']=='all/txt')
		{
			
			$rs = $obj->where('id','=',$_GET['id'])->decrement('down_count',1);
		}*/
		//var_dump($ob);
		//die;
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=".$data['name']); 
		echo readfile($_SERVER['DOCUMENT_ROOT'].$data['content']);
		
		//$data = \EloquentModel\File::update(['down_count'=>])
		
	}
	
	public function delete()
	{	
		if ($_POST)
		{
			$ids=$_POST['ids'];
		}
		else
		{
			$ids=[$_GET['id']];
			
		}
		
		$rs=\EloquentModel\File::whereIn('id',$ids)->delete();
		
		if ($_POST)
		{
			if($rs){
			
			
				echo json_encode(['success'=>true,'message'=>'delete success']);
			}
			else{
				
				echo json_encode(['success'=>false,'message'=>'delete unsuccess']);
			}
			
		}
		else
		{
			if($rs){
			

			header('Refresh: 5;url=/admin/files');
			echo "删除成功";
			}
			else{
			header('Refresh: 5;url=/admin/files');
			echo "删除失败";
			}
			
		}
		
		
		
	}
	
	
	
	public function update()
	{	
		$functionsObj = new \VirgoUtil\Functions;
		$obj = new \EloquentModel\File;
		$data = $functionsObj->deleteNotNeedDataArray($_POST, array('id','file','pictures'));
		//判断文件大小是否超过5M
		// var_dump($_FILES["file"]["type"][0]]);
			//  die;
		if ((($_FILES["file"]["type"][0] == "image/gif")
		|| ($_FILES["file"]["type"][0] == "image/jpeg")
		|| ($_FILES["file"]["type"][0] == "text/xml")
		|| ($_FILES["file"]["type"][0] == "text/plain")
		|| ($_FILES["file"]["type"][0] == "audio/mpeg") //mp3
		|| ($_FILES["file"]["type"][0] == "application/pdf")
		|| ($_FILES["file"]["type"][0] == "application/msword")
		|| ($_FILES["file"]["type"][0] == "application/vnd.ms-excel")
		|| ($_FILES["file"]["type"][0] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")
		|| ($_FILES["file"]["type"][0] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
		|| ($_FILES["file"]["type"][0] == "application/octet-stream")
		|| ($_FILES["file"]["type"][0] == "application/zip")
		|| ($_FILES["file"]["type"][0] == "image/pjpeg"))
		&& ($_FILES["file"]["size"][0] < 5000000))
		  {
			 
		  if ($_FILES["file"]["error"][0] > 0)
			{
			echo "Error: " . $_FILES["file"]["error"][0] . "<br />";
			}
		  else
			{	
				//操作数据表
				$fileUploader = new \VirgoUtil\UploadEverything;
				$fileUploader->setSaveUrl('/upload/files');
				$fileUploader->doUpload();
				
				if(!empty($fileUploader->getErrorMessage())){
					header("Refresh: 5;url=/admin/file/edit?id=".$_POST['id']);
					echo "<script>alert('修改失败');</script>";
					return true;
				}
				$messages = $fileUploader->getSuccessMessage();
				$data['content'] = $messages[0]['url'];
				
				$data['create_time'] = time();
				
				$data['name']=$_FILES["file"]["name"][0];
				$data['type'] = $fileUploader->typeValueRewrite($_FILES["file"]["type"][0]);
				
				//$data['resource_name']=$_FILES["file"]["name"]
				$rs = $obj->where('id','=',$_POST['id'])->update($data);
				
				if($rs){
					echo "添加成功<br>";
					echo "上传文件成功！<br>";
					echo "Upload: " . $_FILES["file"]["name"][0] . "<br />";
					echo "Type: " . $_FILES["file"]["type"][0] . "<br />";
					if ($_FILES["file"]["size"][0]>1000000) 
					{
						echo "Size: " . ($_FILES["file"]["size"][0] / 1024/1024) ."Mb<br />";
					} 
					else 
					{
						echo "Size: " . ($_FILES["file"]["size"][0] / 1024) . " Kb<br />";
					}  
					echo "temp file: " . $_FILES["file"]["tmp_name"][0]."<br />";
					  if (file_exists("upload/files/" . $_FILES["file"]["name"][0]))
					  {
						echo $_FILES["file"]["name"][0] . " already exists. ";
					  }
					  else
					  {
						echo "Stored in: " . "upload/files/" . $_FILES["file"]["name"][0];
					  }		
				}
				else
					echo "添加失败";
				
				
				 
			}
		  }
		  else if(empty($_FILES["file"]["type"][0]))
		  {		
				$data['create_time'] = time();
				$rs = $obj->where('id','=',$_POST['id'])->update($data);	
				if($rs)
					echo "修改成功";
				else
					echo "修改失败";
			  
		  }
		  else
		  {
			echo "无效文件，请注意上传文件是否存在以下问题：<br>1.文件大小超过5M<br>2.所上传文件不是常见格式<br>3.首次添加必须上传文件<br/>若不存在以上问题，请及时与管理员取得联系";
		  		  
		  }
			
		header("Refresh: 3;url=/admin/files");
	
		
		
	}
	/*其他函数*/
	

	


}
