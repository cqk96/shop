<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>NAME<input type='text' name='name' required class='form-control' id='name' value='<?php echo empty($data['name'])? '':$data['name'];  ?>' placeholder='请输入name' /> </li>
		<li class='list-group-item' draggable='false'>MANAGER_ID<input type='text' name='manager_id' required class='form-control' id='managerId' value='<?php echo empty($data['manager_id'])? '':$data['manager_id'];  ?>' placeholder='请输入manager_id' /> </li>
		<li class='list-group-item' draggable='false'>ACREAGE<input type='text' name='acreage' required class='form-control' id='acreage' value='<?php echo empty($data['acreage'])? '':$data['acreage'];  ?>' placeholder='请输入acreage' /> </li>
		<li class='list-group-item' draggable='false'>ACRE_ID<input type='text' name='acre_id' required class='form-control' id='acreId' value='<?php echo empty($data['acre_id'])? '':$data['acre_id'];  ?>' placeholder='请输入acre_id' /> </li>
		<li class='list-group-item' draggable='false'>TYPE_ID<input type='text' name='type_id' required class='form-control' id='typeId' value='<?php echo empty($data['type_id'])? '':$data['type_id'];  ?>' placeholder='请输入type_id' /> </li>
		<li class='list-group-item' draggable='false'>REMARKS<input type='text' name='remarks' required class='form-control' id='remarks' value='<?php echo empty($data['remarks'])? '':$data['remarks'];  ?>' placeholder='请输入remarks' /> </li>
		<li class='list-group-item' draggable='false'>CROP_AMOUNT<input type='text' name='crop_amount' required class='form-control' id='cropAmount' value='<?php echo empty($data['crop_amount'])? '':$data['crop_amount'];  ?>' placeholder='请输入crop_amount' /> </li>
		<li class='list-group-item' draggable='false'>CROP_TYPE_ID<input type='text' name='crop_type_id' required class='form-control' id='cropTypeId' value='<?php echo empty($data['crop_type_id'])? '':$data['crop_type_id'];  ?>' placeholder='请输入crop_type_id' /> </li>
		<li class='list-group-item' draggable='false'>STATUS_ID<input type='text' name='status_id' required class='form-control' id='statusId' value='<?php echo empty($data['status_id'])? '':$data['status_id'];  ?>' placeholder='请输入status_id' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->