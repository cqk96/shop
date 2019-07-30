<?php
namespace VirgoBack;

class AdminApiController extends AdminBaseController{
	protected $apiObj = '';

	public function __construct()
	{
		$this->apiObj = new \VirgoModel\ApiModel;
		$this->projectObj = new \VirgoModel\ProjectModel;
		$this->pageObj = new \VirgoUtil\Page;
		parent::isLogin();
	}
	
	public function lists()
	{
		
		$page_title = '接口管理';
		$page_sub_title = '接口管理';

		$data = $this->apiObj->lists();
		//分页实现
		$pageObj = $this->pageObj->page('\\EloquentModel\\Api','/admin/apis',5);
		
		//var_dump($pageObj);

		//分页数据
		$data = $pageObj->data;
		
		//起始组装
		$page = $pageObj->current_page;
		$per_count = $pageObj->per_record;
		$record_start = ($page-1)*$per_count;
		//起始组装--end
		$status = ['否','是'];
		require dirname(__FILE__).'/../../views/admin/adminApi/lists.php';

	}

	public function create()
	{

		$page_sub_title = '添加接口';
		$projects = $this->projectObj->lists();
		$params_arr = [];

		require dirname(__FILE__).'/../../views/admin/adminApi/create.php';

	}

	public function doCreate()
	{
		
		$rs = $this->apiObj->create();
		if($rs)
			$this->showPage(['添加接口成功'],'/admin/apis');
		else 
			$this->showPage(['添加接口失败'],'/admin/apis');

	}

	public function update()
	{
		$page_title  = '修改接口';
		$api = $this->apiObj->read();
		$params_arr = array();

		//解析api参数
		if($api['params']!=''){
			$json_arr = json_decode($api['params'], true);
			
			foreach ($json_arr as $key => $value) {
				foreach ($value as $child_key => $child_value) {
					$temp['type'] = $child_key;
					foreach ($child_value as $child_sub_key => $child_sub_value) {
						$temp['key'] = $child_sub_key;
						$temp['value'] = $child_sub_value;
					}	
				}
				
				array_push($params_arr, $temp);

			}

		}

		$projects = $this->projectObj->lists();

		require dirname(__FILE__).'/../../views/admin/adminApi/update.php';
	}

	public function doUpdate()
	{
		$rs = $this->apiObj->update();
		if($rs)
			$this->showPage(['修改接口成功'],'/admin/apis');
		else 
			$this->showPage(['修改接口失败'],'/admin/apis');
	}

	public function doDelete()
	{

		$rs = $this->apiObj->delete();
		
		if($_POST){
			if($rs)
				echo json_encode(['success'=>true,'message'=>'delete success']);
			else 
				echo json_encode(['success'=>false,'message'=>'delete failture']);
		} else {
			if($rs)
				$this->showPage(['删除接口成功'],'/admin/apis');
			else 
				$this->showPage(['删除接口失败'],'/admin/apis');
		}	

	}

	public function run()
	{
		
		$rs = $this->apiObj->run();
		$temp = json_decode($rs,true);
		if(!$temp)
			echo json_encode(['success'=>false, 'content'=>$rs]);
		else
			echo json_encode(['success'=>false, 'content'=>$rs],JSON_UNESCAPED_UNICODE);

	}

	public function test()
	{
		
		ob_clean();
		echo json_encode(['success'=>true, 'content'=>'中文'], JSON_UNESCAPED_UNICODE);

	}

}