<?php
/**
* 获取缩略图
* 此版本拒絕解析webp文件
* php version 5.5.12
* need gd,functions
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoApi;
class ApiImage2Controller extends ApiBaseController{

	/**
	* 自定义function 对象
	* @var Object
	*/
	private $_functionObj;

	/**
	* 初始化函数
	*/
	public function __construct()
	{
		
		//父类初始化
		parent::__construct();
		$this->_functionObj = new \VirgoUtil\Functions;
		$this->_configs = parent::change();

	}

	/**
	* 获取缩略图  先居中裁切  再缩放
	* 接收参数  relative  file  name
	* @author xww
	* @return string/object
	*/
	public function getThumb()
	{
		
		set_time_limit(0);

		//生成thumb文件夹
		$this->_functionObj->mkDir('/thumb');

		//检测必要字段
		$this->configValid('required',$this->_configs,['fpath', 'size']);

		//文件全路径
		$fpath = $_SERVER['DOCUMENT_ROOT'].$this->_configs['fpath'];

		//上传的相对路劲名
		$totalNameArr = explode('/', $this->_configs['fpath']);

		//文件后缀数组
		$fnameArr = explode(".", $totalNameArr[count($totalNameArr)-1]);

		//文件名
		$fname = $fnameArr[0];

		//后缀
		$ext = $fnameArr[count($fnameArr)-1];

		if(!file_exists($fpath)){
			//文件不存在
			$return = $this->functionObj->toAppJson(null, '038', '源文件不存在', false);
		} else {
			//尺寸之间以_隔开
			$curSize = explode('_', $this->_configs['size']);
			if(count($curSize)!=2){
				$return = $this->functionObj->toAppJson(null, '013', '参数不合法', false);
			} else {
				try {

					//请求尺寸 宽高
					$sizeStr = $curSize[0].'_'.$curSize[1];

					//缩略图名
					$fSubName = '/thumb/'.$sizeStr."/".$fname."_".$curSize[0]."_".$curSize[1].'.webp';

					//缩略图全路径
					$fSubPath = $_SERVER['DOCUMENT_ROOT'].$fSubName;

					//先压缩
					$imgPath = $fpath;
					$img = imagecreatefromjpeg($fpath);
					$relativePath = $fSubName;
					imagewebp($img,$_SERVER['DOCUMENT_ROOT'].$relativePath);
					die;
				} catch(\Exception $e) {
					//返回值
					$return = $this->functionObj->toAppJson(null, '043', '解析异常', false);
				}
			}

		}

		//输出
		$this->responseResult($return);

	}

	/**
	* 生成文件
	* @author xww
	* @param [$width]            string 原图宽
	* @param [$height]           string 原图高
	* @param [$afterWidth]       string 计算后宽
	* @param [$afterHeight]      string 计算后高
	* @param [$imgPath]          string 图片全路径
	* @param [$ext]              string 图片后缀
	* @param [$movex]            string 图片偏移量x
	* @param [$movey]            string 图片偏移量y
	* @param [$relativePath]     string 图片相对路径
	* @param [$requestWidth]     string 请求宽
	* @param [$requestHeight]    string 请求高
	* @return string
	*/
	public function createThumb($width, $height, $afterWidth, $afterHeight, $imgPath, $ext, $movex, $movey, $relativePath, $requestWidth, $requestHeight, $extraRelative)
	{
		
		//获取源资源
		$resource = $this->choosePicGd($ext, $imgPath);

		//创建画布
		$img = imagecreatetruecolor($afterWidth, $afterHeight);

		//简单拷贝--已经进行过居中裁切
		imagecopyresampled($img, $resource, 0, 0, $movex, $movey, $width, $height, $width, $height);

		if(empty($this->_configs['strongExt'])){
			switch ($ext) {
				case 'jpg':
				case 'jpeg':
					imagejpeg($img, $_SERVER['DOCUMENT_ROOT'].$extraRelative);
					break;
				case 'png':
					imagepng($img, $_SERVER['DOCUMENT_ROOT'].$extraRelative);
					break;
			}

			$img = $this->choosePicGd($ext, $_SERVER['DOCUMENT_ROOT'].'/'.$extraRelative);

			unlink($_SERVER['DOCUMENT_ROOT'].$extraRelative);

		}

		//创建缩略图--进行缩放
		//创建画布
		$img2 = imagecreatetruecolor($requestWidth, $requestHeight);

		//创建缩放图片
		$scaleWidth = imagesx($img);
		$scaleHeight = imagesy($img);
		// var_dump($requestWidth);
		// var_dump($requestHeight);
		// var_dump($img);
		// exit;
		imagecopyresampled($img2, $img, 0, 0, 0, 0, $requestWidth, $requestHeight, $scaleWidth, $scaleHeight);
		switch ($ext) {
			case 'jpg':
			case 'jpeg':
				imagejpeg($img2, $_SERVER['DOCUMENT_ROOT'].$extraRelative);
				break;
			case 'png':
				imagepng($img2, $_SERVER['DOCUMENT_ROOT'].$extraRelative);
				break;
		}

		ob_clean();
		$img3 = $this->choosePicGd($ext, $_SERVER['DOCUMENT_ROOT'].$extraRelative);

		imagewebp($img3,$_SERVER['DOCUMENT_ROOT'].$relativePath);
		die;
		$this->doCreate($img2, $relativePath, $ext);

	}

	/**
	* 创建缩略图--生成webp
	* @author xww
	* @param [$resource]      图片资源
	* @param [$relativePath]  图片相对路径
	* @param [$ext]           图片后缀
	*/
	public function doCreate($resource, $relativePath, $ext)
	{
		
		/*
		* 如果有strongExt 代表强制转化原图
		*/
		if(!empty($this->_configs['strongExt'])){
			switch ($ext) {
				case 'jpg':
				case 'jpeg':
					imagejpeg($resource, $_SERVER['DOCUMENT_ROOT'].$relativePath);
					break;
				case 'png':
					imagepng($resource, $_SERVER['DOCUMENT_ROOT'].$relativePath);
					break;
			}
		} else {

			//生成webp格式文件
			imagewebp($resource,$_SERVER['DOCUMENT_ROOT'].$relativePath);
		}

		imagedestroy($resource);
		unset($resource);

	}

	/**
	* 获取计算后的宽高
	* @author xww
	* @param  [$width]   string  请求的宽
	* @param  [$height]  string  请求的高
	* @return array
	*/
	public function getSize($width, $height)
	{
		
		//依据最短边
		$short = $width>$height? $height:$width;

		//长边
		$long = $width>$height? 'width':'height';

		// 50*50 , 100*100 , 150*150
		if($short<=50){
			if($long=='width'){
				$movex = (50-$short)/2;
				$movey = (50-$height)/2;
			} else {
				$movey = (50-$short)/2;
				$movex = (50-$width)/2;
			}
			$size = 50;
		} else if($short<=100) {
			if((100-$short)>($short-50)){
				//贴近50
				//$move = -($short-50)/2;
				if($long=='width'){
					$movex = -(50-$width)/2;
					$movey = -($short-50)/2;
				} else {
					$movey = -(50-$height)/2;
					$movex = -($short-50)/2;
				}
				$size = 50;
			} else {
				//贴近100
				//$move = (100-$short)/2;
				if($long=='width'){
					$movex = (100-$short)/2;
					$movey = (100-$short)/2;
				} else {
					$movey = (100-$short)/2;
					$movex = (100-$short)/2;
				}
				$size = 100;
			}
		} else if($short<=150) {
			if((150-$short)>($short-100)){
				//贴近100
				//$move = -($short-100)/2;
				if($long=='width'){
					$movex = ($short-100)/2;
					$movey = -($short-100)/2;
				} else {
					$movey = ($short-100)/2;
					$movex = -($short-100)/2;
				}
				$size = 100;
			} else {
				//贴近150
				//$move = (150-$short)/2;
				if($long=='width'){
					$movex = (150-$short)/2;
					$movey = (150-$short)/2;
				} else {
					$movey = (150-$short)/2;
					$movex = (150-$short)/2;
				}
				$size = 150;
			}
		} else {
			//自定义计算
			$multiple = (int)($short/50);
			$size = $multiple*50;
			//$move = ($short-$size)/2;
			if($long=='width'){
				$movex = ($width-$size)/2;
				$movey = ($short-$size)/2;
			} else {
				$movey = ($height-$size)/2;
				$movex = ($short-$size)/2;
			}

		}

		return ['size'=>$size, 'move'=>[$movex, $movey]];

	}

	/**
	* 通过后缀获取新图像资源
	* @author xww
	* @param  [$ext]  string  后缀
	* @param  [$fDir] string  文件全路径
	* @return resource
	*/

	public function choosePicGd($ext, $fDir)
	{
		
		switch ($ext) {
			case 'jpg':
			case 'jpeg':
				return imagecreatefromjpeg($fDir);
				break;
			case 'png':
				return imagecreatefrompng($fDir);
				break;
			case 'webp':
				return imagecreatefromwebp($fDir);
				break;
		}

	}

}