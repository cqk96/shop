<?php include_once('../app/views/admin/_header.php') ?>
<form id="applicationForm" action="/application/create" method="post" onsubmit="return submitApplication();">
<div class="smart-widget-inner">
	<div class="smart-widget">
		<div class="smart-widget-header">
			<h2>吐槽？</h2>
		</div>
		<div class="smart-widget-inner">
			<div class="smart-widget-body">
				<form class="no-margin" id="formValidate2" data-validate="parsley" novalidate="">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">姓名</label>
								<input type="text" placeholder="姓名 *" class="form-control input-sm name" name="name" data-parsley-required="true">
							</div>
						</div><!-- /.col -->
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">Email</label>
								<input type="email" placeholder="Email *" class="form-control input-sm Email" name="email" data-parsley-required="true" data-parsley-type="url">
							</div>
						</div><!-- /.col -->
					</div><!-- /.row -->
					
					<div class="form-group">
						<label class="control-label">主题</label>
						<input type="text" placeholder="主题" class="form-control input-sm Subject" name="Subject" data-parsley-required="true" data-parsley-type="email">
					</div><!-- /form-group -->
					<div class="form-group">
						<label class="control-label">内容</label>
						<textarea class="form-control Message" name="content" placeholder="内容 *" rows="3" data-parsley-required="true"></textarea>
					</div><!-- /form-group -->	 

					<div class="text-right m-top-md">
						<button class="btn btn-info" type="button" onclick="return submitApplication();">发送</button>
						<button class="btn btn-default" type="reset">清空</button>
					</div>
				</form>
			</div>
		</div><!-- ./smart-widget-inner -->
	</div>
</div>
</form>


<?php include_once('../app/views/admin/_footer.php') ?>
<script src="/js/jquery.form.js" type="text/javascript"></script>
<script type="text/javascript">
	var canApply = true;
	function submitApplication(){
		// jquery 表单提交  
		if(canApply){
			canApply = false;
			$("#applicationForm").ajaxSubmit(function(rs) {  
				var result = JSON.parse(rs);
				alert(result.message);
				canApply = true;
			});  
		}
		
		  window.location.href=window.location.href;
		return false; // 必须返回false，否则表单会自己再做一次提交操作，并且页面跳转  
	}
	
	function resetApplicationForm(){
		$("#applicationForm input").val("");
		$("#applicationForm textarea").val("");
		$("#applicationForm textarea").html("");
	}
</script>