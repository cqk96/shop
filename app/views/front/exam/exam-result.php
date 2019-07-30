<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>考试结果</title>
    <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <style type="text/css">
	    html,body,div,span,input,p,img,table,tbody,tr,td,a{
	        padding: 0px;
	        margin: 0px;
	    }
	    html,body {
	        width: 100%;
	        height: 100%;
	    }
	    .body-container {
	    	width: 100%;
	    	height: 100%;
	    	background-color: #FFF;
	    }
	    .info-box {
	    	background-image: linear-gradient(0deg, rgba(0,136,255,0.98) 0%, #19ABFC 100%);
	    	width: 100%;
	    	min-height: 204px;
	    }

	    .score-box {
	    	font-size: 18px;
			color: #FFFFFF;
			letter-spacing: 1.8px;
			text-align: center;
	    }
	    .get-score {
	    	font-size: 36px;
			color: #FFFFFF;
			letter-spacing: 1.8px;
	    }
	    .score-desc-box {
	    	font-size: 18px;
			color: #FFFFFF;
			letter-spacing: 1.08px;	
			text-align: center;
	    }
	    .seperate-line {
	    	width: 70.9%;
	    	margin: 15px auto 16px auto;
	    	height: 1px;
	    	background-color: #FFF;
	    }

	    .answer-count-box {
	    	width: 95%;
	    	margin: 0 auto;
	    }
	    .right-count-box {
	    	width: 50%;
	    	display: inline-block;
	    	font-size: 15px;
			color: #FFFFFF;
			letter-spacing: 0.9px;
			text-align: center;
	    }
	    .error-count-box {
	    	width: 49%;
	    	float: right;
	    	font-size: 15px;
			color: #FFFFFF;
			letter-spacing: 0.9px;
			border-left: 1px solid;
			text-align: center;
	    }
	    .answer-count {
	    	font-size: 30px;
			color: #FFFFFF;
			letter-spacing: 1.8px
	    }

	    .other-img-box {
	    	width: 100%;
	    	height: auto;
	    	position: absolute;
		    bottom: 0px;
		    left: 0px;
	    }
	    .other-img-box img{
	    	display: block;
	    	max-width: 100%;
	    	margin: 0 auto;
	    }

	    .trophy-box {
	    	width: 72%;
	    	margin: 32px auto 0px auto;
	    	height: auto;
	    }

	    .trophy-box img {
	    	display: block;
	    	width: 100%;
	    }

	    .return-btn {
	    	margin: 60px auto 30px auto;
	    	display: block;
	    }

	    .wave-icon {
	    	position: relative;
	    	display: block;
	    	width: 100%;
	    	height: auto;
	    	/*bottom: 0px;
	    	left: 0px;*/
	    }

	    /*缺省*/
	    .rest-18 {
	    	width: 100%;
	    	height: 18px;
	    }

    </style>
</head>
<body>
	<div class='body-container'>
		<div class='info-box'>

			<div class='rest-18'></div>

			<!-- <button type="button" onclick="javascript:window.location.reload();">临时刷新按钮</button> -->

			<!-- 分数 -->
			<div class='score-box'>
				<span class='get-score'><?php echo $score; ?></span>分
				<p class='score-desc-box'>考试成绩</p>
			</div>

			<div class="seperate-line"> </div>

			<div class='answer-count-box'>
				
				<div class="right-count-box">
					<span class='answer-count'><?php echo $rightCount; ?></span>题
					<p class='answer-count-desc-box'>正确答题数目</p>
				</div>

				<div class="error-count-box">
					<span class='answer-count'><?php echo $errorCount; ?></span>题
					<p class='answer-count-desc-box'>错误答题数目</p>
				</div>

			</div>

		</div> <!-- end info -->

		<div class='trophy-box'>
			<img src="/images/trophy.png">
		</div>

		<img class='return-btn' src="/images/return-list.png" onclick="html5.closeWindow()">

		<img class='wave-icon' src="/images/wave.png">

		<!-- <div class='other-img-box'>
			<img src="/images/exam-result-bg.png">
		</div> -->

	</div>
</body>
<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){

});
</script>
</html>