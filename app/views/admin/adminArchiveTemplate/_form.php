<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>NAME<input type='text' name='name' required class='form-control' id='name' value='<?php echo empty($data['name'])? '':$data['name'];  ?>' placeholder='请输入name' /> </li>
		<li class='list-group-item' draggable='false'>CODE<input type='text' name='code' required class='form-control' id='code' value='<?php echo empty($data['code'])? '':$data['code'];  ?>' placeholder='请输入code' /> </li>
		<li class='list-group-item' draggable='false'>SHOW_CODE<input type='text' name='show_code' required class='form-control' id='showCode' value='<?php echo empty($data['show_code'])? '':$data['show_code'];  ?>' placeholder='请输入show_code' /> </li>
		<li class='list-group-item' draggable='false'>MODEL_DATA<input type='text' name='model_data' required class='form-control' id='modelData' value='<?php echo empty($data['model_data'])? '':$data['model_data'];  ?>' placeholder='请输入model_data' /> </li>
		<li class='list-group-item' draggable='false'>STATUS_ID<input type='text' name='status_id' required class='form-control' id='statusId' value='<?php echo empty($data['status_id'])? '':$data['status_id'];  ?>' placeholder='请输入status_id' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->