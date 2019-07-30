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
	table {
		width: 100%;
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
		/*margin-top: 10px;*/
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
	td img {
		display: block;
		width: 70px;
		height: 70px;
		margin: 0 auto;
	}

	.searchForm .search-box {
		display: inline-block;
		width: 30%;
	}
	.searchForm .search-box .form-control {
		display: inline-block;
	}
	.searchForm .search-box .search-label{
		margin-right: 20px;
		/*vertical-align:  middle;*/
	}
	.searchForm .search-box .search-button{
		margin-right: 20px;
		    /*vertical-align: text-bottom;*/
	}
	.searchForm .search-box .search-item-box{ 
		display: inline-block;
	}
	.search-button-box .btn-sm{
		line-height: inherit;
		vertical-align: inherit;
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
		/*margin-top: inherit;*/
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

		<form id="searchForm" class='searchForm' action='/admin/users' method='get'>

			<div class='search-box'>
				<label class="label-control search-label"> 用户名称: </label>
				<div class="search-item-box">
					<input class="form-control" type='text' name='username' value='<?php echo empty($_GET['username'])? '':$_GET['username']; ?>' placeholder='请输入用户名称进行搜索' />
				</div>
			</div>

			<div class='search-box search-button-box'>

				<span class="search-button">
					<button id="searchBtn" class='btn btn-primary btn-sm' type='submit'>提交</button>
				</span>

				<div class="search-item-box">
					<div class="search-result-box"> 共查询到 <?php echo $pageObj->totalCount; ?> 条记录 </div>
				</div>

			</div>

		</form>

	</div>
	<table class="">
		<thead>
			<tr class='firstLine'>
				<th class="">Id</th>
				<th class="">用户账号</th>
				<th class="">用户名称</th>
				<th class="">头像</th>
				<th class="">性别</th>
				<th class="" colspan=2>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php for($i=0; $i<count($data); $i++){ ?>
			<tr class="<?php echo $i%2==0? 'singular':'dual' ?>">
				<td class=""><?php echo ($record_start+$i+1);?></td>
				<td class=""> <?php echo $data[$i]['user_login'];?> </td>
				<td class=""> <?php echo $data[$i]['name']; ?> </td>
				<td class=""> <img src="<?php echo empty($data[$i]['avatar'])? '/images/avatar.png':$data[$i]['avatar'] ?>"> </td>
				<td class=""> <?php echo empty($gender[$data[$i]['gender']])? '未知':$gender[$data[$i]['gender']]; ?> </td>
				<!-- <td class=""> <?php //echo $userTypes[$data[$i]['user_type']]; ?> </td> -->
				<td class='operationBox' colspan=2>
					<a href="/admin/user/update?id=<?php echo $data[$i]['id']; ?>" ><span class="icon-img"><img src="/images/edit-icon.png" /></span>编辑</a>
					<!-- <a type='button' href="/admin/user/doDelete?id=<?php echo $data[$i]['id']; ?>" onclick="return confirm('你确定要删除吗？')" class=' '><span class="icon-img"><img src="/images/delete-icon.png" /></span>删除</a> -->
					<a href="/admin/user/resetPwd?id=<?php echo $data[$i]['id']; ?>" onclick="return confirm('确定要重置密码吗?')" ><span class="icon-img"><img src="/images/reset-pwd-icon.png" /></span>重置密码</a>
				</td>
			</tr>
			<?php } ?>

		</tbody>
		
	</table>

	<div class="bottom-bar-box">
		
		<a href='javascript:void(0);' class='allCheckBox btn btn-default btn-xs operate-btn first-operate-btn' style='visibility: hidden;' >全选</a>
		
		<?php echo $pageObj->pagination; ?>
		
	</div>

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

/*首页js*/
$(document).ready(function(){
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
	
	/*首页js end*/

	_userAgent = navigator.userAgent;
	if(_userAgent.indexOf("MSIE")>0 && parseInt(_userAgent.match(/.*?MSIE\s(.*?);/)[1])<=9){

		$("input[type='text']").each(function(){
			var curPlaceHolder = $(this).attr("placeholder");
			var curValue = $.trim($(this).val());
			if( curValue=="" ) {
				$(this).val(curPlaceHolder);
			}
			
		});

		$("input[type='text']").focus(function(){
			
			var curPlaceHolder = $.trim($(this).attr("placeholder"));
			var curValue = $.trim($(this).val());

			if( curPlaceHolder==curValue ) {
				$(this).val("");
			}

		});

		$("input[type='text']").blur(function(){
			
			var curPlaceHolder = $.trim($(this).attr("placeholder"));
			var curValue = $.trim($(this).val());

			if( curValue=="" ) {
				$(this).val(curPlaceHolder);
			}

		});

	}

	if(_userAgent.indexOf("MSIE")>0) {

		document.body.attachEvent("onkeydown", function(event) {
			if( event.keyCode!=13 ) {
				// return false;
			} else {
				$("#searchBtn").click();
			}

		});
	} else {
		window.document.body.addEventListener("keydown", function(event) {
			if( event.which!=13 ) {
				return false;
			} else {
				$("#searchBtn").click();
			}

		});
	}

	$("#searchForm").submit(function(){

		$("#searchForm input").each(function(){
			
			var curPlaceHolder = $.trim($(this).attr("placeholder"));
			var curValue = $.trim($(this).val());

			if( curValue==curPlaceHolder ) {
				$(this).val("");
			}

		});

	});

});
</script>