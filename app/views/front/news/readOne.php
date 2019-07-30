<!DOCTYPE html>
<html>
<head>
	<title><?php echo empty($data['title'])? '文章详情页':$data['title']; ?></title>
	<meta charset="UTF-8">    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
    <meta content='initial-scale=1.0,user-scalable=no,maximum-scale=1,width=device-width' name='viewport' >
    <link rel="stylesheet" type="text/css" href="/css/template/newsTemplate.css">
    <style type="text/css">
    html,body,div,span, img, p{margin: 0px; padding: 0px; }
html, body {width: 100%; height: 100%; overflow-x: hidden; }
body {background-color: #FFF; position: relative; }
.container-box {width: 100%; height: 100%; margin: 0 auto; overflow-y: scroll; }
.title-box {line-height: 2; padding-top: 25px; font-weight: 500; font-size: 18px; text-align: justify; }
.avatar-with-name-box {width: 100%; height: 48px; margin-top: 30px; margin-bottom: 30px; }
.avatar-box {width: 48px; height: 48px; display: inline-block; overflow: hidden; border-radius: 100%; box-shadow: 0px 0px 3px #bebebe; float: left; margin-right: 20px; }
.avatar-box img{display: block; width:  100%; height: auto; }
.name-box {font-size: 16px; color: #bebebe; margin-top: 2px; }
.time-box {font-size: 14px; color: #dcdcdc; margin-top: 5px; }
.rest-dashed-1 {width: 100%; height: 1px; border-top: 1px dashed #dcdcdc; }
.sample-description-box {font-size: 16px; color: #bebebe; line-height: 2; text-align: justify; margin-top: 10px; margin-bottom: 30px; }
.rest-40 {width: 100%; height: 40px; }
.news-content{
    padding-left: 17px;
    padding-right: 17px;
}
 .news-content img{display: block; width: 100%; margin: 0 auto; }
    .container-box {
        background: #FFFFFF;
        box-shadow: 0 0 20px 0 rgba(0,0,0,0.08);
    }
    .book-icon{
        display: inline-block;
        vertical-align: middle;
        max-width: 50px;
        margin-right: 15px;
        margin-left: 15px;
    }
    </style>
</head>
<body>
	<div class="container-box">

        <!-- 标题 -->
        <div  class="title-box">
            <img class="book-icon" src="/images/book2@1.5x.png">
            <?php echo empty($data['title'])? '':$data['title']; ?>
        </div>

        <!--缺省-->
        <div class="rest-40"></div>

        <div class="news-content">
            <?php echo empty($data['content'])? '':$data['content']; ?>
        </div>

        <div class="rest-40"></div>
        
    </div>
</body>
</html>