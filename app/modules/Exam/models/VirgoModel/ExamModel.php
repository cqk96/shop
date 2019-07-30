<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace Module\Exam\VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
use VirgoModel;
class ExamModel extends VirgoModel\BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \Module\Exam\EloquentModel\Exam; 
	}

	/**
	* 列表
	* @author 	xww
	* @param 	int/string 		$typeId
	* @param 	string 			$url
	* @return 	object
	*/
	public function lists($typeId=null, $url=null)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;
		
		// set query 
		$query = $this->_model->where("is_deleted", '=', 0)->orderBy("create_time", "desc");

		if( !is_null( $typeId ) ) {
			$query = $query->where("type_id", $typeId);			
		}

		// 标题过滤
		if(!empty($_GET['title'])){
			$_GET['title'] = trim($_GET['title']);
			$query = $query->where("title", 'like', '%'.$_GET['title'].'%');
			$pageObj->setPageQuery(['title'=>$_GET['title']]);
		}

		if(!empty($_GET['statusId'])){
			if($_GET['statusId']==1) {
				$query = $query->where("status_id", 0);	
			} else if($_GET['statusId']==2){
				$query = $query->where("status_id", 1);	
			}
			$pageObj->setPageQuery(['statusId'=>$_GET['statusId']]);
		}
		
		// // 开始时间过滤
		// if(!empty($_GET['startTime'])){
		// 	$_GET['startTime'] = trim($_GET['startTime']);
		// 	$query = $query->where("update_time", '>=', strtotime($_GET['startTime']." 00:00:00"));
		// 	$pageObj->setPageQuery(['startTime'=>$_GET['startTime']]); 
		// }

		// // 截止时间过滤
		// if(!empty($_GET['endTime'])){
		// 	$_GET['endTime'] = trim($_GET['endTime']);
		// 	$query = $query->where("update_time", '<=', strtotime($_GET['endTime']." 23:59:59"));
		// 	$pageObj->setPageQuery(['endTime'=>$_GET['endTime']]);
		// }

		// 父菜单总记录数
		$totalCount = count($query->get()->toArray());
		//分页的take,size
		$size = 10;
		if(!empty($_GET['page'])){
			$page = (int)$_GET['page'];
			$skip = ($page-1)*$size;
		} else {
			$skip = 0;
		}

		if( is_null($url) ) {
			$url = '/admin/exams';
		}

		// 获取记录
		$data = $query->skip($skip)->take($size)->get()->toArray();
		//设置页数跳转地址
		$pageObj->setUrl($url);
		// 设置分页数据
		$pageObj->setData($data);
		// 设置记录总数
		$pageObj->setTotalCount($totalCount);
		// 设置分页大小
		$pageObj->setSize($size);
		// 进行分页并返回
		return $pageObj->doPage();
	}
	/**
	* 逻辑增加
	* @author xww
	* @return sql result
	*/
	public function doCreate()
	{
		unset($_POST['id']);
		unset($_POST['coverPath']);
		unset($_POST['page']);
		// 上传文件
		if(!empty($_FILES['cover']) && $_FILES['cover']['error']==0){
			$ext = str_replace('image/', '', $_FILES['cover']['type']);
			$fpath = '/upload/product/'.microtime(true).".".$ext;
			$rs = move_uploaded_file($_FILES['cover']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$fpath);
			if($rs){
				$_POST['cover'] = $fpath;
			}
		}
		// 创建时间
		$_POST['create_time'] = time();
		// 修改时间
		$_POST['update_time'] = time();
		return $this->_model->insert($_POST);
	}
	/**
	* 返回对应id数据
	* @param  $id  string/int    会话id
	* @author xww
	* @return object
	*/
	public function read($id)
	{
		return $this->_model->where("is_deleted", '=', 0)->find($id);
	}
	/**
	* 逻辑修改
	* @author xww
	* @return sql result
	*/
	public function doUpdate()
	{
		
		try{

			$id = $_POST['recordId'];

			// var_dump($_POST);
			// die;

			DB::beginTransaction();

			// 先判断是否有跟这个考试相关的所有东西
			$hasData = $this->hasExamQuestionsOptions($id);

			if( $hasData ) {

				$rs = $this->softDeleteQuestionsOptions($id);
				if( !$rs ) {
					throw new \Exception("删除考试题目选项失败");
				}

			}

			$examQuestionModelObj = new \Module\Exam\VirgoModel\ExamQuestionModel;
			$examOptionModelObj = new \Module\Exam\VirgoModel\ExamOptionModel;
			
			// 遍历题目
			for ($i=0; $i < count($_POST['question-name']); $i++) { 
				
				// 判断这个题目是否存在
				$questionData = $this->getExamQuestion($id, $i+1);

				$answerArr = $_POST['question-' . ($i+1) . '-options'];

				sort($answerArr, SORT_NUMERIC);

				// 题目不存在
				if( empty($questionData) ) {
					
					// 新建题目
					$temp['exam_id'] = $id;
					$temp['content'] = $_POST['question-name'][$i];
					$temp['score'] = $_POST['question-score'][$i];
					$temp['question_type'] = $_POST['question-type'][$i];
					$temp['html_type'] = $_POST['question-type'][$i]==1||$_POST['question-type'][$i]==2? 1:2;
					$temp['question_index'] = $i+1;
					$temp['answer_str'] = implode(',', $answerArr);
					$temp['is_deleted'] = 0;
					$temp['create_time'] = time();
					$temp['update_time'] = time();

					$eqId =  $examQuestionModelObj->create($temp);
					unset($temp);

					if( !$eqId ) {
						throw new \Exception("新建题目失败");
					}

				} else {

					$eqId = $questionData['id'];

					// 更新
					$temp['content'] = $_POST['question-name'][$i];
					$temp['score'] = $_POST['question-score'][$i];
					$temp['question_type'] = $_POST['question-type'][$i];
					$temp['html_type'] = $_POST['question-type'][$i]==1||$_POST['question-type'][$i]==2? 1:2;
					$temp['answer_str'] = implode(',', $answerArr);
					$temp['is_deleted'] = 0;
					$temp['update_time'] = time();

					$rs = $examQuestionModelObj->partUpdate($eqId, $temp);
					unset($temp);

					if( !$rs ) {
						throw new \Exception("更新题目失败");
					}

				}

				// 获取选项
				$options = $_POST['hidden-question-' . ($i+1) . '-options'];
				for ($j=0; $j < count($options); $j++) { 
					// 判断这个题目的这个选项是否存在    不存在新建    存在更新
					$optionData = $examOptionModelObj->getExamQuestionOption($eqId, $j+1);

					if( empty($optionData) ) {
						// 新建

						$temp['eq_id'] = $eqId;
						$temp['option_content'] = $options[$j];
						$temp['option_index'] = $j+1;
						$temp['is_right'] = 0;
						$temp['is_deleted'] = 0;
						$temp['create_time'] = time();
						$temp['update_time'] = time();

						$eqoId =  $examOptionModelObj->create($temp);
						unset($temp);

						if( !$eqoId ) {
							throw new \Exception("新建选项失败");
						}

					} else {

						$eqoId = $optionData['id'];
						// 更新
						$temp['option_content'] = $options[$j];
						$temp['is_deleted'] = 0;
						$temp['update_time'] = time();

						$eqoId =  $examOptionModelObj->partUpdate($eqoId, $temp);
						unset($temp);

						if( !$eqoId ) {
							throw new \Exception("更新选项失败");
						}

					}

				}

			}

			DB::commit();
			return true;

		} catch(\Exception $e) {

			DB::rollback();
			// echo $e->getMessage();
			// die;
			return false;

		}

	}
	/**
	* 逻辑删除
	* @author xww
	* @return sql result
	*/
	public function delete()
	{
		$data['is_deleted'] = 1;
		if($_POST){$ids = $_POST['ids'];}
		else{$ids = [$_GET['id']];}
		return $this->_model->whereIn("id", $ids)->update($data);
	}

	/**
	* 根据类型与名称 获取对应记录
	* @author 	xww
	* @param   	string/int    	$typeId    	类型id
	* @param   	string    		$title    	标题
	* @return   array
	*/
	public function getSpecailExamFromTypeAndName($typeId, $title)
	{
		return $this->_model->where("is_deleted", 0)->where("type_id", $typeId)->where("title", $title)->get()->toArray();
	}

	/**
	* 创建记录
	* @author 	xww
	* @param 	array    $data
	* @return 	int
	*/
	public function create($data)
	{
		return $this->_model->insertGetId($data);
	}
	
	/**
	* 获取这个考试的所有题目与选项
	* @author 	xww
	* @param 	int/string 		$examId
	* @return 	array
	*/
	public function getExamQuetionsOptions( $examId )
	{
		
		$data = $this->_model->leftJoin("exam_question", "exam_question.exam_id", "=", "exam.id")
						    ->leftJoin("exam_option", "exam_option.eq_id", "=", "exam_question.id")
						    ->where("exam.is_deleted", 0)
						    ->where("exam_question.is_deleted", 0)
						    ->where("exam_option.is_deleted", 0)
						    ->where("exam.id", $examId)
						    ->orderBy("exam_question.question_index", "asc")
						    ->orderBy("exam_option.option_index", "asc")
						    ->orderBy("exam_option.id", "asc")
						    ->select("exam.title", "exam_question.id","exam_question.content", "exam_question.score", "exam_question.question_type", "exam_question.html_type", "exam_option.option_content", "exam_option.id as optionId", "exam_option.option_index", "exam_question.answer_str", "exam_question.question_index")
						    ->get()
						    ->toArray();

		if( empty($data) ) {
			return null;
		}

		$return = [];
		$return['title'] = $data[0]['title'];
		$return['data'] = [];
		$questionIndexArr = [];

		for ($i=0; $i < count($data); $i++) { 
			
			if( !in_array($data[$i]['id'], $questionIndexArr) ) {
				$questionIndexArr[] = $data[$i]['id'];			
			}

			$posArr = array_keys($questionIndexArr, $data[$i]['id']);

			if( !isset($return['data'][ $posArr[0] ]) ) {
				$return['data'][ $posArr[0] ] = [];
				$temp['id'] = $data[$i]['id'];
				$temp['content'] = $data[$i]['content'];
				$temp['score'] = $data[$i]['score'];
				$temp['html_type'] = $data[$i]['html_type'];
				$temp['question_type'] = $data[$i]['question_type'];
				$temp['question_index'] = $data[$i]['question_index'];
				$temp['answer_str'] = $data[$i]['answer_str'];
				$return['data'][ $posArr[0] ][] = $temp;
				unset($temp);
			}

			if( !isset($return['data'][ $posArr[0] ]['options']) ) {
				$return['data'][ $posArr[0] ]['options'] = [];
			}

			$temp['option_content'] = $data[$i]['option_content'];
			$temp['optionId'] = $data[$i]['optionId'];
			$temp['option_index'] = $data[$i]['option_index'];
			
			$answerArr = explode(",", $data[$i]['answer_str']);
			if( in_array($temp['option_index'], $answerArr) ) {
				$temp['is_right'] = true;
			} else {
				$temp['is_right'] = false;
			}

			$return['data'][ $posArr[0] ]['options'][] = $temp;
			unset($temp);


		}

		return $return;

	}

	/**
	* 获取这个考试的指定的题目与他的选项
	* @author 	xww
	* @param 	int/string 		$examId
	* @param 	int/string 		$questionId
	* @return 	array
	*/
	public function getExamQuetionOptions( $examId, $questionId)
	{
		
		$data = $this->_model->leftJoin("exam_question", "exam_question.exam_id", "=", "exam.id")
						    ->leftJoin("exam_option", "exam_option.eq_id", "=", "exam_question.id")
						    ->where("exam.is_deleted", 0)
						    ->where("exam_question.is_deleted", 0)
						    ->where("exam_option.is_deleted", 0)
						    ->where("exam.id", $examId)
						    ->where("exam_question.id", $questionId)
						    ->orderBy("exam_question.question_index", "asc")
						    ->orderBy("exam_option.option_index", "asc")
						    ->orderBy("exam_option.id", "asc")
						    ->select("exam_question.question_type", "exam_question.question_index", "exam_option.option_index")
						    ->get()
						    ->toArray();

		if( empty($data) ) {
			return null;
		}

		return $data;

	}

	/**
	* 获取这个考试的题目数量
	* @author 	xww
	* @param 	int/string 		$examId
	* @param 	int/string 		$questionId
	* @return 	array
	*/
	public function getExamQuetionCount( $examId)
	{
		return $this->_model->leftJoin("exam_question", "exam_question.exam_id", "=", "exam.id")
						    ->where("exam.is_deleted", 0)
						    ->where("exam_question.is_deleted", 0)
						    ->where("exam.id", $examId)
						    ->count();
	}

	/**
	* 获取前端可用列表
	* @author 	xww
	* @param 	int/string 	 	$take
	* @param 	int/string 	 	$skip
	* @param 	string 	 		$skip
	* @param 	int/string 	 	$search
	* @return 	array
	*/
	public function getLists($search=null, $skip=null, $take=null, $typeId=1)
	{
		
		$query = $this->_model->where("is_deleted", 0)
					 ->where("status_id", 0)
					 ->where("type_id", $typeId)
					 ->orderBy("create_time", "desc")
					 ->select("id", "title");

		if( !is_null($take) && !is_null($skip) ) {
			$query = $query->skip($skip)->take($take);
		}

		if( !is_null($search) ) {
			$query = $query->where("title", "like", "%" . $search . "%");
		}

		return $query->get()->toArray();

	}

	/**
	* 获取前端可用列表
	* @author 	xww
	* @param 	int/string 	 	$typeId
	* @param 	int/string 	 	$search
	* @return 	array
	*/
	public function getListsCount($search=null, $typeId=1)
	{
		
		$query = $this->_model->where("is_deleted", 0)
					 ->where("status_id", 0)
					 ->where("type_id", $typeId);

		if( !is_null($search) ) {
			$query = $query->where("title", "like", "%" . $search . "%");
		}

		return $query->count();
		
	}

	/**
	* 数据更新
	* @author 	xww
	* @param 	int/string    	$id
	* @param 	array 		 	$data
	* @return 	affect    		rows
	*/
	public function updateParts($id, $data)
	{
		return $this->_model->where("id", $id)->update($data);
	}

	/**
	* 列表--获取用户可见的可以考试的考试列表
	* @author 	xww
	* @param 	int/string 		$typeId
	* @param 	string 			$url
	* @return 	object
	*/
	public function testLists($typeId=null, $url=null)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;
		
		// set query 
		$query = $this->_model->where("is_deleted", '=', 0)->where("status_id", '=', 0)->orderBy("create_time", "desc");

		if( !is_null( $typeId ) ) {
			$query = $query->where("type_id", $typeId);			
		}

		// 标题过滤
		if(!empty($_GET['title'])){
			$_GET['title'] = trim($_GET['title']);
			$query = $query->where("title", 'like', '%'.$_GET['title'].'%');
			$pageObj->setPageQuery(['title'=>$_GET['title']]);
		}

		// 父菜单总记录数
		$totalCount = count($query->get()->toArray());
		//分页的take,size
		$size = 10;
		if(!empty($_GET['page'])){
			$page = (int)$_GET['page'];
			$skip = ($page-1)*$size;
		} else {
			$skip = 0;
		}

		if( is_null($url) ) {
			$url = '/admin/exams/testLists';
		}

		// 获取记录
		$data = $query->skip($skip)->take($size)->get()->toArray();
		//设置页数跳转地址
		$pageObj->setUrl($url);
		// 设置分页数据
		$pageObj->setData($data);
		// 设置记录总数
		$pageObj->setTotalCount($totalCount);
		// 设置分页大小
		$pageObj->setSize($size);
		// 进行分页并返回
		return $pageObj->doPage();
	}

	/**
	* 判断是否有这个考试相关的东西 
	* @author 	xww
	* @param 	int/string 		$examId
	* @return 	bool
	*/
	public function hasExamQuestionsOptions($examId)
	{
		$count = \Module\Exam\EloquentModel\ExamQuestion::leftJoin("exam_option", "exam_option.eq_id", "=", "exam_question.id")
						    ->where("exam_question.is_deleted", 0)
						    ->where("exam_option.is_deleted", 0)
						    ->where("exam_question.exam_id", $examId)
						    ->count();

		return $count? true:false;
	}

	/**
	* 删除这个考试相关的东西 
	* @author 	xww
	* @param 	int/string 		$examId
	* @return 	affect rows
	*/
	public function softDeleteQuestionsOptions($examId)
	{
		
		$temp['exam_option.is_deleted'] = 1;
		$temp['exam_option.update_time'] = time();
		$temp['exam_question.is_deleted'] = 1;
		$temp['exam_question.update_time'] = time();

		return \Module\Exam\EloquentModel\ExamQuestion::leftJoin("exam_option", "exam_option.eq_id", "=", "exam_question.id")
						    ->where("exam_question.is_deleted", 0)
						    ->where("exam_option.is_deleted", 0)
						    ->where("exam_question.exam_id", $examId)
						    ->update( $temp );
	}

	/**
	* 获取指定考试 指定题目数据 (不判断是否删除)
	* @author 	xww
	* @param 	int/string 		$examId
	* @param 	int/string 		$questionId
	* @return 	object
	*/
	public function getExamQuestion($examId, $questionId)
	{
		return \Module\Exam\EloquentModel\ExamQuestion::where("exam_id", $examId)
										  ->where("question_index", $questionId)
										  ->first();
	}

}
?>