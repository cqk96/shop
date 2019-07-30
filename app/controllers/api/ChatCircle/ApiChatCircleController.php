<?php
namespace VirgoApi\ChatCircle;
use Illuminate\Database\Capsule\Manager as DB;
class ApiChatCircleController extends \VirgoApi\ApiBaseController
{

	/**
	* api参数数组
	* @var array
	*/
	private $_configs;

	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
		$this->_configs = parent::change();
	}

	/**
	* 获取朋友圈列表
	* @author 	xww
	* @return 	json
	*/
	public function lists()
	{
		
		try{

			//获取用户
			$user = $this->getUserApi($this->_configs);	

			// 必要验证 id
			$this->configValid('required',$this->_configs,['page', 'size']);

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			// 实例化对象
			$chatCircleModelObj = new \VirgoModel\ChatCircleModel;

			$data = $chatCircleModelObj->getChatCircleLists($skip, $size, $user[0]['id']);
			$data = empty($data)? null:$data;

			$dataCount = $chatCircleModelObj->getChatCircleListsCount();
			$totalPage = is_null($size)? 1:ceil( $dataCount / $size );
			$totalPage = is_null($data)? 0:$totalPage;

			$return = $this->functionObj->toAppJson(['data'=>$data, 'totalPage'=>$totalPage], '001', '获取说说列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* 发表说说
	* @author 	xww
	* @return 	json
	*/
	public function create()
	{
		
		try{

			//获取用户
			$user = $this->getUserApi($this->_configs);	

			// 必要验证 id
			$this->configValid('required',$this->_configs,['content']);

			$data['user_id'] = $user[0]['id'];
			$data['content'] = $this->_configs['content'];
			$data['imgs'] = empty($this->_configs['imgs'])? null:$this->_configs['imgs'];
			$data['like_count'] = 0;
			$data['is_deleted'] = 0;
			$data['create_time'] = time();
			$data['update_time'] = time();

			// 实例化对象
			$chatCircleModelObj = new \VirgoModel\ChatCircleModel;
			$rs = $chatCircleModelObj->create($data);
			if( !$rs ) {
				throw new \Exception("发表说说失败", "005");
			}

			$return = $this->functionObj->toAppJson(null, '001', '发表说说成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}
	}

	/**
	* 喜欢或不喜欢一个说说
	* @author 	xww
	* @return 	json
	*/
	public function likeOrNot()
	{
		
		try {

			//获取用户
			$user = $this->getUserApi($this->_configs);	

			// 必要验证 id
			$this->configValid('required',$this->_configs,['id']);

			DB::beginTransaction();

			// 实例化对象
			$likeChatCircleModelObj = new \VirgoModel\LikeChatCircleModel;

			$recordData = \EloquentModel\ChatCircle::lockForUpdate()->find( $this->_configs['id'] );

			// 0喜欢    1不喜欢
			$statusId = empty($this->_configs['statusId'])? 0:1; 

			// 是否有对应数据
			$likeRercord = $likeChatCircleModelObj->getLikedRecord($this->_configs['id'], $user[0]['id']);

			$rs1 = true;
			$rs2 = true;
			if( $statusId==0 ) {

				if( empty($likeRercord) ) {
					// 新建记录    喜欢数+1
					$insertData['chat_id'] = $this->_configs['id'];
					$insertData['user_id'] = $user[0]['id'];
					$insertData['is_deleted'] = 0;
					$insertData['create_time'] = time();
					$insertData['update_time'] = time();

					$rs1 = $likeChatCircleModelObj->create($insertData);

					$rs2 = \EloquentModel\ChatCircle::where("id", $this->_configs['id'])->increment("like_count", 1);

				} else {

					if( $likeRercord['is_deleted']==1 ) {

						// 更新不删除  喜欢数+1
						$updateData['is_deleted'] = 0;
						$updateData['update_time'] = time();

						$rs1 = $likeChatCircleModelObj->partUpdate($likeRercord['id'], $updateData);

						$rs2 = \EloquentModel\ChatCircle::where("id", $this->_configs['id'])->increment("like_count", 1);

					}

				}


			} else {

				if( !empty($likeRercord) ) { 
					// 更新删除  喜欢数-1	

					// 更新不删除  喜欢数+1
					$updateData['is_deleted'] = 1;
					$updateData['update_time'] = time();

					$rs1 = $likeChatCircleModelObj->partUpdate($likeRercord['id'], $updateData);

					$rs2 = \EloquentModel\ChatCircle::where("id", $this->_configs['id'])->decrement("like_count", 1);
				}

			}

			$message = $statusId==0? '喜欢':"不喜欢";

			if( $rs1 && $rs2 ) {
				DB::commit();
				$message .= "成功";
			} else {
				throw new \Exception($message."失败", '092');
			}

			
			$return = $this->functionObj->toAppJson(null, '001', $message, true);

		} catch(\Exception $e) {
			DB::rollback();
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* 评论说说
	* @author 	xww
	* @return 	json
	*/
	public function comment()
	{
		
		try {

			//获取用户
			$user = $this->getUserApi($this->_configs);	

			// 必要验证 id
			$this->configValid('required', $this->_configs,['id', 'content']);

			// 实例化对象
			$chatCircleCommentModelObj = new \VirgoModel\CommentChatCircleModel;

			$toId = empty($this->_configs['toId']) || empty( (int)$this->_configs['toId'] ) ? 0:(int)$this->_configs['toId'];

			$data['chat_id'] = $this->_configs['id'];
			$data['user_id'] = $user[0]['id'];
			$data['content'] = $this->_configs['content'];
			$data['to_id'] = $toId;
			$data['is_deleted'] = 0;
			$data['created_time'] = time();
			$data['updated_time'] = time();

			$rs = $chatCircleCommentModelObj->create($data);

			$message = $toId==0? '发表评论':'回复评论';

			if( !$rs ) {
				throw new \Exception($message . "失败", '005');
			}

			$return = $this->functionObj->toAppJson(null, '001', $message . "成功", true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* 删除十万个为什么
	* @author 	xww
	* @return 	json
	*/
	public function delete()
	{

		try{

			if(empty($_COOKIE['user_id'])) {
				
				//获取用户
				$user = $this->getUserApi($this->_configs);	

			} else {
				$userObj = new \VirgoModel\UserModel;
				//获取用户
				$id = $_COOKIE['user_id'];
				$record = $userObj->readSingleTon($id);
				if(empty($record)) {
					throw new \Exception("用户不存在", '006');
				}
				$user[] = $record->toArray();
			}

			$this->configValid('required',$this->_configs,['id']);

			$id = $this->_configs['id'];
			$uid = $user[0]['id'];

			// 获取实例化对象
			$modelObj = new \VirgoModel\ChatCircleModel;

			// 验证数据有效性
			$hasRecord = $modelObj->getUserRecord($uid, $id);

			if( empty($hasRecord) ) {
				throw new \Exception("无法查询到数据", '006');
			}

			$data['is_deleted'] = 1;
			$data['update_time'] = time();

			$rs = $modelObj->partUpdate($id, $data);

			if( !$rs ) {
				throw new \Exception("删除失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '删除成功', true);

		}catch(\Exception $e){
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

}