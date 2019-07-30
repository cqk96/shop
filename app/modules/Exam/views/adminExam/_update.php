<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo empty($questions)? $data['title']:$questions['title']; ?>的考题修改</title>
    <link rel="stylesheet" href="/js/tools/layui/css/layui.css">
    <style>
    /*更改全局默认样式*/
/* ----------------Reset Css--------------------- */
html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li,
fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, figcaption, figure, footer, header, hgroup, menu, nav, section, summary,
time, mark, audio, video, input  {
    margin: 0;
    padding: 0;
    border: none;
    outline: 0;
    font-size: 100%;
    /*vertical-align: baseline;*/
}

html, body, form, fieldset, p, div, h1, h2, h3, h4, h5, h6 {
    -webkit-text-size-adjust: none;
}

article, aside, details, figcaption, figure, footer, header, hgroup, menu, nav, section {
    display: block;
}

body {
    font-family: PingFangSC-Medium;
}

ol, ul {
    list-style: none;
}

blockquote, q {
    quotes: none;
}

blockquote:before, blockquote:after, q:before, q:after {
    content: '';
    content: none;
}

ins {
    text-decoration: none;
}

del {
    text-decoration: line-through;
}

table {
    border-collapse: collapse;
    border-spacing: 0;
}
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
a, a:hover {
    text-decoration: none;
}
ul {
    list-style: none;
}
input {
    overflow:auto;
    background-attachment: fixed;
    background-repeat: no-repeat;
    border-style: solid;
    border: 0px;
    outline: none;
}


