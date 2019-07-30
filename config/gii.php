<?php
/**
* 仿yii gii 路由
*/
use NoahBuscher\Macaw\Macaw;
Macaw::get('/gii/create','VirgoBack\AdminGiiController@create');

Macaw::post('/gii/doCreate','VirgoBack\AdminGiiController@doCreate');