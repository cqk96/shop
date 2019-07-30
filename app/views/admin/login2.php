<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 

Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401

-19991224/loose.dtd">
<!-- saved from url=(0064)

http://www.17sucai.com/preview/137615/2015-01-

15/demo/index.html -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 

Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1

-transitional.dtd"><HTML 
xmlns="http://www.w3.org/1999/xhtml"><HEAD><META 

content="IE=11.0000" 
http-equiv="X-UA-Compatible">
 
<META http-equiv="Content-Type" content="text/html; 

charset=utf-8"> 
<TITLE>后台管理员登陆</TITLE> 
<SCRIPT src="/js/jquery-1.11.1.min.js" 

type="text/javascript"></SCRIPT>
 
<STYLE>
body{
  background: #ebebeb;
  font-family: "Helvetica Neue","Hiragino Sans GB","Microsoft YaHei","\9ED1\4F53",Arial,sans-serif;
  color: #222;
  font-size: 12px;
}
*{padding: 0px;margin: 0px;}
.top_div{
  background: #008ead;
  width: 100%;
  height: 350px;
}
.ipt{
  border: 1px solid #d3d3d3;
  padding: 10px 10px;
  width: 290px;
  border-radius: 4px;
  padding-left: 35px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
  box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
  -webkit-transition: border-color ease-in-out .15s,-

webkit-box-shadow ease-in-out .15s;
  -o-transition: border-color ease-in-out .15s,box-shadow 

ease-in-out .15s;
  transition: border-color ease-in-out .15s,box-shadow 

ease-in-out .15s
}
.ipt:focus{
  border-color: #66afe9;
  outline: 0;
  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 

8px rgba(102,175,233,.6);
  box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px 

rgba(102,175,233,.6)
}
.u_logo{
  background: url("/images/username.png") no-repeat;
  padding: 10px 10px;
  position: absolute;
  top: 43px;
  left: 40px;

}
.p_logo{
  background: url("/images/password.png") no-repeat;
  padding: 10px 10px;
  position: absolute;
  top: 12px;
  left: 40px;
}

.v_logo{
  background: url("/images/password.png") no-repeat;
  padding: 10px 10px;
  position: absolute;
  top: 17px;
  left: 40px;
}

a{
  text-decoration: none;
}
.tou{
  background: url("/images/tou.png") no-repeat;
  width: 97px;
  height: 92px;
  position: absolute;
  top: -87px;
  left: 140px;
}
.left_hand{
  background: url("/images/left_hand.png") no-repeat;
  width: 32px;
  height: 37px;
  position: absolute;
  top: -38px;
  left: 150px;
}
.right_hand{
  background: url("/images/right_hand.png") no-repeat;
  width: 32px;
  height: 37px;
  position: absolute;
  top: -38px;
  right: -64px;
}
.initial_left_hand{
  background: url("/images/hand.png") no-repeat;
  width: 30px;
  height: 20px;
  position: absolute;
  top: -12px;
  left: 100px;
}
.initial_right_hand{
  background: url("/images/hand.png") no-repeat;
  width: 30px;
  height: 20px;
  position: absolute;
  top: -12px;
  right: -112px;
}
.left_handing{
  background: url("/images/left-handing.png") no-repeat;
  width: 30px;
  height: 20px;
  position: absolute;
  top: -24px;
  left: 139px;
}
.right_handinging{
  background: url("/images/right_handing.png") no-repeat;
  width: 30px;
  height: 20px;
  position: absolute;
  top: -21px;
  left: 210px;
}

.ipt_verify{
  border: 1px solid #d3d3d3;
  padding: 10px 10px;
  width: 87px;
  border-radius: 4px;
  padding-left: 34px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
  box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
  -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
  -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
  transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
  }

  .info{
    display: none;
    float: left;
    text-align: left;
    padding-left: 9%;
    padding-top: 5px;
    color: red;
  }

  .info_out{
    float: left;
    text-align: left;
    padding-left: 9%;
    padding-top: 5px;
    color: red;
  }

.logo-img {
  margin: 0px auto 0 auto;
  display: block;
  width: auto;
}
.rest-img {
  width: 100%;
  height: 63px;
}
</STYLE>
     
