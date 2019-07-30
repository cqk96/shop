<?php include_once('../app/views/admin/_header.php') ?>
<div class="smart-widget-inner table-responsive">
<form id="myForm" method='POST' action="/admin/sys/operate/doUpdate" enctype='multipart/form-data' style='padding:20px !important;'>
    <div class="smart-widget-inner">
    <?php include_once '_form.php'; ?>
</div>
</form>
</div>
<?php include_once('../app/views/admin/_footer.php') ?>
