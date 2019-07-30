	<!-- 隐藏域 -->
	<input type='hidden' name='id' value="<?php echo empty($sensitiveWord['id'])? '':$sensitiveWord['id'];  ?>" />

	<div class="smart-widget-inner">
		
		<ul class="list-group to-do-list sortable-list no-border">

			<li class="list-group-item" draggable="false">
				敏感词修改(以英文符合","隔开不同敏感词)
				<textarea name='content' class='form-control' placeholder="请输入用户介绍"><?php echo empty($sensitiveWord['content'])? '':$sensitiveWord['content'] ?></textarea>
			</li>
			
			<li class="list-group-item" draggable="false">
				<button type='submit' class='btn btn-primary btn-sm'>提交</button>
			</li>

		</ul>
	</div><!-- ./smart-widget-inner -->
</div><!-- ./smart-widget -->

