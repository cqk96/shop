<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
	th {
		width: 100px;
	}
</style>
<div class='main-container'>
<form id="myForm" method='POST' action="/admin/nav/doCreate" style='padding:20px !important;'>
<table>
<div class="smart-widget">
	<div class="smart-widget-header">
		<?php echo $page_title;?>
	</div>
    <?php include_once '_form.php'; ?>
</table>
</form>
</div>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type='text/javascript' src='/js/myFuncs.js'></script>
<script type='text/javascript' src='/js/admin-news.js'></script>
