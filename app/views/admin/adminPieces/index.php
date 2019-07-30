<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">

</style>
<div class='main-container'>

<div class="smart-widget">
	<div class="smart-widget-header">
		<?php echo $page_title;?>
		<span class="smart-widget-option">
			<a href="/admin/piece/add">
				<i class="fa fa-plus"></i>
			</a>
			<a href="#" onclick="location.reload()" class="widget-refresh-option">
				<i class="fa fa-refresh"></i>
			</a>
		</span>
	</div>
	<div class="smart-widget-inner table-responsive">
		<table class="table table-striped no-margin">
			<thead>
				<tr class='firstLine'>
					<th class="text-right" width="1%"></th>
					<th class="text-right" width="1%">Id</th>
					<th class="text-right">说明</th>
					<th class="text-right">类型</th>
					<th class="text-right">内容</th>
					
					<th class="text-right">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php for($i=0; $i<count($data); $i++){ ?>
				<tr>
					<td class="text-right"><input type='checkbox' name='classesIds[]' class='eachNewsClassCheckBox' value="<?php echo $data[$i]['id'] ?>" /></td>
					<td class="text-right"><?php echo ($record_start+$i+1);?></td>
					<td class="text-right"> <?php echo $data[$i]['description'];?> </td>
					<td class="text-right"> <?php echo $data[$i]['type']==1 ? "文本" : ($data[$i]['type']==2?"图片":"代码段"); ?> </td>
					<td class="text-right"> <?php echo $data[$i]['content'] ?> </td>
					
					<td class='text-right operationBox'>
						<a type='button' href="/admin/piece/edit?id=<?php echo $data[$i]['id']; ?>" class='btn btn-info btn-sm'>编辑</a>
						<a type='button' href="/admin/piece/destroy?id=<?php echo $data[$i]['id']; ?>" onclick="return delNotice(this)" class='btn btn-danger btn-sm'>删除</a>
					</td>
				</tr>
				<?php } ?>

			</tbody>
			<?php include_once('../app/views/admin/adminPieces/_tfoot.php') ?>
		</table>
	</div><!-- ./smart-widget-inner -->
</div><!-- ./smart-widget -->


</div>

<iframe id="idIframe" name="id_iframe" style="display: none;"></iframe>
<?php include_once('../app/views/admin/_footer.php') ?>

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
