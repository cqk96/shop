
<?php
use NoahBuscher\Macaw\Macaw;

// 后台session

// 后台登录
Macaw::get('/admin','VirgoBack\AdminController@login');

// 后台注册页面
Macaw::get('/signup','VirgoBack\AdminSessionController@signup');

// 后台注册逻辑
Macaw::post('/register','VirgoBack\AdminSessionController@register');

//修改管理员密码
Macaw::post('/admin/user/updateAdminPwd','VirgoBack\AdminSessionController@updateAdminPwd');

//后台管理员登出
Macaw::post('/admin/user/logOut','VirgoBack\AdminSessionController@logOut');

// 后台用户注册
Macaw::post('/admin/user/register','VirgoBack\AdminSessionController@userRegister');

// 后台用户详情
Macaw::get('/admin/user/read','VirgoBack\AdminSessionController@read');

//后台修改用户信息 
Macaw::post('/admin/user/updateUserInfo','VirgoBack\AdminSessionController@updateUserInfo');

//后台用户登录判断
Macaw::post('/admin/readUser','VirgoBack\AdminController@readUserVer3');

//后台用户登录判断--ver2
Macaw::post('/admin/readUserVer2','VirgoBack\AdminController@readUserVer2');

//后台首页
Macaw::get('/admin/home','VirgoBack\AdminController@home');

/*个人中心*/ 
Macaw::get('/admin/mine','VirgoBack\AdminSessionController@mine');

/*后台菜单*/
Macaw::get('/admin/menus','VirgoBack\AdminController@menus');

/*后台新闻分类*/
Macaw::get('/admin/newsClasses','VirgoBack\AdminNewsClassesController@lists');

/*后台新闻*/
Macaw::get('/admin/news','VirgoBack\AdminNewsController@lists');

// 后台修改用户头像--base64图片上传
Macaw::post('/admin/user/updateUserAvatar','VirgoBack\AdminSessionController@updateUserAvatar');
// 后台session--end

// 说说管理
Macaw::get('/admin/chatCircle','VirgoBack\AdminChatCircleController@lists');
Macaw::get('/admin/chatCircle/doDelete','VirgoBack\AdminChatCircleController@doDelete');
Macaw::post('/admin/chatCircle/doDelete','VirgoBack\AdminChatCircleController@doDelete');
// 说说管理--end

// 说说评论
Macaw::get('/admin/commentChatCircle','VirgoBack\AdminCommentChatCircleController@lists');
Macaw::get('/admin/commentChatCircle/delete','VirgoBack\AdminCommentChatCircleController@delete');
// 说说评论--end

/*新闻栏目模块*/
Macaw::get('/admin/newsClass/create','VirgoBack\AdminNewsClassesController@create');
Macaw::post('/admin/newsClass/doCreate','VirgoBack\AdminNewsClassesController@doCreate');
Macaw::get('/admin/newsClass/update','VirgoBack\AdminNewsClassesController@update');
Macaw::post('/admin/newsClass/doUpdate','VirgoBack\AdminNewsClassesController@doUpdate');
Macaw::get('/admin/newsClass/read','VirgoBack\AdminNewsClassesController@read');
Macaw::get('/admin/newsClass/delete','VirgoBack\AdminNewsClassesController@delete');
Macaw::post('/admin/newsClass/delete2','VirgoBack\AdminNewsClassesController@delete');

/*新闻栏目下属具体文章列表*/ 
Macaw::get('/admin/newsClass/news/lists','VirgoBack\NewsClass\News\AdminNewsController@lists');

/*新闻栏目模块--end*/

/*新闻管理*/
Macaw::get('/admin/news/create','VirgoBack\AdminNewsController@create');
Macaw::post('/admin/news/doCreate','VirgoBack\AdminNewsController@doCreate');
Macaw::get('/admin/news/update','VirgoBack\AdminNewsController@update');
Macaw::post('/admin/news/doUpdate','VirgoBack\AdminNewsController@doUpdate');
Macaw::get('/admin/news/read','VirgoBack\AdminNewsController@read');
Macaw::get('/admin/news/delete','VirgoBack\AdminNewsController@delete');

// 批量删除
Macaw::post('/admin/news/delete2','VirgoBack\AdminNewsController@delete');

// 置顶
Macaw::post('/admin/news/doTop','VirgoBack\AdminNewsController@doTop');

// 删除封面
Macaw::post('/admin/news/deleteNewsCover','VirgoBack\AdminNewsController@deleteNewsCover');

/*后台文章详情*/
Macaw::get('/admin/news/readVer2','VirgoBack\AdminNewsController@readVer2');

/*新闻管理--end*/

/*站点设置*/
Macaw::get('/admin/site','VirgoBack\AdminSiteController@read');
Macaw::post('/admin/site/doUpdate','VirgoBack\AdminSiteController@doUpdate');
/*站点设置--end*/

// 用户管理
Macaw::get('/admin/users','\VirgoBack\AdminUserController@index');
Macaw::get('/admin/user/create','VirgoBack\AdminUserController@create');
Macaw::post('/admin/user/doCreate','VirgoBack\AdminUserController@doCreate');
Macaw::get('/admin/user/update','VirgoBack\AdminUserController@update');
Macaw::post('/admin/user/doUpdate','VirgoBack\AdminUserController@doUpdate');
Macaw::get('/admin/user/doDelete','VirgoBack\AdminUserController@doDelete');
Macaw::post('/admin/user/doDelete2','VirgoBack\AdminUserController@doDelete');
Macaw::get('/admin/user/resetPwd','VirgoBack\AdminUserController@resetPwd');
// 用户管理--end

// 前台导航管理
Macaw::get('/admin/nav','\VirgoBack\AdminNavController@lists');
Macaw::get('/admin/nav/create','VirgoBack\AdminNavController@create');
Macaw::post('/admin/nav/doCreate','VirgoBack\AdminNavController@doCreate');
Macaw::get('/admin/nav/update','VirgoBack\AdminNavController@update');
Macaw::post('/admin/nav/doUpdate','VirgoBack\AdminNavController@doUpdate');
Macaw::get('/admin/nav/read','VirgoBack\AdminNavController@read');
Macaw::get('/admin/nav/delete','VirgoBack\AdminNavController@delete');

