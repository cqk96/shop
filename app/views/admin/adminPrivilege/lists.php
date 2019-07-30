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
		/*margin-top: 10px;
		margin-bottom: 10px;*/
	}
	.page-pagination li {
		min-width: 20px;
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
<div class="smart-widget">
	<div class="smart-widget-header">
		<?php echo empty($page_title)? '':$page_title;?>
		<span class="smart-widget-option">
			<a href="/admin/sys/privilege/create">
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
			<th>名称</th>
			<th>类型</th>
			<th>Operation</th>
		</tr>
	</thead>
	<tbody>
		<?php for($i=0; $i<count($data); $i++){ ?>
		<tr class="<?php echo $i%2==0? 'singular':'dual' ?>">
			<td width='10%'><input type="checkbox" name="ids" class='ids' value="<?php echo $data[$i]['id'] ?>"></td>
			<td width='10%'> <?php echo $data[$i]['id'];?></td>
			<td width='35%'> <?php echo $data[$i]['name']; ?> </td>
			<td width='10%'> <?php echo $data[$i]['type_id']; ?> </td>
			<td class='operationBox' width='35%'>
				<a href="/admin/sys/privilege/update?id=<?php echo $data[$i]['id']; ?>" ><span class="icon-img"><img src="/images/edit-icon.png" /></span>修改</a>
				<a href="/admin/sys/privilege/doDelete?id=<?php echo $data[$i]['id']; ?>" onclick="return confirm('你确定要删除吗？')" ><span class="icon-img"><img src="/images/delete-icon.png" /></span>删除</a>
			</td>
		</tr>
		<?php } ?>
	</tbody>
	</table>

	<div class="bottom-bar-box">

		<a href="javascript:void(0);" class='allCheckBox btn btn-default btn-xs operate-btn first-operate-btn'>全选</a>
		<a href="javascript:void(0);" class='deleteChooseBtn btn btn-danger btn-xs operate-btn'>删除</a>
		
		<?php echo $pageObj->pagination; ?>
		
	</div>

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
		deleteChooseItems('ids','你确定要删除选定项吗？','/admin/sys/privilege/doDelete2','true','showDeleteItems');
	});
});
</script>