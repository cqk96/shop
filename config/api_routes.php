<?php
use NoahBuscher\Macaw\Macaw;

//工具模块

// 上传图片
Macaw::post('/api/v1/tools/uploadImage','VirgoApi\ApiUploadImageController@uploadimage');

// 上传附件
Macaw::post('/api/v1/tools/attachment/upload','VirgoApi\ApiAttachmentController@upload');

//工具模块--end

//获取注册验证码
Macaw::post('/api/v1/user/getRegisterVerify2','VirgoApi\ApiSessionController2@getRegisterVerify');//注册获取验证码

//用于password加密
Macaw::get('/api/v1/user/passwordEncrypt','VirgoApi\ApiSessionController@passwordEncrypt');

//将用户设置为登录 存于cookie中
Macaw::get('/api/v1/user/setUserIn','VirgoApi\ApiSessionController@setUserIn');

//获取忘记密码,修改密码验证码
Macaw::post('/api/v1/user/restPasswordVerify','VirgoApi\ApiSessionController2@restPasswordVerify');

//忘记密码
Macaw::post('/api/v1/user/resetPassword','VirgoApi\ApiSessionController2@resetPassword');

//前台修改密码
Macaw::post('/api/v1/front/resetPasswordFront','VirgoApi\ApiSessionController@resetPasswordFront');

Macaw::post('/api/v1/user/doForgetPasswordVerify','VirgoApi\ApiSessionController2@forgetPasswordVerify');

//执行用户注册操作
Macaw::post('/api/v1/user/doRegister','VirgoApi\ApiSessionController2@doRegister');

//执行用户登录
Macaw::get('/api/v1/user/loginVerify','VirgoApi\ApiSessionController2@loginVerify');

Macaw::post('/api/v1/user/getUserInfo','VirgoApi\ApiSessionController2@getUserInfo');//获取用户信息

//签到  
Macaw::post('/api/v1/user/doSignIn','VirgoApi\ApiSessionController@doSignIn');
/*用户基础模块 end*/

//轮播
Macaw::post('/api/v1/carousels','VirgoApi\ApiCarouselController@lists');

//获取文章
Macaw::post('/api/v1/news/read','VirgoApi\ApiNewsController@read');

//获取item_id
Macaw::post('/api/v1/newsClasses/read','VirgoApi\ApiNewsClassesController@read');

//修改用户信息
Macaw::post('/api/v1/user/update','VirgoApi\ApiSessionController@update');

/*修改用户信息*/
Macaw::post('/api/v1/user/back/userupdate','VirgoApi\User\ApiUserController@userupdate');
//通过文章类型 获取该类型下所有文章
#Macaw::get('/api/v1/news','VirgoApi\ApiNewsController@lists');
Macaw::post('/api/v1/news','VirgoApi\ApiNewsController@lists');

//收藏
Macaw::post('/api/v1/news/collect','VirgoApi\ApiNewsController@collect');

//评论--文章
Macaw::post('/api/v1/news/comment','VirgoApi\ApiCommentController@newsComment');

//评论点赞--文章
Macaw::post('/api/v1/news/comment/favor','VirgoApi\ApiCommentController@newsCommentFavor');

//评论列表--文章
Macaw::post('/api/v1/news/comments','VirgoApi\ApiCommentController@newsComments');

//上传附件
Macaw::post('/api/v1/tool/upload','VirgoApi\ApiFileController@upload');

//修改密码
Macaw::post('/api/v1/user/updatePWD','VirgoApi\ApiSessionController@updatePWD');

//我的收藏
Macaw::post('/api/v1/user/collected','VirgoApi\ApiSessionController@collected');

//添加建议
Macaw::post('/api/v1/advice','VirgoApi\ApiAdviceController@advice');

//通过新闻列表获取新闻 用于后台
Macaw::post('/api/v1/classes/back','VirgoApi\ApiNewsClassesController@backLists');

//举报
Macaw::post('/api/v1/user/reportComment','VirgoApi\ApiSessionController@reportComment');

//我的回复
Macaw::post('/api/v1/user/myComments','VirgoApi\ApiSessionController@myComments');

//工具--上傳apk
Macaw::post('/api/tools/uploadApk','VirgoApi\ApiUploadController@uploadApk');

//獲取安裝包
Macaw::get('/api/v1/app/lastest','VirgoApi\App\ApiAppController@lastest');

//获取缩略图
Macaw::get('/api/thumb','VirgoApi\ApiImageController@getThumb');

//前台修改用户基本信息
Macaw::post('/api/v1/front/editBaseInfo','VirgoApi\ApiSessionController@editBaseInfo');

//前台修改用户头像--数据资源
Macaw::post('/api/v1/front/avatarResource','VirgoApi\ApiSessionController@avatarResource');

//检测用户是否已登录
Macaw::get('/api/v1/front/userStillIn','VirgoApi\ApiSessionController@userStillIn');

//用户登出
Macaw::get('/api/v1/front/userOut','VirgoApi\ApiSessionController@userOut');

//显示cookie 
Macaw::get('/showCookie','VirgoApi\ApiSessionController@showCookie');

//异常奔溃上传
Macaw::post('/api/v/pushError','VirgoApi\ApiExceptionHandingController@pushError');

// 上传图片
Macaw::post('/api/v1/image/uploadBase64','VirgoApi\ApiImageController@uploadBase64');

/*音频服务--end*/

