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
	/*头部样式*/
	table {
		width: 100%;
	}
	thead tr{
		height: 40px;
	}
	/*修改默认样式*/
	.smart-widget-header {
		margin-bottom: 5px;
	}
	.smart-widget {
		border-top-width: 0px;
	}
	.smart-widget .smart-widget-header {
		background-color: #86a4ee;
		color: #fff;
	}
	tbody tr {
		height: 100px;
	}
	.singular {
		background-color: #f5f8fe;
	}
	tbody tr:nth-child(2n+1){
		background:#f5f8fe;
	}
	.dual {
		background-color: #FFF;
	}
	tbody tr:nth-child(2n){
		background:#FFF;
	}
	.smart-widget-option i {
		color: #FFF;
	}
	/*图像*/
	.operationBox a {
		display: block;
		width: 100%;
		margin-bottom: 10px;
	}
	.icon-img {
		margin-right: 5px;
	}
	.icon-img img{
		display: inline-block;
		width: 13px;
		height: 13px;
	}
	.operationBox {
		font-size: 12px;
	}
	/*分页样式*/
	.page-pagination {
		float: right;
		margin-right: 27px;
		margin-top: 10px;
		margin-bottom: 10px;
	}
	.page-pagination li {
		width: 20px;
		height: 20px;
		background-color: #FFF;
		color: #7c7c7c;
		float: left;
		text-align: center;
		margin-left: 3px;
		margin-right: 3px;
	}
	.page-pagination li a{
		display: block;
		width: 100%;
		height: 100%;
		line-height: 18px;
		border: 1px solid #dadada;
		color: #343434;
	}
	.page-pagination .active a{
		color: #FFF !important;
		background-color: #86a4ee !important;
		border-color: #7088c4 !important;
	}
</style>
<div class='main-container'>
<div class="smart-widget">
	<div class="smart-widget-header">
		<?php echo empty($page_title)? '':$page_title;?>
		<span class="smart-widget-option">
			<a href="/admin/sys/opm/create">
				<i class="fa fa-plus"></i>
			</a>
			<a href="#" onclick="location.reload()" class="widget-refresh-option">
				<i class="fa fa-refresh"></i>
			</a>
		</span>
	</div>
	<table class="">
	<thead>
		<tr>
			<th><input type="checkbox" name="chooseAll" class="allCheckBox chooseAll"></th>
			<th>Id</th>
			<th>角色名称</th>
			<th>拥有的权限</th>
			<th>Operation</th>
		</tr>
	</thead>
	<tbody>
		<?php for($i=0; $i<count($data); $i++){ ?>
		<tr class="<?php echo $i%2==0? 'singular':'dual' ?>">
			<td width='10%'><input type="checkbox" name="ids" class='ids' value="<?php echo $data[$i]['role_id'] ?>"></td>
			<td width='10%'><?php echo ($i+1);?></td>
			<td width='20%'> <?php echo $roles[$data[$i]['role_id']]['name']; ?> </td>
			<td width='30%'> <?php echo $data[$i]['user_privileges']; ?> </td>
			<td class='operationBox' width='20%'>
				<a href="/admin/sys/opm/update?id=<?php echo $data[$i]['role_id']; ?>" ><span class="icon-img"><img src="/images/edit-icon.png" /></span>修改</a>
				<a href="/admin/sys/opm/doDelete?id=<?php echo $data[$i]['role_id']; ?>" onclick="return confirm('你确定要删除吗？')" ><span class="icon-img"><img src="/images/delete-icon.png" /></span>删除</a>
			</td>
		</tr>
		<?php } ?>
	</tbody>
	<tfoot>
		<tr>
			<th colspan=6><?php echo $pageObj->pagination ?></th>
		</tr>
		<tr>
			<th colspan=6>
				<a href="javascript:void(0);" style='float: right;margin-right: 27px;margin-bottom: 20px;' class='deleteChooseBtn btn btn-danger btn-xs'>删除</a>
				<a href="javascript:void(0);" style='float: right;margin-right: 27px;' class='allCheckBox btn btn-default btn-xs'>全选</a>
			</th>
		</tr>
	</tfoot>
	</table>
</div><!-- ./smart-widget -->
<?php include_once('../app/views/admin/_footer.php') ?>
<script type="text/javascript" src="/js/myFuncs.js"></script>
<script type="text/javascript">

function showDeleteItems(data)
{	
	window.location.reload();
}

$(document).ready(function(){
	//全选checkbox
	var allCheckBoxBtnClick = 1;

	$('.allCheckBox').click(function(){

		if(allCheckBoxBtnClick%2!=0){
			$('.ids').each(function(){
				$(this).prop('checked',true);
			});
			$('input.allCheckBox').prop('checked',true);
		} else {
			$('.ids').each(function(){
				$(this).prop('checked',false);
			});
			$('input.allCheckBox').prop('checked',false);
		}

		allCheckBoxBtnClick++;

	});
	
	//删除选择的项目
	$('.deleteChooseBtn').click(function(){
		deleteChooseItems('ids','你确定要删除选定项吗？','/admin/sys/opm/destroy','true','showDeleteItems');
	});
});
</script>