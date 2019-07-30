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

	
	<style type="text/css">
	.smart-widget-header {font-size: 16px; }
	table { color: black; }
	</style>
	<!-- Jquery -->
	<script src="/js/jquery-1.11.1.min.js"></script>
<link rel="stylesheet" href="/js/tools/layDate/theme/default/laydate.css">
<style type="text/css">
.coverImg {
	display: block;
	width: 200px;
	height: 100px;
}
.list-group-item {position: relative; }
.shade-box { width: 100%; height: 46px; position: absolute; bottom: 0px; /*background-color: red*/; }
.nosee { display: none; }
</style>
 <div class='main-container'>
	 <form id='myForm' method='POST' action='/admin/activitys/doUpdate' enctype='multipart/form-data'>
		 <div class='smart-widget-header'>
			 <?php echo $page_title;?>
		 </div>
		 <?php include_once '_form.php'; ?>
	 </form>
 </div>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type='text/javascript' src='/js/myFuncs.js'></script>
<script type='text/javascript' src='/js/tools/layDate/laydate.js'></script>
<script type="text/javascript">

// 检测时间是否不正确
function checkDate()
{
	if($("#startTime").val()=="") {
		alert("活动开始时间不为空");
		return false;
	}

	if($("#endTime").val()=="") {
		alert("活动结束时间不为空");
		return false;
	}

	if($("#applyStartTime").val()=="") {
		alert("报名开始时间不为空");
		return false;
	}

	if($("#applyEndTime").val()=="") {
		alert("报名结束时间不为空");
		return false;
	}

	// 签到
	if($("#signInStartTime").val()=="") {
		alert("签到开始时间不为空");
		return false;
	}

	if($("#signInEndTime").val()=="") {
		alert("签到结束时间不为空");
		return false;
	}

	if($("#startTime").val()>$("#endTime").val()) {
		alert("活动开始时间大于结束时间");
		return false;	
	}

	if($("#applyStartTime").val()>$("#applyEndTime").val()) {
		alert("报名开始时间大于结束时间");
		return false;	
	}

	if($("#signInStartTime").val()>$("#signInEndTime").val()) {
		alert("签到开始时间大于结束时间");
		return false;	
	}

	document.getElementById('stopsubmit').disabled=true;
	$("#myForm").submit();
	//alert('添加成功！'); 

	return true;
	
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

/*from web*/
function pad(num, n) {  
    var len = num.toString().length;  
    while(len < n) {  
        num = "0" + num;  
        len++;  
    }  
    return num;  
}

$(document).ready(function(){

	_userAgent = navigator.userAgent;

	if(_userAgent.indexOf("MSIE")>0 && parseInt(_userAgent.match(/.*?MSIE\s(.*?);/)[1])<=9){
		showPlaceholder();
	}

	// 当前时间
	var dateObj = new Date();
	var curYear = dateObj.getFullYear(); 
	var curMonth = pad( dateObj.getMonth()+1, 2);
	var curDay = pad( dateObj.getDate(), 2);

	curDateTime = curYear + '-' + curMonth + '-' + curDay;
	curSignDateTime = curYear + '-' + curMonth + '-' + curDay + " 00:00:00";
	// window.curDateTime2= curYear + '.' + curMonth;
	
	laydate.render({
	  	elem: '#startTime'
	});

	laydate.render({
	  	elem: '#endTime'
	});

	laydate.render({
	  	elem: '#applyStartTime'
	});

	laydate.render({
	  	elem: '#applyEndTime'
	});

	laydate.render({
	  	elem: '#signInStartTime'//指定元素
	  	,type: 'datetime'
	});

	laydate.render({
	  	elem: '#signInEndTime' //指定元素
	  	,type: 'datetime'
	});

	$("#myForm").submit(function(){

		$("#myForm input").each(function(){
			
			var curPlaceHolder = $.trim($(this).attr("placeholder"));
			var curValue = $.trim($(this).val());

			if( curValue==curPlaceHolder ) {
				$(this).val("");
			}

		});

	});

});
</script>
</script>