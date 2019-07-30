<?php
namespace VirgoApi\Farm\Acre;
use VirgoApi;
class ApiAcreController extends VirgoApi\ApiBaseController{

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
	* @SWG\Get(path="/api/v1/farm/acre/all", tags={"Farm"}, 
	*  summary="获取农场下 所有地块",
	*  description="用户鉴权后 通过传入的农场id获取所有地块",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="farmId", type="integer", required=true, in="query", description="农场id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "AllInAcre", "status": { "code": "001", "message": "获取全部地块成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/AllInAcre"
	*   )
	*  )
	* )
	* 获取农场下面所有地块
	* @author 	xww
	* @return 	json
	*/
	public function all()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\AcreModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 4]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和查看数据权限", '070');
			}

			//验证
			$this->configValid('required',$this->_configs,['farmId']);

			$farmId = $this->_configs['farmId'];

			// 获取用户负责的地块

			// 获取用户负责的工作过的地块
			$areaRelManagerModel = new \VirgoModel\AreaRelManagerModel;

			$acresArr = $areaRelManagerModel->getUserWorksAcre( $uid );			

			$acreIds = [];
			for ($i=0; $i < count($acresArr); $i++) { 
				$acreIds[] = $acresArr[$i]['id'];
			}

			// 查询数据
			$data = $model->getFarmAcres($farmId);
			$data = empty($data)? null:$data;

			for ($i=0; $i < count($data); $i++) {

				$data[$i]['checked'] = false;
				if( in_array($data[$i]['id'], $acreIds) ) {
					$data[$i]['checked'] = true;
				}

				unset($data[$i]['farm_id']);
				unset($data[$i]['manager_id']);
				unset($data[$i]['acreage']);
				unset($data[$i]['area_amount']);
				unset($data[$i]['is_deleted']);
				unset($data[$i]['create_time']);
				unset($data[$i]['managerName']);
				unset($data[$i]['update_time']);

			}

			$return = $this->functionObj->toAppJson($data, '001', '获取全部地块成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/farm/acre/managerInall", tags={"Farm"}, 
	*  summary="获取农场下 所有地块--精确到地块负责人",
	*  description="用户鉴权后 通过传入的农场id获取所有地块",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="farmId", type="integer", required=true, in="query", description="农场id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "AllInAcre", "status": { "code": "001", "message": "获取全部地块成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/AllInAcre"
	*   )
	*  )
	* )
	* 获取农场下面所有地块
	* @author 	xww
	* @return 	json
	*/
	public function managerInAll()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\AcreModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 4]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和查看数据权限", '070');
			}

			//验证
			$this->configValid('required',$this->_configs,['farmId']);

			$farmId = $this->_configs['farmId'];

			// 获取用户负责的地块
			$acresArr = $model->getMyAcres($uid, $farmId);

			$acreIds = [];
			for ($i=0; $i < count($acresArr); $i++) { 
				$acreIds[] = $acresArr[$i]['id'];
			}

			// 查询数据
			$data = $model->getFarmAcres($farmId);
			$data = empty($data)? null:$data;

			for ($i=0; $i < count($data); $i++) {

				$data[$i]['checked'] = false;
				if( in_array($data[$i]['id'], $acreIds) ) {
					$data[$i]['checked'] = true;
				}

				unset($data[$i]['farm_id']);
				unset($data[$i]['manager_id']);
				unset($data[$i]['acreage']);
				unset($data[$i]['area_amount']);
				unset($data[$i]['is_deleted']);
				unset($data[$i]['create_time']);
				unset($data[$i]['managerName']);
				unset($data[$i]['update_time']);

			}

			$return = $this->functionObj->toAppJson($data, '001', '获取全部地块成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}