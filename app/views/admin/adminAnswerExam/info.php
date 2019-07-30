<?php include_once('../app/views/admin/_header.php') ?>
<link rel="stylesheet" type="text/css" href="/js/tools/layer/skin/default/layer.css">
<style type="text/css">
.contianer { width: 100%; height: 100%; padding-top: 21px; background-color: #FFF;  }

.each-dairy-box {
	width: 80%;
	/*border: 1px solid black;*/
	margin: 6px auto;
	height: 120px;
	background-color: #fff;
	overflow: hidden;
}
.dairy-title {
	font-size: 18px;
	margin-top: 10px;
	padding-left: 20px;
}
.user-name {
	font-size: 16px;
	text-align: right;
	padding-right: 15px;
}
.concrete-content {
	margin-left: 10px;
}
.operation-box {
	width: 100%;
	height: 20px;
}
.detail-btn {
	margin-right: 15px;
	float: right;
}
.line-box {
	width: 98%;
	height: 12px;
	border-bottom: 1px solid #000000;
	margin: 0 auto;
}
.page-pagination-box {
	width: 80%;
	margin: 0 auto;
}
/*分页样式*/
.page-pagination {
	float: left;
	/*margin-left: 27px;*/
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
textarea {
	resize: none;
}

/*管理列表标题*/
.top-rest {
	width: 100%;
	height: 15px;
}
.management-box {
	width: 98%;
	height: 60px;
	background-image: linear-gradient(-180deg, #80C3F3 0%, #4A90E2 100%);
	filter: -ms-filter:progid:DXImageTransform.Microsoft.gradient(startcolorstr=#80C3F3,endcolorstr=#4A90E2,gradientType=0);/*IE8*/
    filter: progid:DXImageTransform.Microsoft.gradient(startcolorstr=#80C3F3,endcolorstr=#4A90E2,gradientType=0);
	border: 1px solid #C4C4C4;
	margin: 0 auto;
}
.management-title {
	font-size: 16px;
	color: #FFFFFF;
	line-height: 60px;
	height: 100%;
	padding-left: 22px;
}

/*列表内容*/
.lists-content-box {
	width: 98%;
	margin: 0 auto;
}

.search-form {
	width: 100%;
	/*border: 1px solid black;*/
}

.form-horizontal .form-group {
     margin-left: 0px; 
     margin-right: 0px; 
}


/*rest list*/
.rest-25{
	width: 100%;
	height: 25px;
}
.data-lists {
	margin-top: 10px;
}
.search-btn-img {
	margin-top: -3px;
}
.data-lists {
	border: 1px solid #ddd;
}
.data-lists th {
 	font-size: 16px;
	color: #333333;
}
.data-lists td{
	word-wrap: break-word;
    word-break: break-all;
    font-size: 14px;
   
}
.data-lists>tbody>tr>td {
	 padding-top: 15px;
    padding-bottom: 15px;
    vertical-align: middle;
}
.operate-btn {
	margin-right: 8px;
	width: 16px;
	display: inline-block;
}
.edit-btn {
	width: 12px;
	margin-right: 8px;
	display: inline-block;
}
.data-lists>tbody>tr:nth-child(odd)>td, .data-lists>tbody>tr:nth-child(odd)>th {
    background-color: #eff5fc;
}
.total-page-box {
	margin-top: 10px;
	text-align: right;
	font-size: 14px;
	color: #000000;
}
.total-page-span {
	color: #4A90E2;
}
.show-detail-btn-box {
	margin-right: 25px;
}

.create-goal-btn {
	float: right;
	margin-top: -3px;
}
.import-btn {
	margin-top: -3px;
	cursor: pointer;
}

.width-auto {

	width: auto !important;
	padding-left: inherit; 
    padding-right: inherit; 

}
.remind-text {
	color: red;
}
.item-margin-right {
	margin-right: 20px;
}

.search-form .col-xs-2 {
	width: 12.6666%;
}
.search-form .col-xs-1 {
	padding-left: 0px;
}

.data-lists th {
	text-align: center;
}
.data-lists td {
	text-align: center;
}
</style>
<body>
<div class="contianer" id="contianer">

	<div class='top-rest'></div>

	<!-- 职业指导列表 -->
	<div class="management-box">
		<p class="management-title">成绩列表</p>
	</div>

	<div class="lists-content-box">

		<form id="searchForm" class="search-form form-horizontal" action="<?php echo $action; ?>" type="GET" >

			<input type="hidden" name="id" value="<?php echo $id; ?>" />

			<div class="rest-25"></div>

			<div class="form-group">

				<div class="col-xs-1 width-auto">
					<label for="username" class="control-label ">姓名：</label>
				</div>
		    	<div class="col-xs-2">
		      		<input type="text" class="form-control" id="username" name="username" placeholder="请输入用户名称" value="<?php echo $username; ?>" />
		   	 	</div>

		   	 	<div class="col-xs-4">
		   	 		<input id="searchBtn" class="search-btn-img" type="image" src="/images/search-btn2.png">
		   	 	</div>

		  	</div>
		</form>

		<!-- 数据列表 -->
		<table class="table data-lists table-striped">
			<thead>
				<tr>
					<th width="20%">姓名</th>
					<th width="20%">考试成绩</th>
					<th width="20%">正确题数</th>
					<th width="20%">错误题数</th>
				</tr>
			</thead>
			<tbody>

				<?php for ($i=0; $i < count($data) ; $i++) { ?>
				<tr>
					<td><?php echo empty($data[$i]['name'])? '':$data[$i]['name']; ?></td>
					<td><?php echo empty($data[$i]['get_score'])? 0:$data[$i]['get_score']; ?>分</td>
					<td><?php echo empty($data[$i]['right_count'])? 0:$data[$i]['right_count']; ?></td>
					<td><?php echo empty($data[$i]['error_count'])? 0:$data[$i]['error_count']; ?></td>
				</tr>
				<?php } ?>

			</tbody>

			<tfoot>
				<tr>
					<td colspan=2>
						<div class="total-page-box">共 <span class="total-page-span"><?php echo $totalPage; ?> </span> 页</div>
					</td>
					<td colspan=2>
						<?php if(!empty($data)): ?>
							<div class="page-pagination-box">
								<?php echo $pageObj->pagination; ?>
							</div>
						<?php endif; ?>
					</td>
				</tr>
			</tfoot>
		</table>

	</div>

</div>
</body>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type="text/javascript" src="/js/pagination.js"></script>
<script type="text/javascript" src="/js/tools/layer/layer.js"></script>
<script type="text/javascript">

/*显示placeholder*/
function showPlaceholder()
{

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

		/*textarea*/
		$("textarea").each(function(){
			var curPlaceHolder = $(this).attr("placeholder");
			var curValue = $.trim($(this).val());
			if( curValue=="" ) {
				$(this).val(curPlaceHolder);
			}
			
		});

		$("textarea").focus(function(){
			
			var curPlaceHolder = $.trim($(this).attr("placeholder"));
			var curValue = $.trim($(this).val());

			if( curPlaceHolder==curValue ) {
				$(this).val("");
			}

		});

		$("textarea").blur(function(){
			
			var curPlaceHolder = $.trim($(this).attr("placeholder"));
			var curValue = $.trim($(this).val());

			if( curValue=="" ) {
				$(this).val(curPlaceHolder);
			}

		}); 

	}

}

$(document).ready(function(){

	_userAgent = navigator.userAgent;

	if(_userAgent.indexOf("MSIE")>0) {

		window.document.body.attachEvent("onkeydown", function(event) {
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
	
	if(_userAgent.indexOf("MSIE")>0 && parseInt(_userAgent.match(/.*?MSIE\s(.*?);/)[1])<=9){
		showPlaceholder();
	}

	// 搜索表单提交前触发事件
	$('#searchForm').submit(function(){

		var canSubmit = true;

		// 由于搜索条件存在于input中   所以遍历input
		$('#searchForm').find("input[type='text']").each(function(){
			var curValue = $.trim( $(this).val() );
			var curPlaceholder = $.trim( $(this).attr("placeholder") );

			if( curValue==curPlaceholder ) {
				$(this).val("");
			}

		});

	});

});
</script>