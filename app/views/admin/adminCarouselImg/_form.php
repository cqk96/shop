<input type='hidden' name='id' value='<?php echo empty($data['id'])? '':$data['id'];  ?>' />
 <input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
 <div class='smart-widget-inner'>
	 <ul class='list-group to-do-list sortable-list no-border'>
		<li class='list-group-item' draggable='false'>
			选择封面
			<input type='file' name='cover' <?php if(isset($isCreate)) { echo "required"; } ?> class='form-control' id='cover' accept="image/*"/>
		</li>
		<!-- <li class='list-group-item' draggable='false'>
			标题
			<input type='text' name='title' required class='form-control' id='title' value='<?php echo empty($data['title'])? '':$data['title'];  ?>' placeholder='请输入标题' />
		</li> -->
		<li class='list-group-item' draggable='false' >
			<div class="layui-form-item">
				链接文章
				<!-- <input type='text' name='url'  class='form-control' id='url' value='<?php echo empty($data['url'])? '':$data['url'];  ?>' placeholder='请输入链接全地址' /> -->
				<select name="url" class="form-control" lay-search>
					<option value="0">请选择</option>
					<?php for ($i=0; $i < count($news); $i++) { ?>
					<option value="/front/showNews?id=<?php echo $news[$i]['id']; ?>" <?php if( !empty($data['url']) && $data['url']==( "/front/showNews?id=" . $news[$i]['id'] ) ) { echo "selected"; } ?> ><?php echo $news[$i]['title']; ?></option>
					<?php } ?>
				</select>
			</div>
		</li>
		<li class='list-group-item' draggable='false'>
			排序
			<input type='text' name='order_index'  class='form-control' id='order_index' value='<?php echo empty($data['order_index'])? '':$data['order_index'];  ?>' placeholder='请输入排序' />
		</li>
		<li class='list-group-item' draggable='false'>
			是否隐藏
			<select name="is_hidden" class="form-control">
				<?php foreach ($hidden_status as $hidden_status_key => $hidden_status_val) {?>
					<option value="<?php echo $hidden_status_key; ?>" <?php if(!empty($data['is_hidden']) && $data['is_hidden']==$hidden_status_key) echo "selected" ?> ><?php echo $hidden_status_val; ?></option>
				<?php }?>
			</select>
		</li>
		
		 <li class='list-group-item' draggable='false'>
			 <button type='submit' id="stopsubmit" onclick="return stopSubmit()" class='btn btn-default btn-sm'>提交</button>
			 <a href="/admin/carouselImgs" class="btn btn-default btn-sm">返回</a>
		 </li>
	 </ul>
 </div><!-- ./smart-widget-inner -->