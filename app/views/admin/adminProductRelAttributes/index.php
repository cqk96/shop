<?php include_once('../app/views/admin/_header.php') ?>
<link rel="stylesheet" href="/css/date-picker/jquery-ui.min.css" />
<style type="text/css">
	html,body,form,div{
		margin: 0px;
		padding: 0px;
	}
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

	/*搜索*/
	.search-box {
		margin-top: 5px;
	}
	.searchForm input{
		color: black;
	}
	.ui-datepicker-calendar tbody  tr{
		height: auto !important;
	}
</style>
<div class='main-container'>
	<div class="smart-widget">
		<div class="smart-widget-header">
			<?php echo $page_title; ?>
			<span class="smart-widget-option">

			<!-- 记录在哪一页添加 -->
			<a href="/admin/productRelAttribute/create?page=<?php echo empty($_GET['page'])? 1:$_GET['page']; ?>">
				<i class="fa fa-plus"></i>
			</a>
			<a href="#" onclick="location.reload()" class="widget-refresh-option">
				<i class="fa fa-refresh"></i>
			</a>
		</span>

		<div class="search-box">
			<form action="/admin/productRelAttributes" method="GET" class="searchForm">

				<!-- 标题查找 -->
				<input type="text" name="title" value="<?php echo empty($_GET['title'])? '':$_GET['title']; ?>" placeholder="请输入标题" />

				<!-- 时间戳查找 -->
				<input type="text" name="startTime" id="startTime" value="<?php echo empty($_GET['startTime'])? '':$_GET['startTime'] ?>" placeholder="请输入起始时间">
				<input type="text" name="endTime" id="endTime" value="<?php echo empty($_GET['endTime'])? '':$_GET['endTime'] ?>" placeholder="请输入结束时间">
				
				<button type="submit" class="btn btn-primary btn-xs">提交</button>

			</form>
		</div>

	</div>
	<table class="">
	<thead>
		<tr class='firstLine'>
			<th><input type="checkbox" name="chooseAll" class="chooseAll"></th>
			<th>Id</th>
			<th>商品标题</th>
			<!-- <th>专区顶图</th> -->
			<th>修改时间</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		
		<?php for($i=0; $i<count($data); $i++){ ?>
		<tr class="<?php echo $i%2==0? 'singular':'dual' ?>">
			<td width='18%'><input type='checkbox' name='classesIds[]' class='eachItemCheckBox' value="<?php echo $data[$i]['id'] ?>" /></td>
			<td width='18%'><?php echo $data[$i]['id'] ?></td>
			<td width='18%'> <?php echo $data[$i]['name']; ?> </td>
			<!-- <td width='18%'>
				<img src="<?php echo empty($data[$i]['top_cover'])? '':$data[$i]['top_cover']; ?>">
			</td> -->
			<td width='18%'> <?php echo date("Y-m-d H:i:s",$data[$i]['create_time']); ?> </td>
			<td class='operationBox' width='18%'>
				<a type='button' href="/admin/productRelAttribute/update?id=<?php echo $data[$i]['id']; ?>&page=<?php echo empty($_GET['page'])? 1:$_GET['page']; ?>&dataClass=<?php echo empty($_GET['dataClass'])? '':$_GET['dataClass']; ?>"><span class="icon-img"><img src="/images/edit-icon.png" /></span>修改</a>
				<!-- <a type='button' href="/admin/data/read?id=<?php echo $data[$i]['id']; ?>" class='btn btn-info btn-sm'>Read</a> -->
				<a type='button' href="/admin/productRelAttribute/doDelete?id=<?php echo $data[$i]['id']; ?>" onclick="return confirm('你确定要删除吗？')"><span class="icon-img"><img src="/images/delete-icon.png" /></span>删除</a>
			</td>
		</tr>
		<?php } ?>
	</tbody>
	<tfoot>
		<tr>
			<th colspan=6><?php echo $pageObj->pagination ?></th>
		</tr>
		<tr>
			<td class='operationBox'  colspan=6>
				<?php if(count($data)!=0):?>
				<button type='button' style='float: right;margin-right: 27px;margin-bottom: 20px;'  class='deleteChooseBtn btn btn-danger btn-xs'>删除</button>
				<button type='button' style='float: right;margin-right: 27px;'  class='allCheckBox btn btn-xs'>全选</button>
				<?php endif;?>
			</td>
		</tr>
	</tfoot>
</table>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type='text/javascript' src='/js/myFuncs.js'></script>
<script type='text/javascript' src='/js/date-picker/jquery-ui.min.js'></script>
<script type="text/javascript">
function showDeleteItems(data)
{
	
	if(!data['success']){
		alert("删除失败");
	} else {
		// alert("删除成功");
		window.location.href = '/admin/productRelAttributes';
	}
}
$(document).ready(function(){
	//全选checkbox
	var allCheckBoxBtnClick = 1;

	$('.allCheckBox').click(function(){
		
		if(allCheckBoxBtnClick%2!=0)
			$('.eachItemCheckBox').each(function(){
				$(this).prop('checked',true);
			});
		else
			$('.eachItemCheckBox').each(function(){
				$(this).prop('checked',false);
			});

		allCheckBoxBtnClick++;

	});

	//初始化日期选择
	$( "#startTime" ).datepicker({dateFormat:'yy-mm-dd'});
	$( "#endTime" ).datepicker({dateFormat:'yy-mm-dd'});

	$('.deleteChooseBtn').click(function(){
		deleteChooseItems('eachItemCheckBox','你确定要删除选定项吗？','/admin/productRelAttribute/doDelete','','showDeleteItems','');
	});

});
</script>