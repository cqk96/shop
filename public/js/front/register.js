//发送注册
function sendRegister()
{
	//非空判断
	var rs = judgeEmpty();
	if(rs){
		if(checkPwdEqual()){
			var pwd = $("#pwd").val();
			//密码加密
			$.ajax({
				url: '/api/v1/user/passwordEncrypt?password='+pwd,
				type: "GET",
				dataType: "JSON",
				async: "false",
				success: function(data){
					if(data['status']['success']){
						//进行注册
						var postArr = new Array();
						postArr['phone'] = $("#telPhone").val();
						postArr['captcha'] = $("#captcha").val();
						postArr['password'] = data['data'];
						//注册--end
						sendAjax(postArr, '/api/v1/user/doRegister', 'true', 'registerResult')
					}
				}
			});
			//密码加密--end
		}
	}
}

//发送验证码
function sendPhone()
{
	if(checkPhoneLength()){
			if(judgePhoneHead($("#telPhone").val())){
				//发送短信
				var postArr = new Array();
				postArr['phone'] = $("#telPhone").val().trim();
				sendAjax(postArr, '/api/v1/user/getRegisterVerify2', 'true', 'sendOk');
				//发送短信--end

			} else {
				showError('非法手机号');
				return false;
			}
		}
}

//注册结果
function registerResult(data)
{
	
	if(data['status']['success']){
		//注册成功
		$('.transparent-box').removeClass("nosee");
		$('.msg-box').removeClass("visibility");

		// var url = $("#url").val();
		// if(url==''){
			// 由于是优惠券活动 所以跳转至优惠券
			url = '/coupons';
		// }

		//将用户设置为登录状态存储于cookie中
		var setUserIn = '/api/v1/user/setUserIn?user_login='+data['data']['user_login']+"&access_token="+data['data']['access_token'];
		$.ajax({
			url: setUserIn,
			type: "GET",
			async: true
		});

		var currentTime = new Date().getTime()-1000;
        var expireDate = new Date(currentTime);
        document.cookie = "restTime=; expires="+expireDate+";";

        // 过期注册会话
        outUserInput();

		//@todo 跳转
		setTimeout(function(){
			window.location.href = url;
		},1500);
		//跳转--end
	} else {
		showError("注册失败,由于:"+data['status']['message']);
		return false;
	}

}

//验证码发送成功
function sendOk(data)
{
	
	if(data['status']['code']!="001"){
		showError(data['status']['message']);
		return false;
	} else {
		var arr = document.cookie.match(new RegExp("(^| )restTime=([^;]*)(;|$)"));
		if(!(arr != null && arr[2]!='')){
			changeSendPhoneText(60);
		}
		showError("验证码发送成功");
		// $("#captcha").val(data['data']);
	}

}

//判断两次输入是否相同
function checkPwdEqual()
{
	if($("#pwd").val()!=$("#certainPwd").val()){
		showError('两次密码不相同');
		return false;
	}
	return true;
}

//检测手机号长度
function checkPhoneLength()
{
	if($("#telPhone").val().length!=11){
		showError('手机号长度不对');
		return false;
	}
	return true;
}

//显示错误以及隐藏
function showError(txt)
{

	if($('.errorRemindBox').length!=0){
		$(".errorRemindBox").text(txt);
		$(".errorRemindBox").removeClass("nosee");
	} else {
		$('.errorRemindBox').remove();
		var str = "<span class='errorRemindBox'>"+txt+"</span>";
		$('.showErrDiv').append(str);
	}
	setTimeout(function(){
		$(".errorRemindBox").remove();
	},400);

}

