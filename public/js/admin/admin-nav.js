//改变生成菜单的微信id
function changeMid()
{

	var index_current_message_id = $('#message_id').val();
	var index_current_a_href = $("#navATag").attr("href");
	var index_id_format = /mid/;
	var mid_format = /[&|\?]mid=(.*)?/;
	var change_a_href = '';

	if(index_id_format.test(index_current_a_href)){
		//此时有mid 进行末尾替换
		change_a_href = index_current_a_href.replace(mid_format,'?mid='+index_current_message_id);
	} else {
		change_a_href = index_current_a_href+"?mid="+index_current_message_id;
	}

	$("#navATag").prop('href',change_a_href);

}

$(document).ready(function(){

	//首页选择微信号进行推送
	if(document.getElementById('indexTable')){
		changeMid();
	}

	//首页改变
	$("#message_id").change(function(){
		changeMid();
	});

});