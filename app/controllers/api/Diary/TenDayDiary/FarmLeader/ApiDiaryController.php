<?php
namespace VirgoApi\Diary\TenDayDiary\FarmLeader;
use Illuminate\Database\Capsule\Manager as DB;
class ApiDiaryController extends \VirgoApi\ApiBaseController{

	/**
	* api参数数组
	* @var array
	*/
	private $_configs;

	public function __construct()
	{
		$this->_configs = parent::change();
		$this->functionObj = new \VirgoUtil\Functions;
	}

	/**
	* @SWG\Post(path="/api/v1/diary/tenDayDiary/farmLeader/save", tags={"TenDayDiary"}, 
	*  summary="场长审批",
	*  description="用户鉴定后 通过传入的指定参数进行场长审批 修改行为也为同一个接口 参数不同",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="diaryId", type="integer", required=true, in="formData", description="日志id--添加时传入"),
	*  @SWG\Parameter(name="evaluation", type="string", required=true, in="formData", description="评语--添加/修改时传入"),
	*  @SWG\Parameter(name="commentId", type="integer", required=false, in="formData", description="评论id--修改时传入"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "recordId", "status": { "code": "001", "message": "成功", "success": true } } }
	*  )
	* )
	* 场长审批
	* @author 	xww
	* @return 	json
	*/
	public function save()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 对象
			$model = new \VirgoModel\TenDayDiaryCommentModel;

			// 对象
			$diaryModel = new \VirgoModel\TenDayDiaryModel;

			// 日志审批对象
			$approverModel = new \VirgoModel\DiaryExaminationModel;

			// 用户角色对象
			$roleToUserModel = new \VirgoModel\RoleToUserModel;

			if( empty($this->_configs['commentId']) ) {
				
				//验证
				$this->configValid('required', $this->_configs, ['diaryId']);

				$id = $this->_configs['diaryId'];

			} else {
				$commentRecord = $model->read( $this->_configs['commentId'] );

				if( empty($commentRecord) ) {
					throw new \Exception("评论数据不存在", '006');
				}

				$id = $commentRecord['diary_id'];
			}

			/*获取高管评价*/
			$record = $model->getDiaryCommentContentWithTypeId($id, 2);

			if( !empty($record) ) {
				throw new \Exception("已经在审核不允许修改", '096');
			}

			/*获取场长评价*/
			$record = $model->getDiaryCommentContentWithTypeId($id, 1);

			if( !empty($record) ) {

				$returnRecordId = null;

				/*可能由于竞争*/
				if( !empty($this->_configs['diaryId']) ) {
					$diaryId = $this->_configs['diaryId'];

					// 如果不是该用户评论的话 提醒已经被审批
					$curApprovers = \EloquentModel\DiaryExamination::where("type_id", 1)
												->where("item_id", $diaryId)
												->where("approver_type_id", 1108)
												->where("status_id", 2)
												->get()
												->toArray();

					$ok = true;
					for ($i=0; $i < count($curApprovers); $i++) { 
						if( $curApprovers[$i]['approver_id']!=$uid ){
							$ok = false;
						}
					}

					if( !$ok ) {
						throw new \Exception("已被审批", '086');
					}

				}

				// 更新

				//验证
				$this->configValid('required',$this->_configs,['commentId', 'evaluation']);

				DB::beginTransaction();

				$isBlock = true;

				$commentId = $this->_configs['commentId'];
				$evaluation = $this->_configs['evaluation'];

				$updateData['evaluation'] = $evaluation;
				$updateData['update_time'] = time();

				$rs = $model->partUpdate($commentId, $updateData);

				if( !$rs ) {
					throw new \Exception("更新评论失败", '003');		
				}

				$message = "更新评论";

			} else {
				// 创建+转交

				//验证
				$this->configValid('required',$this->_configs,['diaryId', 'evaluation']);

				$diaryId = $this->_configs['diaryId'];
				$evaluation = $this->_configs['evaluation'];

				// 判断日志是否已经被审批
				$dataLines = \EloquentModel\DiaryExamination::where("type_id", 1)
												->where("item_id", $diaryId)
												->where("status_id", 0)
												->where("approver_type_id", 1108)
												->get()
												->toArray();

				if( empty($dataLines) ) {
					throw new \Exception("日志已被审批", '006');
				}

				$diaryData = $diaryModel->read($diaryId);
				$ownerId = $diaryData['user_id'];

				if( empty($diaryData) ) {
					throw new \Exception("日志数据不存在", '006');
				}

				// 验证是否是由你做审核员
				$rs = $approverModel->uAreApprover(1, $diaryId, $uid);

				if( !$rs ) {
					throw new \Exception("非该条日志审核员", '096');		
				}

				DB::beginTransaction();

				$isBlock = true;

				/*锁行--待更新*/
				$dataLines = \EloquentModel\DiaryExamination::where("type_id", 1)
												->where("item_id", $diaryId)
												->where("status_id", 0)
												->where("approver_type_id", 1108)
												->lockForUpdate()
												->get()
												->toArray();

				// 插入评语
				$commentInsertData['diary_id'] = $diaryId;
				$commentInsertData['comment_type_id'] = 1;
				$commentInsertData['approver'] = $uid;
				$commentInsertData['evaluation'] = $evaluation;
				$commentInsertData['is_deleted'] = 0;
				$commentInsertData['create_time'] = time();
				$commentInsertData['update_time'] = time();

				$recordId = $model->create( $commentInsertData );
				unset($commentInsertData);
				if( !$recordId ) {
					throw new \Exception("创建场长审批失败", '005');
				}

				$returnRecordId = $recordId;

				// 更新审批状态
				$approverData['status_id'] = 2;
				$approverData['update_time'] = time();
				$rs = \EloquentModel\DiaryExamination::where("type_id", 1)
												->where("item_id", $diaryId)
												->where("status_id", 0)
												->where("approver_type_id", 1108)
												->update( $approverData );

				if( !$rs ) {
					throw new \Exception("更新审批状态失败", '003');
				}

				// 查询高管角色--不存在则回滚
				$roleIds = [1109];
				$approvers = $roleToUserModel->getRoleUsers( $roleIds );

				if( empty($approvers) ) {
					throw new \Exception("不存在具有公司高管角色的人员，日志回退", '006');
				}

				$userIds = [];
				for ($i=0; $i < count($approvers); $i++) { 
					$userIds[] = $approvers[$i]['user_id'];
				}

				// 进行日志审批
				$rs = $approverModel->createWorks($ownerId, 1, $diaryId, $userIds, 1109);

				if( !$rs ) {
					throw new \Exception("十日报提交审批失败", '005');
				}

				// 多审批删除其他非该角色审批
				if( count($dataLines)>1 ) {
					$rs = \EloquentModel\DiaryExamination::where("type_id", 1)
												->where("item_id", $diaryId)
												->where("approver_type_id", 1108)
												->where("approver_id", "<>", $uid)
												->delete();

					if( !$rs ) {
						throw new \Exception("多审批数据删除失败", '012');
					}

				}

				$message = "场长评论";

			}

			DB::commit();

			$return = $this->functionObj->toAppJson($returnRecordId, '001', $message . '成功', true);

		} catch(\Exception $e) {

			if( isset($isBlock) ) {
				DB::rollback();
			}

			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}