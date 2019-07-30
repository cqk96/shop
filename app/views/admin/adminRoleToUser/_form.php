<!-- 隐藏域 -->

<ul class="list-group to-do-list sortable-list no-border">
<li class="list-group-item" draggable="false">
	用户
	<select name="user_id" class="form-control">
		<?php for($i=0; $i<count($users); $i++){?>
		<option value="<?php echo $users[$i]['id']; ?>" <?php if(!empty($uid) && $uid==$users[$i]['id']) echo "selected"; ?>><?php echo $users[$i]['user_login']; ?></option>
		<?php }?>
	</select>
</li>
<li class="list-group-item" draggable="false">
	角色<br />
	<?php for($i=0; $i<count($roles); $i++){?>
	<label class="checkbox-inline">
	  <input type="checkbox" name="role_ids[]" id="" value="<?php echo $roles[$i]['id']; ?>" <?php if(!empty($userRoles) && in_array($roles[$i]['id'], $userRoles)) echo "checked"; ?> > <?php echo $roles[$i]['name']; ?>
	</label>
	<?php }?>
</li>
<li class="list-group-item" draggable="false">
	<button type='submit' id="stopsubmit" onclick='return stopSubmit()' class='btn btn-primary btn-sm'>提交</button><!-- return validate(); -->
</li>
</ul>