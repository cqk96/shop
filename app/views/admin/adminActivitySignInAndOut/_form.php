<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>TYPE_ID<input type='text' name='type_id' required class='form-control' id='typeId' value='<?php echo empty($data['type_id'])? '':$data['type_id'];  ?>' placeholder='请输入type_id' /> </li>
		<li class='list-group-item' draggable='false'>ACTIVITY_ID<input type='text' name='activity_id' required class='form-control' id='activityId' value='<?php echo empty($data['activity_id'])? '':$data['activity_id'];  ?>' placeholder='请输入activity_id' /> </li>
		<li class='list-group-item' draggable='false'>USER_ID<input type='text' name='user_id' required class='form-control' id='userId' value='<?php echo empty($data['user_id'])? '':$data['user_id'];  ?>' placeholder='请输入user_id' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->