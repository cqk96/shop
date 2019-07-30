<?php
/**
*  生成图形验证码
* @author  xww <5648*****@qq.com>
* @version 1.0.0
* @since 1.0.0  无错使用
* @since 1.0.1  修改y轴偏移计算方法
* @todo version 1.0.2  增加验证码背景图
*/
namespace VirgoUtil; 
class Captcha {

	/*宽高*/ 
	public $width;
    public $height;

    /*输入的字符串*/ 
    public $_str;

    /*字体大小*/
    public $_fontSize=14; 

    /*画布背景rgb*/
    public $bg_r = 77;
    public $bg_g = 176;
    public $bg_b = 250;

    /*字串rgb*/
    public $str_r = 255;
    public $str_g = 255;
    public $str_b = 255;

    /*噪点颜色*/
    public $pixel_r = 39;
    public $pixel_g = 245;
    public $pixel_b = 155;

    /*是否启用噪点*/
    public $is_pixel = true;

    /*是否采用背景图资源*/ 
    public $use_bg = false;

    /*背景图资源*/ 
    public $bgResource;

	/**
    * 初始化
	* @author xww
	* @return void
    */    
    function __construct($str, $width=200, $height=30)
    {
        // 异常捕获
        try{
        	
        	if(empty($str)){throw new Exception("Error Param");
        	}

        	$this->width = $width;
	        $this->height = $height;
	        $this->_str = $str;

        } catch(Exception $e){
        	echo "<h1>".$e->getMessage()."</h1>";
        	exit();
        }
    }

    /**
    * 调用方法检错
	* @author xww
	* @return void
    */
    function __call($name,$arguments)
    {
        echo "调用的方法".$name."不存在于本类中";
    }

    /**
    * 综合检错
	* @author xww
	* @return void
    */
    public function judge_error()
    {
        //检测是否开启gd拓展
        if(!extension_loaded("gd")){throw new Exception("尚未开启gd库", 1);}
    }

    /**
    * 设置验证码尺寸
    * @author xww
    * @param  [$width]    int/string
    * @param  [$height]    int/string
    * @return void
    */
    public function set_bg_size($width,$height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    /**
    * 设置背景颜色(rgb)
	* @author xww
	* @param  [$r]   int/string
	* @param  [$g]   int/string
	* @param  [$b]   int/string
	* @return void
    */
    public function set_bg_color($r,$g,$b)
    {
        $this->bg_r = $r;
        $this->bg_g = $g;
        $this->bg_b = $b;
    }

    /**
    * 设置文字颜色(rgb)
	* @author xww
	* @param  [$r]   int/string
	* @param  [$g]   int/string
	* @param  [$b]   int/string
	* @return void
    */
    public function set_str_color($r,$g,$b)
    {
        $this->str_r = $r;
        $this->str_g = $g;
        $this->str_b = $b;
    }

    /**
    * 设置噪点颜色(rgb)
	* @author xww
	* @param  [$r]   int/string
	* @param  [$g]   int/string
	* @param  [$b]   int/string
	* @return void
    */
    public function set_pixel_color($r,$g,$b)
    {
        $this->pixel_r = $r;
        $this->pixel_g = $g;
        $this->pixel_b = $b;
    }

    /**
    * 设置是否启用噪点
	* @author xww
	* @param  [$bool]   boolean
	* @return void
    */ 
    public function setPixel($bool)
    {
    	$this->is_pixel = $bool;
    }

    /**
    * 设置字体大小
    * @author xww
    * @param  [$size]   int/string
    * @return void
    */ 
    public function setFontSize($size)
    {
        $this->_fontSize = $size;
    }

    /**
    * 设置是否使用图片资源
    * @author xww
    * @param  [$use]   bool
    * @param  [$resource]   resource  of image
    * @return void
    */ 
    public function setUserBg($use, $resource)
    {
        $this->use_bg = $use;
        $this->bgResource = $resource;
    }

    //形成验证码
    public function verifica()
    {   
        
        try{
            //检错
            $this->judge_error();

            //创建画布  默认黑色
            $img = imagecreatetruecolor($this->width, $this->height);
            
            if($this->use_bg && !empty($this->bgResource)){
                // 使用背景图片资源
                imagecopyresampled($img, $this->bgResource, 0, 0, 0, 0, $this->width, $this->height, $this->width, $this->height);
            } else {
                //调制背景颜色  16进制
                $color = imagecolorallocate($img, $this->bg_r, $this->bg_g, $this->bg_b);
                
                //上色
                imagefill($img, 0, 0, $color);
            }

            //文字颜色
            $stringcolor = imagecolorallocate($img, $this->str_r, $this->str_g, $this->str_b);

            // 绘制文字 初始偏移-x
            $limit = $this->width/strlen($this->_str);
            $offset = $limit<$this->_fontSize? $limit:$this->_fontSize;
            $x1 = rand(0,$offset);

            // y1的初始偏移
            if($this->height<=$this->_fontSize){
            	$y1 = $this->_fontSize;
            	$yLimit = $y1;
            } else {
            	$yOffset = $this->height-$this->_fontSize;
            	$yLimit = $this->height-$yOffset;
            	$y1 = rand($yLimit, $this->height);
            }

            //记录形成的文字
            for($i=0;$i<strlen($this->_str);$i++){
                //记录被写过的位置
                $y1 = rand($yLimit,$this->height);

                // 字体最大也小
                // imagestring($img, 5, $x1, $y1,$this->_str[$i]." " , $stringcolor);

               	// 使用新函数
               	imagettftext($img, $this->_fontSize, 0, $x1, $y1, $stringcolor, $_SERVER['DOCUMENT_ROOT'].'/font/Soopafresh.ttf', $this->_str[$i]);
                $x1 = $x1+$this->width/strlen($this->_str);

                // 大于宽度
                if($x1>$this->width){
                	$x1 = $this->width-$this->_fontSize;
                }
                
            }
            
            if($this->is_pixel){
                //绘制噪点
                $pixel_color = imagecolorallocate($img, $this->pixel_r, $this->pixel_g, $this->pixel_b);
                $times = rand(50,$this->width);
                for($i=0;$i<$times;$i++){
                    //随机位置
                    $x = rand(0,$this->width);
                    $y = rand(0,$this->height);
                    imagesetpixel($img, $x, $y, $pixel_color);
                }
            }

            //显示
            $data = ob_get_contents();
            
            ob_clean();/*加上此行可以显示*/
            header('Cache-Control: private, max-age=0, no-store, no-cache, must-revalidate');
            header('Cache-Control: post-check=0, pre-check=0', false);      
            header('Pragma: no-cache');
            header("Content-type: image/png;");
            imagepng($img);
            imagedestroy($img);
        }catch(Exception $e){
            echo $e->getMessage();
            exit();
        }

    }

}