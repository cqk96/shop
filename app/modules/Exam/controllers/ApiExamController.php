<?php
namespace Module\Exam\VirgoApi\Exam;
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
	* 获取启用的考试中心考题
	* @author 	xww
	* @return 	void
	*/
	public function lists()
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

			$user_login = $user[0]['user_login'];
			$access_token = $user[0]['access_token'];

			$model = new \Module\Exam\VirgoModel\ExamModel;

			$this->configValid('required',$this->_configs,['page', 'size']);
			$page = empty((int)$this->_configs['page'])? 1:(int)$this->_configs['page'];
			$curPage = $page;
			$page = --$page;
			$size = (int)$this->_configs['size'];

			$typeId = empty($this->_configs['classTypeId'])? 1:(int)$this->_configs['classTypeId'];

			$search = empty($this->_configs['search'])? null:$this->_configs['search'];

			$data = $model->getLists($search, $page*$size, $size, $typeId);
			
			// 组装url
			for ($i=0; $i < count($data); $i++) {
				$data[$i]['url'] = "/front/v1/exam/start?id=" . $data[$i]['id'] . "&user_login=" . $user_login . "&access_token=" . $access_token;
			}

			$data = empty($data)? null:$data;

			$dataCount = $model->getListsCount($search, $typeId);
			$totalPage = is_null($size)? 1:ceil( $dataCount / $size );
			$totalPage = is_null($data)? 0:$totalPage;

			// ['data'=>$data, 'totalPage'=>$totalPage]
			$return = $this->functionObj->toAppJson($data, '001', '获取考试列表数据成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* 改变考题状态
	* @author 	xww
	* @return 	void
	*/
	public function changeStatus()
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

			// 实例化
			$model = new \Module\Exam\VirgoModel\ExamModel;

			// 接收状态  1启用2关闭
			$this->configValid('required',$this->_configs,['id', 'statusId']);

			$id = $this->_configs['id'];

			$data = $model->read($id);

			if( empty($data) ) {
				throw new \Exception("查询不到数据", '006');
			}

			$title = '';
			if( $data['type_id']==1 ) {
				$title = "考试中心";
			} else if( $data['type_id']==3 ) {
				$title = "评估室";
			}

			$updateData['status_id'] = $this->_configs['statusId']==1? 0:1;
			$updateData['update_time'] = time();

			$rs = $model->updateParts($id, $updateData);

			if( !$rs ) {
				throw new \Exception('修改' . $title . '状态失败', '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '修改' . $title . '状态成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}
		
	}

	/**
	* 创建空白考试
	* @author 	xww
	* @return 	json
	*/
	public function create()
	{
		
		try{

			if(empty($_COOKIE['user_id'])) {
				throw new \Exception("请重新登录", '007');
			}

			// 实例化
			$model = new \Module\Exam\VirgoModel\ExamModel;

			// 接收状态  1启用2关闭
			$this->configValid('required',$this->_configs,['examName']);

			$name = $this->_configs['examName'];

			$hasData = $model->getSpecailExamFromTypeAndName(1, $name);

			if( !empty($hasData) ) {
				throw new \Exception("存在同名考试", '026');
			}

			$data['title'] = $name;
			$data['status_id'] = 1;
			$data['type_id'] = 1;
			$data['is_deleted'] = 0;
			$data['create_time'] = time();
			$data['update_time'] = time();

			$rs = $model->create($data);

			if( !$rs ) {
				throw new \Exception("创建考试失败", '005');
			}


			$return = $this->functionObj->toAppJson(null, '001', '创建考试成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

}