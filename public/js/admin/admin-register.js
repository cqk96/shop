

//注册结果
function registerResult(data)
{
    
    var data_json = data;
    
    if(!data_json['status']['success'])
      alert(data_json['status']['message']);
    else
      window.location.href = '/admin/home';

}

//共同验证
function commonValidate()
{
    var finalReturn = true;
    //如果手机号错误进行提醒
    var phone = $("#userPhone").val().trim();

    $('.user-phone-box-result').removeClass('nosee');
    if(phone==''){
        phoneError('手机号不为空');
        return false;
    } else if(!(phone.length==11 && judgePhoneHead(phone))){
        phoneError('手机号不正确');
        return false;
    } else {
        
        var continueTodo = phoneAlreadyExist(phone);
        if(continueTodo)
            return false;

        $('.user-phone-box-error-icon').addClass('nosee');
        $('.user-phone-box-success-icon').removeClass('nosee');
        $('.user-phone-box').removeClass('has-error');
        $('.user-phone-box').addClass('has-success');
        $('.user-phone-box-error-msg').addClass('sr-only');
        $('.user-phone-box-success-msg').removeClass('sr-only');
        
    }

    

    //图形验证码错误进行提醒
    var picVerify = $("#picVerify").val().trim();
    $('.user-pic-verify-box-result').removeClass('nosee');

    var cookieVerify = getCookieValue('verify');
    if(picVerify==''){
        picVerifyError('图形码不空');          
        return false;
    } else if(picVerify != cookieVerify){
        picVerifyError('图形码错误');
        return false;
    } else {
        $('.user-pic-verify-box-error-icon').addClass('nosee');
        $('.user-pic-verify-box-success-icon').removeClass('nosee');
        $('.user-pic-verify-box').removeClass('has-error');
        $('.user-pic-verify-box').addClass('has-success');
        $('.user-pic-verify-box-error-msg').addClass('sr-only');
        $('.user-pic-verify-box-success-msg').removeClass('sr-only');
    }

    return finalReturn;

}

//验证
function Validate()
{
    
    var finalReturn = commonValidate();
    if(!finalReturn)
        return false;

    //密码错误提醒
    var password = $("#userPassword").val().trim();
    $('.user-password-box-result').removeClass('nosee');
    if(password==''){
        $('.user-password-box-error-icon').removeClass('nosee');
        $('.user-password-box-success-icon').addClass('nosee');
        $('.user-password-box').addClass('has-error');
        $('.user-password-box').removeClass('has-success');
        $('.user-password-box-error-msg').removeClass('sr-only');
        $('.user-password-box-success-msg').addClass('sr-only');

        $('.user-password-box-error-icon').removeClass('nosee');
        $('.user-password-box').addClass('has-error');
        $('.user-password-box-error-msg').text('密码不为空');
        $('.user-password-box-error-msg').removeClass('sr-only');
        return false;
    }else {
        $('.user-password-box-error-icon').addClass('nosee');
        $('.user-password-box-success-icon').removeClass('nosee');
        $('.user-password-box').removeClass('has-error');
        $('.user-password-box').addClass('has-success');
        $('.user-password-box-error-msg').addClass('sr-only');
        $('.user-password-box-success-msg').removeClass('sr-only'); 
    }

    //短信验证码错误进行提醒
    var msgVerify = $("#msgVerify").val().trim();
    $('.user-msg-verify-box-result').removeClass('nosee');
    if(msgVerify==''){
        $('.user-msg-verify-box-error-icon').removeClass('nosee');
        $('.user-msg-verify-box-success-icon').addClass('nosee');
        $('.user-msg-verify-box').addClass('has-error');
        $('.user-msg-verify-box').removeClass('has-success');
        $('.user-msg-verify-box-error-msg').removeClass('sr-only');
        $('.user-msg-verify-box-success-msg').addClass('sr-only'); 

        $('.user-msg-verify-box-error-icon').removeClass('nosee');
        $('.user-msg-verify-box').addClass('has-error');
        $('.user-msg-verify-box-error-msg').text('短信码不为空');
        $('.user-msg-verify-box-error-msg').removeClass('sr-only');
        return false;
    }else {
        $('.user-msg-verify-box-error-icon').addClass('nosee');
        $('.user-msg-verify-box-success-icon').removeClass('nosee');
        $('.user-msg-verify-box').removeClass('has-error');
        $('.user-msg-verify-box').addClass('has-success');
        $('.user-msg-verify-box-error-msg').addClass('sr-only');
        $('.user-msg-verify-box-success-msg').removeClass('sr-only'); 
    }

    //未勾选协议
    var userChecked = $("#protocalCheckbox").prop("checked");
    $('.user-protocal-box-result').removeClass('nosee');

    if(!userChecked){
        $('.user-protocal-box-error-icon').removeClass('nosee');
        $('.user-protocal-box-success-icon').addClass('nosee');
        $('.user-protocal-box').addClass('has-error');
        $('.user-protocal-box').removeClass('has-success');
        $('.user-protocal-box-error-msg').removeClass('sr-only');
        $('.user-protocal-box-success-msg').addClass('sr-only'); 

        $('.user-protocal-box-error-icon').removeClass('nosee');
        $('.user-protocal-box').addClass('has-error');
        $('.user-protocal-box-error-msg').text('要勾选');
        $('.user-protocal-box-error-msg').removeClass('sr-only');
        return false;
    } else {
        $('.user-protocal-box-error-icon').addClass('nosee');
        $('.user-protocal-box-success-icon').removeClass('nosee');
        $('.user-protocal-box').removeClass('has-error');
        $('.user-protocal-box').addClass('has-success');
        $('.user-protocal-box-error-msg').addClass('sr-only');
        $('.user-protocal-box-success-msg').removeClass('sr-only'); 
    }

    return true;

}

