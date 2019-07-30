$(document).ready(function(){
	$('#goForm select').change(function(){
		var page = $(this).val();
		$('#paginationPageInput').val(page);
	});

});