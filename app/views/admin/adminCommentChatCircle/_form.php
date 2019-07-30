<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>CHAT_ID<input type='text' name='chat_id' required class='form-control' id='chat_id' value='<?php echo empty($data['chat_id'])? '':$data['chat_id'];  ?>' placeholder='请输入chat_id' /> </li>
		<li class='list-group-item' draggable='false'>USER_ID<input type='text' name='user_id' required class='form-control' id='user_id' value='<?php echo empty($data['user_id'])? '':$data['user_id'];  ?>' placeholder='请输入user_id' /> </li>
		<li class='list-group-item' draggable='false'>CONTENT<input type='text' name='content' required class='form-control' id='content' value='<?php echo empty($data['content'])? '':$data['content'];  ?>' placeholder='请输入content' /> </li>
		<li class='list-group-item' draggable='false'>COMMENT_ID<input type='text' name='comment_id' required class='form-control' id='comment_id' value='<?php echo empty($data['comment_id'])? '':$data['comment_id'];  ?>' placeholder='请输入comment_id' /> </li>
		<li class='list-group-item' draggable='false'>CREATED_TIME<input type='text' name='created_time' required class='form-control' id='created_time' value='<?php echo empty($data['created_time'])? '':$data['created_time'];  ?>' placeholder='请输入created_time' /> </li>
		<li class='list-group-item' draggable='false'>UPDATED_TIME<input type='text' name='updated_time' required class='form-control' id='updated_time' value='<?php echo empty($data['updated_time'])? '':$data['updated_time'];  ?>' placeholder='请输入updated_time' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' id="stopsubmit" onclick="return stopSubmit()" class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->