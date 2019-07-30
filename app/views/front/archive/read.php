<html>
<head>
	<title><?php echo $data['name']?:'模板'; ?> </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<link rel="stylesheet" type="text/css" href="/js/tools/layui/css/layui.css">
	<style type="text/css">
	html,body,form{
		margin: 0px;
		padding: 0px;
	}
	html,body {
		width: 100%;
		height: 100%;
	}
	body {
		background-color: #FFF;
		color: #333333;
	}

	form {
		font-size: 14px;
	}
	textarea {
		resize: none !important;
	}
	form input,form textarea {
		color: #4a4a4a;
		font-size: 14px;
	}

	form .layui-form-label {
		text-align: left;
	    padding: 18px 0px 18px 30px;
	    /*text-indent: 15px;*/
	    width: 110px;
	    padding-left: 30px;
	    box-sizing: border-box;
	}
	form .layui-input-block {
		padding-top: 9px;
		padding-bottom: 9px;
	}
	form .layui-input-block input{
		border: none;
	}
	form .layui-form-item {
		margin-bottom: 1px;
		position: relative;
	}
	.canlendar-icon {
		position: absolute;
		top: 17px;
		right: 23px;
	}

	form .layui-select-title .layui-edge {
		border-top-color: #4a4a4a;
		right: 30px;
	}

	.item-textarea-name {
		position: relative;
	}
	.item-textarea-name label{
		width: 100%;
	    float: inherit;
	    padding: 18px 44px 18px 30px;
	    text-align: left;
	    word-wrap: break-word;
	    word-break: break-all;
	    display: table-cell;
	}

	.item-textarea .layui-input-block{
		margin-left: 4%;
	}
	.trangle-icon {
		position: absolute;
		top: 18px;
		right: 23px;
	}

	/*特殊组件样式*/

	/*化学元素样式*/
	.item-type-7 input{
		width: 27px;
		display: inline-block;
		padding-left: 0px;
    	text-align: center;
	}

	/*特殊组件样式--end*/
	.bottom-line {
		width: 96%;
	    background-color: #ECECEC;
	    margin-left: 4%;
	    height: 1px;
	}

	.move-left-10{
		margin-left: 10px;
	}

	.nosee {
		display: none;
	}

	.img-list-box {
		margin-left: 10px;
		width:156px;
	}

	.img-create-box img {
		display: block;
		margin: 27px auto 0px auto;
	}

	.img-create-box {
		width: 100%;
		height:108px;
		border: 1px solid #9B9B9B;
		margin-top: 9px;
		cursor: pointer;
	}

	.img-box {
		position: relative;
	}
	.img-box img{
		display: block;
		width: 100%;
		height: auto;
		margin-top: 10px;
	}

	.remove-btn {
		position: absolute;
	    top: 0px;
	    right: 0px;
	    color: #FFF;
	    width: 47%;
	    text-align: center;
	    height: 30px;
	    line-height: 30px;
	    background: rgba(0, 0, 0, 0.5);
	}

	.show-big-img-box img {
	    display: block;
	    width: 100%;
	    height: auto;
	}

	/*缺省*/
	.rest-36 {
		width: 100%;
		height: 36px;
	}
	</style>
