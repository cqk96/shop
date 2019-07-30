//绑定成功
function editBindPhoneOk(data)
{
	
	if(data['status']['code']=="001"){
		//成功  跳转重新登录
		$.ajax({
			url: "/api/v1/front/userOut",
			type: "get",
			async: false
		});
		setTimeout(function(){
			//用户登出
			window.location.href = '/login';
		},1000);
	} else {
		// @todo  根据返回code处理逻辑
		showError(data['status']['message']);
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

//验证码发送成功
function sendOk(data)
{
	// console.log(data);
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

//发送验证码
function sendPhone()
{
	if(checkPhoneLength()){
			if(judgePhoneHead($("#phone").val())){
				//发送短信
				var postArr = new Array();
				postArr['phone'] = $("#phone").val().trim();
				sendAjax(postArr, '/api/v1/user/getRegisterVerify2', 'true', 'sendOk');
				//发送短信--end
			} else {
				showError('非法手机号');
				return false;
			}
		}
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

//检测手机号长度
function checkPhoneLength()
{
	if($("#phone").val().length!=11){
		showError('手机号长度不对');
		return false;
	}
	return true;
}

//检测是否为空
function judgeEmpty()
{
	
	if($("#loginPwd").val()==""){
		$("#loginPwd").focus();
		return false;
	} else if($("#phone").val()=="") {
		$("#phone").focus();
		return false;
	} else if($("#captcha").val()==""){
		$("#captcha").focus();
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
$(document).ready(function(){

	// 清除cookie
	var ct = new Date().getTime()-1000;
    var ed = new Date(ct);
    document.cookie = "restTime=; expires="+ed+";";

	$('.captcha-btn').click(function(){
		if($("#phone").val()==''){
			$("#phone").focus();
			return false;
		}
		var arr = document.cookie.match(new RegExp("(^| )restTime=([^;]*)(;|$)"));
		if(!(arr != null && arr[2]!='')){
			sendPhone();	
		}
	});

	// 提交
	$('.certain-btn').click(function(){
		
		//非空判断
		var rs = judgeEmpty();
		if(rs){
			if(checkPhoneLength()){
				if(judgePhoneHead($("#phone").val())){
					//获取令牌
				$.ajax({
					url: '/api/v1/front/userStillIn',
					type: "GET",
					success: function(data){
						if(data['status']['success']){
							var user_login = data['data']['user_login'];
							var access_token = data['data']['access_token'];
							//非空  传递
				    		var postArr = new Array();
				    		postArr['loginPwd'] = $('#loginPwd').val();
				    		postArr['phone'] = $('#phone').val();
				    		postArr['captcha'] = $('#captcha').val();
				    		postArr['user_login'] = user_login;
				    		postArr['access_token'] = access_token;
				    		sendAjax(postArr, '/api/v1/user/editBindPhone', 'false', 'editBindPhoneOk');
						} else {
							//跳转登录页  携带地址
							window.location.href = '/login?url=/edit-bind-phone';
						}

					}
				});
				}
			}
		}

	});

});