<?php
namespace VirgoApi\Image;
use Illuminate\Database\Capsule\Manager as DB;
class ApiImageController extends \VirgoApi\ApiBaseController
{

	/**
	* api参数数组
	* @var array
	*/
	private $_configs;

	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
		$this->_configs = parent::change();
	}

	/**
	* 图片裁切
	* @author 	xww
	* @return 	json
	*/ 
	public function cut()
	{
		
		try{

			// 必要验证--条款id
			$this->configValid('required',$this->_configs,['url', 'x', 'y', 'w', 'h']);

			// 判断图片文件是否存在
			if(!file_exists($_SERVER['DOCUMENT_ROOT'].$this->_configs['url'])) {
				throw new \Exception("文件不存在", "038");
			}

			$mine = mime_content_type($_SERVER['DOCUMENT_ROOT'].$this->_configs['url']);

			if(stripos($mine, "image")===false) { throw new \Exception("文件类型不存在", '037'); }

			$ext = str_replace("image/", "", $mine);

			if($ext=="gif"){ throw new \Exception("暂时无法处理", '081'); }

			if($ext=="jpeg" || $ext=="jpg" ) {
				$resource = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT'].$this->_configs['url']);
			} else if($ext=="png"){
				$resource = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'].$this->_configs['url']);
			}

			$img = imagecreatetruecolor($this->_configs['w'], $this->_configs['h']);
			imagecopyresampled($img, $resource, 0, 0, $this->_configs['x'], $this->_configs['y'], $this->_configs['w'], $this->_configs['h'], $this->_configs['w'], $this->_configs['h']);


			if($ext=="jpeg" || $ext=="jpg" ) {
				$filePath = "/upload/userPhoto/".microtime(true).'.jpeg';
				$rs = imagejpeg($img, $_SERVER['DOCUMENT_ROOT'].$filePath);
			} else if($ext=="png"){
				$filePath = "/upload/userPhoto/".microtime(true).'.png';
				$rs = imagepng($img, $_SERVER['DOCUMENT_ROOT'].$filePath);
			}

			imagedestroy($img);
			unset($img);

			if(!$rs) {throw new \Exception("文件生成失败", "082"); }

			$return = $this->functionObj->toAppJson($filePath, '001', '文件生成成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

}