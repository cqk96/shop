<?php

use Nette\Mail\Message;

/**
* \Mail
*/
class Mail extends Message
{
  public $config;

  // [String] e-mail
  protected $from;
  // [Array] e-mail list
  protected $to;

  protected $title;
  protected $body;

  function __construct($to)
  {
    $this->config = require BASE_PATH.'/config/mail.php';

    $this->setFrom($this->config['username']);

    if ( is_array($to) ) {
      foreach ($to as $email) {
        $this->addTo($email);
      }
    } else {
      $this->addTo($to);
    }
  }

  public function from($from=null)
  {
    if ( !$from ) {
      throw new InvalidArgumentException("邮件发送地址不能为空！");
    }
    $this->setFrom($from);
    return $this;
  }

  public static function to($to=null)
  {
    if ( !$to ) {
      throw new InvalidArgumentException("邮件接收地址不能为空！");
    }
    return new Mail($to);
  }

  public function title($title=null)
  {
    if ( !$title ) {
      throw new InvalidArgumentException("邮件标题不能为空！");
    }
    $this->setSubject($title);
    return $this;
  }

  public function content($content=null)
  {
    if ( !$content ) {
      throw new InvalidArgumentException("邮件内容不能为空！");
    }
    $this->setHTMLBody($content);
    return $this;
  }

  /*
  *
  * 收邮件
  * 返回邮件Array
  */
  public function receive()
 {
    // 先建立连接
    $item = array(
        "host"      => $this->config['pop-host'],
        "port"      => $this->config['pop-port'],
        "user"      => $this->config['username'],
        "password"  => $this->config['password']
    );
    //echo "<html><head><meta charset='utf-8'></head></html>";
    $mails = $this->get_mails($item);
    return $mails;
 }


 /*
 * 获取邮件的方法
 */
 private function get_mails($array_values)
 {
   $host       = $array_values['host'];
   $port       = $array_values['port'];
   $user       = $array_values['user'];
   $password   = $array_values['password'];
 
   $msg        = '';
   $return_msg = '';

   $result = array();

   if(!($sock = fsockopen(gethostbyname($host),$port,$errno,$errstr)))
                exit($errno.': '.$errstr);
    stream_set_blocking($sock,true);


    $command = "USER ".$user."\r\n";
    fwrite($sock,$command);
    $msg = fgets($sock);

    $command = "PASS ".$password."\r\n";
    fwrite($sock,$command);
    $msg = fgets($sock);


    $command = "STAT\r\n";
    fwrite($sock,$command);
    $msg = fgets($sock);

    $command = "LIST\r\n";
    fwrite($sock,$command);
    $all_mails = array();
    while(true)
    {
        $msg = fgets($sock);

        if(!preg_match('/^\+OK/' , $msg) && !preg_match('/^\./' , $msg))
        {
          $msg = preg_replace('/\ .*\r\n/' , '' , $msg);

          array_push($all_mails,$msg);
        }
        if(preg_match('/^\./',$msg))
          break;
    }

    $detail_mails = array();
    for ($i=0; $i < count($all_mails); $i++) { 
      fwrite($sock, "RETR ".$all_mails[$i]."\r\n");
      $str = array("");
      $n=0;
      while(true)
      {
        $msg = fgets($sock);
        
        if(preg_match('/^\./',$msg))
          break;
        if(strlen($msg)<=2) {$n++;$str[$n]="";continue;} // 当此行长度小于等于2，即换行符时，后续行存入下一元素
        $str[$n]=$str[$n].$msg;
      }
      // 循环取值存入结果
      $mail = array();
      // 第一个是头
      //echo ($str[0]);
      //$rs2 = iconv_mime_decode_headers($str[0],ICONV_MIME_DECODE_CONTINUE_ON_ERROR);
      //var_dump($rs2);
      //var_dump(iconv_mime_decode("Subject: =?gb18030?B?u9i4tKO6xOPK1bW90ru34tfJ0a/Tyrz+?=",0,"UTF-8"));
      //iconv_mime_decode_headers($str[0],0,"UTF-8");
      $head = iconv_mime_decode_headers($str[0],0,"UTF-8");
      // From To Subject Date可用
      $mail["From"] = $head["From"];
      $mail["To"] = $head["To"];
      $mail["Subject"] = $head["Subject"];
      $mail["Date"] = $head["Date"];
      // 第四个(下标3)是不带html格式内容
      // 第六个(下标5)是带html格式内容

      if(count($str)<=5){
        //$mail["Body"] = iconv("GB2312", "UTF-8", base64_decode($str[3]));
      }else{
        $mail["Body"] = iconv("GB2312", "UTF-8", base64_decode($str[5]));
      }
      //在Body中通过正则匹配隐藏的字段
      $resultMatch = preg_match_all('/==YOUZIXIN.com(.*?)YOUZIXIN.com==/i', $mail["Body"], $matches);

      //测试时间日期转换
      array_push($result, $mail);
      // TODO:将此邮件存入数据库，根据==YOUZIXIN.com000001YOUZIXIN.com==进行匹配，获取session_number
      // 表所存：id from to subject body date  from_user_id  to_user_id  session_number 
      $userEmailobj = new UserEmail;
      $userEmailobj->from = $mail["From"];
      $userEmailobj->to = $mail["To"];
      $userEmailobj->subject = $mail["Subject"];
      $userEmailobj->body = $mail["Body"];
      $userEmailobj->from_user_id = $matches[1][2];
      $userEmailobj->to_user_id = $matches[1][1];
      $userEmailobj->session_number = $matches[1][0];
      $userEmailobj->date = date('Y-m-d H:i:s',strtotime($mail["Date"]));
      //获取类型
      $useremail = UserEmail::where('session_number','=',$matches[1][0])->take(1)->get();
      $userEmailobj->first_from_user_type = $useremail[0]['first_from_user_type'];
      $userEmailobj->first_to_user_type = $useremail[0]['first_to_user_type'];
      
      $userEmailobj->save();
      // 删除此邮件
      $command = "DELE ".$all_mails[$i]."\r\n";
      fwrite($sock,$command);
      $msg = fgets($sock);
      
    }
    // 关闭连接
    $command = "QUIT\r\n";
    fwrite($sock,$command);
    $msg = fgets($sock);
    return $result;
 }
}
