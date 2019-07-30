	<!-- 隐藏域 -->
	<input type='hidden' name='id' value="<?php echo empty($department['id'])? '':$department['id'];  ?>" />
	<input type='hidden' name='logo' value="<?php echo empty($department['logo'])? '':$department['logo'];  ?>" />


	<div class="smart-widget-inner">
		
		<ul class="list-group to-do-list sortable-list no-border">
			<li class="list-group-item" draggable="false">
				名称
				<input type='text' name='name' <?php if(!empty($department['name'])) echo "readonly"; ?> required class='form-control' id='name' value='<?php echo empty($department['name'])? '':$department['name'];  ?>' placeholder='请输入部门名称' />
			</li>
			<li class="list-group-item" draggable="false">
				选择Logo
				<input type='file' name='logo' class='form-control' />
			</li>

			<li class="list-group-item" draggable="false">
				部门介绍
				<textarea name='content' class='form-control' placeholder="请输入部门介绍"><?php echo empty($department['content'])? '':$department['content'] ?></textarea>
			</li>

			<li class="list-group-item" draggable="false">
				备注
				<textarea name='description' class='form-control' placeholder="请输入备注"><?php echo empty($department['description'])? '':$department['description'] ?></textarea>
			</li>


			<li class="list-group-item" draggable="false">
				所属部门
				<select name="upper_id" class='form-control'>
					
					<option value="0" <?php if($department['upper_id']==0){ echo "selected='selected'";} ?>>无</option>
	
							<?php for ($i=0; $i<count($departmentList); $i++) {?>
								<?php if(empty($department['id'])|| $department['id']!=$departmentList[$i]['id']){?>
									<option value="<?php echo $departmentList[$i]['id']; ?>" <?php if(!empty($department['upper_id']) && $department['upper_id']==$departmentList[$i]['id']) echo "selected"; ?> ><?php echo $departmentList[$i]['name']; ?></option>
								<?php }?>
							<?php }?>
					
				</select>
			</li>
			<li class='list-group-item' draggable='false'>
				主管
				<select name="staff_id" class="form-control">
					<option value='0'>无</option>
					<?php for ($i=0; $i < count($staffs); $i++) { ?>
						<option value="<?php echo $staffs[$i]['id']; ?>" <?php if(!empty($department['staff_id']) && $department['staff_id']==$staffs[$i]['id']) echo "selected" ?> ><?php echo $staffs[$i]['name']; ?></option>
					<?php } ?>
				</select>
			</li>
			<li class="list-group-item" draggable="false">
				<button type='submit' class='btn btn-primary btn-sm'>提交</button>
			</li>

		</ul>
	</div><!-- ./smart-widget-inner -->
</div><!-- ./smart-widget -->

