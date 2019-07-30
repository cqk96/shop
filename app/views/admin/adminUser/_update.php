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

	th {
		width: 100px;
		
	}
	
	textarea { resize: none; }
	.layui-form-label {width: auto; } 
	.experience-item-create-box {
		width: 100%;
		margin-bottom: 10px;
	}
	.create-experience-btn {
		margin-left: 30px;
	}
	.remove-experience-btn:hover{
		cursor: pointer;
	}
	.relative-item {
		position: relative;
	}
	.date-choose-img:hover {
		cursor: pointer;
	}
	.date-choose-img {
		position: absolute;
	    top: 10px;
	    right: 10px;
	}
</style>

<script>
	
 </script>
</head>

 
 <body>
<div class='main-container'>
<form class="layui-form"  id="myForm" method='POST' action="/admin/user/doUpdate" enctype='multipart/form-data'>

    <div class="smart-widget">
	<div class="smart-widget-header">
		<?php echo $page_title;?>
	</div>

	<!-- 隐藏域 -->
	<input type='hidden' name='id' value="<?php echo empty($user['id'])? '':$user['id'];  ?>" />
	<input type='hidden' name='avatar' value="<?php echo empty($user['avatar'])? '':$user['avatar'];  ?>" />


	<div class="smart-widget-inner">
		
		<ul class="list-group to-do-list sortable-list no-border">

			<li class="list-group-item" draggable="false">
				用户账号
				<input maxLength="11" type='text' name='user_login' <?php if(!empty($user['user_login'])) echo "readonly"; ?> required class='form-control' id='user_login' value='<?php echo empty($user['user_login'])? '':$user['user_login'];  ?>' placeholder='请输入用户账号' />
			</li>

			<!-- 额外增加字段 -->
			<li class="list-group-item" draggable="false">
				手机号
				<input type='text' maxLength="11" name='phone'  class='form-control' id='phone' value='<?php echo empty($user['phone'])? '':$user['phone'];  ?>' placeholder='请输入手机号' />
			</li>
			
			<li class="list-group-item" draggable="false">
				姓名
				<input type='text' maxLength="50" name='name'  class='form-control' id='name' value='<?php echo empty($user['name'])? '':$user['name'];  ?>' placeholder='请输入姓名' />
			</li>
			
			
			<li class="list-group-item" draggable="false">
				民族
				<input maxLength="20" type='text' name='ethnicity'  class='form-control' id='ethnicity' value='<?php echo empty($user['ethnicity'])? '':$user['ethnicity'];  ?>' placeholder='请输入民族' />
			</li>

			<li class="list-group-item" draggable="false">
				籍贯
				<input maxLength="50" type='text' name='native_place'  class='form-control' id='native_place' value='<?php echo empty($user['native_place'])? '':$user['native_place'];  ?>' placeholder='请输入籍贯' />
			</li>

			<li class="list-group-item" draggable="false">
				政治面貌
				<select name="political" class="form-control">
					<option value='0' <?php if(!empty($user['political']) && $user['political']==0 ) { echo "selected"; } ?> >无</option>
					<option value='1' <?php if(!empty($user['political']) && $user['political']==1 ) { echo "selected"; } ?> >团员</option>
					<option value='2' <?php if(!empty($user['political']) && $user['political']==2 ) { echo "selected"; } ?> >预备党员</option>
					<option value='3' <?php if(!empty($user['political']) && $user['political']==3 ) { echo "selected"; } ?> >党员</option>
				</select>
			</li>

			<li class="list-group-item" draggable="false">
				入党(团)时间
				<input type='text' name='join_time'  class='form-control' id='joinTime' value='<?php echo empty($user['join_time'])? '':$user['join_time'];  ?>' placeholder='请输入 入党(团)时间' />
			</li>

			<li class="list-group-item" draggable="false">
				毕业院校
				<input type='text' maxLength="30" name='university'  class='form-control' id='university' value='<?php echo empty($user['university'])? '':$user['university'];  ?>' placeholder='请输入 毕业院校' />
			</li>

			<li class="list-group-item" draggable="false">
				所学专业
				<input type='text' maxLength="12" name='major'  class='form-control' id='major' value='<?php echo empty($user['major'])? '':$user['major'];  ?>' placeholder='请输入 所学专业' />
			</li>

			<li class="list-group-item" draggable="false">
				学历
				<select name="education" class="form-control">
					<option value='0' <?php if(!empty($user['education']) && $user['education']==0 ) { echo "selected"; } ?> >无</option>
					<option value='1' <?php if(!empty($user['education']) && $user['education']==1 ) { echo "selected"; } ?> >博士</option>
					<option value='2' <?php if(!empty($user['education']) && $user['education']==2 ) { echo "selected"; } ?> >硕士</option>
					<option value='3' <?php if(!empty($user['education']) && $user['education']==3 ) { echo "selected"; } ?> >本科</option>
					<option value='4' <?php if(!empty($user['education']) && $user['education']==4 ) { echo "selected"; } ?> >专科</option>
					<option value='5' <?php if(!empty($user['education']) && $user['education']==5 ) { echo "selected"; } ?> >高中</option>
					<option value='6' <?php if(!empty($user['education']) && $user['education']==6 ) { echo "selected"; } ?> >初中</option>
				</select>
			</li>

			<li class="list-group-item" draggable="false">
				家庭住址
				<input type='text' maxLength="50" name='address'  class='form-control' id='address' value='<?php echo empty($user['address'])? '':$user['address'];  ?>' placeholder='请输入 家庭住址' />
			</li>

			<li class="list-group-item" draggable="false">
				<div class="layui-form-item">
	    			<label class="layui-form-label">工作年限</label>
	    				<div class="layui-input-inline">
	      				<input type="text" name="working_life_time" maxLength="4" value='<?php echo empty($user['working_life_time'])? '':$user['working_life_time'];  ?>'  placeholder="请输入工作年限" autocomplete="off" class="layui-input" onkeyup="value=value.replace(/[^\d\.]/g,'')" >
	    			</div>
	    			<div class="layui-form-mid layui-word-aux">年</div>
	  			</div>
  			</li>
			<!-- end 额外增加字段  -->

			<li class="list-group-item" draggable="false">
				选择用户头像
				<input type='file' name='userAvatar' class='form-control' />
			</li>

			<li class="list-group-item" draggable="false">
				用户昵称
				<input type='text' maxLength="10" name='nickname' class='form-control' value="<?php echo empty($user['nickname'])? '':$user['nickname'];  ?>" placeholder="请输入用户昵称(10字内)" />
			</li>

			<li class="list-group-item" draggable="false">
				<div class="layui-form-item gender-item">
					<label class="layui-form-label">性别</label>
				    <div class="layui-input-block">
				    	<?php foreach ($gender as $gender_key => $gender_val) {?>
				    	<input type='radio' name='gender' value="<?php echo $gender_key ?>" title="<?php echo $gender_val ?>" <?php if(!empty($user['gender']) && ($user['gender']==$gender_key)) echo "checked"; ?> />
				    	<?php }?>
				    </div>
				</div>
				
			</li>

			<li class="list-group-item" draggable="false">
				用户年龄
				<div class="input-group" style='width:30%'>
					<input type="text" name="age" maxLength="3" class="form-control" oninput="InputNumber(this,3,'+')" value="<?php echo empty($user['age'])? '':$user['age'];  ?>" placeholder="请输入用户年龄">
					<span class="input-group-addon">岁</span>
				</div>
			</li>

			<li class="list-group-item" draggable="false">
				出生年月
				<input type='text' name='birthday'  class='form-control' id='birthdayTime' value='<?php echo empty($user['birthday'])? '':date("Y-m-d", $user['birthday']);  ?>' placeholder='请输入 出生年月' />
			</li>

			<li class="list-group-item" draggable="false">
				用户介绍
				<textarea name='introduce' class='form-control' placeholder="请输入用户介绍"><?php echo empty($user['introduce'])? '':$user['introduce'] ?></textarea>
			</li>

			<li class="list-group-item" draggable="false">
				<div class="layui-form-item gender-item">
					<label class="layui-form-label">角色</label>
				    <div class="layui-input-block">
				    	<?php for ($i=0; $i < count($roles) ; $i++) { ?>
							<input type='checkbox' name='roles[]' value="<?php echo $roles[$i]['id']; ?>" title="<?php echo $roles[$i]['name']; ?>" <?php if(!empty($userRoles) && in_array($roles[$i]['id'], $userRoles) ) { echo "checked"; } ?> />
						<?php } ?>
				    </div>
				</div>

			</li>
			
			<li class="list-group-item" draggable="false">
				<button type='submit'  id="stopsubmit" class='btn btn-primary btn-sm' onclick="return stopSubmit()">提交</button>
			</li>

		</ul>
		
	</div><!-- ./smart-widget-inner -->