// 获取图片详情
Macaw::get('/api/v1/pics/read','VirgoApi\ApiImageController@read');

// 根据账号查找用户
Macaw::get('/api/v1/user/search','VirgoApi\User\ApiUserController@search');

// 创建忘记密码提醒
Macaw::post('/api/v1/message/forgetPwdNotice','VirgoApi\ApiMessageController@createPcForgetPwdNoticeMessage');

// 获取pc提醒
Macaw::get('/api/v1/messages/timeRange','VirgoApi\ApiMessageController@timeRange');



/*更改部门领导人*/
// Macaw::post('/front/api/v1/department/user/leader/update','VirgoApi\Department\User\Leader\ApiLeaderController@update');

/*获取特定分类的文章*/
Macaw::get('/api/v1/newsClass/all','VirgoApi\NewsClass\News\ApiNewsController@all');

/*获取所有轮播图*/ 
Macaw::get('/api/v1/carouselImg/lists','VirgoApi\CarouselImg\ApiCarouselImgController@lists');

// 将搜索数量+1
Macaw::post('/api/v1/news/createSearchCount','VirgoApi\News\ApiNewsController@createSearchCount');

// 说说列表
Macaw::get('/api/v1/chatCircle/lists','VirgoApi\ChatCircle\ApiChatCircleController@lists');

// 发表说说
Macaw::post('/api/v1/chatCircle/chat/create','VirgoApi\ChatCircle\ApiChatCircleController@create');

// 是否点赞说说
Macaw::post('/api/v1/chatCircle/chat/likeOrNot','VirgoApi\ChatCircle\ApiChatCircleController@likeOrNot');

// 评论说说
Macaw::post('/api/v1/chatCircle/chat/comment','VirgoApi\ChatCircle\ApiChatCircleController@comment');

// 获取指定用户的说说
Macaw::get('/api/v1/user/chatCircle/lists','VirgoApi\User\ChatCircle\ApiChatCircleController@lists');

// 修改头像
Macaw::post('/api/v1/user/avatar/update','VirgoApi\ApiSessionController@updateAvatar');

// 获取未读消息数量
Macaw::get('/api/v1/user/message/unreadCount','VirgoApi\User\Message\ApiMessageController@unreadCount');

// 获取用户消息列表
Macaw::get('/api/v1/user/message/lists','VirgoApi\User\Message\ApiMessageController@lists');

// 创建待推送消息
Macaw::post('/api/v1/message/webStation/create','VirgoApi\Message\WebStation\ApiMessageController@create');

// 推送待推送消息
Macaw::post('/api/v1/message/webStation/push','VirgoApi\Message\WebStation\ApiMessageController@push');

// 删除一个说说
Macaw::post('/api/v1/chatCircle/chat/delete','VirgoApi\ChatCircle\ApiChatCircleController@delete');

/*执行后台用户登录*/
Macaw::get('/api/v1/user/backLogin','VirgoApi\User\ApiUserController@backLogin');

/**菜单模块**/

/*获取用户拥有的所有菜单(登录后显示的菜单)*/
Macaw::get('/api/v1/user/menu/lists','VirgoApi\User\Menu\ApiMenuController@lists');

/*菜单管理 菜单列表*/
Macaw::get('/api/v1/menu/lists','VirgoApi\Menu\ApiMenuController@lists');

/*增加菜单*/
Macaw::post('/api/v1/menu/create','VirgoApi\Menu\ApiMenuController@create');

/*查看菜单详情*/
Macaw::get('/api/v1/menu/read','VirgoApi\Menu\ApiMenuController@read');

/*更新菜单*/
Macaw::post('/api/v1/menu/update','VirgoApi\Menu\ApiMenuController@update');

/*获取菜单列表(添加与修改时)*/
Macaw::get('/api/v1/menu/parentLists','VirgoApi\Menu\ApiMenuController@parentLists');

/*删除菜单*/
Macaw::post('/api/v1/menu/delete','VirgoApi\Menu\ApiMenuController@delete');

/*创建全部菜单*/
Macaw::post('/api/v1/menu/all/create','VirgoApi\Menu\ApiMenuController@allCreate');

/*查看菜单详情--嵌套包裹*/
Macaw::get('/api/v1/menu/detail','VirgoApi\Menu\ApiMenuController@detail');

/**菜单模块--end**/

/**角色模块**/

/*获取角色列表*/
Macaw::get('/api/v1/role/lists','VirgoApi\Role\ApiRoleController@lists');

/*创建角色*/
Macaw::post('/api/v1/role/create','VirgoApi\Role\ApiRoleController@create');

/*删除角色*/
Macaw::post('/api/v1/role/delete','VirgoApi\Role\ApiRoleController@delete');

/*角色详情*/
Macaw::get('/api/v1/role/read','VirgoApi\Role\ApiRoleController@read');

/*角色更新*/
Macaw::post('/api/v1/role/update','VirgoApi\Role\ApiRoleController@update');

/*获取角色列表*/
Macaw::get('/api/v1/role/all','VirgoApi\Role\ApiRoleController@all');

/**角色模块--end**/

/**角色菜单分配模块**/

/*获取当前角色 已获取到的菜单列表*/
Macaw::get('/api/v1/role/menu/inAll','VirgoApi\Role\Menu\ApiMenuController@inAll');

/*获取当前角色 已获取到的菜单列表*/
Macaw::get('/api/v1/role/menu/parentTreeInAll','VirgoApi\Role\Menu\ApiMenuController@parentTreeInAll');

