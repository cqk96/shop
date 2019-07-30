<link href="/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

<script type="text/javascript" src="/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="/js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
	<div class="smart-widget-inner">
		
		<ul class="list-group to-do-list sortable-list no-border">
			<input type='hidden' name='id' value="<?php echo empty($data['id'])? '':$data['id'];  ?>" />
			<li class="list-group-item" draggable="true">
				兑换码
				<input type='text' name='code' class='form-control' id='text' value='<?php echo empty($data['code'])? '':$data['code'];  ?>' placeholder='请输入兑换码' />
			</li>
			<li class="list-group-item" draggable="true">
				类型
				<select name="type">
					<option value="1" <?php if($data["type"]==1) echo "selected";?>>网站账号注册邀请码</option>
					<option value="0" <?php if($data["type"]==0) echo "selected";?>>其他</option>
				</select>
			</li>
			<li class="list-group-item" draggable="true">
				状态
				<select name="status">
					<option value="0" <?php if($data["status"]==0) echo "selected";?>>未启用</option>
					<option value="1" <?php if($data["status"]==1) echo "selected";?>>未使用</option>
					<option value="2" <?php if($data["status"]==2) echo "selected";?>>已使用</option>
					<option value="3" <?php if($data["status"]==3) echo "selected";?>>无效</option>
				</select>
			</li>
			<li class="list-group-item" draggable="true">
				过期时间
				<div class="input-group date form_datetime col-md-5" data-date="<?php echo date("Y-m-d H:i:s",time());?>" data-date-format="yyyy-mm-dd HH:ii" data-link-field="dtp_input1">
                    <input class="form-control" size="16" name="expire_datetime" type="text" value="<?php echo empty($data['expire_datetime'])? '':$data['expire_datetime'];  ?>" readonly>
					<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                </div>
				<input type="hidden" id="dtp_input1" value="" /><br/>
			</li>
			<script type="text/javascript">
				$('.form_datetime').datetimepicker({
					language:  'zh',
					weekStart: 1,
					todayBtn:  1,
					autoclose: 1,
					todayHighlight: 1,
					startView: 2,
					forceParse: 0,
					showMeridian: 1
				});
			</script>
			
			<li class="list-group-item" draggable="true">
				<button type='submit' class='btn btn-primary btn-sm'>提交</button>
			</li>
		</ul>
	</div><!-- ./smart-widget-inner -->
</div><!-- ./smart-widget -->


