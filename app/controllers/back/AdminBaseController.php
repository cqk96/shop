<?php
/**
* AdminBaseController   后台控制器基础父类
*/
namespace VirgoBack;
use Illuminate\Database\Capsule\Manager as DB;

class AdminBaseController
{
    //定义全局变量
    public $assetUrl = '';
    public $is_mobile = false;
    public function __construct()
    {

        $this->assetUrl = $this->getDocumentRoot();

        //判断是否为手机浏览器 仅适用apache服务器
        $requestHeadersArray = apache_request_headers();
        $pattern = "/Mobile/";
        $is_mobile = preg_match($pattern, $requestHeadersArray['User-Agent']);
        if($is_mobile)
            $this->is_mobile = true;

    }

    //获取文档根目录
    /*
    *@param url
    *return String of complete url;
    */
    public function getDocumentRoot($opration="")
    {
        $baseUrl_array = explode("/", $_SERVER['SCRIPT_NAME']);
        array_pop($baseUrl_array);
        $baseUrl = implode("/", $baseUrl_array);
        $fullUrl = $baseUrl.$opration;
        return $fullUrl;
    }

    //是否有登陆
    public static function isLogin()
    {
        
        //判断是否有登陆 无则强制登陆
        if(empty($_COOKIE['user_login'])){
            setcookie("user_login", "", time()-1, "/");
            setcookie("user_id", "", time()-1, "/");
            header("Refresh: 5;url=/admin");
            echo "请重新登录";
            exit();
        }

    }

    //是否有登陆_ver2
    public static function isLogin_ver2()
    {
        
        //判断是否有登陆 无则强制登陆
        if(empty($_COOKIE['user_login'])){
            echo "<script>parent.location.reload();</script>";
            exit();
        }

    }

    //获取接下来的自增值域
    public function getNextIncrement($table)
    {
        
        if(empty($table)){
            throw new \Exception("table is not null", 1);
            exit();
        }

        $nextIncrement = DB::select("SELECT auto_increment FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='shop' AND TABLE_NAME='".$table."'");
        return $nextIncrement[0]->auto_increment;
        
    }

    public function showPage($variable=[], $url, $time='', $tempDir='/../../templates/show.php')
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
        
    /**
    * 根据阶段id返回阶段文本
    * @author   xww
    * @param    int/string      $processId
    * @return   string
    */
    public function getPrecessText($processId)
    {
        switch (intval($processId)) {
            case 1:
                return '启航';
                break;
            case 2:
                return '领航';
                break;
            case 3:
                return '远航';
                break;
            default:
                return '未知';
                break;
        }
    }

    /**
    * 返回前端使用字符串
    * @author   xww
    * @param    string          $string
    * @param    int/string      $length
    * @return   string
    */
    public function cutStr($string, $length)
    {
        if(mb_strlen($string, "utf8")>$length) {
            return mb_substr($string, 0, $length, "utf-8")."...";
        } else {
            return $string;
        }
    }

    /**
    * 根据阶段文本返回阶段id
    * @author   xww
    * @param    string      $processText
    * @return   int
    */
    public function getPrecessId($processText)
    {
      
        switch ($processText) {
            case '启航':
                return 1;
                break;
            case '领航':
                return 2;
                break;
            case '远航':
                return 3;
                break;
            default:
                return null;
                break;
        }
    }

    /**
    * 根据奖励等级id 返回对应文本
    * @author       xww
    * @param        int/string      $rewardId
    * @return       json
    */
    public function getRewardLevel($rewardId)
    {
        switch ((int)$rewardId) {
            case 1:
                return '监狱级';
                break;
            case 2:
                return '省局';
                break;
            case 3:
                return '省厅';
                break;
            case 4:
                return '省级';
                break;
            case 5:
                return '部级';
                break;
            case 6:
                return '国家级';
                break;
            default:
                return "未知";
                break;
        }
    }

    /**
    * 获取奖励等级数组
    * @author   xww
    * @return   array
    */
    public function getRewardLevelArray()
    {
        return [1=>"监狱级", 2=>"省局", 3=>"省厅",  4=>"省级", 5=>"部级", 6=>"国家级"];
    }

}