/*更新角色拥有的分配菜单列表*/
Macaw::post('/api/v1/role/menu/update','VirgoApi\Role\Menu\ApiMenuController@update');

/**角色菜单分配模块--end**/


/**角色操作权限分配模块**/

/*获取当前角色 已获取到的操作权限列表*/
Macaw::get('/api/v1/role/operation/inAll','VirgoApi\Role\Operation\ApiOperationController@inAll');

/*更新角色拥有的操作权限列表*/
Macaw::post('/api/v1/role/operation/update','VirgoApi\Role\Operation\ApiOperationController@update');

/**角色操作权限分配模块--end**/

/**角色权限模块**/

/*获取当前角色 已获取到的权限列表*/
Macaw::get('/api/v1/role/privilege/inAll','VirgoApi\Role\Privilege\ApiPrivilegeController@inAll');

/*更新角色拥有的权限列表*/
Macaw::post('/api/v1/role/privilege/update','VirgoApi\Role\Privilege\ApiPrivilegeController@update');

/**角色权限模块--end**/

/*部门模块*/

/*部门列表*/
Macaw::get('/api/v1/department/lists','VirgoApi\Department\ApiDepartmentController@lists');

/*部门列表--all*/
Macaw::get('/api/v1/department/all','VirgoApi\Department\ApiDepartmentController@all');

/*添加顶级部门或下级部门*/
Macaw::post('/api/v1/department/create','VirgoApi\Department\ApiDepartmentController@create');

/*添加上级部门*/
Macaw::post('/api/v1/department/createHigher','VirgoApi\Department\ApiDepartmentController@createHigher');

/*更新部门信息*/
Macaw::post('/api/v1/department/updateDepartmentInfo','VirgoApi\Department\ApiDepartmentController@updateDepartmentInfo');

/*创建部门信息*/
Macaw::post('/api/v1/department/createDepartmentInfo','VirgoApi\Department\ApiDepartmentController@createDepartmentInfo');

/*创建部门信息*/
Macaw::post('/api/v1/department/createDepartment','VirgoApi\Department\ApiDepartmentController@createDepartment');
/*删除部门*/
Macaw::post('/api/v1/department/delete','VirgoApi\Department\ApiDepartmentController@delete');

/*查看部门详情*/
Macaw::get('/api/v1/department/read','VirgoApi\Department\ApiDepartmentController@read');

/*修改部门*/
Macaw::post('/api/v1/department/update','VirgoApi\Department\ApiDepartmentController@update');

/*当前部门用户*/
Macaw::get('/api/v1/department/user/lists','VirgoApi\Department\User\ApiUserController@lists');


/*添加部门用户*/
Macaw::post('/api/v1/department/user/create','VirgoApi\Department\User\ApiUserController@create');

/*删除部门用户*/
Macaw::post('/api/v1/department/user/delete','VirgoApi\Department\User\ApiUserController@delete');

/*app员工管理--附带搜索用户*/
Macaw::get('/api/v1/department/users','VirgoApi\Department\User\ApiUserController@users');

/*app 获取当前用户可以选择的部门列表*/
Macaw::get('/api/v1/department/selection','VirgoApi\Department\ApiDepartmentController@selection');

/*app 选择消息或其他接受人员*/
Macaw::get('/api/v1/department/departmentUsers','VirgoApi\Department\User\ApiUserController@departmentUsers');

/*部门模块--end*/

/*用户模块*/

/*获取用户列表*/
Macaw::get('/api/v1/user/lists','VirgoApi\User\ApiUserController@lists');

/*增加用户*/
Macaw::post('/api/v1/user/create','VirgoApi\User\ApiUserController@create');

/*显示用户列表*/
Macaw::get('/api/v1/user/getuserlists','VirgoApi\User\ApiUserController@getuserlists');
/*删除用户*/
Macaw::post('/api/v1/user/delete','VirgoApi\User\ApiUserController@delete');

/*查询用户详情 用于后台管理*/
Macaw::get('/api/v1/user/info','VirgoApi\User\ApiUserController@info');

/*查询用户详情 用于修改个人信息*/
Macaw::get('/api/v1/user/backInfo','VirgoApi\User\ApiUserController@backInfo');

/*查询用户详情*/
Macaw::get('/api/v1/user/read','VirgoApi\User\ApiUserController@read');

/*修改用户信息*/
Macaw::post('/api/v1/user/back/update','VirgoApi\User\ApiUserController@update');

/*是否存在该用户账号*/
Macaw::get('/api/v1/user/hasAccount','VirgoApi\User\ApiUserController@hasAccount');

/*用户修改自己的信息*/ 
Macaw::post('/api/v1/user/self/update','VirgoApi\User\ApiUserController@selfUpdate');

/*获取即时通讯用户列表(全部用户)--附带进行搜索*/
Macaw::get('/api/v1/user/all/chat/lists','VirgoApi\User\ApiUserController@chatLists');

/*获取全部用户*/ 
Macaw::get('/api/v1/user/all','VirgoApi\User\ApiUserController@all');

/*获取用户所在的班组*/
Macaw::get('/api/v1/user/department/groupMembers','VirgoApi\User\Department\ApiDepartmentController@groupMembers');

/*获取用户干过活的地块*/
Macaw::get('/api/v1/user/acre/works','VirgoApi\User\Acre\ApiAcreController@worksAcre');

