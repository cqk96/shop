<?php
namespace VirgoModel;
class ApiModel {
	protected $apiObj = '';

	public function __construct()
	{
		$this->apiObj = new \EloquentModel\Api;
	}

	public function lists($kv = false)
	{
		
		$data = $this->apiObj->where('status', '=', 0)->get();
		if($kv){
			foreach ($data as $key => $value) {
				$return[$value['id']] = $value;
			}

			unset($data);
			$data = $return;
		}

		return $data;

	}

	public function create()
	{
		
		//转化文本键值对
		$json = array();
		
		if(!empty($_POST['keys'])){
			foreach ($_POST['keys'] as $key => $value) {
				$temp['text'] = [$value => $_POST['values'][$key]];
				array_push($json, $temp);
			}
		}

		//文件进行上传
		$file_count = 1;
		foreach ($_FILES as $key => $value) {
			if(is_array($value['name'])){
				foreach ($value['name'] as $key_position => $value_name) {
				// 	$arrUploadedName = $this->doEachFilesUpload($key,$key_position,$file_count);
					$arrUploadedName = '\images\logo.jpg';
					if(!empty($arrUploadedName)){
						$temp_f['file'] = [($key.'[]')=>$arrUploadedName];
						array_push($json, $temp_f);
					}
					$file_count++;
				}

			} else {
				//$uploadedName = $this->doNormalUpload($key,$file_count);
				$uploadedName = '\images\logo.jpg';
				if(!empty($uploadedName)){
					$temp_f['file'] = [$key=>$uploadedName];
					array_push($json, $temp_f);
				}
				$file_count++;
			}

		}

		
		$config['project_id'] = $_POST['project_id'];
		$config['url'] = $_POST['url'];
		$config['description'] = $_POST['description'];
		$config['method'] = $_POST['method'];
		$config['params'] = json_encode($json,JSON_UNESCAPED_UNICODE);
		$config['http_protocal'] = $_POST['http_protocal'];
		return $this->apiObj->insert($config);

	}

	public function read()
	{
		if($_POST)
			$id = $_POST['id'];
		else
			$id = $_GET['id'];
		return $this->apiObj->find($id);
	}

	public function update()
	{
		$id = $_POST['id'];

		//转化文本键值对
		$json = array();
		//var_dump($_POST);
		//var_dump($_POST['values']);
		if(!empty($_POST['keys'])){
			foreach ($_POST['keys'] as $key => $value) {
				$temp['text'] = [$value => $_POST['values'][$key]];
				array_push($json, $temp);
			}
		}
		//文件进行上传
		$file_count = 1;
		// var_dump($_FILES);
		foreach ($_FILES as $key => $value) {
			if(is_array($value['name'])){
				foreach ($value['name'] as $key_position => $value_name) {
					// $arrUploadedName = $this->doEachFilesUpload($key,$key_position,$file_count);
					$arrUploadedName = '\images\logo.jpg';
					if(!empty($arrUploadedName)){
						$temp_f['file'] = [($key.'[]')=>$arrUploadedName];
						array_push($json, $temp_f);
					}
					$file_count++;
				}
			} else {
				// $uploadedName = $this->doNormalUpload($key,$file_count);
				$uploadedName = '\images\logo.jpg';
				if(!empty($uploadedName)){
					$temp_f['file'] = [$key=>$uploadedName];
					array_push($json, $temp_f);
				}
				$file_count++;
			}

		}

		
		$config['project_id'] = $_POST['project_id'];
		$config['url'] = $_POST['url'];
		$config['description'] = $_POST['description'];
		$config['method'] = $_POST['method'];
		$config['params'] = json_encode($json, JSON_UNESCAPED_UNICODE);
		$config['http_protocal'] = $_POST['http_protocal'];
		// var_dump($config);
		// die;
		return $this->apiObj->where('id',$id)->update($config);

	}

	public function delete()
	{
		
		$data['status'] = 1;
		if($_POST)
			$ids = $_POST['ids'];
		else
			$ids = [$_GET['id']];
		return $this->apiObj->whereIn('id',$ids)->update($data);

	}

