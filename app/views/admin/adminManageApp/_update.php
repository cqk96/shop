<?php include_once('../app/views/admin/_header.php') ?>
 <div class='main-container'>
	 <form id='myForm' method='POST' action='/admin/manageApps/doUpdate' enctype='multipart/form-data'>
		 <div class='smart-widget-header'>
			 <?php echo $page_title;?>
		 </div>
		 <?php include_once '_form.php'; ?>
	 </form>
 </div>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type='text/javascript' src='/js/myFuncs.js'></script>
<script type="text/javascript">
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

// 检测是否为空
function checkEmpty()
{

	var ok = true;
	$("input[type='text']").each(function(){
		
		var isRequired = $(this).attr("required");
		if( typeof(isRequired)!="undefined" ) {
			var curPlaceHolder = $.trim($(this).attr("placeholder"));
			var curValue = $.trim($(this).val());
			if( curPlaceHolder== curValue) {
				autoMessageNotice(curPlaceHolder);
				ok = false;
				return true;
			}
		}

	});

	if(_userAgent.indexOf("MSIE")>0 && parseInt(_userAgent.match(/.*?MSIE\s(.*?);/)[1])<=9){
		var fileValue = $("#apkFile").val();
		var isRequired = $("#apkFile").attr("required");
		if( typeof(isRequired)!="undefined" ) {
			if( fileValue=="" ) {
				autoMessageNotice("请选择上传文件");
				ok = false;
			}
		}
	}

	return ok;

}

// 检测文件类型
function checkType(obj)
{

	var curValue = $(obj).val();
	if( curValue.indexOf(".apk")<0 ) {
		autoMessageNotice("文件类型不对");
		$(obj).replaceWith("<input id='apkFile' type='file' name= 'file' class= 'form-control' required onchange='checkType(this)' />");
	}

}

$(document).ready(function(){

	// 判断是否 ie ie10以下处理placeholder
	_userAgent = navigator.userAgent;
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

	}

});
</script>