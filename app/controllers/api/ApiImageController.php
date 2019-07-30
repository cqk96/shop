<?php
/**
* 获取缩略图
* 不生成webp文件以及对webp文件生成缩略图
* php version 5.5.12
* need gd,functions
* @author  xww <5648*****@qq.com>
* @version 1.0.1
* @since 1.0.0 基本功能
* @since 1.0.1 增加type表示选择缩略图方式,修复超出原图尺寸的缩略图生成方式
*/
namespace VirgoApi;
class ApiImageController extends ApiBaseController{

	/**
	* 自定义function 对象
	* @var Object
	*/
	private $_functionObj;

	/**
	* 自定义size 数组--图片尺寸为正方形
	* @var Array
	*/
	private $_size = [50, 100, 150];

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
		
		try{

			// 防止超时
			set_time_limit(0);

			// 生成thumb文件夹
			$this->_functionObj->mkDir('/thumb');

			// 检测必要字段
			$this->configValid('required',$this->_configs,['fpath']);

			// 强制不使用webp
			$this->_configs['strongExt'] = true;

			// 文件全路径
			$fpath = $_SERVER['DOCUMENT_ROOT'].$this->_configs['fpath'];

			// 上传的相对路径数组
			$totalNameArr = explode('/', $this->_configs['fpath']);

			// 文件后缀数组
			$fnameArr = explode(".", $totalNameArr[count($totalNameArr)-1]);

			//后缀
			$ext = array_pop($fnameArr);

			// 文件名
			$fname = implode(',' , $fnameArr);

			// 文件不存在 
			if(!file_exists($fpath)){
				$return = $this->functionObj->toAppJson(null, '038', '源文件不存在', false);
				throw new \Exception($return);
			}

			// 打开fileinfo资源
			$finfoObj = new \finfo(FILEINFO_MIME_TYPE);

			// 获取文件信息
			$fileInfo = $finfoObj->file($fpath);

			// 判断是否是图片
			if(stripos($fileInfo, 'image')===false){
				$return = $this->functionObj->toAppJson(null, '037', '文件类型错误', false);
				throw new \Exception($return);
			}
				
			/*
			* 判断是否为webp
			* 如果为webp 暂时返回原文件地址
			*/
			if($ext=='webp'){
				// 返回源文件
				$return = $this->functionObj->toAppJson($this->_configs['fpath'], '001', 'ok', true);
				throw new \Exception($return);
				// webp缩略图
				// $webpResource = imagecreatefromwebp($fpath);
				// $fileInfo[0] = imagesx($webpResource);
				// $fileInfo[1] = imagesy($webpResource);
			}

			// 此时区分缩略图生成方式
			if(empty($this->_configs['type'])){
				$this->_configs['type'] = 1;
			}

			// 选择缩略图方法
			switch ((int)$this->_configs['type']) {
				case 1:
					
					// 检测必要字段
					$this->configValid('required',$this->_configs,['size']);

					// 验证size
					$curSize = explode('_', $this->_configs['size']);

					// 原图宽高
					$fileInfo = getimagesize($fpath);
					
					// 原图宽高
					$width = $fileInfo[0];
					$height = $fileInfo[1];
					
					// 尺寸长度判断
					if(count($curSize)!=2){
						$return = $this->functionObj->toAppJson(null, '013', '参数不合法', false);
						throw new \Exception($return);
					}

					// 整型
					$curSize[0] = (int)$curSize[0];
					$curSize[1] = (int)$curSize[1];

					// 判断是否尺寸长度=0
					if($curSize[0]==0 || $curSize[1]==0){
						$return = $this->functionObj->toAppJson(null, '013', '参数不合法', false);
						throw new \Exception($return);
					}

					// 如果当请求的参数长宽都大于原图长宽时，返回原图
					if($curSize[0]>$width && $curSize[1]>$height){
						// 返回源文件
						$return = $this->functionObj->toAppJson($this->_configs['fpath'], '001', 'ok', true);
						throw new \Exception($return);	
					}

					// 如果当请求的参数长宽都大于预设宽高
					if($curSize[0]>150 && $curSize[1]>150){
						// 返回源文件
						$return = $this->functionObj->toAppJson($this->_configs['fpath'], '001', 'ok', true);
						throw new \Exception($return);
					}

					// 获取计算后的宽高
					$info = $this->getSize($width,$height, $curSize[0], $curSize[1]);

					// 尺寸文件夹
					$sizeStr = $info['size'].'_'.$info['size'];

					/*
					* 如果有strongExt 代表强制转化原图
					*/
					if(empty($this->_configs['strongExt'])){
						//缩略图名
						$fSubName = '/thumb/'.$sizeStr."/".$fname."_".$info['size']."_".$info['size'].'.webp';//.$ext;
					} else {
						//缩略图名
						$fSubName = '/thumb/'.$sizeStr."/".$fname."_".$info['size']."_".$info['size'].'.'.$ext;
					}

					$fSubPath = $_SERVER['DOCUMENT_ROOT'].$fSubName;

					//判断文件是否存在
					if(file_exists($fSubPath)){
						$return = $this->functionObj->toAppJson($fSubName, '001', '获取成功', true);
						throw new \Exception($return);
					}

					// 居中裁切
					$thumbName = $this->centerCut($fpath, $curSize, $width, $height, $fSubName, $sizeStr, $info, $ext);

					// 生成失败
					if(empty($thumbName)){
						$return = $this->functionObj->toAppJson(null, '050', 'fail', false);
						throw new \Exception($return);					
					}

					$return = $this->functionObj->toAppJson($thumbName, '001', 'ok', true);

					break;
				case 2:

					// 检测必要字段
					$this->configValid('required',$this->_configs,['scaleSize']);

					// 原图宽高
					$fileInfo = getimagesize($fpath);
					
					// 原图宽高
					$width = $fileInfo[0];
					$height = $fileInfo[1];

					// scaleSize
					$scaleSize = (int)$this->_configs['scaleSize'];

					// 尺寸不合要求
					if($scaleSize==0){
						$return = $this->functionObj->toAppJson(null, '013', '参数不合法', false);
						throw new \Exception($return);
					}

					// 如果当请求的参数长宽都大于原图长宽时，返回原图
					if($scaleSize>$width && $scaleSize>$height){
						$return = $this->functionObj->toAppJson($this->_configs['fpath'], '001', 'ok', true);
						throw new \Exception($return);
					}

					// 获取等比例的宽高
					$info = $this->getScaleSize($width, $height, $scaleSize);

					// 尺寸文件夹
					$sizeStr = $info['width'].'_'.$info['height'];

					/*
					* 如果有strongExt 代表强制转化原图
					*/
					if(empty($this->_configs['strongExt'])){
						//缩略图名
						$fSubName = '/thumb/'.$sizeStr."/".$fname."_".$sizeStr.'.webp';//.$ext;
					} else {
						//缩略图名
						$fSubName = '/thumb/'.$sizeStr."/".$fname."_".$sizeStr.'.'.$ext;
					}

					$fSubPath = $_SERVER['DOCUMENT_ROOT'].$fSubName;

					//判断文件是否存在
					if(file_exists($fSubPath)){
						$return = $this->functionObj->toAppJson($fSubName, '001', '获取成功', true);
						throw new \Exception($return);
					}

					// 等比例缩略
					$thumbName = $this->scaleZoom($width, $height, $info, $ext, $fpath, $fSubName, $sizeStr);

					if(empty($thumbName)){
						$return = $this->functionObj->toAppJson(null, '050', 'fail', false);
						throw new \Exception($return);
						
					}
					
					$return = $this->functionObj->toAppJson($thumbName, '001', 'ok', true);

					break;
				default:
					$return = $this->functionObj->toAppJson(null, '014', 'type参数传入错误', false);
					throw new \Exception($return);
					break;
			}

			//输出
			$this->responseResult($return);
		} catch(\Exception $e){
			$this->responseResult($e->getMessage());
		}

	}

	/**
	* 等比例缩放
	* @author 	xww
	* @param   	[$width]			int    	 	原图宽度
	* @param   	[$height]			int    		原图高度
	* @param   	[$info]				array    	等比的信息（包含宽度=》width,高度=》height）
	* @param   	[$ext]				string    	图片后缀
	* @param   	[$imgPath]			string    	原图全路径
	* @param   	[$relativePath]		string    	缩略图存储路径
	* @param   	[$sizeStr]			string    	尺寸文件夹
	* @return 	string
	*/ 
	public function scaleZoom($width, $height, $info, $ext, $imgPath, $relativePath, $sizeStr)
	{
		
		// 生成尺寸文件夹
		$this->_functionObj->mkDir('/thumb/'.$sizeStr);

		// 获取源资源
		$resource = $this->choosePicGd($ext, $imgPath);

		// 创建画布
		$img = imagecreatetruecolor($info['width'], $info['height']);
		
		// 整合图片资源
		imagecopyresampled($img, $resource, 0, 0, 0, 0, $info['width'], $info['height'], $width, $height);

		// 真实生成缩略图
		return $this->doCreate($img, $relativePath, $ext);

	}

	/**
	* 居中裁切
	* @author xww
	* @return string 空为失败,有则为成功
	*/ 
	public function centerCut($fpath, $curSize, $width, $height,  $fSubName, $sizeStr, $info, $ext)
	{

		// 生成尺寸文件夹
		$this->_functionObj->mkDir('/thumb/'.$sizeStr);

		//缩略图全路径
		$fSubPath = $_SERVER['DOCUMENT_ROOT'].$fSubName;

		// 计算后的图片大小
		$afterWidth = $info['size'];
		$afterHeight = $info['size'];
		$requestWidth = $info['size'];
		$requestHeight = $info['size'];

		// 偏移量
		$movex = $info['move'][0];
		$movey = $info['move'][1];

		// 创建缩略图
		return $this->createThumb($width, $height, $afterWidth, $afterHeight, $fpath, $ext, $movex, $movey, $fSubName);

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
	* @return string
	*/
	public function createThumb($width, $height, $afterWidth, $afterHeight, $imgPath, $ext, $movex, $movey, $relativePath)
	{
		
		// 获取源资源
		$resource = $this->choosePicGd($ext, $imgPath);

		// 创建画布
		$img = imagecreatetruecolor($afterWidth, $afterHeight);

		// 简单拷贝 进行居中裁切
		imagecopyresampled($img, $resource, 0, 0, $movex, $movey, $width, $height, $width, $height);
		
		// 真实生成缩略图
		return $this->doCreate($img, $relativePath, $ext);

	}

	/**
	* 获取文件详情
	* @author xww
	* @return string json
	*/ 
	public function read()
	{
		
		ob_clean();

		// 检测必要字段
		$this->configValid('required',$this->_configs,['fpath']);

		$fPath = $_SERVER['DOCUMENT_ROOT'].$this->_configs['fpath'];

		$fContent = '';
		if(file_exists($fPath)){
			$fContent = file_get_contents($fPath);
			$gzip = gzencode($fContent, 9);

			header("Send-Data-Type:1");
			header("Content-Type:gzip");
			echo $gzip;
		} else {
			echo $fContent;
		}

	}

	/**
	* 创建缩略图
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
					$rs = imagejpeg($resource, $_SERVER['DOCUMENT_ROOT'].$relativePath);
					break;
				case 'png':
					$rs = imagepng($resource, $_SERVER['DOCUMENT_ROOT'].$relativePath);
					break;
			}
		} else {

			//生成webp格式文件
			$rs = imagewebp($resource,$_SERVER['DOCUMENT_ROOT'].$relativePath);
		}

		imagedestroy($resource);
		unset($resource);
		
		if($rs){ return $relativePath; }
		else { return ''; }

	}

	/**
	* 获取计算后的宽高
	* 比例限制50*50, 100*100, 150*150
	* @author xww
	* @param  [$width]   string  原图的宽
	* @param  [$height]  string  原图的高
	* @param  [$requestWidth]   string  请求的宽
	* @param  [$requestHeight]  string  请求的高
	* @return array
	*/
	public function getSize($width, $height, $requestWidth, $requestHeight)
	{
		
		//依据最短边
		$requestShort = $requestWidth>$requestHeight? $requestHeight:$requestWidth;

		// 50*50 , 100*100 , 150*150
		if($requestShort<=50){
			$movex = ($width-50)/2;
			$movey = ($height-50)/2;
			$size = 50;
		} else if($requestShort<=100) {
			if((100-$requestShort)>($requestShort-50)){
				//贴近50
				$movex = ($width-50)/2;
				$movey = ($height-50)/2;
				$size = 50;
			} else {
				//贴近100
				$movex = ($width-100)/2;
				$movey = ($height-100)/2;
				$size = 100;
			}
		} else if($requestShort<=150) {
			if((150-$requestShort)>($requestShort-100)){
				//贴近100
				$movex = ($width-100)/2;
				$movey = ($height-100)/2;
				$size = 100;
			} else {
				//贴近150
				$movex = ($width-150)/2;
				$movey = ($height-150)/2;
				$size = 150;
			}
		} else {
			//自定义计算 超过150
			$multiple = (int)number_format($short/50, 2,'.','');
			$size = $multiple*50;
			$movex = ($width-$size)/2;
			$movey = ($height-$size)/2;
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

	/**
	* 获取等比例的宽高
	* @author xww
	* @param  [$width]     		int    原图宽
	* @param  [$height]    		int    原图高
	* @param  [$scaleSize]		int    请求的大小
	* @return array
	*/ 
	public function getScaleSize($width, $height, $scaleSize)
	{
		
		// 长边
		$long = $width>$height? $width:$height;

		// 计算适合比例
		$scale = number_format($scaleSize/$long, 2, '.', '');

		// 最后的结果不保留小数
		$newWidth = ceil($width*$scale);
		$newHeight = ceil($height*$scale);

		// 返回计算后的比例长宽
		return ['width'=>$newWidth, 'height'=>$newHeight];

	}

	/**
	* 上传图片--base64图片
	* @author xww
	* @return string json
	*/ 
	public function uploadBase64()
	{
		
		set_time_limit(0);
		//验证
		$this->configValid('required',$this->_configs,['pics']);

		// 相对
		$relative = [];

		// 遍历存入
		$pics = explode('-,-', $this->_configs['pics'] );
		foreach ($pics as $pic) {
			$head = substr($pic, 0, 20);
			$rs = preg_match('/data:image\/(.*)?;/i', $head, $matches);
			$ext = $matches[1];
			// error_log($pic, 3, $_SERVER['DOCUMENT_ROOT']."/".microtime(true).".txt");
			// for ($i=0; $i < count($matches); $i++) { 
			// 	error_log($matches[$i], 3, $_SERVER['DOCUMENT_ROOT']."/".microtime(true).".txt");
			// }

			// 取代为空format:data:image/png;base64,
			$pic = str_replace('data:image/'.$ext.';base64,', '', $pic);
			
			// 文件名
			$name = microtime(true).".".$ext;

			// 路径
			$path = $_SERVER['DOCUMENT_ROOT'].'/upload/images/'.$name;

			$fp = fopen($path, 'w');
			fwrite($fp, base64_decode($pic));
			array_push($relative, '/upload/images/'.$name);
			fclose($fp);
		}

		// 输出
		$return = $this->functionObj->toAppJson($relative, '001', 'ok', true);
		$this->responseResult($return);
		return false;

	}


}