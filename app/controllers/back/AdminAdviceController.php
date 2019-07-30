<?php
namespace VirgoBack;

class AdminAdviceController extends AdminBaseController{

	public function __construct()
	{
		
		$this->projectObj = new \VirgoModel\ProjectModel;
		$this->pageObj = new \VirgoUtil\Page;
		parent::isLogin();
		
	}

	public function users()
	{

		$page_title = '用户建议管理';

		// //分页实现
		// $pageObj = $this->pageObj->page('\\EloquentModel\\UserAdvice','/admin/advice/users',10);

		// //分页数据
		// $data = $pageObj->data;

		// //获取用户
		// $usersTemp = \EloquentModel\User::select("id",'user_login', 'nickname')->get()->toArray();
		// $users = [];
		// foreach ($usersTemp as $ut_key => $ut_val) {
		// 	$users[$ut_val['id']] = $ut_val;
		// }

		// //组装data
		// foreach ($data as $key => $value) {
		// 	$data[$key]['show_name'] = empty($users[$value['user_id']]['nickname'])? $users[$value['user_id']]['user_login']:$users[$value['user_id']]['nickname'];
		// }
		
		// //起始组装
		// $page = $pageObj->current_page;
		// $per_count = $pageObj->per_record;
		// $record_start = ($page-1)*$per_count;
		// //起始组装--end
		$userAdviceObj = new \EloquentModel\UserAdvice;
		$userAdviceObj = $userAdviceObj->orderBy("create_time", 'desc');
		//父菜单总记录数
		$totalCount = count(\EloquentModel\UserAdvice::all());
		//分页
		$size = 10;
		if(!empty($_GET['page'])){
			$page = (int)$_GET['page'];
			$skip = ($page-1)*$size;
			$userAdviceObj = $userAdviceObj->skip($skip)->take($size);
		} else {
			$skip = 0;
			$userAdviceObj = $userAdviceObj->skip($skip)->take($size);
		}

		$userAdviceObj = $userAdviceObj->leftJoin("users", 'users.id', '=', 'user_advices.user_id')
				   ->select("user_advices.*", "users.user_login","users.nickname");

		$userAdvices = $userAdviceObj->get()->toArray();
		if(empty($userAdvices)){
			$data = [];
		} else {
			$data = $userAdvices;
		}
		$pageObj = new \VirgoUtil\Page2;
		$pageObj->setUrl('/admin/advice/users');
		$pageObj->setData($data);
		$pageObj->setTotalCount($totalCount);
		$pageObj->setSize($size);
		$dataObj = $pageObj->doPage();
		require dirname(__FILE__).'/../../views/admin/adminAdvice/users.php';

	}

}