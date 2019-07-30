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

		
		<style type="text/css">
		
		</style>
		<!-- Jquery -->
		<script src="/js/jquery-1.11.1.min.js"></script>
		
		<link rel="stylesheet" href="/js/tools/layui/css/layui.css">
<style type="text/css">
	th {
		width: 100px;
	}
	.gender-item {
		margin-bottom: 0px;
	}
	.smart-widget-header {font-size: 16px; }

	table {
		width: 100%;
		color: black;
	}
</style>

<script>
	
 </script>
</head>

 
 <body>
<div class='main-container'>
<form class="layui-form" id="myForm" method='POST' action="/admin/user/doCreate" enctype="multipart/form-data" style='padding:20px !important;'>
<div class="smart-widget">
	<div class="smart-widget-header">
		<?php echo $page_title;?>
	</div>
    <?php include_once '_form.php'; ?>
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
<script type='text/javascript' src='/js/date-picker/jquery-ui.min.js'></script> 
<script type='text/javascript' src='/js/tools/layui/layui.js'></script>

<script type="text/javascript">

// 移除节点
function removeNode(obj)
{
	var workExperienceId = $(obj).parent().find(".experience-ids").val();

	if( workExperienceId!="" || workExperienceId!=0 ) {
		// 接口移除
	}

	// 节点移除
	$(obj).parent().remove();

}

function removeNodeOne(obj)
{
	var workExperienceId = $(obj).parent().find(".department-ids").val();

	if( workExperienceId!="" || workExperienceId!=0 ) {
		// 接口移除
	}

	// 节点移除
	$(obj).parent().remove();

}

// 添加工作经历
function createExperience()
{
	
	timeIndex += 1;

	var content = '<div class="layui-form-item">';
		content += 		'<input type="hidden" class="experience-ids" name="workExperienceIds[]" value="0" />';
		content += 		'<div class="layui-inline date-time-item">';
		content += 		    '<label class="layui-form-label">起始时间: </label>';
		content += 		   	'<div class="layui-input-inline relative-item" style="width: 200px;">';
		content += 		      	'<input type="text" id="stratTime_'+timeIndex+'" maxLength="7" required  lay-verify="required" name="experienceStartTimes[]" class="layui-input start-time-input" placeholder="请输入开始年月" />';
		content += 		      	'<img class="date-choose-img" id="stratTimeImg_'+timeIndex+'" src="/images/date-choose.png" >';
		content += 		    '</div>';
		content += 		    '<div class="layui-form-mid">至</div>';
		content += 		    '<div class="layui-input-inline relative-item" style="width: 200px;">';
		content += 		      	'<input type="text" id="endTime_'+timeIndex+'"maxLength="7" name="experienceEndTimes[]" class="layui-input" placeholder="请输入结束年月" />';
		content += 		      	'<img class="date-choose-img" id="endTimeImg_'+timeIndex+'" src="/images/date-choose.png" >';
		content += 		    '</div>';
		content += 		'</div>';
		content += 		'<div class="layui-inline">';
		content += 		    '<label class="layui-form-label">职位: </label>';
		content += 		   	'<div class="layui-input-inline" style="width: 200px;">';
		content += 		      '<input type="text" name="experienceJobs[]" class="layui-input" required  lay-verify="required"  placeholder="请输入职位" />';
		content += 		   	'</div>';
		content += 		'</div>';
		content += 		'<div class="layui-inline">';
		content += 		    '<label class="layui-form-label">公司: </label>';
		content += 		    '<div class="layui-input-inline" style="width: 200px;">';
		content += 		      '<input type="text" name="experienceCompanies[]" class="layui-input" required  lay-verify="required"  placeholder="请输入公司" />';
		content += 		   	'</div>';
		content += 		'</div>';
	  	content += 		'<div class="layui-inline" onclick="removeNode(this)"> <span class="remove-experience-btn">X</span> </div>';
		content += '</div>';

	if(typeof(laydate)!='undefined') {
		
		$('.experience-lists-box').append(content);
		
		// 开始时间
		laydate.render({ 
		  	elem: '#stratTime_'+timeIndex,
		  	type: "month",
		  	eventElem: '#stratTimeImg_'+timeIndex,
		  	format: "yyyy.MM",
		  	trigger: 'click',
		  	value: window.curDateTime2
		});

		// 结束时间
		laydate.render({ 
		  	elem: '#endTime_'+timeIndex,
		  	type: "month",
		  	eventElem: '#endTimeImg_'+timeIndex,
		  	format: "yyyy.MM",
		  	trigger: 'click',
		  	value: window.curDateTime2
		});

	}
		
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

	// 全局时间索引
	window.timeIndex = 0;

	// 当前时间
	var dateObj = new Date();
	var curYear = dateObj.getFullYear(); 
	var curMonth = pad( dateObj.getMonth(), 2);
	var curDay = pad( dateObj.getDate(), 2);

	window.curDateTime = curYear + '-' + curMonth + '-' + curDay;
	window.curDateTime2= curYear + '.' + curMonth;

	//日期选择
	layui.use(['layer', 'form', 'laydate'], function(){
		var layer = layui.layer;
		window.laydate = layui.laydate;
		window.form = layui.form;

		window.globalForm = layui.form;

		form.on("select(pupilLists)", function(data){
		  	var selectedValue = data.value;
		  	$("#userId").val(selectedValue);
		});

		window.laydate.render({ 
		  	elem: '#joinTime',
		  	value: window.curDateTime
		});

		window.laydate.render({ 
		  	elem: '#birthdayTime',
		  	value: window.curDateTime
		});

		window.laydate.render({ 
		  	elem: '#recordWorkingTime',
		  	value: window.curDateTime
		});

		if(_userAgent.indexOf("MSIE")>0 && parseInt(_userAgent.match(/.*?MSIE\s(.*?);/)[1])<=9){
			showPlaceholder();
		}
		
	});

});


// 消息提醒
function autoMessageNotice(content)
{
    var time =  arguments[1] ? arguments[1] : 2000;//设置参数b的默认值为2 
    layer.open({
        id: 1,
        content: content,
        skin: 'msg',
        time: time //2m秒后自动关闭
    });
}

function stopSubmit(){

	var canSubmit = true;

	$('#myForm').find("input[type='text']").each(function(){
		var curValue = $.trim( $(this).val() );
		var curPlaceholder = $.trim( $(this).attr("placeholder") );
		var isRequired = $(this).attr("required");
		// console.log( isRequired );

		if( isRequired=="required" ) {
			if(curValue=="" || curValue==curPlaceholder) {
				canSubmit = false;
				autoMessageNotice(curPlaceholder);
				return true;
			}
		}

	});
	
	if( canSubmit ) {

		$('#myForm').find("input[type='text']").each(function(){
			var curValue = $.trim( $(this).val() );
			var curPlaceholder = $.trim( $(this).attr("placeholder") );
			if(curValue=="" || curValue==curPlaceholder) {
				$(this).val("");
			}

		});

		$('#myForm').find("textarea").each(function(){
			var curValue = $.trim( $(this).val() );
			var curPlaceholder = $.trim( $(this).attr("placeholder") );
			if(curValue=="" || curValue==curPlaceholder) {
				$(this).val("");
			}

		});

		document.getElementById('stopsubmit').disabled=true;
		$("#myForm").submit();
		return false;
		
	}
	
	return false;

} 

</script>