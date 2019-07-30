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
		list-style-type: none;
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
<link rel="stylesheet" type="text/css" href="/css/back/admin-menu.css?"+Math.random()>
<div>
<div class="smart-widget">
	<div class="smart-widget-header">
		<?php echo empty($page_title)? '':$page_title;?>
		<span class="smart-widget-option">
			<a href="/admin/sys/menu/create">
				<i class="fa fa-plus"></i>
			</a>
			<a href="#" onclick="location.reload()" class="widget-refresh-option">
				<i class="fa fa-refresh"></i>
			</a>
		</span>
	</div>
	<table id="indexTable" class="">
	<thead>
		<tr>
			<th><input type="checkbox" name="chooseAll" class="allCheckBox chooseAll"></th>
			<th>Id</th>
			<th>显示文字</th>
			<th>跳转链接</th>
			<th>状态</th>
			<th>排序</th>
			<th>Operation</th>
		</tr>
	</thead>
	<tbody>
		<?php echo empty($pageObj->data)? '':$pageObj->data;?>
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


	//顺便选择子菜单
	$('.ids').click(function(){
		//当前状态
		var currentSingleChosen = $(this).prop('checked');
		var currentSingleIndex = $('.ids').index(this);
		var nextIndex = currentSingleIndex+1;

		var parentObj = $(this).parent();
		var className = parentObj.next().next().prop('class');
		var nextClassName = parentObj.parent().next().children('td').eq(2).prop('class');
		var endPosition = '';
		if(className!=nextClassName && className<nextClassName){
			//获取截至点
			var collection = $("#indexTable tbody tr").slice(currentSingleIndex);
			for (var j = 1; j < collection.length; j++) {
				if(className>=collection[j]['childNodes'][5]['className']){
					endPosition = j;			
					break;
				}
			}
			if(endPosition=='')
				endPosition = collection.length;
			//real 截至点

			for (var i = currentSingleIndex; i<= (currentSingleIndex+endPosition-1); i++) {
					$('.ids').eq(i).prop('checked', currentSingleChosen);
			}

		}	

	});

	//修改排序
	$("input[name='order']").blur(function(){
		var postArray = new Array();
		//var name = $(this).prop('name');
		postArray['id'] = $(this).parent().parent().find('.ids').val();
		postArray['value'] = $(this).val();
		sendAjax(postArray,'/admin/sys/menu/updateColumn','true');
	});
	
	//删除选择的项目
	$('.deleteChooseBtn').click(function(){
		deleteChooseItems('ids','你确定要删除选定项吗？','/admin/sys/menu/destroy','true','showDeleteItems');
	});
});
</script>