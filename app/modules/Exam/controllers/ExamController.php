<?php
namespace Module\Exam\Controller;
use VirgoFront;
class ExamController extends VirgoFront\BaseController {

	/**
	* 开始考试
	* @author 	xww
	* @return 	void
	*/ 
	public function examStart()
	{
		
		try {

			if( empty($_GET['id']) || empty($_GET['user_login']) || empty( $_GET['access_token'] ) ) {
				throw new \Exception("Wrong Param");
			}

			// 当前考题
			$questionId = empty( $_GET['questionId'] ) || (int)$_GET['questionId']<1? 1:(int)$_GET['questionId'];

			if( empty($_COOKIE['examId']) || empty($_COOKIE['examUserId']) ) {

				$rs1 = true;
				$rs2 = true;
				$rs3 = true;
				$rs4 = true;

				if( !empty($_COOKIE['examId']) ) {
					$rs1 = setcookie('examId', '', time()-1, "/");
				}

				if( !empty($_COOKIE['examUserId']) ) {
					$rs2 = setcookie('examUserId', '', time()-1, "/");
				}

				if( !empty($_COOKIE['curQuestionId']) ) {
					$rs3 = setcookie('curQuestionId', '', time()-1, "/");
				}

				if( !empty($_COOKIE['answerSerialize']) ) {
					$rs4 = setcookie('answerSerialize', '', time()-1, "/");
				}
				
				if( !$rs1 || !$rs2 || !$rs3 || !$rs4 ) {
					throw new \Exception("清除cookie失败", '084');
				}

				// 获取用户
				$user = \EloquentModel\User::where("is_deleted", 0)->where("user_login", $_GET['user_login'])->where("access_token", $_GET['access_token'])->first();

				if( empty($user) ) {
					throw new \Exception("用户不存在", '006');
				}

				if( is_null( $user['token_expire_time'] ) || $user['token_expire_time']<time() ) {
					throw new \Exception("请重新登录", '007');
				}

				$rs1 = setcookie('examId', $_GET['id'], time()+3600, "/");
				$rs2 = setcookie('examUserId', $user['id'], time()+3600, "/");
				

				if( !$rs1 || !$rs2) {
					throw new \Exception("设置cookie失败", '067');
				}

				// 为了保障cookie可取到 重新定位
				$url = "/front/v1/exam/start?id=" . $_GET['id'] . "&user_login=" . $_GET['user_login'] . "&access_token=" . $_GET['access_token'] . "&questionId=" . $questionId;
				header("Location: " . $url);
				exit();

			}

			// 如果当前考试名与cookie中不相符
			// 清空并强制跳转
			if( $_GET['id'] != $_COOKIE['examId'] ) {
				$rs1 = setcookie('examId', '', time()-1, "/");
				$rs2 = setcookie('examUserId', '', time()-1, "/");
				$rs3 = setcookie('curQuestionId', '', time()-1, "/");
				$rs4 = setcookie('answerSerialize', '', time()-1, "/");

				if( !$rs1 || !$rs2 || !$rs3 || !$rs4 ) {
					throw new \Exception("清除cookie失败", '084');
				}

				// 为了保障cookie可取到 重新定位
				$url = "/front/v1/exam/start?id=" . $_GET['id'] . "&user_login=" . $_GET['user_login'] . "&access_token=" . $_GET['access_token'] . "&questionId=" . $questionId;
				header("Location: " . $url);
				exit();

			}

			// 获取所有该考试的题目
			$examModelObj = new \Module\Exam\VirgoModel\ExamModel;

			// index 从0开始
			$questions = $examModelObj->getExamQuetionsOptions($_COOKIE['examId']);

			// 题目数量
			$questionsCount = count($questions['data']);

			// var_dump($questions);
			// die;
			// var_dump($questions['data'][0][0]);

			if( empty($questions) ) {
				throw new \Exception("考试不存在", '006');
			}

			// 判断当前题目是否存在  索引从0开始
			if( !isset($questions['data'][$questionId-1]) ) {
				throw new \Exception("题目不存在", '006');
			}

			// 存入当前题目cookie
			// setcookie('curQuestionId', $questionId, time()+3600, "/");

			// 当前数据
			// $data = $questions['data'][$questionId-1];

			// var_dump($data);

			// 获取上题 下题
			$prevQuestionId = null;
			$nextQuestionId = null;

			// 题目列表
			$questionLists = [];
			if( !empty($_COOKIE['answerSerialize']) ) {
				$answerArr = unserialize($_COOKIE['answerSerialize']);
				foreach ($answerArr as $key => $value) {
					$answerArr[$key] = explode(",", $value);
				}
			}

			// var_dump($questions['data'][4]['options']);
			// var_dump(!empty($answerArr[4+1]));
			// die;

			// @todo    下一题
			for ($i=0; $i < count($questions['data']); $i++) { 

				$questionLists[] = ($i+1);

				// 下一题
				if( is_null( $nextQuestionId ) && ( ($i+1) > $questionId ) ) {
					$nextQuestionId = $i+1;
				}

			}

			// 上一题 
			for ($i=count($questions['data'])-1; $i >=0 ; $i--) { 

				if( is_null( $prevQuestionId ) && ($i-1) < $questionId) {
					$prevQuestionId = $i-1;
				}

			}

			$questionCount = count($questionLists);

			$hrefBaseUrl = "/front/v1/exam/start?id=" . $_GET['id'] . "&user_login=" . $_GET['user_login'] . "&access_token=" . $_GET['access_token'] . "&questionId=";

			// var_dump($data);
			// var_dump($prevQuestionId);
			// var_dump($nextQuestionId);
			// var_dump($questionLists);
			

			require dirname(__FILE__)."/../views/exam/exam.php";

		} catch(\Exception $e) {
			echo $this->showHtmlNotice( $e->getMessage() );
		}

	}

	/**
	* 考试结果
	* render the page
	* @author　	xww
	* @return 	void
	*/
	public function examResult()
	{
		
		try{

			if( empty($_GET['rightCount']) && empty($_GET['errorCount']) && empty($_GET['score']) ) {
				throw new \Exception("Wrong Param");
			}

			$rightCount = empty($_GET['rightCount']) || (int)$_GET['rightCount']<0? 0:(int)$_GET['rightCount'];
			$errorCount = empty($_GET['errorCount']) || (int)$_GET['errorCount']<0? 0:(int)$_GET['errorCount'];
			$score = empty($_GET['score']) || (int)$_GET['score']<0? 0:(int)$_GET['score'];

			require dirname(__FILE__)."/../views/exam/exam-result.php";

		} catch(\Exception $e) {
			echo $this->showHtmlNotice( $e->getMessage() );
		}

	}

}