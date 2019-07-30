<?php
namespace VirgoApi;

use VirgoUtil\OssInUe as OssInUe;
/*
* 图片上传Api
* @author		wkl
* @version		0.1.0
*/
class ApiUploadImageController extends ApiBaseController
{
	private $fileField; //文件域名
    private $file; //文件上传对象
    private $base64; //文件上传对象
    private $config; //配置信息
    private $oriName; //原始文件名
    private $fileName; //新文件名
    private $fullName; //完整文件名,即从当前配置目录开始的URL
    private $filePath; //完整文件名,即从当前配置目录开始的URL
    private $fileSize; //文件大小
    private $fileType; //文件类型
    private $stateInfo; //上传状态信息,
    private $stateMap = array( //上传状态映射表，国际化用户需考虑此处数据的国际化
        "SUCCESS", //上传成功标记，在UEditor中内不可改变，否则flash判断会出错
        "文件大小超出 upload_max_filesize 限制",
        "文件大小超出 MAX_FILE_SIZE 限制",
        "文件未被完整上传",
        "没有文件被上传",
        "上传文件为空",
        "ERROR_TMP_FILE" => "临时文件错误",
        "ERROR_TMP_FILE_NOT_FOUND" => "找不到临时文件",
        "ERROR_SIZE_EXCEED" => "文件大小超出网站限制",
        "ERROR_TYPE_NOT_ALLOWED" => "文件类型不允许",
        "ERROR_CREATE_DIR" => "目录创建失败",
        "ERROR_DIR_NOT_WRITEABLE" => "目录没有写权限",
        "ERROR_FILE_MOVE" => "文件保存时出错",
        "ERROR_FILE_NOT_FOUND" => "找不到上传文件",
        "ERROR_WRITE_CONTENT" => "写入文件内容错误",
        "ERROR_UNKNOWN" => "未知错误",
        "ERROR_DEAD_LINK" => "链接不可用",
        "ERROR_HTTP_LINK" => "链接不是http链接",
        "ERROR_HTTP_CONTENTTYPE" => "链接contentType不正确",
        "INVALID_URL" => "非法 URL",
        "INVALID_IP" => "非法 IP"
    );
	  /**
     * 构造函数
     * @param string $fileField 表单名称
     * @param array $config 配置项
     * @param bool $base64 是否解析base64编码，可省略。若开启，则$fileField代表的是base64编码的字符串表单名
     */
	public function __construct()
	{

		$this->functionObj = new \VirgoUtil\Functions;
		
        
	}
	 

  
    

	

	public function uploadimage()
	{
		// var_dump($_GET);
		// die;
		if(empty($_FILES)){
			$return = $this->functionObj->toAppJson(null,'018', '文件不为空',false);
			//返回
			$this->responseResult($return);
			return false;
		}

		$result = [];
		// 上传
		foreach ($_FILES as $value) {
			
			// 判断是否为图片
			$is_image = stripos($value["type"], 'image');

			// 非图片
			if($is_image===false){
				//失败
				$arr['message'] = $this->getErrorMessage(100);
				$arr['type'] = 100;
				$arr['url'] = '';
				
				// 判断是否有额外参数通过get方式传递 有的话则将此返回
				$arr = $this->getOtherParam($arr, 'uploadImage');
				array_push($result, $arr);
			} else {
				// 处理图片
				$name = microtime(true);

				// 图片资源有问题
				if($value['error']!=0){
					$arr['message'] = $this->getErrorMessage($value['error']);
					$arr['type'] = $value['error'];
					$arr['url'] = '';
					// 判断是否有额外参数通过get方式传递 有的话则将此返回
					$arr = $this->getOtherParam($arr, 'uploadImage');
					array_push($result,$arr);
				} else {
					// 资源上传成功

					// 获取后缀
					$ext = $this->getPicExt($value["type"]);

					// 相对路劲
					// $path = "/upload/images/".$name.'.'.$ext;

					// 目标
					// $destination = $_SERVER['DOCUMENT_ROOT'].$path;
					// OSS
					$ossInUe = new OssInUe();
					// var_dump($value["tmp_name"]);die();
					$ext = $this->getPicExt($value["type"]);
	                $obj = $ossInUe->uploadToAliOSS($value["tmp_name"],".".$ext);
	                 // var_dump($obj);die();
	                if($obj['status'] == true){
						// 成功写入
						$arr['message'] = 'upload successful';
						$arr['type'] = 0;
						$arr['url'] = $obj['path'];
						// 判断是否有额外参数通过get方式传递 有的话则将此返回
						$arr = $this->getOtherParam($arr, 'uploadImage');
						array_push($result, $arr);
					} else {
						// 写入失败
						$arr['message'] = '服务器图片写入失败';
						$arr['type'] = 101;
						$arr['url'] = '';
						// 判断是否有额外参数通过get方式传递 有的话则将此返回
						$arr = $this->getOtherParam($arr, 'uploadImage');
						array_push($result, $arr);
					}
	                
					// 文件写入
					// $rs = move_uploaded_file($value["tmp_name"],$destination);

					// if($rs){
					// 	// 成功写入
					// 	$arr['message'] = 'upload successful';
					// 	$arr['type'] = 0;
					// 	$arr['url'] = $path;
					// 	// 判断是否有额外参数通过get方式传递 有的话则将此返回
					// 	$arr = $this->getOtherParam($arr, 'uploadImage');
					// 	array_push($result, $arr);
					// } else {
					// 	// 写入失败
					// 	$arr['message'] = '服务器图片写入失败';
					// 	$arr['type'] = 101;
					// 	$arr['url'] = '';
					// 	// 判断是否有额外参数通过get方式传递 有的话则将此返回
					// 	$arr = $this->getOtherParam($arr, 'uploadImage');
					// 	array_push($result, $arr);
					// }

				}
			}

		}

		// 结果输出
		$return = $this->functionObj->toAppJson($result,'001', '上传结束',true);
		//返回
		$this->responseResult($return);
		return false;
	}

