<?php
namespace Module\Exam\VirgoApi\User\Exam;
use Illuminate\Database\Capsule\Manager as DB;
use VirgoApi;
class ApiExamController extends VirgoApi\ApiBaseController
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
	* 返回用户考试结果列表
	* @author　	xww
	* @return 	json
	*/
	public function resultLists()
	{
		
		try{

			if(empty($_COOKIE['user_id'])) {
				
				//获取用户
				$user = $this->getUserApi($this->_configs);	

			} else {
				$userObj = new \Module\Exam\VirgoModel\UserModel;
				//获取用户
				$id = $_COOKIE['user_id'];
				$record = $userObj->readSingleTon($id);
				if(empty($record)) {
					throw new \Exception("用户不存在", '006');
				}
				$user[] = $record->toArray();
			}

			$this->configValid('required',$this->_configs,['page', 'size']);

			// 实例化对象
			$model = new \Module\Exam\VirgoModel\AnswerExamModel;

			$page = empty((int)$this->_configs['page'])? 1:(int)$this->_configs['page'];
			$curPage = $page;
			$page = --$page;
			$size = (int)$this->_configs['size'];

			$skip = $page*$size;

			$data = $model->getUserExamResultLists($user[0]['id'], $skip, $size);

			for ($i=0; $i < count($data); $i++) { 
				$data[$i]['createTime'] = empty($data[$i]['createTime'])? '':date("Y-m-d", $data[$i]['createTime']);
			}

			$data = empty($data)? null:$data;

			$dataCount = $model->getUserExamResultListsCount($user[0]['id']);
			$totalPage = is_null($size)? 1:ceil( $dataCount / $size );
			$totalPage = is_null($data)? 0:$totalPage;

			$return = $this->functionObj->toAppJson(['data'=>$data, 'totalPage'=>$totalPage], '001', '获取数据成功', true);

		}catch(\Exception $e){
			$return = $this->functionObj->toAppJson(null, $e->getCode(), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* 返回用户评估室结果列表
	* @author 	xww
	* @return 	json
	*/
	public function evaluationRoomResultLists()
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

			$this->configValid('required',$this->_configs,['page', 'size']);

			$examId = empty($this->_configs['examId'])? null:$this->_configs['examId'];

			// 实例化对象
			$model = new \Module\Exam\VirgoModel\AnswerExamModel;

			$page = empty((int)$this->_configs['page'])? 1:(int)$this->_configs['page'];
			$curPage = $page;
			$page = --$page;
			$size = (int)$this->_configs['size'];

			$skip = $page*$size;

			$data = $model->getUserEvaluationRoomResultLists($user[0]['id'], $skip, $size, $examId);

			for ($i=0; $i < count($data); $i++) { 
				$data[$i]['createTime'] = empty($data[$i]['createTime'])? '':date("Y-m-d", $data[$i]['createTime']);
			}

			$data = empty($data)? null:$data;

			$dataCount = $model->getUserEvaluationRoomResultListsCount($user[0]['id'], $examId);
			$totalPage = is_null($size)? 1:ceil( $dataCount / $size );
			$totalPage = is_null($data)? 0:$totalPage;

			$return = $this->functionObj->toAppJson(['data'=>$data, 'totalPage'=>$totalPage], '001', '获取数据成功', true);

		}catch(\Exception $e){
			$return = $this->functionObj->toAppJson(null, $e->getCode(), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

}