</head>
<body>

	<form class="layui-form" id="myForm" >
		
		<?php for ($i=0; $i < $dataCount; $i++) { ?>
			
			<!-- 日期输入框 -->
			<?php if( $modelData[$i]['type']==1 && isset($modelData[$i]['isDateTime']) ){ ?>
				<div class="layui-form-item" data-item-order="<?php echo $i+1; ?>" data-type="<?php echo $modelData[$i]['type']; ?>" date-time="true">
					<label class="layui-form-label"><?php echo empty($modelData[$i]['label'])? '&nbsp;':$modelData[$i]['label']; ?></label>
				    <div class="layui-input-block">
				      	<input id="item<?php echo $i+1; ?>" readonly type="text" name="data<?php echo $i+1; ?>[]" placeholder="<?php echo empty($modelData[$i]['placeholder'])? '':$modelData[$i]['placeholder']; ?>" autocomplete="off" class="layui-input" value="<?php echo empty($userData) || empty($userData[$i])? '':$userData[$i]; ?>" />
				     	<img id="item<?php echo $i+1; ?>Canlendar" class="canlendar-icon" src="/images/calendar.png">
				    </div>

				    <!-- line -->
					<div class="bottom-line <?php echo $i+1==$dataCount? ' nosee ':''; ?> "></div>
				</div>
			<?php } else if($modelData[$i]['type']==1){?>
			<!-- 简单输入框 -->
				<div class="layui-form-item" data-item-order="<?php echo $i+1; ?>" data-type="<?php echo $modelData[$i]['type']; ?>">
				    <label class="layui-form-label"><?php echo empty($modelData[$i]['label'])? '&nbsp;':$modelData[$i]['label']; ?> </label>
				    <div class="layui-input-block">
				      	<input type="text" name="data<?php echo $i+1; ?>[]" readonly lay-verify="required" placeholder="<?php echo empty($modelData[$i]['placeholder'])? '':$modelData[$i]['placeholder']; ?>" autocomplete="off" class="layui-input"  value="<?php echo empty($userData) || empty($userData[$i])? '':$userData[$i]; ?>" >
				    </div>

				    <!-- line -->
				    <div class="bottom-line <?php echo $i+1==$dataCount? ' nosee ':''; ?> "></div>
				</div>
			<?php } ?>

			<!-- 选择框 -->
			<?php if( $modelData[$i]['type']==6 ){ ?>
			<div class="layui-form-item" data-item-order="<?php echo $i+1; ?>" data-type="<?php echo $modelData[$i]['type']; ?>">

				<label class="layui-form-label"><?php echo empty($modelData[$i]['label'])? '&nbsp;':$modelData[$i]['label']; ?></label>
			    <div class="layui-input-block">
			      
			    	<select name="data<?php echo $i+1; ?>[]" lay-verify="required" readonly disabled>
			      	<?php for ($j=0; $j < count($modelData[$i]['options']); $j++) { ?>
			      		<option <?php if( !empty($userData) && !empty($userData[$i]) && $userData[$i]==$modelData[$i]['options'][$j] ) { echo "selected"; } ?> value="<?php echo $modelData[$i]['options'][$j] ?>"><?php echo $modelData[$i]['options'][$j] ?></option>
			      	<?php } ?>
			      	</select>

			    </div>

				<!-- line -->
				<div class="bottom-line <?php echo $i+1==$dataCount? ' nosee ':''; ?> "></div>
			</div>
			<?php } ?>				

			<!-- 特殊组件 指定格式 -->
			<?php if( $modelData[$i]['type']==7 ){ if( !empty($userData) && !empty($userData[$i]) ) { $tempData = explode("*,*", $userData[$i]); } ?>
			<div class="layui-form-item item-type-7" data-item-order="<?php echo $i+1; ?>" data-type="<?php echo $modelData[$i]['type']; ?>">

				<label class="layui-form-label"><?php echo empty($modelData[$i]['label'])? '&nbsp;':$modelData[$i]['label']; ?></label>
			    <div class="layui-input-block">
			    	<label class="move-left-10">N</label>(<input readonly type="text" name="data<?php echo $i+1; ?>[]"  lay-verify="required" autocomplete="off" class="layui-input" maxLength="3" value="<?php echo empty($tempData[0])? '':$tempData[0] ?>" >)P(<input readonly type="text" name="data<?php echo $i+1; ?>[]"  lay-verify="required" autocomplete="off" class="layui-input" maxLength="3" value="<?php echo empty($tempData[1])? '':$tempData[1] ?>" >)K(<input readonly type="text" name="data<?php echo $i+1; ?>[]"  lay-verify="required" autocomplete="off" class="layui-input" maxLength="3" value="<?php echo empty($tempData[2])? '':$tempData[2] ?>" >)
			    </div>

				<!-- line -->
				<div class="bottom-line <?php echo $i+1==$dataCount? ' nosee ':''; ?> "></div>
			</div>
			<?php } ?>				

			<!-- 文本框组件 -->
			<?php if( $modelData[$i]['type']==2 ){ ?>
			<div class="layui-form-item item-textarea-name" data-item-order="<?php echo $i+1; ?>" data-type="<?php echo $modelData[$i]['type']; ?>">
				
				<label class=""><?php echo empty($modelData[$i]['label'])? '&nbsp;':$modelData[$i]['label']; ?></label>

		    	<img id="item<?php echo $i+1; ?>Rotate" class="trangle-icon" src="/images/icon_trangle-right.png">

				<!-- line -->
				<div class="bottom-line <?php echo $i+1==$dataCount? ' nosee ':''; ?> "></div>
			</div>
			<div class="layui-form-item layui-form-text item-textarea">
			    <div class="layui-input-block">
			      <textarea readonly rows="8" name="data<?php echo $i+1; ?>[]" lay-verify="required" placeholder="<?php echo empty($modelData[$i]['placeholder'])? '':$modelData[$i]['placeholder']; ?>" class="layui-textarea"><?php echo empty($userData) || empty($userData[$i])? '':$userData[$i]; ?></textarea>
			    </div>
			</div>
			<?php } ?>

			<!-- 图片文件组件 -->
			<?php if( $modelData[$i]['type']==3 ){ ?>
			<div class="layui-form-item" data-item-order="<?php echo $i+1; ?>" data-type="<?php echo $modelData[$i]['type']; ?>">

				<label class="layui-form-label"><?php echo empty($modelData[$i]['label'])? '图片列表':$modelData[$i]['label']; ?></label>

				 <div class="layui-input-block">

				 	<div class="img-list-box">

				 	<?php if( !empty($userData) && !empty($userData[$i]) ) { $tempData = explode("*,*", $userData[$i]); ?>
					 	<?php for($j=0; $j<count($tempData); $j++){?>
					 		<div class='img-box'>
					 			<img src="<?php echo $tempData[$j]; ?>" onclick='showBigImg(this)' />
					 		</div>
					 	<?php } ?>
				 	<?php } ?>

				 	</div>

			    </div>

				<!-- line -->
				<div class="bottom-line <?php echo $i+1==$dataCount? ' nosee ':''; ?> "></div>
			</div>
			<?php } ?>

		<?php } ?>

		<div class="rest-36"></div>

		<!-- 测试的提交按钮 提交正式服务器时应关闭 -->
		<!-- <button class="layui-btn" onclick="return checkEmpty()">立即提交</button> -->

	</form>
