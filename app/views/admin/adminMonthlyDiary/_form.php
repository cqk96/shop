<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>YEAR<input type='text' name='year' required class='form-control' id='year' value='<?php echo empty($data['year'])? '':$data['year'];  ?>' placeholder='请输入year' /> </li>
		<li class='list-group-item' draggable='false'>MONTH<input type='text' name='month' required class='form-control' id='month' value='<?php echo empty($data['month'])? '':$data['month'];  ?>' placeholder='请输入month' /> </li>
		<li class='list-group-item' draggable='false'>CONTENT<input type='text' name='content' required class='form-control' id='content' value='<?php echo empty($data['content'])? '':$data['content'];  ?>' placeholder='请输入content' /> </li>
		<li class='list-group-item' draggable='false'>TRANSLITERATION<input type='text' name='transliteration' required class='form-control' id='transliteration' value='<?php echo empty($data['transliteration'])? '':$data['transliteration'];  ?>' placeholder='请输入transliteration' /> </li>
		<li class='list-group-item' draggable='false'>MAINTENANCE<input type='text' name='maintenance' required class='form-control' id='maintenance' value='<?php echo empty($data['maintenance'])? '':$data['maintenance'];  ?>' placeholder='请输入maintenance' /> </li>
		<li class='list-group-item' draggable='false'>WEED<input type='text' name='weed' required class='form-control' id='weed' value='<?php echo empty($data['weed'])? '':$data['weed'];  ?>' placeholder='请输入weed' /> </li>
		<li class='list-group-item' draggable='false'>MECHANICAL_USAGE<input type='text' name='mechanical_usage' required class='form-control' id='mechanicalUsage' value='<?php echo empty($data['mechanical_usage'])? '':$data['mechanical_usage'];  ?>' placeholder='请输入mechanical_usage' /> </li>
		<li class='list-group-item' draggable='false'>FERTILIZATION<input type='text' name='fertilization' required class='form-control' id='fertilization' value='<?php echo empty($data['fertilization'])? '':$data['fertilization'];  ?>' placeholder='请输入fertilization' /> </li>
		<li class='list-group-item' draggable='false'>OTHER_WORK<input type='text' name='other_work' required class='form-control' id='otherWork' value='<?php echo empty($data['other_work'])? '':$data['other_work'];  ?>' placeholder='请输入other_work' /> </li>
		<li class='list-group-item' draggable='false'>REMARKS<input type='text' name='remarks' required class='form-control' id='remarks' value='<?php echo empty($data['remarks'])? '':$data['remarks'];  ?>' placeholder='请输入remarks' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->