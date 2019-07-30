<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo empty($questions['title'])? '考试':$questions['title']; ?></title>
    <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="/js/tools/icheck/square/orange.css">
    <link rel="stylesheet" type="text/css" href="/css/shop/css/swiper.min.css">
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
            overflow-x: hidden;
            background-color: #FFF;
            overflow-y: scroll;
            position: relative;
        }
        .cover-img {
        	position: relative;
        	overflow: hidden;
            min-height: 150px;
            background-image: url('/images/exam-cover.png');
            background-size: cover;
        }
        .cover-img img {
        	display: block;
        	width: 100%;
        }

        .text-box {
        	width: 100%;
        	/*height: 100%;*/
        	position: absolute;
        	top: 0px;
        	left: 0px;
        }

        .exam-content {
            font-size: 16px;
            color: #FFFFFF;
            letter-spacing: -0.41px;
            padding: 53px 31px 53px 33px;
            /*padding-top: 53px;*/
            width: 82.9%;
            margin: 0 auto;
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

        .btn-box {
            width: 100%;
            margin-top: 51px;
        }
        .operate-btn {
            border-radius: 8px;
            font-size: 16px;
            color: #FFFFFF;
            background: #20AEFC;
            padding: 7px 22px;
            text-align: center;
        }
        a {
            text-decoration: none;
        }
        a:hover{
            color: #FFF;
            text-decoration: none;
        }
        a:active{
            color: #FFF;
            text-decoration: none;
        }
        a:visited{
            color: #FFF;
            text-decoration: none;
        }
        .right-btn-box .operate-btn {
            float: right;
        }

        /*切换*/
        .exam-swiper-container {
            width: 100%;
            height: 100%;
        }
        .exam-swiper-container .swiper-wrapper {
            width: 100%;
            height: 100%;
        }
        .exam-swiper-container .swiper-wrapper .swiper-slide {
            width: 100%;
            height: 100%;
            overflow: auto  
        }

        .exam-swiper-container .swiper-wrapper .swiper-slide .item-container {
            width: 100%;
            height: 100%;
        }

        .nosee {
            display: none;
        }

        .next-btn {
            font-size: 16px;
            color: #FFFFFF;
            /*padding: 7px 43px;*/
            background: #20AEFC;
            border-radius: 8px;
            width: 133px;
            height: 38px;
            line-height: 38px;
            text-align: center;
            margin: 0px auto;
        }
        .rest-50 {
            width: 100%;
            height: 50px;
        }

        .exam-info-box {
            position: fixed;
            bottom: 0px;
            left: 0px;
            height: 38px;
            background-color: #FFF;
            width: 100%;
            overflow: hidden;
            z-index: 334;
            box-shadow: 0 0 2px 0;
        }
        .list-icon {
            width: 20px;
            height: 20px;
            position: absolute;
            top: 9px;
            left: 12px;
        }
        .questions-count-info {
            width: 100%;
            height: 38px;
            text-align: center;
            line-height: 38px;
            font-size: 16px;
            color: #3F3F3F;
            letter-spacing: 0;
        }
        .current-question-index {
            font-size: 18px;
            color: #000000;
        }

        /*透明背景*/
        .transparent-box {
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0px;
            left: 0px;
            z-index: 333;
            background-color: rgba(0,0,0,0.40);
        }
        .each-type-item {
            background: #F2F2F2;
            background: #F2F2F2;
            font-size: 14px;
            color: #666666;
            padding: 9px 0px 9px 10px;
        }
        .questions-info-box {
            overflow-y: scroll;
        }
        .each-question-index {
            width: 36px;
            height: 36px;
            margin-left: 7.2%;
            margin-top: 3%;
            display: inline-block;
            text-align: center;
            line-height: 36px;
            font-size: 14px;
            border-radius: 100%;
            margin-bottom: 3%;
        }

        .done-index {
            background: #fff;
            color: #7ED321;
            border: 1.5px solid #7ED321;
            font-size: 16px;
        }
        .current-index {
            border: 1px solid #20AEFC;
            color: #20AEFC;
        }
        .wait-index {
            border: 1px solid #979797;
            color: #666666;  
        }
    </style>
</head>
<body>

	<div class="swiper-container exam-swiper-container">
        <div class="swiper-wrapper">
            <?php for ($j=0; $j < $questionsCount; $j++) { $data = $questions['data'][$j]; ?>
                <div class="swiper-slide">
                   <div class='item-container nosee'>
                        <!-- cover -->
                        <div class="cover-img">

                            <p class='exam-content'><?php echo ($j+1) . "." .$data[0]['content']; ?></p>

                        </div>

                        <div class="options-content">

                            <div class="show-options-box" data-question-id="<?php echo $data[0]['id']; ?>">
                                
                                <?php for ($i=0; $i < count($data['options']); $i++) { ?>
                                <p class="show-option-item">
                                    
                                    <?php if($data[0]['html_type']==1){ ?>
                                    <input id="itemId<?php echo $j . '-' . $i; ?>" type="radio" name='answer-<?php echo $j; ?>[]' value="<?php echo $data['options'][$i]['option_index'] ?>" <?php if( isset($answerArr) && !empty($answerArr[($j+1)]) && in_array($i+1, $answerArr[($j+1)]) ) { echo "checked selected"; } ?> >
                                    <?php }else if($data[0]['html_type']==2){ ?>
                                    <input id="itemId<?php echo $j . '-' . $i; ?>"type="checkbox" name='answer-<?php echo $j; ?>[]' value="<?php echo $data['options'][$i]['option_index'] ?>" <?php if( isset($answerArr) && !empty($answerArr[($j+1)]) && in_array($i+1, $answerArr[($j+1)]) ) { echo "checked"; } ?> >
                                    <?php } ?>

                                    <?php if($data[0]['question_type']==1 || $data[0]['question_type']==2){ ?>
                                        <label class='option-title' for="itemId<?php echo $j . '-' . $i; ?>"><?php echo chr($i+1+64) . '.' . $data['options'][$i]['option_content'] ?> </label>
                                    <?php } else if($data[0]['question_type']==3){  ?>
                                        <label class='option-title' for="itemId<?php echo $j . '-' . $i; ?>"><?php echo chr($i+1+64) . '.' . $data['options'][$i]['option_content'] ?>  </label>
                                    <?php } ?>

                                    
                                </p>
                                <?php } ?>
                                
                            </div>

                        </div>

                        <!-- 除了最后一题外都有下一题 -->
                        <?php if( ( $j+1 ) != $questionsCount ): ?>
                        <div class="next-btn" onclick="moveQuestion(<?php echo $j+1; ?>)">
                            下一题
                        </div>
                        <?php endif;?>

                        <!-- 测试给自己一个提交按钮 -->
                        <?php if( ( $j+1 ) == $questionsCount ): ?>
                        <div class="next-btn done-btn" onclick="submitAnswer()">
                            完成并提交
                        </div>
                        <?php endif;?>

                        <div class="rest-50"></div>

                        
                   </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- 显示题目信息 -->
    <div class="transparent-box nosee" onclick="hiddenQuestionsInfo()"></div>

    <div class="exam-info-box" onclick="showQuestionsInfo()">
        <div class="questions-count-info"> <span class='current-question-index'><?php echo $questionId; ?> </span>/<?php echo $questionsCount ?> </div>
        <img class='list-icon' src="/images/list-icon.png">
        <div class='questions-info-box'>
            
        </div>
    </div>

</body>
<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/tools/icheck/icheck.min.js"></script>
<script type="text/javascript" src="/js/swiper.min.js"></script>
<script type="text/javascript" src="/js/tools/layer/layer.js"></script>
<script type="text/javascript">

// 隐藏题目详情
function hiddenQuestionsInfo()
{
    
    $('.exam-info-box').animate({
        height: 38
    }, 300, "linear", function(){
        $('.transparent-box').addClass("nosee");
        $('.list-icon').removeClass("nosee");
    });

}

// 显示题目详情
function showQuestionsInfo()
{
    
    $('.transparent-box').removeClass("nosee");
    $('.list-icon').addClass("nosee");

    // 计算高度
    var clientHeight = $("body").height();
    var scrollHeight = clientHeight-150
    
    $('.exam-info-box').animate({
        height: scrollHeight
    }, 300, "linear", function(){
        
        $.ajax({
            url: "/api/v1/user/exam/info",
            type: "GET",
            async: false,
            dataType: "JSON",
            success: function(response) {
                console.log(response);
                var data = response['data'];
                if(response['status']['success']) {

                    var curIndex = mySwiper.activeIndex+1;
                    console.log(curIndex);

                    var str = "";

                    // 单选
                    if( data[0] != null ) {
                        str += "<div class='each-type-item'>";
                        str +=      "题型: 单选";
                        str += "</div>";
                        for(var i=0; i<data[0].length; i++) {

                            var curClassName = "";
                            if(curIndex==data[0][i].index) {
                                curClassName = " current-index ";
                            } else {
                                if(data[0][i].is_answered) {
                                    curClassName = " done-index ";
                                } else {
                                    curClassName = " wait-index ";
                                }
                            }

                            str +=  "<a href='"+hrefBaseUrl +data[0][i].index +"'><div class='each-question-index " + curClassName + "'>" + data[0][i].index +"</div></a>";

                        }
                        
                    }

                    // 多选
                    if( data[1] != null ) {
                        str += "<div class='each-type-item'>";
                        str +=      "题型: 多选";
                        str += "</div>";
                        for(var i=0; i<data[1].length; i++) {

                            var curClassName = "";
                            if(curIndex==data[1][i].index) {
                                curClassName = " current-index ";
                            } else if(data[1][i].is_answered) {
                                curClassName = " done-index ";
                            } else {
                                curClassName = " wait-index ";
                            }

                            str +=  "<a href='"+hrefBaseUrl +data[1][i].index +"'><div class='each-question-index " + curClassName + "'>" + data[1][i].index +"</div></a>";

                        }
                        
                    }

                    // 判断
                    if( data[2] != null ) {
                        str += "<div class='each-type-item'>";
                        str +=      "题型: 判断";
                        str += "</div>";
                        for(var i=0; i<data[2].length; i++) {

                            var curClassName = "";
                            if(curIndex==data[2][i].index) {
                                curClassName = " current-index ";
                            } else if(data[2][i].is_answered) {
                                curClassName = " done-index ";
                            } else {
                                curClassName = " wait-index ";
                            }

                            str +=  "<a href='"+hrefBaseUrl +data[2][i].index +"'><div class='each-question-index " + curClassName + "'>" + data[2][i].index +"</div></a>";

                        }
                        
                    }

                    $('.questions-info-box').html(str);

                } else {
                    autoMessageNotice(response['status']['message']);
                }
            },
            error: function(err) {
                // console.log(err.responseText);
            }
        });

    });

}

// 真实提交
function doneExam()
{
    $(".done-btn").attr("disabled", "disabled");
    
    $.ajax({
        url: "/api/v1/user/exam/done",
        dataType: "JSON",
        async: false,
        success: function(response) {
            // console.log(response);
            if(response['status']['success']) {
                autoMessageNotice("答案已提交");
                html5.submitSuccess();
                setTimeout(function(){
                    window.location.href = "/front/v1/exam/result?rightCount=" + response['data']['rightCount'] + "&errorCount=" + response['data']['errorCount'] + "&score=" + response['data']['getScore'];
                }, 1400);
            } else {
                autoMessageNotice(response['status']['message']);
            }
        },
        error: function(err) {
            // console.log(err.responseText);
        }
    });

}

// 提交答案前询问
function submitAnswer()
{
    
    layer.confirm('确定要提交吗?', {icon: 3, title:'提示'}, function(index){

        // 获取cookie里答案是否全部填写
        $.ajax({
            url: "/api/v1/user/exam/isAnswerAll",
            type: "GET",
            dataType: "JSON",
            success: function(response) {
                // console.log(response);
                if(response['status']['success']) {
                    
                    if( !response['data'] ) {
                        
                        // 获取详情
                        layer.confirm('您还有部分题目未答，确定要提交吗?', {icon: 3, title:'提示'}, function(index){
                            doneExam();
                            layer.close(index);
                        });

                    } else {
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

// 消息提醒
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

function moveQuestion(index)
{
    
    if( typeof(mySwiper) != "undifined") {
        mySwiper.slideTo(index, 300, true);//切换到第一个slide，速度为1秒
    }

}

$(document).ready(function(){

    // 计算题目索引的高度
    var clientHeight = $("body").height();
    var questionBoxScrollHeight = clientHeight-150-38;
    $('.questions-info-box').css("height", questionBoxScrollHeight)

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
            url: "/api/v1/user/exam/answer",
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
            url: "/api/v1/user/exam/removeAnswer",
            type: "POST",
            dataType: "JSON",
            data: {
                val: curValue,
                questionId: questionId        
            },
            success: function(response) {
                console.log(response);
                if( !response['status']['success'] ) {
                    autoMessageNotice(response['status']['message']);
                }
            },
            error: function(err) {
                console.log(err.responseText);
            }
        });

    });

    var curQuestionId = <?php echo $questionId; ?>;
    hrefBaseUrl = <?php echo "'".$hrefBaseUrl."'"; ?>;

    // 滑动切换上一题 下一题
    mySwiper = new Swiper('.exam-swiper-container',{
        initialSlide: curQuestionId-1,
        spaceBetween: 30,  
        longSwipesRatio:0.1,  
        threshold:50,  
        followFinger:false,  
        observer: true,//修改swiper自己或子元素时，自动初始化swiper  
        observeParents: true,//修改swiper的父元素时，自动初始化swiper
        onInit: function(swiper){
          //Swiper初始化了
          $('.item-container').removeClass("nosee");

        },
        onSlideChangeEnd: function(swiper){
            var  questionIndex = swiper.activeIndex;
            $('.current-question-index').text(parseInt(questionIndex)+1);
        }
    }); 

});
</script>
</html>