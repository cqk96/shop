<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>DEPARTMENT_ID<input type='text' name='department_id' required class='form-control' id='departmentId' value='<?php echo empty($data['department_id'])? '':$data['department_id'];  ?>' placeholder='请输入department_id' /> </li>
		<li class='list-group-item' draggable='false'>AREA_ID<input type='text' name='area_id' required class='form-control' id='areaId' value='<?php echo empty($data['area_id'])? '':$data['area_id'];  ?>' placeholder='请输入area_id' /> </li>
		<li class='list-group-item' draggable='false'>USER_ID<input type='text' name='user_id' required class='form-control' id='userId' value='<?php echo empty($data['user_id'])? '':$data['user_id'];  ?>' placeholder='请输入user_id' /> </li>
		<li class='list-group-item' draggable='false'>ISSUE<input type='text' name='issue' required class='form-control' id='issue' value='<?php echo empty($data['issue'])? '':$data['issue'];  ?>' placeholder='请输入issue' /> </li>
		<li class='list-group-item' draggable='false'>START_TIME<input type='text' name='start_time' required class='form-control' id='startTime' value='<?php echo empty($data['start_time'])? '':$data['start_time'];  ?>' placeholder='请输入start_time' /> </li>
		<li class='list-group-item' draggable='false'>END_TIME<input type='text' name='end_time' required class='form-control' id='endTime' value='<?php echo empty($data['end_time'])? '':$data['end_time'];  ?>' placeholder='请输入end_time' /> </li>
		<li class='list-group-item' draggable='false'>CURRENT_WORK_CONTENT<input type='text' name='current_work_content' required class='form-control' id='currentWorkContent' value='<?php echo empty($data['current_work_content'])? '':$data['current_work_content'];  ?>' placeholder='请输入current_work_content' /> </li>
		<li class='list-group-item' draggable='false'>NEXT_WORKING_PLAN<input type='text' name='next_working_plan' required class='form-control' id='nextWorkingPlan' value='<?php echo empty($data['next_working_plan'])? '':$data['next_working_plan'];  ?>' placeholder='请输入next_working_plan' /> </li>
		<li class='list-group-item' draggable='false'>NUMBER_OF_GROUP_MEMBERS<input type='text' name='number_of_group_members' required class='form-control' id='numberOfGroupMembers' value='<?php echo empty($data['number_of_group_members'])? '':$data['number_of_group_members'];  ?>' placeholder='请输入number_of_group_members' /> </li>
		<li class='list-group-item' draggable='false'>WORKING_MEMBERS_COUNT<input type='text' name='working_members_count' required class='form-control' id='workingMembersCount' value='<?php echo empty($data['working_members_count'])? '':$data['working_members_count'];  ?>' placeholder='请输入working_members_count' /> </li>
		<li class='list-group-item' draggable='false'>COMPLETION_OF_CURRENT_TERM<input type='text' name='completion_of_current_term' required class='form-control' id='completionOfCurrentTerm' value='<?php echo empty($data['completion_of_current_term'])? '':$data['completion_of_current_term'];  ?>' placeholder='请输入completion_of_current_term' /> </li>
		<li class='list-group-item' draggable='false'>EXISTING_PROBLEMS<input type='text' name='existing_problems' required class='form-control' id='existingProblems' value='<?php echo empty($data['existing_problems'])? '':$data['existing_problems'];  ?>' placeholder='请输入existing_problems' /> </li>
		<li class='list-group-item' draggable='false'>PRIOR_PERIOD_EXISTING_PROBLEMS<input type='text' name='prior_period_existing_problems' required class='form-control' id='priorPeriodExistingProblems' value='<?php echo empty($data['prior_period_existing_problems'])? '':$data['prior_period_existing_problems'];  ?>' placeholder='请输入prior_period_existing_problems' /> </li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' class='btn btn-default btn-sm'>提交</button>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->