<?php
use NoahBuscher\Macaw\Macaw;

Macaw::get('/admin/activitys','Module\Activity\Controller\AdminActivityController@lists');
Macaw::get('/admin/activitys/create','Module\Activity\Controller\AdminActivityController@create');
Macaw::post('/admin/activitys/doCreate','Module\Activity\Controller\AdminActivityController@doCreate');
Macaw::get('/admin/activitys/update','Module\Activity\Controller\AdminActivityController@update');
Macaw::post('/admin/activitys/doUpdate','Module\Activity\Controller\AdminActivityController@doUpdate');
Macaw::get('/admin/activitys/read','Module\Activity\Controller\AdminActivityController@read');
Macaw::get('/admin/activitys/doDelete','Module\Activity\Controller\AdminActivityController@doDelete');
Macaw::post('/admin/activitys/destroy','Module\Activity\Controller\AdminActivityController@doDelete');

// 活动二维码
Macaw::get('/admin/activitys/readQrcode','Module\Activity\Controller\AdminActivityController@readQrcode');

// 报名情况查询
Macaw::get('/admin/activitys/application','Module\Activity\Controller\AdminActivityController@application');

// 删除报名人
Macaw::get('/admin/application/delete','Module\Activity\Controller\AdminActivityController@delete');

// 获取活动列表
Macaw::get('/api/v1/activity/lists','Module\Activity\Controller\ApiActivityController@lists');

// 报名
Macaw::post('/api/v1/activity/apply','Module\Activity\Controller\ApiActivityController@apply');

// 活动扫码签到 由于是走地址所以只能由get方式进行
Macaw::get('/api/v1/user/activity/scanQrcode/signIn','Module\Activity\Controller\User\Activity\ApiActivityController@scanQrcodeSignIn');