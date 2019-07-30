<?php include_once('../app/views/admin/_header.php') ?>
 <div class='main-container'>
	 <form id='myForm' method='POST' action='/admin/chatCircle/doUpdate' enctype='multipart/form-data'>
		 <div class='smart-widget-header'>
			 <?php echo $page_title;?>
		 </div>
		 <?php include_once '_form.php'; ?>
	 </form>
 </div>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type='text/javascript' src='/js/myFuncs.js'></script>

<script>
	// 提交锁定 
	function stopSubmit(){
	
		document.getElementById('stopsubmit').disabled=true;
		$("#myForm").submit();
		return false;
		//alert('添加成功！'); 
		
	}
</script>