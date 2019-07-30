<!-- 隐藏域 -->
<input type='hidden' name='id' value="<?php echo empty($menu['id'])? '':$menu['id'] ?>" >

<ul class="list-group to-do-list sortable-list no-border">
<li class="list-group-item" draggable="false">
	Name
	<input type='text' required class='form-control' id='name' name='name' value="<?php echo empty($menu['name'])? '':$menu['name'] ?>" placeholder='请填写导航名称' size='32'></td>
</li>
<li class="list-group-item" draggable="false">
	Url
	<textarea type='text' class='form-control' id='url' name='url' placeholder='请填写导航地址'><?php echo empty($menu['url'])? '':$menu['url'] ?></textarea>
</li>
<li class="list-group-item" draggable="false">
	Show
	<select name="show" class='form-control'>
		<option value="0" <?php if(!empty($menu['show']) && $menu['show']==0) echo 'selected'; ?>>否</option>
		<option value="1" <?php if(!empty($menu['show']) && $menu['show']==1) echo 'selected'; ?> >是</option>
		
	</select>
</li>

<li class="list-group-item" draggable="false">
	一级菜单
	<select name="pNav" class="pNav form-control">
		<option value="1" <?php if(!empty($menu['parentid']) && $menu['parentid']==0) echo 'selected'; ?> >是</option>
		<option value="0" <?php if(!empty($menu['parentid']) && $menu['parentid']!=0) echo 'selected'; ?>>否</option>
	</select>
</li>

<li class="list-group-item menuBox" draggable="false">
	选择上级菜单
	<select name="parentid" class='form-control'>
		<?php for ($i=0; $i < count($menus); $i++) { ?>
		<option value="<?php echo $menus[$i]['id'] ?>" <?php if(!empty($menu['parentid']) && $menu['parentid']==$menus[$i]['id']) echo 'selected'; ?>><?php echo $menus[$i]['name'] ?></option>
		<?php }?>
	</select>
</li>

<li class="list-group-item" draggable="false">
	<button type='submit' id="stopsubmit" onclick='return stopSubmit()' class='btn btn-primary btn-sm'>提交</button><!-- return validate(); -->
</li>
</ul>