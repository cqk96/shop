//回复成功
function replyOk(data)
{
	if(!data['success']){
		alert(data['message']);
		return false;
	}
	window.location.reload();

}

//结束工单成功
function closedOk(data)
{
	if(!data['success']){
		alert(data['message']);
		return false;
	}
	window.location.reload();
}

//转入聊天
function turnToTalkOk(data)
{
	
	if(!data['success']){
		alert(data['message']);
		return false;
	}
	window.location.href = '/admin/huanXin/chat';

}

//删除节点
function moveNode(obj)
{
	var rs = confirm("确定要删除吗?");
	if(rs){
		$(obj).parent().remove();
		countOwnBoxSize();
	}
}

//计算宽度
function countOwnBoxSize()
{
	var count = $('.own-box').length;
	if(count/3==0){
		var mutiplier = count/3;
	} else {
		var mutiplier1 = count/3;
		var mutiplier = mutiplier1+1;
	}
	var finalHeight = 35*mutiplier+45;
	$('.currentOwnBox').height(finalHeight+"px");
	$(".upload-resource-span").html('');
}

$(document).ready(function(){

	var currentValue = $('.consultative').val();
	if(currentValue==12){
		$('.relate-consultative').removeClass('nosee');
	}

	$('.consultative').change(function(){
		var value = $(this).val();
		if(value==12){
			$('.relate-consultative').removeClass('nosee');
		} else {
			$('.relate-consultative').addClass('nosee');
			$('.controversy option').eq(0).prop('selected', true);
		}

	});

	//点此回复
	$('.relateQuestionBtn').click(function(){

		var id = $(this).next().val();
		var type = $(this).next().next().val();

		$("#itemId").val(id);
		$("#itemType").val(type);

		$("#replyContent").focus();

	});

	//上传附件
	$("#attachment").change(function(){
		
		$(".upload-resource-span").html('');
		var btnSpan = $(".upload-resource-span");
		$("#attachment").unwrap();
		$("#attachment").wrap("<form id='uploadResource' action='/admin/workOrder/uploadResource' method='post' enctype='multipart/form-data'></form>");
        $("#uploadResource").ajaxSubmit({

            dataType:  'json', //数据格式为json

            beforeSend: function() { //开始上传
                btnSpan.html("上传中..."); //上传按钮显示上传中
            },
            success: function(data) { //成功
                //获得后台返回的json数据，显示文件名，大小，以及删除按钮
                if(data['success']){
                	btnSpan.html("上传成功");
                } else {
                	btnSpan.html(data['message']);
                }
                //$('#attachmentUrl').val(data['data']['url']);
                //$('#originName').val(data['data']['origin_name']);
				//增加节点
				var inputNode = "<div class='own-box' style='float:left'><input class='form-control' readonly type='text' style='width:140px;display: inline-block;' value='"+data['data']['origin_name']+"'><input type='hidden' value='"+data['data']['url']+"'><span class='removeNodeSpan' onclick='moveNode(this)' style='margin-left: 20px;font-size:20px'>X</span></div>";
				$('.own-attachments').append(inputNode);
				countOwnBoxSize();
				$(".form-wrap").unwrap();
            },
            error:function(xhr){ //上传失败
                //console.log(xhr.responseText); //返回失败信息
            },
            clearForm: true
        });
	});

	$('.replyPostBtn').click(function(){
		var postArr = new Array();
		var itemId = $("#itemId").val();
		var itemType = $("#itemType").val();
		//var attachmentUrl = $("#attachmentUrl").val();
		//var originName = $("#originName").val();
		var content = $("#replyContent").val();

		if(content==''){
			alert("回复不为空");
			return false;
		}
		
		//重新获取
		var resourceArr = new Array();
		var nameArr = new Array();
		$('.own-box').each(function(){
			resourceArr.push($(this).find("input").eq(1).val());
			nameArr.push($(this).find("input").eq(0).val());
		});
		
		//不空判断
		var attachmentUrl = '';
		var originName = '';
		if(resourceArr.length!=0 && nameArr.length!=0){
			attachmentUrl = resourceArr.join("|");
			originName = nameArr.join("|");
		}
		postArr['itemId'] = itemId;
		postArr['itemType'] = itemType;
		postArr['attachmentUrl'] = attachmentUrl;
		postArr['content'] = content;
		postArr['originName'] = originName;

		sendAjax(postArr,'/admin/workOrder/reply','false','replyOk');

	});

	$('.closeWorkOrderBtn').click(function(){

		var rs = confirm("您确定要结束此工单吗?");
		if(rs){
			var postArr = new Array();
			postArr['id'] = $("#workOrderId").val();
			sendAjax(postArr,'/admin/workOrder/close','false','closedOk');
		}

	});

	//转入工单
	$('.turnToTalkBtn').click(function(){
		var rs = confirm("您确定要转入聊天吗");
		if(rs){
			var postArr = new Array();
			postArr['id'] = $("#workOrderId").val();
			sendAjax(postArr,'/admin/workOrder/turnToTalk','false','turnToTalkOk');
		}
	});

});