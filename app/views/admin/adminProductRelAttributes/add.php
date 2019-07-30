<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
	th {
		width: 100px;
	}
	.nosee {
        display: none !important;
    }
    .addImgBtn {
    	color: #00ADFF;
    }
    .addImgBtn:hover {
    	cursor: pointer;
    	animation:myRotate 2s 1;
    	-moz-animation:myRotate 2s 1; /* Firefox */
		-webkit-animation:myRotate 2s 1; /* Safari and Chrome */
		-o-animation:myRotate 2s 1; /* Opera */
    }

    @keyframes myRotate
	{
		from {transform:rotate(0deg);}
		to {transform:rotate(90deg);}
	}
	@-moz-keyframes myRotate /* Firefox */
	{
		from {font-size: 1em;}
		to {font-size: 10em;}
	}

	@-webkit-keyframes myRotate /* Safari 和 Chrome */
	{
		from {font-size: 1em;}
		to {font-size: 10em;}
	}

	@-o-keyframes myRotate /* Opera */
	{
		from {font-size: 1em;}
		to {font-size: 10em;}
	}

	/*组图样式*/
	.images-box {
		margin-top: 10px;
	}
	.each-images-box {
		width: 90px;
		height: 60px;
		overflow: hidden;
		display: inline-block;
	}
	.images-box input{
		/*display: inline-block;
	    margin-top: 16px;
	    vertical-align: top;
	    margin-left: 15px;
	    width: 200px;*/
	}
	.each-images-box img{
		display: block;
		width: 100%;
		height: auto;
	}
	.removeImgBtn {
		    display: inline-block;
		    vertical-align: top;
		    font-size: 2em;
		    /* margin-top: 8px; */
		    margin-left: 10px;
	}
	.removeImgBtn:hover {
		cursor: pointer;
		animation:myRotate 2s 1;
    	-moz-animation:myRotate 2s 1; /* Firefox */
		-webkit-animation:myRotate 2s 1; /* Safari and Chrome */
		-o-animation:myRotate 2s 1; /* Opera */
	}
	.showUploadResult {
		display: inline-block;
	    vertical-align: top;
	    font-size: 14px;
	    margin-top: 18px;
	    margin-left: 15px;
	}
	.content img{
		width: 100%;
		height: 120px;
		display: inline-block;
	}
	.each-content {
		width: 200px;
		display: inline-block;
    	margin-right: 10px;
	}
	.attr-select {
		width: 160px;
   	 	display: inline-block;
	}
	.priceBox {
		width: 200px;
	    display: inline-block;
	    vertical-align: top;
	    margin-left: 17px;
	}
</style>
<div class="smart-widget-inner table-responsive">
<div class="smart-widget-inner">
<form id="myForm" method='POST' action="/admin/productRelAttribute/doCreate" enctype='multipart/form-data' style='padding:20px !important;'>
<ul class="list-group to-do-list sortable-list no-border">
    <?php include_once '_form.php'; ?>
</ul>
</form>
</div>
</div>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type='text/javascript' src='/js/myFuncs.js'></script>
<script type="text/javascript">

// 去除节点
function removeImgNode(obj)
{
	
	var rs = confirm("确定删除嘛?");
	if(rs){
		$(obj).parent().remove();
	}
}

$(document).ready(function(){

	var selectStr = <?php echo "\"".$select."\""; ?>;
	
	//增加属性
	$('.addImgBtn').click(function(){
		var str = "<div>"+selectStr+"<div class='priceBox'>"+
                    "<div class='input-group'><div class='input-group-addon'>￥</div>"+
                    "<input type='text' class='form-control' name='price[]' placeholder='请填写附加价格' value=''>"+
                    "<div class='input-group-addon'>元</div></div>"+
                	"</div><span class='removeImgBtn' onclick='removeImgNode(this)'>&times;</span></div>";
        $('.images-box').append(str);
	});

});
</script>