/*后台修改用户密码*/
Macaw::post('/api/v1/user/backUpdatePWD','VirgoApi\User\ApiUserController@backUpdatePWD');

/*获取主管领导用户*/
Macaw::get('/api/v1/user/managers/all','VirgoApi\User\ApiUserController@managersAll');

/*用户模块--end*/

/*农场模块*/
/*获取农场列表*/
Macaw::get('/api/v1/farm/lists','VirgoApi\Farm\ApiFarmController@lists');

/*添加农场*/
Macaw::post('/api/v1/farm/create','VirgoApi\Farm\ApiFarmController@create');

/*删除农场*/
Macaw::post('/api/v1/farm/delete','VirgoApi\Farm\ApiFarmController@delete');

/*查询农场详情*/
Macaw::get('/api/v1/farm/read','VirgoApi\Farm\ApiFarmController@read');

/*修改农场*/
Macaw::post('/api/v1/farm/update','VirgoApi\Farm\ApiFarmController@update');

/*获取所有农场*/
Macaw::get('/api/v1/farm/all','VirgoApi\Farm\ApiFarmController@all');

/*获取对应地块列表--全部(精确到片区负责人)*/
Macaw::get('/api/v1/farm/acre/all','VirgoApi\Farm\Acre\ApiAcreController@all');

/*获取对应地块列表--全部(精确到地块负责人)*/
Macaw::get('/api/v1/farm/acre/managerInAll','VirgoApi\Farm\Acre\ApiAcreController@managerInAll');

/*农场模块--end*/

/*地块模块*/

/*获取地块列表*/
Macaw::get('/api/v1/acre/lists','VirgoApi\Acre\ApiAcreController@lists');

/*创建地块*/
Macaw::post('/api/v1/acre/create','VirgoApi\Acre\ApiAcreController@create');

/*删除地块*/
Macaw::post('/api/v1/acre/delete','VirgoApi\Acre\ApiAcreController@delete');

/*查看地块*/
Macaw::get('/api/v1/acre/read','VirgoApi\Acre\ApiAcreController@read');

/*修改地块*/
Macaw::post('/api/v1/acre/update','VirgoApi\Acre\ApiAcreController@update');

/*获取所有地块*/
Macaw::get('/api/v1/acre/all','VirgoApi\Acre\ApiAcreController@all');

/*地块模块--end*/


/*作物种类模块*/

/*获取作物种类列表*/
Macaw::get('/api/v1/cropType/lists','VirgoApi\CropType\ApiCropTypeController@lists');

/*获取全部作物种类*/
Macaw::get('/api/v1/cropType/all','VirgoApi\CropType\ApiCropTypeController@all');

/*增加作物种类*/
Macaw::post('/api/v1/cropType/create','VirgoApi\CropType\ApiCropTypeController@create');

/*删除作物种类*/
Macaw::post('/api/v1/cropType/delete','VirgoApi\CropType\ApiCropTypeController@delete');

/*查看作物种类*/
Macaw::get('/api/v1/cropType/read','VirgoApi\CropType\ApiCropTypeController@read');

/*查看作物种类*/
Macaw::get('/api/v1/cropType/detail','VirgoApi\CropType\ApiCropTypeController@detail');

/*修改作物种类*/
Macaw::post('/api/v1/cropType/update','VirgoApi\CropType\ApiCropTypeController@update');

/*作物种类模块--end*/

/*片区模块*/

/*获取片区列表*/
Macaw::get('/api/v1/area/lists','VirgoApi\Area\ApiAreaController@lists');

/*增加片区*/
Macaw::post('/api/v1/area/create','VirgoApi\Area\ApiAreaController@create');

/*删除片区*/
Macaw::post('/api/v1/area/delete','VirgoApi\Area\ApiAreaController@delete');

/*查看片区*/
Macaw::get('/api/v1/area/read','VirgoApi\Area\ApiAreaController@read');

/*修改片区*/
Macaw::post('/api/v1/area/update','VirgoApi\Area\ApiAreaController@update');

/*片区档案列表*/
Macaw::get('/api/v1/area/archive/lists','VirgoApi\Area\ApiAreaController@archiveLists');

/*获取指定地块 片区指定分类列表*/
Macaw::get('/api/v1/area/type/lists','VirgoApi\Area\ApiAreaController@typeLists');

/*获取指定地块 片区指定分类列表--精确到地块负责人*/
Macaw::get('/api/v1/area/manager/type/lists','VirgoApi\Area\ApiAreaController@managerTypeLists');

/*获取片区下作物列表*/
Macaw::get('/api/v1/area/crop/lists','VirgoApi\Area\Crop\ApiCropController@lists');

/*获取作物有档案时间*/
Macaw::get('/api/v1/area/operateTime','VirgoApi\Area\ApiAreaController@operateTime');

/*获取作物档案时间 内的档案数据*/
Macaw::get('/api/v1/area/operateTime/templates','VirgoApi\Area\ApiAreaController@operateTimeTemplates');


/*片区模块--end*/

/*apk模块*/

/*获取apk包版本列表*/
Macaw::get('/api/v1/apk/lists','VirgoApi\App\ApiAppController@lists');

/*增加apk包版本*/
Macaw::post('/api/v1/apk/create','VirgoApi\App\ApiAppController@create');

/*删除apk包版本*/
Macaw::post('/api/v1/apk/delete','VirgoApi\App\ApiAppController@delete');

