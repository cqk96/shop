<?php include_once '../app/views/admin/adminNewsClasses/_head.php'; ?>
<h1><?php echo $page_title; ?></h1>
<form method='POST' action="/admin/newsClass/doUpdate">
<table>
    <caption><?php echo $page_title; ?></caption>
    <?php include_once '../app/views/admin/adminNewsClasses/_form.php'; ?>
</table>
</form>
<?php include_once '../app/views/admin/adminNewsClasses/_footer.php'; ?>
<script type="text/javascript">
	$(document).ready(function(){
		$('button').addClass('nosee');
	});
</script>
