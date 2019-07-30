
function showDeleteItems(data)
{
	
	if(!data.success){
		alert("批量删除失败");
	} else {
		alert("批量删除成功");
		window.location.href = '/admin/servers';
	}
}

$(document).ready(function(){

	/*首页js*/

	//全选checkbox
	var allCheckBoxBtnClick = 1;

	$('.allCheckBox').click(function(){

		if(allCheckBoxBtnClick%2!=0)
			$('.eachNewsClassCheckBox').each(function(){
				$(this).prop('checked',true);
			});
		else
			$('.eachNewsClassCheckBox').each(function(){
				$(this).prop('checked',false);
			});

		allCheckBoxBtnClick++;

	});

	
//删除选择的项目
	$('.deleteChooseBtn').click(function(){
		deleteChooseItems('eachNewsClassCheckBox','你确定要删除选定项吗？','/admin/server/delete','','showDeleteItems','');
	});
	
});