/*查看apk包版本记录*/
Macaw::get('/api/v1/apk/read','VirgoApi\App\ApiAppController@read');

/*修改apk包版本记录*/
Macaw::post('/api/v1/apk/update','VirgoApi\App\ApiAppController@update');

/*apk模块--end*/

/*模板分类模块*/

/*获取模板分类列表*/
Macaw::get('/api/v1/archiveTemplateCategory/lists','VirgoApi\ArchiveTemplateCategory\ApiArchiveTemplateCategoryController@lists');

/*增加*/
Macaw::post('/api/v1/archiveTemplateCategory/create','VirgoApi\ArchiveTemplateCategory\ApiArchiveTemplateCategoryController@create');

/*删除*/
Macaw::post('/api/v1/archiveTemplateCategory/delete','VirgoApi\ArchiveTemplateCategory\ApiArchiveTemplateCategoryController@delete');

/*查看*/
Macaw::get('/api/v1/archiveTemplateCategory/read','VirgoApi\ArchiveTemplateCategory\ApiArchiveTemplateCategoryController@read');

/*修改*/
Macaw::post('/api/v1/archiveTemplateCategory/update','VirgoApi\ArchiveTemplateCategory\ApiArchiveTemplateCategoryController@update');

/*获取指定分类的档案列表*/
Macaw::get('/api/v1/archiveTemplateCategory/archiveTemplate/lists','VirgoApi\ArchiveTemplateCategory\ArchiveTemplate\ApiArchiveTemplateController@lists');

/*模板分类模块--end*/

/*档案模块*/

/*列表*/
Macaw::get('/api/v1/archiveTemplate/lists','VirgoApi\ArchiveTemplate\ApiArchiveTemplateController@lists');

/*增加*/
Macaw::post('/api/v1/archiveTemplate/create','VirgoApi\ArchiveTemplate\ApiArchiveTemplateController@create');

/*删除*/
Macaw::post('/api/v1/archiveTemplate/delete','VirgoApi\ArchiveTemplate\ApiArchiveTemplateController@delete');

/*查看*/
Macaw::get('/api/v1/archiveTemplate/read','VirgoApi\ArchiveTemplate\ApiArchiveTemplateController@read');

/*修改*/
Macaw::post('/api/v1/archiveTemplate/update','VirgoApi\ArchiveTemplate\ApiArchiveTemplateController@update');

/*修改启用状态*/
Macaw::post('/api/v1/archiveTemplate/status/update','VirgoApi\ArchiveTemplate\ApiArchiveTemplateController@statusUpdate');

/*修改*/
// Macaw::post('/api/v1/archiveTemplate/update','VirgoApi\ArchiveTemplate\ApiArchiveTemplateController@update');

/*上传档案数据*/
Macaw::post('/api/v1/archiveTemplate/uploadData','VirgoApi\ArchiveTemplate\ApiArchiveTemplateController@uploadData');

/*获取所有档案--可通过传入分类id判定这个分类是否包含了这个档案*/
Macaw::get('/api/v1/archiveTemplate/inAll','VirgoApi\ArchiveTemplate\ApiArchiveTemplateController@inAll');

/*档案模块--end*/

/*作物模块*/

/*作物档案列表*/
Macaw::get('/api/v1/crop/archive/lists','VirgoApi\Crop\ApiCropController@archiveLists');

/*作物批量上传*/
Macaw::post('/api/v1/crop/batchUpload','VirgoApi\Crop\ApiCropController@batchUpload');

/*获取作物种类列表*/
Macaw::get('/api/v1/crop/lists','VirgoApi\Crop\ApiCropController@lists');

/*删除作物*/
Macaw::post('/api/v1/crop/delete','VirgoApi\Crop\ApiCropController@delete');

/*改变作物状态*/
Macaw::post('/api/v1/crop/changeStatus','VirgoApi\Crop\ApiCropController@changeStatus');

/*获取作物有档案时间*/
Macaw::get('/api/v1/crop/operateTime','VirgoApi\Crop\ApiCropController@operateTime');

/*获取作物档案时间 内的档案数据*/
Macaw::get('/api/v1/crop/operateTime/templates','VirgoApi\Crop\ApiCropController@operateTimeTemplates');

/*修改作物*/
Macaw::post('/api/v1/crop/update','VirgoApi\Crop\ApiCropController@update');

/*作物模块--end*/

/*主体模块*/

/*获取全部主体--可通过传入分类id判定这个分类是否包含了这个主体*/
Macaw::get('/api/v1/mainBody/inAll','VirgoApi\MainBody\ApiMainBodyController@inAll');

/*获取主体对应的档案分类*/
Macaw::get('/api/v1/mainBody/type/lists','VirgoApi\MainBody\ApiMainBodyController@typeLists');

/*主体模块--end*/

/*文章分类模块*/

/*列表*/
Macaw::get('/api/v1/NewsClass/lists','VirgoApi\NewsClass\ApiNewsClassController@lists');

/*增加分类*/
Macaw::post('/api/v1/NewsClass/create','VirgoApi\NewsClass\ApiNewsClassController@create');

/*删除分类*/
Macaw::post('/api/v1/NewsClass/delete','VirgoApi\NewsClass\ApiNewsClassController@delete');

/*查看分类*/
Macaw::get('/api/v1/NewsClass/read','VirgoApi\NewsClass\ApiNewsClassController@read');

