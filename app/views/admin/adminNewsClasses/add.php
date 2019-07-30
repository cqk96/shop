<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
	th {
		width: 100px;
	}
</style>
<div class="smart-widget-inner">
<!-- <h1><?php echo $page_title; ?></h1> -->
<form id="myForm" method='POST' action="/admin/newsClass/doCreate"  enctype='multipart/form-data' >
	<ul class="list-group to-do-list sortable-list no-border">
<!-- <table> -->
    <!-- <caption>添加新闻栏目</caption> -->
    <?php include_once '../app/views/admin/adminNewsClasses/_form.php'; ?>
<!-- </table> -->
	</ul>
</form>
</div>
<?php include_once('../app/views/admin/_footer.php') ?>

<script>
        
	 function stopSubmit(){
		$("#myForm").submit();
		document.getElementById('stopsubmit').disabled=true;
		return false;
		//alert('添加成功！'); 
		
	} 
        
</script>