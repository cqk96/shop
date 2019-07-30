<?php
namespace VirgoApi\NewsClass\News;
use VirgoApi;
class ApiNewsController extends VirgoApi\ApiBaseController
{

	/**
	* api参数数组
	* @var array
	*/
	private $_configs;

	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
		$this->model = new \VirgoModel\NewsModel;
		$this->_configs = parent::change();
	}

	/**
	* 获取更多特定分类文章
	* @author 	xww
	* @return 	json
	*/ 
	public function more()
	{
		
		try{

			// 报价id 合同id
			$this->configValid('required',$this->_configs,['id', 'page', 'size']);

			if(empty($_COOKIE['user_id'])) { throw new \Exception("重新登录", '002'); }

			$page = empty((int)$this->_configs['page'])? 1:(int)$this->_configs['page'];
			$curPage = $page;
			$page = --$page;
			$size = (int)$this->_configs['size'];

			$skip = $page*$size;

			$dataObj = $this->model->getFrontLists($this->_configs['id'], $size, $skip);
			$dataObj['curPage'] = $curPage;

			for ($i=0; $i < count($dataObj['data']); $i++) { 
				$dataObj['data'][$i]['createTime'] = substr($dataObj['data'][$i]['created_at'], 0, 10);
			}

			$return = $this->functionObj->toAppJson($dataObj, '001', '更新成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* 根据分类id  获取文章列表
	* @author 	xww
	* @return 	string/object 	json 
	*/
	public function all()
	{
		
		try{

			// $user = $this->getUserApi($this->_configs);
			$this->configValid('required',$this->_configs,['id', 'page', 'size']);

			$search = empty( $this->_configs['search'] )? null:trim( $this->_configs['search'] );

			$page = empty((int)$this->_configs['page'])? 1:(int)$this->_configs['page'];
			$curPage = $page;
			$page = --$page;
			$size = (int)$this->_configs['size'];

			$needs = ['id', 'title', 'cover', 'origin', 'description', 'url'];

			$skip = $page*$size;

			$data = $this->model->getListFromClassIdVer2($this->_configs['id'], $skip, $size, "desc", $needs, $search);

			// 组装url
			for ($i=0; $i < count($data); $i++) { 
				$data[$i]['showUrl'] = 	empty($data[$i]['url'])? "http://".$_SERVER['HTTP_HOST']."/front/showNews?id=".$data[$i]['id']:$data[$i]['url'];
				unset( $data[$i]['url'] );
			}

			$data = $this->dataToNull($data);
			$data = empty($data)? null:$data;

			$dataCount = $this->model->getListFromClassIdVer2Count( $this->_configs['id'], $search );
			$totalPage = is_null($size)? 1:ceil( $dataCount / $size );
			$totalPage = is_null($data)? 0:$totalPage;

			$return = $this->functionObj->toAppJson(['data'=>$data, 'totalPage'=>$totalPage], '001', '获取数据成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/NewsClass/news/classNamesLists", tags={"NewsClass"}, 
	*  summary="app 根据分类名获取对应列表",
	*  description="传入页数 条数 和分类名称 获取列表",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="条数"),
	*  @SWG\Parameter(name="className", type="string", required=true, in="query", description="分类名称"),
	*  @SWG\Response(
	*   response=200,
	*   description="获取成功",
	*   examples={"application/json": { "data": "AppNewsLists", "status": { "code": "001", "message": "获取文章列表成功", "success": true } } },
	*   @SWG\Schema(
	*     type="array",
	*     @SWG\Items(ref="#/definitions/AppNewsLists")
	*   )
	*  )
	* )
	* 根据分类名获取对应列表
	* @author 	xww
	* @return 	json
	*/
	public function classNamseLists()
	{

		try{

			//验证
			$this->configValid('required',$this->_configs,['page', 'size', 'className']);

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$className = $this->_configs['className'];

			$model = new \VirgoModel\NewsModel;

			$data = $model->getListsByClassName( $className );

			$data = empty($data)? null:$data;

			for ($i=0; $i < count($data); $i++) { 
				$data[$i]['url'] = "http://" . $_SERVER['HTTP_HOST'] . "/front/api/v1/NewsClass/news/show?id=" . $data[$i]['id'];
			}			

			$return = $this->functionObj->toAppJson($data, '001', '获取文章列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

}