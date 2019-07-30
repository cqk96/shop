<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>EXAM_ID<input type='text' name='exam_id' required class='form-control' id='exam_id' value='<?php echo empty($data['exam_id'])? '':$data['exam_id'];  ?>' placeholder='请输入exam_id' /> </li>
		<li class='list-group-item' draggable='false'>CONTENT<input type='text' name='content' required class='form-control' id='content' value='<?php echo empty($data['content'])? '':$data['content'];  ?>' placeholder='请输入content' /> </li>
		<li class='list-group-item' draggable='false'>SCORE<input type='text' name='score' required class='form-control' id='score' value='<?php echo empty($data['score'])? '':$data['score'];  ?>' placeholder='请输入score' /> </li>
		<li class='list-group-item' draggable='false'>QUESTION_TYPE<input type='text' name='question_type' required class='form-control' id='question_type' value='<?php echo empty($data['question_type'])? '':$data['question_type'];  ?>' placeholder='请输入question_type' /> </li>
		<li class='list-group-item' draggable='false'>HTML_TYPE<input type='text' name='html_type' required class='form-control' id='html_type' value='<?php echo empty($data['html_type'])? '':$data['html_type'];  ?>' placeholder='请输入html_type' /> </li>
		<li class='list-group-item' draggable='false'>QUESTION_INDEX<input type='text' name='question_index' required class='form-control' id='question_index' value='<?php echo empty($data['question_index'])? '':$data['question_index'];  ?>' placeholder='请输入question_index' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->