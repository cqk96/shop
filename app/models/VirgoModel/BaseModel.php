<?php
namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class BaseModel {

	public function __construct()
	{
		$this->functionsObj = new \VirgoUtil\Functions;
		//$this->linkMemcache();
	}

	//展示结果的函数
	/*
	* param1 array values
	* param2 string url
	* param3 string template file
	*/
	public function showPage($variable=[], $url='', $time='', $tempDir='/../../templates/show.php')
	{
		
		$time = empty($time)? 5:$time; 
		if(!is_array($variable)){
			echo "函数参数必须为数组";
			return false;
		}
		ob_clean();
		ob_start();

		header('Refresh: '.$time.'; url='.$url);
		header('Content-type:text/html;charset=utf8');
		require dirname(__FILE__).$tempDir;
		$str = ob_get_clean();
		print_r($str);
	}

	//展示调试结果的函数
	/*
	* param1 array values
	* param2 array header   [do something]
	* param3 string template file
	*/
	public function showDebugPage($variable=[], $tempDir='/../../templates/debug.php')
	{
		if(!is_array($variable)){
			echo "函数参数必须为数组";
			return false;
		}
		ob_clean();
		ob_start();

		require dirname(__FILE__).$tempDir;
		$str = ob_get_clean();
		print_r($str);
	}

	//获取接下来的自增值域
    public function getNextIncrement($table)
    {
        
        if(empty($table)){
            throw new \Exception("table is not null", 1);
            exit();
        }

        $nextIncrement = DB::select("SELECT auto_increment FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='voyage' AND TABLE_NAME='".$table."'");
        return $nextIncrement[0]->auto_increment;
        
    }

    //图片信息
    /*
	* return array
    */
    public function getFormDataFileInfo($relativePath)
	{
		$absolutePath = $_SERVER['DOCUMENT_ROOT'].$relativePath;
		$fSize = filesize($absolutePath);
		$fType = mime_content_type($absolutePath);
		$file['filename'] = $relativePath;
		$file['content-type'] = $fType;
		$file['filelength'] = $fSize;
		clearstatcache();
		return $file;
	}

	/**
	* 列表型数据  存储与缓存
	* @todo   	  				存储逻辑
	* @param      [$key]		键名
	* @param      [$class]		类名
	* @param      [$method]		方法名
	* @param      [$params]		方法的参数
	* @param      [$timeout]	自定义超时时间 默认0  无限制
	* @return     string        
	*/
	public function doMemcache($key, $class='', $method='', $params=[] ,$timeout=0)
	{
		
		try{
			
			//参数个数验证
			$this->functionsObj->validateFunctionParamsCount(4,func_num_args());

			$args = func_get_args();

			//参数规则验证
			$this->functionsObj->validateFunctionParams('string',['0','1','2'],$args);

			$this->functionsObj->validateFunctionParams('array',['3'],$args);

			$this->functionsObj->validateFunctionParams('integer',['4'],$args);

			$value = $this->memcache->get($key);   //从内存中取出key的值
			if(empty($value)){
				$classObj = new $class;
				$data = call_user_func_array(array($classObj,$method),$params);

				// if(is_array($data) || is_object($data)){
				// 	$data = serialize($data);
				// }

				$this->memcache->set($key, $data, MEMCACHE_COMPRESSED, $timeout);        //设置一个变量到内存中，名称是key 值是test
				$value = $this->memcache->get($key);

			}
			
			return $value;

		} catch(\Exception $e) {

			echo $e->getMessage();

		}

	}


	/**
	*	关闭链接
	* @todo 		关闭memcache链接
	* @return       bool
	*/
	public function closeMemcache()
	{
		return $this->memcache->close();
	}


	/**
	* 关闭链接
	* @todo 				删除memcache某个key
	* @param    [$key] 		存在memcache中的键名
	* @return       		bool
	*/
	public function deleteMemcacheKey($key)
	{
		
		//global $memcache;
		return $this->memcache->delete($key);
	}

	/**
	* 获取某个key
	* @todo 				获取memcache某个key
	* @param    [$key] 		存在memcache中的键名
	* @return   
	*/
	public function getMemcacheKey($key)
	{
		
		//global $memcache;
		return $this->memcache->get($key);
	}

	/**
	* 链接memcache
	* @todo 				获取memcache某个key
	* @param    [$key] 		存在memcache中的键名
	* @return   
	*/
	public function linkMemcache($host='localhost',$port='11211')
	{
		$this->memcache = new \Memcache;             //创建一个memcache对象
		$this->memcache->connect($host, $port) or exit("Could not connect memcache"); //连接Memcached服务器
	}

	/**
	* @todo 	获取下一个自增id
	*/
	public function getNextIncrement_ver_2($db,$table)
    {
        
        if(empty($table)){
            throw new \Exception("table is not null", 1);
            exit();
        }

        $nextIncrement = DB::select("SELECT auto_increment FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='".$db."' AND TABLE_NAME='".$table."'");
        return $nextIncrement[0]->auto_increment;
        
    }

}