/*修改分类*/
Macaw::post('/api/v1/NewsClass/update','VirgoApi\NewsClass\ApiNewsClassController@update');

/*全部*/
Macaw::get('/api/v1/NewsClass/allList','VirgoApi\NewsClass\ApiNewsClassController@allList');

/*根据分类名获取对应列表*/
Macaw::get('/api/v1/NewsClass/news/classNamesLists','VirgoApi\NewsClass\News\ApiNewsController@classNamseLists');

/*文章分类模块--end*/

/*文章模块*/

/*列表*/
Macaw::get('/api/v1/news/lists','VirgoApi\News\ApiNewsController@lists');

/*增加*/
Macaw::post('/api/v1/news/create','VirgoApi\News\ApiNewsController@create');

/*删除*/
Macaw::post('/api/v1/news/delete','VirgoApi\News\ApiNewsController@delete');

/*查看*/
Macaw::get('/api/v1/news/detail','VirgoApi\News\ApiNewsController@read');

/*修改*/
Macaw::post('/api/v1/news/update','VirgoApi\News\ApiNewsController@update');

/*文章模块--end*/

/*十日报模块*/

/*添加*/
Macaw::post('/api/v1/diary/tenDayDiary/create','VirgoApi\Diary\TenDayDiary\ApiDiaryController@create');

/*详情*/
Macaw::get('/api/v1/diary/tenDayDiary/detail','VirgoApi\Diary\TenDayDiary\ApiDiaryController@detail');

/*获取当前用户填写过的期号*/
Macaw::get('/api/v1/diary/tenDayDiary/user/term','VirgoApi\Diary\TenDayDiary\ApiDiaryController@userTerm');

/*修改*/
Macaw::post('/api/v1/diary/tenDayDiary/update','VirgoApi\Diary\TenDayDiary\ApiDiaryController@update');

/*场长审批*/
Macaw::post('/api/v1/diary/tenDayDiary/farmLeader/save','VirgoApi\Diary\TenDayDiary\FarmLeader\ApiDiaryController@save');

/*公司高管审批*/
Macaw::post('/api/v1/diary/tenDayDiary/companyExecutives/save','VirgoApi\Diary\TenDayDiary\CompanyExecutives\ApiDiaryController@save');

/*我的审批任务*/
Macaw::get('/api/v1/diary/tenDayDiary/missions','VirgoApi\Diary\TenDayDiary\ApiDiaryController@missions');

/*我的审批任务--之后台使用*/
Macaw::get('/api/v1/diary/tenDayDiary/backMissions','VirgoApi\Diary\TenDayDiary\ApiDiaryController@backMissions');

/*我的十日报*/
Macaw::get('/api/v1/user/diary/tenDayDiary/lists','VirgoApi\User\Diary\TenDayDiary\ApiDiaryController@lists');

/*我的十日报之后台接口*/
Macaw::get('/api/v1/user/diary/tenDayDiary/backLists','VirgoApi\User\Diary\TenDayDiary\ApiDiaryController@backLists');

/*十日报模块--end*/

/*月报模块*/

/*添加*/
Macaw::post('/api/v1/diary/monthly/create','VirgoApi\Diary\Monthly\ApiDiaryController@create');

/*修改*/
Macaw::post('/api/v1/diary/monthly/update','VirgoApi\Diary\Monthly\ApiDiaryController@update');

/*我的月报*/
Macaw::get('/api/v1/user/diary/monthly/lists','VirgoApi\User\Diary\Monthly\ApiDiaryController@lists');

/*我的月报--之后台使用*/
Macaw::get('/api/v1/user/diary/monthly/backLists','VirgoApi\User\Diary\Monthly\ApiDiaryController@backLists');

/*我的审阅月报*/
Macaw::get('/api/v1/user/diary/monthly/read/lists','VirgoApi\User\Diary\Monthly\ApiDiaryController@readLists');

/*我的审阅月报--之后台使用*/
Macaw::get('/api/v1/user/diary/monthly/read/backReadLists','VirgoApi\User\Diary\Monthly\ApiDiaryController@backReadLists');

/*月报详情*/
Macaw::get('/api/v1/diary/monthly/detail','VirgoApi\Diary\Monthly\ApiDiaryController@detail');

/*月报模块--end*/

/*指令模块*/

/*增加(即刻推送)*/
Macaw::post('/api/v1/instruction/create','VirgoApi\Instruction\ApiInstructionController@create');

/*我创建的指令列表*/
Macaw::get('/api/v1/user/instruction/creatorLists','VirgoApi\User\Instruction\ApiInstructionController@creatorLists');

/*获取给我的指令列表--是否已读/是否已完成*/
Macaw::get('/api/v1/user/instruction/lists','VirgoApi\User\Instruction\ApiInstructionController@lists');

/*标记阅读一条指令*/
Macaw::post('/api/v1/user/instruction/tagRead','VirgoApi\User\Instruction\ApiInstructionController@tagRead');

/*标记阅读部分指令*/
Macaw::post('/api/v1/user/instruction/part/tagRead','VirgoApi\User\Instruction\ApiInstructionController@partTagRead');

/*标记阅读全部指令*/
Macaw::post('/api/v1/user/instruction/all/tagRead','VirgoApi\User\Instruction\ApiInstructionController@allTagRead');

/*标记完成一条指令*/
Macaw::post('/api/v1/user/instruction/tagDone','VirgoApi\User\Instruction\ApiInstructionController@tagDone');

