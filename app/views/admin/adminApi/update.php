<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
	.removeTag-a {
		display: block;
	    margin-top: 10px;
	    margin-left: 10px;
	}
</style>
<div class="smart-widget-inner table-responsive">
<form id="myForm" method='POST' action="/admin/api/doUpdate" enctype='multipart/form-data' style='padding:20px !important;'>
    <div class="smart-widget-inner">
    <?php include_once '_form.php'; ?>
</div>
</form>
</div>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type="text/javascript" src="/js/admin/admin-api.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	//console.log($('input').files);
});
</script>