// 批量删除
Macaw::post('/admin/nav/delete2','VirgoBack\AdminNavController@delete');
// 前台导航管理--end

// 片段管理
Macaw::get('/admin/pieces','\VirgoBack\AdminPieceController@index');
Macaw::get('/admin/piece/add','VirgoBack\AdminPieceController@add');
Macaw::post('/admin/piece/create','VirgoBack\AdminPieceController@create');
Macaw::get('/admin/piece/edit','VirgoBack\AdminPieceController@edit');
Macaw::post('/admin/piece/update','VirgoBack\AdminPieceController@update');
Macaw::get('/admin/piece/detail','VirgoBack\AdminPieceController@detail');
Macaw::get('/admin/piece/delete','VirgoBack\AdminPieceController@delete');
Macaw::get('/admin/piece/destroy','VirgoBack\AdminPieceController@destroy');
// 片段管理--end

// 权限管理
Macaw::get('/admin/sys/privileges','VirgoBack\AdminPrivilegeController@lists');
Macaw::get('/admin/sys/privilege/create','VirgoBack\AdminPrivilegeController@create');
Macaw::post('/admin/sys/privilege/doCreate','VirgoBack\AdminPrivilegeController@doCreate');
Macaw::get('/admin/sys/privilege/update','VirgoBack\AdminPrivilegeController@update');
Macaw::post('/admin/sys/privilege/doUpdate','VirgoBack\AdminPrivilegeController@doUpdate');
//Macaw::get('/admin/sys/privilege/read','VirgoBack\AdminPrivilegeController@read');
Macaw::get('/admin/sys/privilege/doDelete','VirgoBack\AdminPrivilegeController@doDelete');
Macaw::post('/admin/sys/privilege/doDelete2','VirgoBack\AdminPrivilegeController@doDelete');
//权限管理--end

// 角色管理
Macaw::get('/admin/sys/roles','VirgoBack\AdminRoleController@lists');
Macaw::get('/admin/sys/role/create','VirgoBack\AdminRoleController@create');
Macaw::post('/admin/sys/role/doCreate','VirgoBack\AdminRoleController@doCreate');
Macaw::get('/admin/sys/role/update','VirgoBack\AdminRoleController@update');
Macaw::post('/admin/sys/role/doUpdate','VirgoBack\AdminRoleController@doUpdate');
//Macaw::get('/admin/sys/role/read','VirgoBack\AdminRoleController@read');
Macaw::get('/admin/sys/role/doDelete','VirgoBack\AdminRoleController@doDelete');
Macaw::post('/admin/sys/role/destroy','VirgoBack\AdminRoleController@doDelete');
// 角色管理--end

// 用户角色管理
Macaw::get('/admin/sys/rtus','VirgoBack\AdminRoleToUserController@lists');
Macaw::get('/admin/sys/rtu/create','VirgoBack\AdminRoleToUserController@create');
Macaw::post('/admin/sys/rtu/doCreate','VirgoBack\AdminRoleToUserController@doCreate');
Macaw::get('/admin/sys/rtu/update','VirgoBack\AdminRoleToUserController@update');
Macaw::post('/admin/sys/rtu/doUpdate','VirgoBack\AdminRoleToUserController@doUpdate');
//Macaw::get('/admin/sys/rtu/read','VirgoBack\AdminRoleToUserController@read');
Macaw::get('/admin/sys/rtu/doDelete','VirgoBack\AdminRoleToUserController@doDelete');
Macaw::post('/admin/sys/rtu/destroy','VirgoBack\AdminRoleToUserController@doDelete');
// 用户角色管理--end

// 角色权限管理
Macaw::get('/admin/sys/ptrs','VirgoBack\AdminPrivilegeToRoleController@lists');
Macaw::get('/admin/sys/ptr/create','VirgoBack\AdminPrivilegeToRoleController@create');
Macaw::post('/admin/sys/ptr/doCreate','VirgoBack\AdminPrivilegeToRoleController@doCreate');
Macaw::get('/admin/sys/ptr/update','VirgoBack\AdminPrivilegeToRoleController@update');
Macaw::post('/admin/sys/ptr/doUpdate','VirgoBack\AdminPrivilegeToRoleController@doUpdate');
//Macaw::get('/admin/sys/ptr/read','VirgoBack\AdminPrivilegeToRoleController@read');
Macaw::get('/admin/sys/ptr/doDelete','VirgoBack\AdminPrivilegeToRoleController@doDelete');
Macaw::post('/admin/sys/ptr/destroy','VirgoBack\AdminPrivilegeToRoleController@doDelete');

// 角色权限管理--end

// 操作权限管理
Macaw::get('/admin/sys/operates','VirgoBack\AdminSysOperateController@lists');
Macaw::get('/admin/sys/operate/create','VirgoBack\AdminSysOperateController@create');
Macaw::post('/admin/sys/operate/doCreate','VirgoBack\AdminSysOperateController@doCreate');
Macaw::get('/admin/sys/operate/update','VirgoBack\AdminSysOperateController@update');
Macaw::post('/admin/sys/operate/doUpdate','VirgoBack\AdminSysOperateController@doUpdate');
//Macaw::get('/admin/sys/operate/read','VirgoBack\AdminSysOperateController@read');
Macaw::get('/admin/sys/operate/doDelete','VirgoBack\AdminSysOperateController@doDelete');
Macaw::post('/admin/sys/operate/destroy','VirgoBack\AdminSysOperateController@doDelete');
// 操作权限管理--end

