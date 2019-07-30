<!-- 隐藏域 -->
<input type='hidden' name='id' value="<?php echo empty($api['id'])? '':$api['id'] ?>" >
<ul class="list-group to-do-list sortable-list no-border">
<li class="list-group-item" draggable="false">
	选择项目
	<select name="project_id" class="form-control">
		<?php for($i=0; $i<count($projects); $i++){ ?>
			<option value="<?php echo $projects[$i]['id'] ?>" <?php if(!empty($api['project_id']) && $api['project_id']==$projects[$i]['id']) echo "selected"; ?> ><?php echo $projects[$i]['name'] ?></option>
		<?php } ?>
	</select>
</li>
<li class="list-group-item" draggable="false">
	接口地址
	<input type='text' required class='form-control' id='url' name='url' placeholder='请填写接口' value="<?php echo empty($api['url'])? '':$api['url'] ?>" /></td>
</li>
<li class="list-group-item" draggable="false">
	接口描述
	<input type='text' required class='form-control' id='description' name='description' placeholder='请填写接口描述' value="<?php echo empty($api['description'])? '':$api['description'] ?>" /></td>
</li>
<li class="list-group-item" draggable="false">
	选择http方法
	<select name="method" class='form-control'>
		<option value="GET" <?php if(!empty($api['method']) && $api['method']=='GET') echo "selected"; ?> >GET</option>
		<option value="POST" <?php if(!empty($api['method']) && $api['method']=="POST") echo "selected"; ?> >POST</option>
		<option value="PUT" <?php if(!empty($api['method']) && $api['method']=="PUT") echo "selected"; ?> >PUT</option>
		<option value="HEAD" <?php if(!empty($api['method']) && $api['method']=="HEAD") echo "selected"; ?> >HEAD</option>
		<option value="DELETE" <?php if(!empty($api['method']) && $api['method']=='DELETE') echo "selected"; ?> >DELETE</option>
	</select>
</li>
<li class="list-group-item" draggable="false">
	选择协议
	<select name="http_protocal" class='form-control'>
		<option value="HTTP" <?php if(!empty($api['http_protocal']) && $api['http_protocal']=='HTTP') echo "selected"; ?> >HTTP</option>
		<option value="HTTPS" <?php if(!empty($api['http_protocal']) && $api['http_protocal']=="HTTPS") echo "selected"; ?> >HTTPS</option>
	</select>
</li>
<li class="list-group-item" draggable="false">
	<div class="smart-widget">
		<div class="smart-widget-header">
			Params
			<span class="smart-widget-option">
				<a href="javascript:void(0);" class="addParamGroup">
					<i class="fa fa-plus"></i>
				</a>
			</span>
		</div>
		<div class="smart-widget-inner params-from">
			<?php for($i=0; $i<count($params_arr); $i++){?>
			<div class="form-group param-box">
				<label class="col-lg-2 control-label"></label>
				<div class="col-lg-12">
					<div class="row">
						<?php
						$names = 'keys[]';
						$type = 'text';
						$value_names = 'values[]';
						$showText = 'Text';
						$real_value = $params_arr[$i]['value'];
						$fileInputChangeFunc = '';
						if($params_arr[$i]['type']=='file'){
							$names = '';
							$type = 'file';
							$value_names = $params_arr[$i]['key'];
							$showText = 'File';
							$real_value = '';
							$fileInputChangeFunc = 'changeFileInput(this)';
						}
						?>
						<div class="col-md-offset-1 col-md-3">
							<input type="text" name="<?php echo $names; ?>" oninput="<?php echo $fileInputChangeFunc; ?>" onpropertychange="<?php echo $fileInputChangeFunc; ?>" required class="form-control keyInput " placeholder="Key" value="<?php echo $params_arr[$i]['key'] ?>">
						</div><!-- /.col -->
						<div class="col-md-offset-1 col-md-3">
							<input type='<?php echo $type; ?>' name="<?php echo $value_names; ?>" class='form-control changeInputType' placeholder='Value' value="<?php echo $real_value; ?>">
						</div><!-- /.col -->
						<div class="col-md-4 input-group">
							<div class="input-group-btn">
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="showInlineText"><?php echo $showText; ?> </span><span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="javascript:void(0);"  onclick='changeInputType(this)' input-type="text" class="typeText">Text</a></li>
									<li><a href="javascript:void(0);"  onclick='changeInputType(this)' input-type="file" class="typeText">File</a></li>
								</ul>
							</div>
							<label class="control-label">
								<a class="removeTag-a" href="javascript:void(0);" onclick="removeParamGroup(this)"><i class="glyphicon glyphicon-remove"></i></a>
							</label>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.col -->
			</div>
			<?php }?>
		</div>
	</div>
</li>
<li class="list-group-item" draggable="false">
	<?php if(count($projects)!=0){ ?>
	<button type='submit' onclick='' class='btn btn-primary btn-sm'>提交</button>
	<?php }?>
</li>
</ul>