button {
    outline: none;
    border-radius: 3px;
    color: #ffffff;
    display: block;
    cursor: pointer;
}body {
    overflow-y: auto;
    background-color: #F5F9FC ;
}
.middle-content {
    width:95%;
    margin: 30px auto;
}
.title {
    background-image: linear-gradient(-180deg, #80C3F3 0%, #4A90E2 100%);
    border: 1px solid #C4C4C4;
}
.title span {
    line-height: 40px;
    color: #ffffff;
    font-size: 16px;
    padding-left: 20px;
}
.list-content {
    background-color: #ffffff;

    overflow: hidden;
}
/*选择框*/
.form-select {
    width: 97%;
    margin:20px auto;
}
/*.layui-form-item {*/
    /*margin-bottom: 0px!important;*/
/*}*/
.layui-input {
    /*height: 34px;*/
    border-radius: 8px!important;
}
.layui-form-select dl dd.layui-this{
    background-color: #4A90E2!important;
}



/*按钮*/

.btn{
    height: 34px!important;
    line-height: 34px!important;
    width: 100px;
    color: #ffffff;
    border-radius: 8px!important;
}
.small-btn {
    height: 28px!important;
    line-height: 28px!important;
    font-size: 12px!important;
    color: #ffffff;
    border-radius: 8px!important;
}
.orange-btn {
    background-image: linear-gradient(-180deg, #fad45e 0%, #ee6723 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(GradientType = 1, startColorstr = #fad45e, endColorstr = #ee6723);
}
.blue-btn {
    background-image: linear-gradient(-180deg, #71c1f1 0%, #4d93e3 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(GradientType = 1, startColorstr = #71c1f1, endColorstr = #4d93e3);
}
.blue-btn:hover {
    
}
.green-btn {
    background-image: linear-gradient(-180deg, #aae54d 0%, #449422 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(GradientType = 1, startColorstr = #aae54d, endColorstr = #449422);
}
.gray-btn {
    background-image: linear-gradient(-180deg, #e8e8e8 0%, #9d9d9d 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(GradientType = 1, startColorstr = #e8e8e8, endColorstr = #9d9d9d);
}
/*表格列表*/
.form-list {
    width: 97%;
    margin: 30px auto;
}
.form-table {
    width:100%;
    border: 1px solid #C4C4C4;
}
.form-table  thead tr th {
    line-height: 40px;
}
.form-table  tbody tr td {
    text-align: center;
    line-height: 40px;
}
.table-icon {
    margin-top: -3px;
    margin-right: 5px;
}

/*带颜色的字体*/
.orange-text {
    color: #F5AE63;
}
.gary-text {
    color: #A8ACB9!important;
}
        body {
            background-color: #fff;
        }

        .create-content {
            width: 76%;
            margin: 0 auto;
        }

        .question {
            /*min-width: 1000px;*/
        }

        .add-submit {
            width: 60%;
            margin: 20px auto;
            text-align: center;
        }

        .layui-btn-primary {
            border-color: #4A90E2;
            color: #4A90E2;
        }

        .layui-btn-primary:hover {
            border-color: #3253D6;
            color: #3253D6;
        }

        .submit-btn {
            margin-left: 30px;
            cursor: pointer;
        }

        /*分数提示*/
        .grade-hint {
            border: 2px solid #4A90E2;
            border-radius: 5px;
            margin: 30px 0;
            width: 100%;
        }

        .hint-img {
            margin: 0 15px;
        }

        .grade-hint span {
            display: inline-block;
            line-height: 42px;
            font-size: 16px;
            color: #4A90E2;
        }

        .grade-hint span p {
            display: inline-block;
            line-height: 42px;
            font-size: 16px;
            color: #4A90E2;
        }

        /*题目内容*/
        .item-content {
            width: 100%;
            background-color: #F2F2F2;
            /*min-width: 1000px;*/
        }

        .example {
            padding: 5px;
        }

        .example-list {
            margin-top: 10px;
        }

        .title-text,
        .topic-title {
            width: 100% !important;
            float: none;
            text-align: left !important;
            font-size: 14px;
        }

        .topic-title {
            color: #333333;
            font-size: 14px;
            font-weight: bold;
        }
        .layui-form-radio {
            display: table;
            margin:6px 10px 0 5px;
        }
        .layui-form-radio i {
            font-size: 18px !important;
        }

        .form-margin {
            margin-left: 15px !important;
        }

        .layui-form-radio i:hover, .layui-form-radioed i {
            color: #3253D6 !important;
        }

        .line {
            background-color: #979797 !important;
            color: #979797 !important;
            width: 96%;
            margin: 30px auto;
        }

        .layui-form-radio span {
            color: #2A2A2A;
            letter-spacing: 1px;
            line-height: 30px;
        }

        /*创建题目内容*/
        .create-form {
            width: 90%;
            margin: 0 auto;
            padding-bottom: 25px;
        }

        .layui-textarea,
        .layui-input {
            background-color: rgba(255, 255, 255, 0);
            border: 1px solid #979797;
        }

        .half-div {
            width: 45%;
        }

        .two-thirds {
            width: 70%;
        }
        /*多选*/
        .layui-form-checkbox span {
            display: inline-block;
            line-height: 30px;
            letter-spacing: 1px;
            color: #333333!important;
        }
        .layui-form-checked[lay-skin=primary] i {
            border-color: #4A90E2 !important;
            background-color: #4A90E2 !important;
        }

        .layui-form-checkbox[lay-skin=primary]:hover i {
            border-color: #4A90E2 !important;
            background-color: #4A90E2 !important;
        }

        .layui-form-item .layui-form-checkbox[lay-skin=primary] {
            display: table;
            margin: 15px 0 0 5px;
        }

        .edit-box {
            text-align: center;
            margin: 20px 0;
        }
        .display-none {
            display: none;
        }
        .display-block {
            display: block;
        }

        .item-content {
            margin-bottom: 41px;
        }
        .background-white {
            background-color: #FFF;
        }
        textarea {
            resize: none !important;
        }
        .delete-btn {
            float: right;
            margin-right: 12%;
        }
        .operate-btn-row img:hover {
            cursor: pointer;
        }
        .topbar-fixed {
            position: fixed;
            background: #FFF;
            z-index: 333;
            width: 76%;
            left: 12%;
        }

        .rest-bar {
            width: 100%;
            height: 72px;
        }

        .create-option-btn-box {
            margin-top: 10px;
            padding-left: 15px;
        }
        .create-option-btn-box img{
            max-width: 100%;
            display: block;
            /*margin: 0 auto;*/
        }
        .create-option-btn-box img:hover{
            cursor: pointer;
        }
        .option-remind-box {
            padding-top: 30px;
            padding-bottom: 7px;
        }
        .set-option-box {
            padding-left: 15px;
        }
        .right-or-not-text-box {
            text-align: right;
        }

        .save-edit-btn-box img:hover{
            cursor: pointer;
        }

        /*选项*/
        .option-letter {
            border: 1px solid #979797;
            border-radius: 4px;
            color: #979797;
            margin: 0 30px 0 20px!important;
            width: 35px;
            line-height: 34px;
            text-align: center;

        }
        .option-text {
            padding-left: 10px;
            font-size: 14px;
            height: 36px;
            border: 1px solid #979797;
            background: transparent;
            border-radius: 4px;
            width: 75%;
            line-height: 36px;
        }
        .delete-box {
            margin-left: 30px;
        }
        .delete-img {
            cursor: pointer;
        }

.layui-inline, img {
    letter-spacing: 0;
    /*border: 1px solid;*/
    word-spacing: 0;
}
    </style>
</head>
<body>
<div class="create-content ">
    <!--分数提示-->
    <div class="question">
        <div class="grade-hint">
            <img class="hint-img" src="/images/dengpao.png" alt="">
            <span>分数提示：<p> <span class='current-score'>0</span>／<span class='total-score'>100</span></p></span>
        </div>
    </div>
    <div class="rest-bar display-none"></div>

    <!-- 表单 -->
    <form id="myForm" action="/admin/exams/doUpdate" method="POST">

        <input type="hidden" name="recordId" value="<?php echo $id; ?>" >

        <?php for ($i=0; $i < count($questionsData); $i++) { $options = $questionsData[$i]['options']; ?>
        <div class="item-content background-white">
            <input type="hidden" name="question-name[]"  class="question-name" value="<?php echo $questionsData[$i][0]['content']; ?>" >
            <input type="hidden" name="question-score[]" class="question-score" value="<?php echo $questionsData[$i][0]['score']; ?>" >
            <input type="hidden" name="question-type[]" class="question-type" value="<?php echo $questionsData[$i][0]['question_type']; ?>" >
            <div class="example">
                <div class="layui-form example-list">

                    <!-- 单选题显示 -->
                    <div class="layui-form-item radio display-none <?php if( $questionsData[$i][0]['question_type']==1) { echo ' display-block '; }  ?>">
                        <label class="layui-form-label topic-title">
                            <span class="serial-num"><?php echo $i+1; ?>．</span>
                            <span class="item-title"><?php echo $questionsData[$i][0]['content']; ?></span>
                            <span class="orange-text display-none">　(分值: <span class="current-question-score"> <?php echo $questionsData[$i][0]['score']; ?> </span>分)</span>
                        </label>
                        <div class="layui-input-block single-choice form-margin" style>

                            <?php if( $questionsData[$i][0]['question_type']==1){ ?>
                                <?php for ($j=0; $j < count($options); $j++) { ?>
                                <div class="layui-form layui-input-block" style="margin-left: 0">
                                    <input type="radio" name="question-<?php echo ($i+1); ?>-options[]" value="<?php echo $j+1; ?>" title="<?php echo $options[$j]['option_content']; ?>" <?php if( $options[$j]['is_right'] ) { echo "checked=true"; } ?> >
                                    <input type="hidden" name="hidden-question-<?php echo ($i+1); ?>-options[]" value="<?php echo $options[$j]['option_content']; ?>" />
                                </div>
                                <?php } ?>
                            <?php } ?>

                        </div>
                    </div>

                    <!-- 多选题显示 -->
                    <div class="layui-form-item multiple display-none  <?php if( $questionsData[$i][0]['question_type']==3) { echo ' display-block '; }  ?>">
                        <label class="layui-form-label topic-title">
                            <span class="serial-num"><?php echo $i+1; ?>．</span>
                            <span class="item-title"><?php echo $questionsData[$i][0]['content']; ?></span>
                            <span class="orange-text display-none">　(分值: <span class="current-question-score"><?php echo $questionsData[$i][0]['score']; ?></span>分)</span>
                        </label>
                        <div class="layui-input-block multiple-choice form-margin ">

                            <?php if( $questionsData[$i][0]['question_type']==3){ ?>
                                <?php for ($j=0; $j < count($options); $j++) { ?>
                                <div class="layui-form layui-input-block" style="margin-left: 0">
                                    <input type="checkbox" lay-skin="primary" name="question-<?php echo ($i+1); ?>-options[]" value="<?php echo $j+1; ?>" title="<?php echo $options[$j]['option_content']; ?>" <?php if( $options[$j]['is_right'] ) { echo "checked=true"; } ?> >
                                    <input type="hidden" name="hidden-question-<?php echo ($i+1); ?>-options[]" value="<?php echo $options[$j]['option_content']; ?>" />
                                </div>                                
                                <?php } ?>
                            <?php } ?>

                        </div>
                    </div>

                    <!-- 判断题显示 -->
                    <div class="layui-form-item judge display-none  <?php if( $questionsData[$i][0]['question_type']==2) { echo ' display-block '; }  ?> ">
                        <label class="layui-form-label topic-title">
                            <span class="serial-num"><?php echo $i+1; ?>．</span>
                            <span class="item-title"><?php echo $questionsData[$i][0]['content']; ?></span>
                            <span class="orange-text display-none">　(分值: <span class="current-question-score"><?php echo $questionsData[$i][0]['score']; ?></span>分)</span>
                        </label>
                        <div class="layui-input-block judge-choice form-margin">

                            <?php if( $questionsData[$i][0]['question_type']==2){ ?>
                            <div class="layui-form layui-input-inline">
                                <?php for ($j=0; $j < count($options); $j++) { ?>
                                <div class="layui-form layui-input-block" style="margin-left: 0">
                                    <input type="radio" name="question-<?php echo ($i+1); ?>-options[]" value="<?php echo $j+1; ?>" title="<?php echo $options[$j]['option_content']; ?>" style="float:left;" <?php if( $options[$j]['is_right'] ) { echo "checked='true'"; } ?> >
                                    <input type="hidden" name="hidden-question-<?php echo ($i+1); ?>-options[]" value="<?php echo $options[$j]['option_content']; ?>" />
                                </div>                                
                                <?php } ?>
                            </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>    
                <hr class="line">
                <div class="layui-row operate-btn-row display-none display-block">
                    <div class="layui-col-md9">&nbsp;</div>
                    <div class="layui-col-md3">
                        <img src="/images/edit-btn.png" onclick="showEditContent(this)" />
                        <img class="delete-btn" src="/images/delete-btn.png" onclick="deleteQuestion(this)" />
                    </div>
                </div>
            </div>

            <div class="create-content-list display-none">
                <div class="layui-form radio-multiple-content create-form">
                    <div class="layui-form-item layui-inline half-div">
                        <label class="layui-form-label title-text">题目类型</label>
                        <div class="layui-input-block form-margin">
                            <select class="question-type-select" lay-verify="required" lay-filter="selectType">
                                <option value="0" <?php if( $questionsData[$i][0]['question_type']==0) { echo " selected"; }  ?> >-- 请选择题目类型 --</option>
                                <option value="1" <?php if( $questionsData[$i][0]['question_type']==1) { echo " selected"; }  ?> >单项选择</option>
                                <option value="2" <?php if( $questionsData[$i][0]['question_type']==3) { echo " selected"; }  ?> >多项选择</option>
                                <option value="3" <?php if( $questionsData[$i][0]['question_type']==2) { echo " selected"; }  ?> >判断题</option>
                            </select>
                        </div>
                    </div>

                    <div class="layui-form-item layui-inline half-div">
                        <label class="layui-form-label title-text">题目分值</label>
                        <div class="layui-input-block form-margin">
                           <input type="text" maxLength="2" lay-verify="required" placeholder="请输入分值" autocomplete="off" class="layui-input currentScore" value="<?php echo $questionsData[$i][0]['score']; ?>"  onkeyup="this.value=this.value.replace(/\D/g,'') " onafterpaste="this.value=this.value.replace(/\D/g,'') " />
                        </div>
                    </div>

                    <!-- 第一个题目的内容模块 -->
                    <div class="first-part-content display-none <?php if( $questionsData[$i][0]['question_type']==1 || $questionsData[$i][0]['question_type']==3 ) { echo ' display-block '; }  ?> " >
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label title-text gray-text">题目内容</label>
                            <div class="layui-input-block form-margin">
                               <textarea placeholder="请输入题目内容" class="layui-textarea radio-multiple-title question-content"><?php echo $questionsData[$i][0]['content']; ?></textarea>
                            </div>
                        </div>
                        <div class="layui-row option-remind-box">
                            <div class="layui-col-md9 set-option-box">选项设置</div>
                            <div class="layui-col-md3 right-or-not-text-box">是否正确答案</div>
                        </div>

                        <div class="radio-options display-none <?php if( $questionsData[$i][0]['question_type']==1 ){ echo ' display-block '; } ?>">
                            <?php if( $questionsData[$i][0]['question_type']==1){ ?>
                                <?php for ($j=0; $j < count($options); $j++) { ?>

                                <div class="layui-form-item options-box radio-box">
                                    <div class="one-of-option">
                                        <div class="option-letter layui-inline"><?php echo chr($j+1+64); ?></div><div class="layui-inline" style="margin-bottom: 0 ;width: 75%;"><input class="option-text" type="text" value="<?php echo $options[$j]['option_content']; ?>"><div class="layui-inline delete-box" style="margin-bottom: 2px;"><img class="delete-img" src="/images/delete-btn.png" alt="" onclick="deleteOption(this)"> </div></div><div class="layui-inline" style="margin-left: 20px;"><input class="layui-input" name="show-question-<?php echo ($i+1); ?>" type="radio" title="是" <?php if( $options[$j]['is_right'] ) { echo "checked=true"; } ?> ></div>
                                    </div>
                                </div>
                                <?php } ?>
                            <?php } ?>
                        </div>

                        <!-- 添加单项选项按钮 -->
                        <?php if( $questionsData[$i][0]['question_type']==1){ ?>
                        <div class="layui-row create-option-btn-box radio-btn">
                            <img src="/images/create-option-btn.png" onclick="appendRadio(this)"/>
                        </div>
                        <?php } ?>

                        <div class='multiple-options display-none  <?php if( $questionsData[$i][0]['question_type']==3){ echo ' display-block '; } ?>" '>
                            <?php if( $questionsData[$i][0]['question_type']==3){ ?>
                                <?php for ($j=0; $j < count($options); $j++) { ?>
                                <div class="layui-form-item options-box multiple-box">
                                    <div class="one-of-option">
                                        <div class="option-letter layui-inline"><?php echo chr($j+1+64); ?></div><div class="layui-inline" style="margin-bottom: 0 ;width: 75%;"><input class="option-text" type="text" value="<?php echo $options[$j]['option_content']; ?>"><div class="layui-inline delete-box" style="margin-bottom: 2px;"><img class="delete-img" src="/images/delete-btn.png" alt="" onclick="deleteOption(this)"></div></div><div class="layui-inline" style="margin-left: 20px;"><input class="layui-input"  lay-skin="primary" name="show-question-<?php echo ($i+1); ?>" type="checkbox" title="是" <?php if( $options[$j]['is_right'] ) { echo "checked=true"; } ?> ></div>
                                    </div>
                                </div>
                                <?php } ?>
                            <?php } ?>
                        </div>

                        <!-- 添加多项选项按钮 -->
                        <?php if( $questionsData[$i][0]['question_type']==3){ ?>
                        <div class="layui-row create-option-btn-box multiple-btn">
                            <img src="/images/create-option-btn.png" onclick="appendMultiple(this)"/>
                        </div>
                        <?php } ?>

                        <!-- 保存修改按钮 -->
                        <div class="edit-box save-edit-btn-box">
                            <img class="save-btn" src="/images/save-edit-btn.png" onclick="saveEdit(this);" />
                        </div>

                    </div>

                    <!-- 第二个题目的内容模块 -->
                    <div class="second-part-content display-none <?php if( $questionsData[$i][0]['question_type']==2 ) { echo ' display-block '; }  ?> " >
                        <div class="layui-form-item layui-form-text layui-inline two-thirds">
                            <label class="layui-form-label title-text gray-text">题目内容</label>
                            <div class="layui-input-block form-margin">
                                <textarea placeholder="请输入内容" class="layui-textarea question-content"><?php echo $questionsData[$i][0]['content']; ?></textarea>
                            </div>
                        </div>

                        <div class="layui-form-item layui-inline ">
                            <label class="layui-form-label title-text">正确答案</label>
                            <div class="layui-input-inline form-margin">
                                <?php if( $questionsData[$i][0]['question_type']==2){ ?>
                                    <div class="layui-form layui-input-inline">
                                        <?php for ($j=0; $j < count($options); $j++) { ?>
                                        <div class="layui-form layui-input-block" style="margin-left: 0">
                                            <input type="radio" name="show-question-<?php echo ($i+1); ?>" value="<?php echo $options[$j]['option_content']; ?>" title="<?php echo $options[$j]['option_content']; ?>" style="float:left;" <?php if( $options[$j]['is_right'] ) { echo "checked=true"; } ?> >
                                        </div>                                
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="edit-box save-edit-btn-box">
                            <img class="save-btn" src="/images/save-edit-btn.png" onclick="saveEdit(this);" />
                        </div>

                    </div>
                    
                    
                </div>

            </div>
        </div>
       <?php } ?> 
    </form>

    <!--添加 提交按钮-->
    <div class="add-submit">
        <div class="layui-btn layui-btn-primary add-item">添加题目</div>
        <div class="submit-btn layui-inline " >
            <img src="/images/submit-btn.png" alt="" onclick="beforeSubmit()">
        </div>
    </div>

</div>

<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/tools/layui/layui.all.js"></script>
<script>

function beforeSubmit()
{
    $("#myForm").submit();
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

// 保存这个结果
function saveEdit(obj)
{
    
    // 根节点
    var rootNode = $(obj).parent().parent().parent().parent().parent();

    var curIndex = $('.item-content').index( rootNode );

    // console.log(curIndex);

    var createFormNode = $(obj).parent().parent().parent();
    
//    console.log(rootNode);
    // 获取题目类型
    var questionType = rootNode.find('.question-type-select').val();

    var optionOk = true;

    
    if(questionType==1) {
        var optionCount = 0;
        //单选题
        var eqIndex = 0;
        $('.item-content').eq(curIndex).find(".single-choice").empty();
        $('.item-content').eq(curIndex).find(".multiple-choice").empty();
        $('.item-content').eq(curIndex).find(".judge-choice").empty();

        // 判断是否有添加选项
        if( $('.item-content').eq(curIndex).find(".radio-box .option-text").length==0 ) {
            autoMessageNotice("请添加选项");
            optionOk = false;
        }


        $('.item-content').eq(curIndex).find(".radio-box .option-text").each(function(){

            var radioOptionText = $(this).val();
            var radioOptionPlaceholder = $(this).attr("placeholder");
            var isChecked = $(this).parent().next().find("input[type='radio']").prop("checked");

            // 是否选中
            var checkedStr = "";
            if( isChecked ) {
                checkedStr = " checked='true' ";
            }

            if( radioOptionText=="" || radioOptionText==radioOptionPlaceholder ) {
                autoMessageNotice("请填写选项");
                optionOk = false;
                return true;
            }

            radioArr = new Array();
            radioArr.push(radioOptionText);            

            optionCount++;

            layui.use('form',function() {
                var form = layui.form;
                radioOptionShow = '';
                radioOptionShow += '<div class="layui-form layui-input-block" style="margin-left: 0">';
                for ( i = 0; i < radioArr.length; i++){
                    radioOptionShow += '<input ' + checkedStr + ' type="radio" name="question-' + (curIndex+1) + '-options[]" value="'+ optionCount + '" title="'+ radioArr[i] +'"></div>';
                    radioOptionShow += '<input type="hidden" name="hidden-question-' + (curIndex+1) + '-options[]" value="'+ radioArr[i] +'"  />';
                }

                $('.item-content').eq(curIndex).find(".single-choice").append(radioOptionShow);
                form.render();
            });
//            console.log(radioArr);
        });

    }else if (questionType==2){
        var optionCount = 0;
        //多选题
        var eqIndex = 0;
        $('.item-content').eq(curIndex).find(".single-choice").empty();
        $('.item-content').eq(curIndex).find(".multiple-choice").empty();
        $('.item-content').eq(curIndex).find(".judge-choice").empty();

        // 判断是否有添加选项
        if( $('.item-content').eq(curIndex).find(".multiple-box .option-text").length==0 ) {
            autoMessageNotice("请添加选项");
            optionOk = false;
        }

        $('.item-content').eq(curIndex).find(".multiple-box .option-text").each(function(index){

            var multipleOptionText = $(this).val();
            var multipleOptionPlaceholder = $(this).attr("placeholder");

            var isChecked = $(this).parent().next().find("input[type='checkbox']").prop("checked");

            // 是否选中
            var checkedStr = "";
            if( isChecked ) {
                checkedStr = " checked='true' ";
            }

            if( multipleOptionText=="" || multipleOptionText==multipleOptionPlaceholder ) {
                autoMessageNotice("请填写选项");
                optionOk = false;
                return true;
            }

            multipleArr = new Array();
            multipleArr.push(multipleOptionText);

            optionCount++;

            layui.use('form',function() {
                var form = layui.form;
                multipleOptionShow = '';
                multipleOptionShow += '<div class="layui-form layui-input-block" style="margin-left: 0">';
                for ( i = 0; i < multipleArr.length; i++){
                    multipleOptionShow += '<input ' + checkedStr + ' type="checkbox" lay-skin="primary" name="question-' + (curIndex+1) + '-options[]" value="'+ optionCount + '" title="'+ multipleArr[i] +'"></div>';
                    multipleOptionShow += '<input type="hidden" name="hidden-question-' + (curIndex+1) + '-options[]" value="'+ multipleArr[i] + '" />';
                }
                $('.item-content').eq(curIndex).find(".multiple-choice").append(multipleOptionShow);
                form.render();

            });
//            console.log(radioArr);
        });

    } else {
        //判断题
        var eqIndex = 1;
        // console.log('判断题');
        $('.item-content').eq(curIndex).find(".single-choice").empty();
        $('.item-content').eq(curIndex).find(".multiple-choice").empty();
        $('.item-content').eq(curIndex).find(".judge-choice").empty();
        layui.use('form',function() {

            var checkedArr = new Array();
            $('.second-part-content').eq(curIndex).find("input[type='radio']").each(function(index){
                checkedArr[ index ] = "";
                var isChecked = $(this).prop("checked");
                if( isChecked ) {
                    checkedArr[ index ] = " checked=true ";
                }
            })

            // 是否选中
            var checkedStr_1 = checkedArr[0];
            var checkedStr_2 = checkedArr[1];

            // console.log(checkedArr);

            var form = layui.form;
            var judgeTopic = '';
            judgeTopic += '<div class="layui-form layui-input-inline">';
            judgeTopic += '<input ' + checkedStr_1 + ' type="radio" name="question-' + (curIndex+1) + '-options[]" value="1" title="对" style="float:left;">';
            judgeTopic += '<input type="hidden" name="hidden-question-' + (curIndex+1) + '-options[]" value="对" title="对" style="float:left;">';
            judgeTopic += '<input ' + checkedStr_2 + ' type="radio" name="question-' + (curIndex+1) + '-options[]" value="2" title="错" style="float:left;">';
            judgeTopic += '<input type="hidden" name="hidden-question-' + (curIndex+1) + '-options[]" value="错" title="错" style="float:left;">';
            judgeTopic += '</div>';
            $('.item-content').eq(curIndex).find(".judge-choice").append(judgeTopic);
            form.render();
        });
    }

    if( !optionOk ) {
        return false;
    }

    // 兼容trim ie8
    String.prototype.trim = function(){ return Trim(this);};

    // 判断是否有填写题目内容
    var questionContent = $.trim( rootNode.find(".question-content").eq(eqIndex).val() );
    var questionContentPlaceholder = $.trim( rootNode.find(".question-content").eq(eqIndex).attr("placeholder") );
    // console.log( questionContent );
    // console.log( questionContentPlaceholder );

    if( questionContent=="" || questionContent==questionContentPlaceholder) {
        autoMessageNotice("题目内容不为空");
        return false;
    }

    // 显示分值
    rootNode.find('.orange-text').removeClass("display-none");

    if(questionType==0 || questionType==1) {

        // 判断是否有填写选项数据
//         var optionContent = $.trim( rootNode.find('.option-content').val() );
//         if( optionContent=="" ) {
//             autoMessageNotice("选项内容不为空");
//             return false;
//         }

    }

    // 替换题目内容
    rootNode.find('.item-title').text(questionContent);
    rootNode.find('.question-name').val(questionContent);

    // 这道题目分数
    var curScore = rootNode.find(".currentScore").val();
    curScore = parseInt(curScore);

    var tempCurQuestionIndex = $('.create-form').index(createFormNode);

    scoreArr[tempCurQuestionIndex] = curScore;

    var tempTotalScore = 0;
    // 此时总分
    for (var i = 0; i < scoreArr.length; i++) {
        tempTotalScore += scoreArr[i];
    }

    if( tempTotalScore > defaultTotalScore ) {
        autoMessageNotice("超过总分!");
        return false;
    }

    rootNode.find(".current-question-score").text(curScore);

    rootNode.find(".question-score").val(curScore);

    $(obj).parent().parent().parent().parent().addClass("display-none");
    rootNode.addClass("background-white");
    rootNode.find('.layui-row').addClass("display-block");


    // 将当前分数更新
    updateCurrentScore();

}

function LTrim(str)
{
    var i;
    for(i=0;i<str.length;i++)
    {
        if(str.charAt(i)!=" "&&str.charAt(i)!=" ")break;
    }
    str=str.substring(i,str.length);
    return str;
}

function RTrim(str)
{
    var i;
    for(i=str.length-1;i>=0;i--)
    {
        if(str.charAt(i)!=" "&&str.charAt(i)!=" ")break;
    }
    str=str.substring(0,i+1);
    return str;
}

function Trim(str)
{
    return LTrim(RTrim(str));
}

// 更新当前分数
function updateCurrentScore()
{
    // console.log( scoreArr );
    var tempTotalScore = 0;
    // 此时总分
    for (var i = 0; i < scoreArr.length; i++) {
        tempTotalScore += scoreArr[i];
    }

    defaultCurrentScore = tempTotalScore;
    $('.current-score').text( tempTotalScore );

}

// 显示修改的内容框
function showEditContent(obj)
{
    $(obj).parent().parent().removeClass("display-block");
    $(obj).parent().parent().parent().parent().removeClass("background-white");
    $(obj).parent().parent().parent().parent().find(".create-content-list").removeClass("display-none");
}

// 删除这个题目
function deleteQuestion(obj)
{
    layer.confirm('你确定要删除吗？', {
        title : "提示",
        btn: ['确定','取消'] //按钮
    }, function(index){
        layer.msg('删除成功', {icon: 1});
        // todo 修改对应准备上传的节点数据

        // 减少索引
        curQuestionIndex = curQuestionIndex-1<0? 1:curQuestionIndex-1;

        // 删除这个题目分数
        var curImgIndex = $('.delete-btn').index( $(obj) );
        scoreArr.splice(curImgIndex, 1);

        var tempScoreArr = new Array();
        var tempI = 0;
        for (var i in scoreArr) {
            tempScoreArr[tempI] = scoreArr[i];
            tempI++;
        }
        scoreArr = tempScoreArr;

        // 移除整个节点
        $(obj).parent().parent().parent().parent().remove();

        // 将当前分数更新
        updateCurrentScore();

        // 更新隐藏域的题目选项
        updateHiddenQuestionsName();

        // 更新题目索引
        var newIndex = 1;
        $('.item-content').each(function(index){
            $(this).find('.serial-num').text(newIndex + " . ");
            newIndex++;
        });
        layer.close(index);
    });


}

// 更新隐藏域的题目选项
function updateHiddenQuestionsName()
{
    
    $('.item-content').each(function(){

        var curIndex = $('.item-content').index($(this));
        
        // 获取当前题目类型
        var curQuestionType = $(this).find('.question-type-select').val();
        if( curQuestionType==1 ) {
            
            // 单选
            $(this).find(".single-choice input[type='radio']").each(function(){
                $(this).attr("name", "question-" + (curIndex+1) + "-options[]");
            });

            // 单选
            $(this).find(".single-choice input[type='hidden']").each(function(){
                $(this).attr("name", "hidden-question-" + (curIndex+1) + "-options[]");
            });

        } else if( curQuestionType==2 ) {
            // 多选
            $(this).find(".multiple-choice input[type='radio']").each(function(){
                $(this).attr("name", "question-" + (curIndex+1) + "-options[]");
            });

            // 多选
            $(this).find(".multiple-choice input[type='hidden']").each(function(){
                $(this).attr("name", "hidden-question-" + (curIndex+1) + "-options[]");
            });
        } else if( curQuestionType==3 ) {
            
            // 判断
            $(this).find(".judge-choice input[type='radio']").each(function(){
                $(this).attr("name", "question-" + (curIndex+1) + "-options[]");
            });

            // 判断
            $(this).find(".judge-choice input[type='hidden']").each(function(){
                $(this).attr("name", "hidden-question-" + (curIndex+1) + "-options[]");
            });

        }
        
    });

}

// 删除题目里面的选项
function deleteOption (obj)
{
    var $this = $(obj);
    layer.confirm('是否要删除该选项？', {
        btn: ['是的','取消'] //按钮
    }, function(){

        // 获取对应的索引
        var curIndex = $('.item-content').index( $this.parent().parent().parent().parent().parent().parent().parent().parent().parent() );

        // 将对应的选项数目减去1
        questionsOptionsObject[ curIndex ]--;

        // 删除选项
        $this.parent().parent().parent().parent().remove();

        // 更新当前题目显示在外面的选项索引
        updateQuestionShowOptions(curIndex);

        layer.msg('删除成功', {icon: 1});
//        $(".radio-options").load(location.href+".radio-options");
    });
}

// 添加单选题目的选项
function appendRadio(obj)
{

    // 获取索引
    var curIndex = $('.item-content').index( $(obj).parent().parent().parent().parent().parent() );

//    $(".radio-options").empty();
    layui.use('form',function(){

        // 判断对应对象索引是否存在  不存在就生成对象    并生成对应的选项数字
        if ( typeof(questionsOptionsObject[ curIndex ])=="undefined" ) {
            // 新建这个对象
            questionsOptionsObject[ curIndex ] = 0;
        }

        questionsOptionsObject[ curIndex ]++;

        // 选项的字母数组
        letterChar = new Array("A", "B", "C", "D","E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P","Q","R", "S", "T","U", "V", "W", "X", "Y", "Z");

        var form = layui.form;

        //获取选项的个数
        var optionLength = $(".radio-options").find(".options-box").length;

        // 显示的字母
        var currLetter = letterChar[ questionsOptionsObject[ curIndex ]-1 ];

        // 填写内容
        var radioOption = '';
        radioOption += '<div class="layui-form-item options-box radio-box">';
        radioOption += '<div class="one-of-option">';
        //选项字母
        radioOption += '<div class="option-letter layui-inline"> '+ currLetter +'</div>';
        //选项内容
        radioOption += '<div class="layui-inline" style="margin-bottom: 0 ;width: 75%;"><input class="option-text" type="text"><div class="layui-inline delete-box" style="margin-bottom: 2px;"><img class="delete-img" src="/images/delete-btn.png" alt="" onclick="deleteOption(this)"></div></div>';
        radioOption += '<div class="layui-inline" style="margin-left: 20px;"><input class="layui-input" type="radio" name="yes" title="是"></div>';
        radioOption += '</div>';
        radioOption += '</div>';

        $(".radio-options").eq(curIndex).append(radioOption);
        form.render();

    });
    $(".multiple-options").eq(curIndex).empty();
//    letterChar = [];
}

// 添加多选题目选项
function appendMultiple(obj)
{
    
    // 获取索引
    var curIndex = $('.item-content').index( $(obj).parent().parent().parent().parent().parent() );

    layui.use('form',function(){

        // 用来判断是第几个选项
        // 判断对应对象索引是否存在  不存在就生成对象    并生成对应的选项数字
        if ( typeof(questionsOptionsObject[ curIndex ])=="undefined" ) {
            // 新建这个对象
            questionsOptionsObject[ curIndex ] = 0;
        }
        
        questionsOptionsObject[ curIndex ]++;

        var form = layui.form;

        // 选项的字母数组
        letterChar = new Array("A", "B", "C", "D","E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P","Q","R", "S", "T","U", "V", "W", "X", "Y", "Z");

        //获取选项的个数
        var optionLength = $(".multiple-options").find(".options-box").length;

        // console.log(optionLength);
        // 显示的字母
        var currLetter = letterChar[  questionsOptionsObject[ curIndex ]-1  ];

        var multipleOption = '';

        multipleOption += '<div class="layui-form-item options-box multiple-box">';
        multipleOption += '<div class="one-of-option">';
        //选项字母
        multipleOption += '<div class="option-letter layui-inline">'+currLetter+'</div>';
        //选项内容
        multipleOption += '<div class="layui-inline" style="margin-bottom: 0 ;width: 75%;"><input class="option-text" type="text"><div class="layui-inline delete-box" style="margin-bottom: 2px;"><img class="delete-img" src="/images/delete-btn.png" alt="" onclick="deleteOption(this)"></div></div>';
        multipleOption += '<div class="layui-inline" style="margin-left: 20px;"><input class="layui-input" lay-skin="primary" type="checkbox" name="yes" title="是"></div>';
        multipleOption += '</div>';
        multipleOption += '</div>';

        $(".multiple-options").eq(curIndex).append(multipleOption);
        form.render();

    });

    $(".radio-options").eq(curIndex).empty();

//    letterChar.splice(0,letterChar.length);
//    console.log(letterChar);
}

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

/**
* 更新当前题目显示的选项索引
* qIndex    题目的索引
*/ 
function updateQuestionShowOptions(qIndex)
{

    // 获取当前题目类型
    var curQuestionType = $('.question-type-select').eq(qIndex).val();
    if( curQuestionType==1 ) {
        // 单选
        var curCount = questionsOptionsObject[qIndex];
        if( curCount==0 ) {
            return false;
        }

        // 遍历选项
        $('.radio-options').eq(qIndex).find('.options-box').each(function(index){
            // 改变显示的索引值
            $(this).find('.option-letter').text( letterChar[index] );
        });


    } else if( curQuestionType==2 ) {
        // 多选
        var curCount = questionsOptionsObject[qIndex];
        if( curCount==0 ) {
            return false;
        }

        // 遍历选项
        $('.multiple-options').eq(qIndex).find('.options-box').each(function(index){
            // 改变显示的索引值
            $(this).find('.option-letter').text( letterChar[index] );
        });

    }
    // 遍历题目
    // $('.item-content').eq(qIndex).find("")
}

window.onscroll = function(){
    var scrollTopHeight = $(document).scrollTop();
    
    if( scrollTopHeight>70 ) {

        // 悬浮
        $('.question').addClass("topbar-fixed");
        $(".rest-bar").addClass("display-block");
        $(".rest-bar").css("height", 200);

    } else {
        // 取消悬浮
        $('.question').removeClass("topbar-fixed");
        $(".rest-bar").removeClass("display-block");

    }
};

$(document).ready(function(){

    _userAgent = navigator.userAgent;

    if(_userAgent.indexOf("MSIE")>0) {

        window.document.body.attachEvent("onkeydown", function(event) {
            if( event.keyCode!=13 ) {
                // return false;
            } else {
                $(".save-btn").click();
            }

        });

    } else {
        // 回车
        window.document.body.addEventListener("keydown", function(event) {
            if( event.which!=13 ) {
                return false;
            } else {
                $(".save-btn").click();
            }

        });
    }

    var hasTesting = <?php echo (int)$hasTesting; ?>;

    if( hasTesting ) {
        autoMessageNotice("已经有人进行过该试题测试 无法进行修改", 5000);
        $('.submit-btn').remove();
    }

    var questionOptionObjectJson = <?php echo "'" . $questionOptionObjectJson . "'"; ?>;

    var questionOptionObjectArr = JSON.parse( questionOptionObjectJson );

    var questionsDataJson = <?php echo "'" . $questionsDataJson . "'"; ?>

    var questionsDataArr = JSON.parse(questionsDataJson);

    if( questionOptionObjectArr.length==0 ) {

        // 题目选项对象
        questionsOptionsObject = new Object();

        // 当前题目索引
        curQuestionIndex = 1;

    } else {

        // 题目选项对象
        questionsOptionsObject = questionOptionObjectArr;

        curQuestionIndex = questionOptionObjectArr.length+1;
    }

    if( questionsDataArr.length==0 ) {

        // 分数数组
        scoreArr = new Array();

    } else {

        // 分数数组
        scoreArr = new Array();

        for (var i = 0; i < questionsDataArr.length; i++) {
            scoreArr[i] = questionsDataArr[i][0]['score'];
        }

    }

    // 默认总分数
    defaultTotalScore = 100;

    // 默认当前分数
    defaultCurrentScore = <?php echo $score; ?>;

    // 设置默认总分
    $('.total-score').text( defaultTotalScore );

    // 设置默认当前分
    $('.current-score').text( defaultCurrentScore );

    letterChar = new Array("A", "B", "C", "D","E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P","Q","R", "S", "T","U", "V", "W", "X", "Y", "Z");

    var tempContent = "";
    tempContent += '<div class="item-content">';
    tempContent += ' <input type="hidden" name="question-name[]"  class="question-name" >';
    tempContent += ' <input type="hidden" name="question-score[]" class="question-score" >';
    tempContent += ' <input type="hidden" name="question-type[]" class="question-type" >';
    tempContent +=        '<div class="example">';
    tempContent +=            '<div action="" class="layui-form example-list">';
    //    单选题显示
    tempContent +=                '<div class="layui-form-item radio display-none">';
    tempContent +=                    '<label class="layui-form-label topic-title">';
    tempContent +=                        '<span class="serial-num">{$questionIndex}．</span>';
    tempContent +=                        '<span class="item-title"></span>';
    tempContent +=                        '<span class="orange-text display-none">　(分值: <span class="current-question-score">0</span>分)</span>';
    
    tempContent +=                    '</label>';
    tempContent +=                    '<div class="layui-input-block single-choice form-margin" style>';
    
    tempContent +=                    '</div>';
    tempContent +=                '</div>';
    //    多选题显示
    tempContent +=                '<div class="layui-form-item multiple display-none">';
    tempContent +=                    '<label class="layui-form-label topic-title">';
    tempContent +=                        '<span class="serial-num">{$questionIndex}．</span>';
    tempContent +=                        '<span class="item-title"></span>';
    tempContent +=                        '<span class="orange-text display-none">　(分值: <span class="current-question-score">0</span>分)</span>';
    
    tempContent +=                    '</label>';
    tempContent +=                    '<div class="layui-input-block multiple-choice form-margin ">';
    
    tempContent +=                    '</div>';
    tempContent +=                '</div>';
    //    判断题显示
    tempContent +=                '<div class="layui-form-item judge display-none display-block">';
    tempContent +=                    '<label class="layui-form-label topic-title">';
    tempContent +=                        '<span class="serial-num">{$questionIndex}．</span>';
    tempContent +=                        '<span class="item-title"></span>';
    tempContent +=                        '<span class="orange-text display-none">　(分值: <span class="current-question-score">0</span>分)</span>';
    
    tempContent +=                    '</label>';
    tempContent +=                    '<div class="layui-input-block judge-choice form-margin">';
    
    tempContent +=                    '</div>';
    tempContent +=                '</div>';
    tempContent +=            '</div>';
    tempContent +=            '<hr class="line">';

    // 按钮
    tempContent +=              '<div class="layui-row operate-btn-row display-none">';
    tempContent +=                  '<div class="layui-col-md9">';
    tempContent +=                    '&nbsp;';
    tempContent +=                  '</div>';
    tempContent +=                  '<div class="layui-col-md3">';
    tempContent +=                    '<img src="/images/edit-btn.png" onclick="showEditContent(this)" />';
    tempContent +=                    '<img class="delete-btn" src="/images/delete-btn.png" onclick="deleteQuestion(this)" />';
    tempContent +=                  '</div>';
    tempContent +=              '</div>';
    tempContent +=        '</div>';

    tempContent +=        '<div class="create-content-list">';
    tempContent +=            '<div action="" class="layui-form radio-multiple-content create-form">';

    tempContent +=                '<div class="layui-form-item layui-inline half-div">';
    tempContent +=                    '<label class="layui-form-label title-text">题目类型</label>';
    tempContent +=                    '<div class="layui-input-block form-margin">';
    tempContent +=                        '<select class="question-type-select" lay-verify="required" lay-filter="selectType">';
    tempContent +=                            '<option value="0" selected>-- 请选择题目类型 --</option>';
    tempContent +=                            '<option value="1">单项选择</option>';
    tempContent +=                            '<option value="2">多项选择</option>';
    tempContent +=                            '<option value="3">判断题</option>';
    tempContent +=                        '</select>';
    tempContent +=                    '</div>';
    tempContent +=                '</div>';

    tempContent +=                '<div class="layui-form-item layui-inline half-div">';
    tempContent +=                    '<label class="layui-form-label title-text">题目分值</label>';
    tempContent +=                    '<div class="layui-input-block form-margin">';
    tempContent +=                        '<input type="text" maxLength="2" lay-verify="required" placeholder="请输入分值" autocomplete="off" class="layui-input currentScore" value="5"  onkeyup="this.value=this.value.replace(/\\D/g,\'\') " onafterpaste="this.value=this.value.replace(/\\D/g,\'\') " >';
    tempContent +=                    '</div>';
    tempContent +=                '</div>';

    // 第一个题目的内容模块
    tempContent +=                '<div class="first-part-content display-none display-block" >';
    tempContent +=                  '<div class="layui-form-item layui-form-text">';
    tempContent +=                    '<label class="layui-form-label title-text gray-text">题目内容</label>';
    tempContent +=                    '<div class="layui-input-block form-margin">';
    tempContent +=                        '<textarea placeholder="请输入题目内容" class="layui-textarea radio-multiple-title question-content"></textarea>';
    tempContent +=                    '</div>';
    tempContent +=                  '</div>';
    tempContent +=                  '<div class="layui-row option-remind-box">';
    tempContent +=                    '<div class="layui-col-md9 set-option-box">选项设置</div>';
    tempContent +=                    '<div class="layui-col-md3 right-or-not-text-box">是否正确答案</div>';
    tempContent +=                  '</div>';

    tempContent += '<div action="" class="radio-options">';

    tempContent += '</div>';

    tempContent += '<div action="" class="multiple-options">';

    tempContent += '</div>';
//    // 选项内容盒子
//    tempContent +=                    '<div class="layui-form-item options-box">';
//    tempContent +=                  '<div class="one-of-option">';
//    //选项字母
//    tempContent +=                  '<div class="option-letter layui-inline">A</div>';
//    //选项内容
//    tempContent +=                  '<div class="layui-inline" style="margin-bottom: 0 ;width: 75%;"><input class="option-text" type="text"><div class="layui-inline delete-box" style="margin-bottom: 2px;"><img class="delete-img" src="/images/delete-btn.png" alt="" onclick="deleteOption(this)"></div></div>';
//    tempContent +=                  '<div class="layui-inline" style="margin-left: 20px;"><input class="layui-input" type="radio" name="yes" title="是"></div>';
//    tempContent +=                  '</div>';
//    tempContent +=                    '</div>';


    // 添加选项按钮
//    tempContent +=                  '<div class="layui-row create-option-btn-box">';
//    tempContent +=                      '<img src="/images/create-option-btn.png" onclick="appendRadio(this)"/>';
//    tempContent +=                  '</div>';

    // 保存修改按钮
    tempContent +=                  ' <div class="edit-box save-edit-btn-box">';
    tempContent +=                      '<img class="save-btn" src="/images/save-edit-btn.png" onclick="saveEdit(this);" />';
    tempContent +=                  ' </div>';
    tempContent +=               ' </div>';

    // 第二个题目的内容模块
    tempContent +=                '<div class="second-part-content display-none" >';
    tempContent +=                  '<div class="layui-form-item layui-form-text layui-inline two-thirds">';
    tempContent +=                    '<label class="layui-form-label title-text gray-text">题目内容</label>';
    tempContent +=                    '<div class="layui-input-block form-margin">';
    tempContent +=                       ' <textarea placeholder="请输入内容" class="layui-textarea question-content"></textarea>';
    tempContent +=                    '</div>';
    tempContent +=                  '</div>';
    tempContent +=                  '<div class="layui-form-item layui-inline ">';
    tempContent +=                   ' <label class="layui-form-label title-text">正确答案</label>';
    tempContent +=                   ' <div class="layui-input-inline form-margin">';
    tempContent +=                        '<input type="radio" name="yesorno" value="" title="对">';
    tempContent +=                        '<input type="radio" name="yesorno" value="" title="错" checked>';
    tempContent +=                    '</div>';
    tempContent +=                  '</div>';
    tempContent +=                  ' <div class="edit-box save-edit-btn-box">';
    tempContent +=                      '<img class="save-btn" src="/images/save-edit-btn.png" onclick="saveEdit(this);" />';
    tempContent +=                  ' </div>';
    tempContent +=              '</div>';

    tempContent +=           ' </div>';



    // 增加题目
    $(".add-item").on("click", function(){

        // $(".option-letter").empty();

        if( typeof(globalForm)!="undefined") {

            var questionContent = tempContent.replace(/\{\$questionIndex\}/g, curQuestionIndex);
            
            // 加入模板题目
            $("#myForm").append(questionContent);

            $(".question").addClass("display-block");
            $(".item-content").addClass("display-block");


            // 如果有题目    则出现提交按钮
            var itemLength = $('.item-content').length;
            if( itemLength!= 0) {
                $('.submit-btn').removeClass("display-none");
            }

            // 将题目索引加1
            curQuestionIndex++;

            // 渲染组件
            globalForm.render();
            globalElement.render();

            if(_userAgent.indexOf("MSIE")>0 && parseInt(_userAgent.match(/.*?MSIE\s(.*?);/)[1])<=9){
                showPlaceholder();
            }

        }
        
    });

    layui.use(['form', 'element'], function () {
        globalForm = layui.form;
        globalElement = layui.element;

        globalForm.on('select(selectType)', function(data){

            var curSelectDomIndex = $('.question-type-select').index(data.elem);

            // console.log(data.elem); //得到select原始DOM对象
            // console.log(data.value); //得到被选中的值
            // console.log(data.othis); //得到美化后的DOM对象

            if (data.value == "1"){

                $('.item-content').eq(curSelectDomIndex).find('.question-type').val(1);

                //单选框
                $('.item-content').eq(curSelectDomIndex).find(".radio").addClass("display-block");
                $('.item-content').eq(curSelectDomIndex).find(".multiple").removeClass("display-block");
                $('.item-content').eq(curSelectDomIndex).find(".judge").removeClass("display-block");

                // $(".radio-multiple-content").addClass("display-block");
                // $(".judge-content").removeClass("display-block");
                // $(".judge-content").addClass("display-none");

                $('.item-content').eq(curSelectDomIndex).find('.second-part-content').removeClass("display-block");
                $('.item-content').eq(curSelectDomIndex).find('.first-part-content').addClass("display-block");

                $('.item-content').eq(curSelectDomIndex).find(".radio-btn").remove();
                //添加 按钮

                var appendBtn ='';
                appendBtn += '<div class="layui-row create-option-btn-box radio-btn">';
                appendBtn += '<img src="/images/create-option-btn.png" onclick="appendRadio(this)"/>';
                appendBtn += '</div>';

                $('.item-content').eq(curSelectDomIndex).find(".multiple-btn").remove();
                $('.item-content').eq(curSelectDomIndex).find(".radio-options").after(appendBtn);

                $(".multiple-options").eq(curSelectDomIndex).removeClass("display-block");
                $(".multiple-options").eq(curSelectDomIndex).addClass("display-none"); // 多选隐藏
                $(".radio-options").eq(curSelectDomIndex).removeClass("display-none");
                $(".radio-options").eq(curSelectDomIndex).addClass("display-block"); // 单选内容显示

                // $(".multiple-btn").eq(curSelectDomIndex).addClass("display-none"); //多选按钮隐藏

                // 判断对应对象索引是否存在  不存在就生成对象    并生成对应的选项数字
                if ( typeof(questionsOptionsObject[ curSelectDomIndex ])!="undefined" ) {
                    // 新建这个对象
                    questionsOptionsObject[ curSelectDomIndex ] = 0;
                }

            }else if (data.value == "2"){

                $('.item-content').eq(curSelectDomIndex).find('.question-type').val(3);

                //多选框
                $('.item-content').eq(curSelectDomIndex).find(".radio").removeClass("display-block");
                $('.item-content').eq(curSelectDomIndex).find(".multiple").addClass("display-block");
                $('.item-content').eq(curSelectDomIndex).find(".judge").removeClass("display-block");

                // $(".radio-multiple-content").addClass("display-block");
                // $(".judge-content").removeClass("display-block");
                $('.item-content').eq(curSelectDomIndex).find('.second-part-content').removeClass("display-block");
                $('.item-content').eq(curSelectDomIndex).find('.first-part-content').addClass("display-block");

                $('.item-content').eq(curSelectDomIndex).find(".multiple-btn").remove();
                //添加 按钮
                var appendBtn ='';
                appendBtn += '<div class="layui-row create-option-btn-box multiple-btn">';
                appendBtn += '<img src="/images/create-option-btn.png" onclick="appendMultiple(this)"/>';
                appendBtn += '</div>';

                $('.item-content').eq(curSelectDomIndex).find(".radio-btn").remove();
                $('.item-content').eq(curSelectDomIndex).find(".multiple-options").after(appendBtn);

                $(".multiple-options").eq(curSelectDomIndex).removeClass("display-none");
                $(".multiple-options").eq(curSelectDomIndex).addClass("display-block");
                $(".radio-options").eq(curSelectDomIndex).removeClass("display-block");
                $(".radio-options").eq(curSelectDomIndex).addClass("display-none");

                // $(".multiple-btn").eq(curSelectDomIndex).addClass("display-block");

                // 判断对应对象索引是否存在  不存在就生成对象    并生成对应的选项数字
                if ( typeof(questionsOptionsObject[ curSelectDomIndex ])!="undefined" ) {
                    // 新建这个对象
                    questionsOptionsObject[ curSelectDomIndex ] = 0;
                }

            }else if (data.value == "3"){

                $('.item-content').eq(curSelectDomIndex).find('.question-type').val(2);

                //判断题
                $('.item-content').eq(curSelectDomIndex).find(".radio").removeClass("display-block");
                $('.item-content').eq(curSelectDomIndex).find(".multiple").removeClass("display-block");
                $('.item-content').eq(curSelectDomIndex).find(".judge").addClass("display-block");

                // $(".radio-multiple-content").removeClass("display-block");
                // $(".judge-content").addClass("display-block");

                $('.item-content').eq(curSelectDomIndex).find('.first-part-content').removeClass("display-block");
                $('.item-content').eq(curSelectDomIndex).find('.second-part-content').addClass("display-block");

            }

        });

    });

});

</script>
</body>
</html>