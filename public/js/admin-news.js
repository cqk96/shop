
function showDeleteItems(data)
{
	if(data.code!='001'){
		alert("删除失败");
	} else {
		alert("删除成功");
		window.location.href = '/admin/news';
	}
}

function showTopItems(data)
{
	if(data.code!='001'){
		$('.showResultText').text('置顶失败');
	} else {
		$('.showResultText').text('置顶成功');
		setTimeout(function(){
			window.location.href = '/admin/news';
		},600);
	}
}

function showDeleteCover(data)
{
	
	if(data.code!='001'){
		alert("删除封面失败");
	} else {
		//alert("删除封面成功");
		var newsId = $("input[name='id']").val();
		window.location.href = '/admin/news/update?id='+newsId;
	}
	
}

function clearNewsCover()
{
	var is_wish = confirm('您确实想清除该篇文章的封面吗？');
	if(is_wish){
		var postParam = new Array();
		var newsId = $("input[name='id']").val();
		postParam['id'] = newsId;
		sendAjax(postParam,'/admin/news/deleteNewsCover','','showDeleteCover');
	}
}

$(document).ready(function(){

	/*通用*/
	//按钮修改封面
	$('.coverBtn').click(function(){
		$('.coverInput').click();
	});

	/*首页js*/

	//全选checkbox
	var allCheckBoxBtnClick = 1;

	$('.allCheckBox').click(function(){
		
		if(allCheckBoxBtnClick%2!=0) {
			$('.eachNewsClassCheckBox').each(function(){
				var isDisabled = $(this).prop("disabled");
				if( !isDisabled ) {
					$(this).prop('checked',true);
				}
			});
		} else {
			$('.eachNewsClassCheckBox').each(function(){
				var isDisabled = $(this).prop("disabled");
				if( !isDisabled ) {
					$(this).prop('checked',false);
				}
			});
		}

		allCheckBoxBtnClick++;

	});
	
	//删除选择的项目
	$('.deleteChooseBtn').click(function(){
		deleteChooseItems('eachNewsClassCheckBox','你确定要删除选定项吗？','/admin/news/delete','','showDeleteItems','');
	});
	/*首页js end*/

	/*文章置顶*/
	$('.topBtn').click(function(){
		doSomeWithCheckBoxItems('eachNewsClassCheckBox','你确定要指定选定项吗？','/admin/news/doTop','','showTopItems');
	});
	/*文章置顶 end*/ 

});