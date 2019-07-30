<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
.container {width: 100%; height: 100%; overflow-x: hidden; background-color: #FFF; }
.title { font-size: 20px; font-weight: bold; margin-top: 40px; margin-bottom: 30px;}
.content {line-height: 2; text-align: justify; }
.content img {display: block; width: 60%; margin-left: auto; margin-right: auto; }
</style>
<body>
	<div class="container">
		<div class="title"><?php echo $data['title']; ?></div>
		<div class="content"><?php echo $data['content']; ?></div>

		<!-- 返回按钮-->
		<a class="btn btn-default btn-sm" onclick="history.back();">返回</a>

	</div>
</body>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type="text/javascript">
$(document).ready(function(){
	window.parent.$(".main-container").css("backgroundColor", "#FFF");
});
window.onbeforeunload = function(){
	window.parent.$(".main-container").css("backgroundColor", "#f5f5f5");
}
</script>