</div><!-- ./smart-widget -->

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

		if( $.trim( $("#joinTime").val() )=="" || $.trim( $("#joinTime").val() )== $.trim( $("#joinTime").attr("placeholder") ) ) {
			laydate.render({ 
			  	elem: '#joinTime',
			  	value: window.curDateTime
			});
		} else {
			laydate.render({ 
			  	elem: '#joinTime'
			});
		}

		if( $.trim( $("#birthdayTime").val() )=="" || $.trim( $("#birthdayTime").val() )== $.trim( $("#birthdayTime").attr("placeholder") ) ) {
			laydate.render({ 
			  	elem: '#birthdayTime',
			  	value: window.curDateTime
			});
		} else {
			laydate.render({ 
			  	elem: '#birthdayTime'
			});
		}

		if( $.trim( $("#recordWorkingTime").val() )=="" || $.trim( $("#recordWorkingTime").val() )== $.trim( $("#recordWorkingTime").attr("placeholder") ) ) {
			laydate.render({ 
			  	elem: '#recordWorkingTime',
			  	value: window.curDateTime
			});
		} else {
			laydate.render({ 
			  	elem: '#recordWorkingTime'
			});
		}
		
		if(_userAgent.indexOf("MSIE")>0 && parseInt(_userAgent.match(/.*?MSIE\s(.*?);/)[1])<=9){
			showPlaceholder();
		}

	});

	

});


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