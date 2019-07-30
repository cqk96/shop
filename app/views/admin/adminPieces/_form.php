
	<div class="smart-widget-inner">
		
		<ul class="list-group to-do-list sortable-list no-border">
			<input type='hidden' name='id' value="<?php echo empty($data['id'])? '':$data['id'];  ?>" />
			<li class="list-group-item" draggable="false">
				说明
				<input type='text' name='description' class='form-control' id='description' value='<?php echo empty($data['description'])? '':$data['description'];  ?>' placeholder='请输入说明' />
			</li>
			<li class="list-group-item" draggable="false">
				类型
				<select name='type' id='typeSelect'>
					<option value='1' <?php if($data['type']==1){echo 'selected';}?>>文本</option>
					<option value='2' <?php if($data['type']==2){echo 'selected';}?>>图片</option>
					<option value='3' <?php if($data['type']==3){echo 'selected';}?>>代码段</option>
				</select>
			</li>
			<li class="list-group-item" draggable="false">
				内容
				<textarea id='formContent' name='content' onkeyup="ResizeTextarea()" style="width:210px;" <?php if($data['type']==2){ echo 'style="display:none"';}?>><?php echo $data['content'];?></textarea>
				<input type="file" name='pictures[]' id='picture' <?php if($data['type']!=2){ echo 'style="display:none"';}?>>
			</li>
			
			<li class="list-group-item" draggable="false">
				<button type='submit' id="stopsubmit" onclick="return stopSubmit()"  class='btn btn-primary btn-sm'>提交</button>
			</li>
		</ul>
	</div><!-- ./smart-widget-inner -->
</div><!-- ./smart-widget -->

<!-- 类型切换 -->
<script>
	$(document).ready(function(){
		$('#typeSelect').change(function(){
			var value = $(this).children('option:selected').val(); 
			if(value == 2){
				$("#picture").show();
				$("#formContent").hide();
			}else{
				$("#formContent").show();
				$("#picture").hide();
			}
		});
	});
	
</script>
<script type="text/javascript">
// 最小高度 
var minRows = 2; 
// 最大高度，超过则出现滚动条 
var maxRows = 12; 
function ResizeTextarea(){ 
var t = document.getElementById('formContent'); 
if (t.scrollTop == 0) t.scrollTop=1; 
while (t.scrollTop == 0){ 
if (t.rows > minRows) 
t.rows--; 
else 
break; 
t.scrollTop = 1; 
if (t.rows < maxRows) 
t.style.overflowY = "hidden"; 
if (t.scrollTop > 0){ 
t.rows++; 
break; 
} 
} 
while(t.scrollTop > 0){ 
if (t.rows < maxRows){ 
t.rows++; 
if (t.scrollTop == 0) t.scrollTop=1; 
} 
else{ 
t.style.overflowY = "auto"; 
break; 
} 
} 
} 
</script>


