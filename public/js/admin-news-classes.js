
function showDeleteItems(data)
{
	if(data.code!='001'){
		alert("删除失败");
	} else {
		alert("删除成功");
		window.location.href = '/admin/newsClasses';
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

	//展开
	$('.zoomIcon').click(function(){

		//判断是展开还是收缩
		var zoomInconCurrentClass = $(this).prop('class');
		var zoomExpandingFormat = /glyphicon-plus/;

		//判断下面是否直接是一级栏目
		var classNameFormat = /hasChild/;
		var nextClassName = $(this).parents('tr').next().prop('class');
		var child_exist = classNameFormat.test(nextClassName);

		//展开
		if(zoomExpandingFormat.test(zoomInconCurrentClass)){
			$(this).removeClass('glyphicon-plus');
			$(this).addClass('glyphicon-minus');

			if(child_exist){
				//有子节点

				$(this).parents('tr').nextAll().each(function(){
					var eachClassName = $(this).prop('class');

					if(eachClassName==''){
						return false;
					} else {
						$(this).removeClass('nosee');
					}
				});
			}
		} else {
			//收缩
			$(this).addClass('glyphicon-plus');
			$(this).removeClass('glyphicon-minus');

			if(child_exist){
				//有子节点

				$(this).parents('tr').nextAll().each(function(){
					var eachClassName = $(this).prop('class');

					if(eachClassName==''){
						return false;
					} else {
						$(this).addClass('nosee');
					}
				});
			}
		}

	});

	//选择项目的时候判断是否有子项目如果有就将项目全部选中
	$('.eachNewsClassCheckBox').click(function(){

		//判断下面是否直接是一级栏目
		var classNameFormat = /hasChild/;
		var nextClassName = $(this).parents('tr').next().prop('class');
		var child_exist = classNameFormat.test(nextClassName);

		if(child_exist){
			//有子节点  && 判断当前状态是什么
			var checked_status = $(this).prop('checked');
			
			$(this).parents('tr').nextAll().each(function(){
				var eachClassName = $(this).prop('class');
				
				if(eachClassName==''){
					return false;
				} else {
					
					$(this).find("input[name='classesIds[]']").prop('checked',checked_status);

				}

			});

		}

	});
	
	//删除选择的项目
	$('.deleteChooseBtn').click(function(){
		deleteChooseItems('eachNewsClassCheckBox','你确定要删除选定项吗？','/admin/newsClass/delete2','','showDeleteItems','');
	});
	/*首页js end*/

});