<SCRIPT type="text/javascript">
$(function(){
  //得到焦点
  $("#password").focus(function(){
    $("#left_hand").animate({
      left: "150",
      top: " -38"
    },{step: function(){
      if(parseInt($("#left_hand").css("left"))>140){
        $("#left_hand").attr("class","left_hand");
      }
    }}, 2000);
    $("#right_hand").animate({
      right: "-64",
      top: "-38px"
    },{step: function(){
      if(parseInt($("#right_hand").css("right"))> -70){
        $("#right_hand").attr("class","right_hand");
      }
    }}, 2000);
  });
  //失去焦点
  $("#password").blur(function(){
    $("#left_hand").attr("class","initial_left_hand");
    $("#left_hand").attr("style","left:100px;top:-12px;");
    $("#right_hand").attr("class","initial_right_hand");
    $("#right_hand").attr("style","right:-112px;top:-12px");
  });
});
</SCRIPT>
 
<META name="GENERATOR" content="MSHTML 

11.00.9600.17496"></HEAD> 
<BODY>
<DIV class="top_div">
  
</DIV>
<DIV style="background: rgb(255, 255, 255);margin: -130px auto auto;border: 1px solid rgb(231, 231, 231);border-image: none;width: 410px;height: 230px;text-align: center;">
<DIV style="width: 165px; height: 96px; position: absolute;">
<DIV class="tou"></DIV>
<DIV class="initial_left_hand" id="left_hand"></DIV>
<DIV class="initial_right_hand" id="right_hand"></DIV></DIV>
  <P style="padding: 30px 0px 10px; position: relative;">
    <SPAN class="u_logo"></SPAN>         
    <INPUT class="ipt" id="user_login" type="text" name="user_login" placeholder="请输入用户名或邮箱" value=""> 
    </P>
  <P style="position: relative;" id="passwordBox">
    <SPAN class="p_logo"></SPAN>         
    <INPUT class="ipt" id="password" type="password" name="password" placeholder="请输入密码" value="">   
  </P>
  <!-- <P style="position: relative;"><SPAN class="v_logo"></SPAN>         
    <INPUT maxLength="4" class="ipt_verify" id="verify" type="text" name="verify" placeholder="请输入验证码" value=""> 
    <span class="captcha-box">
      <img src="/verify" style="position: relative;top: 10px;cursor: pointer;">
    </span>  
  </P> -->
  <P class="info">
    123
  </P>
<DIV style="height: 50px; line-height: 50px; margin-top: 30px; border-top-color: rgb(231, 231, 231); border-top-width: 1px; border-top-style: solid;">
  <table style="width: 100%;background: #FFF;">
      <tbody>
         <tr>
            <td>
              <A style="background: rgb(0, 142, 173); padding: 7px 10px; border-radius: 4px; border: 1px solid rgb(26, 117, 152); border-image: none; color: rgb(255, 255, 255); font-weight: bold;" href="javascript:void(0);" id="loginBtn">登陆</A>  
            </td>
            <td>
              <?php if($site["register_type"]!=0){ ?>
              <A style="background: rgb(0, 142, 173); padding: 7px 10px; border-radius: 4px; border: 1px solid rgb(26, 117, 152); border-image: none; color: rgb(255, 255, 255); font-weight: bold;" href="/signup" id="">注册</A>  
              <?php } else { echo "&nbsp;"; } ?>
            </td>
            <td>
              <A style="background: rgb(0, 142, 173); padding: 7px 10px; border-radius: 4px; border: 1px solid rgb(26, 117, 152); border-image: none; color: rgb(255, 255, 255); font-weight: bold;" href="javascript:void(0);" id="forgetPwd">忘记密码</A>  
            </td>
         </tr>
      </tbody>
  </table>
  <!-- <P style="margin: 0px 35px 20px 45px;"><SPAN style="float: left;"><A style="color: rgb(204, 204, 204);" href="#">忘记密码?</A></SPAN>  -->
           <!-- <SPAN style="float: right;"><A style="color: rgb(204, 204, 204); margin-right: 10px;" href="#">注册</A>   -->
  
           <!-- </SPAN>         </P></DIV></DIV> -->
  <div style="text-align:center;">
