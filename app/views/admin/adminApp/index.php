<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
	.smart-widget, body, .bigBox{
		background-color: #F6F6F6 !important;
	}
	.bigBox {
		width: 100%;
		height: 500px;
		margin-bottom: 40px;
		margin-top: 20px;
		background-color: #F6F6F6 !important;
	}
	.app-box {
		width: 320px;
		height: 460px;
		float: left;
		position: relative;
		margin-left: 60px;
		margin-right: 40px;
		margin-bottom: 10px;
		margin-top: 25px;
		padding: 25px 25px 0px 25px;
		background-color: #FFF;
	}
	.imgBox {
		width: 100%;
		height: 90px;
	}
	.imgBox img {
		display: block;
		height: 90px;
		width: 90px;
	}

	.itemBox {
		width: 100%;
		height: 30px;
		line-height: 30px;
		margin-top: 30px;
		font-size: 16px;
	}
	.fireIcon {
		color: #FF870B;
	}
	.itemText {
		color: gray;
	}

	table.otherBox  {
		width: 100%;
		margin-top: 30px;
	}
	table.otherBox td{
		line-height: 25px;
		font-size: 8px;
		word-break: break-all;
		word-wrap:break-word;
	}

	.otherItemBelongText {
		color: gray;
	}
	.otherItemText {
		color: black;
	}

	.operateBox {
		width: 270px;
		height: 40px;
		position: absolute;
		bottom: 50px;
		font-size: 14px;
	}

	.editBox {
		width: 50%;
		height: 100%;
		float: left;
	}
	.concreteEditBox {
		width: 76%;
		height: 100%;
		line-height: 40px;
		text-align: center;
		margin: 0 auto;
		border-radius: 20px;
		border: 1px solid gray;
		display: block;
		color: gray;
	}

	.onItem {
		box-shadow: 0px 10px 10px gray;
	}
	.textBig {
		font-weight: bold ;
    	color: black !important;
	}
	.concreteEditBox:hover{
		cursor: pointer;
		color: #FF7E0F !important;
		border-color: #FF7E0F !important;
	}
	.tagTrangleBox {
		width: 0px;
		height: 0px;
		position: absolute;
		top: 0px;
		right: 0px;
		border-top: 20px solid #A4C639;
		border-left: 20px solid transparent;
		border-right: 20px solid #A4C639;
		border-bottom: 20px solid transparent;
	}
	.tagTrangleBox span{
		position: absolute;
		top: -14px;
		right: -14px;
		font-size: 16px;
		color: #FFF;
	}
	.app-box:hover{
		cursor: pointer;
	}
</style>
<div class='main-container'>
<div class="smart-widget">
	<div class="smart-widget-header">
		<?php echo $page_title;?>
		<span class="smart-widget-option">
			<a href="/admin/app/create">
				<i class="fa fa-plus"></i>
			</a>
			<a href="#" onclick="location.reload()" class="widget-refresh-option">
				<i class="fa fa-refresh"></i>
			</a>
		</span>
	</div>
		<?php for($i=0; $i<count($data); $i++){ ?>
		<?php if($i%2==0){ ?>
			<div class='bigBox'>
		<?php } ?>
		
		<div class="app-box" data-href="/admin/app/read?appId=<?php echo $data[$i]['app_id'] ?>">
			<div class="tagTrangleBox">
				<span class='fa fa-android'></span>
			</div>
			<div class="imgBox">
				<img src="<?php echo $data[$i]['icon'] ?>">
			</div>

			<div class="itemBox">
				<span class='fireIcon glyphicon glyphicon-fire'></span>
				<span class="itemText"><?php echo $data[$i]['name'] ?></span>
			</div>

			<table class='otherBox'>
				<tr class='otherItemBox'>
					<td width='30%'><span class="otherItemBelongText">appId:</span></td>
					<td width='70%'><span class="otherItemText"><?php echo $data[$i]['app_id'] ?></span></td>
				</tr>
				<tr class='otherItemBox'>
					<td><span class="otherItemBelongText">包名:</span></td>
					<td><span class="otherItemText"><?php echo $data[$i]['package_name'] ?></span></td>
				</tr>
				<tr class='otherItemBox'>
					<td><span class="otherItemBelongText">最新版本:</span></td>
					<td><span class="otherItemText"><?php echo $data[$i]['version_code'] ?></span></td>
				</tr>
			</table>

			<div class="operateBox">
				<div class="editBox">
					<a class="concreteEditBox" href="/admin/app/read?appId=<?php echo $data[$i]['app_id'] ?>">
						<span class="glyphicon glyphicon-pencil"></span>
						<span>編輯</span>
					</a>
				</div>
			</div>
		</div>

		<?php if(($i+1)%2==0 || (($i+1)>=count($data))){ ?>
			</div>
		<?php } ?>

		<?php } ?>
</div><!-- ./smart-widget -->


</div>
<?php //include_once('../app/views/admin/_footer.php') ?>
<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript">

$(".app-box").mouseenter(function(){
	var index = $(".app-box").index(this);
	$(".app-box").eq(index).addClass("onItem");
	$(".itemText").eq(index).addClass("textBig");
  	$(this).stop().animate({marginTop:"10px"});
});

$(".app-box").mouseleave(function(){
	var index = $(".app-box").index(this);
	$(".app-box").eq(index).removeClass("onItem");
	$(".itemText").eq(index).removeClass("textBig");
 	$(this).stop().animate({marginTop:"25px"});
});

$(document).ready(function(){
	$('.app-box').click(function(){
		var href = $(this).attr("data-href");
		window.location.href = href;
	});

});
</script>