<meta http-equiv='Content-Type' content="charset=utf-8">
<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">

</style>
<div class='main-container'>
	<?php
		foreach ($variable as $key => $value) {
			echo "<h4>".$value."</h4>";
		}
	?>
</div>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type='text/javascript' src='/js/myFuncs.js'></script>
<script type='text/javascript' src='/js/admin-news.js'></script>
