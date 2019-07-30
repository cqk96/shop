<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
	td img {
		display: block;
		width: 100px;
		height: 70px;
		margin: 0 auto;
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

	table td,table th{
		text-align: center;
	}
</style>
<div>
	<div class="smart-widget">
		<div class="smart-widget-header">
			<?php echo $page_sub_title; ?>
			<span class="smart-widget-option">
			<a href="/admin/data/create">
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
			<th>ID</th>
			<th>文章标题</th>
			<th>文章封面</th>
			<th>修改时间</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		
		<?php for($i=0; $i<count($data); $i++){ ?>
		<tr class="<?php echo $i%2==0? 'singular':'dual' ?>">
			<td width=''><?php echo $data[$i]['id'] ?></td>
			<td width=''> <?php echo $data[$i]['title']; ?> </td>
			<td width=''>
				<img src="<?php echo empty($data[$i]['cover'])? '/images/default-pic.jpg':$data[$i]['cover']; ?>">
			</td>
			<td width=''> <?php echo $data[$i]['updated_at']; ?> </td>
			<td class='operationBox' width=''>
				<a href="/admin/news/read?id=<?php echo $data[$i]['id']; ?>"><span class="icon-img"><img src="/images/eye-icon.png" /></span>查看</a>
			</td>
		</tr>
		<?php } ?>
	</tbody>
	<tfoot>
		<tr>
			<th colspan=5><?php echo $pageObj->pagination ?></th>
		</tr>
		<tr>
			<td colspan=4>
				<span class="showResultText"></span>
			</td>
			<td class='operationBox'  colspan=3>
				<button type='button' style='float: right;margin-right: 27px;margin-bottom: 20px;'  class='deleteChooseBtn btn btn-danger btn-xs'>删除</button>
				<button type='button' style='float: right;margin-right: 27px;'  class='topBtn btn btn-warning btn-xs'>置顶</button>
				<button type='button' style='float: right;margin-right: 27px;'  class='allCheckBox btn btn-xs'>全选</button>
			</td>
		</tr>
	</tfoot>
</table>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type='text/javascript' src='/js/myFuncs.js'></script>