<?php
namespace Module\Exam\VirgoApi\User\Exam\Answer;
use Illuminate\Database\Capsule\Manager as DB;
use VirgoApi;
class ApiAnswerController extends VirgoApi\ApiBaseController
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
	* 回答题目
	* @author 	xww
	* @return 	json
	*/
	public function answer()
	{
		
		try {

			$this->configValid('required',$this->_configs,['questionId', 'val']);

			if( empty($_COOKIE['examId']) ) {
				throw new \Exception("请重新进行考试", '006');
			}

			$examModelObj = new \Module\Exam\VirgoModel\ExamModel;

			$examId = $_COOKIE['examId'];
			$questionId = $this->_configs['questionId'];
			$val = $this->_configs['val'];

			$options = $examModelObj->getExamQuetionOptions($examId , $questionId);

			if( empty($options) ) {
				throw new \Exception("获取题目失败", '006');	
			}

			$questionIndex = $options[0]['question_index'];
			$questionType = $options[0]['question_type'];

			$answerIndex = null;
			for ($i=0; $i < count($options); $i++) { 
				if( $options[$i]['option_index'] == $val ) {
					$answerIndex = $options[$i]['option_index'];
					break;
				}
			}

			if( is_null($answerIndex) ) {
				throw new \Exception("未知回答", '006');
			}

			// 判断是否存在回答
			if( !empty($_COOKIE['answerSerialize']) ){
				// 解析
				$answers = unserialize($_COOKIE['answerSerialize']);

				if( empty($answers[$questionIndex]) ) {
					// 存储回答
					$answers[$questionIndex] = $val;
					$answerSerialize = serialize($answers);

					$rs1 = setcookie("answerSerialize", $answerSerialize, time()+3600, "/");
					if( !$rs1 ) {
						throw new \Exception("设置cookie失败", '067');
					}

				} else {

					if( $questionType == 1 || $questionType == 2 ) {
						// 单选/判断
						$answers[$questionIndex] = $val;
					} else if($questionType == 3) {
						// 多选
						$curValArr = explode(",", $answers[$questionIndex]);

						$hasVal = false;
						for ($i=0; $i < count($curValArr); $i++) { 
							if( $curValArr[$i] == $val ) {
								$hasVal = true;
							}
						}

						if(!$hasVal) {
							array_push($curValArr, $val);
						}

						$answers[$questionIndex] = implode(",", $curValArr);
					}

					$answerSerialize = serialize($answers);
					$rs1 = setcookie("answerSerialize", $answerSerialize, time()+3600, "/");
					if( !$rs1 ) {
						throw new \Exception("设置cookie失败", '067');
					}

				}

			} else {
				// 存储回答
				$temp[$questionIndex] = $val;
				$answerSerialize = serialize($temp);

				$rs1 = setcookie("answerSerialize", $answerSerialize, time()+3600, "/");
				if( !$rs1 ) {
					throw new \Exception("设置cookie失败", '067');
				}

			}

			$return = $this->functionObj->toAppJson(null, '001', '保存答案成功', true);
 
		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* 移除答案
	* @author 	xww
	* @return 	json
	*/
	public function removeAnswer()
	{
		
		try{

			$this->configValid('required',$this->_configs,['questionId', 'val']);

			if( empty($_COOKIE['examId']) ) {
				throw new \Exception("请重新进行考试", '006');
			}

			$examModelObj = new \Module\Exam\VirgoModel\ExamModel;

			$examId = $_COOKIE['examId'];
			$questionId = $this->_configs['questionId'];
			$val = $this->_configs['val'];

			$options = $examModelObj->getExamQuetionOptions($examId , $questionId);

			if( empty($options) ) {
				throw new \Exception("获取题目失败", '006');	
			}

			$questionIndex = $options[0]['question_index'];
			$questionType = $options[0]['question_type'];

			// 判断是否存在回答
			if( !empty($_COOKIE['answerSerialize']) ){

				// 解析
				$answers = unserialize($_COOKIE['answerSerialize']);

				if($questionType == 3) {
					// 多选
					$curValArr = explode(",", $answers[$questionIndex]);

					for ($i=0; $i < count($curValArr); $i++) { 
						if( $curValArr[$i] == $val ) {
							unset($curValArr[$i]);
						}
					}

					if( empty($curValArr) ) {
						unset($answers[$questionIndex]);
					} else {
						sort($curValArr);
						$answers[$questionIndex] = implode(",", $curValArr);
					}

					if(empty($answers)) {
						$rs1 = setcookie("answerSerialize", "", time()-1, "/");
					} else {
						$answerSerialize = serialize($answers);
						$rs1 = setcookie("answerSerialize", $answerSerialize, time()+3600, "/");
					}

					if( !$rs1 ) {
						throw new \Exception("设置cookie失败", '067');
					}

				}

			}

			$return = $this->functionObj->toAppJson(null, '001', '移除多选答案成功', true);
 
		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* 是否已经全部答完
	* @author 	xww
	* @return 	json
	*/
	public function isAnswerAll()
	{
		
		try{

			// $this->configValid('required',$this->_configs);

			if( empty($_COOKIE['examId']) ) {
				throw new \Exception("请重新进行考试", '006');
			}

			$examModelObj = new \Module\Exam\VirgoModel\ExamModel;

			$examId = $_COOKIE['examId'];
			$questionCount = $examModelObj->getExamQuetionCount( $examId );
			
			if( empty($_COOKIE['answerSerialize']) ) {
				$rs = false;
			} else {
				
				$answerArr = unserialize($_COOKIE['answerSerialize']);
				$answerCount = count($answerArr);

				if($answerCount == $questionCount) {
					$rs = true;
				} else {
					$rs = false;
				}

			}

			$return = $this->functionObj->toAppJson($rs, '001', '获取答题数量成功', true);
 
		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* 完成一个考试
	* @author 	xww
	* @return 	json
	*/
	public function done()
	{

		try{

			DB::beginTransaction();

			if( empty($_COOKIE['examId']) ) {
				throw new \Exception("请重新进行考试", '006');
			}

			if( empty($_COOKIE['examUserId']) ) {
				throw new \Exception("请重新登录", '006');
			}

			$examModelObj = new \Module\Exam\VirgoModel\ExamModel;
			$answerExamModelObj = new \Module\Exam\VirgoModel\AnswerExamModel;
			$answerResultModelObj = new \Module\Exam\VirgoModel\AnswerResultModel;

			$examId = $_COOKIE['examId'];
			$userId = $_COOKIE['examUserId'];

			// @todo 插入到对应回答表
			$temp['user_id'] = $userId;
			$temp['exam_id'] = $examId;
			$temp['is_deleted'] = 0;
			$temp['create_time'] = time();
			$temp['update_time'] = time();

			$aeId = $answerExamModelObj->create($temp);
			unset($temp);

			if(!$aeId) {
				throw new \Exception("新建回答题目失败", '003');
			}

			// 获取所有该考试的题目
			// index 从0开始
			$questions = $examModelObj->getExamQuetionsOptions($examId);

			if( empty( $questions ) ) {
				throw new \Exception("查询不到题目", '006');
			}

			$answerArr = [];
			if( !empty($_COOKIE['answerSerialize']) ){
				$answerArr = unserialize($_COOKIE['answerSerialize']);
			}

			// 全部题目
			$totalCount = 0;
			$getScore = 0;
			$rightCount = 0;
			$errorCount = 0;

			// 修改对应回答表
			for ($i=0; $i < count($questions['data']); $i++) { 

				$totalCount++;

				// 问题数据
				$data = $questions['data'][$i][0];

				if( !empty($answerArr) && !empty($answerArr[ $data['question_index'] ]) ) {

					$answer = $answerArr[ $data['question_index'] ];
					$answer = explode(",", $answer);
					sort($answer);

					$questionAnswer = $data['answer_str'];
					$questionAnswer = explode(",", $questionAnswer);
					sort($questionAnswer);

					if( $answer==$questionAnswer ) {
						// 回答正确 --增加获取分数, 答题正确数
						$getScore += (int)$data['score'];
						$rightCount++;
					} else {
						// 回答错误
						$errorCount++;
					}

					// 没有回答该题
					$temp['ae_id'] = $aeId;
					$temp['eq_id'] = $data['id'];
					$temp['answer'] = implode(",", $answer);
					$temp['is_deleted'] = 0;
					$temp['create_time'] = time();
					$temp['update_time'] = time();
					$rs1 = $answerResultModelObj->create($temp);
					unset($temp);
					if( !$rs1 ) {
						throw new \Exception("新建回答失败", '005');
					}

				} else {

					// 回答错误
					$errorCount++;
					
					// 没有回答该题
					$temp['ae_id'] = $aeId;
					$temp['eq_id'] = $data['id'];
					$temp['answer'] = 0;
					$temp['is_deleted'] = 0;
					$temp['create_time'] = time();
					$temp['update_time'] = time();
					$rs1 = $answerResultModelObj->create($temp);
					unset($temp);
					if( !$rs1 ) {
						throw new \Exception("新建回答失败", '005');
					}

				}

			}

			// 修改answer exam表
			$temp['get_score'] = $getScore;
			$temp['right_count'] = $rightCount;
			$temp['error_count'] = $errorCount;
			$temp['total_count'] = $totalCount;

			$rs2 = $answerExamModelObj->partUpdate($aeId, $temp);
			if( !$rs2 ) {
				throw new \Exception("更新回答失败", '003');
			}

			$rs1 = setcookie('examId', '', time()-1, "/");
			$rs2 = setcookie('curQuestionId', '', time()-1, "/");
			$rs3 = setcookie('answerSerialize', '', time()-1, "/");

			if( !$rs1 || !$rs2 || !$rs3) {
				throw new \Exception("清除cookie失败", '084');
			}

			DB::commit();
			$return = $this->functionObj->toAppJson(['totalCount'=>$totalCount, "getScore"=>$getScore, "rightCount"=>$rightCount, "errorCount"=>$errorCount], '001', '完成结算', true);
 
		} catch(\Exception $e) {
			DB::rollback();
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* 获取题目详情
	* @author 	xww
	* @return 	json
	*/
	public function info()
	{
		
		try{

			if( empty($_COOKIE['examId']) ) {
				throw new \Exception("请重新进行考试", '006');
			}

			if( empty($_COOKIE['examUserId']) ) {
				throw new \Exception("请重新登录", '006');
			}

			$examModelObj = new \Module\Exam\VirgoModel\ExamModel;

			$examId = $_COOKIE['examId'];

			$questions = $examModelObj->getExamQuetionsOptions($examId);

			if( empty( $questions ) ) {
				throw new \Exception("查询不到题目", '006');
			}

			$answerArr = [];
			if( !empty($_COOKIE['answerSerialize']) ){
				$answerArr = unserialize($_COOKIE['answerSerialize']);
			}

			$data = [];

			// 单选
			$data[0] = [];

			// 多选
			$data[1] = [];

			// 判断
			$data[2] = [];

			// 统计信息
			for ($i=0; $i < count($questions['data']); $i++) {
				$temp = [];
				$temp['index'] = $questions['data'][$i][0]['question_index'];
				$temp['is_answered'] = !empty($answerArr) && !empty($answerArr[ $questions['data'][$i][0]['id'] ])? true:false;

				if($questions['data'][$i][0]['question_type']==1) {
					$data[0][] = $temp;
				} else if($questions['data'][$i][0]['question_type']==2) {
					$data[2][] = $temp;
				} else if($questions['data'][$i][0]['question_type']==3) { 
					$data[1][] = $temp;
				}

			}

			$data[0] = empty($data[0])? null:$data[0];
			$data[1] = empty($data[1])? null:$data[1];
			$data[2] = empty($data[2])? null:$data[2];


			$return = $this->functionObj->toAppJson($data, '001', '获取题目信息成功', true);
 
		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* 回答题目--后台直接回答题目
	* @author 	xww
	* @return 	json
	*/
	public function backAnswer()
	{
		
		try {

			$this->configValid('required',$this->_configs,['questionId', 'val']);

			if( empty($_COOKIE['backExamId']) ) {
				throw new \Exception("请重新进行考试", '006');
			}

			$examModelObj = new \Module\Exam\VirgoModel\ExamModel;

			$examId = $_COOKIE['backExamId'];
			$questionId = $this->_configs['questionId'];
			$val = $this->_configs['val'];

			$options = $examModelObj->getExamQuetionOptions($examId , $questionId);

			if( empty($options) ) {
				throw new \Exception("获取题目失败", '006');	
			}

			$questionIndex = $options[0]['question_index'];
			$questionType = $options[0]['question_type'];

			$answerIndex = null;
			for ($i=0; $i < count($options); $i++) { 
				if( $options[$i]['option_index'] == $val ) {
					$answerIndex = $options[$i]['option_index'];
					break;
				}
			}

			if( is_null($answerIndex) ) {
				throw new \Exception("未知回答", '006');
			}

			// 判断是否存在回答
			if( !empty($_COOKIE['backAnswerSerialize']) ){
				// 解析
				$answers = unserialize($_COOKIE['backAnswerSerialize']);

				if( empty($answers[$questionIndex]) ) {
					// 存储回答
					$answers[$questionIndex] = $val;
					$answerSerialize = serialize($answers);

					$rs1 = setcookie("backAnswerSerialize", $answerSerialize, time()+3600, "/");
					if( !$rs1 ) {
						throw new \Exception("设置cookie失败", '067');
					}

				} else {

					if( $questionType == 1 || $questionType == 2 ) {
						// 单选/判断
						$answers[$questionIndex] = $val;
					} else if($questionType == 3) {
						// 多选
						$curValArr = explode(",", $answers[$questionIndex]);

						$hasVal = false;
						for ($i=0; $i < count($curValArr); $i++) { 
							if( $curValArr[$i] == $val ) {
								$hasVal = true;
							}
						}

						if(!$hasVal) {
							array_push($curValArr, $val);
						}

						$answers[$questionIndex] = implode(",", $curValArr);
					}

					$answerSerialize = serialize($answers);
					$rs1 = setcookie("backAnswerSerialize", $answerSerialize, time()+3600, "/");
					if( !$rs1 ) {
						throw new \Exception("设置cookie失败", '067');
					}

				}

			} else {
				// 存储回答
				$temp[$questionIndex] = $val;
				$answerSerialize = serialize($temp);

				$rs1 = setcookie("backAnswerSerialize", $answerSerialize, time()+3600, "/");
				if( !$rs1 ) {
					throw new \Exception("设置cookie失败", '067');
				}

			}

			$return = $this->functionObj->toAppJson(null, '001', '保存答案成功', true);
 
		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* 移除答案--后台直接回答题目
	* @author 	xww
	* @return 	json
	*/
	public function backRemoveAnswer()
	{
		
		try{

			$this->configValid('required',$this->_configs,['questionId', 'val']);

			if( empty($_COOKIE['backExamId']) ) {
				throw new \Exception("请重新进行考试", '006');
			}

			$examModelObj = new \Module\Exam\VirgoModel\ExamModel;

			$examId = $_COOKIE['backExamId'];
			$questionId = $this->_configs['questionId'];
			$val = $this->_configs['val'];

			$options = $examModelObj->getExamQuetionOptions($examId , $questionId);

			if( empty($options) ) {
				throw new \Exception("获取题目失败", '006');	
			}

			$questionIndex = $options[0]['question_index'];
			$questionType = $options[0]['question_type'];

			// 判断是否存在回答
			if( !empty($_COOKIE['backAnswerSerialize']) ){

				// 解析
				$answers = unserialize($_COOKIE['backAnswerSerialize']);

				if($questionType == 3) {
					// 多选
					$curValArr = explode(",", $answers[$questionIndex]);

					for ($i=0; $i < count($curValArr); $i++) { 
						if( $curValArr[$i] == $val ) {
							unset($curValArr[$i]);
						}
					}

					if( empty($curValArr) ) {
						unset($answers[$questionIndex]);
					} else {
						sort($curValArr);
						$answers[$questionIndex] = implode(",", $curValArr);
					}

					if(empty($answers)) {
						$rs1 = setcookie("backAnswerSerialize", "", time()-1, "/");
					} else {
						$answerSerialize = serialize($answers);
						$rs1 = setcookie("backAnswerSerialize", $answerSerialize, time()+3600, "/");
					}

					if( !$rs1 ) {
						throw new \Exception("设置cookie失败", '067');
					}

				}

			}

			$return = $this->functionObj->toAppJson(null, '001', '移除多选答案成功', true);
 
		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* 是否已经全部答完--后台直接回答
	* @author 	xww
	* @return 	json
	*/
	public function backIsAnswerAll()
	{
		
		try{

			// $this->configValid('required',$this->_configs);

			if( empty($_COOKIE['backExamId']) ) {
				throw new \Exception("请重新进行考试", '006');
			}

			$examModelObj = new \Module\Exam\VirgoModel\ExamModel;

			$examId = $_COOKIE['backExamId'];
			$questionCount = $examModelObj->getExamQuetionCount( $examId );
			
			if( empty($_COOKIE['backAnswerSerialize']) ) {
				$rs = false;
			} else {
				
				$answerArr = unserialize($_COOKIE['backAnswerSerialize']);
				$answerCount = count($answerArr);

				if($answerCount == $questionCount) {
					$rs = true;
				} else {
					$rs = false;
				}

			}

			$return = $this->functionObj->toAppJson($rs, '001', '获取答题数量成功', true);
 
		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* 后台直接回答--完成一个考试
	* @author 	xww
	* @return 	json
	*/
	public function backDone()
	{

		try{

			DB::beginTransaction();

			if( empty($_COOKIE['backExamId']) ) {
				throw new \Exception("请重新进行考试", '006');
			}

			if( empty($_COOKIE['user_id']) ) {
				throw new \Exception("请重新登录", '006');
			}

			$examModelObj = new \Module\Exam\VirgoModel\ExamModel;
			$answerExamModelObj = new \Module\Exam\VirgoModel\AnswerExamModel;
			$answerResultModelObj = new \Module\Exam\VirgoModel\AnswerResultModel;

			$examId = $_COOKIE['backExamId'];
			$userId = $_COOKIE['user_id'];

			// @todo 插入到对应回答表
			$temp['user_id'] = $userId;
			$temp['exam_id'] = $examId;
			$temp['is_deleted'] = 0;
			$temp['create_time'] = time();
			$temp['update_time'] = time();

			$aeId = $answerExamModelObj->create($temp);
			unset($temp);

			if(!$aeId) {
				throw new \Exception("新建回答题目失败", '003');
			}

			// 获取所有该考试的题目
			// index 从0开始
			$questions = $examModelObj->getExamQuetionsOptions($examId);

			if( empty( $questions ) ) {
				throw new \Exception("查询不到题目", '006');
			}

			$answerArr = [];
			if( !empty($_COOKIE['backAnswerSerialize']) ){
				$answerArr = unserialize($_COOKIE['backAnswerSerialize']);
			}

			// 全部题目
			$totalCount = 0;
			$getScore = 0;
			$rightCount = 0;
			$errorCount = 0;

			// 修改对应回答表
			for ($i=0; $i < count($questions['data']); $i++) { 

				$totalCount++;

				// 问题数据
				$data = $questions['data'][$i][0];

				if( !empty($answerArr) && !empty($answerArr[ $data['question_index'] ]) ) {

					$answer = $answerArr[ $data['question_index'] ];
					$answer = explode(",", $answer);
					sort($answer);

					$questionAnswer = $data['answer_str'];
					$questionAnswer = explode(",", $questionAnswer);
					sort($questionAnswer);

					if( $answer==$questionAnswer ) {
						// 回答正确 --增加获取分数, 答题正确数
						$getScore += (int)$data['score'];
						$rightCount++;
					} else {
						// 回答错误
						$errorCount++;
					}

					// 没有回答该题
					$temp['ae_id'] = $aeId;
					$temp['eq_id'] = $data['id'];
					$temp['answer'] = implode(",", $answer);
					$temp['is_deleted'] = 0;
					$temp['create_time'] = time();
					$temp['update_time'] = time();
					$rs1 = $answerResultModelObj->create($temp);
					unset($temp);
					if( !$rs1 ) {
						throw new \Exception("新建回答失败", '005');
					}

				} else {

					// 回答错误
					$errorCount++;
					
					// 没有回答该题
					$temp['ae_id'] = $aeId;
					$temp['eq_id'] = $data['id'];
					$temp['answer'] = 0;
					$temp['is_deleted'] = 0;
					$temp['create_time'] = time();
					$temp['update_time'] = time();
					$rs1 = $answerResultModelObj->create($temp);
					unset($temp);
					if( !$rs1 ) {
						throw new \Exception("新建回答失败", '005');
					}

				}

			}

			// 修改answer exam表
			$temp['get_score'] = $getScore;
			$temp['right_count'] = $rightCount;
			$temp['error_count'] = $errorCount;
			$temp['total_count'] = $totalCount;

			$rs2 = $answerExamModelObj->partUpdate($aeId, $temp);
			if( !$rs2 ) {
				throw new \Exception("更新回答失败", '003');
			}

			$rs1 = setcookie('backExamId', '', time()-1, "/");
			$rs2 = setcookie('backCurQuestionId', '', time()-1, "/");
			$rs3 = setcookie('backAnswerSerialize', '', time()-1, "/");

			if( !$rs1 || !$rs2 || !$rs3) {
				throw new \Exception("清除cookie失败", '084');
			}

			DB::commit();
			$return = $this->functionObj->toAppJson(['totalCount'=>$totalCount, "getScore"=>$getScore, "rightCount"=>$rightCount, "errorCount"=>$errorCount], '001', '完成结算', true);
 
		} catch(\Exception $e) {
			DB::rollback();
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

}