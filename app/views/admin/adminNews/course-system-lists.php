<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
html,body,div,p,form,table,tbody,tr,td,th,thead{
	padding: 0px;
	margin: 0px;
}
body { 
	background-color: #FFF;
}
.contianer { width: 100%; height: 100%; padding-top: 21px; background-color: #fff;  }
.rest-top {
	width: 100%;
	height: 25px;
}

.parent-box {
	width: 78.4%;
	margin: 0 auto;
}
.news-class-title{
	padding-left: 8px;
	font-size: 24px;
	color: #333333;
	position: relative;
}
.search-form {
	float: right;
}
.search-div {
    /*margin-top: 40px;*/
    position: relative;
}
.search-form .search-input {
    display: block;
    border:none;
    padding-left: 56px;
    background-color: #F2F2F2;
    color: #a3a3a3;
    line-height: 34px;
    border-radius: 100px;
    width: 300px;
}
.search-form .search-input:focus{
	background-color: #F2F2F2;
}
.search-img {
    display: inline-block;
    position: absolute;
    top: 7px; ;
    left: 21px;
}

.split-line {
	width: 100%;
	height: 1px;
	box-shadow: 0 2px 4px 0 #4D5AFF;
	background-color: #4A90E2;
	margin-top: 16px;
	margin-bottom: 21.5px;
}
.item-circle {
	width: 4px;
	height: 4px;
	display: inline-block;
	vertical-align: middle;;
	background-color: #333333;
	border-radius: 100%;
	margin-right: 12px;
}
.each-data-item {
	position: relative;
	font-size: 14px;
	color: #666666;
	height: 38px;
	line-height: 38px;
	padding-left: 8px;
	border-bottom: 1px solid #ECECEC;
}
.item-time {
	font-size: 14px;
	color: #999999;
	float: right;
}

/*分页样式*/
.page-pagination {
	/*float: right;*/
	position: relative;
	display: inline-block;
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
.pagination-detail-box {
	font-size: 14px;
	color: #999999;
}
.total-page-box {
	color: #78acea;
}
.page-detail-td {
	padding-left: 10px;
	padding-right: 10px;
}
.pagination-box-table {
	width: 53%;
	margin: 41.4px auto 0px auto;
}

.each-tab-index {
	width: 110px;
	height: 38px;
	overflow: hidden;
	display: inline-block;
}
.each-tab-index img {
	width: 100%;
	height: auto;
}
.theory-btn {
	margin-right: 35px;
}

/*缺省*/
.rest-37 {
	width: 100%;
	height: 37px;
}

</style>
<body>
	<div class="contianer">
		<div clas="rest-top"></div>

		<div class="parent-box">
			<div class="news-class-title">
				
				<div class="each-tab-index theory-btn">
					<a href="/admin/news/database/lists/courseSystem?index=1">
						<img src="<?php echo $index==1? '/images/theory-blue.png':'/images/theory-grey.png'; ?>">
					</a>
				</div>

				<div class="each-tab-index">
					<a href="/admin/news/database/lists/courseSystem?index=2">
						<img src="<?php echo $index==2? '/images/training-blue.png':'/images/training-grey.png'; ?>">
					</a>
				</div>

			</div>
			<div class="split-line"></div>

			<div class="data-lists-box">

				<?php for ($i=0; $i < count($data); $i++) { ?>
					<a href="/admin/news/readVer2?id=<?php echo $data[$i]['id']; ?>">
						<div class="each-data-item" title="<?php echo $data[$i]['title']; ?>">
							<span class="item-circle"></span>
							<?php echo $this->cutStr($data[$i]['title'], 40); ?>
							<span class="item-time"> <?php echo empty($data[$i]['created_at'])? '':"【".substr($data[$i]['created_at'], 0, 10)."】" ?></span>
						</div>
					</a>
				<?php } ?>

			</div>

			<!-- 分页 -->
			<table class="pagination-box pagination-box-table">
				<tbody>
					<tr>
						<td class="page-detail-td">
							<span class="pagination-detail-box">
								共 <span class="total-page-box"><?php echo $totalPage ?> </span> 页
							</span>
						</td>

						<td>
							<?php echo $paginationObj->pagination; ?>
						</td>
					</tr>
				</tbody>
			</table>

		</div>

	</div>
</body>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type="text/javascript">

$(document).ready(function(){
	
	_userAgent = navigator.userAgent;

	if(_userAgent.indexOf("MSIE")>0) {

		document.body.attachEvent("onkeydown", function(event) {
			if( event.keyCode!=13 ) {
				// return false;
			} else {
				$("#formImageSubmit").click();
			}

		});
	} else {
		window.document.body.addEventListener("keydown", function(event) {
		
			if( event.which!=13 ) {
				return false;
			} else {
				$("#formImageSubmit").click();
			}

		});
		
	}

});
</script>