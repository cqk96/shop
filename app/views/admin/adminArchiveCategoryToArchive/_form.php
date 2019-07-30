<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>ARCHIVE_TEMPLATE_ID<input type='text' name='archive_template_id' required class='form-control' id='archiveTemplateId' value='<?php echo empty($data['archive_template_id'])? '':$data['archive_template_id'];  ?>' placeholder='请输入archive_template_id' /> </li>
		<li class='list-group-item' draggable='false'>ARCHIVE_TEMPLATE_CATEGORY_ID<input type='text' name='archive_template_category_id' required class='form-control' id='archiveTemplateCategoryId' value='<?php echo empty($data['archive_template_category_id'])? '':$data['archive_template_category_id'];  ?>' placeholder='请输入archive_template_category_id' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->