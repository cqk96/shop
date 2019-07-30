<?php
use NoahBuscher\Macaw\Macaw;

Macaw::get('/test', function() {
    echo "successed!";
});

/*方法控制器  用于一些公用的方法之类的*/ 
//Macaw::post('/uploadJpegImage','FunctionsController@uploadJpegImage');

//网页验证码
Macaw::get('/verify','VirgoFront\VerifyController@verifica');

//注册验证码
Macaw::get('/registerVerify','VirgoFront\FunctionsController@registerVerify');

//手机号是否已存在 /hasPhone
Macaw::post('/hasPhone','VirgoFront\FunctionsController@hasPhone');

//测试
Macaw::post('/test1','TestController@test1');


// admin
require_once('admin_routes.php');

//mobile
require_once('mobile_routes.php');

// api
require_once('api_routes.php');

// front
require_once('front_routes.php');

// gii
require_once('gii.php');

// 模块
require_once('module_routes.php');

/*自定义无法找到路由错误处理*/
// \VirgoModel\NotFoundRouteModel::parseRoute();
Macaw::error("\VirgoModel\NotFoundRouteModel@parseRoute");

Macaw::dispatch();

