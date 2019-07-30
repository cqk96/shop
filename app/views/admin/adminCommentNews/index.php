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
		<table class="">
			<thead>
				<tr class='firstLine'>
					<th class=""><input type="checkbox" name="chooseAll" class="allCheckBox chooseAll"></th>
					<th class="">Id</th>
					<th class="">用户名</th>
					<th class="">评论对象</th>
					<th class="">评论内容</th>
					<!-- <th class="">操作</th> -->
				</tr>
			</thead>
			<tbody>
				<?php for($i=0; $i<count($data); $i++){ ?>
				<tr class="<?php echo $i%2==0? 'singular':'dual' ?>">
					<td class="">
						<input type='checkbox' name='classesIds[]' class='eachNewsClassCheckBox' value="<?php echo $data[$i]['id'] ?>" />
					</td>
					<td class=""><?php echo ($record_start+$i+1);?></td>
					<td class=""> <?php echo $data[$i]['nickname'];?> </td>
					<td class=""> <?php echo $data[$i]['comment_obj']; ?> </td>
					<td class=""> <?php echo $data[$i]['content']; ?> </td>
					<td class='operationBox'>
						<!-- <a type='button' href="/admin/user/update?id=<?php echo $data[$i]['id']; ?>" class='btn btn-info btn-sm'>编辑</a> -->
						<!-- <a type='button' href="/admin/comments/news/doDelete?id=<?php echo $data[$i]['id']; ?>" onclick="return confirm('你确定要删除吗？')" class='btn btn-danger btn-sm'>删除</a> -->
					</td>
				</tr>
				<?php } ?>

			</tbody>
			<?php include_once('../app/views/admin/adminCommentNews/_tfoot.php') ?>
		</table>
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
	/*首页js*/

	//全选checkbox
	var allCheckBoxBtnClick = 1;

	$('.allCheckBox').click(function(){
		
		if(allCheckBoxBtnClick%2!=0)
			$('.eachNewsClassCheckBox').each(function(){
				$(this).prop('checked',true);
			});
		else
			$('.eachNewsClassCheckBox').each(function(){
				$(this).prop('checked',false);
			});

		allCheckBoxBtnClick++;

	});
	
	//删除选择的项目
	$('.deleteChooseBtn').click(function(){

		var params = new Array();
		var postParam = new Array();

		$(".eachNewsClassCheckBox").each(function(){

			if($(this).prop('checked'))
				params.push($(this).val());
		});

		if(params.length==0){
			alert("尚未选择任何项目");
			return false;
		}

		postParam['ids'] = params;		

		var stillDeleted = confirm("你确定要删除吗?");
		if(stillDeleted){
			//输入删除的理由
			var reason = prompt("请输入删除原因");
			postParam['ids'] = params;
			postParam['reason'] = reason;
			sendAjax(postParam,'/admin/comments/news/doDelete','false','showDeleteItems');
		}
	});
	// $('.deleteChooseBtn').click(function(){
	// 	deleteChooseItems('eachNewsClassCheckBox','你确定要删除选定项吗？','/admin/comments/news/doDelete','','showDeleteItems','');
	// });
	/*首页js end*/
});
</script>