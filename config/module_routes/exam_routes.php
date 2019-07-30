<?php
use NoahBuscher\Macaw\Macaw;

/*考试管理*/
Macaw::get('/admin/exams','Module\Exam\Controller\AdminExamController@lists');
Macaw::post('/admin/exam/batchUpload','Module\Exam\Controller\AdminExamController@batchUpload');
Macaw::get('/admin/exams/update','Module\Exam\Controller\AdminExamController@update');
Macaw::post('/admin/exams/doUpdate','Module\Exam\Controller\AdminExamController@doUpdate');

// 后台显示考试列表 (用户可以点击进行考试)
Macaw::get('/admin/exams/testLists','Module\Exam\Controller\AdminExamController@testLists');

// 后台开始考试
Macaw::get('/admin/exams/testList/start','Module\Exam\Controller\AdminExamController@startTesting');

/*考试管理end*/

/*考试回答管理*/ 
Macaw::get('/admin/answerExams','Module\Exam\Controller\AdminAnswerExamController@lists');
Macaw::get('/admin/answerExams/info','Module\Exam\Controller\AdminAnswerExamController@info');
/*考试回答管理--end*/ 

// 回答考题答案
Macaw::post('/api/v1/user/exam/answer','Module\Exam\VirgoApi\User\Exam\Answer\ApiAnswerController@answer');

// 后台回答考题答案
Macaw::post('/back/api/v1/user/exam/answer','Module\Exam\VirgoApi\User\Exam\Answer\ApiAnswerController@backAnswer');

// 移除多选考题答案
Macaw::post('/api/v1/user/exam/removeAnswer','Module\Exam\VirgoApi\User\Exam\Answer\ApiAnswerController@removeAnswer');

// 后台回答移除多选考题答案
Macaw::post('/back/api/v1/user/exam/removeAnswer','Module\Exam\VirgoApi\User\Exam\Answer\ApiAnswerController@backRemoveAnswer');

// 判断是否已完全答完
Macaw::get('/api/v1/user/exam/isAnswerAll','Module\Exam\VirgoApi\User\Exam\Answer\ApiAnswerController@isAnswerAll');

// 后台回答判断是否已完全答完
Macaw::get('/back/api/v1/user/exam/isAnswerAll','Module\Exam\VirgoApi\User\Exam\Answer\ApiAnswerController@backIsAnswerAll');

// 结束一个考试
Macaw::get('/api/v1/user/exam/done','Module\Exam\VirgoApi\User\Exam\Answer\ApiAnswerController@done');

// 后台直接回答--结束一个考试
Macaw::get('/back/api/v1/user/exam/done','Module\Exam\VirgoApi\User\Exam\Answer\ApiAnswerController@backDone');

// 获取考试题目列表    根据分类传回    带有状态是否做过
Macaw::get('/api/v1/user/exam/info','Module\Exam\VirgoApi\User\Exam\Answer\ApiAnswerController@info');

// 获取考试中心考题
Macaw::get('/api/v1/exam/lists','Module\Exam\VirgoApi\Exam\ApiExamController@lists');

// 改变考题的状态（启用和关闭）
Macaw::post('/api/v1/exam/changeStatus','Module\Exam\VirgoApi\Exam\ApiExamController@changeStatus');

// 创建空白考试
Macaw::post('/api/v1/exam/create','Module\Exam\VirgoApi\Exam\ApiExamController@create');

// 考试
Macaw::get('/front/v1/exam/start','Module\Exam\Controller\ExamController@examStart');

// 考试结果
Macaw::get('/front/v1/exam/result','Module\Exam\Controller\ExamController@examResult');