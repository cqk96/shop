	<style type="text/css">
	textarea { resize: none; }
	.layui-form-label {width: auto; } 
	.experience-item-create-box {
		width: 100%;
		margin-bottom: 10px;
	}
	.create-experience-btn {
		margin-left: 30px;
	}
	.remove-experience-btn:hover{
		cursor: pointer;
	}
	.relative-item {
		position: relative;
	}
	.date-choose-img:hover {
		cursor: pointer;
	}
	.date-choose-img {
		position: absolute;
	    top: 10px;
	    right: 10px;
	}
	/*.date-time-item {display: block; }*/
	</style>
	<!-- 隐藏域 -->
	<input type='hidden' name='id' value="<?php echo empty($user['id'])? '':$user['id'];  ?>" />
	<input type='hidden' name='avatar' value="<?php echo empty($user['avatar'])? '':$user['avatar'];  ?>" />


	<div class="smart-widget-inner">
		
		<ul class="list-group to-do-list sortable-list no-border">

			<li class="list-group-item" draggable="false">
				用户账号
				<input maxLength="11" type='text' name='user_login' <?php if(!empty($user['user_login'])) echo "readonly"; ?> required class='form-control' id='user_login' value='<?php echo empty($user['user_login'])? '':$user['user_login'];  ?>' placeholder='请输入用户账号' />
			</li>

			<!-- 额外增加字段 -->
			<li class="list-group-item" draggable="false">
				手机号
				<input type='text' maxLength="11" name='phone'  class='form-control' id='phone' value='<?php echo empty($user['phone'])? '':$user['phone'];  ?>' placeholder='请输入手机号' />
			</li>
			
			<li class="list-group-item" draggable="false">
				姓名
				<input type='text' maxLength="50" name='name'  class='form-control' id='name' value='<?php echo empty($user['name'])? '':$user['name'];  ?>' placeholder='请输入姓名' />
			</li>
			
			
			<li class="list-group-item" draggable="false">
				民族
				<input maxLength="20" type='text' name='ethnicity'  class='form-control' id='ethnicity' value='<?php echo empty($user['ethnicity'])? '':$user['ethnicity'];  ?>' placeholder='请输入民族' />
			</li>

			<li class="list-group-item" draggable="false">
				籍贯
				<input maxLength="50" type='text' name='native_place'  class='form-control' id='native_place' value='<?php echo empty($user['native_place'])? '':$user['native_place'];  ?>' placeholder='请输入籍贯' />
			</li>

			<li class="list-group-item" draggable="false">
				政治面貌
				<select name="political" class="form-control">
					<option value='0' <?php if(!empty($user['political']) && $user['political']==0 ) { echo "selected"; } ?> >无</option>
					<option value='1' <?php if(!empty($user['political']) && $user['political']==1 ) { echo "selected"; } ?> >团员</option>
					<option value='2' <?php if(!empty($user['political']) && $user['political']==2 ) { echo "selected"; } ?> >预备党员</option>
					<option value='3' <?php if(!empty($user['political']) && $user['political']==3 ) { echo "selected"; } ?> >党员</option>
				</select>
			</li>

			<li class="list-group-item" draggable="false">
				入党(团)时间
				<input type='text' name='join_time'  class='form-control' id='joinTime' value='<?php echo empty($user['join_time'])? '':$user['join_time'];  ?>' placeholder='请输入 入党(团)时间' />
			</li>

			<li class="list-group-item" draggable="false">
				毕业院校
				<input type='text' maxLength="30" name='university'  class='form-control' id='university' value='<?php echo empty($user['university'])? '':$user['university'];  ?>' placeholder='请输入 毕业院校' />
			</li>

			<li class="list-group-item" draggable="false">
				所学专业
				<input type='text' maxLength="12" name='major'  class='form-control' id='major' value='<?php echo empty($user['major'])? '':$user['major'];  ?>' placeholder='请输入 所学专业' />
			</li>

			<li class="list-group-item" draggable="false">
				学历
				<select name="education" class="form-control">
					<option value='0' <?php if(!empty($user['education']) && $user['education']==0 ) { echo "selected"; } ?> >无</option>
					<option value='1' <?php if(!empty($user['education']) && $user['education']==1 ) { echo "selected"; } ?> >博士</option>
					<option value='2' <?php if(!empty($user['education']) && $user['education']==2 ) { echo "selected"; } ?> >硕士</option>
					<option value='3' <?php if(!empty($user['education']) && $user['education']==3 ) { echo "selected"; } ?> >本科</option>
					<option value='4' <?php if(!empty($user['education']) && $user['education']==4 ) { echo "selected"; } ?> >专科</option>
					<option value='5' <?php if(!empty($user['education']) && $user['education']==5 ) { echo "selected"; } ?> >高中</option>
					<option value='6' <?php if(!empty($user['education']) && $user['education']==6 ) { echo "selected"; } ?> >初中</option>
				</select>
			</li>

			<li class="list-group-item" draggable="false">
				家庭住址
				<input type='text' maxLength="50" name='address'  class='form-control' id='address' value='<?php echo empty($user['address'])? '':$user['address'];  ?>' placeholder='请输入 家庭住址' />
			</li>

			<li class="list-group-item" draggable="false">
				<div class="layui-form-item">
	    			<label class="layui-form-label">工作年限</label>
	    				<div class="layui-input-inline">
	      				<input type="text" name="working_life_time" maxLength="4" value='<?php echo empty($user['working_life_time'])? '':$user['working_life_time'];  ?>'  placeholder="请输入工作年限" autocomplete="off" class="layui-input" onkeyup="value=value.replace(/[^\d\.]/g,'')" >
	    			</div>
	    			<div class="layui-form-mid layui-word-aux">年</div>
	  			</div>
  			</li>

			<!-- end 额外增加字段  -->

			<li class="list-group-item" draggable="false">
				选择用户头像
				<input type='file' name='userAvatar' class='form-control' />
			</li>

			<li class="list-group-item" draggable="false">
				用户昵称
				<input type='text' maxLength="10" name='nickname' class='form-control' value="<?php echo empty($user['nickname'])? '':$user['nickname'];  ?>" placeholder="请输入用户昵称(10字内)" />
			</li>

			<li class="list-group-item" draggable="false">
				<div class="layui-form-item gender-item">
					<label class="layui-form-label">性别</label>
				    <div class="layui-input-block">
				    	<?php foreach ($gender as $gender_key => $gender_val) {?>
				    	<input type='radio' name='gender' value="<?php echo $gender_key ?>" title="<?php echo $gender_val ?>" <?php if(!empty($user['gender']) && ($user['gender']==$gender_key)) echo "checked"; ?> />
				    	<?php }?>
				    </div>
				</div>
				
			</li>

			<li class="list-group-item" draggable="false">
				用户年龄
				<div class="input-group" style='width:30%'>
					<input type="text" name="age" maxLength="3" class="form-control" oninput="InputNumber(this,3,'+')" value="<?php echo empty($user['age'])? '':$user['age'];  ?>" placeholder="请输入用户年龄">
					<span class="input-group-addon">岁</span>
				</div>
			</li>

			<li class="list-group-item" draggable="false">
				出生年月
				<input type='text' name='birthday'  class='form-control' id='birthdayTime' value='<?php echo empty($user['birthday'])? '':date("Y-m-d", $user['birthday']);  ?>' placeholder='请输入 出生年月' />
			</li>

			<li class="list-group-item" draggable="false">
				用户介绍
				<textarea name='introduce' class='form-control' placeholder="请输入用户介绍"><?php echo empty($user['introduce'])? '':$user['introduce'] ?></textarea>
			</li>

			<li class="list-group-item" draggable="false">
				<div class="layui-form-item gender-item">
					<label class="layui-form-label">角色</label>
				    <div class="layui-input-block">
				    	<?php for ($i=0; $i < count($roles) ; $i++) { ?>
							<input type='checkbox' name='roles[]' value="<?php echo $roles[$i]['id']; ?>" title="<?php echo $roles[$i]['name']; ?>" <?php if(!empty($userRoles) && in_array($roles[$i]['id'], $userRoles) ) { echo "checked"; } ?> />
						<?php } ?>
				    </div>
				</div>

			</li>
			
			<li class="list-group-item" draggable="false">
				<button type='submit'  id="stopsubmit" class='btn btn-primary btn-sm' onclick="return stopSubmit()">提交</button>
			</li>

		</ul>
	</div><!-- ./smart-widget-inner -->
</div><!-- ./smart-widget -->

