<!-- 隐藏域 -->
<input type='hidden' name='id' value="<?php echo empty($role['id'])? '':$role['id'] ?>" >
<ul class="list-group to-do-list sortable-list no-border">
<li class="list-group-item" draggable="false">
	Name
	<input type='text' required class='form-control' id='name' name='name' value="<?php echo empty($role['name'])? '':$role['name'] ?>" placeholder='请填写角色名称' size='32'></td>
</li>
<li class="list-group-item" draggable="false">
	Description
	<input type='text' required class='form-control' id='description' name='description' value="<?php echo empty($role['description'])? '':$role['description'] ?>" placeholder='请填写角色描述(简短)'></td>
</li>
<li class="list-group-item" draggable="false">
	<button type='submit' id="stopsubmit" onclick='return stopSubmit()' class='btn btn-primary btn-sm'>提交</button><!-- return validate(); -->
</li>
</ul>