<?php
namespace VirgoApi\Statistics;
use Illuminate\Database\Capsule\Manager as DB;
use VirgoApi;
class ApiStatisticsController extends VirgoApi\ApiBaseController{

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
	* @SWG\Get(path="/api/v1/Statistics/allgoods", tags={"Statistics"}, 
	*  summary="查看产品总数",
	*  description="产品总数",
	*  produces={"application/json"},
	*  
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "获取产品总数成功", "success": true } } }
	*  )
	* )
	* 产品总数
	* @author 	xww
	* @return 	json
	*/



		public function allgoods()
			{
		
		try{

		

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;
			// 实例化对象
			$StatisticsModel = new \VirgoModel\StatisticsModel;
			$codModel = new \VirgoModel\CodModel;

		

			/*获取全部商品*/
			$data = $StatisticsModel->getallgoods();

			$return = $this->functionObj->toAppJson($data, '001', '获取产品总数成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}


		 /**
	* @SWG\Get(path="/api/v1/Statistics/allproduct", tags={"Statistics"}, 
	*  summary="查看订单总数",
	*  description="订单总数",
	*  produces={"application/json"},
	*  
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "获取订单总数成功", "success": true } } }
	*  )
	* )
	* 订单总数
	* @author 	xww
	* @return 	json
	*/

		public function allproduct()
			{
		
		try{

			

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;
			// 实例化对象
			$StatisticsModel = new \VirgoModel\StatisticsModel;
			$codModel = new \VirgoModel\CodModel;
	$this->configValid('required',$this->_configs,['uid']);

			$uid = $this->_configs['uid'];
			/*获取全部商品*/
			$data = $StatisticsModel->allproduct($uid);
			$return = $this->functionObj->toAppJson($data, '001', '获取产品总数成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

 /**
	* @SWG\Get(path="/api/v1/Statistics/todayproduct", tags={"Statistics"}, 
	*  summary="获取今日订单",
	*  description="今日订单",
	*  produces={"application/json"},
	*  
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "获取今日订单成功", "success": true } } }
	*  )
	* )
	* 今日订单
	* @author 	xww
	* @return 	json
	*/

	public function todayproductorder()
			{
		
		try{

			

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;
			// 实例化对象
			$StatisticsModel = new \VirgoModel\StatisticsModel;
			$codModel = new \VirgoModel\CodModel;

			/*获取全部商品*/
			$data = $StatisticsModel->todayproductorder();

			$return = $this->functionObj->toAppJson($data, '001', '获取今日订单成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

 /**
	* @SWG\Get(path="/api/v1/Statistics/todaygoods", tags={"Statistics"}, 
	*  summary="获取今日商品",
	*  description="今日商品",
	*  produces={"application/json"},
	*  
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "获取今日商品成功", "success": true } } }
	*  )
	* )
	* 今日商品
	* @author 	xww
	* @return 	json
	*/
	public function todaygoods()
			{
		
		try{

			

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;
			// 实例化对象
			$StatisticsModel = new \VirgoModel\StatisticsModel;
			$codModel = new \VirgoModel\CodModel;

			/*获取全部商品*/
			$data = $StatisticsModel->todaygoods();

			$return = $this->functionObj->toAppJson($data, '001', '获取今日商品成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

/**
	* @SWG\Get(path="/api/v1/Statistics/Turnover", tags={"Statistics"}, 
	*  summary="获取今日营业",
	*  description="获取今日营业",
	*  produces={"application/json"},
	*  
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "获取今日营业成功", "success": true } } }
	*  )
	* )
	* 统计汇总
	* @author 	xww
	* @return 	json
	*/


	public function Turnover()
			{
		
		try{

			

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;
			// 实例化对象
			$StatisticsModel = new \VirgoModel\StatisticsModel;
			$codModel = new \VirgoModel\CodModel;

		$this->configValid('required',$this->_configs,['uid']);

			$uid = $this->_configs['uid'];
			/*获取全部商品*/
			$data = $StatisticsModel->Turnover($uid);

			$return = $this->functionObj->toAppJson($data, '001', '获取今日营业额成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}


 /**
	* @SWG\Get(path="/api/v1/Statistics/gather", tags={"Statistics"}, 
	*  summary="统计汇总",
	*  description="统计汇总",
	*  produces={"application/json"},
	*  
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "获取统计汇总成功", "success": true } } }
	*  )
	* )
	* 统计汇总
	* @author 	xww
	* @return 	json
	*/
	public function gather()
	{
		try{
		// 实例化对象
			$userModel = new \VirgoModel\UserModel;
			// 实例化对象
			$StatisticsModel = new \VirgoModel\StatisticsModel;
			$codModel = new \VirgoModel\CodModel;
	$this->configValid('required',$this->_configs,['uid']);

			$uid = $this->_configs['uid'];
			/*获取全部商品*/
			$productordercount = $StatisticsModel->todayproductorder($uid);
			$goodscount= $StatisticsModel->todaygoods($uid);
			$allgoods= $StatisticsModel->getallgoods($uid);
			$data['productordercount'] =$productordercount;
			$data['goodscount'] =$goodscount;
			$data['allgoods'] =$allgoods;

			$return = $this->functionObj->toAppJson($data, '001', '获取成功', true);
		
		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}
 }