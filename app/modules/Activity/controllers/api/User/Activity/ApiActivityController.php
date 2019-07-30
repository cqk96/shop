<?php
/**
* php version 5.5.12
* @author  xww<5648*****@qq.com>
* @copyright  xww  20161214
* @version 1.0.0
*/
namespace Module\Activity\Controller\User\Activity;
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
	* 活动扫码签到
	* @author 	xww
	* @return 	json
	*/
	public function scanQrcodeSignIn()
	{
		
		try{

			if( empty($_GET['user_login']) || empty($_GET['access_token']) || empty($_GET['id']) ) {
				throw new \Exception("请登录");
			}

			//获取用户 
			$user = \EloquentModel\User::where("user_login", $_GET['user_login'])
									   ->where("access_token", $_GET['access_token'])
									   ->take(1)
									   ->get()
									   ->toArray();

			if( empty($user) ) {

				$hasUser = \EloquentModel\User::where("user_login", $_GET['user_login'])->count();

				if( !empty($hasUser) ) {
					throw new \Exception("令牌失效, 请重新登录");
				} else {
					throw new \Exception("用户不存在");
				}
					
			} else {

				if( $user[0]['token_expire_time']<time() ){
					throw new \Exception("登陆过期, 请重新登录");
				}

			}

			//验证
			$this->configValid('required',$this->_configs,['id']);

			$aid = $this->_configs['id'];

			// 实例化对象

			// 活动
			$activityModelObj = new \Module\Activity\VirgoModel\ActivityModel;

			// 活动签到
			$activitySignInAndOutModelObj = new \Module\Activity\VirgoModel\ActivitySignInAndOutModel;			

			// 判断活动是否存在
			$activity = $activityModelObj->read($aid);

			if( empty($activity) ) {
				throw new \Exception("活动不存在");
			}

			// 判断是否活动在进行中
			if( $activity['start_time'] > time() ) {
				throw new \Exception("活动未开始");
			}

			if( $activity['end_time'] < time() ) {
				throw new \Exception("活动已结束");	
			}

			// 判断签到是否在进行中
			if( $activity['sign_in_start_time'] > time() ) {
				throw new \Exception("签到未开始");
			}

			if( $activity['sign_in_end_time'] < time() ) {
				throw new \Exception("签到已结束");	
			}

			// 是否已经签到过了
			$hasRecord = $activitySignInAndOutModelObj->getUserActivitySignInRecord($user[0]['id'], $aid);

			if( !empty($hasRecord) ) {
				throw new \Exception("已签到");	
			}

			$data['type_id'] = 1;
			$data['activity_id'] = $aid;
			$data['user_id'] = $user[0]['id'];
			$data['is_deleted'] = 0;
			$data['create_time'] = time();
			$data['update_time'] = time();

			$rs = $activitySignInAndOutModelObj->create( $data );
			if( !$rs ) {
				throw new \Exception("签到失败");		
			}

			echo $this->showHtmlNotice( '签到成功' );

		}catch(\Exception $e){

			echo $this->showHtmlNotice( $e->getMessage() );

		}

	}

}