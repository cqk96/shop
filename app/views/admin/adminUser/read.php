<?php include_once('../app/views/admin/_header.php') ?>
<h1><?php echo $page_title; ?></h1>
<form id='myForm' method='POST' action="/admin/news/doUpdate" enctype='multipart/form-data'>
<table>
    <caption><?php echo $page_title; ?></caption>
    <?php include_once '_form.php'; ?>
</table>
</form>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type="text/javascript">
	$(document).ready(function(){
		$('button').addClass('nosee');
	});
</script>
