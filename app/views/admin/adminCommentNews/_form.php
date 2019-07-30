	<!-- 隐藏域 -->
	<input type='hidden' name='id' value="<?php echo empty($user['id'])? '':$user['id'];  ?>" />
	<input type='hidden' name='avatar' value="<?php echo empty($user['avatar'])? '':$user['avatar'];  ?>" />


	<div class="smart-widget-inner">
		
		<ul class="list-group to-do-list sortable-list no-border">
			<li class="list-group-item" draggable="false">
				用户账号
				<input type='text' name='user_login' required class='form-control' id='user_login' value='<?php echo empty($user['user_login'])? '':$user['user_login'];  ?>' placeholder='请输入用户账号' />
			</li>
			<li class="list-group-item" draggable="false">
				选择用户头像
				<input type='file' name='userAvatar' class='form-control' />
			</li>

			<li class="list-group-item" draggable="false">
				用户昵称
				<input type='text' name='nickname' class='form-control' value="<?php echo empty($user['nickname'])? '':$user['nickname'];  ?>" placeholder="请输入用户昵称" />
			</li>

			<li class="list-group-item" draggable="false">
				用户性别
				<?php foreach ($gender as $gender_key => $gender_val) {?>
				<label class='control-label'><?php echo $gender_val; ?></label><input type='radio' name='gender' value="<?php echo $gender_key ?>" <?php if(!empty($user['gender']) && ($user['gender']==$gender_key)) echo "checked"; ?> />
				<?php }?>
			</li>

			<li class="list-group-item" draggable="false">
				用户年龄
				<div class="input-group" style='width:30%'>
					<input type="text" name="age" class="form-control" oninput="InputNumber(this,3,'+')" value="<?php echo empty($user['age'])? '':$user['age'];  ?>" placeholder="请输入用户年龄">
					<span class="input-group-addon">岁</span>
				</div>
			</li>

			<li class="list-group-item" draggable="false">
				用户介绍
				<textarea name='introduce' class='form-control' placeholder="请输入用户介绍"><?php echo empty($user['introduce'])? '':$user['introduce'] ?></textarea>
			</li>

			<li class="list-group-item" draggable="false">
				选择用户类型
				<select name="user_type" class='form-control'>
					<?php foreach ($userTypes as $userTypes_key => $userTypes_val) {?>
						<option value="<?php echo $userTypes_key ?>" <?php if(!empty($user['user_type']) && $user['user_type']==$userTypes_key) echo "selected"; ?> ><?php echo $userTypes_val ?></option>
					<?php }?>
				</select>
			</li>
			<li class="list-group-item" draggable="false">
				user_level
				<select name="user_level" class='form-control'>
					<?php if(count($userLevel)==0){?>
					<option value="0">空</option>
					<?php }else {?>
							<?php for ($i=0; $i<count($userLevel); $i++) {?>
								<option value="<?php echo $userLevel[$i]['id']; ?>" <?php if(!empty($user['user_level']) && $user['user_level']==$userLevel[$i]['id']) echo "selected"; ?> ><?php echo $userLevel[$i]['name'].'-'.$userLevel[$i]['level_number'].'级'; ?></option>
							<?php }?>
					<?php }?>
				</select>
			</li>
			
			<li class="list-group-item" draggable="false">
				<button type='submit' class='btn btn-primary btn-sm'>提交</button>
			</li>

		</ul>
	</div><!-- ./smart-widget-inner -->
</div><!-- ./smart-widget -->

