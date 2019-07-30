<?php include_once('../app/views/admin/_header.php') ?>
 <link rel='stylesheet' type='text/css' href='/css/admin/index.css?1'>
 <div class='main-container'>
 <div class='smart-widget'>
	 <div class='smart-widget-header'>
		 <?php echo $page_title;?>
		 <span class='smart-widget-option'>
			 <a href='#' onclick='location.reload()' class='widget-refresh-option'>
				 <i class='fa fa-refresh'></i>
			 </a>
		 </span>
	 </div>
	 <table id='indexTable' class=''>
		 <thead>
			 <tr class='firstLine'>
				<th>姓名</th>
				<th>手机号</th>
				<th>性别</th>
				<th>报名时间</th>
				<th>操作</th>
			 </tr>
		 </thead>
		 <tbody>
			 <?php for($i=0; $i<count($data); $i++){ ?>
			 <tr class='<?php echo $i%2==0? 'singular':'dual' ?>'>
				<td><?php echo $data[$i]['name'];?></td>
				<td><?php echo $data[$i]['phone'];?></td>
				<td><?php echo $data[$i]['genderText'];?></td>
				<td><?php echo date("Y-m-d H:i:s", $data[$i]['apply_time']);?></td>
				<td>
					<a href="/admin/application/delete?id=<?php echo $data[$i]['id']; ?>&aid=<?php echo $data[$i]['aid']; ?>" onclick="return delNotice(this)" class="btn btn-danger btn-xs">删除报名人</a>
				</td>
			</tr>
			<?php } ?>
		 </tbody>
	 </table>
 </div><!-- ./smart-widget -->
 </div>
 
 <iframe id="idIframe" name="id_iframe" style="display: none;"></iframe>
 <?php include_once('../app/views/admin/_footer.php') ?>
 <script type='text/javascript'>
 $(document).ready(function(){
 });
 </script>
 
 <!-- 删除提醒  -->
 <script >
	function delNotice(obj){
		
		var delurl = $(obj).attr("href");
		
		 layer.confirm('确认要删除吗？删除后不能恢复！', {
			  btn: ['确认','取消'] //按钮 
			}, function(){
				// layer.msg('删除成功', {icon: 1});
				// $("#idIframe").attr("src", delurl);
				// window.location.reload();
				window.location.href = delurl;
				
			},function(index){ // 取消 
				layer.close(index);
				return false;
				 
			}); 

		return false;
	}
		
</script>
 