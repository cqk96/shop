<?php include_once('../app/views/admin/_header.php') ?>
 <div class='main-container'>
	 <form id='myForm' method='POST' action='/admin/diaryExaminations/doCreate' enctype='multipart/form-data' style='padding:20px !important;'>
		 <div class='smart-widget'>
			 <div class='smart-widget-header'>
				 <?php echo $page_title;?>
			 </div>
			 <?php include_once '_form.php'; ?>
		 </div>
	 </form>
 </div>
 <?php include_once('../app/views/admin/_footer.php') ?>
 <script type='text/javascript' src='/js/myFuncs.js'></script>