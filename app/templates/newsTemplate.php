<!DOCTYPE html>
<html>
<head>
	<title><?php echo empty($data['title'])? '文章详情页':$data['title']; ?></title>
	<meta charset="UTF-8">    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
    <meta content='initial-scale=1.0,user-scalable=no,maximum-scale=1,width=device-width' name='viewport' >
    <link rel="stylesheet" type="text/css" href="/css/template/newsTemplate.css?1">
</head>
<body>
	<div class="container-box">

        <!-- 标题 -->
        <div  class="title-box">
            <?php echo empty($data['title'])? '':$data['title']; ?>
        </div>

        <!-- 头像与名称 -->
        <div class="avatar-with-name-box">
            <div class="avatar-box">
                <img src="<?php echo empty($data['avatar'])? '/images/avatar.png':$data['avatar']; ?>" />
            </div>
            <p class="name-box"><?php echo empty($data['nickname'])? '管理员':$data['nickname']; ?></p>
            <p class="time-box"><?php echo empty($data['created_at'])? '': $data['created_at']; ?></p>
        </div>

        <div class="rest-dashed-1"></div>

        <!-- 标题 -->
        <div  class="title-box">
            导语
        </div>

        <!--简述内容-->
        <div class="sample-description-box">
            <?php echo empty($data['description'])? '':$data['description']; ?>
        </div>

        <div class="rest-dashed-1"></div>

        <!--缺省-->
        <div class="rest-40"></div>

        <div class="news-content">
            <?php echo empty($data['content'])? '':$data['content']; ?>
        </div>

        <div class="rest-40"></div>
        
    </div>
</body>
</html>