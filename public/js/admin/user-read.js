
// 修改结果
function updateUserInfoOk(data)
{
	// if(data.code=="002"){
	// 	window.location.href = '/admin';
	// }
}

//修改用户信息
function changeItem(name,value){
	var userObject = new Array();
	userObject['name'] = name;
	userObject['value'] = value;
	
	//传入数组 userAvatar
	sendAjax(userObject,'/admin/user/updateUserInfo', false, 'updateUserInfoOk');

}

//显示修改头像
// function showChangeImg(data)
// {
// 	var file = $("#updateAvatar").prop('files');
// 	var reader = new FileReader(); 
//     reader.readAsDataURL(file);
    
//     reader.onload = function(e){

// 		$("#userAvatar").attr('src',this.result);

//     }

// }

function readFile()
{
	
	var file = $("#updateAvatar")[0].files[0];
	
	var fileFragArray = file['name'].split('.');
    var result = document.getElementById('result');
    var allowSuffixArray = new Array('jpg','jpeg');
    var allowOk = false;
    var position = '';

    for (var i = 0; i <allowSuffixArray.length; i++) {
    	if(fileFragArray[fileFragArray.length-1]==allowSuffixArray[i]){
    		allowOk = true;
    		position = i;
    	}
    }

    if(allowOk){
    	var reader = new FileReader(); 
    	
	    reader.readAsDataURL(file);
	    
	    reader.onload = function(e){
	    	//console.log(this.result);

	  //   	var imageObject = new Array();
			// imageObject['imageStr'] = this.result;
			// imageObject['type'] = file['type'];
			
			//传入数组 userAvatar
			//sendAjax(imageObject,'/admin/user/updateUserAvatar','showChangeImg');

			$("#userAvatar").attr('src',this.result);

	    }

    }else {

    	alert("请上传图片格式的文件");

    }

}

function updateUserAvatarOk(data)
{
	if(data['success']){
		// return false;
		window.location.reload();
	}
}

$(document).ready(function(){

	//点击修改文本
	$('.edit-span').click(function(){
		var index = $(this).attr("data-index");
		$(this).prev().addClass("form-control");
		// $('.editItem').eq(index-1).addClass("form-control");
		// $('.editItem').eq(index-1).focus();
	});

	//失去焦点
	$("input").blur(function(){
		$(this).removeClass("form-control");
	});
	$("textarea").blur(function(){
		$(this).removeClass("form-control");
	});

	//换头像
	$("#userAvatar").click(function(){
		$("#updateAvatar").click();
	});
	
	var currentGender = $("#gender").val();
	for (var i = 1; i <=3; i++) {
		if(i==currentGender)
			$(".gender"+i).click();
	}

	// $("input.editItem").blur(function(){
	// 	var name = $(this).prop('name');
	// 	var value = $(this).val();
	// 	//changeItem(name,value);
	// });

	// $("textarea.editItem").blur(function(){
	// 	var name = $(this).prop('name');
	// 	var value = $(this).val();
	// 	//changeItem(name,value);
	// });

	// $("select.editItem").change(function(){
	// 	var name = $(this).prop('name');
	// 	var value = $(this).val();
	// 	//changeItem(name,value);
	// });

	// $("input[type='radio']").click(function(){
		
	// 	var name = $(this).prop('name');
	// 	var value = $(this).val();
	// 	//changeItem(name,value);

	// });

	//测试是否能获取iframe外元素
	//console.log(parent.document.getElementById("adminAvatar").alt);

	//保存与修改
	$('.saveBtn').click(function(){
		$('.editItem').each(function(){
			var name = $(this).prop('name');
			var value = $(this).val();
			changeItem(name,value);
		});

		// 修改性别
		var name = $("input[name='gender']:checked").prop('name');
		var value = $("input[name='gender']:checked").val();
		changeItem(name,value);

		//修改头像
		var imageObject = new Array();
		var format = /data/;
		imageObject['imageStr'] = $("#userAvatar").prop("src");
		if(format.test(imageObject['imageStr'])){
			// console.log( imageObject['imageStr'] );
			imageObject['type'] = "image/jpeg";
			
			sendAjax(imageObject,'/admin/user/updateUserAvatar',false, "updateUserAvatarOk");	
		} else {
			setTimeout(function(){
				window.location.reload();
			},800);
		}
		
	});

});
