<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
html,body,img,div,p,h1,h2,h3,h4,h5,h6,a {
	padding: 0px;
	margin: 0px;
}
body,html {
	width: 100%;
	height: 100%;
}
body {
	/*padding: 30px;*/
}
	#userAvatar {
		display: block;
		width: 70px;
		height: 70px;
		margin-top: 20px;
    	margin-bottom: 20px;
	}
	/*头部样式*/
	table {
		width: 100%;
	}
	thead tr{
		height: 40px;
	}
	/*修改默认样式*/
	.smart-widget-header {
		/*margin-bottom: 5px;*/
		font-size: 16px;
	}
	.smart-widget {
		border-top-width: 0px;
	}
	.smart-widget .smart-widget-header {
		background-color: #86a4ee;
		color: #fff;
	}
	tbody tr,tfoot tr{
		height: 70px;
	}
	.singular {
		background-color: #f5f8fe;
	}
	.dual {
		background-color: #FFF;
	}
	.smart-widget-option i {
		color: #FFF;
	}
	/*图像*/
	.operationBox a {
		display: block;
		width: 100%;
		margin-bottom: 10px;
	}
	.icon-img {
		margin-right: 5px;
	}
	.icon-img img{
		display: inline-block;
		width: 13px;
		height: 13px;
	}
	.operationBox {
		font-size: 12px;
	}

	table {
		width: 100%;
	}
	table td {
		text-align: left;
		color: #313131;
		position: relative;
	}
	table tr {
		 border-bottom: 1px solid #e9e9e9;
	}

	.td-title {
		color: #979797 !important;
		/*text-align: right !important;*/
		padding-right: 20px;
		text-align: right;
	}
	.nosee {
		display: none !important;
	}
	.custom-radio label:after {
		background-color: #4778c7 !important;
	}

	/*修改按钮*/
	.saveBtn {
		width: 110px;
		height: 38px;
		line-height: 38px;
		color: #FFF;
		background-color: #86a4ee;
		text-align: center;
		border-radius: 5px;
		margin-left: 90px;
	}
	.saveBtn:hover {
		cursor: pointer;
	}
	#userAvatar:hover {
		cursor: pointer;
	}
	input,textarea {
		border: none;
		color: #4c4c4c !important;
		font-size: 14px !important;
	}
	input::-webkit-input-placeholder,textarea::-webkit-input-placeholder{
		color: #4c4c4c !important;
	}
	input::-moz-placeholder,textarea::-moz-placeholder{
		color: #4c4c4c !important;
	}
	input::-ms-input-placeholder,textarea::-ms-input-placeholder{
		color: #4c4c4c !important;
	}
	textarea {
		resize: none;
	}
	.edit-span {
		color: #86a4ee;
		position: absolute;
		font-size: 10px;
		top: 30px;
		right: 60px;
	}
	.edit-span:hover {
		cursor: pointer;
	}

	.rest-bottom-bar {
		width: 100%;
		height: 46px;
	}
</style>
<div class="smart-widget">
		<div class="smart-widget-header">
			基本信息
			<span class="smart-widget-option">
				<a href="#" onclick="location.reload()" class="widget-refresh-option">
					<i class="fa fa-refresh"></i>
				</a>
			</span>
		</div>
		<input type='hidden' name='id' id="id" value="<?php echo empty($user['id'])? '':$user['id'];  ?>" />
		<table>
			<tbody>
				<tr>
					<td width="15%" class="td-title">
						<label class="control-label">登陆账号</label>
					</td>
					<td width="85%">
						<?php echo empty($user['user_login'])? '':$user['user_login'];  ?>
					</td>
				</tr>
				<tr>
					<td class="td-title">
						<input type='hidden' name='domain' class='form-control' id='avatar' value="<?php echo empty($user['avatar'])? '':$user['avatar'];  ?>"/>
						<label class="control-label">头像</label>
					</td>
					<td>
						<img id="userAvatar" src="<?php echo empty($user['avatar'])? '/images/avatar.png':$user['avatar']; ?>">
						<div class="">
							<input class="nosee" type="file" id="updateAvatar" onchange="readFile()" accept="image/jpeg">
						</div><!-- /.col -->
					</td>
				</tr>
				<tr>
					<td class="td-title">
						<label class="control-label">昵称</label>
					</td>
					<td>
						<input style="width: 100px;" type='text' class='editItem' name='nickname' placeholder='请输入昵称' value='<?php echo empty($user['nickname'])? '': $user['nickname']; ?>' data-origin="<?php echo empty($user['nickname'])? '': $user['nickname']; ?>" >
						<span class="edit-span" data-index="1">修改</span>
					</td>
				</tr>
				<tr>
					<td class="td-title">
						<input type="hidden" id="gender" value="<?php echo empty($user['gender'])? 3:$user['gender'];?>" data-origin="<?php echo empty($user['gender'])? 3:$user['gender'];?>" >
							<label class="control-label">性别</label>
					</td>
					<td>
						<div class="radio inline-block" style="padding-left: 0px;">
							<div class="custom-radio m-right-xs">
								<input type="radio" id="gender1" name="gender" value="1">
								<label for="gender1" class='gender1'></label>
							</div>
							<div class="inline-block vertical-top">男</div>
						</div>
						<div class="radio inline-block">
							<div class="custom-radio m-right-xs">
								<input type="radio" id="gender2" name="gender" value="2">
								<label for="gender2" class='gender2'></label>
							</div>
							<div class="inline-block vertical-top">女</div>
						</div>
						<div class="radio inline-block">
							<div class="custom-radio m-right-xs">
								<input type="radio" id="gender3" name="gender" value="3">
								<label for="gender3" class='gender3'></label>
							</div>
							<div class="inline-block vertical-top">保密</div>
						</div>
					</td>
				</tr>
				<tr>
					<td class="td-title">
						<label class="control-label">年龄</label>
					</td>
					<td>
						<select style="width: 80px;"  class="form-control editItem" name="age" data-origin="<?php echo $user['age']; ?>">
							<?php for($i=1; $i<=100; $i++){  ?>
							<option <?php if($user['age']==$i) echo "selected"; ?> value="<?php echo $i; ?>" ><?php echo $i.'岁' ?></option>
							<?php }?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="td-title">
						<label class="control-label">介绍</label>
					</td>
					<td>
						<textarea style="width: 510px;margin-top: 6px;" class="editItem" name='introduce' placeholder='请输入介绍' data-origin="<?php echo empty($user['introduce'])? '': $user['introduce']; ?>" ><?php echo empty($user['introduce'])? '': $user['introduce']; ?></textarea>
						<span class="edit-span" data-index="3">修改</span>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan=2>
						<div class="saveBtn" id="stopsubmit"  onclick="">
							保存修改
						</div>
					</td>
				</tr>
			</tfoot>
		</table>