//检测前三位
//判断手机号前三位
function judgePhoneHead(phone)
{
    
    var phoneThree = phone.substr(0,3);
    var tel = parseInt(phoneThree);
    
    var phoneHeadThree = [
          130,131,132,134,133,135,136,137,138,139,
          150,151,152,153,155,156,157,158,159,
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

//判断是否为空
function judgeEmpty()
{
	if($("#telPhone").val()==''){
		showError("手机号不为空");
		return false;
	} else if($("#captcha").val()==''){
		showError("验证码不为空");
		return false;
	} else if($("#pwd").val()==''){
		showError("密码不为空");
		return false;
	} else if($("#certainPwd").val()==''){
		showError("验证密码不为空");
		return false;
	} else {
		return true;
	}
}

//倒计时
function changeSendPhoneText(time)
{
    
    document.cookie = "restTime="+time;

    if(time>=1){
		    $('.captcha-btn').text("剩余"+time+"秒");
		    timer = setTimeout("changeSendPhoneText("+(time-1)+")",1500);
		    // $('.captcha-btn').unbind("click");
	  } else{
		    $('.captcha-btn').text("获取验证码");
		    $('.captcha-btn').prop("disabled",false);
		    clearTimeout(timer);
		 //    $('.captcha-btn').click(function(){
			// 	sendPhone();
			// });
	        var currentTime = new Date().getTime()-1000;
	        var expireDate = new Date(currentTime);
	        document.cookie = "restTime=; expires="+expireDate+";";
	        $('.captcha-btn').bind("click");
		    return false;
	  }
}

$(document).ready(function(){

	// 将注册会话中的数据填入对应地方
	getUserInputValue();

	// icheck 初始化
	$('.register-protocol').iCheck({
	    checkboxClass: 'icheckbox_flat-orange',
	    radioClass: 'iradio_square',
	    increaseArea: '30%'
	});

	// 初始化清空剩余时间cookie
	var currentTime = new Date().getTime()-1000;
    var expireDate = new Date(currentTime);
    document.cookie = "restTime=; expires="+expireDate+";";

	//统计点击次数
	clickCount = 1;

	//计算宽高
	var height = $('.msg-box').height();
	var width = $('.msg-box').width();
	var clientHeight = $(document.body)[0].clientHeight;
	var clientWidth = $(document.body)[0].clientWidth;
	var left = (clientWidth-width)/2;
	var top = (clientHeight-height-100)/2;
	$('.msg-box').css({
		"left": left,
		"top": top
	});

	$('.captcha-btn').click(function(){
		// 获取cookie的值
		var restTime = getCookieValue('restTime');
		if(restTime=="" && restTime!==false){
			sendPhone();
		}
	});

	// 点击注册
	$(".register-btn").click(function(){
		
		// 未同意用户协议
		if(!$('.register-protocol').prop("checked")){
			showError("请勾选用户协议");
			return false;
		}

		// 存储用户填写的数据至会话
		setUserInputValue();

		sendRegister();

	});


});

// 将用户填写的数据存入会话
function setUserInputValue()
{
	
	// 手机号
	var telPhone = $("#telPhone").val();

	// 验证码
	var captcha = $("#captcha").val();

	// 密码
	var pwd = $("#pwd").val();

	if(telPhone!=''){
		setInCookie("register_username", telPhone, 60*60*1000);		
	}

	if(captcha!=''){
		setInCookie("register_captcha", captcha, 60*60*1000);		
	}

	if(pwd!=''){
		setInCookie("register_pwd", pwd, 60*60*1000);		
	}

}

function setInCookie (name, value,time) {
	var currentTime = new Date().getTime()+time;
    var expireDate = new Date(currentTime);
	document.cookie = name+"="+value+";expires="+expireDate;
}

// 将注册会话中的数据填入对应地方
function getUserInputValue()
{
	var registerUsername = getCookieValue('register_username');
	var registerCaptcha = getCookieValue('register_captcha');
	var registerPwd = getCookieValue('register_pwd');

	if(registerUsername){
		$("#telPhone").val(registerUsername);
	}

	if(registerCaptcha){
		$("#captcha").val(registerCaptcha);
	}

	if(registerPwd){
		$("#pwd").val(registerPwd);
	}

}

// 注册用户填写值过期
function outUserInput()
{
	var currentTime = new Date().getTime()-1000;
    var expireDate = new Date(currentTime);
    document.cookie = "register_username=; expires="+expireDate+";";
    document.cookie = "register_captcha=; expires="+expireDate+";";
    document.cookie = "register_pwd=; expires="+expireDate+";";
}