</body>
<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/tools/layui/layui.all.js"></script>
<script type="text/javascript" src="/js/tools/rotate/jquery.rotate.min.js"></script>
<script type="text/javascript">

// 消息提醒
function autoMessageNotice(content)
{
	
	if( typeof(layer)!="undefined") {
		
		var time =  arguments[1] ? arguments[1] : 2000;//设置参数b的默认值为2 

		var contentStr = "";
		contentStr += "<div class='msg-notice-box'>";
		contentStr += content;
		contentStr += "</div>";

		layer.open({
			id: 1,
		    content: contentStr,
		    area: "300px",
		    skin: 'msg',
		    time: time //2m秒后自动关闭
		});

	}

}

// 检测文件提交
function checkEmpty()
{

	var ok = true;
	if( typeof(layer)!="undefined") {

		$('#myForm .layui-form-item').each(function(){

			var orderIndex = $(this).attr("data-item-order");
			var curDataType = $(this).attr("data-type");
			var curThis = $(this);

			if( typeof(curDataType)!="undefined" ) {

				curDataType = parseInt(curDataType);
				
				switch(curDataType) {
					case 1:
						// 简单输入框
						var curValue = curThis.find("input").val();
						var placeholder = curThis.find("input").attr("placeholder");
						placeholder = placeholder==""? '请填写必要项':placeholder;
						if( curValue=="" ) {
							ok = false;
							autoMessageNotice(placeholder);
						}
						break;
					case 2:
						// 文本输入框
						var curValue = curThis.next().find("textarea").val();
						var labelText = curThis.find("label").text();
						var placeholder = curThis.next().find("textarea").attr("placeholder");

						placeholder = labelText==""? placeholder:labelText;
						if( curValue=="" ) {
							ok = false;
							autoMessageNotice(placeholder);
						}
						break;
					case 7:
						// 有机质分子式
						var subOk = true;
						curThis.find("input").each(function(){
							var value2 = $(this).val();
							if( value2=="" ) {
								subOk = false;
								return false;
							}
						});

						if( !subOk ) {
							ok = false;
							autoMessageNotice("请填写分子式");
						}
						break;
				}

				if( !ok ) {
					return false;
				}

			}

		});

		if( ok ) {
			submitMyForm();
		}

	}

	return false;

}

