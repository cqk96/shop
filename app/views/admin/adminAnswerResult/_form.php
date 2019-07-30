<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>AE_ID<input type='text' name='ae_id' required class='form-control' id='ae_id' value='<?php echo empty($data['ae_id'])? '':$data['ae_id'];  ?>' placeholder='请输入ae_id' /> </li>
		<li class='list-group-item' draggable='false'>EQ_ID<input type='text' name='eq_id' required class='form-control' id='eq_id' value='<?php echo empty($data['eq_id'])? '':$data['eq_id'];  ?>' placeholder='请输入eq_id' /> </li>
		<li class='list-group-item' draggable='false'>ANSWER<input type='text' name='answer' required class='form-control' id='answer' value='<?php echo empty($data['answer'])? '':$data['answer'];  ?>' placeholder='请输入answer' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->