//图形验证码错误
function picVerifyError(text)
{
    
    $('.user-pic-verify-box-error-icon').removeClass('nosee');
    $('.user-pic-verify-box-success-icon').addClass('nosee');
    $('.user-pic-verify-box').addClass('has-error');
    $('.user-pic-verify-box').removeClass('has-success');
    $('.user-pic-verify-box-error-msg').removeClass('sr-only');
    $('.user-pic-verify-box-success-msg').addClass('sr-only');

    $('.user-pic-verify-box-error-icon').removeClass('nosee');
    $('.user-pic-verify-box').addClass('has-error');  
    $('.user-pic-verify-box-error-msg').text(text);
    $('.user-pic-verify-box-error-msg').removeClass('sr-only');
}

//手机号已存在
function phoneAlreadyExist(phone)
{
    hasPhone = false;
    //已存在提示
    $.ajax({
        url: '/hasPhone',
        type: "POST",
        async: false,
        data: {
            phone:phone,
        },
        success:function(data){
            var data_json = JSON.parse(data);
            if(data_json['success']){
                phoneError('手机号已存在');
                hasPhone = true;
            }
        }
    });

    return hasPhone;

}

//手机号填写出错
function phoneError(text)
{
    $('.user-phone-box-error-icon').removeClass('nosee');
    $('.user-phone-box-success-icon').addClass('nosee');
    $('.user-phone-box').addClass('has-error');
    $('.user-phone-box').removeClass('has-success');
    $('.user-phone-box-error-msg').removeClass('sr-only');
    $('.user-phone-box-success-msg').addClass('sr-only');

    $('.user-phone-box-error-icon').removeClass('nosee');
    $('.user-phone-box').addClass('has-error');
    $('.user-phone-box-error-msg').text(text);
    $('.user-phone-box-error-msg').removeClass('sr-only');

}

//判断手机号前三位
function judgePhoneHead(phone)
{
    
    var phoneThree = phone.substr(0,3);
    var tel = parseInt(phoneThree);
    
    var phoneHeadThree = [
          130,131,132,134,133,135,136,137,138,139,
          150,151,152,153,155,156,157,158,159,176,177,178,
          180,181,182,183,185,186,187,188,189
        ];
    var result = false;
    for(var i=0; i<phoneHeadThree.length; i++){
        if(tel==phoneHeadThree[i]){
            result = true;
            break;
        }
    }
    
    return result;

}


//倒计时
function changeSendPhoneText(time)
{
    
    document.cookie = "restTime="+time;

    if(time>=1){
		
		    $('.verifyBtn').text("剩余("+time+")秒可再发");
		    timer = setInterval("changeSendPhoneText("+(time-1)+")",1500);
		    clearTimeout(timer-1);

	  } else{
		    $('.verifyBtn').text("发送手机短信");
		    $('.verifyBtn').prop("disabled",false);
		    clearTimeout(timer);
        var currentTime = new Date().getTime()-1000;
        var expireDate = new Date(currentTime);
        document.cookie = "restTime=; expires="+expireDate+";";
		    return false;
	  }

}

//重置注册容器宽高
function getLoginBoxHW()
{
	  var widthValue = $("#register-box").width();
	  var heightValue = $("#register-box").height();
	  $('.login_sub').css({
		    "width": widthValue,
		    "height": heightValue
	  });
}

$(document).ready(function(){
  
  	//自动居中
  	getAutoMiddle('register-box');
  	getLoginBoxHW();
  	window.onresize = function(){
      	getAutoMiddle('register-box');
      	getLoginBoxHW();
  	}

    //判断是否还在倒计时中
    var restTime = getCookieValue('restTime');
    if(restTime){
      $('.verifyBtn').prop("disabled",true);
      changeSendPhoneText(restTime);
    }

  	//切换注册验证码
  	$('.registerVerify').click(function(){
  		$('.registerVerify').attr('src','/registerVerify?'+Math.random());
  	});

    //是否明文显示密码
    $('.showPassword').click(function(){
      var currentType = $("#userPassword").prop("type");
      if(currentType=='text'){
          $("#userPassword").prop("type",'password');
          $('.showPassword').removeClass('eyeOpen');
      }else {
          $("#userPassword").prop("type",'text');
          $('.showPassword').addClass('eyeOpen');
      }
      
      
    });

    //提交
    document.onkeydown=function(event){
        if(event.keyCode==13)
            $('.submitBtn').click();
    }
    

  	//发送短信验证码
  	$('.verifyBtn').click(function(){

  		//var canContinue = commonValidate();
      //if(!canContinue)
         // return false;

        //验证手机号
        var stillOk = commonValidate();
        if(!stillOk){
            return false;
        }

      var phoneArray = new Array();
      phoneArray['phone'] = $("#userPhone").val();

  		//发送验证码
      sendAjax(phoneArray,'/api/v1/user/getRegisterVerify2');

  		//验证码按钮处理
  		$('.verifyBtn').prop("disabled",true);
  		changeSendPhoneText(6);

  	});

    //真实发送
    $('.submitBtn').click(function(){
        var canContinue = Validate();
        if(!canContinue)
           return false;
        var postArray = new Array();
        postArray['phone'] = $("#userPhone").val();
        postArray['password'] = $("#userPassword").val();
        postArray['verify'] = $("#msgVerify").val();
        
        sendAjax(postArray,'/admin/user/register','false','registerResult');
    });

});