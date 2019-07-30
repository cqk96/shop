<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
	.menuLevel_2{
		padding-left: 2% !important;
	}
	.menuLevel_3{
		padding-left: 4% !important;
	}
	.menuLevel_4{
		padding-left: 6% !important;
	}
	.menuLevel_5{
		padding-left: 8% !important;
	}
	.menuLevel_6{
		padding-left: 10% !important;
	}
	tbody img {
		display: block;
		width: 100px;
		height: 70px;
		margin: 0 auto;
	}
	table td,table th{
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
</style>

<div class="smart-widget">
	<div class="smart-widget-header">
	新闻栏目管理	
		<span class="smart-widget-option">
			<a href="/admin/newsClass/create">
				<i class="fa fa-plus"></i>
			</a>
			<a href="#" onclick="location.reload()" class="widget-refresh-option">
				<i class="fa fa-refresh"></i>
			</a>
		</span>
	</div>
		<table class="">
			<thead>
				<tr class='firstLine'>
					<th class=""><input type="checkbox" name="chooseAll" class="allCheckBox chooseAll"></th>
					<th class="">Id</th>
					<th class="">栏目名称</th>
					<th class="">封面</th>
					<th class="">操作</th>
				</tr>
			</thead>

			<tbody>
				<?php echo $pageObj->data; ?>
			</tbody>
			<tfoot>
			
				<tr>
					<th colspan=5><?php echo $pageObj->pagination; ?></th>
				</tr>
				<tr>
				
					<td class='operationBox' colspan=5>
						<button type='button' style='float: right;margin-right: 27px;margin-bottom: 20px;'  class='deleteChooseBtn btn btn-danger btn-xs'>删除</button>
						<button type='button' style='float: right;margin-right: 27px;' class='allCheckBox btn btn-default btn-xs'>全选</button>
					</td>
				</tr>
			</tfoot>
		</table>
</div><!-- ./smart-widget -->

<?php include_once('../app/views/admin/_footer.php') ?>
<script type='text/javascript' src='/js/myFuncs.js'></script>
<script type='text/javascript' src='/js/admin-news-classes.js?1'></script>