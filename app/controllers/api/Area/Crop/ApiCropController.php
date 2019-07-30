<?php
namespace VirgoApi\Area\Crop;
use Illuminate\Database\Capsule\Manager as DB;
use VirgoApi;
class ApiCropController extends VirgoApi\ApiBaseController{

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
	* @SWG\Get(path="/api/v1/area/crop/lists", tags={"Area"}, 
	*  summary="获取指定片区下作物列表",
	*  description="用户鉴权后 通过传入的片区id、page,size获取数据列表",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="areaId", type="integer", required=true, in="query", description="片区id"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Parameter(name="search", type="string", required=false, in="query", description="编码查询"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "AreaCropLists", "status": { "code": "001", "message": "获取作物列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="array",
	*    @SWG\Items(ref="#/definitions/AreaCropLists")
	*   )
	*  )
	* )
	*/
	public function lists()
	{
		
		try {

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 作物对象
			$model = new \VirgoModel\CropModel;

			// 片区对象
			$areaModel = new \VirgoModel\AreaModel;

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
			$this->configValid('required',$this->_configs,['page', 'size', 'areaId']);

			$areaId = $this->_configs['areaId'];
			$search = empty($this->_configs['search'])? null:$this->_configs['search'];

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$data = $model->getAreaCropLists($areaId, $skip, $size, $search);
			$data = empty($data)? null:$data;

			$area = $areaModel->singleTonDetail( $areaId );

			$typeName = empty($area)? '':$area['cropTypeName'];
			for ($i=0; $i < count($data); $i++) { 
				$data[$i]['cropTypeName'] = $typeName;
			}

			$return = $this->functionObj->toAppJson($data, '001', '获取作物列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}
	
}