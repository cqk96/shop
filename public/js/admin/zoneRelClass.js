//获取模板--持续性拓展
function getTemplateOk(data)
{
	
	switch(parseInt(data.data['template'])){
		case 0:
			// 默认模板没有布置方式
			var str = '';
		break;
		case 1:
			//养生模板 有两种方式  一是 一行三个, 二是 一行一个的方式
			var str = '';
			for(var i=1; i<=2; i++){
				var str = str+'<div class="each-content"> <img src="/images/ys'+i+'.jpg">'+
     			'<label>布局'+i+'：</label><input type="radio" name="product_type" value="'+i+'">'+
				'</div>';
			}
			
		break;
	}
	
	$('.content').html(str);
}

$(document).ready(function(){

	/*
	改变分类--获取模板
	由于模板固定写死
	所以操作在前端
	*/ 
	$("#zoneId").change(function(){
		var id = $("#zoneId").val();
		var postArr = new Array();
		postArr['id'] = id;
		sendAjax(postArr, '/admin/zoneClass/getTemplate', 'true', 'getTemplateOk')
	});

});