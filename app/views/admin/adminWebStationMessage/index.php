<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>站内消息列表</title>
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
html,body {
    width: 100%;
    height: 100%;
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
}
    	body {
    overflow-y: auto;
    background-color: #F5F9FC ;
}
.middle-content {
    width:95%;
    margin: 30px auto;
}
.title {
    background-image: linear-gradient(-180deg, #80C3F3 0%, #4A90E2 100%);
    filter: -ms-filter:progid:DXImageTransform.Microsoft.gradient(startcolorstr=#80C3F3,endcolorstr=#4A90E2,gradientType=0);/*IE8*/
    filter: progid:DXImageTransform.Microsoft.gradient(startcolorstr=#80C3F3,endcolorstr=#4A90E2,gradientType=0);
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
.layui-form-item {
    margin-bottom: 0px!important;
}
.layui-input {
    height: 34px;
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
}
.blue-btn {
    background-image: linear-gradient(-180deg, #71c1f1 0%, #4d93e3 100%);
}
.green-btn {
    background-image: linear-gradient(-180deg, #aae54d 0%, #449422 100%);
}
.gray-btn {
    background-image: linear-gradient(-180deg, #e8e8e8 0%, #9d9d9d 100%);

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
        .layui-form-label {
            width: 60px!important;
            padding: 8px 0px !important;
        }
        .layui-input-block {
            margin-left:70px!important;
        }
        .layui-form-select dl dd.layui-this{
            background-color: #4A90E2!important;
        }

        .news-form{
            margin: 40px auto 0 auto;
            text-align: center;
        }

/*分页样式*/
.page-pagination {
	float: left;
    margin-left: 27px !important;
    margin-top: 23px;
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
.total-page-box {
	margin-top: 10px;
	text-align: right;
	font-size: 14px;
	color: #000000;
}
.total-page-span {
	color: #4A90E2;
}
.page-pagination-box {
	font-size: 12px;
}

.btn-xs {
	/*margin-left: 10px; */
    /* width: 30px; */
    /* height: 16px !important; */
    background: #3278b3;
    -webkit-transition: background-color .3s ease;
    padding: 1px 5px;
    font-size: 12px;
    line-height: 1.5 !important;
    border-radius: 3px !important;
    color: #fff;
    display: inline-block;
    margin-bottom: 0;
    font-weight: 400;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    white-space: nowrap;
    user-select: none;
    -webkit-appearance: button;
    width: inherit;
    height: inherit !important;
}
.remind-text {
	color: red;
}
.img-list{
	margin-left:2%;
	margin-top: 25px;
	margin-bottom: 25px;
}
.create-course-btn:hover {
	cursor: pointer;
}
.wait-for-push-btn:hover{
    cursor: pointer;
}
.text-left {
    text-align: left !important;
}
    </style>
</head>
<body>
<div class="middle-content">

    <div class="title">
        <span>站内消息列表</span>
    </div>

    <div class="list-content">
        
        <!-- 操作按钮 -->
		<div class="img-list">
			<a href="/admin/webStationMessages/create"> <img class="create-course-btn" src="/images/create-btn-2.png" > </a>
		</div>

        <div class="form-list">
            <table class="form-table">
                <thead>
                    <tr>
                        <th width="15%">序号</th>
                        <th width="5%"></th>
                        <th width="25%" class="text-left">消息文本</th>
                        <th width="10%">发起人</th>
                        <th width="15%">发起时间</th>
                        <th width="15%">状态</th>
                        <th width="15%">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i=0; $i < count($data); $i++) { ?>
                        <tr>
                            <td> <?php echo $data[$i]['id']; ?> </td>
                            <td>&nbsp;</td>
                            <td title="<?php echo $data[$i]['content']; ?>"  class="text-left"> <?php echo $this->cutStr($data[$i]['content'], 20); ?> </td>
                            <td><?php echo empty($data[$i]['name'])? '未填写名称':$data[$i]['name']; ?></td>
                            <td><?php echo empty($data[$i]['create_time'])? '':date("Y-m-d", $data[$i]['create_time']); ?></td>
                            <td>
                                <?php if( $data[$i]['is_pushed'] == 0 ){ ?>
                                <img class='wait-for-push-btn' src="/images/wait-for-push.png" onclick="pushMessage(this, <?php echo $data[$i]['id']; ?>)">
                                <?php } else {?>
                                <img src="/images/pushed-btn.png">
                                <?php } ?>
                            </td>
                            <td>
                                <a href="/admin/webStationMessages/read?id=<?php echo $data[$i]['id']; ?>">
                                    <img class='operate-btn' src="/images/green-eyes.png"> 查看
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>

                <tfoot>
					<tr>
						<td colspan=2>&nbsp;</td>
						<td>
							<div class="total-page-box">共 <span class="total-page-span"><?php echo $totalPage; ?> </span> 页</div>
						</td>
						<td colspan=4>
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
</div>
</body>
<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/tools/layui/layui.all.js"></script>
<script>

// 消息推送
function pushMessage(obj, id)
{

    layer.confirm('确定要推送嘛?', {icon: 3, title:'提示'}, function(index){
        
        $(obj).unbind("click");
        
        //do something
        $.ajax({
            url: "/api/v1/message/webStation/push",
            type: "POST",
            dataType: "JSON",
            data: {
                id: id
            },
            async: true,
            success: function(response){
                if( response['status']['success'] ) {
                    $(obj).attr("src", "/images/pushed-btn.png");        
                     autoMessageNotice("推送消息成功");
                } else {
                    autoMessageNotice( response['status']['message'] + "请稍后刷新页面重试" );
                }
            },
            error: function(error){
                console.log( error.responseText );
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

// 提醒功能暂未开通
function notice()
{
    autoMessageNotice("暂未开通此功能");
    return false;
}

    $(document).ready(function(){
        $(".form-table tbody tr:even").css("background-color", "#eff5fc");
    });
    
    //改变状态
    // function changeState(obj, rid) {
    //     var $this = $(obj);
    //     layer.confirm('要更改该题的状态吗?', {
    //         title: ""
    //         ,btn: ['是的','取消'] //按钮
    //     }, function(index){
    //         if ($this.hasClass("green-btn")){
    //             var statusId = 2;
                
    //         }else if ($this.hasClass("gray-btn")){
    //             var statusId = 1;
    //         }

    //         $.ajax({
    //             url: "/api/v1/exam/changeStatus",
    //             type: "POST",
    //             dataType: "JSON",
    //             data: {
    //                 id: rid,
    //                 statusId: statusId
    //             },
    //             async: false,
    //             success: function(response){
                    
    //                 if(response['status']['success']) {

    //                     autoMessageNotice("修改状态成功");
                        
    //                     if( statusId==2 ) {
    //                         $(obj).removeClass("green-btn");
    //                         $(obj).addClass("gray-btn");
    //                         $(obj).text("已关闭");
    //                     } else if( statusId==1 ) {
    //                         $(obj).removeClass("gray-btn");
    //                         $(obj).addClass("green-btn");
    //                         $(obj).text("已启用");
    //                     }

    //                 } else {

    //                     if(response['status']['code']=="014") {
    //                         autoMessageNotice("请重新登录");
    //                     } else {
    //                         autoMessageNotice(response['status']['message']);
    //                     }

    //                 }

    //             },
    //             error: function(err) {
    //                 console.log(err.responseText);
    //             }
    //         });

    //         layer.close(index);

    //     });

    // }

   
    layui.use('form', function() {
        var form = layui.form
    })
</script>
</html>