// 后台菜单管理
Macaw::get('/admin/sys/menus','VirgoBack\AdminSysMenuController@lists');
Macaw::get('/admin/sys/menu/create','VirgoBack\AdminSysMenuController@create');
Macaw::post('/admin/sys/menu/doCreate','VirgoBack\AdminSysMenuController@doCreate');
Macaw::get('/admin/sys/menu/update','VirgoBack\AdminSysMenuController@update');
Macaw::post('/admin/sys/menu/doUpdate','VirgoBack\AdminSysMenuController@doUpdate');
//Macaw::get('/admin/sys/menu/read','VirgoBack\AdminSysMenuController@read');
Macaw::get('/admin/sys/menu/doDelete','VirgoBack\AdminSysMenuController@doDelete');
Macaw::post('/admin/sys/menu/destroy','VirgoBack\AdminSysMenuController@doDelete');
Macaw::post('/admin/sys/menu/updateColumn','VirgoBack\AdminSysMenuController@updateColumn');
// 后台菜单管理--end

// 后台菜单分配
Macaw::get('/admin/sys/rtms','VirgoBack\AdminRoleToMenuController@lists');
Macaw::get('/admin/sys/rtm/create','VirgoBack\AdminRoleToMenuController@create');
Macaw::post('/admin/sys/rtm/doCreate','VirgoBack\AdminRoleToMenuController@doCreate');
Macaw::get('/admin/sys/rtm/update','VirgoBack\AdminRoleToMenuController@update');
Macaw::post('/admin/sys/rtm/doUpdate','VirgoBack\AdminRoleToMenuController@doUpdate');
//Macaw::get('/admin/sys/rtm/read','VirgoBack\AdminRoleToMenuController@read');
Macaw::get('/admin/sys/rtm/doDelete','VirgoBack\AdminRoleToMenuController@doDelete');
Macaw::post('/admin/sys/rtm/destroy','VirgoBack\AdminRoleToMenuController@doDelete');
// 后台菜单分配--end

// 敏感词管理
Macaw::get('/admin/sensitiveWords','\VirgoBack\AdminSensitiveWordController@index');
Macaw::get('/admin/sensitiveWord/update','VirgoBack\AdminSensitiveWordController@update');
Macaw::post('/admin/sensitiveWord/doUpdate','VirgoBack\AdminSensitiveWordController@doUpdate');
// 敏感词管理--end

// 角色操作权限管理
Macaw::get('/admin/sys/opms','VirgoBack\AdminOperatePrivilegeToRoleController@lists');
Macaw::get('/admin/sys/opm/create','VirgoBack\AdminOperatePrivilegeToRoleController@create');
Macaw::post('/admin/sys/opm/doCreate','VirgoBack\AdminOperatePrivilegeToRoleController@doCreate');
Macaw::get('/admin/sys/opm/update','VirgoBack\AdminOperatePrivilegeToRoleController@update');
Macaw::post('/admin/sys/opm/doUpdate','VirgoBack\AdminOperatePrivilegeToRoleController@doUpdate');
//Macaw::get('/admin/sys/opm/read','VirgoBack\AdminOperatePrivilegeToRoleController@read');
Macaw::get('/admin/sys/opm/doDelete','VirgoBack\AdminOperatePrivilegeToRoleController@doDelete');
Macaw::post('/admin/sys/opm/destroy','VirgoBack\AdminOperatePrivilegeToRoleController@doDelete');
// 角色操作权限管理--end

// 应用管理
Macaw::get('/admin/apps','VirgoBack\AdminAppController@lists');
Macaw::get('/admin/app/create','VirgoBack\AdminAppController@create');
Macaw::post('/admin/app/parseApk','VirgoBack\AdminAppController@parseApk');
Macaw::post('/admin/app/doUpdate','VirgoBack\AdminAppController@doUpdate');
Macaw::post('/admin/app/doCancel','VirgoBack\AdminAppController@doCancel');
Macaw::get('/admin/app/read','VirgoBack\AdminAppController@read');
Macaw::get('/admin/app/download','VirgoBack\AdminAppController@download');
Macaw::post('/admin/app/deleteOlder','VirgoBack\AdminAppController@deleteOlder');
Macaw::post('/admin/app/doDelete','VirgoBack\AdminAppController@doDelete');
Macaw::post('/admin/app/doUpdateDescription','VirgoBack\AdminAppController@doUpdateDescription');
// 应用管理--end

/*轮播图管理*/
Macaw::get('/admin/carouselImgs','VirgoBack\AdminCarouselImgController@lists');
Macaw::get('/admin/carouselImgs/create','VirgoBack\AdminCarouselImgController@create');
Macaw::post('/admin/carouselImgs/doCreate','VirgoBack\AdminCarouselImgController@doCreate');
Macaw::get('/admin/carouselImgs/update','VirgoBack\AdminCarouselImgController@update');
Macaw::post('/admin/carouselImgs/doUpdate','VirgoBack\AdminCarouselImgController@doUpdate');
Macaw::post('/admin/carouselImgs/read','VirgoBack\AdminCarouselImgController@read');
Macaw::get('/admin/carouselImgs/doDelete','VirgoBack\AdminCarouselImgController@doDelete');
Macaw::post('/admin/carouselImgs/destroy','VirgoBack\AdminCarouselImgController@doDelete');
/*轮播图管理--end*/

/*说说管理列表*/
// Macaw::get('/admin/chatCircles','VirgoBack\AdminChatCircleController@lists');
// Macaw::get('/admin/chatCircles/create','VirgoBack\AdminChatCircleController@create');
// Macaw::post('/admin/chatCircles/doCreate','VirgoBack\AdminChatCircleController@doCreate');
// Macaw::get('/admin/chatCircles/update','VirgoBack\AdminChatCircleController@update');
// Macaw::post('/admin/chatCircles/doUpdate','VirgoBack\AdminChatCircleController@doUpdate');
// Macaw::get('/admin/chatCircles/read','VirgoBack\AdminChatCircleController@read');
// Macaw::get('/admin/chatCircles/doDelete','VirgoBack\AdminChatCircleController@doDelete');
// Macaw::post('/admin/chatCircles/doDelete','VirgoBack\AdminChatCircleController@doDelete');
/*说说管理列表--end*/

