<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>USER_ID<input type='text' name='user_id' required class='form-control' id='user_id' value='<?php echo empty($data['user_id'])? '':$data['user_id'];  ?>' placeholder='请输入user_id' /> </li>
		<li class='list-group-item' draggable='false'>EXAM_ID<input type='text' name='exam_id' required class='form-control' id='exam_id' value='<?php echo empty($data['exam_id'])? '':$data['exam_id'];  ?>' placeholder='请输入exam_id' /> </li>
		<li class='list-group-item' draggable='false'>GET_SCORE<input type='text' name='get_score' required class='form-control' id='get_score' value='<?php echo empty($data['get_score'])? '':$data['get_score'];  ?>' placeholder='请输入get_score' /> </li>
		<li class='list-group-item' draggable='false'>RIGHT_COUNT<input type='text' name='right_count' required class='form-control' id='right_count' value='<?php echo empty($data['right_count'])? '':$data['right_count'];  ?>' placeholder='请输入right_count' /> </li>
		<li class='list-group-item' draggable='false'>ERROR_COUNT<input type='text' name='error_count' required class='form-control' id='error_count' value='<?php echo empty($data['error_count'])? '':$data['error_count'];  ?>' placeholder='请输入error_count' /> </li>
		<li class='list-group-item' draggable='false'>TOTAL_COUNT<input type='text' name='total_count' required class='form-control' id='total_count' value='<?php echo empty($data['total_count'])? '':$data['total_count'];  ?>' placeholder='请输入total_count' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->