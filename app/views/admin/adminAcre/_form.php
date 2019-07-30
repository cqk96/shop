<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>NAME<input type='text' name='name' required class='form-control' id='name' value='<?php echo empty($data['name'])? '':$data['name'];  ?>' placeholder='请输入name' /> </li>
		<li class='list-group-item' draggable='false'>FARM_ID<input type='text' name='farm_id' required class='form-control' id='farmId' value='<?php echo empty($data['farm_id'])? '':$data['farm_id'];  ?>' placeholder='请输入farm_id' /> </li>
		<li class='list-group-item' draggable='false'>MANAGER_ID<input type='text' name='manager_id' required class='form-control' id='managerId' value='<?php echo empty($data['manager_id'])? '':$data['manager_id'];  ?>' placeholder='请输入manager_id' /> </li>
		<li class='list-group-item' draggable='false'>ACREAGE<input type='text' name='acreage' required class='form-control' id='acreage' value='<?php echo empty($data['acreage'])? '':$data['acreage'];  ?>' placeholder='请输入acreage' /> </li>
		<li class='list-group-item' draggable='false'>AREA_AMOUNT<input type='text' name='area_amount' required class='form-control' id='areaAmount' value='<?php echo empty($data['area_amount'])? '':$data['area_amount'];  ?>' placeholder='请输入area_amount' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->