	public function run()
	{
		
		$api = $this->read();

		$url = $_SERVER['HTTP_HOST'].$api['url'];
		if($api['method']=="GET"){
			$query = '';
			if ($api['params']!='')
				$query = $this->getGetSpecialQuery($api['params']);
			$rs = $this->doGet($url.$query);
		} else if($api['method']=="POST"){
			$data = $this->getPostSpecialQuery($api['params']);
			$rs = $this->doPost($url,$data);
		}
		//var_dump($rs);
		//die;
		return $rs;
	}

	//循环存储文件
	public function doEachFilesUpload($key,$position,$file_count)
	{
		
		if($_FILES[$key]['error'][$position]!=0)
			return '';
		else {
			$dirPath = $_SERVER['DOCUMENT_ROOT'].'/upload/api/';

			if(!is_dir($dirPath))
				mkdir($dirPath);
			$ext = $this->getFileExt($_FILES[$key]['name'][$position]);
			if (empty($ext))
				return '';
			$fname = '/upload/api/'.(time()+$file_count).".".$ext;
			$filePath = $_SERVER['DOCUMENT_ROOT'].$fname;
			$rs = move_uploaded_file($_FILES[$key]['tmp_name'][$position], $filePath);
			if($rs)
				return $fname;
			else
				return '';
		}

	}

	//存储单个文件
	public function doNormalUpload($key,$file_count)
	{

		if($_FILES[$key]['error']!=0)
			return '';
		else {
			$dirPath = $_SERVER['DOCUMENT_ROOT'].'/upload/api/';

			if(!is_dir($dirPath))
				mkdir($dirPath);
			$ext = $this->getFileExt($_FILES[$key]['name']);
			if (empty($ext))
				return '';
			$fname = '/upload/api/'.(time()+$file_count).".".$ext;
			$filePath = $_SERVER['DOCUMENT_ROOT'].$fname;
			$rs = move_uploaded_file($_FILES[$key]['tmp_name'], $filePath);
			if($rs)
				return $fname;
			else
				return '';
		}

	}

	public function getFileExt($name)
	{
		
		$name_arr = explode('.', $name);
		if(count($name_arr)==1)
			return '';
		return $name_arr[count($name_arr)-1];

	}

	//获取get解析后的查询参数
	public function getGetSpecialQuery($jsonParams)
	{
		
		$json_arr = json_decode($jsonParams, true);
		$params = array();
		foreach ($json_arr as $key => $value) {
			if($value=='text'){
				foreach ($value as $value_key => $value_value) {
					if(!empty($value_key)){
						array_push($params, $value_key."=".$value_value);
					}
				}
			}
		}

		if(!empty($params)){
			$query = implode('&', $params);
			return "?".$query;
		} else 
			return '';

	}

	//获取post解析后的查询参数
	public function getPostSpecialQuery($jsonParams)
	{
		
		$json_arr = json_decode($jsonParams, true);

		$params = array();
		foreach ($json_arr as $key => $value) {
			if(!empty($value['text'])){

				foreach ($value['text'] as $value_key => $value_value) {
					if(!empty($value_key)){
						$params[$value_key] = $value_value;
					}
				}

			} else if(!empty($value['file'])) {
				
				foreach ($value['file'] as $file_key => $file_value) {
					if(!empty($file_value)){
						$fDir = $_SERVER['DOCUMENT_ROOT'].$file_value;
						$contentType = mime_content_type($fDir);
						$curlClassObj = new \CURLFile($fDir, $contentType, $file_key);
						
						if(preg_match("/\[/", $file_key)){
							$search[0] = '/\[/';
							$search[1] = '/\]/';
							
							$keyName = preg_replace($search, '', $file_key);
							if(!isset($count) && !isset($params[$keyName.'0']))
								$count = 0;
							
							$params[$keyName.$count] = $curlClassObj;
							$count++;
						} else 
							$params[$file_key] = $curlClassObj;

					}

				}

			}

		}

		
		return $params;

	}

	//post 方式
	public function doPost($url, $data)
	{

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$out = curl_exec($ch);
		curl_close($ch);
		return $out;

	}

	//get 方式
	public function doGet($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$out = curl_exec($ch);
		curl_close($ch);
		return $out;
	}

}