/*说说评论管理列表*/
// Macaw::get('/admin/commentChatCircles','VirgoBack\AdminCommentChatCircleController@lists');
// Macaw::get('/admin/commentChatCircles/create','VirgoBack\AdminCommentChatCircleController@create');
// Macaw::post('/admin/commentChatCircles/doCreate','VirgoBack\AdminCommentChatCircleController@doCreate');
// Macaw::get('/admin/commentChatCircles/update','VirgoBack\AdminCommentChatCircleController@update');
// Macaw::post('/admin/commentChatCircles/doUpdate','VirgoBack\AdminCommentChatCircleController@doUpdate');
// Macaw::get('/admin/commentChatCircles/read','VirgoBack\AdminCommentChatCircleController@read');
// Macaw::get('/admin/commentChatCircles/doDelete','VirgoBack\AdminCommentChatCircleController@doDelete');
// Macaw::post('/admin/commentChatCircles/doDelete','VirgoBack\AdminCommentChatCircleController@doDelete');
/*说说评论管理列表--end*/

/*喜欢说说管理*/
// Macaw::get('/admin/likeChatCircles','VirgoBack\AdminLikeChatCircleController@lists');
// Macaw::get('/admin/likeChatCircles/create','VirgoBack\AdminLikeChatCircleController@create');
// Macaw::post('/admin/likeChatCircles/doCreate','VirgoBack\AdminLikeChatCircleController@doCreate');
// Macaw::get('/admin/likeChatCircles/update','VirgoBack\AdminLikeChatCircleController@update');
// Macaw::post('/admin/likeChatCircles/doUpdate','VirgoBack\AdminLikeChatCircleController@doUpdate');
// Macaw::get('/admin/likeChatCircles/read','VirgoBack\AdminLikeChatCircleController@read');
// Macaw::get('/admin/likeChatCircles/doDelete','VirgoBack\AdminLikeChatCircleController@doDelete');
// Macaw::post('/admin/likeChatCircles/doDelete','VirgoBack\AdminLikeChatCircleController@doDelete');
/*喜欢说说管理--end*/

// 该项目的app管理页面
Macaw::get('/admin/manageApps','VirgoBack\AdminManageAppController@lists');
Macaw::get('/admin/manageApps/create','VirgoBack\AdminManageAppController@create');
Macaw::post('/admin/manageApps/doCreate','VirgoBack\AdminManageAppController@doCreate');
Macaw::get('/admin/manageApps/update','VirgoBack\AdminManageAppController@update');
Macaw::post('/admin/manageApps/doUpdate','VirgoBack\AdminManageAppController@doUpdate');
Macaw::get('/admin/manageApps/read','VirgoBack\AdminManageAppController@read');
Macaw::get('/admin/manageApps/doDelete','VirgoBack\AdminManageAppController@doDelete');
Macaw::post('/admin/manageApps/destroy','VirgoBack\AdminManageAppController@doDelete');
Macaw::get('/admin/manageApps/download','VirgoBack\AdminManageAppController@download');
// 该项目的app管理页面--end

/*用户消息管理*/
// Macaw::get('/admin/userMessages','VirgoBack\AdminUserMessageController@lists');
// Macaw::get('/admin/userMessages/create','VirgoBack\AdminUserMessageController@create');
// Macaw::post('/admin/userMessages/doCreate','VirgoBack\AdminUserMessageController@doCreate');
// Macaw::get('/admin/userMessages/update','VirgoBack\AdminUserMessageController@update');
// Macaw::post('/admin/userMessages/doUpdate','VirgoBack\AdminUserMessageController@doUpdate');
// Macaw::get('/admin/userMessages/read','VirgoBack\AdminUserMessageController@read');
// Macaw::get('/admin/userMessages/doDelete','VirgoBack\AdminUserMessageController@doDelete');
// Macaw::post('/admin/userMessages/doDelete','VirgoBack\AdminUserMessageController@doDelete');
/*用户消息管理--end*/

/*站内消息管理*/ 
Macaw::get('/admin/webStationMessages','VirgoBack\AdminWebStationMessageController@lists');
Macaw::get('/admin/webStationMessages/create','VirgoBack\AdminWebStationMessageController@create');
// Macaw::post('/admin/webStationMessages/doCreate','VirgoBack\AdminWebStationMessageController@doCreate');
// Macaw::get('/admin/webStationMessages/update','VirgoBack\AdminWebStationMessageController@update');
// Macaw::post('/admin/webStationMessages/doUpdate','VirgoBack\AdminWebStationMessageController@doUpdate');
Macaw::get('/admin/webStationMessages/read','VirgoBack\AdminWebStationMessageController@read');
// Macaw::get('/admin/webStationMessages/doDelete','VirgoBack\AdminWebStationMessageController@doDelete');
// Macaw::post('/admin/webStationMessages/doDelete','VirgoBack\AdminWebStationMessageController@doDelete');
/*站内消息管理--end*/

/*站内消息待推送管理*/
// Macaw::get('/admin/webStationWaitForPushs','VirgoBack\AdminWebStationWaitForPushController@lists');
// Macaw::get('/admin/webStationWaitForPushs/create','VirgoBack\AdminWebStationWaitForPushController@create');
// Macaw::post('/admin/webStationWaitForPushs/doCreate','VirgoBack\AdminWebStationWaitForPushController@doCreate');
// Macaw::get('/admin/webStationWaitForPushs/update','VirgoBack\AdminWebStationWaitForPushController@update');
// Macaw::post('/admin/webStationWaitForPushs/doUpdate','VirgoBack\AdminWebStationWaitForPushController@doUpdate');
// Macaw::get('/admin/webStationWaitForPushs/read','VirgoBack\AdminWebStationWaitForPushController@read');
// Macaw::get('/admin/webStationWaitForPushs/doDelete','VirgoBack\AdminWebStationWaitForPushController@doDelete');
// Macaw::post('/admin/webStationWaitForPushs/doDelete','VirgoBack\AdminWebStationWaitForPushController@doDelete');
/*站内消息待推送管理--end*/

