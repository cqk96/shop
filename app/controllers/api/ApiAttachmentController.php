<?php
namespace VirgoApi;
/**
* 图片上传Api
* @author		
* @version		0.1.0
*/
class ApiAttachmentController extends ApiBaseController
{
	/**
	* api参数数组
	* @var array
	*/
	private $_configs;

	public function __construct()
	{
		$this->_configs = parent::change();
		$this->functionObj = new \VirgoUtil\Functions;
	}

	/**
	* @SWG\Post(path="/api/v1/tools/attachment/upload", tags={"Attachment"}, 
	*  summary="上传附件",
	*  description="这里只能示例了一个附件的上传方法 多附件就是多参数 返回是多数组 命名规则 file,file1 以此类推",
	*  consumes={"multipart/form-data"},
	*  produces={"application/json"},
	*  @SWG\Parameter(name="file", type="file", required=true, in="formData", description="附件文件"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "UploadAttachmentObject", "status": { "code": "001", "message": "上传结束", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/UploadAttachmentObject")
	*   )
	*  )
	* )
	* 附件上传
	* @author 	xww
	* @return 	json
	*/
	public function upload()
	{

		try{

			if( empty($_FILES) ) {
				throw new \Exception("附件不为空", '018');
			}

			// 判断是否已经生成了附件文件夹地址
			$rootDir = $_SERVER['DOCUMENT_ROOT'];
			$uploadDir = "/uploadAttachment";
			$dateStr = date("Ymd");

			$saveDir = $uploadDir . "/" . $dateStr;
			if( !is_dir($rootDir.$saveDir) ) {
				$this->functionObj->mkDir( $saveDir );
			}

			// 包含原名，成功或失败，上传后代码（服务器代码），结果地址
			$result = [];

			// 上传
			foreach ($_FILES as $file) {

				// 原名
				$name = $file['name'];

				// 临时地址
				$tempServerDir = $file['tmp_name'];

				// 错误
				$error = $file['error'];

				// 上传结果
				$temp['name'] = $name;
				$temp['uploadResult'] = false;
				$temp['serverMsg'] = "上传服务器成功";
				$temp['uploadPath'] = "";

				// 文件成功上传至服务器
				if( $error==0 ) {

					$nameArr = explode(".", $file['name']);

					// 后缀
					$ext = array_pop($nameArr);

					// 文件名 
					$fileName = $saveDir . "/" . microtime(true) . "." . $ext;

					// 进行上传

					$rs = move_uploaded_file($tempServerDir, $rootDir . $fileName );

					// 失败
					if( !$rs ) {
						$temp['serverMsg']="上传文件失败";
					} else {
						$temp['uploadResult'] = true;
						$temp['uploadPath'] = $fileName;
					}

				} else {
					$temp['serverMsg'] = $this->getErrorMessage($error);
				}


				$result[] = $temp;

			}
			unset( $file );

			$return = $this->functionObj->toAppJson($result, '001', '上传附件成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

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

}