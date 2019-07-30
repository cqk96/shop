function getTermItem(termId)
{
	var url = "/api/v1/classes/back";
	var formClass = $('#myForm').prop('class');
	var format = new RegExp('edit');
	currentItemId = '';
	var selectStr = '';
	$('.loadingBtn').removeClass('nosee');

	if(format.test(formClass)){
		currentItemId = $('.item_id').val();
	}
	
	var postArr = new Array();
	sendAjax(postArr, url+"?term_id="+termId,  'false','postOk');
	// $.ajax({
	// 	url: ,
	// 	type:"POST",
	// 	dataType:"JSON",
	// 	data: {
	// 		test: 'test'
	// 	},
	// 	success: function(data){
	// 		if(data.length==0){
	// 			alert("当前栏目没有内容");
	// 			//清空栏目
	// 			$('.item_id').html('');
	// 			$('.loadingBtn').addClass('nosee');
	// 		} else {
	// 			var optionStr = '';
	// 			for(var i=0; i<data.length; i++){
	// 				if(currentItemId==data[i].id)
	// 					selectStr = 'selected';
	// 				else
	// 					selectStr = '';

	// 				optionStr = optionStr+
	// 							"<option value='"+data[i].id+"' "+selectStr+">"+
	// 							data[i].post_title+
	// 							"</option>";
	// 			}
	// 			$('.item_id').html(optionStr);
	// 		}
	// 	},
	// 	error: function(e){
	// 		console.log(e.responseText);
	// 	}
	// });

}

//传送完成
function postOk(data)
{

	setTimeout(function(){
		if(data['code']=='006'){
			$('.loadingBtn').addClass('nosee');
			//alert("当前栏目没有内容");
			//清空栏目
			$('.item_id').html('');
			
		} else {

			var optionStr = '';
			for(var i=0; i<data['data'].length; i++){
				if(currentItemId==data['data'][i].id)
					selectStr = 'selected';
				else
					selectStr = '';

				optionStr = optionStr+
							"<option value='"+data['data'][i].id+"' "+selectStr+">"+
							data['data'][i].title+
							"</option>";
			}
			$('.item_id').html(optionStr);
			$('.loadingBtn').addClass('nosee');
		}
	},1000);
}

$(document).ready(function(){

	//初始化item
	var termId = $(".term_name").val();
	getTermItem(termId);

	$('.term_name').change(function(){
		var termId = $(this).val();
		getTermItem(termId);
	});

});