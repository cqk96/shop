<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
	form {
		padding: 0px;
		margin: 0px;
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
		text-align: left;
		padding-left: 15px;
	}
	/*分页样式*/
	.page-pagination {
		float: right;
		margin-right: 27px;
		/*margin-top: 10px;*/
		/*margin-bottom: 10px;*/
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

	table td,table th{
		text-align: center;
	}

	.searchForm select {
		    color: black;
		    margin-right: 10px;
	}
	.search-item-title {
		margin-left: 10px;
		margin-right: 10px;
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
</style>
<body>
		<div class="smart-widget">
			<div class="smart-widget-header">
				<?php echo $page_sub_title; ?>
				<span class="smart-widget-option">
				<a href="/admin/news/create">
					<i class="fa fa-plus"></i>
				</a>
				<a href="#" onclick="location.reload()" class="widget-refresh-option">
					<i class="fa fa-refresh"></i>
				</a>
			</span>

			<form class='searchForm' id="searchForm" action='/admin/news' method='get'>

				<div class='search-box'>
					<label class="label-control search-label"> 文章名称: </label>
					<div class="search-item-box">
						<input class="form-control" type='text' name='title' value='<?php echo empty($_GET['title'])? '':$_GET['title']; ?>' placeholder='请输入文章名称进行搜索' />
					</div>
				</div>

				<div class='search-box'>
					<label class="label-control search-label search-item-title"> 文章分类搜索: </label>
					<div class="search-item-box">
						<select name="classId" class="form-control">
							<option value="0" <?php if( !empty( $_GET['classId'] ) && $_GET['classId']==0 ) { echo "selected"; } ?> >全部</option>
							<?php for ($i=0; $i < count($newsClasses); $i++) { ?>
								<option value="<?php echo $newsClasses[$i]['id']; ?>" <?php if( !empty( $_GET['classId'] ) && $_GET['classId']==$newsClasses[$i]['id'] ) { echo "selected"; } ?> ><?php echo $newsClasses[$i]['class_name']; ?></option>
							<?php } ?>
						</select>
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
				<th><input type="checkbox" name="chooseAll" class="allCheckBox chooseAll"></th>
				<th>Id</th>
				<th>所属栏目名称</th>
				<th>文章标题</th>
				<th>文章封面</th>
				<th>修改时间</th>
				<th>浏览数</th>
				<th>点赞数</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			
			<?php for($i=0; $i<count($news); $i++){ ?>
			<tr class="<?php echo $i%2==0? 'singular':'dual' ?>">
				<td width='5%'>
					<input type='checkbox' name='classesIds[]' class='eachNewsClassCheckBox' value="<?php echo $news[$i]['id'] ?>" <?php if($news[$i]['class_id']==12){ echo " disabled='disabled' "; } ?> />
				</td>
				<td width='5%'><?php echo $news[$i]['id'] ?></td>
				<td width='19%'> <?php echo empty($news[$i]['class_name'])? '该栏目已经被删除':$news[$i]['class_name']; ?> </td>
				<td width='19%'> <?php echo $news[$i]['title']; ?> </td>
				<td width='10%'>
					<img src="<?php echo empty($news[$i]['cover'])? '/images/default-pic.jpg':$news[$i]['cover']; ?>">
				</td>
				<td width='15%'> <?php echo $news[$i]['updated_at']; ?> </td>
				<td width='5%'><?php echo $news[$i]['hits']; ?></td>
				<td width='5%'><?php echo empty($news[$i]['like_count'])? 0:$news[$i]['like_count']; ?></td>
				<td class='operationBox' width='7%'>
					<a type='button' href="/admin/news/update?id=<?php echo $news[$i]['id']; ?>"><span class="icon-img"><img src="/images/edit-icon.png" /></span>修改</a>
					<!-- <a type='button' href="/admin/news/read?id=<?php echo $news[$i]['id']; ?>" class='btn btn-info btn-sm'>Read</a> -->

					<?php if( !empty($news[$i]['class_id']) && $news[$i]['class_id']!=12): ?>
						<a type='button' href="/admin/news/delete?id=<?php echo $news[$i]['id']; ?>" onclick="return delNotice(this)" ><span class="icon-img"><img src="/images/delete-icon.png" /></span>删除</a>
					<?php endif; ?>	

				</td>
			</tr>
			<?php } ?>

		</tbody>
	</table>

	<div class="bottom-bar-box">
		
		<button type='button' class='allCheckBox btn btn-xs operate-btn first-operate-btn'>全选</button>
		<button type='button' class='topBtn btn btn-warning btn-xs operate-btn'>置顶</button>
		<button type='button' class='deleteChooseBtn btn btn-danger btn-xs operate-btn'>删除</button>
		
		<?php echo $dataObj->pagination ?>
		
	</div>


	<iframe id="idIframe" name="id_iframe" style="display: none;"></iframe>
</body>

<!-- Placed at the end of the document so the pages load faster -->

<!-- Jquery -->
<script src="/js/jquery-1.11.1.min.js"></script>

<!-- Bootstrap -->
<script src="/js/bootstrap.min.js"></script>

<!-- Flot -->
<!-- <script src='/js/jquery.flot.min.js'></script> -->

<!-- Slimscroll -->
<script src='/js/jquery.slimscroll.min.js'></script>

<!-- Morris -->
<script src='/js/rapheal.min.js'></script>	
<script src='/js/morris.min.js'></script>	

<!-- Datepicker -->
<script src='/js/uncompressed/datepicker.js'></script>

<!-- Sparkline -->
<script src='/js/sparkline.min.js'></script>

<!-- Skycons -->
<script src='/js/uncompressed/skycons.js'></script>

<!-- Popup Overlay -->
<script src='/js/jquery.popupoverlay.min.js'></script>

<!-- Easy Pie Chart -->
<script src='/js/jquery.easypiechart.min.js'></script>

<!-- Sortable -->
<script src='/js/uncompressed/jquery.sortable.js'></script>

<!-- Owl Carousel -->
<script src='/js/owl.carousel.min.js'></script>

<!-- Modernizr -->
<script src='/js/modernizr.min.js'></script>

<!-- Simplify -->
<script src="/js/simplify/simplify.js"></script>
<!-- <script src="/js/simplify/simplify_dashboard.js"></script>-->

<!-- 分页 -->
<script type='text/javascript' src='/js/pagination.js'></script>

<script type='text/javascript' src='/js/myFuncs.js'></script>
<!-- <script type='text/javascript' src='/js/admin-news.js?2'></script> -->
<script type="text/javascript">
$(document).ready(function(){

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

<!-- 删除提醒  -->
<script >
	function delNotice(obj){
		
		var delurl = $(obj).attr("href");
		
		 layer.confirm('确认要删除吗？删除后不能恢复！', {
			  btn: ['确认','取消'] //按钮 
			}, function(){
				layer.msg('删除成功', {icon: 1} );
				$("#idIframe").attr("src", delurl);
				window.location.reload();
				
			},function(index){ // 取消 
				layer.close(index);
				return false;
				 
			}); 

		return false;
	}	
</script>

<script >
	
function showDeleteItems(data)
{
	if(data.code!='001'){
		alert("删除失败");
	} else {
		alert("删除成功");
		window.location.href = '/admin/news';
	}
}

function showTopItems(data)
{
	if(data.code!='001'){
		$('.showResultText').text('置顶失败');
	} else {
		$('.showResultText').text('置顶成功');
		setTimeout(function(){
			window.location.href = '/admin/news';
		},600);
	}
}

function showDeleteCover(data)
{
	
	if(data.code!='001'){
		alert("删除封面失败");
	} else {
		//alert("删除封面成功");
		var newsId = $("input[name='id']").val();
		window.location.href = '/admin/news/update?id='+newsId;
	}
	
}

function clearNewsCover()
{
	var is_wish = confirm('您确实想清除该篇文章的封面吗？');
	if(is_wish){
		var postParam = new Array();
		var newsId = $("input[name='id']").val();
		postParam['id'] = newsId;
		sendAjax(postParam,'/admin/news/deleteNewsCover','','showDeleteCover');
	}
}

$(document).ready(function(){

	/*通用*/
	//按钮修改封面
	$('.coverBtn').click(function(){
		$('.coverInput').click();
	});

	/*首页js*/

	//全选checkbox
	var allCheckBoxBtnClick = 1;

	$('.allCheckBox').click(function(){
		
		if(allCheckBoxBtnClick%2!=0) {
			$('.eachNewsClassCheckBox').each(function(){
				var isDisabled = $(this).prop("disabled");
				if( !isDisabled ) {
					$(this).prop('checked',true);
				}
			});
		} else {
			$('.eachNewsClassCheckBox').each(function(){
				var isDisabled = $(this).prop("disabled");
				if( !isDisabled ) {
					$(this).prop('checked',false);
				}
			});
		}

		allCheckBoxBtnClick++;

	});
	
	//删除选择的项目
	$('.deleteChooseBtn').click(function(){
		deleteChooseItems('eachNewsClassCheckBox','你确定要删除选定项吗？','/admin/news/delete2','','showDeleteItems','');
	});
	/*首页js end*/

	/*文章置顶*/
	$('.topBtn').click(function(){
		doSomeWithCheckBoxItems('eachNewsClassCheckBox','你确定要指定选定项吗？','/admin/news/doTop','','showTopItems');
	});
	/*文章置顶 end*/ 

});
</script>
 

	