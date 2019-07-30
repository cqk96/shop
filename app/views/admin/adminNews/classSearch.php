<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
html,body,div,p,img,a,form{
	padding: 0px;
	margin: 0px;
}
html, body {
	width: 100%;
	height: 100%;
}
body {
	background-color: #FFF;
}
.contianer {
	background-color: #FFF;
	width: 100%;
	/*height: 100%;*/
}
.rest-top {
	width: 100%;
	height: 29px;
}
.search-bar {
	width: 68.9%;
	height: 35px;
	margin:  auto;
	overflow: hidden;
	position: relative;
	/*border: 1px solid black;*/
}
input[type=text].search-input  {
	/*width: 85.34%;*/
	/*float: left;*/
	width: 100%;
	text-align: center;
	border-color: #4A90E2;
}
.search-btn {
	/*width: 14.66%;*/
	height: 100%;
	position: absolute;
	top: 0px;
	right: 0px;
}
.nosee {
	display: none;
}

/*暂未开始搜索*/
.no-search-box {
	width: 68.5%;
	margin: 0 auto;
	/*border: 1px solid black;*/
	border-top: 1px solid transparent;
	padding-top: 35px;
}
.section-title {
	/*margin-top: 35px;*/
	margin-bottom: 27px;
	font-size: 20px;
	color: #333333;
	text-align: center;
}
.hot-news-box,.recommended-reading-news-box {
	width: 100%;
	height: auto;
	min-height: 200px;
}
.hot-news-box .each-new-box,.recommended-reading-news-box .each-new-box{
	min-width: 115px;
	display: inline-block;
    width: 12.7%;
    margin-left: 1.2%;
    margin-right: 1.4%;
}
.hot-news-box .each-new-box img,.recommended-reading-news-box .each-new-box img{
	display: block;
	width: 100%;
	max-height: 158px;
}
.hot-news-box .each-new-box .new-title, .recommended-reading-news-box .each-new-box .new-title{
	margin-top: 15px;
	margin-bottom: 15px;
	font-size: 15px;
	color: #333333;
}

/*没有结果*/
.no-search-result-box {
	width: 68.5%;
	margin: 0 auto;
	height: auto;
	padding-top: 35px;
}
.no-search-result-box img {
	display: block;
	margin: 0 auto;
}
.no-search-result-remind {
	text-align: center;
	margin-top: 34px;
	font-size: 15px;
	color: #333333;
}

/*结果列表*/
.news-lists {
	width: 68.5%;
	margin: 0 auto;
	height: auto;
	border-top: 1px solid transparent;
}
.each-data-item {
	width: 77.7777%;
	margin: 45px auto 0px auto;
	/*border: 1px solid red;*/
	height: auto;
	overflow: hidden;
	position: relative;
}
.each-data-item .cover-box {
	width: 30%;
	display: inline-block;
	/*min-width: 247px;*/
	max-height: 342px;
    overflow: hidden;
}
.each-data-item .cover-box img{ 
	display: block;
	width: 100%;
	border-radius: 5px;
}
.data-detail-box {
	width: 60%;
	/*margin-left: 8%;*/
	/*display: inline-block;*/
	float: right;
}
.data-title {
	font-size: 24px;
	color: #00418C;
	text-align: center;
}
.simple-descrip {
	font-size: 13px;
	color: #666666;
	line-height: 30px;
	text-align: justify;
	height: 23%;
	overflow: hidden;
}
.read-detail-btn {
	display: block;
	/*margin: 19px auto 0 auto;*/
	position: absolute;
	left: 62%;
	bottom: 0px;
}