function fileChange (obj) {

	if( obj.value =="" ) {
		return false;
	}

	var file = $(obj)[0].files[0];
	var imgBoxObj = $(obj).parent().find(".img-create-box");
	var curOrderIndex = $(obj).parent().parent().parent().attr("data-item-order");

	var reader = new FileReader(); 
    	
    reader.readAsDataURL(file);
    
    reader.onload = function(e){
    	showImg(imgBoxObj, this.result, curOrderIndex);
    	$(obj).val("");
    }

}

function showImg(imgBoxObj, base64Value, orderIndex)
{

	var imgStr = "";
	imgStr += "<div class='img-box'>";
	imgStr += "<div class='remove-btn' onclick='removeImgNode(this)'>删除图片</div>";
	imgStr += "<img src='" + base64Value + "' onclick='showBigImg(this)' />";
	imgStr += "<input type='hidden' name='data" + orderIndex + "[]' value='" + base64Value + "' />";
	imgStr += "</div>";

	imgBoxObj.before( imgStr );

}

/*显示大图*/
function showBigImg (obj)
{
	
	if( typeof(layer)!="undefined") {

		var imgSrc = $(obj).attr("src");
		var showImgStr = "";
		showImgStr += "<div class='show-big-img-box'>";
		showImgStr += "<img src='" + imgSrc + "' />";
		showImgStr += "</div>";
		layer.open({
			content: showImgStr,
			area: "100%"
		});

	}

}

function removeImgNode(obj)
{
	
	if( typeof(layer)!="undefined") {

		layer.confirm('确定要删除吗?', function(index){

			$(obj).parent().remove();

			// 确定删除
			layer.close(index);

		});

	}

}

$(document).ready(function(){
	
	layui.use(['form', 'laydate', 'layer'], function(){
	  
	  	var form = layui.form;
	  	var laydate = layui.laydate;
	  	layer = layui.layer;
	  
	  	form.render();

	});

	$(".item-textarea-name").click(function(){

		var orderIndex = $(this).attr("data-item-order");
		var curClass = $(this).attr("class");
		var curObj = $(this);
		var angle = 0;
	    var animateTo = -90;

		if( curClass.indexOf("rotate-down") >=0 ) {
			angle = -90;
			animateTo = 0;
		} 

		// $('#item' + orderIndex + 'Rotate').stop().rotate({
		// 	angle: angle, 
	 //        animateTo: animateTo,
	 //        callback: function(){
	 //        	if( !angle ) {
	 //        		curObj.addClass("rotate-down");

	 //        		// 展开
	 //        		curObj.next().fadeIn();
	 //        	} else {
	 //        		curObj.removeClass("rotate-down");

	 //        		// 收缩
	 //        		curObj.next().fadeOut();
	 //        	}
	 //        }
		// });
		
	});

	$('.img-create-box').click(function(){
		$(this).next().click();
	});

});
</script>
</html>