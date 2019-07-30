<!-- 隐藏域 -->
<input type='hidden' name='id' value="<?php echo empty($privilege['id'])? '':$privilege['id'] ?>" >
<ul class="list-group to-do-list sortable-list no-border">
<li class="list-group-item" draggable="false">
	name
	<input type='text' required class='form-control' id='name' name='name' value="<?php echo empty($privilege['name'])? '':$privilege['name'] ?>" placeholder='请填写权限名称' size='32'></td>
</li>
<li class="list-group-item" draggable="false">
	<button type='submit' id="stopsubmit" onclick='return stopSubmit()' class='btn btn-primary btn-sm' >提交</button><!-- return validate(); -->
</li>
</ul>