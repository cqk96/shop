<?php include_once('../app/views/admin/_header.php') ?>
<form id='myForm' method='POST' action="/admin/site/doUpdate" enctype='multipart/form-data'>
<table>
	<div class="smart-widget">
		<div class="smart-widget-header">
			<?php echo $page_title;?>
		</div>
    <?php include_once '_form.php'; ?>
</table>
</form>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type="text/javascript">
	$(document).ready(function(){
		$('button').addClass('nosee');
	});
</script>
