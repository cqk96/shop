<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>DIARY_ID<input type='text' name='diary_id' required class='form-control' id='diaryId' value='<?php echo empty($data['diary_id'])? '':$data['diary_id'];  ?>' placeholder='请输入diary_id' /> </li>
		<li class='list-group-item' draggable='false'>COMMENT_TYPE_ID<input type='text' name='comment_type_id' required class='form-control' id='commentTypeId' value='<?php echo empty($data['comment_type_id'])? '':$data['comment_type_id'];  ?>' placeholder='请输入comment_type_id' /> </li>
		<li class='list-group-item' draggable='false'>APPROVER<input type='text' name='approver' required class='form-control' id='approver' value='<?php echo empty($data['approver'])? '':$data['approver'];  ?>' placeholder='请输入approver' /> </li>
		<li class='list-group-item' draggable='false'>EVALUATION<input type='text' name='evaluation' required class='form-control' id='evaluation' value='<?php echo empty($data['evaluation'])? '':$data['evaluation'];  ?>' placeholder='请输入evaluation' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->