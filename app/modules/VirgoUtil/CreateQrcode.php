<?php
/**
* need QRcode Module
*/
namespace VirgoUtil;
class CreateQrcode
{
	//纠错级别：L、M、Q、H	
	public $errorCorrectionLevel='M';
	// 点的大小：1~10	
	public $matrixPointSize=4;
	public $code_url='';

	function __construct()
	{
		$this->QRcodeobj = new \VirgoUtil\QRcode;
	}
	/**
	* 参数配置(set param)
	*/
	public function config($errorCorrectionLevel='M',$matrixPointSize=4)
	{
		$this->errorCorrectionLevel=$errorCorrectionLevel;
		$this->matrixPointSize=$matrixPointSize;
	}
	/**
	* 生成二维码(生成的二维不带logo)
	* author wkl
	* @param string $code_url
	* @return string
	*/	
	public function createQrcode($code_url, $name)
	{	
		//创建目录	
		// $this->tomkdir();
		//二维码数据
		$value=$code_url;
		//生成文件名
		// $autoname=time();
		//组装文件名与路径
		$filename =  "/wxQrcode/".$name.".png";
		//返回路径
		// $return="/upload/qrcode/qr".$autoname.".png";
		/* 获取预设值start*/
		//纠错级别：L、M、Q、H	
		$errorCorrectionLevel = $this->errorCorrectionLevel;  
		// 点的大小：1~10
		$matrixPointSize = $this->matrixPointSize;	
		/* 获取预设值end*/

		$rs = $this->QRcodeobj->png($value, $_SERVER['DOCUMENT_ROOT'].$filename, $errorCorrectionLevel, $matrixPointSize, 2);
		
		return $filename;
	}

	public function tomkdir()
	{
		$tobj=new \VirgoUtil\Functions;
		$tobj->mkdir('/upload/qrcode');
	}		

	/**
	* 普通生成二维码
	* @author 	xww
	* @return 	void
	*/ 
	public function createStaffCardQrcode($name, $path, $str, $rgb = [])
	{
		
		/* 获取预设值start*/
		//纠错级别：L、M、Q、H	
		$errorCorrectionLevel = $this->errorCorrectionLevel;  
		// 点的大小：1~10
		$matrixPointSize = $this->matrixPointSize;	
		/* 获取预设值end*/

		if(!empty($rgb)){
			$this->QRcodeobj->setQrcodeImgRGB($rgb);
		}

		$this->QRcodeobj->png($str, $path, $errorCorrectionLevel, $matrixPointSize, false);

	}

}