</div><!-- ./smart-widget -->

<div class="rest-bottom-bar"></div>

<?php include_once('../app/views/admin/_footer.php') ?>
<script type="text/javascript" src="/js/myFuncs.js?1"></script>
<!-- <script type="text/javascript" src="/js/admin/user-read.js?7"></script> -->
<script type="text/javascript">
$(document).ready(function(){
	 
	var _parentIframe = window.parent;
	
	if( _parentIframe.length!=0 ){
		_parentIframe.document.getElementById('adminAvatar').src = document.getElementById('userAvatar').src;
	} 

	window.onkeypress=function(event){
	    //回车事件
	    if(event.keyCode==13){
	    	$(".saveBtn").click();
	    }
	}
});


</script>

<script>


// 修改结果
function updateUserInfoOk(data)
{

	if( data['success']==0 ) {
		globalOk = false;
	}

}

//修改用户信息
function changeItem(name,value){

	console.log( "修改" );

	var userObject = new Array();
	userObject['name'] = name;
	userObject['value'] = value;

	
	//传入数组 userAvatar
	sendAjax(userObject,'/admin/user/updateUserInfo', false, 'updateUserInfoOk');

}

function readFile()
{
	
	var file = $("#updateAvatar")[0].files[0];
	
	var fileFragArray = file['name'].split('.');
    var result = document.getElementById('result');
    var allowSuffixArray = new Array('jpg','jpeg');
    var allowOk = false;
    var position = '';

    for (var i = 0; i <allowSuffixArray.length; i++) {
    	if(fileFragArray[fileFragArray.length-1]==allowSuffixArray[i]){
    		allowOk = true;
    		position = i;
    	}
    }

    if(allowOk){
    	var reader = new FileReader(); 
    	
	    reader.readAsDataURL(file);
	    
	    reader.onload = function(e){

			$("#userAvatar").attr('src',this.result);

	    }

    }else {

    	alert("请上传图片格式的文件");

    }

}

function updateUserAvatarOk(data)
{
	
	if(data['success']){
		
	} else {
		globalOk = false;
	}

}

$(document).ready(function(){

	// 全局ok 
	globalOk = true;

	//点击修改文本
	$('.edit-span').click(function(){
		var index = $(this).attr("data-index");
		$(this).prev().addClass("form-control");
	});

	//失去焦点
	$("input").blur(function(){
		$(this).removeClass("form-control");
	});
	$("textarea").blur(function(){
		$(this).removeClass("form-control");
	});

	//换头像
	$("#userAvatar").click(function(){
		$("#updateAvatar").click();
	});
	
	var currentGender = $("#gender").val();
	for (var i = 1; i <=3; i++) {
		if(i==currentGender) {
			$(".gender"+i).click();
		}
	}

	//保存与修改
	$('.saveBtn').click(function(){
		$('.editItem').each(function(){
			var name = $(this).prop('name');
			var dataOrigin = $(this).attr("data-origin");
			var value = $(this).val();

			if( dataOrigin!=value ) {
				changeItem(name,value);	
			}
			
		});

		// 修改性别
		var name = $("input[name='gender']:checked").prop('name');
		var value = $("input[name='gender']:checked").val();
		if( $("#gender").val()!=value ) {
			changeItem(name,value);
		}

		//修改头像
		var imageObject = new Array();
		var format = /data/;
		imageObject['imageStr'] = $("#userAvatar").prop("src");

		if(format.test(imageObject['imageStr'])){

			imageObject['type'] = "image/jpeg";
			
			sendAjax(imageObject,'/admin/user/updateUserAvatar',false, "updateUserAvatarOk");	
		} else {
			
			// document.getElementById('stopsubmit').disabled=true;
			// setTimeout(function(){
			// 	window.location.reload();
			// },800);
		}

		if( globalOk ) {
			// console.log( "ok" );
			setTimeout(function(){
				window.location.reload();
			},800);	
		}
		
	});

});

</script>

