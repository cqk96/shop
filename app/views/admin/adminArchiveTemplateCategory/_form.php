<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>NAME<input type='text' name='name' required class='form-control' id='name' value='<?php echo empty($data['name'])? '':$data['name'];  ?>' placeholder='请输入name' /> </li>
		<li class='list-group-item' draggable='false'>COVER<input type='text' name='cover' required class='form-control' id='cover' value='<?php echo empty($data['cover'])? '':$data['cover'];  ?>' placeholder='请输入cover' /> </li>
		<li class='list-group-item' draggable='false'>RESUME<input type='text' name='resume' required class='form-control' id='resume' value='<?php echo empty($data['resume'])? '':$data['resume'];  ?>' placeholder='请输入resume' /> </li>
		<li class='list-group-item' draggable='false'>ORDER_INDEX<input type='text' name='order_index' required class='form-control' id='orderIndex' value='<?php echo empty($data['order_index'])? '':$data['order_index'];  ?>' placeholder='请输入order_index' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->