/*推送结果管理*/
// Macaw::get('/admin/webStationAlreadyPushedResults','VirgoBack\AdminWebStationAlreadyPushedResultController@lists');
// Macaw::get('/admin/webStationAlreadyPushedResults/create','VirgoBack\AdminWebStationAlreadyPushedResultController@create');
// Macaw::post('/admin/webStationAlreadyPushedResults/doCreate','VirgoBack\AdminWebStationAlreadyPushedResultController@doCreate');
// Macaw::get('/admin/webStationAlreadyPushedResults/update','VirgoBack\AdminWebStationAlreadyPushedResultController@update');
// Macaw::post('/admin/webStationAlreadyPushedResults/doUpdate','VirgoBack\AdminWebStationAlreadyPushedResultController@doUpdate');
// Macaw::get('/admin/webStationAlreadyPushedResults/read','VirgoBack\AdminWebStationAlreadyPushedResultController@read');
// Macaw::get('/admin/webStationAlreadyPushedResults/doDelete','VirgoBack\AdminWebStationAlreadyPushedResultController@doDelete');
// Macaw::post('/admin/webStationAlreadyPushedResults/doDelete','VirgoBack\AdminWebStationAlreadyPushedResultController@doDelete');
/*推送结果管理--end*/

/*签到签退管理*/
// Macaw::get('/admin/activitySignInAndOuts','VirgoBack\AdminActivitySignInAndOutController@lists');
// Macaw::get('/admin/activitySignInAndOuts/create','VirgoBack\AdminActivitySignInAndOutController@create');
// Macaw::post('/admin/activitySignInAndOuts/doCreate','VirgoBack\AdminActivitySignInAndOutController@doCreate');
// Macaw::get('/admin/activitySignInAndOuts/update','VirgoBack\AdminActivitySignInAndOutController@update');
// Macaw::post('/admin/activitySignInAndOuts/doUpdate','VirgoBack\AdminActivitySignInAndOutController@doUpdate');
// Macaw::get('/admin/activitySignInAndOuts/read','VirgoBack\AdminActivitySignInAndOutController@read');
// Macaw::get('/admin/activitySignInAndOuts/doDelete','VirgoBack\AdminActivitySignInAndOutController@doDelete');
// Macaw::post('/admin/activitySignInAndOuts/doDelete','VirgoBack\AdminActivitySignInAndOutController@doDelete');
/*签到签退管理--end*/

/*农场管理*/
// Macaw::get('/admin/farms','VirgoBack\AdminFarmController@lists');
// Macaw::get('/admin/farms/create','VirgoBack\AdminFarmController@create');
// Macaw::post('/admin/farms/doCreate','VirgoBack\AdminFarmController@doCreate');
// Macaw::get('/admin/farms/update','VirgoBack\AdminFarmController@update');
// Macaw::post('/admin/farms/doUpdate','VirgoBack\AdminFarmController@doUpdate');
// Macaw::get('/admin/farms/read','VirgoBack\AdminFarmController@read');
// Macaw::get('/admin/farms/doDelete','VirgoBack\AdminFarmController@doDelete');
// Macaw::post('/admin/farms/destroy','VirgoBack\AdminFarmController@doDelete');

/*地块管理*/
// Macaw::get('/admin/acres','VirgoBack\AdminAcreController@lists');
// Macaw::get('/admin/acres/create','VirgoBack\AdminAcreController@create');
// Macaw::post('/admin/acres/doCreate','VirgoBack\AdminAcreController@doCreate');
// Macaw::get('/admin/acres/update','VirgoBack\AdminAcreController@update');
// Macaw::post('/admin/acres/doUpdate','VirgoBack\AdminAcreController@doUpdate');
// Macaw::get('/admin/acres/read','VirgoBack\AdminAcreController@read');
// Macaw::get('/admin/acres/doDelete','VirgoBack\AdminAcreController@doDelete');
// Macaw::post('/admin/acres/destroy','VirgoBack\AdminAcreController@doDelete');

/*片区*/ 
// Macaw::get('/admin/areas','VirgoBack\AdminAreaController@lists');
// Macaw::get('/admin/areas/create','VirgoBack\AdminAreaController@create');
// Macaw::post('/admin/areas/doCreate','VirgoBack\AdminAreaController@doCreate');
// Macaw::get('/admin/areas/update','VirgoBack\AdminAreaController@update');
// Macaw::post('/admin/areas/doUpdate','VirgoBack\AdminAreaController@doUpdate');
// Macaw::get('/admin/areas/read','VirgoBack\AdminAreaController@read');
// Macaw::get('/admin/areas/doDelete','VirgoBack\AdminAreaController@doDelete');
// Macaw::post('/admin/areas/destroy','VirgoBack\AdminAreaController@doDelete');

/*作物种类管理*/
// Macaw::get('/admin/cropTypes','VirgoBack\AdminCropTypeController@lists');
// Macaw::get('/admin/cropTypes/create','VirgoBack\AdminCropTypeController@create');
// Macaw::post('/admin/cropTypes/doCreate','VirgoBack\AdminCropTypeController@doCreate');
// Macaw::get('/admin/cropTypes/update','VirgoBack\AdminCropTypeController@update');
// Macaw::post('/admin/cropTypes/doUpdate','VirgoBack\AdminCropTypeController@doUpdate');
// Macaw::get('/admin/cropTypes/read','VirgoBack\AdminCropTypeController@read');
// Macaw::get('/admin/cropTypes/doDelete','VirgoBack\AdminCropTypeController@doDelete');
// Macaw::post('/admin/cropTypes/destroy','VirgoBack\AdminCropTypeController@doDelete');

/*作物管理*/
// Macaw::get('/admin/crops','VirgoBack\AdminCropController@lists');
// Macaw::get('/admin/crops/create','VirgoBack\AdminCropController@create');
// Macaw::post('/admin/crops/doCreate','VirgoBack\AdminCropController@doCreate');
// Macaw::get('/admin/crops/update','VirgoBack\AdminCropController@update');
// Macaw::post('/admin/crops/doUpdate','VirgoBack\AdminCropController@doUpdate');
// Macaw::get('/admin/crops/read','VirgoBack\AdminCropController@read');
// Macaw::get('/admin/crops/doDelete','VirgoBack\AdminCropController@doDelete');
// Macaw::post('/admin/crops/destroy','VirgoBack\AdminCropController@doDelete');

