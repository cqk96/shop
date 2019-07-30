
	<div class="smart-widget-inner">
		
		<ul class="list-group to-do-list sortable-list no-border">
			<input type='hidden' name='id' value="<?php echo empty($nav['id'])? '':$nav['id'];  ?>" />
			<li class="list-group-item" draggable="false">
				显示文字
				<input type='text' name='text' class='form-control' id='text' value='<?php echo empty($nav['text'])? '':$nav['text'];  ?>' placeholder='请输入显示文字' />
			</li>
			<li class="list-group-item" draggable="false">
				跳转链接
				<input type='text' name='url' class='form-control' id='url' value='<?php echo empty($nav['url'])? '':$nav['url'];  ?>' placeholder='请输入跳转链接' />
			</li>
			<li class="list-group-item" draggable="false">
				排序
				<input type='text' name='order' class='form-control' placeholder='请输入排序' value='<?php echo empty($nav['order'])? '': $nav['order']; ?>' >
			</li>
			<li class="list-group-item" draggable="false">
				 是否显示
					<label>是</label><input type='radio' name='show' value='1' <?php if($nav['show']==1) echo "checked"; ?> />
					<label>否</label><input type='radio' name='show' value='0' <?php if($nav['show']==0) echo "checked"; ?>  />
			</li>
			
			<li class="list-group-item" draggable="true">
				<button type='submit' class='btn btn-primary btn-sm'>提交</button>
			</li>
		</ul>
	</div><!-- ./smart-widget-inner -->
</div><!-- ./smart-widget -->