</div>
<script type="text/javascript" src="/js/jquery.min.js"></script>
<!-- <script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/myFuncs.js"></script>
<script type="text/javascript" src="/js/admin-login.js"></script> -->
<script type="text/javascript">
 //点击事件
  $("#loginBtn").click(function(){

    var ok = true;
    $("input").each(function() {
        var curPlaceHolder = $(this).attr("placeholder");
        var curValue = $.trim($(this).val());
        if( curValue=="" || curValue==curPlaceHolder ) {
            $('.info').text(curPlaceHolder);
            $('.info').css('display','block');
            ok = false;
            return false;
        }
    });

    if( ok ) {

       //判断是否所有输入框都有内容
      var user_login = $.trim( $('#user_login').val() );
      var password = $.trim( $('#password').val() );
      var verify = 1;// $.trim( $('#verify').val() );

        //发送ajax
        $.post('/admin/readUser',{'user_login':user_login, 'password':password, 'verify': verify}, function(data2){

            // console.log( data2 );
            // var data2 = JSON.parse(data);

            // if($.trim(data) == '验证码出错'){
            //   $('.info').text('验证码出错');
            //   $('.info').css('display','block');
            //   return;
            // }
            // if($.trim(data) =='用户名或密码错误'){
            //   $('.info').text('用户名或密码错误');
            //   $('.info').css('display','block');
            //   return;
            // }
            
            if(data2['status']['success']){
                //判断用户名是否登录成功
                window.location.href = '/admin/home';
            } else {
                $('.info').text(data2['status']['message']);
                $('.info').css('display','block');
                return;
            }
          
        });

    }
    
});

   window.onkeypress=function(event){
    //回车事件
    if(event.keyCode==13)
      $("#loginBtn").click();
  }

$(document).ready(function(){

  // 判断是否 ie ie10以下处理placeholder
  _userAgent = navigator.userAgent;
  if(_userAgent.indexOf("MSIE")>0 && parseInt(_userAgent.match(/.*?MSIE\s(.*?);/)[1])<=9){

      $("#passwordBox").html('<SPAN class="p_logo"></SPAN><INPUT class="ipt" id="password" type="text" name="password" placeholder="请输入密码" value="请输入密码"> ');
      
      $("#passwordBox").delegate("input", "click", "", function(){
        var curPlaceHolder = $(this).attr("placeholder");
        var curValue = $.trim($(this).val());
        if( curValue==curPlaceHolder ) {
          $("#passwordBox").html('<SPAN class="p_logo"></SPAN><INPUT class="ipt" id="password" type="password" name="password" placeholder="请输入密码" value=""> ');
        } else {
          $("#passwordBox").html('<SPAN class="p_logo"></SPAN><INPUT class="ipt" id="password" type="password" name="password" placeholder="请输入密码" value="' + curValue + '"> ');
        }      
        $("#password").focus();
      });

      $("#passwordBox").delegate("input", "blur", "", function(){
        var curPlaceHolder = $(this).attr("placeholder");
        var curValue = $.trim($(this).val());
        if( curValue==curPlaceHolder || curValue=="" ) {
          $("#passwordBox").html('<SPAN class="p_logo"></SPAN><INPUT class="ipt" id="password" type="text" name="password" placeholder="请输入密码" value=" ' + curPlaceHolder + '"> ');
        }     
      });

      // $("#passwordBox").delegate("input", "focus", "", function(){
      //      console.log(1);
      // });

      $("input[type='text']").each(function(){
        var curPlaceHolder = $(this).attr("placeholder");
        var curValue = $.trim($(this).val());
        if( curValue=="" ) {
          $(this).val(curPlaceHolder);
        }
        
      });

      $("input[type='text']").focus(function(){
      
      var curPlaceHolder = $.trim($(this).attr("placeholder"));
      var curValue = $.trim($(this).val());

      if( curPlaceHolder==curValue ) {
        $(this).val("");
      }

    });

    $("input[type='text']").blur(function(){
      
      var curPlaceHolder = $.trim($(this).attr("placeholder"));
      var curValue = $.trim($(this).val());

      if( curValue=="" ) {
        $(this).val(curPlaceHolder);
      }

      // 如果是密码则要变成密码
      if($(this).attr("id")=="password") {
            $("#passwordBox").html('<SPAN class="p_logo"></SPAN><INPUT class="ipt" id="password" type="password" name="password" placeholder="请输入密码" value="' + curValue + '"> ');
      }

    });

  }

  $("#forgetPwd").click(function(){

    var rs = confirm("是否请求管理员辅助重置密码");

    if(rs){
      var userLogin = $("#user_login").val();
      if(userLogin==""){ alert("请填写账号"); return false; }

      // @todo 增加提醒
      $.ajax({
        url: "/api/v1/message/forgetPwdNotice",
        type: "POST",
        dataType: "JSON",
        data: {
          userId: 1,
          content: userLogin+"账号请求管理员辅助重置密码"
        },
        success: function(response){
          if(response['status']['success']){
            alert("已发送, 请等待");
          }
        }
      });

    }

  });

  // 重新请求图形验证码
  var clickCount = 0;
  $('.captcha-box').delegate("img", "click", "", function(){
      clickCount++;
      $('.captcha-box').html('<img src="/verify?' + clickCount + '" style="position: relative;top: 10px;cursor: pointer;">');
  });

});

</script>
</BODY></HTML>