	/**
	* 获取其他get方式传递的参数
	* @param  [$inArr]      array 赋值于此数组上
	* @param  [$exceptStr]  哪个字符串上的是不要的 通常过滤url地址
	* @author xww
	* @return inArr array
 	*/ 
 	public function getOtherParam($inArr, $exceptStr)
 	{
 		foreach ($_GET as $url_key => $url_value) {
			if(!strripos($url_key, $exceptStr)){
				//去除地址的get  将其他传递
				$inArr[$url_key] = $url_value;
			}
		}
		return $inArr;
 	}

	/**
	* 返回图片上传失败原因
	* @author xww
	* @param  type  int/string   file  type  增加一个type表示类型错误  防止冲突  用100号
	* @return string
	*/
	public function getErrorMessage($type)
	{
		
		switch ((int)$type) {
			case 1:
				return '上传文件大小超过服务器允许上传的最大值';
				break;
			case 2:
				return '上传文件大小超过HTML表单中隐藏域MAX_FILE_SIZE选项指定的值';
				break;
			case 3:
				return '文件只有部分被上传';
				break;
			case 4:
				return '没有找到要上传的文件';
				break;
			case 5:
				return '服务器临时文件夹丢失';
				break;
			case 6:
				return '文件写入到临时文件夹出错';
				break;
			case 100:
				return '类型错误';
				break;
			default:
				return '未定义错误类型';
				break;
		}

	}
	
	//数组图片上传,[0]
	/**
	* @param  [$urltype]  选择返回地址类型默认为绝对地址  0：相对地址 1：绝对地址   default=>1
	* @param  [$return_method] 是否输出json结果默认不echo  0：仅以返回结果	1：echo json	default=>0
	*
	*/
	public function uploadimage0($urltype=1,$return_method=0)
	{
		//$url=$_POST['url'];
		//var_dump($_FILES);
		
		if ((($_FILES["file"]["type"][0] == "image/gif")
		|| ($_FILES["file"]["type"][0] == "image/jpeg")
		|| ($_FILES["file"]["type"][0] == "image/png")
		|| ($_FILES["file"]["type"][0] == "image/bmp")
		|| ($_FILES["file"]["type"][0] == "image/pjpeg"))
		&& ($_FILES["file"]["size"][0] >0))
		  {
			  $ext=$this->getPicExt($_FILES["file"]["type"][0]);
			if ($_FILES["file"]["error"][0] > 0)
			{
				if($return_method==1){
					echo $this->functionObj->toAppJson(null,'101', $_FILES["file"]["error"][0],'false');
				}	
				return $this->functionObj->toAppJson(null,'101', $_FILES["file"]["error"][0],'false');
			}
		  else
			{
			$lastname=time();
			 move_uploaded_file($_FILES["file"]["tmp_name"][0],
			 "upload/images/" .$lastname.'.'.$ext);
			 $store='/upload/images/'.$lastname.'.'.$ext;
			 $u='http://'.$_SERVER['SERVER_NAME'].$store;
			 if($urltype){
				 if($return_method==1){
					 echo $this->toEchoJson(null,'100','上传成功',$u,'true');
				 }
				 return $this->toEchoJson(null,'100','上传成功',$u,'true'); 
			 }
			  else{
				  if($return_method==1){
					 echo $this->toEchoJson(null,'100','上传成功',$store,'true');
				 }
				  return $this->toEchoJson(null,'100','上传成功',$store,'true');
			  }
			}
		  }
		else
		  {		
				if($return_method==1){
					echo $this->functionObj->toAppJson(null,'102', 'Invalid file','false');
				}	
				return $this->functionObj->toAppJson(null,'102', 'Invalid file','false');
		  }
	}
	
	//输出json，$d:data,$c:code,$m:message,$u:saveurl,$s:success
	public function toEchoJson($d, $c, $m, $u ,$s)
	{
		$return = array(
            'data' => $d,
            'status' => array(
            	'code' => $c,
            	'message' => $m,
				'saveurl' => $u,
            	'success' => $s
            	)
            );

        return json_encode($return);
	}

	//获取图片后缀
	public function getPicExt($fExt)
	{
		$extArray  = explode('/', $fExt);
		return $extArray[count($extArray)-1];
	}

	
}
?>