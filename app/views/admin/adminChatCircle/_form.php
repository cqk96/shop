<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>评论者名称<input type='text' name='user_id' required class='form-control' id='user_id' value='<?php echo empty($data['user_id'])? '':$data['user_id'];  ?>' placeholder='请输入user_id' /> </li>
		<li class='list-group-item' draggable='false'>评论内容<input type='text' name='content' required class='form-control' id='content' value='<?php echo empty($data['content'])? '':$data['content'];  ?>' placeholder='请输入content' /> </li>
		<li class='list-group-item' draggable='false'>图片<input type='text' name='imgs' required class='form-control' id='imgs' value='<?php echo empty($data['imgs'])? '':$data['imgs'];  ?>' placeholder='请输入imgs' /> </li>
		<li class='list-group-item' draggable='false'>点赞数<input type='text' name='like_count' required class='form-control' id='like_count' value='<?php echo empty($data['like_count'])? '':$data['like_count'];  ?>' placeholder='请输入like_count' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' id="stopsubmit" onclick="return stopSubmit()" class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->