/*模板分类管理*/
// Macaw::get('/admin/archiveTemplateCategorys','VirgoBack\AdminArchiveTemplateCategoryController@lists');
// Macaw::get('/admin/archiveTemplateCategorys/create','VirgoBack\AdminArchiveTemplateCategoryController@create');
// Macaw::post('/admin/archiveTemplateCategorys/doCreate','VirgoBack\AdminArchiveTemplateCategoryController@doCreate');
// Macaw::get('/admin/archiveTemplateCategorys/update','VirgoBack\AdminArchiveTemplateCategoryController@update');
// Macaw::post('/admin/archiveTemplateCategorys/doUpdate','VirgoBack\AdminArchiveTemplateCategoryController@doUpdate');
// Macaw::get('/admin/archiveTemplateCategorys/read','VirgoBack\AdminArchiveTemplateCategoryController@read');
// Macaw::get('/admin/archiveTemplateCategorys/doDelete','VirgoBack\AdminArchiveTemplateCategoryController@doDelete');
// Macaw::post('/admin/archiveTemplateCategorys/destroy','VirgoBack\AdminArchiveTemplateCategoryController@doDelete');

/*档案模板管理*/
// Macaw::get('/admin/archiveTemplates','VirgoBack\AdminArchiveTemplateController@lists');
// Macaw::get('/admin/archiveTemplates/create','VirgoBack\AdminArchiveTemplateController@create');
// Macaw::post('/admin/archiveTemplates/doCreate','VirgoBack\AdminArchiveTemplateController@doCreate');
// Macaw::get('/admin/archiveTemplates/update','VirgoBack\AdminArchiveTemplateController@update');
// Macaw::post('/admin/archiveTemplates/doUpdate','VirgoBack\AdminArchiveTemplateController@doUpdate');
// Macaw::get('/admin/archiveTemplates/read','VirgoBack\AdminArchiveTemplateController@read');
// Macaw::get('/admin/archiveTemplates/doDelete','VirgoBack\AdminArchiveTemplateController@doDelete');
// Macaw::post('/admin/archiveTemplates/destroy','VirgoBack\AdminArchiveTemplateController@doDelete');

/*作物档案数据管理*/
// Macaw::get('/admin/cropTemplateDatas','VirgoBack\AdminCropTemplateDataController@lists');
// Macaw::get('/admin/cropTemplateDatas/create','VirgoBack\AdminCropTemplateDataController@create');
// Macaw::post('/admin/cropTemplateDatas/doCreate','VirgoBack\AdminCropTemplateDataController@doCreate');
// Macaw::get('/admin/cropTemplateDatas/update','VirgoBack\AdminCropTemplateDataController@update');
// Macaw::post('/admin/cropTemplateDatas/doUpdate','VirgoBack\AdminCropTemplateDataController@doUpdate');
// Macaw::get('/admin/cropTemplateDatas/read','VirgoBack\AdminCropTemplateDataController@read');
// Macaw::get('/admin/cropTemplateDatas/doDelete','VirgoBack\AdminCropTemplateDataController@doDelete');
// Macaw::post('/admin/cropTemplateDatas/destroy','VirgoBack\AdminCropTemplateDataController@doDelete');

/*片区档案数据管理*/
// Macaw::get('/admin/areaTemplateDatas','VirgoBack\AdminAreaTemplateDataController@lists');
// Macaw::get('/admin/areaTemplateDatas/create','VirgoBack\AdminAreaTemplateDataController@create');
// Macaw::post('/admin/areaTemplateDatas/doCreate','VirgoBack\AdminAreaTemplateDataController@doCreate');
// Macaw::get('/admin/areaTemplateDatas/update','VirgoBack\AdminAreaTemplateDataController@update');
// Macaw::post('/admin/areaTemplateDatas/doUpdate','VirgoBack\AdminAreaTemplateDataController@doUpdate');
// Macaw::get('/admin/areaTemplateDatas/read','VirgoBack\AdminAreaTemplateDataController@read');
// Macaw::get('/admin/areaTemplateDatas/doDelete','VirgoBack\AdminAreaTemplateDataController@doDelete');
// Macaw::post('/admin/areaTemplateDatas/destroy','VirgoBack\AdminAreaTemplateDataController@doDelete');

/*模板分类关联模板管理*/
// Macaw::get('/admin/archiveCategoryToArchives','VirgoBack\AdminArchiveCategoryToArchiveController@lists');
// Macaw::get('/admin/archiveCategoryToArchives/create','VirgoBack\AdminArchiveCategoryToArchiveController@create');
// Macaw::post('/admin/archiveCategoryToArchives/doCreate','VirgoBack\AdminArchiveCategoryToArchiveController@doCreate');
// Macaw::get('/admin/archiveCategoryToArchives/update','VirgoBack\AdminArchiveCategoryToArchiveController@update');
// Macaw::post('/admin/archiveCategoryToArchives/doUpdate','VirgoBack\AdminArchiveCategoryToArchiveController@doUpdate');
// Macaw::get('/admin/archiveCategoryToArchives/read','VirgoBack\AdminArchiveCategoryToArchiveController@read');
// Macaw::get('/admin/archiveCategoryToArchives/doDelete','VirgoBack\AdminArchiveCategoryToArchiveController@doDelete');
// Macaw::post('/admin/archiveCategoryToArchives/destroy','VirgoBack\AdminArchiveCategoryToArchiveController@doDelete');

/*模板分类关联主体管理*/
// Macaw::get('/admin/archiveCategoryToMainBodys','VirgoBack\AdminArchiveCategoryToMainBodyController@lists');
// Macaw::get('/admin/archiveCategoryToMainBodys/create','VirgoBack\AdminArchiveCategoryToMainBodyController@create');
// Macaw::post('/admin/archiveCategoryToMainBodys/doCreate','VirgoBack\AdminArchiveCategoryToMainBodyController@doCreate');
// Macaw::get('/admin/archiveCategoryToMainBodys/update','VirgoBack\AdminArchiveCategoryToMainBodyController@update');
// Macaw::post('/admin/archiveCategoryToMainBodys/doUpdate','VirgoBack\AdminArchiveCategoryToMainBodyController@doUpdate');
// Macaw::get('/admin/archiveCategoryToMainBodys/read','VirgoBack\AdminArchiveCategoryToMainBodyController@read');
// Macaw::get('/admin/archiveCategoryToMainBodys/doDelete','VirgoBack\AdminArchiveCategoryToMainBodyController@doDelete');
// Macaw::post('/admin/archiveCategoryToMainBodys/destroy','VirgoBack\AdminArchiveCategoryToMainBodyController@doDelete');

