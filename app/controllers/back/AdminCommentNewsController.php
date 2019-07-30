<?php
namespace VirgoBack;
class AdminCommentNewsController extends AdminBaseController
{
	
	public function __construct()
	{
		$this->pageObj = new \VirgoUtil\Page;
		$this->commentNewsObj = new \VirgoModel\CommentNewsModel;
		$this->functionObj = new \VirgoUtil\Functions;
		parent::isLogin();
	}

	public function index()
	{
		$page_title = '评论管理';

		$temp = \EloquentModel\User::where('is_deleted', '=',0)
											->get()
											->toArray();

		foreach ($temp as $key => $value) {
			$user[$value['id']] = $value;
		}

		$temp2 = \EloquentModel\News::where('status', '=',0)
											->get()
											->toArray();

		foreach ($temp2 as $temp2_key => $temp2_value) {
			$news[$temp2_value['id']] = $temp2_value;
		}

		//分页实现

		$this->pageObj->setWhereAnd('=',['is_deleted', 0]);

		$pageObj = $this->pageObj->page('\\EloquentModel\\CommentNews','/admin/comments/news',10);
		
		

		//分页数据
		$data = $pageObj->data;

		//由于组件没有更新  处理其他额外操作
		foreach ($data as $data_key => $data_value) {
			$data[$data_key]['nickname'] = empty($user[$data_value['user_id']])? '惨遭删除':$user[$data_value['user_id']]['nickname'];
			if($data_value['comment_id']==0){
				$data[$data_key]['comment_obj'] = empty($news[$data_value['news_id']])? '惨遭删除':'新闻--'.$news[$data_value['news_id']]['title'];
			} else {
				//查找对应条
				$commentItem = \EloquentModel\CommentNews::find($data_value['comment_id']);
				$data[$data_key]['comment_obj'] = empty($user[$commentItem['user_id']])? '惨遭删除':'用户--'.$user[$commentItem['user_id']]['nickname'];
			}

		}
		
		//起始组装
		$page = $pageObj->current_page;
		$per_count = $pageObj->per_record;
		$record_start = ($page-1)*$per_count;
		//起始组装--end
		require_once dirname(__FILE__).'/../../views/admin/AdminCommentNews/index.php';

	}

	public function doDelete()
	{
		
		$rs = $this->commentNewsObj->doDelete();
		if($_POST){
			if($rs){
				echo json_encode(['success'=>true,'message'=>'delete success', 'code'=>'001']);
				//推送
				$app_key = '29dec5f933618755f9a80a1b';
				$master_secret = '190736f23e276da58137c163';
				$client = new \JPush\Client($app_key, $master_secret);
				//id + 时间戳+ 内容20 + reason
				$return['type'] = 4;
				$return['push_time'] = time();
				$return['reason'] = $_POST['reason'];
				foreach ($_POST['ids'] as $id_key => $id_val) {
					$comment = \EloquentModel\CommentNews::find($id_val);
					$return['content'] = strlen($comment['content'])>60? mb_substr($comment['content'], 0, 20):$comment['content'];
					$toObj = \EloquentModel\User::find($comment['user_id']);
					try {
					
						$pushData['return'] = $return;
						$push_payload = $client->push()
									    ->setPlatform('all')
									    ->addAlias([$toObj['user_login']])
									    ->message('您有一条评论被删除', [
										  'title' => '您有一条评论被删除',
										  'content_type' => 'text',
										  'extras' => $pushData
										])
									    ->send();
					}catch (\JPush\Exceptions\APIConnectionException $e) {
					    // try something here
					    error_log("Connention_Problem:".$e, 3, $_SERVER['DOCUMENT_ROOT']."/jpushLog/".time().".txt");
					} catch (\JPush\Exceptions\APIRequestException $e) {
					    // try something here
					    error_log("Request_Problem:".$e, 3, $_SERVER['DOCUMENT_ROOT']."/jpushLog/".time().".txt");
					}

				}
			} else {
				echo json_encode(['success'=>false,'message'=>'delete failture','code'=>'012']);
			}
		} else {
			if($rs){
				header('Refresh: 5;url=/admin/comments/news');
				echo "删除成功";
			} else {
				header('Refresh: 5;url=/admin/comments/news');
				echo "删除失败";
			}
		}
	}

	/*其他函数*/

	//token
	public function getToken()
	{
		$functionObj = new Functions;

		$ok = true;
		$access_token = '';
		while($ok){
			$tokenStr = $functionObj->tokenStr();
			$token_is_used = User::where('access_token','=',$tokenStr)->get();
			if(count($token_is_used)==0){
				$access_token = $tokenStr;
				$ok = false;
			}
		}

		return $access_token;

	}

	//nickname
	public function getNickName()
	{
		$functionObj = new Functions;
	
		$ok = true;
		$nickname = '';
		while($ok){
			$nicknameStr = $functionObj->getNickName();
			$nickname_is_used = User::where('nickname','=',$nicknameStr)->get();
			if(count($nickname_is_used)==0){
				$nickname = $nicknameStr;
				$ok = false;
			}
		}

		return $nickname;

	}

}
