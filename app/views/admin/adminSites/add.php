<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
	th {
		width: 100px;
	}
</style>
<div class='main-container'>
<h1><?php echo $page_title; ?></h1>
<form id="myForm" method='POST' action="/admin/news/doCreate" enctype='multipart/form-data' style='padding:20px !important;'>
<table>
    <caption>添加新闻栏目</caption>
    <?php include_once '_form.php'; ?>
</table>
</form>
</div>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type='text/javascript' src='/js/myFuncs.js'></script>
<script type='text/javascript' src='/js/admin-news.js'></script>
