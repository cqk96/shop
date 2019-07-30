<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>AREA_ID<input type='text' name='area_id' required class='form-control' id='areaId' value='<?php echo empty($data['area_id'])? '':$data['area_id'];  ?>' placeholder='请输入area_id' /> </li>
		<li class='list-group-item' draggable='false'>NUMBER<input type='text' name='number' required class='form-control' id='number' value='<?php echo empty($data['number'])? '':$data['number'];  ?>' placeholder='请输入number' /> </li>
		<li class='list-group-item' draggable='false'>COLUMN_NUMBER<input type='text' name='column_number' required class='form-control' id='columnNumber' value='<?php echo empty($data['column_number'])? '':$data['column_number'];  ?>' placeholder='请输入column_number' /> </li>
		<li class='list-group-item' draggable='false'>ROW_NUMBER<input type='text' name='row_number' required class='form-control' id='rowNumber' value='<?php echo empty($data['row_number'])? '':$data['row_number'];  ?>' placeholder='请输入row_number' /> </li>
		<li class='list-group-item' draggable='false'>STATUS_ID<input type='text' name='status_id' required class='form-control' id='statusId' value='<?php echo empty($data['status_id'])? '':$data['status_id'];  ?>' placeholder='请输入status_id' /> </li>
		<li class='list-group-item' draggable='false'>PLANTING_TIME<input type='text' name='planting_time' required class='form-control' id='plantingTime' value='<?php echo empty($data['planting_time'])? '':$data['planting_time'];  ?>' placeholder='请输入planting_time' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->