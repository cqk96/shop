<?php include_once('../app/views/admin/_header.php') ?>
 <link rel='stylesheet' type='text/css' href='/css/admin/index.css'>
<style type="text/css">
.allCheckBox {
	margin-left: 15px;
}
.operate-box ul{
	margin-top: 15px;
}
.bottom-bar-box{
	margin-top: 10px;
	margin-bottom: 10px;
}
.bottom-bar-box .operate-btn{
	margin-right: 27px;
}
.bottom-bar-box .first-operate-btn{
	margin-left: 15px;	
}
#goForm  .btn-xs{
	line-height: inherit;
	vertical-align: inherit;
}
.page-pagination {
	float: right;
	margin-bottom: inherit;
}
</style>
 <div class='main-container'>
 <div class='smart-widget'>
	 <div class='smart-widget-header'>
		 <?php echo $page_title;?>
		<span class='smart-widget-option'>
			 <a href='/admin/carouselImgs/create'>
				 <i class='fa fa-plus'></i>
			 </a>
			 <a href='#' onclick='location.reload()' class='widget-refresh-option'>
				 <i class='fa fa-refresh'></i>
			 </a>
		 </span>
		
	 </div>
	 <table id='indexTable' class=''>
		 <thead>
			 <tr class='firstLine'>
				 <th class=''><input type='checkbox' name='chooseAll' class='allCheckBox chooseAll'></th>
				 <th>ID</th>
				<th>封面</th>
				<th>排序</th>
				<th>是否隐藏</th>
				<th>更新时间</th>
				 <th class='' colspan=2>操作</th>
			 </tr>
		 </thead>
		 <tbody>
			 <?php for($i=0; $i<count($data); $i++){ ?>
			 <tr class='<?php echo $i%2==0? 'singular':'dual' ?>'>
				<td class=''>
					<input type='checkbox' name='classesIds[]' class='eachNewsClassCheckBox' value='<?php echo $data[$i]['id'] ?>' /></td>
				<td><?php echo $data[$i]['id'];?></td>
				<td><img src="<?php echo $data[$i]['cover'];?>"></td>
				<td><?php echo $data[$i]['order_index'];?></td>
				<td><?php echo $hidden_status[$data[$i]['is_hidden']];?></td>
				<td><?php echo empty($data[$i]['create_time'])? '':date("Y-m-d H:i:s", $data[$i]['create_time']);?></td>
				<td><?php echo empty($data[$i]['update_time'])? '':date("Y-m-d H:i:s", $data[$i]['update_time']);?></td>
				
				 <td class='operationBox' colspan=2>
					 <a href='/admin/carouselImgs/update?id=<?php echo $data[$i]['id']; ?>' ><span class='icon-img'><img src='/images/edit-icon.png' /></span>编辑</a>
					 <a type='button' href='/admin/carouselImgs/doDelete?id=<?php echo $data[$i]['id']; ?>' onclick='return delNotice(this)'><span class='icon-img'><img src='/images/delete-icon.png'></span>删除</a>
				 </td>
			 </tr>
			 <?php } ?>
		 </tbody>
	 </table>

	 <div class="bottom-bar-box">
		
		<a href='javascript:void(0);' style='' class='allCheckBox btn btn-default btn-xs operate-btn first-operate-btn'>全选</a>
		<a href='javascript:void(0);' class='deleteChooseBtn btn btn-danger btn-xs operate-btn'>删除</a>
		
		<?php echo $pageObj->pagination; ?>
		
	</div>

 </div><!-- ./smart-widget -->
 </div>
 
 <iframe id="idIframe" name="id_iframe" style="display: none;"></iframe>
 <?php include_once('../app/views/admin/_footer.php') ?>
 <script type='text/javascript' src='/js/myFuncs.js'></script> 
 <script type='text/javascript' src='/js/date-picker/jquery-ui.min.js'></script> 
 <script type='text/javascript'>
 function showDeleteItems(data)
 {
	 if(!data.success){alert('删除失败'); }
	else {window.location.reload(); }
	 }
 $(document).ready(function(){
	 //全选checkbox
	 var allCheckBoxBtnClick = 1;
	 $('.allCheckBox').click(function(){
		 if(allCheckBoxBtnClick%2!=0){
			 $('.eachNewsClassCheckBox').each(function(){
				 $(this).prop('checked',true);
			 });
		 } else{
			 $('.eachNewsClassCheckBox').each(function(){
				 $(this).prop('checked',false);
			 });
		 }
		 allCheckBoxBtnClick++;
	 });
	 //删除选择的项目
	 $('.deleteChooseBtn').click(function(){
		 deleteChooseItems('eachNewsClassCheckBox','你确定要删除选定项吗？','/admin/carouselImgs/destroy','','showDeleteItems','');
	 });
	//日期选择
	$( '#startTime' ).datepicker({dateFormat:'yy-mm-dd'});
	$( '#endTime' ).datepicker({dateFormat:'yy-mm-dd'});
 });
 </script>
 
 <!-- 删除提醒  -->
 <script >
	function delNotice(obj){
		
		var delurl = $(obj).attr("href");
		
		 layer.confirm('确认要删除吗？删除后不能恢复！', {
			  btn: ['确认','取消'] //按钮 
			}, function(){
				layer.msg('删除成功', {icon: 1});
				$("#idIframe").attr("src", delurl);
				window.location.reload();
				
			},function(index){ // 取消 
				layer.close(index);
				return false;
				 
			}); 

		return false;
	}	
</script>
 