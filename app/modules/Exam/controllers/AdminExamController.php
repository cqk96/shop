<?php
 /**
 * 控制器
 * @author xww <5648*****@qq.com>
 * @version 1.0.0
 */
namespace Module\Exam\Controller;
use Illuminate\Database\Capsule\Manager as DB;
use VirgoBack;
 class AdminExamController extends VirgoBack\AdminBaseController{
	 /*
	 * @param  object  reflect this controller's  virgo model object
	 */
	 private $model;

	 public function __construct()
	 {
		$this->model = new \Module\Exam\VirgoModel\ExamModel;
		parent::isLogin();
	 }

	 // 获取列表
	 public function lists()
	 {
		 
		$page_title = '管理';
		$pageObj = $this->model->lists(1);
		
		// 赋值数据
		$data = $pageObj->data;

		// 题目搜索
		$title = null;
		if( !empty($_GET['title']) ) {
			$title = trim( $_GET['title'] );
		}

		// 题目状态搜索
		$statusId = null;
		if( !empty($_GET['statusId']) ) {
			if($_GET['statusId']==1) {
				$statusId = 1;
			} else if($_GET['statusId']==2){
				$statusId = 2;
			}
		}

		$totalCount = $pageObj->totalCount;

		$size = $pageObj->size;

		$totalPage = $totalCount % $size == 0? $totalCount / $size:ceil($totalCount / $size);

		require_once dirname(__FILE__).'/../views/adminExam/index.php';

	 }

	 // 增加专区分类界面
	 public function create()
	 {
		 $page_title = '增加管理';
		 // 增加页面
		 require_once dirname(__FILE__).'/../views/adminExam/_create.php';
	 }

	 // 处理增加
	 public function doCreate()
	 {
		 $page = $_POST['page'];
		 $rs = $this->model->doCreate();
		 if($rs){$this->showPage(['添加专区分类成功'],'/admin/exams?page='.$page); }
		 else {$this->showPage(['添加专区分类失败'],'/admin/exams?page='.$page); }
	 }

	 //修改专区分类页面
	 public function update()
	 {
		
		try{

			if( empty($_GET['id']) ) {
				throw new \Exception("Wrong Param");
			}

			$id = $_GET['id'];

			$page_title = '修改管理';
			
			$data = $this->model->read($id);

			if( empty($data) ) {
				throw new \Exception("无法查询到数据");
			}

			$answerModel = new \Module\Exam\VirgoModel\AnswerExamModel;

			// 获取考试的数据
			$questions = $this->model->getExamQuetionsOptions( $id );

			$questionsData = [];
			if( !empty($questions) ) {
				$questionsData = $questions['data'];
			}

			$questionOptionObject = [];
			$score = 0;
			for ($i=0; $i < count($questionsData); $i++) { 
				$questionOptionObject[ $i ] = count($questionsData[$i]['options']);
				$score += $questionsData[$i][0]['score'];
			}

			$questionOptionObjectJson = json_encode( $questionOptionObject );

			$questionsDataJson = json_encode( $questionsData );
			// var_dump($questionsData);

			// 判断是否已经有人考试过了--如果有考试过的则去除提交按钮
			$hasTesting = $answerModel->hasTestingTheExam( $id );

			// 专区分类修改页面
			require_once dirname(__FILE__).'/../views/adminExam/_update.php';

		} catch(\Exception $e) {
			echo $e->getMessage();
		}

	 }

	 // 处理修改
	 public function doUpdate()
	 {
		
		// @todo    已经存在考试过的要停止更新
		$page = empty($_POST['page'])? 1:$_POST['page'];
			
		$rs = $this->model->doUpdate();

		if( $rs ) {
			$this->showPage(['修改成功'],'/admin/exams?page='.$page);
		} else {
			$this->showPage(['修改失败'],'/admin/exams?page='.$page);
		}

	 }

	 // 处理删除
	 public function doDelete()
	 {
		 $rs =  $this->model->delete();
		 if($_POST){
			 if($rs){echo json_encode(['success'=>true,'message'=>'delete success']);}
			 else{echo json_encode(['success'=>false,'message'=>'delete failture']);}
		 } else {
			 if($rs){$this->showPage(['删除成功'],'/admin/exams');}
			 else {$this->showPage(['删除失败'],'/admin/exams');}
		 }
	 }

	/**
	* 默认考试中心--批量上传
	* @author 	xww
	* @return 	void
	*/
	public function batchUpload()
	{
		
		try {

			if( !empty($_FILES) && !empty($_FILES['file']) && $_FILES['file']['error']==0) {

				// 实例化对象
				$examModelObj = new \Module\Exam\VirgoModel\ExamModel;
				$examQuestionModelObj = new \Module\Exam\VirgoModel\ExamQuestionModel;
				$examOptionModelObj = new \Module\Exam\VirgoModel\ExamOptionModel;

				// var_dump($_FILES);

				$fileName = $_FILES['file']['name'];

				$fileArr = explode(".", $fileName);

				$ext = array_pop($fileArr);

				$title = implode(".", $fileArr);

				DB::beginTransaction();

				// 变量

				// 考试名id
				$examId = null;

				// 题目id
				$questionId = null;

				// 答案
				$answerArr = null;

				// 查询对应考试是否存在    存在就跳过
				$exam = $examModelObj->getSpecailExamFromTypeAndName(1, $title);

				if( !empty( $exam ) ) {
					throw new \Exception("已经存在该考试", '026');
				} else {
					$temp['title'] = $title;
					$temp['status_id'] = 1;
					$temp['type_id'] = 1;
					$temp['is_deleted'] = 0;
					$temp['create_time'] = time();
					$temp['update_time'] = time();

					$examId = $examModelObj->create($temp);
					unset($temp);

					if( !$examId ) {
						throw new \Exception("生成考试失败", '005');
					}

				}

				$phpWord = \PhpOffice\PhpWord\IOFactory::load($_FILES['file']['tmp_name']);

				// var_dump($phpWord);

				// $document = $phpWord->getDocumentProperties();

				// 约定规则
				// 格式必须为word2007
				// 1. 文件名作为考试名
				// 2. 整数+、 作为题目顺序    
				// 3. 题目中如果存在[单选题/多选题/判断题]  那么题目为对应的地方（这里存储答案 每个字符分隔）(这里存储分数)
				// 4. 选项为A-Z + . 方式命名    同时也是选项顺序
				// 5. 判断题索引约定    1对    2错

				$sections = $phpWord->getSections();

				for ($i=0; $i < count( $sections ); $i++) { 
					$section = $sections[$i];
					$elements = $section->getElements();

					for ($j=0; $j < count($elements); $j++) { 

						$clasName = get_class( $elements[$j] );

						if( stripos($clasName, "TextRun") ) {

							$innerElements = $elements[$j]->getElements();

							$strArr = [];
							for ($k=0; $k < count($innerElements); $k++) {
								// var_dump($innerElements[$k]); 
								$strArr[] = $innerElements[$k]->getText();
							}

							// 将一行的文本获取
							$str = implode("", $strArr);

							// var_dump($str);

							// 进行处理
							$examFormat = "/^(\d{1,})、/";

							if( preg_match($examFormat, $str, $questionIndexMatch) ) {
								// 这是个题目
								$answerArr = null;
								$questionIndex = $questionIndexMatch[1];

								// 获取题目类型文本
								$typeFormat = "/\[(.*?)\]/";
								if( preg_match($typeFormat, $str, $typeMatches) ) {

									$formatTypeStr = $typeMatches[0];
									$typeStr = $typeMatches[1];
									$typeInt = $this->getTypeInt( $typeStr );

									if( $typeInt==0 ) {
										throw new \Exception("未知题目类型");	
									}

									// 获取对应input类型
									$inputTypeInt = $this->getInputTypeInt( $typeInt );

									if( $inputTypeInt==0 ) {
										throw new \Exception("未知题目类型");	
									}

									// 将题目类型清空
									$str = str_replace($formatTypeStr, "", $str);

									// 将格式中的题目索引去掉
									$str = str_replace($questionIndex ."、", "", $str);

									// 获取分数
									$scoreFormat = "/\((\d{1,})分\)/";

									if( preg_match($scoreFormat, $str, $scoreMatch) ) {
										$score = $scoreMatch[1];
									} else {
										throw new \Exception("没有分数", '006');
									}

									
									// 获取答案 并将题目中的答案的替换为空
									$answerFormat = "/（(.*?)）/";

									if( preg_match($answerFormat, $str, $answerMatch) ) {
										$answerOrigin = $answerMatch[0];
										$answerText = trim($answerMatch[1]);

										if( $typeInt == 2) {
											// 判断题 获取判断题整形
											$answerStr = $this->getCheckInt($answerText);
											if( $answerStr==-1 ) {
												throw new \Exception("未知判断题回答");				
											}
											$answerArr[] = $answerStr;
										} else {
											// 打散 + ','拼接答案
											$answerArr = str_split($answerText);
											for ($l=0; $l < count($answerArr); $l++) { 
												$answerArr[$l] = strtoupper($answerArr[$l]);
												$answerArr[$l] = ord($answerArr[$l])-64;
											}
											sort($answerArr);
											$answerStr = implode(",", $answerArr);
										}

										// 将对应的答案清空
										$str = str_replace($answerOrigin, "( )", $str);

										// 判断是否有对应索引题目    如果存在则跳过
										$hasQuestion = $examQuestionModelObj->getExamQuestionIndex($examId, $questionIndex);

										if( !empty( $hasQuestion ) ) {
											throw new \Exception("已经存在该索引题目", '026');
										} else {

											$temp['exam_id'] = $examId;
											$temp['content'] = $str;
											$temp['score'] = empty($score)? 0:$score;
											$temp['question_type'] = $typeInt;
											$temp['html_type'] = $inputTypeInt;
											$temp['question_index'] = $questionIndex;
											$temp['answer_str'] = $answerStr;
											$temp['is_deleted'] = 0;
											$temp['create_time'] = time();
											$temp['update_time'] = time();

											$questionId = $examQuestionModelObj->create($temp);
											unset( $temp );

											// 新建对应题目    失败throw
											if( !$questionId ) {
												throw new \Exception("新建题目失败", "005");
											}

											// 当题型是判断题的时候 要存储对应选项
											if( $typeInt == 2) {
												for ($m=0; $m <= 1; $m++) { 
													$optionRecord = $examOptionModelObj->getExamQuestionOptionIndex($questionId, ($m+1) );

													if( !empty( $optionRecord ) ) {
														throw new \Exception("已经存在该题目答案选项", "026");
													} else {

														// todo   存储选项
														$temp['eq_id'] = $questionId;
														$temp['option_content'] = $m==0? '对':"错";
														$temp['option_index'] = $m+1;
														$temp['is_right'] = in_array($m+1, $answerArr)? 1:0;
														$temp['is_deleted'] = 0;
														$temp['create_time'] = time();
														$temp['update_time'] = time();
														$examOptionId = $examOptionModelObj->create($temp);
														unset($temp);

														if( !$examOptionId ) {
															throw new \Exception("新建选项失败", "005");
														} 

													}

												}
											}

											continue;

										}

									} else {
										throw new \Exception("未知答案格式");	
									}
									
								} else {
									throw new \Exception($str."未知题目类型格式");
								}

							}

							// 判断是否是选项 A从65开始 意味着要-64才是排序
							$optionFormat = "/^([A-Z]\.)/";
							
							if( preg_match($optionFormat, $str, $optionMatch) ) {

								// 排序
								$optionIndex = ord( str_replace(".", "", $optionMatch[1]) )-64;

								// 选项
								$optionValue = str_replace(".", "", $optionMatch[1]);

								// 选项描述
								$optionContent = str_replace($optionMatch[1], "", $str);

								$optionRecord = $examOptionModelObj->getExamQuestionOptionIndex($questionId, $optionIndex);

								if( !empty( $optionRecord ) ) {
									throw new \Exception("已经存在该题目答案选项", "026");
								} else {

									// todo   存储选项
									$temp['eq_id'] = $questionId;
									$temp['option_content'] = $optionContent;
									$temp['option_index'] = $optionIndex;
									$temp['is_right'] = in_array($optionValue, $answerArr)? 1:0;
									$temp['is_deleted'] = 0;
									$temp['create_time'] = time();
									$temp['update_time'] = time();
									$examOptionId = $examOptionModelObj->create($temp);
									unset($temp);

									if( !$examOptionId ) {
										throw new \Exception("新建选项失败", "005");
									} 

								}

								

							}
							
						}// textrun if end

					}// element end		

				}// section end

			}// if end

			DB::commit();

		} catch (\Exception $e) {
			DB::rollback();
			// echo $e->getMessage();
		} finally {
			$this->showPage(['解析成功'],'/admin/exams');
		}

		// var_dump($sections);
		// var_dump($sections[0]->elements);
		// var_dump( $sections->getText() );

		// var_dump($document);

	}

	/**
	* 根据题目类型字符串获取  类型id
	* @author 	xww
	* @param 	string 		$typeString
	* @return 	int
	*/
	public function getTypeInt($typeString)
	{
		switch ($typeString) {
			case '单选题':
				return 1;
				break;
			case '判断题':
				return 2;
				break;
			case '多选题':
				return 3;
				break;
			default:
				return 0;
				break;
		}
	}

	/**
	* 根据题目类型id    获取对应html input类型
	* @author 	xww
	* @param 	int/string 		$typeInt
	* @return 	int
	*/
	public function getInputTypeInt($typeInt)
	{
		
		switch ((int)$typeInt) {
			case 1:
			case 2:
				return 1;
				break;
			case 3:
				return 2;
				break;
			default:
				return 0;
				break;
		}

	}

	/**
	* 根据判断题答案获取对应整形
	* @author　	xww
	* @param 	string  	$answer
	* @return 	int
	*/
	public function getCheckInt($answer)
	{
		switch ($answer) {
			case '对':
				return 1;
				break;
			case '错':
				return 2;
				break;
			
			default:
				return -1;
				break;
		}
	}

	/**
	* 可以被普通用户查看的考试中心
	* render the page
	* @author 	xww
	* @return 	void
	*/
	public function testLists()
	{
		
		try{

			$pageObj = $this->model->testLists(1);

			// 赋值数据
			$data = $pageObj->data;

			// 题目搜索
			$title = null;
			if( !empty($_GET['title']) ) {
				$title = trim( $_GET['title'] );
			}

			$totalCount = $pageObj->totalCount;

			$size = $pageObj->size;

			$totalPage = $totalCount % $size == 0? $totalCount / $size:ceil($totalCount / $size);

			// 用户可见考试列表
			require_once dirname(__FILE__).'/../views/adminExam/test-lists.php';

		} catch(\Exception $e) {
			echo $e->getMessage();
		}

	}

	/**
	* 开始考试 
	* render the page
	* @author 	xww
	* @return 	void
	*/
	public function startTesting()
	{
		
		try{

			if( empty($_GET['id']) ) {
				throw new \Exception("Wrong Param");
			}

			if( empty($_COOKIE['user_id']) ) {
				throw new \Exception("请重新登录");
			}

			// 当前考题
			$questionId = empty( $_GET['questionId'] ) || (int)$_GET['questionId']<1? 1:(int)$_GET['questionId'];

			if( empty($_COOKIE['backExamId']) ) {

				$rs1 = true;
				$rs2 = true;
				$rs3 = true;
				$rs4 = true;

				if( !empty($_COOKIE['backExamId']) ) {
					$rs1 = setcookie('backExamId', '', time()-1, "/");
				}

				if( !empty($_COOKIE['backCurQuestionId']) ) {
					$rs3 = setcookie('backCurQuestionId', '', time()-1, "/");
				}

				if( !empty($_COOKIE['backAnswerSerialize']) ) {
					$rs4 = setcookie('backAnswerSerialize', '', time()-1, "/");
				}
				
				if( !$rs1 || !$rs2 || !$rs3 || !$rs4 ) {
					throw new \Exception("清除cookie失败");
				}

				// 获取用户
				$uid = $_COOKIE['user_id'];
				$user = \EloquentModel\User::where("is_deleted", 0)->find($uid);

				if( empty($user) ) {
					throw new \Exception("用户不存在", '006');
				}

				$rs1 = setcookie('backExamId', $_GET['id'], time()+3600, "/");

				if( !$rs1 || !$rs2) {
					throw new \Exception("设置cookie失败", '067');
				}

				// 为了保障cookie可取到 重新定位
				$url = "/admin/exams/testList/start?id=" . $_GET['id'] . "&questionId=" . $questionId;
				header("Location: " . $url);
				exit();

			}

			// 如果当前考试名与cookie中不相符
			// 清空并强制跳转
			if( $_GET['id'] != $_COOKIE['backExamId'] ) {
				$rs1 = setcookie('backExamId', '', time()-1, "/");
				$rs2 = true;
				$rs3 = setcookie('backCurQuestionId', '', time()-1, "/");
				$rs4 = setcookie('backAnswerSerialize', '', time()-1, "/");

				if( !$rs1 || !$rs2 || !$rs3 || !$rs4 ) {
					throw new \Exception("清除cookie失败", '084');
				}

				// 为了保障cookie可取到 重新定位
				$url = "/admin/exams/testList/start?id=" . $_GET['id'] . "&questionId=" . $questionId;
				header("Location: " . $url);
				exit();

			}

			// 获取所有该考试的题目
			$examModelObj = new \Module\Exam\VirgoModel\ExamModel;

			// index 从0开始
			$questions = $examModelObj->getExamQuetionsOptions($_COOKIE['backExamId']);

			// 题目数量
			$questionsCount = count($questions['data']);

			if( empty($questions) ) {
				throw new \Exception("考试不存在", '006');
			}

			// 判断当前题目是否存在  索引从0开始
			if( !isset($questions['data'][$questionId-1]) ) {
				throw new \Exception("题目不存在", '006');
			}

			$curData = $questions['data'][$questionId-1];

			// var_dump($curData);
			// die;

			// 获取上题 下题
			$prevQuestionId = null;
			$nextQuestionId = null;

			// 题目列表
			$questionLists = [];
			// if( !empty($_COOKIE['backAnswerSerialize']) ) {
			// 	$answerArr = unserialize($_COOKIE['backAnswerSerialize']);
			// 	foreach ($answerArr as $key => $value) {
			// 		$answerArr[$key] = explode(",", $value);
			// 	}
			// }

			// var_dump($questions['data'][4]['options']);
			// var_dump(!empty($answerArr[4+1]));
			// die;

			// 下一题
			for ($i=0; $i < count($questions['data']); $i++) { 

				$questionLists[] = ($i+1);

				// 下一题
				if( is_null( $nextQuestionId ) && ( ($i+1) > $questionId ) ) {
					$nextQuestionId = $i+1;
				}

			}

			// 上一题 
			// for ($i=count($questions['data'])-1; $i >=0 ; $i--) { 

			// 	if( is_null( $prevQuestionId ) && ($i+1) < $questionId) {
			// 		$prevQuestionId = $questions['data'][$i][0]['id'];
			// 	}

			// }

			$questionCount = count($questionLists);

			$hrefBaseUrl = "/admin/exams/testList/start?id=" . $_GET['id'] . "&questionId=";

			// 题目类型与题目

			$answerArr = [];
			if( !empty($_COOKIE['backAnswerSerialize']) ){
				$answerArr = unserialize($_COOKIE['backAnswerSerialize']);
				foreach ($answerArr as $key => $value) {
					$answerArr[$key] = explode(",", $value);
				}
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
				$temp['is_answered'] = !empty($answerArr) && !empty($answerArr[ $questions['data'][$i][0]['question_index'] ])? true:false;

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

			// var_dump($questionsCount);
			// var_dump($questionId);
			
			// 用户可见考试列表
			require_once dirname(__FILE__).'/../views/adminExam/start-testing.php';			

		} catch(\Exception $e) {
			echo $e->getMessage();
		}

	}

}
?>