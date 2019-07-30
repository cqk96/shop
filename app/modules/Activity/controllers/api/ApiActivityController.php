<?php
/**
* php version 5.5.12
* @author  xww<5648*****@qq.com>
* @copyright  xww  20161214
* @version 1.0.0
*/
namespace Module\Activity\Controller;
use Illuminate\Database\Capsule\Manager as DB;
use VirgoApi;
class ApiActivityController extends VirgoApi\ApiBaseController
{

	/**
	* 自定义函数对象
	* @var object
	*/
	private $_functionObj;

	/**
	* api参数数组
	* @var array
	*/
	public $_configs;

	public function __construct()
	{
		$this->_configs = parent::change();
		$this->_model = new \Module\Activity\VirgoModel\ActivityModel;
		$this->_functionObj = new \VirgoUtil\Functions;
		$this->activityEObj = new \Module\Activity\EloquentModel\Activity;
		parent::__construct();
	}

	/**
	* 分页获取活动列表
	*/ 
	public function lists()
	{
		
		try {

			//获取用户
			$user = $this->getUserApi($this->_configs);	

			//验证
			$this->configValid('required',$this->_configs,['page', 'size']);

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			// 获取实例化对象
			$applyObj = new \Module\Activity\VirgoModel\ApplyActivityModel;

			// 获取活动列表
			$data = $this->_model->getActivityList($skip, $size);

			// 获取用户参加过的活动
			$joinArr = $applyObj->getUserApplyActiviyies($user[0]['id']);

			$newJoinArr = [];
			for ($i=0; $i < count($joinArr); $i++) { 
				$newJoinArr[ $joinArr[$i]['active_id'] ] = $joinArr[$i];
			}


			for ($i=0; $i < count($data); $i++) { 

				$data[$i]['cover'] = empty($data[$i]['cover'])? '/images/empty-activity-cover.png':$data[$i]['cover'];
				$data[$i]['activityLevel'] = $data[$i]['activity_level'];
				$data[$i]['totalPeopleCount'] = $data[$i]['total_people_count'];
				$data[$i]['applyPeopleCount'] = $data[$i]['apply_people_count'];
				$data[$i]['url'] = "/front/activity/show?id=".$data[$i]['id'];

				$data[$i]['activityStatus'] = 0;

				if( !empty( $newJoinArr[ $data[$i]['id'] ] ) ) {
					
					// 已参加
					$data[$i]['activityStatus'] = 1;

				} else if( time() < $data[$i]['start_time'] ) {

					// 未开始
					$data[$i]['activityStatus'] = 2;

				} else if( time() > $data[$i]['end_time'] ) {

					// 已结束
					$data[$i]['activityStatus'] = 3;

				}

				unset($data[$i]['activity_level']);
				unset($data[$i]['total_people_count']);
				unset($data[$i]['apply_people_count']);

				unset($data[$i]['start_time']);
				unset($data[$i]['end_time']);

			}

			$data = empty($data)? null:$data;
			
			$dataCount = $this->_model->getActivityListCount();
			$totalPage = is_null($size)? 1:ceil( $dataCount / $size );
			$totalPage = is_null($data)? 0:$totalPage;

			$return = $this->functionObj->toAppJson(['data'=>$data, 'totalPage'=>$totalPage], '001', 'ok', true);

		}catch(\Exception $e){
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0, STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}
	
	}

	/**
	* 报名
	* @author xww
	* @return json string/object
	*/ 
	public function apply()
	{
		
		try{

			//获取用户
			$user = $this->getUserApi($this->_configs);	

			$isBlock = true;

			//验证
			$this->configValid('required',$this->_configs,['id']);

			DB::beginTransaction();
			$data = $this->activityEObj->where("id", $this->_configs['id'])->lockForUpdate()->first();			

			// 活动隐藏
			if( empty($data) ){
				throw new \Exception("活动不存在", '051');
			}

			if( $data['is_deleted']==1 || $data['is_hidden']==1 ) {
				throw new \Exception("活动已经取消", '051');
			}

			// 实例化对象
			$applyObj = new \Module\Activity\VirgoModel\ApplyActivityModel;

			// 判断是否已经报名
			$hasJoined = $applyObj->userIsApply($this->_configs['id'], $user[0]['id']);

			if( $hasJoined ) {
				throw new \Exception("已报名", '053');
			}

			// 先判断活动状态
			if( $data['start_time']>time() ) {
				throw new \Exception("活动尚未开始", '052');	
			}

			if( $data['end_time']<time() ) {
				throw new \Exception("活动已结束", '088');	
			}

			// 活动报名是否开始
			if( $data['apply_start_time']>time()){ 
				throw new \Exception("活动报名尚未开始", '089');
			}

			if( time()>$data['apply_end_time'] ) {
				throw new \Exception("活动报名已结束", '052');
			}

			if( ( $data['total_people_count'] - $data['apply_people_count'] ) <= 0 ) {
				throw new \Exception("报名人数已满", '091');
			}

			$insertData['active_id'] = $this->_configs['id'];
			$insertData['user_id'] = $user[0]['id'];
			$insertData['is_deleted'] = 0;
			$insertData['create_time'] = time();
			$insertData['update_time'] = time();

			$rs = $applyObj->create($insertData);
			if( !$rs ) {
				throw new \Exception("报名失败", '005');
			}

			// 修改已报名人数
			$this->activityEObj->where("id", $this->_configs['id'])->increment("apply_people_count", 1);

			DB::commit();
			
			$return = $this->functionObj->toAppJson(null, '001', '报名成功', true);

			//输出
			$this->responseResult($return);

		}catch(\Exception $e){
			
			if( isset($isBlock) ) {
				DB::rollback();
			}

			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0, STR_PAD_LEFT), $e->getMessage(), false);
			
		} finally {

			//输出
			$this->responseResult($return);

		}

	}

}