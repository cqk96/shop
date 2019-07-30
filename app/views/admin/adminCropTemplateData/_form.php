<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>CROP_ID<input type='text' name='crop_id' required class='form-control' id='cropId' value='<?php echo empty($data['crop_id'])? '':$data['crop_id'];  ?>' placeholder='请输入crop_id' /> </li>
		<li class='list-group-item' draggable='false'>ARCHIVE_TEMPLATE_ID<input type='text' name='archive_template_id' required class='form-control' id='archiveTemplateId' value='<?php echo empty($data['archive_template_id'])? '':$data['archive_template_id'];  ?>' placeholder='请输入archive_template_id' /> </li>
		<li class='list-group-item' draggable='false'>TEMPLATE_DATA<input type='text' name='template_data' required class='form-control' id='templateData' value='<?php echo empty($data['template_data'])? '':$data['template_data'];  ?>' placeholder='请输入template_data' /> </li>
		<li class='list-group-item' draggable='false'>USER_ID<input type='text' name='user_id' required class='form-control' id='userId' value='<?php echo empty($data['user_id'])? '':$data['user_id'];  ?>' placeholder='请输入user_id' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->