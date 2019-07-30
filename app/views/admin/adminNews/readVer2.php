<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
html,body,div,p,form,table,tbody,tr,td,th,thead{
	padding: 0px;
	margin: 0px;
}
body { 
	background-color: #FFF;
	height: 100%; 
}
.contianer { width: 100%; padding-top: 21px; background-color: #fff;  }
.rest-top {
	width: 100%;
	height: 35px;
}
.news-content-box {
	width: 87.7%;
	margin: 0 auto;
}
.news-title {
	font-size: 28px;
	color: #000000;
	margin-bottom: 37px;
}
.news-content{

}
.news-content img {
	display: block;
	max-width: 100%;
}
.return-btn {
	padding: 5px 26px;
    font-size: 12px;
    color: #333333;
    border: 1px solid #C4C4C4;
    border-radius: 7px;
    margin-top: 57px;
}
</style>
<body>
	<div class="contianer">
		<div clas="rest-top"></div>
		
		<div class="news-content-box">
			<p class="news-title"><?php echo $data['title']; ?></p>
			<div class="news-content"> <?php echo $data['content']; ?> </div>

			<a href="javascript:void(0);" class="btn btn-default btn-sm return-btn" onclick="history.back();">返回</a>
			
		</div>

	</div>
</body>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type="text/javascript">
	$(document).ready(function(){
		
	});
</script>