/*指令推送的用户列表*/
// Macaw::get('/api/v1/user/instruction/userLists','VirgoApi\User\Instruction\ApiInstructionController@userLists');

/*指令详情*/
Macaw::get('/api/v1/instruction/detail','VirgoApi\Instruction\ApiInstructionController@detail');

/*我创建的指令列表--之后台使用*/
Macaw::get('/api/v1/user/instruction/backCreatorLists','VirgoApi\User\Instruction\ApiInstructionController@backCreatorLists');

/*指令模块--end*/

/*消息模块*/

/*我收到的消息列表*/
Macaw::get('/api/v1/user/message/lists','VirgoApi\User\Message\ApiMessageController@lists');

/*标记部分消息已读*/
Macaw::post('/api/v1/user/message/part/tagRead','VirgoApi\User\Message\ApiMessageController@tagRead');

/*标记全部消息已读*/
Macaw::post('/api/v1/user/message/all/tagRead','VirgoApi\User\Message\ApiMessageController@allTagRead');

/*消息列表管理*/
Macaw::get('/api/v1/message/lists','VirgoApi\Message\WebStation\ApiMessageController@lists');

/*创建消息*/
Macaw::post('/api/v1/message/create','VirgoApi\Message\WebStation\ApiMessageController@create');

/*推送消息*/
Macaw::post('/api/v1/message/push','VirgoApi\Message\WebStation\ApiMessageController@push');

/*获取消息推送结果*/
Macaw::get('/api/v1/message/result','VirgoApi\Message\WebStation\ApiMessageController@result');

/*获取未推送消息推送人员列表*/
Macaw::get('/api/v1/message/unpushed/detail','VirgoApi\Message\WebStation\ApiMessageController@unpushedDetail');

/*更新消息*/
Macaw::post('/api/v1/message/update','VirgoApi\Message\WebStation\ApiMessageController@update');

/*显示商品列表*/
Macaw::get('/api/v1/Cod/lists','VirgoApi\Cod\ApiCodController@lists');
/*二级域名是否重复*/
Macaw::get('/api/v1/Cod/hascatalog','VirgoApi\Cod\ApiCodController@hascatalog');
/*显示商品列表*/
Macaw::get('/api/v1/Cod/listscomment','VirgoApi\Cod\ApiCodController@listscomment');
/*添加商品*/
Macaw::post('/api/v1/Cod/goods','VirgoApi\Cod\ApiCodController@goods');
/*显示商品详情*/
Macaw::get('/api/v1/Cod/goodsdetail','VirgoApi\Cod\ApiCodController@goodsdetail');
/*修改商品*/
Macaw::post('/api/v1/Cod/goodsupdate','VirgoApi\Cod\ApiCodController@goodsupdate');
/*删除商品*/
Macaw::post('/api/v1/Cod/goodsdelete','VirgoApi\Cod\ApiCodController@goodsdelete');
/*获取套餐*/
Macaw::post('/api/v1/Cod/setmeal','VirgoApi\Cod\ApiCodController@setmeal');
/*显示套餐属性*/
Macaw::get('/api/v1/Cod/getsetmeal','VirgoApi\Cod\ApiCodController@getsetmeal');
/*修改套餐*/
Macaw::post('/api/v1/Cod/setmealupdate','VirgoApi\Cod\ApiCodController@setmealupdate');
/*修改属性*/
Macaw::post('/api/v1/Cod/propertiesupdate','VirgoApi\Cod\ApiCodController@propertiesupdate');
/*获取属性*/
Macaw::post('/api/v1/Cod/properties','VirgoApi\Cod\ApiCodController@properties');

/*添加品类*/
Macaw::post('/api/v1/Category/create','VirgoApi\Category\ApiCategoryController@create');
/*品类*/
Macaw::get('/api/v1/Category/hascategory','VirgoApi\Category\ApiCategoryController@hascategory');
/*品类列表*/
Macaw::get('/api/v1/Category/lists','VirgoApi\Category\ApiCategoryController@lists');
/*所有品类*/
Macaw::get('/api/v1/Category/allcategory','VirgoApi\Category\ApiCategoryController@allcategory');
/*品类修改*/
Macaw::post('/api/v1/Category/update','VirgoApi\Category\ApiCategoryController@update');
/*品类修改*/
Macaw::post('/api/v1/Category/categorydelete','VirgoApi\Category\ApiCategoryController@categorydelete');

/*添加评论*/
Macaw::post('/api/v1/Comment/create','VirgoApi\Comment\ApiCommentController@create');
/*评论列表*/
Macaw::get('/api/v1/Comment/lists','VirgoApi\Comment\ApiCommentController@lists');

/*评论详情*/
Macaw::get('/api/v1/Comment/detail','VirgoApi\Comment\ApiCommentController@detail');
/*修改评论*/
Macaw::post('/api/v1/Comment/update','VirgoApi\Comment\ApiCommentController@update');
/*删除评论*/
Macaw::post('/api/v1/Comment/commentdelete','VirgoApi\Comment\ApiCommentController@commentdelete');
/*消息模块--end*/

/*消息模块--end*/

