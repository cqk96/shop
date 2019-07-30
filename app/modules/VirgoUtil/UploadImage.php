<?php
/*
* need GD support
* need Functions.php
* filed named array
* but it not works on dynamic gif
*/
namespace VirgoUtil;
class UploadImage {

	//尺寸百分比
	protected $size = array('40', '60', '80');

	//尺寸路径
	protected $dir_size = array('/size40/', '/size60/', '/size80/');
	public    $save_url = '/upload/images';

	//文件名时间戳
	protected $ftime;

	//其他类文件
	protected $functionsObj;

	//错误信息
	protected $error_message = array();

	//成功地址
	protected $success_message = array();

	//验证图片格式
	protected $validate_ext = array();

	public function __construct()
	{
		$this->functionsObj = new \VirgoUtil\Functions;
		ini_set('memory_limit', '1G');
	}

	//上传文件
	public function doUpload()
	{
		//上传文件个数
		foreach ($_FILES as $key => $value) {
			$this->judgeError($key);
		}
	}

	//检测错误
	public function judgeError($key)
	{
		for($i=0; $i<count($_FILES[$key]['name']); $i++){

			//文件有错误
			if($_FILES[$key]['error'][$i]!=0){
				$text = $this->fileErrorText($_FILES[$key]['error'][$i]);
				array_push($this->error_message,['name'=>$_FILES[$key]['name'][$i],'response'=>$text]);
			} else {
				$this->uploadPic($key, $i);
			}

		}
	}

	//上传原始文件
	public function uploadPic($fname, $position)
	{
		
		//创建目录

		$this->functionsObj->mkDir($this->save_url);

		//非图片判断
		$is_pic = preg_match('/image/', $_FILES[$fname]['type'][$position]);
		if(!$is_pic){
			array_push($this->error_message,['name'=>$_FILES[$fname]['name'][$position],'response'=>'非图片文件']);
			return false;
		}

		//获取格式
		$ext = $this->getPicExt($_FILES[$fname]['type'][$position]);

		//如果设置了格式  进行格式判断
		if(!empty($this->validate_ext)){
			$is_pass = in_array($ext,$this->validate_ext);
			if(!$is_pass) {
				array_push($this->error_message,['name'=>$_FILES[$fname]['name'][$position],'response'=>'必须上传格式为'.implode(',', $this->validate_ext).'的文件']);
				return false;
			}
		}
		

		//上传原图
		$this->ftime = time();
		$fTotalDir = $_SERVER['DOCUMENT_ROOT'].$this->save_url.'/'.$this->ftime.'.'.$ext;
		$rs = move_uploaded_file($_FILES[$fname]['tmp_name'][$position], $fTotalDir);
		
		if($rs){
			//存储成功信息
			array_push($this->success_message, ['url'=>$this->save_url.'/'.$this->ftime.'.'.$ext]);

			//获取资源  由于被移动过所以文件不存在
			$imageInfo = getimagesize($fTotalDir);
			$ext = $this->getPicExt($imageInfo['mime']);

			$resource = $this->choosePicGd($ext, $fTotalDir);

			//创建小图片文件
			for($j=0; $j<count($this->dir_size); $j++)
				$this->functionsObj->mkDir($this->save_url.$this->dir_size[$j]);
			//获取尺寸
			$size = $this->getPicSize($fTotalDir);			

			//进行小尺寸图片绘制
			$this->doBulidSmallerPic($resource,$size,$ext);
		} else {
			array_push($this->error_message, ['name'=>$_FILES[$fname]['name'][$position],'response'=>'上传失败']);
		}

	}

	//返回错误信息
	public function getErrorMessage()
	{
		return $this->error_message;
	}

	//返回上传成功信息
	public function getSuccessMessage()
	{
		return $this->success_message;
	}

	//文件错误码
	public function fileErrorText($eno)
	{
		switch ((int)$eno) {
			case 1:
				return '文件大小超过服务器允许最大值'.ini_get('upload_max_filesize');
				break;
			case 2:
				return '文件大小超过表单项最大值'.ini_get('MAX_FILE_SIZE');
				break;
			case 3:
				return '只有部分文件被上传';
				break;
			case 4:
				return '没有选择任何文件';
				break;
			default:
				return '服务器上传临时文件夹出错';
				break;
		}
	}

	//获取图片后缀
	public function getPicExt($fExt)
	{
		$extArray  = explode('/', $fExt);
		return $extArray[count($extArray)-1];
	}

	//通过后缀获取新图像
	public function choosePicGd($ext, $fDir)
	{
		switch ($ext) {
			case 'gif':
				return imagecreatefromgif($fDir);
				break;
			case 'jpeg':
				return imagecreatefromjpeg($fDir);
				break;
			case 'png':
				return imagecreatefrompng($fDir);
				break;
			default:
				return imagecreatefromjpeg($fDir);
				break;
		}
	}

	//获取图片尺寸
	public function getPicSize($fDir)
	{
		$size = array();
		$rs = getimagesize($fDir, $imageinfo);
		if($rs){
			$size[0] = $rs[0];
			$size[1] = $rs[1];
		}

		return $size;

	}

	//创建小图像
	public function doBulidSmallerPic($resource, $size, $ext)
	{
		
		$width = empty($size[0])? 400:$size[0];
		$height = empty($size[0])? 400:$size[1];
		for($i=0; $i<count($this->size); $i++){
			$width_new = $width*($this->size[$i]/100);
			$height_new = $height*($this->size[$i]/100);
			$img = imagecreatetruecolor($width_new, $height_new);
			imagecopyresampled($img, $resource, 0, 0, 0, 0, $width_new, $height_new, $width, $height);
			switch ($ext) {
				case 'gif':
					imagegif($img, $_SERVER['DOCUMENT_ROOT'].$this->save_url.$this->dir_size[$i].$this->ftime.'.gif');
					break;
				case 'jpeg':
					imagejpeg($img, $_SERVER['DOCUMENT_ROOT'].$this->save_url.$this->dir_size[$i].$this->ftime.'.jpeg');
					break;
				case 'png':
					imagepng($img, $_SERVER['DOCUMENT_ROOT'].$this->save_url.$this->dir_size[$i].$this->ftime.'.png');
					break;
				default:
					imagejpeg($img, $_SERVER['DOCUMENT_ROOT'].$this->save_url.$this->dir_size[$i].$this->ftime.'.jpeg');
					break;
			}
			imagedestroy($img);
			unset($img);
		}
		unset($resource);
	}

	//设置百分比尺寸
	public function setSize($newSize)
	{
		$tempSizeDir = array();
		if(!is_array($newSize)) {
			echo "百分比必须为数组";
			exit();
		}
		foreach ($newSize as $key => $value) {
			if($value<10) {
				echo "元素必须为整数";
				exit();
			}
			array_push($tempSizeDir, '/size'.(int)$value.'/');
		}

		$this->size = $newSize;
		$this->dir_size = $tempSizeDir;

	}

	//设置存储路径
	public function setSaveUrl($newSaveUrl)
	{
		$this->save_url = $newSaveUrl;
	}

	//设置图片验证格式
	public function setValidateExts($exts)
	{
		if(!is_array($exts)) {
			echo "验证格式必须为数组";
			exit();
		}
		$this->validate_ext = $exts;
	}

}