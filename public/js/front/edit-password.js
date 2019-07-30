
//修改密码成功
function resetPasswordFrontOk(data)
{
	
	if(data['status']['success']){
		//成功
		//@todo--重新登录
		$.ajax({
			url: '/api/v1/front/userOut',
			type: "GET",
			async: false
		});
		// $.ajax({
		// 	url: '/showCookie',
		// 	type: "GET",
		// 	async: false,
		// 	success: function(data){
		// 		console.log(data);
		// 	}
		// });
		// return false;
		window.location.href = '/login';
	} else {
		//失败
		showError('失败:'+data['status']['message']);
	}

}

//检测空值
function checkEmpty()
{
	if($("#captcha").val()==''){
		$("#captcha").focus();
		return false;
	} else if($("#newPassword").val()==''){
		$("#newPassword").focus();
		return false;
	}else if($("#confirmPassword").val()==''){
		$("#confirmPassword").focus();
		return false;
	}
	return true;
}

//检测两次输入的密码是否相同
function checkPassword()
{
	if($("#newPassword").val()!=$("#confirmPassword").val()){
		 showError('两次密码不相同');
		return false;
	}
	return true;
}

//倒计时
function changeSendPhoneText(time)
{
    
    document.cookie = "restTime="+time;

    if(time>=1){
		
		    $('.captcha-btn').text("剩余("+time+")秒");
		    timer = setTimeout("changeSendPhoneText("+(time-1)+")",1500);
		    clearTimeout(timer-1);

	  } else{
		    $('.captcha-btn').text("获取验证码");
		    $('.captcha-btn').prop("disabled",false);
		    clearTimeout(timer);
        var currentTime = new Date().getTime()-1000;
        var expireDate = new Date(currentTime);
        document.cookie = "restTime=; expires="+expireDate+";";
		    return false;
	  }

}

//发送成功
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
		// $("#captcha").val(data['data']);
	}
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

//发送短信
function sendPhone()
{
	//发送短信
	var postArr = new Array();
	postArr['phone'] = phone;
	sendAjax(postArr, '/api/v1/user/restPasswordVerify', 'true', 'sendOk');
	//发送短信--end
}
$(document).ready(function(){

	var ct = new Date().getTime()-1000;
    var ed = new Date(ct);
    document.cookie = "restTime=; expires="+ed+";";

	phone = '';
	$('.captcha-btn').click(function(){
		//检测用户是否登录
		$.ajax({
			url: '/api/v1/front/userStillIn',
			type: "GET",
			success: function(data){
				if(data['status']['success']){
					phone = data['data']['user_login'];
					var arr = document.cookie.match(new RegExp("(^| )restTime=([^;]*)(;|$)"));
					if(!(arr != null && arr[2]!='')){
						sendPhone();	
					}
				} else {
					//成功  跳转重新登录
					$.ajax({
						url: "/api/v1/front/userOut",
						type: "get",
						async: false
					});
					setTimeout(function(){
						//跳转登录页  携带地址
					window.location.href = '/login?url=/edit-password';
					},1000);
					

				}

			}
		});

	});

	//提交修改密码
	$('.certain-btn').click(function(){
		if(checkEmpty()){
			if(checkPassword()){
				//提交修改密码
				var postArr = new Array();
				postArr['captcha'] = $("#captcha").val();
				postArr['password'] = $("#newPassword").val();
				sendAjax(postArr, '/api/v1/front/resetPasswordFront', 'true', 'resetPasswordFrontOk');
			}
		}
	});

});