/*统计*/
/*产品总数*/
Macaw::get('/api/v1/Statistics/allgoods','VirgoApi\Statistics\ApiStatisticsController@allgoods');
/*订单总数*/
Macaw::get('/api/v1/Statistics/allproduct','VirgoApi\Statistics\ApiStatisticsController@allproduct');
/*今日订单数*/
Macaw::get('/api/v1/Statistics/todayproductorder','VirgoApi\Statistics\ApiStatisticsController@todayproductorder');
/*今日产品上新*/
Macaw::get('/api/v1/Statistics/todaygoods','VirgoApi\Statistics\ApiStatisticsController@todaygoods');
/*今日营业额*/
Macaw::get('/api/v1/Statistics/Turnover','VirgoApi\Statistics\ApiStatisticsController@Turnover');
/*统计汇总*/
Macaw::get('/api/v1/Statistics/gather','VirgoApi\Statistics\ApiStatisticsController@gather');
/*统计--end*/


/*国家模块*/

/*列表*/
Macaw::get('/api/v1/country/lists','VirgoApi\V1\Country\ApiCountryController@lists');

/*判断是否已经存在该名称国家*/
Macaw::get('/api/v1/country/hasCountry','VirgoApi\V1\Country\ApiCountryController@hasCountry');

/*增加*/
Macaw::post('/api/v1/country/create','VirgoApi\V1\Country\ApiCountryController@create');

/*删除*/
Macaw::post('/api/v1/country/delete','VirgoApi\V1\Country\ApiCountryController@delete');

/*查看*/
Macaw::get('/api/v1/country/read','VirgoApi\V1\Country\ApiCountryController@read');

/*修改*/
Macaw::post('/api/v1/country/update','VirgoApi\V1\Country\ApiCountryController@update');

/*全部*/
Macaw::get('/api/v1/country/all','VirgoApi\V1\Country\ApiCountryController@all');

/*国家模块--end*/

/*模板模块*/

/*列表*/
Macaw::get('/api/v1/template/productTemplate/lists','VirgoApi\V1\Template\ProductTemplate\ApiProductTemplateController@lists');

/*增加*/
Macaw::post('/api/v1/template/productTemplate/create','VirgoApi\V1\Template\ProductTemplate\ApiProductTemplateController@create');

/*删除*/
Macaw::post('/api/v1/template/productTemplate/delete','VirgoApi\V1\Template\ProductTemplate\ApiProductTemplateController@delete');

/*查看*/
Macaw::get('/api/v1/template/productTemplate/read','VirgoApi\V1\Template\ProductTemplate\ApiProductTemplateController@read');

/*修改*/
Macaw::post('/api/v1/template/productTemplate/update','VirgoApi\V1\Template\ProductTemplate\ApiProductTemplateController@update');

/*查询模板情况*/
Macaw::get('/api/v1/template/productTemplate/search','VirgoApi\V1\Template\ProductTemplate\ApiProductTemplateController@search');

/*模板模块--end*/

/*货币模块*/

/*列表*/
Macaw::get('/api/v1/currency/lists','VirgoApi\V1\Currency\ApiCurrencyController@lists');

/*判断是否已经存在该名称国家*/
Macaw::get('/api/v1/currency/hasCurrency','VirgoApi\V1\Currency\ApiCurrencyController@hasCurrency');

/*增加*/
Macaw::post('/api/v1/currency/create','VirgoApi\V1\Currency\ApiCurrencyController@create');

/*删除*/
Macaw::post('/api/v1/currency/delete','VirgoApi\V1\Currency\ApiCurrencyController@delete');

/*查看*/
Macaw::get('/api/v1/currency/read','VirgoApi\V1\Currency\ApiCurrencyController@read');

/*修改*/
Macaw::post('/api/v1/currency/update','VirgoApi\V1\Currency\ApiCurrencyController@update');

/*查询模板情况*/
Macaw::get('/api/v1/currency/all','VirgoApi\V1\Currency\ApiCurrencyController@all');

/*货币模块--end*/


/*货币模块*/

/*列表*/
Macaw::get('/api/v1/productOrder/lists','VirgoApi\V1\ProductOrder\ApiProductOrderController@lists');
/*删除*/
Macaw::post('/api/v1/productOrder/delete','VirgoApi\V1\ProductOrder\ApiProductOrderController@delete');
/*增加*/
Macaw::post('/api/v1/productOrder/create','VirgoApi\V1\ProductOrder\ApiProductOrderController@create');
/*导出*/
Macaw::get('/api/v1/productOrder/exportOrderExcel','VirgoApi\V1\ProductOrder\ApiProductOrderController@exportOrderExcel');

/*删除*/
// Macaw::post('/api/v1/currency/delete','VirgoApi\V1\Currency\ApiCurrencyController@delete');

/*查看*/
Macaw::get('/api/v1/productOrder/read','VirgoApi\V1\ProductOrder\ApiProductOrderController@read');

/*修改*/
// Macaw::post('/api/v1/currency/update','VirgoApi\V1\Currency\ApiCurrencyController@update');

/*修改订单状态*/
Macaw::post('/api/v1/productOrder/updateStatus','VirgoApi\V1\ProductOrder\ApiProductOrderController@updateStatus');

/*修改订单物流*/
Macaw::post('/api/v1/productOrder/updateExpressInfo','VirgoApi\V1\ProductOrder\ApiProductOrderController@updateExpressInfo');

/*查看订单物流*/
Macaw::get('/api/v1/productOrder/expressTraceInfo','VirgoApi\V1\ProductOrder\ApiProductOrderController@expressTraceInfo');

/*货币模块--end*/