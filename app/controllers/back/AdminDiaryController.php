<?php
/**
* 控制器
* @author xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoBack;
class AdminDiaryController extends AdminBaseController{
	 /*
	 * @param  object  reflect this controller's  virgo model object
	 */
	 private $model;

	 public function __construct()
	 {
		parent::isLogin();
	 }

	// 获取列表
	public function lists()
	{
		
		try {

			$userModel = new \VirgoModel\UserModel;

			$uid = $_COOKIE['user_id'];

			// 获取用户对象
			$user = $userModel->readSingleTon($uid);

			if(empty($user)) {
				throw new \Exception("Wrong Request");
			}

			$user = $user->toArray();

			if( empty($user['record_working_time']) ) {

				// 获取用户第一次填报的日记
				$dayDiaryModelObj = new \VirgoModel\DayDiaryModel;
				$firstDiary = $dayDiaryModelObj->getUserFirstDiary($uid);

				// 根据第一篇日记  决定当前环节

				if(!empty($firstDiary)) {
					$years[] = (int)$firstDiary['year'];
					$years[] = (int)$firstDiary['year']+1;
					$years[] = (int)$firstDiary['year']+2;
				} else {
					$curYear = intval(date("Y"));
					$years[] = $curYear;
					$years[] = $curYear+1;
					$years[] = $curYear+2;
				}

				$startYear = $years[0];

				// 可选阶段

				$processArr = [ 1=>'启航', 2=>'领航', 3=>'远航' ];

			} else {

				// 有登记工作时间

				// 固定阶段
				$recordWorkingTime = $user['record_working_time'];

				$theFirstYear = strtotime("+1 year", strtotime($recordWorkingTime . " 23:59:59" ) );

				$theSecondYear = strtotime("+2 year", strtotime($recordWorkingTime . " 23:59:59" ) );

				$theThirdYear = strtotime("+3 year", strtotime($recordWorkingTime . " 23:59:59" ) );

				if( time() <= $theFirstYear ) {
					$processArr[1] = '启航';
				} else if( time() <= $theSecondYear ) {
					$processArr[2] = '领航';
				} else {
					$processArr[3] = '远航';
				}

				$firstYear = (int)substr($recordWorkingTime, 0, 4);			

				$years[] = (int)$firstYear;
				$years[] = (int)$firstYear+1;
				$years[] = (int)$firstYear+2;
				
				$startYear = $years[0];

			}

			// 显示日记填报页面
			require_once dirname(__FILE__).'/../../views/admin/adminDiary/index.php';

		} catch(\Exception $e) {
			echo "<h1>" . $e->getMessage() . "</h1>";
		}

	}

}