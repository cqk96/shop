/**
* need module jquery
*/ 
$(document).ready(function(){

	// 上方input全选
	if(typeof(allCheckBoxBtnClick)=="undefined"){
		allCheckBoxBtnClick = 1;
	}

	$('.allCheckBox').click(function(){
		if(allCheckBoxBtnClick%2!=0) {
			$('.eachNewsClassCheckBox').each(function(){
				$(this).prop('checked',true);
			});
			$('.eachItemCheckBox').each(function(){
				$(this).prop('checked',true);
			});
			$('.allCheckBox').prop('checked',true);
		} else {
			$('.eachNewsClassCheckBox').each(function(){
				$(this).prop('checked',false);
			});
			$('.eachItemCheckBox').each(function(){
				$(this).prop('checked',false);
			});
			$('.allCheckBox').prop('checked',false);
		}

		allCheckBoxBtnClick++;

	});

});