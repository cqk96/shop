//删除地址
function deletetShippingAddressOk(data)
{
	//console.log(data);
	if(data['status']['success']){
		//成功
		$('.each-address-box').eq(index).remove();
	} else {
		alert("删除地址失败");
	}
}

//修改默认地址
function defaultShippingAddressOk(data)
{
	
	if(!data['status']['success']){
		//失败
		switch(data['status']['code']){
			case "005":
			alert("修改失败");
			break;
			case "047":
			break;
			default:
			//成功  跳转重新登录
			$.ajax({
				url: "/api/v1/front/userOut",
				type: "get",
			});
			setTimeout(function(){
				//用户登出
				window.location.href = '/login?url=/change-address';
			},1000);
			
			break;
		}
	}

}

$(document).ready(function(){
    $("input").iCheck({
    	checkboxClass: 'icheckbox_square-green',
    	radioClass: 'iradio_square-green',
    	increaseArea: '20%' // optional
  	});

  	//删除
  	$('.delete-box').click(function(){
	  	var rs = confirm("确定要删除嘛?");
	  	if(rs){
	  		//全局存储删除变量
	  	    index = $('.delete-box').index(this);
	  	    var radioIndex = $("input[type='radio']").index($("input[type='radio']:checked"));

	  	    //非删除默认地址  可以删除
	  	    if(index!=radioIndex){
	  	    	//@todo  逻辑删除

	  	    	//获取令牌
	  	    	$.ajax({
	  	    		url: '/api/v1/front/userStillIn',
					type: "GET",
					success: function(data){
						if(data['status']['success']){
							var postArr = new Array();
							postArr['id'] = $('.delete-box').eq(index).prev().prev().prev().val();
							postArr['user_login'] = data['data']['user_login'];
							postArr['access_token'] = data['data']['access_token'];
							
							sendAjax(postArr, '/api/v1/user/deletetShippingAddress', 'false', 'deletetShippingAddressOk')
						} else {
							//成功  跳转重新登录
							$.ajax({
								url: "/api/v1/front/userOut",
								type: "get",
							});
							setTimeout(function(){
								//跳转登录页  携带地址
							window.location.href = '/login?url=/change-address';
							},1000);
						}
					}
	  	    	});
	  	    }

	  	}
  	});

	$('.iCheck-helper').click(function(){
		//更换默认地址
		var id = $(this).parent().prev().val();
		//获取令牌
		$.ajax({
			url: '/api/v1/front/userStillIn',
			type: "GET",
			success: function(data){
				if(data['status']['success']){
					var user_login = data['data']['user_login'];
					var access_token = data['data']['access_token'];
					var postArr = new Array();

					postArr['id'] = id;
					postArr['user_login'] = user_login;
					postArr['access_token'] = access_token;

					sendAjax(postArr, '/api/v1/user/defaultShippingAddress', 'false', 'defaultShippingAddressOk')
				} else {
					//成功  跳转重新登录
					$.ajax({
						url: "/api/v1/front/userOut",
						type: "get",
					});
					setTimeout(function(){
						//跳转登录页  携带地址
					window.location.href = '/login?url=/change-address';
					},1000);
					
				}
			}
		});

	});

});