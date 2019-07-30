<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
html,body,div,p{
	padding: 0px;
	margin: 0px;
}
.contianer { width: 100%; height: 100%; padding-top: 21px; background-color: #f5f9fc;  }

/*省略*/
.rest-15 {
	width: 100%;
	height: 15px;
}

/*左侧列表*/
.left-lists-box {
	width: 23.8%;
	/*border: 1px solid black;*/
	display: inline-block;
	height: 100%;
	background-color: #FFF;
}
.question-bank-list-box,.institution-list-box,.course-system-list-box{
	width: 100%;
	height: auto;
	background-color: #fff;
	padding-top: 24px;
	padding-left: 31px;
	padding-right: 38px;
	padding-bottom: 6px;
}
.news-link {
	font-size: 14px;
	color: #666666;
	margin-bottom: 23px;
	width: 100%;
	display: block;
}
.question-bank-bottom-line {
	width: 100%;
	margin-top: 9px;
	background-color:  #ECECEC;
	height: 1px;
}


/*内容*/
.right-lists-box {
	width: 73.4%;
	/*border: 1px solid red;*/
	display: inline-block;	
	float: right;
	margin-right: 15px;
}
.case-lists-box,.expanding-reading-list-box{
	width: 100%;
	height: auto;
	background-color: #fff;
	padding-top: 25px;
	padding-left: 25px;
	padding-right: 14px;
	padding-bottom: 25px;
}
.case-lists-box {
	margin-bottom: 14px;
}
.list-title {
	font-size: 15px;
	color: #333333;
	margin-bottom: 25px;
}
.list-rect-box {
	width: 4px;
	height: 15px;
	display: inline-block;
	vertical-align: middle;
	margin-right: 10px;
}
.rect-color-1 {
	background-color: #20AEFC;
}
.more-btn {
	font-size: 14px;
	color: #FF9F00;
	float: right;
}
.more-btn:hover{
	color: #FF9F00;
}
.more-btn:active{
	color: #FF9F00;
}
.more-btn:visited{
	color: #FF9F00;
}
.each-case-box,.each-expanding-reading-box{
	width: 13.1111%;
	min-width: 115px;
	display: inline-block;
	margin-right: 24px;
	/*height: 159px;
	overflow: hidden;*/
}
.each-case-box img,.each-expanding-reading-box img{
	width: 100%;
	display: block;
	border-radius: 5px;
	box-shadow: 0px 3px 3px #b9c5db;
	max-height: 159px;
}
.case-title,.expanding-reading-title{
	font-size: 14px;
	color: #333333;
	text-align: left;
	margin-top: 16px;
}
</style>
<body>
	<div class="contianer">

		<div class="left-lists-box">

			<div class="question-bank-list-box">
				<!-- 拓展阅读标题 -->
				<p class="list-title">
				 	<span class="list-rect-box rect-color-1"></span> 题库列表 
				 	<a class='more-btn' href="/admin/news/database/lists/more?classId=11">更多 >></a>
				</p>
				<?php for($i=0; $i<count($questionBankData); $i++): ?>
				<a class='news-link' href="/admin/news/readVer2?id=<?php echo $questionBankData[$i]['id']; ?>" title="<?php echo $questionBankData[$i]['title']; ?>"><?php echo $this->cutStr($questionBankData[$i]['title'], 14) ?></a>
				<?php endfor; ?>

				<div class="question-bank-bottom-line"></div>
			</div>

			<div class="institution-list-box">

				<!-- 制度列表标题 -->
				<p class="list-title">
				 	<span class="list-rect-box rect-color-1"></span> 制度列表 
				 	<a class='more-btn' href="/admin/news/database/lists/more?classId=7">更多 >></a>
				</p>

				<?php for($i=0; $i<count($institutionData); $i++): ?>
				<a class='news-link' href="/admin/news/readVer2?id=<?php echo $institutionData[$i]['id']; ?>" title="<?php echo $institutionData[$i]['title']; ?>"><?php echo $this->cutStr($institutionData[$i]['title'], 14) ?></a>
				<?php endfor; ?>

				<div class="question-bank-bottom-line"></div>
			</div>

			<div class="course-system-list-box">

				<!-- 制度列表标题 -->
				<p class="list-title">
				 	<span class="list-rect-box rect-color-1"></span> 课程体系 
				 	<a class='more-btn' href="/admin/news/database/lists/courseSystem">更多 >></a>
				</p>

				<?php for($i=0; $i<count($courseSystemData); $i++): ?>
				<a class='news-link' href="/admin/news/readVer2?id=<?php echo $courseSystemData[$i]['id']; ?>" title="<?php echo $courseSystemData[$i]['title']; ?>"><?php echo $this->cutStr($courseSystemData[$i]['title'], 14) ?></a>
				<?php endfor; ?>

			</div>

		</div>

		<div class="right-lists-box">

			<!-- 案例 -->
			<div class="case-lists-box">

				<!-- 案例标题 -->
				<p class="list-title">
				 	<span class="list-rect-box rect-color-1"></span> 案例 
				 	<a class='more-btn' href="/admin/news/database/search?classId=8">更多 >></a>
				</p>

				<!-- 每个案例 -->
				<?php for($i=0; $i<count($caseData); $i++): ?>
				<a href="/admin/news/readVer2?id=<?php echo $caseData[$i]['id']; ?>"><div class="each-case-box">
					<img alt=" <?php echo $caseData[$i]['cover']; ?> " src="<?php echo empty($caseData[$i]['cover'])? '/images/empty-database.png':$caseData[$i]['cover']; ?>">
					<p class="case-title" title="<?php echo $caseData[$i]['title']; ?>"><?php echo $this->cutStr($caseData[$i]['title'], 6) ?></p>
				</div></a>
				<?php endfor; ?>

			</div>

			<div class="expanding-reading-list-box">

				<!-- 拓展阅读标题 -->
				<p class="list-title">
				 	<span class="list-rect-box rect-color-1"></span> 拓展阅读 
				 	<a class='more-btn' href="/admin/news/database/search?classId=9">更多 >></a>
				</p>

				<!-- 每个拓展阅读 -->
				<?php for($i=0; $i<count($readNewsData); $i++): ?>
				<a href="/admin/news/readVer2?id=<?php echo $readNewsData[$i]['id']; ?>"><div class="each-expanding-reading-box">
					<img alt="<?php echo $readNewsData[$i]['cover']; ?>" src="<?php echo empty($readNewsData[$i]['cover'])? '/images/empty-database.png':$readNewsData[$i]['cover']; ?>">
					<p class="expanding-reading-title" title="<?php echo $readNewsData[$i]['title']; ?>"><?php echo $this->cutStr($readNewsData[$i]['title'], 6) ?></p>
				</div></a>
				<?php endfor; ?>

			</div>

		</div>


	</div>
</body>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type="text/javascript">

$(document).ready(function(){

});
</script>