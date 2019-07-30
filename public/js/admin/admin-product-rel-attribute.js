//上传结果
function uploadResult()
{
	
	if(requestObj.readyState == 4) {
		// 成功响应
		if(requestObj.status==200){
			var result = JSON.parse(requestObj.responseText);
			if(result['status']['success']){
				// 从中获取索引
				var index = result['data'][0]['index'];
				if(result['data'][0]['url']){
					// 设置图片地址
					$('.showUploadResult').eq(index).parent().find("img").attr("src", result['data'][0]['url']);

					//修改名字
					$('.picImgName').eq(index).val(result['data'][0]['url']);

					$('.showUploadResult').eq(index).html("上传成功");
				} else {
					$('.showUploadResult').eq(index).html("上传失败");
				}
				
			} else {
				// 上传失败
				// 从中获取索引
				//$('.showUploadResult').eq(imgIndex).html("上传失败");
			}
		}
	}

}

//异步上传文件
function changeImage(obj)
{

	if(!window.FormData){
		alert("您当前浏览器不支持异步上传方式，请用更高版本或更换浏览器");
		return false;
	}

	// 索引
	var imgIndex = $(".images-box input[type='file']").index(obj);

	//获取属性对象
	var file = obj.files[0];
	
	var formData = new FormData();
	formData.append("file", file);

	// 构造请求
	requestObj = new XMLHttpRequest();

	// 构造地址
	requestObj.open("post", "/api/v1/tools/uploadImage?index="+imgIndex, true);

	// 上传process
	// requestObj.upload.onprogress = function(e){
	// 	if(e.lengthComputable){
	// 		var progress = (e.loaded/e.total * 100 | 0)+"%";
	// 		$("#progressBar").css("width", progress);
	// 	}
	// }

	// 上传完成后调用
	requestObj.onreadystatechange = uploadResult;

	// 显示为上传中
	$('.showUploadResult').eq(imgIndex).html("<i class='fa fa-spinner fa-spin m-right-xs'></i>正在上传");

	// 发送
	requestObj.send(formData);

}

// 去除节点
function removeImgNode(obj)
{
	
	var rs = confirm("确定删除组图嘛?");
	if(rs){
		$(obj).parent().remove();
	}
}

$(document).ready(function(){
	
	//增加组图
	$('.addImgBtn').click(function(){
		var str = "<div><input type='hidden' class='picImgName' name='picImgName[]' /><div class='each-images-box'><img class='' src=''></div>"+
        "<input type='file' name='' onchange='changeImage(this)' /> <span class='removeImgBtn' onclick='removeImgNode(this)'>&times;</span><span class='showUploadResult'></span></div>";
        $('.images-box').append(str);
	});

});