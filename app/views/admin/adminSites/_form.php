<div class="smart-widget-inner">
		
		<ul class="list-group to-do-list sortable-list no-border">
			<input type='hidden' name='id' value="<?php echo empty($site['id'])? '':$site['id'];  ?>" />
			<li class="list-group-item" draggable="true">
				站点名称(site_name)
				<input type='text' name='site_name' class='form-control' id='siteName' value='<?php echo empty($site['site_name'])? '':$site['site_name'];  ?>' placeholder='请输入站点名称' />
			</li>
			<li class="list-group-item" draggable="true">
				域名(domain)
				<input type='text' name='domain' class='form-control' id='domain' value='<?php echo empty($site['domain'])? '':$site['domain'];  ?>' placeholder='请输入域名' />
			</li>
			<li class="list-group-item" draggable="true">
				关键字(keywords)
				<input type='text' class='form-control' name='keywords' placeholder='请输入文章关键字（多个则以空格隔开）' value='<?php echo empty($site['keywords'])? '': $site['keywords']; ?>' >
			</li>
			<li class="list-group-item" draggable="true">
				站点描述(description)
				<textarea class="form-control" name='description' ><?php echo empty($site['description'])? '': $site['description']; ?></textarea>
			</li>
			<li class="list-group-item" draggable="true">
				版权信息(copyright)
				<input type='text' name='copyright' class='form-control' id='copyright' value='<?php echo empty($site['copyright'])? '':$site['copyright'];  ?>' placeholder='请输入版权信息' />
			</li>
			<li class="list-group-item" draggable="true">
				邮编(postcode)
				<input type='text' name='postcode' class='form-control' id='postcode' value='<?php echo empty($site['postcode'])? '':$site['postcode'];  ?>' placeholder='请输入邮编' />
			</li>
			<li class="list-group-item" draggable="true">
				地址(address)
				<input type='text' name='address' class='form-control' id='address' value='<?php echo empty($site['address'])? '':$site['address'];  ?>' placeholder='请输入地址' />
			</li>
			<li class="list-group-item" draggable="true">
				地址经度(address_lnt)
				<input type='text' name='address_lnt' class='form-control' id='address_lnt' value='<?php echo empty($site['address_lnt'])? '':$site['address_lnt'];  ?>' placeholder='请输入地址经度' />
			</li>
			<li class="list-group-item" draggable="true">
				地址纬度(address_lat)
				<input type='text' name='address_lat' class='form-control' id='address_lat' value='<?php echo empty($site['address_lat'])? '':$site['address_lat'];  ?>' placeholder='请输入地址纬度' />
			</li>
			<li class="list-group-item" draggable="true">
				电话(phone)
				<input type='text' name='phone' class='form-control' id='phone' value='<?php echo empty($site['phone'])? '':$site['phone'];  ?>' placeholder='请输入电话' />
			</li>
			<li class="list-group-item" draggable="true">
				邮箱(email)
				<input type='text' name='email' class='form-control' id='email' value='<?php echo empty($site['email'])? '':$site['email'];  ?>' placeholder='请输入邮箱' />
			</li>
			<li class="list-group-item" draggable="true">
				QQ(qq)
				<input type='text' name='qq' class='form-control' id='qq' value='<?php echo empty($site['qq'])? '':$site['qq'];  ?>' placeholder='请输入QQ' />
			</li>
			<li class="list-group-item" draggable="true">
				LOGO(logo)
				<?php  
					if(!empty($site['logo']))
						echo "<img style='width:80px' src='".$site['logo']."'/>";
				?>
				<input type="file" name="logo" />
			</li>
			
			<li class="list-group-item" draggable="true">
				注册方式(register_type)
				<select name="register_type">
					<option value="0" <?php if($site['register_type']==0) echo "selected";?>>不允许注册</option>
					<option value="1" <?php if($site['register_type']==1) echo "selected";?>>开放注册</option>
					<option value="2" <?php if($site['register_type']==2) echo "selected";?>>邀请码注册</option>
				</select>
			</li>
			
			<li class="list-group-item" draggable="true">
				<button type='submit' class='btn btn-primary btn-sm'>提交</button>
			</li>
		</ul>
	</div><!-- ./smart-widget-inner -->
</div><!-- ./smart-widget -->

