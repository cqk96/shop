<!DOCTYPE html>
<html>
<head>
	<title><?php echo empty($data['title'])? '活动详情':$data['title']; ?></title>
	<meta charset="UTF-8">    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
    <meta content='initial-scale=1.0,user-scalable=no,maximum-scale=1,width=device-width' name='viewport' >
    <style type="text/css">
        html,body,div,span, img, p{margin: 0px; padding: 0px; }
        html, body {width: 100%; height: 100%; overflow-x: hidden; }
        body {background-color: #FFF; position: relative; width: 82.9%; margin: 0 auto; }
        .container-box {width: 100%; height: 100%; margin: 0 auto; overflow-y: scroll; }
        .title-box {font-size: 18px; color: #333333; letter-spacing: -0.49px; line-height: 1.3; width: 100%; margin: 0 auto; padding-top: 25px; }
        .rest-dashed-1 {width: 100%; height: 1px; border-top: 1px dashed #dcdcdc; }
        .sample-description-box {font-size: 16px; color: #bebebe; line-height: 2; text-align: justify; margin-top: 10px; margin-bottom: 30px; }
        .rest-40 {width: 100%; height: 40px; }
        .rest-15 {width: 100%; height: 15px; }
        .news-content { line-height: 1.6; width: 100%; margin: 0 auto; }
         .news-content img{display: block; width: 100%; margin: 0 auto; }
         .cover {
            width: 100%;
            margin: 25px auto 20px auto;
         }
        .cover img {
            display: block;
            width: 100%;
            height: auto;
        }
        .item-title {
            font-size: 15px;
            color: #666666;
            width: 100%;
            margin: 0 auto 9px auto;
        }
        .item-content {
            width: 100%;
            margin: 0 auto;  
            font-size: 15px;
            color: #666666;
        }
    </style>
</head>
<body>
	<div class="container-box">

        <!-- 标题 -->
        <div  class="title-box"> <?php echo empty($data['title'])? '活动详情':$data['title']; ?>        </div>

        <!-- <div class="rest-dashed-1"></div> -->

        <!-- 封面 -->
        <div class="cover">
            <img src="<?php echo empty($data['cover'])? '/images/empty-activity-cover.png':$data['cover']; ?>">
        </div>

        <p class="item-title">活动时间</p>
        <p class="item-content"> <?php echo empty($data['start_time'])? '':date("Y-m-d", $data['start_time']); ?> 到 <?php echo empty($data['end_time'])? '':date("Y-m-d", $data['end_time']); ?></p>

        <!--缺省-->
        <div class="rest-15"></div>
        <p class="item-title">活动描述</p>
        <div class="news-content"> <?php echo empty($data['content'])? '':$data['content']; ?> </div>

        <div class="rest-40"></div>
        
    </div>
</body>
</html>