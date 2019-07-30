<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
	td img {
		display: block;
		width: 100px;
		height: 100px;
		margin: 0 auto;
	}
	thead td,thead th,tbody td,tbody th {
		text-align: center;
	}
</style>
<div class='main-container'>

<div class="smart-widget">
	<div class="smart-widget-header">
		<?php echo $page_title;?>
		<span class="smart-widget-option">
			<a href="/admin/user/create">
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
					<th class=""><input type="checkbox" name="chooseAll" class="allCheckBox chooseAll"></th>
					<th class="">Id</th>
					<th class="">敏感词</th>
					<th class="">更新时间</th>
				</tr>
			</thead>
			<tbody>
				<?php for($i=0; $i<count($data); $i++){ ?>
				<tr>
					<td class="">
						<?php if($data[$i]['id']!=1){?>
						<input type='checkbox' name='classesIds[]' class='eachNewsClassCheckBox' value="<?php echo $data[$i]['id'] ?>" />
						<?php }?>
					</td>
					<td class=""><?php echo $data[$i]['id'];?></td>
					<td class="">
						<textarea readonly=true placeholder="请填写敏感词(空格隔开)"><?php echo $data[$i]['content'];?></textarea>
					</td>
					<td class='operationBox'>
						<button type='button' class='btn btn-info btn-sm'>编辑</button>
						<button type='button' class='btn btn-primary btn-sm nosee'>提交</button>
					</td>
				</tr>
				<?php } ?>

			</tbody>
		</table>
	</div><!-- ./smart-widget-inner -->
</div><!-- ./smart-widget -->


</div>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type='text/javascript' src='/js/myFuncs.js'></script>
<script type="text/javascript">
function showDeleteItems(data)
{
	if(data.code!='001'){
		alert("删除失败");
	} else {
		//alert("删除成功");
		window.location.reload();
	}
}
$(document).ready(function(){

});
</script>