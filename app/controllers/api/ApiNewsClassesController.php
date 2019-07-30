<?php
namespace VirgoApi;
class ApiNewsClassesController extends ApiBaseController
{
	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
		$this->newsClassesObj 	   = new \EloquentModel\NewsClasses;
	}

	public function read()
	{
		ob_clean();
		
		$validateRs = $this->functionObj->validateApiParams('required',$_POST,['term_name']);
		if(!$validateRs['success']){
			$messages = implode(',', $validateRs['message']);
			echo $this->functionObj->toAppJson(null, '014', $messages, false);
			return false;
		}

		$has_item = $this->newsClassesObj->where('status', '=', 0)
							 ->where("class_name", '=', $_POST['term_name'])
							 ->get()
							 ->toArray();
		if(!empty($has_item)){
			$code = "001";
        	$message = '获取成功';

        	$has_item[0]['cover'] = empty($has_item[0]['cover'])? '':"http://".$_SERVER['HTTP_HOST'].$has_item[0]['cover'];

        	$needs = array(
        		'id'
        		);

        	if(!empty($_POST['extra'])){
				$extraColumn = explode(',', $_POST['extra']);
				foreach ($extraColumn as $extraColumn_key => $extraColumn_val) {
					array_push($needs, $extraColumn_val);
				}
			}

			$return = $this->functionObj->getNeedDataArray($has_item[0],$needs);


		} else {
			$return = null;
			$code = "006";
        	$message = '没有符合条件数据';
		}

		//输出
		echo $this->functionObj->toAppJson($return, $code, $message, true);

	}

	/**
	* 获取理赔申请所需材料分类
	*/
	public function getMaterials()
	{
		ob_clean();
		$term_name = '理赔申请所需材料';
		$has_item = $this->newsClassesObj
						->where('status', '=', 0)
						->where("class_name", '=', $term_name)
						->get()
						->toArray();

		if(empty($has_item)){
			$return = null;
			$code = '006';
			$message = '没有此分类';
		} else {
			$pid = $has_item[0]['id'];
			$has_items = $this->newsClassesObj
						->where('status', '=', 0)
						->where("pclass_id", '=', $pid)
						->get()
						->toArray();
			if(!empty($has_items)){
				$return = array();
				$code = '001';
				$message = '获取成功';
				$needs = array(
				'id',
        		'class_name'
        		);

				foreach ($has_items as $key => $value) {
					$temp = $this->functionObj->getNeedDataArray($value,$needs);
					array_push($return, $temp);
				}
			} else {
				$return = null;
				$code = '006';
				$message = '没有次级分类';
			}

		}

		//输出
		echo $this->functionObj->toAppJson($return, $code, $message, true);

	}


	/**
	* 获取热门资讯
	*/
	public function getHotMessage()
	{
		ob_clean();
		$term_name = '热门资讯';
		$has_item = $this->newsClassesObj
						->where('status', '=', 0)
						->where("class_name", '=', $term_name)
						->get()
						->toArray();

		if(empty($has_item)){
			$return = null;
			$code = '006';
			$message = '没有此分类';
		} else {
			$pid = $has_item[0]['id'];
			$has_items = $this->newsClassesObj
						->where('status', '=', 0)
						->where("pclass_id", '=', $pid)
						->get()
						->toArray();
			if(!empty($has_items)){
				$return = array();
				$code = '001';
				$message = '获取成功';
				$needs = array(
				'id',
        		'class_name'
        		);

				foreach ($has_items as $key => $value) {
					$temp = $this->functionObj->getNeedDataArray($value,$needs);
					array_push($return, $temp);
				}
			} else {
				$return = null;
				$code = '006';
				$message = '没有次级分类';
			}

		}

		//输出
		echo $this->functionObj->toAppJson($return, $code, $message, true);
	}

	/**
	* 根据分类id 获取文章列表 用于后台
	*/
	public function backLists()
	{
		
		$classId = $_GET['term_id'];
		$news = \EloquentModel\News::where("status", '=', 0)
							->where("pass", '=', 1)
							->where("class_id", '=', $classId)
							->get(['id','title'])
							->toArray();
		if(empty($news)){
			$code = '006';
			$message = '没有符合条件数据';
		} else {
			$code = '001';
			$message = "获取成功";
		}

		echo json_encode(['code'=>$code, 'message'=>$message, 'data'=>$news]);
		
	}

}