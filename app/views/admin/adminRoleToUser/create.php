<?php include_once('../app/views/admin/_header.php') ?>
<div class="smart-widget-inner table-responsive">
<form id="myForm" method='POST' action="/admin/sys/rtu/doCreate" enctype='multipart/form-data' style='padding:20px !important;'>
<div class="smart-widget-inner">
    <?php include_once '_form.php'; ?>
<div class="smart-widget-inner">
</form>
</div>
<?php include_once('../app/views/admin/_footer.php') ?>

<script>
	// 提交锁定 
	function stopSubmit(){
	
		document.getElementById('stopsubmit').disabled=true;
		$("#myForm").submit();
		return false;
		//alert('添加成功！'); 
		
	}
</script>