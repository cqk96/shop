<?php
use NoahBuscher\Macaw\Macaw;

Macaw::get('/','VirgoFront\HomeController@index');
Macaw::get('/map','VirgoFront\HomeController@map');
Macaw::get('/test111','VirgoFront\HomeController@test111');
// application
Macaw::post('/application/create','VirgoFront\ApplicationController@create');

//show news
Macaw::get('/front/showNews','VirgoFront\NewsController@read');

//wechatMsg  
Macaw::get('/wechatMsg','VirgoFront\WechatController@read');
Macaw::post('/wechatMsg','VirgoFront\WechatController@responseMsg');

// 用来测试function
Macaw::get('/func/test','VirgoFront\TestController@funcTest');

// 显示活动详情
Macaw::get('/front/activity/show','VirgoFront\HomeController@activityShow');

// 显示上传文件页面
Macaw::get('/front/test/v1/showForm','VirgoFront\TestController@showForm');

// 解析
Macaw::post('/front/test/v1/parse','VirgoFront\TestController@parse');

//show news-新
Macaw::get('/front/showNewsVer2','VirgoFront\NewsController@read2');

/*接口文档*/
Macaw::get("/app/module/swagger", "Virgo\Module\SwaggerController@index");

/*显示档案模板h5*/
// Macaw::get("/front/archiveTemplate/show", "VirgoFront\ArchiveTemplate\ArchiveTemplateController@show");

Macaw::post("/front/archiveTemplate/show", "VirgoFront\ArchiveTemplate\ArchiveTemplateController@showContent");

/*查看片区档案记录详情*/
Macaw::get('/front/archive/read','VirgoFront\Archive\ArchiveController@read');

/*根据分类名获取该分类最新一条详情*/
Macaw::get('/front/api/v1/NewsClass/news/className/latest','VirgoFront\NewsClass\News\NewsController@classNameLatest');

/*显示文章详情*/
Macaw::get('/front/api/v1/NewsClass/news/show','VirgoFront\NewsClass\News\NewsController@show');