.rest-20 {
	width: 100%;
	height: 20px;
}
</style>
<body>
	<div class="contianer">

		<div class="rest-top"></div>

		<form id="searchForm" action="/admin/news/database/search " method="GET">
			<div class="search-bar">
				<input class="search-input form-control" id="title" name="title" type="text" placeholder="输入文章名进行搜索" value="<?php echo empty($search) ?'':$search ?>" >
				<input id="classId" type="hidden" name="classId" value="<?php echo $classId; ?>" />
				<input id="searchBtn" class="search-btn" type="image" src="/images/search-case-btn.png">
			</div>
		</form>

		<!-- 尚未进行查询 -->
		<?php if( is_null($search) ):?>
		<div class="no-search-box">

			<p class="section-title">- 热门搜索 -</p>

			<div class="hot-news-box">

				<?php for ($i=0; $i < count($hotSearchData); $i++): ?>
					<a href="/admin/news/readVer2?id=<?php echo $hotSearchData[$i]['id']; ?>">
						<div class="each-new-box">
							<img class="" alt="<?php echo $hotSearchData[$i]['cover']; ?>" src="<?php echo empty($hotSearchData[$i]['cover'])? '/images/empty-database.png':$hotSearchData[$i]['cover']; ?>" />
							<p class="new-title" title="<?php echo $hotSearchData[$i]['title']; ?>"><?php echo $this->cutStr($hotSearchData[$i]['title'], 7); ?></p>
						</div>
					</a>
				<?php endfor; ?>

			</div>

			<p class="section-title">- 推荐阅读 -</p>
			<div class="recommended-reading-news-box">

				<?php for ($i=0; $i < count($recommendData); $i++): ?>
				<a href="/admin/news/readVer2?id=<?php echo $recommendData[$i]['id']; ?>">
					<div class="each-new-box">
						<img class="" alt="<?php echo $recommendData[$i]['cover']; ?>" src="<?php echo empty($recommendData[$i]['cover'])? '/images/empty-database.png':$recommendData[$i]['cover']; ?>" />
						<p class="new-title" title="<?php echo $recommendData[$i]['title']; ?>"><?php echo $this->cutStr($recommendData[$i]['title'], 7); ?></p>
					</div>
				</a>
				<?php endfor; ?>

			</div>

		</div>
		<?php endif; ?>

		<!-- 没有结果 -->
		<?php if( isset($data) && empty($data) ): ?>
		<div class="no-search-result-box">
			<img src="/images/no-result.png" />
			<p class="no-search-result-remind">在该关键词下查不到任何文章</p>
		</div>
		<?php endif; ?>

		<!-- 有查询结果 -->
		<?php if( isset($data) && !empty($data) ): ?>
		<div class="news-lists">

			<?php for ($i=0; $i < count($data); $i++) { ?>
				<div class="each-data-item">
					<div class="cover-box">
						<img alt="<?php echo $data[$i]['cover']; ?>" src="<?php echo empty($data[$i]['cover'])? '/images/empty-database.png':$data[$i]['cover']; ?>" />
					</div>

					<div class="data-detail-box">
						<p class="data-title"  title="<?php echo $data[$i]['title']; ?>"><?php echo $this->cutStr($data[$i]['title'], 16); ?></p>
						<div class="simple-descrip">
							<?php echo $this->cutStr($data[$i]['description'], 140); ?>
						</div>

						<a href="/admin/news/readVer2?id=<?php echo $data[$i]['id']; ?>"><img class="read-detail-btn" src="/images/read.png"></a>
					</div>
				</div>
			<?php } ?>

			<div class='rest-20'></div>

		</div>
		<?php endif;?>

	</div>
</body>
<?php include_once('../app/views/admin/_footer.php') ?>
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

	var hasData = <?php echo intval($hasData); ?>;

	if( hasData ) {

		$.ajax({
			url: "/api/v1/news/createSearchCount",
			type: "POST",
			dataType: "JSON",
			data: {
				searchStr: $.trim( $("#title").val() ),
				classId: $.trim( $("#classId").val() )
			},
			success: function(response){
				// console.log(response);
			},
			error: function(err) {
				// console.log(err.responseText);
			}
		});

	}
	
	_userAgent = navigator.userAgent;
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