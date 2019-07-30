<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>CONTENT<input type='text' name='content' required class='form-control' id='content' value='<?php echo empty($data['content'])? '':$data['content'];  ?>' placeholder='请输入content' /> </li>
		<li class='list-group-item' draggable='false'>AUTHOR_ID<input type='text' name='author_id' required class='form-control' id='authorId' value='<?php echo empty($data['author_id'])? '':$data['author_id'];  ?>' placeholder='请输入author_id' /> </li>
		<li class='list-group-item' draggable='false'>IS_PUSHED<input type='text' name='is_pushed' required class='form-control' id='isPushed' value='<?php echo empty($data['is_pushed'])? '':$data['is_pushed'];  ?>' placeholder='请输入is_pushed' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->