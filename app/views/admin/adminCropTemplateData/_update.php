<?php include_once('../app/views/admin/_header.php') ?>
 <div class='main-container'>
	 <form id='myForm' method='POST' action='/admin/cropTemplateDatas/doUpdate' enctype='multipart/form-data'>
		 <div class='smart-widget-header'>
			 <?php echo $page_title;?>
		 </div>
		 <?php include_once '_form.php'; ?>
	 </form>
 </div>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type='text/javascript' src='/js/myFuncs.js'></script>