<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
<input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
<div class='smart-widget-inner'>
 	<ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>
			应用名称<input type='text' name='name' required class='form-control' id='name' value='<?php echo empty($data['name'])? '应用名称':$data['name'];  ?>' placeholder='请输入应用名称' />
		</li>
		<li class='list-group-item' draggable='false'>
			版本号
			<input type='text' maxLength="3" name='version_code' required class='form-control' id='version_code' value='<?php echo empty($data['version_code'])? $maxVersion:$data['version_code'];  ?>' placeholder='请输入版本号' onkeyup="this.value=this.value.replace(/\D/g,'') " onafterpaste="this.value=this.value.replace(/\D/g,'') " />
		</li>
		<li class='list-group-item' draggable='false'>
			用户版本号
			<input type='text' maxLength="11" name='version_text' required class='form-control' id='version_text' value='<?php echo empty($data['version_text'])? '':$data['version_text'];  ?>' placeholder='请输入用户版本号' />
		</li>
		<li class='list-group-item' draggable='false'>
			<input id="apkFile" type="file" class="form-control" onchange="checkType(this)" name="file" <?php echo empty($data['apk_url'])? 'required':'';  ?> />
		</li>
		<li class='list-group-item' draggable='false'>
			描述
			<textarea rows="8" class="form-control" name='description'><?php echo empty($data['description'])? '':$data['description'];  ?></textarea>
		</li>
	
	 	<li class='list-group-item' draggable='false'>
		 	<button type='submit' class='btn btn-default btn-sm' onclick="return checkEmpty()">提交</button>
		 	<a href="/admin/manageApps" class="btn btn-default btn-sm a-link">返回</a>
		 </li>
 	</ul>
</div><!-- ./smart-widget-inner -->