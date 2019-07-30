<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>管理后台</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="hzhanghuan">

<!-- Bootstrap core CSS -->
<link href="/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link href="/css/font-awesome.min.css" rel="stylesheet">

<!-- ionicons -->
<link href="/css/ionicons.min.css" rel="stylesheet">

<!-- Morris -->
<link href="/css/morris.css" rel="stylesheet"/>	

<!-- Datepicker -->
<link href="/css/datepicker.css" rel="stylesheet"/>	

<!-- Animate -->
<link href="/css/animate.min.css" rel="stylesheet">

<!-- Owl Carousel -->
<link href="/css/owl.carousel.min.css" rel="stylesheet">
<link href="/css/owl.theme.default.min.css" rel="stylesheet">

<!-- Simplify -->
<link href="/css/simplify.min.css" rel="stylesheet">

<link href="/css/admin/global.css?1" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="/css/date-picker/jquery-ui.min.css">

<link rel="stylesheet" type="text/css" href="/js/tools/layui/css/layui.css">

<style type="text/css">
.smart-widget-header {font-size: 16px; }
table { color: black; }
</style>
		

</head>
<body>
 <div class='main-container'>
	 <form id='myForm' class="layui-form" method='POST' action='/admin/carouselImgs/doCreate' enctype='multipart/form-data' style='padding:20px !important;'>
		 <div class='smart-widget'>
			 <div class='smart-widget-header'>
				 <?php echo $page_title;?>
			 </div>
			 <?php include_once '_form.php'; ?>
		 </div>
	 </form>
 </div>
 </body>
</html>
<!-- Placed at the end of the document so the pages load faster -->

<!-- Jquery -->
<script src="/js/jquery-1.11.1.min.js"></script>

<!-- Bootstrap -->
<script src="/js/bootstrap.min.js"></script>

<!-- Flot -->
<!-- <script src='/js/jquery.flot.min.js'></script> -->

<!-- Slimscroll -->
<script src='/js/jquery.slimscroll.min.js'></script>

<!-- Morris -->
<script src='/js/rapheal.min.js'></script>	
<script src='/js/morris.min.js'></script>	

<!-- Datepicker -->
<script src='/js/uncompressed/datepicker.js'></script>

<!-- Sparkline -->
<script src='/js/sparkline.min.js'></script>

<!-- Skycons -->
<script src='/js/uncompressed/skycons.js'></script>

<!-- Popup Overlay -->
<script src='/js/jquery.popupoverlay.min.js'></script>

<!-- Easy Pie Chart -->
<script src='/js/jquery.easypiechart.min.js'></script>

<!-- Sortable -->
<script src='/js/uncompressed/jquery.sortable.js'></script>

<!-- Owl Carousel -->
<script src='/js/owl.carousel.min.js'></script>

<!-- Modernizr -->
<script src='/js/modernizr.min.js'></script>

<!-- Simplify -->
<script src="/js/simplify/simplify.js"></script>
<!-- <script src="/js/simplify/simplify_dashboard.js"></script>-->

<!-- 分页 -->
<script type='text/javascript' src='/js/pagination.js'></script>

<!-- 一些全局用的脚本 -->
<script type='text/javascript' src='/js/global.js'></script> 
<script type='text/javascript' src='/js/myFuncs.js'></script>

<!-- layui -->
<script type='text/javascript' src='/js/tools/layui/layui.js'></script>
 <script type='text/javascript' src='/js/myFuncs.js'></script>
<script type="text/javascript">

$(document).ready(function(){

	_userAgent = navigator.userAgent;

	layui.use(['form'], function(){
	  	var form = layui.form;
	  	
	  	if(_userAgent.indexOf("MSIE")>0 && parseInt(_userAgent.match(/.*?MSIE\s(.*?);/)[1])<=9){
			showPlaceholder();
		}

	});

});

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

</script>
 <script>
	// 提交锁定 
	function stopSubmit(){

		$("#myForm input").each(function(){
			
			var curPlaceHolder = $.trim($(this).attr("placeholder"));
			var curValue = $.trim($(this).val());

			if( curValue==curPlaceHolder ) {
				$(this).val("");
			}

		});
	
		document.getElementById('stopsubmit').disabled=true;
		$("#myForm").submit();
		return false;
		//alert('添加成功！'); 
		
	}
</script>