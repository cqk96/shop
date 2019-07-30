
//空值检测
function checkEmpty()
{
	if($("#phone").val()==""){
		$("#phone").focus();
		return false;
	} else if($("#password").val()==''){
		$("#password").focus();
		return false;
	}
	return true;
}

$(document).ready(function(){

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

	// 登录
	$('.login-btn').click(function(){
		//空值检测
		if(checkEmpty()){
			var postArr = new Array();
			postArr['phone'] = $("#phone").val();
			postArr['password'] = $("#password").val();
			$.ajax({
				url: "/api/v1/user/passwordEncrypt?password="+postArr['password'],
				type: "GET",
				async: "FALSE",
				success: function(data){
					var password = data['data'];
					$.ajax({
						url: '/api/v1/user/loginVerify?phone='+postArr['phone']+"&password="+password,
						type: "GET",
						async: "FALSE",
						success: function(data){
							// console.log(data);
							if(data['status']['code']=='042'){
								//账号有问题
								$('.remind-tag-box').eq(0).text("!");
								$('.remind-title-box').eq(0).text("您还未注册哦");
								$('.remind-box').eq(0).removeClass('nosee');
								$('.remind-box').eq(1).addClass('nosee');
							} else if(data['status']['code']=='046'){
								//密码有问题
								$('.remind-box').eq(0).addClass('nosee');
								$('.remind-box').eq(1).removeClass('nosee');
							} else {
								//console.log(data);
								//成功登录
								$('.remind-box').addClass('nosee');
								//将用户设置为登录状态存储于cookie中
								var setUserIn = '/api/v1/user/setUserIn?user_login='+data['data']['user_login']+"&access_token="+data['data']['access_token'];

								$.ajax({
									url: setUserIn,
									type: "GET",
									async: "FALSE",
									dataType: "JSON",
									success: function(data){
										if(data['status']['success']){
											var url = $("#url").val();
											if(url==''){
												url = '/mine';
											}

											$('.transparent-box').removeClass("nosee");
											$('.msg-box').removeClass("visibility");

											//跳转
											setTimeout(function(){
												window.location.href = url;
											},1500);
										} else {
											alert("登录失败，请重新尝试");
											window.reload();
										}
									}
								});

							}

						}
					});
				}
			});
		}
	});

});