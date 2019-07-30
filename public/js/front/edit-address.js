
//更新收货地址结果
function updateShippingAddressOk(data)
{

	if(data['status']['success']){
		if(document.getElementById('from')){
			
			var fromValue = $('#from').val();
			if(fromValue==1){
				window.location.href = "/buy-right-now?id="+$('#pid').val();
				return false;
			} else if(fromValue==2){
				window.location.href = "/shop-cart";
				return false;
			}
		}
		window.location.href = '/change-address';
	} else {
		if(data['status']['code']=='003'){
			alert("修改失败");
		} else {
			//成功  跳转重新登录
			$.ajax({
				url: "/api/v1/front/userOut",
				type: "get",
			});
			setTimeout(function(){
				//重新登录
			window.location.href = '/login?url=/change-address';
			},1000);
			
		}
	}

}

function getLocation(){   
    if (navigator.geolocation){   
        navigator.geolocation.getCurrentPosition(showPosition,showError);   
    }else{   
        alert("浏览器不支持地理定位。");   
    }   
}

function showError(error)
  {
  switch(error.code)
    {
    case error.PERMISSION_DENIED:
      //x.innerHTML="User denied the request for Geolocation."
      break;
    case error.POSITION_UNAVAILABLE:
      //x.innerHTML="Location information is unavailable."
      alert("Location information is unavailable");
      break;
    case error.TIMEOUT:
      // x.innerHTML="The request to get user location timed out."
      alert("The request to get user location timed out");
      break;
    case error.UNKNOWN_ERROR:
      //x.innerHTML="An unknown error occurred.";
      alert("An unknown error occurred");
      break;
    }
  }

function showPosition(position)
{
	// console.log("Latitude: " + position.coords.latitude +
	// "Longitude: " + position.coords.longitude);
	var lat = position.coords.latitude;
	var lon = position.coords.longitude;
	var url = "http://api.map.baidu.com/geocoder/v2/?callback=renderReverse&location="+lat+lon+","+"&output=json&pois=1&ak=duLlWvbV3E9c1oZoO0IFb6PC";
	var point = new BMap.Point(lon,lat);
	var geoc = new BMap.Geocoder();  
	geoc.getLocation(point, function(rs){
		var addComp = rs.addressComponents;
		changeAreaValue(addComp.province,addComp.city,addComp.district);
		// addComp.province = addComp.province.replace(/省/,'');
		// addComp.city = addComp.city.replace(/市/,'');
		// addComp.district = addComp.district.replace(/区/,'');
		// var format = /[重庆|北京|上海|天津]/;
		// if(format.test(addComp.province)){
		// 	$('.address-content').eq(0).addClass('nosee');
		// 	$('.address-tag').eq(0).addClass('nosee');
		// } else {
		// 	$('.address-content').eq(0).removeClass('nosee');
		// 	$('.address-tag').eq(0).removeClass('nosee');
		// 	$('.address-content').eq(0).text(addComp.province);
		// }
		
		// $('.address-content').eq(1).text(addComp.city);
		// $('.address-content').eq(2).text(addComp.district);
		$('.choose-title').text(addComp.province+" "+addComp.city+" "+addComp.district);
		$("#real-address").val(addComp.province+","+addComp.city+","+addComp.district);
		$('.edit-concret-address-box input').val(addComp.street+addComp.streetNumber);
	});
}

// 改变显示文本
function changeShowAddressText()
{
	
	var addrStr = $('#real-address').val();
	var addrArr = addrStr.split(",");
	var startIndex = 0;
	if(addrArr.length==2){
		//直辖
		var startIndex = 1;
		$('.address-content').eq(0).addClass("nosee");
		$('.address-tag').eq(0).addClass("nosee");
	} else {
		$('.address-content').eq(0).removeClass("nosee");
		$('.address-tag').eq(0).removeClass("nosee");
	}
	for (var i = 0; i <addrArr.length; i++) {
		addrArr[i] = addrArr[i].replace(/省/,'');
		addrArr[i] = addrArr[i].replace(/市/,'');
		addrArr[i] = addrArr[i].replace(/区/,'');
		$('.address-content').eq(startIndex+i).html(addrArr[i]);
	}
}

//改变插件数据值
function changeAreaValue(province,city,district)
{
	
	var format = /[重庆|北京|上海|天津]/;
	if(format.test(province)){
		city = district;
		district = '';
	}

	var ids = getAreaValue(province,city,district);
	var idsStr = ids.join(',');
	$("#real-address-ids").val(idsStr);
}

//获取area地址 id数组
function getAreaValue(province,city,district)
{
	
	var arr = area1.data;
	var ids = new Array();
	for(var i=0; i<arr.length; i++){
		if(arr[i]['name']==province){
			ids.push(parseInt(arr[i]['id']));
			for(var j=0; j<arr[i]['child'].length; j++){
				if(arr[i]['child'][j]['name']==city){
					ids.push(parseInt(arr[i]['child'][j]['id']));
					if(typeof(arr[i]['child'][j]['child'])!='undefined'){
						for(var k=0; k<arr[i]['child'][j]['child'].length; k++){
							if(arr[i]['child'][j]['child'][k]['name']==district){
								ids.push(parseInt(arr[i]['child'][j]['child'][k]['id']));
							}
						}
					}
				}
			}
		}
	}

	return ids;

}

