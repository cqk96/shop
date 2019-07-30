<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
	th {
		width: 100px;
	}
</style>
<div class="smart-widget-inner">
<form id="myForm" method='POST' action="/admin/newsClass/doUpdate"  enctype='multipart/form-data' >
<ul class="list-group to-do-list sortable-list no-border">
    <caption><?php echo $page_title; ?></caption>
    <?php include_once '../app/views/admin/adminNewsClasses/_form.php'; ?>
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