<?php
namespace VirgoApi\MainBody;
use VirgoApi;
class ApiMainBodyController extends VirgoApi\ApiBaseController{

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
	* @SWG\Get(path="/api/v1/mainBody/inAll", tags={"MainBody"}, 
	*  summary="获取所有主体",
	*  description="用户鉴权后 可通过传入分类id判定这个分类是否包含了这个主体",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=false, in="query", description="模板分类id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "ArchiveCategoryToMainBodyCheckedObj", "status": { "code": "001", "message": "获取所有主体成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/ArchiveCategoryToMainBodyCheckedObj"
	*   )
	*  )
	* )
	* 获取所有主体
	* @author 	xww
	* @return 	json
	*/
	public function inAll()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 分类关联主体对象
			$model = new \VirgoModel\ArchiveCategoryToMainBodyModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 4]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和查看数据权限", '070');
			}

			// 分类id
			$id = empty($this->_configs['id'])? null:$this->_configs['id'];

			$data = $model->getArchiveMainBodyInAll($id);
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', "获取所有主体成功", true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/mainBody/type/lists", tags={"MainBody"}, 
	*  summary="获取主体对应的档案分类列表",
	*  description="用户鉴权后 传入主体id、page和size获取对应的列表",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="mainBodyId", type="integer", required=true, in="query", description="主体id"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="条数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "MainbodyCategoryLists", "status": { "code": "001", "message": "获取主体对应的档案分类成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/MainbodyCategoryLists")
	*   )
	*  )
	* )
	* 获取主体对应的档案分类
	* @author 	xww
	* @return 	json
	*/
	public function typeLists()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 分类关联主体对象
			$model = new \VirgoModel\ArchiveCategoryToMainBodyModel;

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
			$this->configValid('required',$this->_configs,['page', 'size', 'mainBodyId']);

			$mainBodyId = $this->_configs['mainBodyId'];

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$data = $model->getMainbodyCategoryLists($mainBodyId, $skip, $size);
			$data = empty($data)? null:$data;

			$return = $this->functionObj->toAppJson($data, '001', "获取主体对应的档案分类成功", true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}