//检测非空
function checkEmpty()
{
	if($("#name").val()==''){
		$("#name").focus();
		return false;
	} else if($("#phone").val()==''){
		$("#phone").focus();
		return false;
	} else if($("#real-address").val()==''){
		$('.show-address-box').click();
		return false;
	} else if($("#concretAddress").val()==''){
		$("#concretAddress").focus();
		return false;
	}

	return true;

}

//为了初始化  获取索引数组
function getIndexArea(province,city,district)
{
	
	var arr = area1.data;
	var indexs = new Array();
	for(var i=0; i<arr.length; i++){
		if(arr[i]['name']==province){
			indexs.push(parseInt(i));
			for(var j=0; j<arr[i]['child'].length; j++){
				if(arr[i]['child'][j]['name']==city){
					indexs.push(parseInt(j));
					if(typeof(arr[i]['child'][j]['child'])!='undefined'){
						for(var k=0; k<arr[i]['child'][j]['child'].length; k++){
							if(arr[i]['child'][j]['child'][k]['name'].trim()==district){
								indexs.push(parseInt(k));
							}
						}
					}
				}
			}
		}
	}

	return indexs;
}

// 改变显示文本
function changeShowAddressText()
{
	
	var addrStr = $('#real-address').val();
	var addrArr = addrStr.split(",");
	var startIndex = 0;
	// if(addrArr.length==2){
	// 	//直辖
	// 	var startIndex = 1;
	// 	$('.address-content').eq(0).addClass("nosee");
	// 	$('.address-tag').eq(0).addClass("nosee");
	// } else {
	// 	$('.address-content').eq(0).removeClass("nosee");
	// 	$('.address-tag').eq(0).removeClass("nosee");
	// }
	// for (var i = 0; i <addrArr.length; i++) {
	// 	addrArr[i] = addrArr[i].replace(/省/,'');
	// 	addrArr[i] = addrArr[i].replace(/市/,'');
	// 	addrArr[i] = addrArr[i].replace(/区/,'');
	// 	$('.address-content').eq(startIndex+i).html(addrArr[i]);
	// }
	$('.choose-title').text(addrArr.join(" "));
}

//检测手机号长度与抬头
function checkPhone()
{
	
	var phone = $("#phone").val();

	if(phone.length!=11){
		//showError('手机号长度不对');
		$("#phone").focus();
		//alert('手机号长度不对');
		return false;
	}

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

function changeInputSize()
{
	$("input").each(function(){
		$(this).attr('size', $(this).val().length);
	});
}
$(document).ready(function(){
	changeInputSize();

	area1 = new LArea();
    area1.init({
        'trigger': '.show-address-box', //触发选择控件的文本框，同时选择完毕后name属性输出到该位置
        'valueTo': '#real-address-ids', //选择完毕后id属性输出到该位置
        'keys': {
            id: 'id',
            name: 'name'
        }, //绑定数据源相关字段 id对应valueTo的value属性输出 name对应trigger的value属性输出
        'type': 1, //数据源类型
        'data': LAreaData,//数据源,
        'save_name': '#real-address',//存储name到某个位置
        'trigger_function': 'changeShowAddressText'
    });

    //获取当前地址
    var addrStr = $("#real-address").val();

    var addrArr = addrStr.split(",");

    var district = '';
    if((typeof addrArr[2])!='undefined'){
    	district = addrArr[2];
    }

    //获取地址value
    var rs = getIndexArea(addrArr[0],addrArr[1],district);

    // console.log(rs);

    area1.value = rs;////控制初始位置，注意：该方法并不会影响到input的value

    $('.location-tag').click(function(){
	   	getLocation();
    });

    //保存按钮
    $('.save-box').click(function(){
    	if(checkEmpty()){
    		if(checkPhone()){
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
				    		var addressArr = $("#real-address").val().trim().split(",");
				    		var province = addressArr[0];
				    		var city = addressArr[1];
				    		var other = '';
				    		if((typeof addressArr[2])!='undefined'){
				    			other = addressArr[2];
				    		}

				    		postArr['name'] = $('#name').val();
				    		postArr['phone'] = $('#phone').val();
				    		postArr['province'] = province;
				    		postArr['city'] = city;
				    		postArr['other'] = other;
				    		postArr['address'] = $('#concretAddress').val();
				    		postArr['user_login'] = user_login;
				    		postArr['access_token'] = access_token;
				    		postArr['id'] = $('#id').val();
				    		
				    		sendAjax(postArr, '/api/v1/user/updateShippingAddress', 'false', 'updateShippingAddressOk');
						} else {
							//成功  跳转重新登录
							$.ajax({
								url: "/api/v1/front/userOut",
								type: "get",
							});
							setTimeout(function(){
								//跳转登录页  携带地址
							window.location.href = '/login?url=/create-address';
							},1000);
							

						}

					}
				});
    		} else {
    			alert('手机号不正确');
    		}
    	}
    });

});