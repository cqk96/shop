<?php
/**
* \AdminController
*/
namespace VirgoFront;
class VerifyController extends BaseController
{
    public $width;
    public $height;

    /*画布背景rgb*/
    public $bg_r = 77;
    public $bg_g = 176;
    public $bg_b = 250;

    /*字串rgb*/
    public $str_r = 255;
    public $str_g = 255;
    public $str_b = 255;

    /*字串位数*/
    public $digit = 4;

    /*噪点颜色*/
    public $pixel_r = 39;
    public $pixel_g = 245;
    public $pixel_b = 155;

    /*是否启用噪点*/
    public $is_pixel = true;

    /*默认采用数字型验证码*/
    public $type = 3;

    //初始化
    function __construct()
    {
        $this->width = 200;
        $this->height = 30;
    }

    function __call($name,$arguments)
    {
        echo "调用的方法".$name."不存在于本类中";
    }

    //综合检错
    public function judge_error()
    {
        //检测是否开启gd拓展
        if(!extension_loaded("gd"))
            throw new \Exception("尚未开启gd库", 1);
    }

    //设置验证码尺寸
    public function set_bg_size($width,$height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    //设置背景颜色
    public function set_bg_color($r,$g,$b)
    {
        $this->bg_r = $r;
        $this->bg_g = $g;
        $this->bg_b = $b;
    }

    //设置文字颜色
    public function set_str_color($r,$g,$b)
    {
        $this->str_r = $r;
        $this->str_g = $g;
        $this->str_b = $b;
    }

    //设置噪点颜色
    public function set_pixel_color($r,$g,$b)
    {
        $this->pixel_r = $r;
        $this->pixel_g = $g;
        $this->pixel_b = $b;
    }

    //选择字串构建类型 1字母大小写 2字母数字 3数字
    public function choose_type($num)
    {
        $judge_num = rand(0,100);
        $judge_num_2 = rand(1,3);
        switch ($num) {
            case 1:
                if($judge_num%2==0)
                    $return = rand(65,90);
                else
                    $return = rand(97,122);
                return chr($return);
                break;

            case 2:
                if($judge_num_2==1)
                    $return = rand(65,90);
                else if($judge_num_2==2)
                    $return = rand(97,122);
                else
                    $return = rand(0,9);
                if($judge_num_2!=3)
                    return chr($return);
                else
                    return $return;
                break;

            default:
                return rand(0,9);
                break;
        }
    }
    //形成验证码
    public function verifica()
    {   
        try{
            //检错
            $this->judge_error();

            //创建画布  默认黑色
            $img = imagecreatetruecolor($this->width, $this->height);
            
            //调制背景颜色  16进制
            $color = imagecolorallocate($img, $this->bg_r, $this->bg_g, $this->bg_b);
            
            //上色
            imagefill($img, 0, 0, $color);

            //文字颜色
            $stringcolor = imagecolorallocate($img, $this->str_r, $this->str_g, $this->str_b);

            //绘制文字 初始偏移
            $x1 = rand(10,$this->width/5);
            
            //记录形成的文字
            $verify_str = "";
            for($i=0;$i<$this->digit;$i++){
                //记录被写过的位置
                $str = $this->choose_type($this->type);
                $verify_str = $verify_str.$str;
                $y1 = rand($this->height/4,$this->height/2);
                imagestring($img, 800, $x1, $y1,$str." " , $stringcolor);
                $x1 = $x1+$this->width/5;
            }
            //cookie
            setcookie('verify',"");
            setcookie('verify',$verify_str,time()+300,"/");
            

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
            //var_dump($data);
            ob_clean();/*加上此行可以显示*/
            header('Cache-Control: private, max-age=0, no-store, no-cache, must-revalidate');
            header('Cache-Control: post-check=0, pre-check=0', false);      
            header('Pragma: no-cache');
            header("Content-type: image/png;");
            imagepng($img);
            imagedestroy($img);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}

//调用
