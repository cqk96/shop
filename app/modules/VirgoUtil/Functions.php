<?php
/**
* 帮助开发工具类
* php version 5.5.12
* @author xww <5648*****@qq.com>
* @copyright xww 2016.12.15
* @version 1.0.0
*/

/*
* 创建文件夹
* mkDir(string $dir):void
*
*
* 获取随机字符串
* type: 1: num 2: english 3. Upper 4. 混合
* length: 返回字符串长度
* getRandStr($type=1,$length=6):string
*/
namespace VirgoUtil;

class Functions {

	public function __construct()
	{

	}

	/*
	*写入base64编码文件  只支持POST方式 暂时只支持jpeg
	*/
	public function writePic($fileDir='/')
	{
		
		if(!$this->fileExists($fileDir)) {
			$this->mkDir($fileDir);
		}

		$picStrBase64 = $_POST['imageStr'];
		$picStr =  str_replace('data:'.$_POST['type'].';base64,', '', $picStrBase64);
		$picStr = base64_decode($picStr);
		$fileName = time().".jpg";
		$fp = fopen($_SERVER["DOCUMENT_ROOT"].$fileDir.$fileName, 'w');
		fwrite($fp, $picStr);
		fclose($fp);
		
		return $fileName;

	}

	/*
	* curl 取得本站页面字符串内容
	*/
	public function getViewStr()
	{
		$url = $_POST['url'];
		ob_start();
		$ch = curl_init();
		// curl 走http协议
		curl_setopt($ch, CURLOPT_URL, $_SERVER['HTTP_HOST'].$url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) \r\n Accept: */*'));
		curl_setopt($ch, CURLOPT_NOBODY, 0);
		$chResult = curl_exec($ch);
		curl_close($ch);
		$htmlStr = ob_get_clean();
		echo $htmlStr;

	}

	/**
	* 获取随机字符串
	* @param   [$type]     string/int    验证码类型默认数字
	* @param   [$length]   string/int    验证码字符长度默认6
	* @return  string
	*/
	public function getRandStr($type=1,$length=6)
	{
		
		//根据模式选择生成
		$randStr = $this->switchStrModel($type);
		$returnStr = '';

		for($i=0; $i<$length; $i++){
			$index = rand(0,strlen($randStr)-1);
			$returnStr = $returnStr.$randStr[$index];
		}

		return $returnStr;

	}


	/*
	*获取字符串模式
	* param1 string/int type 1: num 2: english 3. Upper   else => default
	*/

	public function switchStrModel($type)
	{
		switch ((int)$type) {
			case 1:
				return '0123456789';
				break;
			case 2:
				return 'zxcvbnmasdfghjklqwertyuiop';
				break;
			case 3:
				return 'ZXCVBNMASDFGHJKLQWERTYUIOP';
				break;	
			case 4:
				return '0123456789zxcvbnmasdfghjklqwertyuiopZXCVBNMASDFGHJKLQWERTYUIOP';
				break;
			default:
				return '0123456789';
				break;
		}
	}

	//无限制修改数据库表 中字段值
	/*
	 * param1 string modelName
	 * param2 array key=>value key[column_name]
	 * param3 array id => value
	*/
	public  function editColumnsValueById($modelName, $config=[],$id=[])
	{

		if(is_array($config) && is_string($modelName) && is_array($id)){
			$obj = new $modelName;
			foreach($id as $k => $v){
				return $obj->where($k,'=',$v)->update($config);
			}
		} else {
			echo "函数参数错误";
		}

	}

	//数组中删除无用数据 只支持一维数组
	/*
	 * param1 array config
	 * param2 array notConfig  not need
	 * */
	public  function deleteNotNeedDataArray($configs=[], $not=[])
	{

		foreach($not as $k => $v){
			unset($configs[$v]);
		}

		return $configs;

	}

	//数组中获取有用数据
	/*
	 * param1 array config
	 * param2 array needConfig
	 * */
	public  function getNeedDataArray($configs=[], $need=[])
	{
		$return = array();

		foreach($configs as $k => $v){
			$rs = in_array($k, $need, true);
			if($rs)
				$return[$k] = $v;
		}

		return $return;

	}

	/*
	* 组装头像
	* param1 jsonString $jsonStr
	* return array/string picsurl/picurl
	*/
	public function getFullPicsUrlForJson($jsonStr)
	{
		$return = '';

		$json_array = json_decode($jsonStr);
		
		if(!$json_array) {

		} else {
			if(count($json_array)>1){
				$return = array();
				foreach ($json_array as $key => $value) {
					if($value!=""){
					    $return[$key] = $value;
					} else 
					   	$return[$key] = '';
				}
			} else {
				$return = $json_array[0];
			}
		}
		
		return $return;
		
	}

	/*
    * json 格数数据封装
    * param1 array $d data
    * param2 string $c code
    * param3 string $m message
    * param4 bool $s success
    * return json $return 
    */

    function turnToJson($d,$c,$m,$s)
    {
        
        $return = array(
            'data' => $d,
            'code' => $c,
            'message' => $m,
            'success' => $s
            );

        return json_encode($return);

    }

    /*
    * 性别获取数值
    * params string $genderText
    * return int gender;
    */
    function getGenderNum($genderText)
    {
        
        switch($genderText){
            case "男":
                return 1;
                break;
            case "女":
                return 2;
                break;
            default:
                return 3;
                break;
        }

    }

    /*
    * 性别获取文本
    * params string/int num $gender
    * return string gender text;
    */
    function getGenderText($gender)
    {
        
        switch((int)$gender){
            case 1:
                return "男";
                break;
            case 2:
                return "女";
                break;
            default:
                return "保密";
                break;
        }

    }

    /*
    * 转义与清空
    * 只支持一维数组
	* param array $configs
    */
    public function turnToNormalParams($configs)
    {
    	if(is_array($configs)) {
    		$return = array();
    		foreach ($configs as $key => $value) {
	    		$return[$key] = htmlentities(trim($value));
	    	}
    	} else {
    		$return = htmlentities(trim($configs));
    	}
    	
    	return $return;

    }

    /*
    * json型字段转义
	* param json_string jsonStr
    */
	public function jsonColumnToNormal($jsonStr)
	{
		
		$return = '';
		if($jsonStr!=''){
			$str_count = json_decode($jsonStr);
			$str = array();
			foreach ($str_count as $key => $value) {
				if(empty($value))
					$str[$key] = '';
				else
					$str[$key] = $value;
			}

			$return = $str;
		}

		return $return;

	}

	/*上传文件 不同文件不同规则*/
	public function specialUploadFile($uploadFileName,$saveUrl,$ext=[])
	{
		$errorLogs = array();
		$validate = true;
		$doNext = true;
		$rs = false;
		$picUrl = '';

		if(empty($saveUrl)){
			echo "函数uploadFile参数不为空";
			return false;
		}

		$this->mkDir($saveUrl);

		$doNext = $this->judgeExtWithRule($_FILES[$uploadFileName]['name'],$ext);

		if($doNext){
			$currentExtArray = explode('.', $_FILES[$uploadFileName]['name']);
			$currentExt = $currentExtArray[count($currentExtArray)-1];

			$picUrl = $saveUrl.time().'.'.$currentExt;
			$rs = move_uploaded_file($_FILES[$uploadFileName]['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$picUrl);
			
		}

		array_push($errorLogs, array('filename'=>$_FILES[$uploadFileName]['name'],'success'=>$rs,'validate'=>$doNext,'picurl'=>$picUrl));

		return $errorLogs;
		
	}


	/*上传文件*/
	public function uploadFile($saveUrl,$ext=[])
	{
		$errorLogs = array();
		$validate = false;
		$doNext = true;
		$rs = false;
		$picUrl = '';

		if(empty($saveUrl)){
			echo "函数uploadFile参数不为空";
			return false;
		}
		if(!empty($ext)){
			$validate = true;
		}
		$this->mkDir($saveUrl);

		foreach ($_FILES as $key => $value) {
			if($validate){
				$doNext = $this->judgeExtWithRule($value['name'],$ext);
			}

			if($doNext){
				$currentExtArray = explode('.', $value['name']);
				$currentExt = $currentExtArray[count($currentExtArray)-1];

				$picUrl = $saveUrl.time().'.'.$currentExt;
				$rs = move_uploaded_file($_FILES[$key]['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$picUrl);
				
			}

			array_push($errorLogs, array('filename'=>$value['name'],'success'=>$rs,'validate'=>$doNext,'picurl'=>$picUrl));

		}

		return $errorLogs;
	}

	/*获取上传文件后缀与规定是否相同*/
	public function judgeExtWithRule($name,$rules=[])
	{
		$result = false;

		if(!is_array($rules)){
			echo "传递规则不符,参数传递错误";
			return false;
		}

		$currentExtArray = explode('.', $name);
		$currentExt = $currentExtArray[count($currentExtArray)-1];

		foreach ($rules as $key => $value) {
			if($value==$currentExt)
				$result = true;
		}

		return $result;
	}

	/**
	* 文件夹创建
	* 只支持根目录建文件夹
	* @author    xww
	* @param     [$dir]    string    文本目录
	*/
	public function mkDir($dir)
	{
		
		if(empty($dir)){
			throw new \Exception("mkDir函数参数不为空");
		} else {
			$dir_array = explode('/', $dir);
			if($dir_array[0]=='') {
				array_shift($dir_array);
			}
			if($dir_array[count($dir_array)-1]==''){
				array_pop($dir_array);
			}

			$dirStr = '';

			foreach ($dir_array as $key => $value) {
				
				$dirStr = $dirStr.'/'.$value;
				if(!file_exists($_SERVER['DOCUMENT_ROOT'].$dirStr)) {
					$rs = mkdir($_SERVER['DOCUMENT_ROOT'].$dirStr);
					if(!$rs) {
						throw new \Exception("创建".$_SERVER['DOCUMENT_ROOT'].$dirStr."目录失败");
					}
				}

			}


		}

	}

	/*
	* 判断文件是否存在 以根目录为主
	* param string fileUrl
	*/

	public function fileExists($fileUrl)
	{
		
		$return = false;
		
		if(file_exists($_SERVER['DOCUMENT_ROOT'].$fileUrl)) {
			$return = true;
		}

		return $return;

	}

	/**
	* 与app交互json
	* @author xww
	* @param    [$d]    mix       数据
	* @param    [$c]    string    状态码
	* @param    [$m]    string    状态提示消息
	* @param    [$s]    boolean   是否成功
	*/
	public function toAppJson($d, $c, $m, $s)
	{
		$return = array(
            'data' => $d,
            'status' => array(
            	'code' => $c,
            	'message' => $m,
            	'success' => $s
            	)
            );

        return json_encode($return);
	}

	/**
	* 生成token 随机模式
	* @author xww
	* @return string
	*/
	public function tokenStr()
	{
		$randStr = $this->getRandStr($type=4,$length=6);
		return sha1($randStr);
	}

	/*生成随机用户名*/
	public function getNickName($base_name='一片空白_')
	{
		$remainStr = $this->getRandStr($type=4,$length=4);
		return $base_name.$remainStr;
	}

	//降维
	public function decrementDemension($arr=[])
	{
		if(empty($arr) || !is_array($arr)){
			echo "函数decrementDemension参数错误";
			exit();
		}

		$returnArr = array();

		foreach ($arr as $key => $value) {
			array_push($returnArr,$value);
		}

		return $returnArr;
	}

	/**
	*  验证api参数
	*  @author   xww
	*  @param    [$action]        string    指定验证行为
	*  @param    [$waitParams]    array   需要的参数
	*  @param    [$params]        array   等待验证的参数
	*  @return   void
	*/
	public function validateApiParams($action,$waitParams=[],$params=[])
	{
		if(empty($params) || !is_array($params)  || !is_array($waitParams)){//|| empty($waitParams)
			exit("Params/WaitParams must be array and not null");
		}

		$message = array();
		$returnArray = array();
		$result = true;

		switch ($action) {
			case 'required':
				$keys = array_keys($waitParams);
				foreach ($params as $key => $value) {
					if(!in_array($value, $keys) || empty($waitParams[$value])){
						$result = false;
						array_push($message, $value." can not be null");
						//break;
					}
				}
				$returnArray['success'] = $result;
				$returnArray['message'] = $message;
				return $returnArray;
				break;
			default:
				exit('非法验证规则');
				break;
		}

	}

	/**
	*  验证函数传递的参数
	*
	*  @param      [$action]  			验证的方式
	*  @param  	   [$waitPosition]		等待验证参数索引
	*  @param      [$params]			当前函数传递过来的所有参数
	*  @return     bool
	*/
	public function validateFunctionParams($action,$waitPosition=[], $params=[])
	{
		
		if(empty($action)){
			throw  new \Exception("action/waitPosition not null",1);
		}

		if(!is_array($waitPosition) || !is_array($params)){
			throw  new \Exception("waitPosition/params must be array and not null",1);
		}

		// $message = array();
		// $returnArray = array();
		$result = true;

		switch ($action) {
			case 'string':
				//$keys = array_keys($waitParams);
				foreach ($waitPosition as $key => $value) {
					
					if(!is_string($params[$value])){
						throw new \Exception("params-".($value+1)."must be string", 1);
						break;
					}

				}

				return $result;
				break;
			case 'array':
				//$keys = array_keys($waitParams);
				foreach ($waitPosition as $key => $value) {
					
					if(!is_array($params[$value])){
						throw new \Exception("params-".($value+1)."must be array", 1);
						break;
					}

				}
				
				return $result;
				break;
			case 'integer':
				//$keys = array_keys($waitParams);
				foreach ($waitPosition as $key => $value) {
					
					if(!is_integer($params[$value])){
						throw new \Exception("params-".($value+1)."must be integer", 1);
						break;
					}

				}
				
				return $result;
				break;
			default:
				throw  new \Exception("Illegal Validate Rule",1);
				break;
		}

	}

	/**
	*  验证函数传递的参数个数
	*
	*  @param  	   [$paramsCount]		传递的参数个数
	*  @param  	   [$curParamsCount]	当前参数个数
	*/
	public function validateFunctionParamsCount($paramsCount, $curParamsCount)
	{
		
		if(empty($paramsCount) || empty($curParamsCount)){
			exit("paramsCount/curParamsCount/curParamsCount not null");
		}

		$message = array();
		$returnArray = array();
		$result = true;
		if($paramsCount>$curParamsCount){
			throw new \Exception("Error Function Params Count", 1);
		}

	}

	/**
	* 获取间隔时长
	* @param    [$time]		int  (take from current time)
	*/
	public function timeDistance($time)
	{
		//一天以内
		if($time<86400){
			if($time<3600){
				if($time<60){
					return $time."秒以前";
				} else {
					return floor($time/60)."分钟以前";
				}
			} else
				return floor($time/3600)."小时以前";
		} else if($time<604800){
			//一周内
			return floor($time/86400)."天以前";
		} else {
			return floor($time/604800)."周以前";
		}

	}

	/**
	* 与layui交互json
	* @author xww
	* @param    [$d]    mix       数据
	* @param    [$c]    string    状态码
	* @param    [$m]    string    状态提示消息
	* @param    [$s]    boolean   是否成功
	*/
	public function toLayuiJson($d, $c, $m, $s)
	{
		$return = array(
            'data' => $d,
        	'code' => $c,
        	'message' => $m,
        	'totalCount' => $s
            );

        return json_encode($return);
	}


	/**
	* 保存完整base64图片数据
	* @author 	xww
	* @param 	string 		$base64Str
	* @return 	false or url
	*/
	public function saveBase64Img($base64Str, $saveUrl='/upload/images/')
	{

		$pic = $base64Str;
		$head = substr($pic, 0, 20);
		$rs = preg_match('/data:image\/(.*)?;/i', $head, $matches);

		if( !$rs ) {
			return false;
		}

		// 后缀
		$ext = $matches[1];

		// base64内容
		$pic = str_replace('data:image/' . $ext . ';base64,' , '', $pic);

		// 文件名
		$name = microtime(true) . "." . $ext;

		// 创建目录
		$this->mkDir( $saveUrl );

		// 路径

		$filePath = $saveUrl . $name;

		$path = $_SERVER['DOCUMENT_ROOT'] . $filePath;

		$fp = fopen($path, 'w');
		fwrite($fp, base64_decode($pic));
		fclose($fp);

		return $filePath;

	}

}