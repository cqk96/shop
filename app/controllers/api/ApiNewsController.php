<?php
namespace VirgoApi;
class ApiNewsController extends ApiBaseController
{
	
	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
		$this->newsObj 	   = new \EloquentModel\News;
		$this->newsClassesObj 	   = new \EloquentModel\NewsClasses;
	}

	public function lists()
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

		if(empty($has_item)){
			$return = null;
			$code = "006";
        	$message = '没有符合条件数据';
        	//输出
			echo $this->functionObj->toAppJson($return, $code, $message, true);
			return false;
		}

		$term_id = $has_item[0]['id'];

		//如果有下属子类也应该找到子类文章
		$has_sub_id = $this->getChildrenClassesArr($term_id);

		$newsQuery = $this->newsObj->where("pass", '=', 1)
					          ->where("status",'=', 0)
					          ->whereIn("class_id", $has_sub_id)
					          ->orderBy("top","desc");

		//分页
		if(!empty($_POST['page']) && !empty($_POST['size'])){
			$page = $_POST['page']<=1? 1:$_POST['page'];
			$page = $page-1;
			
			$take = $_POST['size'];
			$skip = $page*$take;
			$newsQuery = $newsQuery->take($take)
								   ->skip($skip);
		}

		$news = $newsQuery->get()
						  ->toArray();
        
        $return  = null;
        if(!empty($news)){
        	$return = array();
        	$code = "001";
        	$message = '获取成功';

        	$needs = array(
        		'title',
        		'cover',
        		'hits',
        		'id'
        		);

        	if(!empty($_POST['extra'])){
				$extraColumn = explode(',', $_POST['extra']);
				foreach ($extraColumn as $extraColumn_key => $extraColumn_val) {
					array_push($needs, $extraColumn_val);
				}
			}

        	foreach ($news as $key => $value) {

        		$value['cover'] = empty($value['cover'])? '':"http://".$_SERVER['HTTP_HOST'].$value['cover'];

        		//评论数
				$commentCount = \EloquentModel\CommentNews::where("news_id", '=', $value['id'])
										  ->where("is_deleted", '=', 0)
										  ->count();

				$value['comment_count'] = $commentCount;

				$temp = $this->functionObj->getNeedDataArray($value,$needs);
				array_push($return, $temp);

        	}

        } else {
        	$code = "006";
        	$message = '没有符合条件数据';
        }

        //输出
		echo $this->functionObj->toAppJson($return, $code, $message, true);

	}

	/**
	* 显示文章详情
	*/

	public function read()
	{
		
		ob_clean();
		$validateRs = $this->functionObj->validateApiParams('required',$_POST,['id']);
		if(!$validateRs['success']){
			$messages = implode(',', $validateRs['message']);
			echo $this->functionObj->toAppJson(null, '014', $messages, false);
			return false;
		}

		$news = $this->newsObj->where("pass", '=', 1)
							  ->where("status",'=', 0)
							  ->find($_POST['id']);
		
		if(!empty($news)){
			$news = $news->toArray();

			$news['cover'] = empty($news['cover'])? '':"http://".$_SERVER['HTTP_HOST'].$news['cover'];
			
			$needs = array(
					'id',
					'title',
					'content',
					'updated_at'
					);

			if(!empty($_POST['extra'])){
				$extraColumn = explode(',', $_POST['extra']);
				foreach ($extraColumn as $extraColumn_key => $extraColumn_val) {
					array_push($needs, $extraColumn_val);
				}
			}

			//文章详情替换图片
			preg_match_all('/<img.*?src="(.*?)".*?>/is',$news['content'],$matches);
			preg_match_all('/<img(.*?)\/>/is',$news['content'],$imgs);

			if(!empty($matches[1])){
				foreach ($matches[1] as $matches_key => $matches_val) {
					$matches_val = "http://".$_SERVER['HTTP_HOST'].$matches_val;
					$style_val = str_replace("src=\"".$matches_val."\"", '', $imgs[1][$matches_key]);
					$news['content'] = preg_replace('/<img.*?src="(.*?)".*?>/',"<img src='".$matches_val."' ".$style_val."/>", $news['content'],1);
				}
			}

			//默认是否收藏
			$isCollect = false;
			$news['is_collected'] = $isCollect;

			//用户行为
			if(!empty($_POST['user_login']) && !empty($_POST['access_token'])){
				$user = \EloquentModel\User::where("user_login", '=', $_POST['user_login'])
								   ->where("access_token", '=', $_POST['access_token'])
								   ->where("is_deleted", '=', 0)
								   ->get()
								   ->toArray();

				if(!empty($user)){
					$this->judgeUser($user[0]['access_token'], $_POST['user_login'], 60*60*24*10);
					//是否收藏过
					$isCollect = \EloquentModel\CollectNews::where("news_id", '=', $_POST['id'])
														   ->where("user_id", '=', $user[0]['id'])
														   ->count();
				}
				$news['is_collected'] = empty($isCollect)? false:true;
			}

			$return = $this->functionObj->getNeedDataArray($news,$needs);
			$message = '获取成功';
			$success = true;
			$code = '001';

			//阅读数+1
			$this->newsObj->where('id','=',$_POST['id'])->increment('hits', $amount = 1);

		} else {
			$return = null;
			$message = '没有符合条件数据';
			$success = false;
			$code = '006';
		}

		//输出
		echo $this->functionObj->toAppJson($return, $code, $message, $success);

	}

	/**
	* 获取家属类
	*/
	public function getChildrenClassesArr($term_id)
	{
		
		$hasArr = \EloquentModel\NewsClasses::where("pclass_id", '=', $term_id)
								  ->where("status", '=', 0)
								  ->get()
								  ->toArray();
		
		$rs = [$term_id];								  
		if(!empty($hasArr)){
			foreach ($hasArr as $key => $value) {
				array_push($rs, $value['id']);
			}
		}

		return $rs;

	}

	/**
	* 收藏文章
	*/
	public function collect()
	{
		
		ob_clean();
		$validateRs = $this->functionObj->validateApiParams('required',$_POST,['id','access_token','user_login']);
		if(!$validateRs['success']){
			$messages = implode(',', $validateRs['message']);
			echo $this->functionObj->toAppJson(null, '014', $messages, false);
			return false;
		}

		$this->judgeUser($_POST['access_token'], $_POST['user_login'], 60*60*24*10);

		$user = \EloquentModel\User::where("user_login", '=', $_POST['user_login'])
								   ->where("access_token", '=', $_POST['access_token'])
								   ->where("is_deleted", '=', 0)
								   ->get()
								   ->toArray();

		$has_collected = \EloquentModel\CollectNews::where("news_id", '=', $_POST['id'])
								  ->where("user_id", '=', $user[0]['id'])
								  ->get()
								  ->toArray();

		if(!empty($has_collected)){
			$rs = \EloquentModel\CollectNews::where("id",'=',$has_collected[0]['id'])->delete();
			if($rs){
				$message = "取消收藏成功";
				$code = '001';
				$success = true;
				$return = false;
			} else {
				$message = "取消收藏失败";
				$code = '012';
				$success = false;
				$return = false;
			}
		} else {
			
			$data['news_id'] = $_POST['id'];
			$data['user_id'] = $user[0]['id'];
			$data['created_time'] = time();
			$rs = \EloquentModel\CollectNews::insert($data);
			if($rs){
				$message = "添加收藏成功";
				$code = '001';
				$success = true;
				$return = true;
			} else {
				$message = "添加收藏失败";
				$code = '025';
				$success = false;
				$return = true;
			}

		}

		//输出
		echo $this->functionObj->toAppJson($return, $code, $message, $success);

	}

}