/*片区关联管理人员*/
// Macaw::get('/admin/areaRelManagers','VirgoBack\AdminAreaRelManagerController@lists');
// Macaw::get('/admin/areaRelManagers/create','VirgoBack\AdminAreaRelManagerController@create');
// Macaw::post('/admin/areaRelManagers/doCreate','VirgoBack\AdminAreaRelManagerController@doCreate');
// Macaw::get('/admin/areaRelManagers/update','VirgoBack\AdminAreaRelManagerController@update');
// Macaw::post('/admin/areaRelManagers/doUpdate','VirgoBack\AdminAreaRelManagerController@doUpdate');
// Macaw::get('/admin/areaRelManagers/read','VirgoBack\AdminAreaRelManagerController@read');
// Macaw::get('/admin/areaRelManagers/doDelete','VirgoBack\AdminAreaRelManagerController@doDelete');
// Macaw::post('/admin/areaRelManagers/destroy','VirgoBack\AdminAreaRelManagerController@doDelete');

/*十日报管理*/
// Macaw::get('/admin/tenDayDiarys','VirgoBack\AdminTenDayDiaryController@lists');
// Macaw::get('/admin/tenDayDiarys/create','VirgoBack\AdminTenDayDiaryController@create');
// Macaw::post('/admin/tenDayDiarys/doCreate','VirgoBack\AdminTenDayDiaryController@doCreate');
// Macaw::get('/admin/tenDayDiarys/update','VirgoBack\AdminTenDayDiaryController@update');
// Macaw::post('/admin/tenDayDiarys/doUpdate','VirgoBack\AdminTenDayDiaryController@doUpdate');
// Macaw::get('/admin/tenDayDiarys/read','VirgoBack\AdminTenDayDiaryController@read');
// Macaw::get('/admin/tenDayDiarys/doDelete','VirgoBack\AdminTenDayDiaryController@doDelete');
// Macaw::post('/admin/tenDayDiarys/destroy','VirgoBack\AdminTenDayDiaryController@doDelete');

/*日志待审批表管理*/
// Macaw::get('/admin/diaryExaminations','VirgoBack\AdminDiaryExaminationController@lists');
// Macaw::get('/admin/diaryExaminations/create','VirgoBack\AdminDiaryExaminationController@create');
// Macaw::post('/admin/diaryExaminations/doCreate','VirgoBack\AdminDiaryExaminationController@doCreate');
// Macaw::get('/admin/diaryExaminations/update','VirgoBack\AdminDiaryExaminationController@update');
// Macaw::post('/admin/diaryExaminations/doUpdate','VirgoBack\AdminDiaryExaminationController@doUpdate');
// Macaw::get('/admin/diaryExaminations/read','VirgoBack\AdminDiaryExaminationController@read');
// Macaw::get('/admin/diaryExaminations/doDelete','VirgoBack\AdminDiaryExaminationController@doDelete');
// Macaw::post('/admin/diaryExaminations/destroy','VirgoBack\AdminDiaryExaminationController@doDelete');

/*十日报日志审批内容管理*/
// Macaw::get('/admin/tenDayDiaryComments','VirgoBack\AdminTenDayDiaryCommentController@lists');
// Macaw::get('/admin/tenDayDiaryComments/create','VirgoBack\AdminTenDayDiaryCommentController@create');
// Macaw::post('/admin/tenDayDiaryComments/doCreate','VirgoBack\AdminTenDayDiaryCommentController@doCreate');
// Macaw::get('/admin/tenDayDiaryComments/update','VirgoBack\AdminTenDayDiaryCommentController@update');
// Macaw::post('/admin/tenDayDiaryComments/doUpdate','VirgoBack\AdminTenDayDiaryCommentController@doUpdate');
// Macaw::get('/admin/tenDayDiaryComments/read','VirgoBack\AdminTenDayDiaryCommentController@read');
// Macaw::get('/admin/tenDayDiaryComments/doDelete','VirgoBack\AdminTenDayDiaryCommentController@doDelete');
// Macaw::post('/admin/tenDayDiaryComments/destroy','VirgoBack\AdminTenDayDiaryCommentController@doDelete');

/*月报--日志管理*/
// Macaw::get('/admin/monthlyDiarys','VirgoBack\AdminMonthlyDiaryController@lists');
// Macaw::get('/admin/monthlyDiarys/create','VirgoBack\AdminMonthlyDiaryController@create');
// Macaw::post('/admin/monthlyDiarys/doCreate','VirgoBack\AdminMonthlyDiaryController@doCreate');
// Macaw::get('/admin/monthlyDiarys/update','VirgoBack\AdminMonthlyDiaryController@update');
// Macaw::post('/admin/monthlyDiarys/doUpdate','VirgoBack\AdminMonthlyDiaryController@doUpdate');
// Macaw::get('/admin/monthlyDiarys/read','VirgoBack\AdminMonthlyDiaryController@read');
// Macaw::get('/admin/monthlyDiarys/doDelete','VirgoBack\AdminMonthlyDiaryController@doDelete');
// Macaw::post('/admin/monthlyDiarys/destroy','VirgoBack\AdminMonthlyDiaryController@doDelete');

