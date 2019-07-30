<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>活动名称<input type='text' name='title' required class='form-control' id='title' value='<?php echo empty($data['title'])? '':$data['title'];  ?>' placeholder='请输入活动名称' /> </li>
		<li class='list-group-item' draggable='false'>活动封面 ( 348px * 158px )
			<input accept="image/*" type="file" name="cover" class="form-control">
		</li>

		<li class='list-group-item' draggable='false'>活动开始时间
			<input type='text' name='start_time' required class='form-control' id='startTime' value='<?php echo empty($data['start_time'])? '':$data['start_time'];  ?>' placeholder='请输入活动开始时间' />
		</li>
		<li class='list-group-item' draggable='false'>活动结束时间
			<input type='text' name='end_time' required class='form-control' id='endTime' value='<?php echo empty($data['end_time'])? '':$data['end_time'];  ?>' placeholder='请输入活动结束时间' />
		</li>
		<li class='list-group-item' draggable='false'>
			报名开始时间
			<input type='text' name='apply_start_time' required class='form-control' id="applyStartTime" value='<?php echo empty($data['apply_start_time'])? '':$data['apply_start_time'];  ?>' placeholder='请输入报名开始时间' />
		</li>
		<li class='list-group-item' draggable='false'>
			报名截止时间
			<input type='text' name='apply_end_time' required class='form-control' id='applyEndTime' value='<?php echo empty($data['apply_end_time'])? '':$data['apply_end_time'];  ?>' placeholder='请输入报名截止时间' /> 
		</li>

		<!-- 签到 -->
		<li class='list-group-item' draggable='false'>
			签到开始时间
			<input type='text' name='sign_in_start_time' required class='form-control' id="signInStartTime" value='<?php echo empty($data['sign_in_start_time'])? '':$data['sign_in_start_time'];  ?>' placeholder='请输入签到开始时间' />
		</li>
		<li class='list-group-item' draggable='false'>
			签到截止时间
			<input type='text' name='sign_in_end_time' required class='form-control' id='signInEndTime' value='<?php echo empty($data['sign_in_end_time'])? '':$data['sign_in_end_time'];  ?>' placeholder='请输入签到截止时间' /> 
		</li>

		<li class='list-group-item' draggable='false'>活动人数<input type='text' title="<?php if( !empty($data['apply_people_count']) ) { echo "已有人报名 不允许修改"; } ?>" <?php if( !empty($data['apply_people_count']) ) { echo "disabled"; } ?> required name='total_people_count' class='form-control' id='total_people_count' value='<?php echo empty($data['total_people_count'])? '100':$data['total_people_count'];  ?>' placeholder='10' /> </li>
		<li class='list-group-item' draggable='false'>
			活动简单描述
			<input type='text' name='description' class='form-control' id='description' value='<?php echo empty($data['description'])? '':$data['description'];  ?>' placeholder='请输入活动简单描述(30字以内)' maxLength="30" />
		</li>
		<li class='list-group-item' draggable='false'>
			活动具体描述
			<!-- 加载编辑器的容器 -->
	        <script id="container" name="content" type="text/plain"><?php echo empty($data['content'])? '活动描述':$data['content'];  ?> </script>
	        <!-- 配置文件 -->
	        <script type="text/javascript" src="/ueditor/ueditor.config.js"></script>
	        <!-- 编辑器源码文件 -->
	        <script type="text/javascript" src="/ueditor/ueditor.all.js"></script>
	        <!-- 实例化编辑器 -->
	        <script type="text/javascript">
	            var ue = UE.getEditor('container');
	        </script>
		</li>
		<li class='list-group-item' draggable='false'>是否隐藏
			<select name="is_hidden" class="form-control">
				<?php foreach ($hidden_status as $hidden_status_key => $hidden_status_val) {?>
					<option value="<?php echo $hidden_status_key; ?>" <?php if(!empty($data['is_hidden']) && $data['is_hidden']==$hidden_status_key) echo "selected" ?> ><?php echo $hidden_status_val; ?></option>
				<?php }?>
			</select>
		</li>

		 <li class='list-group-item' draggable='false'>
			 <button type='submit' id="stopsubmit" class='btn btn-default btn-sm' onclick="return checkDate();">提交</button>
			 <a href="/admin/activitys" class="btn btn-default btn-sm" style="margin-left: 20px;">返回</a>
		 </li>
		 
	 </ul>
 </div><!-- ./smart-widget-inner -->