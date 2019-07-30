<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>ARCHIVE_TEMPLATE_CATEGORY_ID<input type='text' name='archive_template_category_id' required class='form-control' id='archiveTemplateCategoryId' value='<?php echo empty($data['archive_template_category_id'])? '':$data['archive_template_category_id'];  ?>' placeholder='请输入archive_template_category_id' /> </li>
		<li class='list-group-item' draggable='false'>MAIN_BODY_TYPE_ID<input type='text' name='main_body_type_id' required class='form-control' id='mainBodyTypeId' value='<?php echo empty($data['main_body_type_id'])? '':$data['main_body_type_id'];  ?>' placeholder='请输入main_body_type_id' /> </li>
		<li class='list-group-item' draggable='false'>ITEM_ID<input type='text' name='item_id' required class='form-control' id='itemId' value='<?php echo empty($data['item_id'])? '':$data['item_id'];  ?>' placeholder='请输入item_id' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->