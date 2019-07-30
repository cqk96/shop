<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
	th {
		width: 100px;
	}
	.nosee {
        display: none !important;
    }
</style>
<div class="smart-widget-inner table-responsive">
<div class="smart-widget-inner">
<form id="myForm" method='POST' action="/admin/news/doUpdate" enctype='multipart/form-data'>
<ul class="list-group to-do-list sortable-list no-border">
    <?php include_once '_form.php'; ?>
</ul>
</form>
</div>
</div>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type='text/javascript' src='/js/myFuncs.js'></script>
<script type='text/javascript' src='/js/admin-news.js'></script>
<script>
        
	 function stopSubmit(){

	 	if(_userAgent.indexOf("MSIE")>0 && parseInt(_userAgent.match(/.*?MSIE\s(.*?);/)[1])<=9){
			$("#myForm input").each(function(){
				
				var curPlaceHolder = $.trim($(this).attr("placeholder"));
				var curValue = $.trim($(this).val());

				if( curValue==curPlaceHolder ) {
					$(this).val("");
				}

			});
			$("#myForm textarea").each(function(){
				
				var curPlaceHolder = $.trim($(this).attr("placeholder"));
				var curValue = $.trim($(this).val());

				if( curValue==curPlaceHolder ) {
					$(this).val("");
				}

			});
		}
		
		document.getElementById('stopsubmit').disabled=true;
		$("#myForm").submit();
		return false;
		//alert('添加成功！'); 
		
	} 

/*显示placeholder*/
function showPlaceholder()
{

	if(_userAgent.indexOf("MSIE")>0 && parseInt(_userAgent.match(/.*?MSIE\s(.*?);/)[1])<=9){

		$("input[type='text']").each(function(){
			var curPlaceHolder = $(this).attr("placeholder");
			var curValue = $.trim($(this).val());
			if( curValue=="" ) {
				$(this).val(curPlaceHolder);
			}
			
		});

		$("input[type='text']").focus(function(){
			
			var curPlaceHolder = $.trim($(this).attr("placeholder"));
			var curValue = $.trim($(this).val());

			if( curPlaceHolder==curValue ) {
				$(this).val("");
			}

		});

		$("input[type='text']").blur(function(){
			
			var curPlaceHolder = $.trim($(this).attr("placeholder"));
			var curValue = $.trim($(this).val());

			if( curValue=="" ) {
				$(this).val(curPlaceHolder);
			}

		});

		/*textarea*/
		$("textarea").each(function(){
			var curPlaceHolder = $(this).attr("placeholder");
			var curValue = $.trim($(this).val());
			if( curValue=="" ) {
				$(this).val(curPlaceHolder);
			}
			
		});

		$("textarea").focus(function(){
			
			var curPlaceHolder = $.trim($(this).attr("placeholder"));
			var curValue = $.trim($(this).val());

			if( curPlaceHolder==curValue ) {
				$(this).val("");
			}

		});

		$("textarea").blur(function(){
			
			var curPlaceHolder = $.trim($(this).attr("placeholder"));
			var curValue = $.trim($(this).val());

			if( curValue=="" ) {
				$(this).val(curPlaceHolder);
			}

		}); 

	}

}

$(document).ready(function(){

	_userAgent = navigator.userAgent;

	if(_userAgent.indexOf("MSIE")>0 && parseInt(_userAgent.match(/.*?MSIE\s(.*?);/)[1])<=9){
		showPlaceholder();
	}

});    

</script>