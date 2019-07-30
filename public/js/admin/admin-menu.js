$(document).ready(function(){
	$(".pNav").change(function(){
		var currentValue = $(this).val();
		if(currentValue==0)
			$('.menuBox').css("display", 'block');
		else
			$('.menuBox').css("display", 'none');
	});

	var pNavValue = $(".pNav").val();
	if(pNavValue==0)
		$('.menuBox').css("display", 'block');
	else
		$('.menuBox').css("display", 'none');
});