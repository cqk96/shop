<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>EQ_ID<input type='text' name='eq_id' required class='form-control' id='eq_id' value='<?php echo empty($data['eq_id'])? '':$data['eq_id'];  ?>' placeholder='请输入eq_id' /> </li>
		<li class='list-group-item' draggable='false'>OPTION_CONTENT<input type='text' name='option_content' required class='form-control' id='option_content' value='<?php echo empty($data['option_content'])? '':$data['option_content'];  ?>' placeholder='请输入option_content' /> </li>
		<li class='list-group-item' draggable='false'>OPTION_INDEX<input type='text' name='option_index' required class='form-control' id='option_index' value='<?php echo empty($data['option_index'])? '':$data['option_index'];  ?>' placeholder='请输入option_index' /> </li>
		<li class='list-group-item' draggable='false'>IS_RIGHT<input type='text' name='is_right' required class='form-control' id='is_right' value='<?php echo empty($data['is_right'])? '':$data['is_right'];  ?>' placeholder='请输入is_right' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->