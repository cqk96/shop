<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
	.row {
		word-break: break-all;
	}
</style>
<div class='main-container'>
<div class="smart-widget">
	<div class="smart-widget-header">
		<?php echo empty($page_title)? '':$page_title;?>
		<span class="smart-widget-option">
			<a href="/admin/api/create">
				<i class="fa fa-plus"></i>
			</a>
			<a href="#" onclick="location.reload()" class="widget-refresh-option">
				<i class="fa fa-refresh"></i>
			</a>
		</span>
	</div>
<div class="smart-widget-inner table-responsive">
	<table class="table table-striped no-margin">
	<!-- <thead>
		<tr>
			<th><input type="checkbox" name="chooseAll" class="allCheckBox chooseAll"></th>
			<th>Id</th>
			<th>接口描述</th>
			<th>接口地址</th>
			<th>所属项目</th>
			<th>Operation</th>
		</tr>
	</thead> -->
	<tbody>
		
		<!-- <tr>
			<td width='10%'><input type="checkbox" name="ids" class='ids' value="<?php echo $data[$i]['id'] ?>"></td>
			<td width='10%'><?php echo ($i+1);?></td>
			<td width='12%'><?php echo $data[$i]['description']; ?></td>
			<td width='12%'><?php echo $data[$i]['url']; ?></td>
			<td width='12%'><?php echo $data[$i]['project_id']; ?></td>
			<td class='operationBox' width='15%'>
				<a href="/admin/api/update?id=<?php echo $data[$i]['id']; ?>" class='btn btn-default btn-xs'>Edit</a>
				<a href="javascript:void(0);" class='btn btn-warning btn-xs'>Test</a>
				<a href="/admin/api/doDelete?id=<?php echo $data[$i]['id']; ?>" onclick="return confirm('你确定要删除吗？')" class='btn btn-danger btn-xs'>Delete</a>
			</td>
		</tr> -->
		
		<?php for($i=0; $i<count($data); $i++){ ?>
		<tr>
			<td colspan=5>
			<div class="smart-widget smart-widget-collapsed">

				<div class="smart-widget-header">
						<input type="hidden" name="id" class="item-id" value="<?php echo $data[$i]['id']; ?>">
						<div class='row' style='width:90%;display:inline-block;'>
							<div class='col-md-4'>
								接口描述:　
								<?php echo $data[$i]['description']; ?>
							</div>
							<div class='col-md-4'>
								接口概况:　
								<?php echo $data[$i]['method']."　".$data[$i]['url']; ?>
							</div>
							<div class='col-md-4'>
								地址:　
								<?php echo $_SERVER['HTTP_HOST'].$data[$i]['url']; ?>
							</div>
						</div>
						<span class="smart-widget-option">
							<span class="refresh-icon-animated" style="display: none;">
								<i class="fa fa-circle-o-notch fa-spin"></i>
							</span>
							<a href="javascript:void(0);" class="doRequestApi">
		                        <i class="fa fa-magic"></i>
		                    </a>
							<a href="/admin/api/update?id=<?php echo $data[$i]['id']; ?>" class="">
		                        <i class="fa fa-pencil"></i>
		                    </a>
		                    <a href="#" class="widget-collapse-option" data-toggle="collapse">
		                        <i class="fa fa-chevron-up"></i>
		                    </a>
		                    <a href="/admin/api/doDelete?id=<?php echo $data[$i]['id']; ?>" onclick="return confirm('你确定要删除吗？')" class="">
		                        <i class="fa fa-times"></i>
		                    </a>
		                </span>
				</div>
				<div class="smart-widget-inner" style='display:none'>
					<div class="smart-widget-body">
						<form class="form-inline no-margin showApiResult">
							
						</form>
					</div>
				</div><!-- ./smart-widget-inner -->
				</div>
			</td>
		</tr>
			<?php } ?>
		<?php include_once('../app/views/admin/adminApi/_tfoot.php') ?>	
	</tbody>
	<!-- <tfoot>
		<tr>
			<th> <a href="javascript:void(0);" class='allCheckBox btn btn-default btn-sm'>全选</a> </th>
			<th> <a href="javascript:void(0);" class='deleteChooseBtn btn btn-danger btn-sm'>删除</a> </th>
			<th colspan=6></th>
		</tr>
	</tfoot> -->
	</table>
</div><!-- ./smart-widget-inner -->
</div><!-- ./smart-widget -->
<?php include_once('../app/views/admin/_footer.php') ?>
<script type="text/javascript" src="/js/myFuncs.js"></script>
<script type="text/javascript">

//api请求结果
function showRunOk(data)
{
	//console.log(data);
	if(typeof(data['content'])===undefined)
		$('.showApiResult').eq(clickPosition).html(data);
	else
		$('.showApiResult').eq(clickPosition).html(data['content']);

}
$(document).ready(function(){
	$('.doRequestApi').click(function(){
		
		clickPosition = $('.doRequestApi').index(this);
		var id = $('.item-id').eq(clickPosition).val();
		var postArray = new Array();

		postArray['id'] = id;

		sendAjax(postArray,'/admin/api/run','true','showRunOk');

	});

});
</script>