/*阅读月报管理*/
// Macaw::get('/admin/diaryReads','VirgoBack\AdminDiaryReadController@lists');
// Macaw::get('/admin/diaryReads/create','VirgoBack\AdminDiaryReadController@create');
// Macaw::post('/admin/diaryReads/doCreate','VirgoBack\AdminDiaryReadController@doCreate');
// Macaw::get('/admin/diaryReads/update','VirgoBack\AdminDiaryReadController@update');
// Macaw::post('/admin/diaryReads/doUpdate','VirgoBack\AdminDiaryReadController@doUpdate');
// Macaw::get('/admin/diaryReads/read','VirgoBack\AdminDiaryReadController@read');
// Macaw::get('/admin/diaryReads/doDelete','VirgoBack\AdminDiaryReadController@doDelete');
// Macaw::post('/admin/diaryReads/destroy','VirgoBack\AdminDiaryReadController@doDelete');


Macaw::get('/admin/instructionsMessages','VirgoBack\AdminInstructionsMessageController@lists');
Macaw::get('/admin/instructionsMessages/create','VirgoBack\AdminInstructionsMessageController@create');
Macaw::post('/admin/instructionsMessages/doCreate','VirgoBack\AdminInstructionsMessageController@doCreate');
Macaw::get('/admin/instructionsMessages/update','VirgoBack\AdminInstructionsMessageController@update');
Macaw::post('/admin/instructionsMessages/doUpdate','VirgoBack\AdminInstructionsMessageController@doUpdate');
Macaw::get('/admin/instructionsMessages/read','VirgoBack\AdminInstructionsMessageController@read');
Macaw::get('/admin/instructionsMessages/doDelete','VirgoBack\AdminInstructionsMessageController@doDelete');
Macaw::post('/admin/instructionsMessages/destroy','VirgoBack\AdminInstructionsMessageController@doDelete');

/*国家管理*/
// Macaw::get('/admin/countries','VirgoBack\AdminCountriesController@lists');
// Macaw::get('/admin/countries/create','VirgoBack\AdminCountriesController@create');
// Macaw::post('/admin/countries/doCreate','VirgoBack\AdminCountriesController@doCreate');
// Macaw::get('/admin/countries/update','VirgoBack\AdminCountriesController@update');
// Macaw::post('/admin/countries/doUpdate','VirgoBack\AdminCountriesController@doUpdate');
// Macaw::get('/admin/countries/read','VirgoBack\AdminCountriesController@read');
// Macaw::get('/admin/countries/doDelete','VirgoBack\AdminCountriesController@doDelete');
// Macaw::post('/admin/countries/destroy','VirgoBack\AdminCountriesController@doDelete');

/*模板管理*/
// Macaw::get('/admin/productTemplateManagements','VirgoBack\AdminProductTemplateManagementController@lists');
// Macaw::get('/admin/productTemplateManagements/create','VirgoBack\AdminProductTemplateManagementController@create');
// Macaw::post('/admin/productTemplateManagements/doCreate','VirgoBack\AdminProductTemplateManagementController@doCreate');
// Macaw::get('/admin/productTemplateManagements/update','VirgoBack\AdminProductTemplateManagementController@update');
// Macaw::post('/admin/productTemplateManagements/doUpdate','VirgoBack\AdminProductTemplateManagementController@doUpdate');
// Macaw::get('/admin/productTemplateManagements/read','VirgoBack\AdminProductTemplateManagementController@read');
// Macaw::get('/admin/productTemplateManagements/doDelete','VirgoBack\AdminProductTemplateManagementController@doDelete');
// Macaw::post('/admin/productTemplateManagements/destroy','VirgoBack\AdminProductTemplateManagementController@doDelete');

/*货币管理*/
// Macaw::get('/admin/currencyManagements','VirgoBack\AdminCurrencyManagementController@lists');
// Macaw::get('/admin/currencyManagements/create','VirgoBack\AdminCurrencyManagementController@create');
// Macaw::post('/admin/currencyManagements/doCreate','VirgoBack\AdminCurrencyManagementController@doCreate');
// Macaw::get('/admin/currencyManagements/update','VirgoBack\AdminCurrencyManagementController@update');
// Macaw::post('/admin/currencyManagements/doUpdate','VirgoBack\AdminCurrencyManagementController@doUpdate');
// Macaw::get('/admin/currencyManagements/read','VirgoBack\AdminCurrencyManagementController@read');
// Macaw::get('/admin/currencyManagements/doDelete','VirgoBack\AdminCurrencyManagementController@doDelete');
// Macaw::post('/admin/currencyManagements/destroy','VirgoBack\AdminCurrencyManagementController@doDelete');

/*商品订单*/
// Macaw::get('/admin/productOrders','VirgoBack\AdminProductOrderController@lists');
// Macaw::get('/admin/productOrders/create','VirgoBack\AdminProductOrderController@create');
// Macaw::post('/admin/productOrders/doCreate','VirgoBack\AdminProductOrderController@doCreate');
// Macaw::get('/admin/productOrders/update','VirgoBack\AdminProductOrderController@update');
// Macaw::post('/admin/productOrders/doUpdate','VirgoBack\AdminProductOrderController@doUpdate');
// Macaw::get('/admin/productOrders/read','VirgoBack\AdminProductOrderController@read');
// Macaw::get('/admin/productOrders/doDelete','VirgoBack\AdminProductOrderController@doDelete');
// Macaw::post('/admin/productOrders/destroy','VirgoBack\AdminProductOrderController@doDelete');

/*商品订单信息*/
// Macaw::get('/admin/productOrderInfos','VirgoBack\AdminProductOrderInfoController@lists');
// Macaw::get('/admin/productOrderInfos/create','VirgoBack\AdminProductOrderInfoController@create');
// Macaw::post('/admin/productOrderInfos/doCreate','VirgoBack\AdminProductOrderInfoController@doCreate');
// Macaw::get('/admin/productOrderInfos/update','VirgoBack\AdminProductOrderInfoController@update');
// Macaw::post('/admin/productOrderInfos/doUpdate','VirgoBack\AdminProductOrderInfoController@doUpdate');
// Macaw::get('/admin/productOrderInfos/read','VirgoBack\AdminProductOrderInfoController@read');
// Macaw::get('/admin/productOrderInfos/doDelete','VirgoBack\AdminProductOrderInfoController@doDelete');
// Macaw::post('/admin/productOrderInfos/destroy','VirgoBack\AdminProductOrderInfoController@doDelete');
