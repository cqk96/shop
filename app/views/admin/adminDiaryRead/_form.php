<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>USER_ID<input type='text' name='user_id' required class='form-control' id='userId' value='<?php echo empty($data['user_id'])? '':$data['user_id'];  ?>' placeholder='请输入user_id' /> </li>
		<li class='list-group-item' draggable='false'>TYPE_ID<input type='text' name='type_id' required class='form-control' id='typeId' value='<?php echo empty($data['type_id'])? '':$data['type_id'];  ?>' placeholder='请输入type_id' /> </li>
		<li class='list-group-item' draggable='false'>ITEM_ID<input type='text' name='item_id' required class='form-control' id='itemId' value='<?php echo empty($data['item_id'])? '':$data['item_id'];  ?>' placeholder='请输入item_id' /> </li>
		<li class='list-group-item' draggable='false'>TO_USER_ID<input type='text' name='to_user_id' required class='form-control' id='toUserId' value='<?php echo empty($data['to_user_id'])? '':$data['to_user_id'];  ?>' placeholder='请输入to_user_id' /> </li>
		<li class='list-group-item' draggable='false'>TO_TYPE_ID<input type='text' name='to_type_id' required class='form-control' id='toTypeId' value='<?php echo empty($data['to_type_id'])? '':$data['to_type_id'];  ?>' placeholder='请输入to_type_id' /> </li>
		<li class='list-group-item' draggable='false'>STATUS_ID<input type='text' name='status_id' required class='form-control' id='statusId' value='<?php echo empty($data['status_id'])? '':$data['status_id'];  ?>' placeholder='请输入status_id' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->