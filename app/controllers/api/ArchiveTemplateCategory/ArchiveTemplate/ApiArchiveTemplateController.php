<?php
namespace VirgoApi\ArchiveTemplateCategory\ArchiveTemplate;
use VirgoApi;
class ApiArchiveTemplateController extends VirgoApi\ApiBaseController{

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
	* @SWG\Get(path="/api/v1/archiveTemplateCategory/archiveTemplate/lists", tags={"ArchiveTemplateCategory"}, 
	*  summary="通过档案分类id 获取档案列表列表",
	*  description="用户鉴权后 通过传入的分类id、page,size获取数据",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="classId", type="integer", required=true, in="query", description="模板分类id"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "CategoryTemplateLists", "status": { "code": "001", "message": "获取模板列表成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/CategoryTemplateLists"
	*   )
	*  )
	* )
	* 分类列表对象
	* @author 	xww
	* @return 	json
	*/
	public function lists()
	{

		try {

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 模板分类对象
			$model = new \VirgoModel\ArchiveCategoryToArchiveModel;

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
			$this->configValid('required',$this->_configs,['page', 'size', 'classId']);

			$classId = $this->_configs['classId'];

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$data = $model->getCategoryArchiveTemplateLists($classId, $skip, $size);
			$data = empty($data)? null:$data;

			for($i=0; $i < count($data); $i++) { 
				$data[$i]['url'] = "http://" . $_SERVER['HTTP_HOST'] . "/front/archiveTemplate/show?id=" . $data[$i]['id'] . "&userLogin=" . $this->_configs['user_login'] . "&accessToken=" . $this->_configs['access_token'];
			}

			$return = $this->functionObj->toAppJson($data, '001', '获取模板列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}
