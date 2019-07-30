<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>TITLE<input type='text' name='title' required class='form-control' id='title' value='<?php echo empty($data['title'])? '':$data['title'];  ?>' placeholder='请输入title' /> </li>
		<li class='list-group-item' draggable='false'>STATUS_ID<input type='text' name='status_id' required class='form-control' id='status_id' value='<?php echo empty($data['status_id'])? '':$data['status_id'];  ?>' placeholder='请输入status_id' /> </li>
		<li class='list-group-item' draggable='false'>TYPE_ID<input type='text' name='type_id' required class='form-control' id='type_id' value='<?php echo empty($data['type_id'])? '':$data['type_id'];  ?>' placeholder='请输入type_id' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->