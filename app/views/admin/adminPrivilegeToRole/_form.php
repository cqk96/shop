<!-- 隐藏域 -->

<ul class="list-group to-do-list sortable-list no-border">
<li class="list-group-item" draggable="false">
	角色
	<select name="role_id" class="form-control">
		<?php for($i=0; $i<count($roles); $i++){?>
		<option value="<?php echo $roles[$i]['id']; ?>" <?php if(!empty($rid) && $rid==$roles[$i]['id']) echo "selected"; ?>><?php echo $roles[$i]['name']; ?></option>
		<?php }?>
	</select>
</li>
<li class="list-group-item" draggable="false">
	权限<br />
	<?php for($i=0; $i<count($privileges); $i++){?>
	<label class="checkbox-inline">
	  <input type="checkbox" name="privilege_ids[]" id="" value="<?php echo $privileges[$i]['id']; ?>" <?php if(!empty($rolePrivileges) && in_array($privileges[$i]['id'], $rolePrivileges)) echo "checked"; ?> > <?php echo $privileges[$i]['name']; ?>
	</label>
	<?php }?>
</li>
<li class="list-group-item" draggable="false">
	<button type='submit' id="stopsubmit" onclick='return stopSubmit()' class='btn btn-primary btn-sm'>提交</button><!-- return validate(); -->
</li>
</ul>