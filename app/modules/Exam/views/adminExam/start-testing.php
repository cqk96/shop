<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="/js/tools/icheck/square/orange.css">
<link rel="stylesheet" type="text/css" href="/js/tools/layer/skin/default/layer.css">
<style type="text/css">
 	html,body,div,span,input,p,img,table,tbody,tr,td,a{
        padding: 0px;
        margin: 0px;
    }
    html,body {
        width: 100%;
        height: 100%;
    }
	body {
		background-color: #F5F9FC;
		position: relative;
	}
	img {
		border: none;
	}
	.contianer {
		padding-left: 2%;
		/*padding-bottom: 2%;*/
		height: 100%;
		position: relative;
		/*overflow: hidden;*/
	}
	.rest-top {
		width: 100%;
		height: 20px;
	}
	.diary-content-box {
		width: 66.8%;
		/*margin: 0 auto;*/
		background-color: #FFF;
		height: 96%;
	}
	.content-title-box {
		font-size: 18px;
		color: #333333;
		padding-left: 20px;
		padding-right: 20px;
	}
	.tag-rect-box {
		width: 5px;
		height: 20px;
		display: inline-block;
		vertical-align: middle;
		margin-right: 9px;
	}
	.rect-color-1 {
		background-color: #4A90E2 ;
	}
	.rect-color-2 {
		background: #F5A623;
	}

	.diary-true-content {
		font-size: 14px;
		color: #666666;
		padding-left: 40px;
		padding-right: 40px;	
		text-align: justify;
	}
	.total-approval-box {
		width: 98%;
		margin: 0 auto;
		background-color: #FFF;	
	}

	.row {
		margin-left: 0px;
		margin-right: 0px;
	}

	.master-evaluation-box, .political-instructor-evaluation-box,.branch-secretary-evaluation-box,.leader-evaluation-box  {
	 	padding-left: 40px;
    	padding-right: 40px;
	}
	.approval-td {
	 	    padding-bottom: 20px;
	 }
	.total-approval-box label {

	    vertical-align: top;
	    display: inline-block;
	    margin-right: 2.2%;

	}
	.total-approval-box textarea {
		width: 100%;
    	display: inline-block;
    	padding: 18px 20.16px 17.1px 21.6px;
	}
	.total-approval-box table {
		width: 94%;
		/*border: 1px solid black;*/
		margin: 0 auto;
	}

	textarea {
		resize: none;
	}

	.submit-btn {
		float: right;
		margin-left: 40px;
	}
	.submit-btn:hover {
		cursor: pointer;
	}
	.return-btn {
		float: right;	
		
	}

	/*已评论*/
	.has-approval {
		background-color: #f2f2f2;
	}

	/*占位*/
	.rest-20 {
		width: 100%;
		height: 20px;
	}
	.rest-40 {
		width: 100%;
		height: 40px;
	}

	.item-info {
		margin-right: 10px;
	}
	.info-item {
		margin-bottom: inherit;
	}
	.creator-info {
		padding-left: 20px;
    	padding-right: 20px;
    	font-size: 14px;
		color: #666666;
		letter-spacing: 0;
	}
	.info-icon {
		margin-right: 7px;
		vertical-align: bottom;
	}

	.operate-btn-bar {
		position: absolute;
		width: 100%;
		height: 30px;
		bottom: 42px;
	}

	.fixed-question-box {
		position: absolute;
		right: 0px;
		top: 20px;
		height: 96%;
		width: 28.8%;
		background-color: #FFF;
		padding-left: 20px;
		overflow: auto;
	}
	.question-type-text {
		font-size: 18px;
		color: #333333;
		margin-top: 8px;
		margin-bottom: 4px; 
	}
	.each-question-tag {
		background-image: linear-gradient(-180deg, #EEEEEE 0%, #D8D8D8 100%);
		filter: -ms-filter:progid:DXImageTransform.Microsoft.gradient(startcolorstr=#EEEEEE,endcolorstr=#D8D8D8,gradientType=0);/*IE8*/
    	filter: progid:DXImageTransform.Microsoft.gradient(startcolorstr=#EEEEEE,endcolorstr=#D8D8D8,gradientType=0);
		border: 1px solid #979797;
		width: 60px;
		height: 40px;
		font-size: 18px;
		color: #333333;
		text-align: center;
		line-height: 40px;
		margin-right: 20px;
		margin-bottom: 16px;
		display: inline-block;
	}
	.done-question-tag a{
		color: #FFFFFF !important;
		background-image: linear-gradient(-180deg, #80C3F3 0%, #4A90E2 100%) !important;
		filter: -ms-filter:progid:DXImageTransform.Microsoft.gradient(startcolorstr=#80C3F3,endcolorstr=#4A90E2,gradientType=0);/*IE8*/
    	filter: progid:DXImageTransform.Microsoft.gradient(startcolorstr=#80C3F3,endcolorstr=#4A90E2,gradientType=0);
		/*border: 1px solid #4A90E2 !important;*/
		border: none !important;
	}

	 .options-content {
        width: 82.9%;
        margin: 31px auto 0 auto;
    }
    .option-title {
        margin-left: 19px;
        margin-right: 20px;
    }
    .show-option-item {
        margin-bottom: 25px;
    }
    .show-options-box {
        min-height: 200px;
    }

    .submit-exam-btn {
    	margin-left: 40px;
    	display: inline-block;
    	width: 87px;
    	margin-right: 26px;
    }
    .next-btn { 
    	display: inline-block;
    	width: 87px;
    }
    .submit-exam-btn:hover {
    	cursor: pointer;
    }
    .next-btn:hover {
    	cursor: pointer;
    }
    .each-question-tag a{
    	display: block;
    	width: 100%;
    	height: 100%;
    }
    
    a:link {
    	text-decoration: none;
    	color: #333333;
    }
    a:visited {
    	text-decoration: none;
    	color: #333333;
    }
    a:hover {
    	text-decoration: none;
    	color: #333333;
    }
    a:active {
    	text-decoration: none;
    	color: #333333;
    }

	.rest-11 {
		width: 100%;
		height: 11px;
	}

	.exam-result-box {
		width: 80%;
		margin: 0 auto;
	}
	.result-text {
		font-size: 14px;
		color: #333333;
		line-height: 1.6;
    	text-align: justify;
	}
	.score-box {
		font-size: 14px;
		color: #20AEFC;
	}
	.trophy-img {
		display: block;
		margin: 20px auto 0px auto;
	}
</style>
<body>
<div class="contianer">

	<div class="rest-top"></div>

	<!-- 日志内容 -->
	<div class="diary-content-box">

		<div class="rest-20"></div>
		<div class='content-title-box'>
			<div class="tag-rect-box rect-color-1"></div>
			题目
		</div>
		<div class="rest-20"></div>

		<div class="rest-20"></div>

		<div class="diary-true-content">
			<label class="item-info"><?php echo $questionId . "."; ?>题目: <?php echo $curData[0]['content'] ?></label>
		</div>
		<div class="rest-40"></div>

		<div class="options-content">

            <div class="show-options-box" data-question-id="<?php echo $curData[0]['id']; ?>">
                
                <?php for ($i=0; $i < count($curData['options']); $i++) { ?>
                <p class="show-option-item">
                    
                    <?php if($curData[0]['html_type']==1){ ?>
                    <input id="itemId<?php echo ($questionId-1) . '-' . $i; ?>" type="radio" name='answer-<?php echo ($questionId-1); ?>[]' value="<?php echo $curData['options'][$i]['option_index'] ?>" <?php if( isset($answerArr) && !empty($answerArr[($questionId)]) && in_array($i+1, $answerArr[($questionId)]) ) { echo "checked selected"; } ?> >
                    <?php }else if($curData[0]['html_type']==2){ ?>
                    <input id="itemId<?php echo ($questionId-1) . '-' . $i; ?>"type="checkbox" name='answer-<?php echo ($questionId-1); ?>[]' value="<?php echo $curData['options'][$i]['option_index'] ?>" <?php if( isset($answerArr) && !empty($answerArr[($questionId)]) && in_array($i+1, $answerArr[($questionId)]) ) { echo "checked"; } ?> >
                    <?php } ?>

                    <?php if($curData[0]['question_type']==1 || $curData[0]['question_type']==2){ ?>
                        <label class='option-title' for="itemId<?php echo ($questionId-1) . '-' . $i; ?>"><?php echo chr($i+1+64) . '.' . $curData['options'][$i]['option_content'] ?> </label>
                    <?php } else if($curData[0]['question_type']==3){  ?>
                        <label class='option-title' for="itemId<?php echo ($questionId-1) . '-' . $i; ?>"><?php echo chr($i+1+64) . '.' . $curData['options'][$i]['option_content'] ?>  </label>
                    <?php } ?>

                    
                </p>
                <?php } ?>
                
            </div>

        </div>

        <!-- 最后一题 实心完成 不然是空心完成 -->
        <?php if( $questionId == $questionCount ){ ?>
        	<img class="submit-exam-btn" src="/images/submit-btn@2x.png" onclick="submitAnswer()">
        <?php } else { ?>
        	<img class="submit-exam-btn" src="/images/exam-submit@2x.png" onclick="submitAnswer()">
        <?php } ?>

        <!-- 除了最后一题外都有下一题 -->
        <?php if( !is_null($nextQuestionId) ): ?>
           <a href="<?php echo $hrefBaseUrl . $nextQuestionId; ?>">	<img class="next-btn" src="/images/next-question-btn@2x.png" > </a>
        <?php endif;?>

	</div>

	<!-- 题目 -->
	<div class="fixed-question-box">
		<div class="rest-11"></div>

		<?php if( !is_null($data[0]) ){?>
			<div class="question-type-text">单选</div>

			<?php for ($i=0; $i < count($data[0]); $i++) { ?>
			<div class="each-question-tag <?php if($data[0][$i]['is_answered']) { echo "done-question-tag"; } ?>">
				<a href="<?php echo $hrefBaseUrl . $data[0][$i]['index']; ?>"><?php echo $data[0][$i]['index']; ?></a>
			</div>
			<?php } ?>
		<?php }?>

		<?php if( !is_null($data[1]) ){?>
			<div class="question-type-text">多选</div>

			<?php for ($i=0; $i < count($data[1]); $i++) { ?>
			<div class="each-question-tag <?php if($data[1][$i]['is_answered']) { echo "done-question-tag"; } ?>">
				<a href="<?php echo $hrefBaseUrl . $data[1][$i]['index']; ?>"><?php echo $data[1][$i]['index']; ?></a>
			</div>
			<?php } ?>
		<?php }?>

		<?php if( !is_null($data[2]) ){?>
			<div class="question-type-text">判断</div>

			<?php for ($i=0; $i < count($data[2]); $i++) { ?>
			<div class="each-question-tag <?php if($data[2][$i]['is_answered']) { echo "done-question-tag"; } ?>">
				<a href="<?php echo $hrefBaseUrl . $data[2][$i]['index']; ?>"><?php echo $data[2][$i]['index']; ?></a>
			</div>
			<?php } ?>
		<?php }?>

	</div>

</div>

</body>
<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/tools/icheck/icheck.min.js"></script>
<script type="text/javascript" src="/js/swiper.min.js"></script>
<script type="text/javascript" src="/js/tools/layer/layer.js"></script>
<script type="text/javascript">

// 真实提交
function doneExam()
{
    $(".done-btn").attr("disabled", "disabled");
    
    $.ajax({
        url: "/back/api/v1/user/exam/done",
        dataType: "JSON",
        async: false,
        success: function(response) {
            // console.log(response);
            if(response['status']['success']) {
                autoMessageNotice("答案已提交");

                var getScore = response['data']['getScore'];
                var rightCount = response['data']['rightCount'];
                var errorCount = response['data']['errorCount'];

                // 结果页
			    var content = '';
			    content += '<div class="exam-result-box">';
			    content += 		'<p class="result-text">本次考试已结束，您的考试成绩为 <span class="score-box">' + getScore + '分</span>，正确答题 <span class="score-box">' + rightCount + '题</span>，错误答题 <span class="score-box">' + errorCount + '题</span>。</p>';
			    content +=  	'<img class="trophy-img" src="/images/trophy.png" />';
			    content +=  '</div>';
			    layer.open({
			    	title: "考试结果",
				    content: content,
				    area: '503px',
				    skin: 'msg',
				    btn1: function(){
				    	// @todo 前往考试结果列表页面
				    	window.location.href = "/admin/answerExams";
				    },
				    cancel: function(){
				    	// @todo 
				    	window.location.href = "/admin/answerExams";
				    }
				});

                // @todo    出现结果页
                // setTimeout(function(){
                // 	// 暂时返回列表
                // 	window.location.href = "/admin/exams/testLists";
                //     // window.location.href = "/front/v1/exam/result?rightCount=" + response['data']['rightCount'] + "&errorCount=" + response['data']['errorCount'] + "&score=" + response['data']['getScore'];
                // }, 1400);
            } else {
                autoMessageNotice(response['status']['message']);
            }
        },
        error: function(err) {
            // console.log(err.responseText);
        }
    });

}

// 提示信息
function autoMessageNotice(content)
{
	var time =  arguments[1] ? arguments[1] : 2000;//设置参数b的默认值为2 
	layer.open({
		id: 1,
	    content: content,
	    skin: 'msg',
	    time: time //2m秒后自动关闭
	});
}

// 提交答案前询问
function submitAnswer()
{
    
    layer.confirm('确定要提交吗?', {icon: 3, title:'提示'}, function(index){

        // 获取cookie里答案是否全部填写
        $.ajax({
            url: "/back/api/v1/user/exam/isAnswerAll",
            type: "GET",
            dataType: "JSON",
            success: function(response) {
                // console.log(response);
                if(response['status']['success']) {
                    
                    if( !response['data'] ) {
                        
                        // 获取详情
                        layer.confirm('您还有部分题目未答，确定要提交吗?', {icon: 3, title:'提示'}, function(index){
                        	$('.submit-exam-btn').unbind("click");
                            doneExam();
                            layer.close(index);
                        });

                    } else {
                    	$('.submit-exam-btn').unbind("click");
                        doneExam();
                    }

                } else {
                    autoMessageNotice(response['status']['message']);
                }

            },
            error: function(err) {
                // console.log(err.responseText);
            }
        });

        layer.close(index);

    });

}

$(document).ready(function(){

	$('input').iCheck({ 
        labelHover : false, 
        cursor : true, 
        checkboxClass : 'icheckbox_square-orange', 
        radioClass : 'iradio_square-orange', 
        increaseArea : '20%'
    });

    // 增加选择
    $('input').on('ifChecked', function(event){

        var target = event.currentTarget;
        var curValue = $(target).val();
        var questionId = $(target).parent().parent().parent().attr("data-question-id");
        
        $.ajax({
            url: "/back/api/v1/user/exam/answer",
            type: "POST",
            dataType: "JSON",
            data: {
                val: curValue,
                questionId: questionId        
            },
            success: function(response) {
                // console.log(response);
                if( !response['status']['success'] ) {
                    autoMessageNotice(response['status']['message']);
                }
            },
            error: function(err) {
                // console.log(err.responseText);
            }
        });

    });

    // 增加多选移除
    $('input[type=checkbox]').on('ifUnchecked', function(event){

        var target = event.currentTarget;
        var curValue = $(target).val();
        var questionId = $(target).parent().parent().parent().attr("data-question-id");
        $.ajax({
            url: "/back/api/v1/user/exam/removeAnswer",
            type: "POST",
            dataType: "JSON",
            data: {
                val: curValue,
                questionId: questionId        
            },
            success: function(response) {
                // console.log(response);
                if( !response['status']['success'] ) {
                    autoMessageNotice(response['status']['message']);
                }
            },
            error: function(err) {
                console.log(err.responseText);
            }
        });

    });

});

</script>