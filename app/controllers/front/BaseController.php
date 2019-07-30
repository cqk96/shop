<?php
/**
* BaseController   控制器基础父类
*/
namespace VirgoFront;
class BaseController
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
        /*if($is_mobile){
            //重定位
            header("Location: /Mobile");
        }*/

        //邮件导入
        if(!empty($_COOKIE['curUserId'])){
                $mailObj = new Mail('xxx@qq.com');
                $mailObj->receive();
        }

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

    /**
    * 去用户参数进行去空以及html转化
    */
    public static function change()
    {
        $config = $_REQUEST;
        foreach ($config as &$value) {
            $value = htmlentities(trim($value));
        }
        unset($value);
    }

    /**
    * html提示
    * @author   xww
    * @param    string  $text
    * @return   string
    */
    public function showHtmlNotice($text)
    {
        
        $str = "<!DOCTYPE html>";
        $str .= '<html lang="en">';
        $str .= '<head>';
        $str .=     '<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">';
        $str .=     '<meta charset="UTF-8">';
        $str .=     '<title>提示</title>';
        $str .=     '<style type="text/css">';
        $str .=         'html,body,div,span,input,p,img,table,tbody,tr,td,a{padding: 0px; margin: 0px; } html,body {width: 100%; height: 100%; background-color: #6e6efa; font-size: 19px; padding-top: 72px; text-align: center; color: #FFF;     overflow: hidden; } .text-box { margin: 0 auto; width: 90%; line-height: 1.5; }';
        $str .=     '</style>';
        $str .= '</head>';
        $str .= '<body>';
        $str .= '<div class="text-box">';
        $str .= $text;
        $str .= '</div>';
        $str .= '</body>';
        $str .= '</html>';

        return $str;

    }

}
