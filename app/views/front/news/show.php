<!DOCTYPE html>
<html>
<head>
	<title><?php echo empty($data['title'])? '文章详情页':$data['title']; ?></title>
	<meta charset="UTF-8">    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
    <meta content='initial-scale=1.0,user-scalable=no,maximum-scale=1,width=device-width' name='viewport' >
    <style type="text/css">
    html,body,div,span, img, p{margin: 0px; padding: 0px; }
    html, body {width: 100%; height: 100%; overflow-x: hidden; }
    body {background-color: #FFF; position: relative; }

    html,body {
        width: 100%;
        height: 100%;
    }

    .container-box {
        width: 100%;
        height: 100%;
    }

    .cover-box {
        width: 100%;
        margin-bottom: 12px;
    }
    .cover-box img {
        display: block;
        width: 100%;
        height: auto;
    }

    .news-content-box {
        width: 94%;
        margin: 0 auto;
    }

    .time-box {
        color: #707070;
        padding-top: 8px;
        margin-bottom: 8px;
        font-size: 12px;
    }

    .title-box {
        line-height: 1.4;
        font-size: 28px;
        color: #000000;
        margin-bottom: 8px;
    }

    .news-content {
        line-height: 1.5;
    }
    .news-content img{
        display: block;
        margin: 0 auto;
    }
    </style>
</head>
<body>
	<div class="container-box">

        <div class="cover-box">   
            <img src="<?php echo empty($data['cover'])? '/images/empty-activity-cover.png':$data['cover'];?> " />
        </div>

        <!-- 文章内容 -->
        <div class="news-content-box">

            <!-- 更新时间 -->
            <div class="time-box">
                <?php echo empty($data['updated_at'])? '&nbsp;':$data['updated_at']; ?>  
            </div>

            <!-- 标题 -->
            <div  class="title-box">
                <?php echo empty($data['title'])? '&nbsp;':$data['title']; ?>  
            </div>

            <div class="news-content">
                <?php echo empty($data['content'])? '':html_entity_decode( $data['content'] ); ?>
            </div>

        </div>
        
    </div>

</body>
</html>