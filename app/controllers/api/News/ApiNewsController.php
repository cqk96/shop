<?php
namespace VirgoApi\News;
class ApiNewsController extends \VirgoApi\ApiBaseController
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

			$page = empty((int)$this->_configs['page'])? 1:(int)$this->_configs['page'];
			$curPage = $page;
			$page = --$page;
			$size = (int)$this->_configs['size'];

			$needs = ['id', 'title', 'cover', 'origin', 'description', 'url'];

			$skip = $page*$size;

			$data = $this->model->getListFromClassIdVer2($this->_configs['id'], $skip, $size, "desc", $needs);

			// 组装url
			for ($i=0; $i < count($data); $i++) { 
				$data[$i]['showUrl'] = 	empty($data[$i]['url'])? "http://".$_SERVER['HTTP_HOST']."/front/showNews?id=".$data[$i]['id']:$data[$i]['url'];
			}

			$data = $this->dataToNull($data);
			$data = empty($data)? null:$data;
			$return = $this->functionObj->toAppJson($data, '001', '获取数据成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* 将匹配到关键字的文章 搜索数量+1
	* @author 	xww
	* @return 	json
	*/
	public function createSearchCount()
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

			$this->configValid('required',$this->_configs,['searchStr', "classId"]);

			$classId = $this->_configs['classId'];

			$newsModelObj = new \VirgoModel\NewsModel;
			$rs = $newsModelObj->createSearchCount( $this->_configs['searchStr'], $classId);

			if(!$rs) {
				throw new \Exception("修改搜索次数失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '请求结束', false);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/news/lists", tags={"News"}, 
	*  summary="获取文章列表",
	*  description="用户鉴权后 通过传入的page,size获取分页对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="page", type="integer", required=true, in="query", description="分页页数"),
	*  @SWG\Parameter(name="size", type="integer", required=true, in="query", description="分页条数"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "NewsListsObj",  "code": "001", "message": "获取农场列表成功", "totalCount": 3 } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/NewsListsObj"
	*   )
	*  )
	* )
	* 获取文章列表
	* @author 	xxw
	* @return 	json
	*/
	public function lists()
	{

		try {

			//验证 
			$user = $this->getUserApi($this->_configs, 1);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 农场对象
			$model = new \VirgoModel\NewsModel;

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
			$this->configValid('required',$this->_configs,['page', 'size']);

			// 分页
			$page = empty( (int)$this->_configs['page'] ) || (int)$this->_configs['page']< 1? 1:(int)$this->_configs['page'];
			$size = empty( (int)$this->_configs['size'] ) || (int)$this->_configs['size']< 1? 5:(int)$this->_configs['size']; 
			$page -= 1;
			$skip = $page*$size;

			$pageObj = $model->getListsObject($skip, $size);

			$data = [];

			$data = empty($pageObj->data)? null:$pageObj->data;
			// $data['totalPage'] = intval( ceil($pageObj->totalCount/$pageObj->size) );
			// $data['currentPage'] = intval( $pageObj->current_page );
			$totalCount = $pageObj->totalCount;

			$return = $this->functionObj->toLayuiJson($data, '001', '获取文章列表成功', $totalCount);

		} catch(\Exception $e) {
			$return = $this->functionObj->toLayuiJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), 0);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/news/create", tags={"News"}, 
	*  summary="创建新闻",
	*  description="用户鉴权后 通过传入的文章名、分类id、内容、封面和其他可选参数 来创建新闻",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="class_id", type="integer", required=true, in="formData", description="分类id"),
	*  @SWG\Parameter(name="title", type="string", required=true, in="formData", description="文章标题"),
	*  @SWG\Parameter(name="cover", type="string", required=true, in="formData", description="封面地址"),
	*  @SWG\Parameter(name="content", type="string", required=true, in="formData", description="文章编辑器内容"),
	*  @SWG\Parameter(name="flags", type="string", required=false, in="formData", description="标签"),
	*  @SWG\Parameter(name="pics", type="string", required=false, in="formData", description="组图"),
	*  @SWG\Parameter(name="pass", type="integer", required=false, in="formData", description="是否审核 默认0否1是"),
	*  @SWG\Parameter(name="top", type="integer", required=false, in="formData", description="是否置顶 0否1是"),
	*  @SWG\Parameter(name="content", type="string", required=false, in="formData", description="文章编辑器内容"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "创建文章成功", "success": true } } }
	*  )
	* )
	* 增加
	* @author 	xww
	* @return 	json
	*/
	public function create()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\NewsModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 2]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和增加数据权限", '070');
			}

			//验证
			$this->configValid('required',$this->_configs,['class_id', 'title', "cover", "content"]);

			$class_id = $this->_configs['class_id'];
			$title = $this->_configs['title'];
			$cover = $this->_configs['cover'];
			$content = $this->_configs['content'];
			$description = empty($this->_configs['description'])? '':$this->_configs['description'];
			$keywords = empty($this->_configs['keywords'])? '':$this->_configs['keywords'];

			$insertData['class_id'] = $class_id;
			$insertData['title'] = $title;
			$insertData['cover'] = $cover;
			$insertData['author'] = $uid;
			$insertData['content'] = $content;
			$insertData['description'] = $description;
			$insertData['keywords'] = $keywords;
			$insertData['created_at'] = time();
			$insertData['updated_at'] = time();

			if( !empty($this->_configs['flags']) ) {
				$insertData['flags'] = $this->_configs['flags'];
			}

			if( !empty($this->_configs['pics']) ) {
				$insertData['pics'] = $this->_configs['pics'];
			}

			if( !empty($this->_configs['pass']) ) {
				$insertData['pass'] = $this->_configs['pass'];
			}

			if( !empty($this->_configs['top']) ) {
				$insertData['top'] = $this->_configs['top'];
			}

			if( !empty($this->_configs['url']) ) {
				$insertData['url'] = $this->_configs['url'];
			}

			if( !empty($this->_configs['origin']) ) {
				$insertData['origin'] = $this->_configs['origin'];
			}

			$rs = $model->create( $insertData );

			if( !$rs ) {
				throw new \Exception("添加文章失败", '005');
			}

			$return = $this->functionObj->toAppJson($data, '001', '创建文章成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/news/delete", tags={"News"}, 
	*  summary="删除文章",
	*  description="用户鉴权后 通过传入的文章ids 进行文章删除",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="ids", type="string", required=true, in="formData", description="文章ids"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "删除文章成功", "success": true } } }
	*  )
	* )
	* 删除文章
	*/
	public function delete()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\NewsModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 3]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和删除数据权限", '070');
			}

			//验证
			$this->configValid('required',$this->_configs,['ids']);

			$idsArr = explode(",", $this->_configs['ids']);

			$ids = [];
			for ($i=0; $i < count($idsArr); $i++) { 
				
				$singleId = (int)$idsArr[$i];
				if( empty($singleId) ) {
					continue;
				}

				$ids[] = $singleId;

			}

			if( empty($ids) ) {
				throw new \Exception("Error Processing Request", '014');
			}

			$updateData['status'] = 1;
			$updateData['updated_at'] = time();

			$rs = $model->multiplePartUpdate($ids, $updateData);

			if( !$rs ) {
				throw new \Exception("删除文章失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '删除文章成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Get(path="/api/v1/news/detail", tags={"News"}, 
	*  summary="文章详情",
	*  description="用户鉴权后 通过传入的id获取文章对象",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="query", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="query", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="query", description="文章记录id"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={"application/json": { "data": "News", "status": { "code": "001", "message": "查询文章情况成功", "success": true } } },
	*   @SWG\Schema(
	*    type="object",
	*    ref="#/definitions/News"
	*   )
	*  )
	* )
	* 农场详情
	* @author 	xww
	* @return 	json
	*/
	public function read()
	{
		
		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\NewsModel;

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
			$this->configValid('required',$this->_configs,['id']);

			$id = $this->_configs['id'];

			// 查询数据
			$dataObj = $model->readSingleTon($id);

			if( empty($dataObj) ) {
				throw new \Exception("数据可能不存在或已删除", '006');	
			}

			$data = $dataObj->toArray();

			$data['content'] = html_entity_decode( $data['content'] );
			$data['created_at'] = empty($data['created_at'])? 0:strtotime($data['created_at']);
			$data['updated_at'] = empty($data['created_at'])? 0:strtotime($data['updated_at']);

			$return = $this->functionObj->toAppJson($data, '001', '查询文章情况成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

	/**
	* @SWG\Post(path="/api/v1/news/update", tags={"News"}, 
	*  summary="修改新闻",
	*  description="用户鉴权后 通过传入的记录id, 文章名、分类id、内容、和其他可选参数 来更新新闻",
	*  produces={"application/json"},
	*  @SWG\Parameter(name="user_login", type="string", required=true, in="formData", description="账号"),
	*  @SWG\Parameter(name="access_token", type="string", required=true, in="formData", description="令牌"),
	*  @SWG\Parameter(name="id", type="integer", required=true, in="formData", description="记录id"),
	*  @SWG\Parameter(name="class_id", type="integer", required=true, in="formData", description="分类id"),
	*  @SWG\Parameter(name="title", type="string", required=true, in="formData", description="文章标题"),
	*  @SWG\Parameter(name="content", type="string", required=true, in="formData", description="文章编辑器内容"),
	*  @SWG\Parameter(name="cover", type="string", required=false, in="formData", description="封面地址"),
	*  @SWG\Parameter(name="flags", type="string", required=false, in="formData", description="标签"),
	*  @SWG\Parameter(name="pics", type="string", required=false, in="formData", description="组图"),
	*  @SWG\Parameter(name="pass", type="integer", required=false, in="formData", description="是否审核 默认0否1是"),
	*  @SWG\Parameter(name="top", type="integer", required=false, in="formData", description="是否置顶 0否1是"),
	*  @SWG\Parameter(name="content", type="string", required=false, in="formData", description="文章编辑器内容"),
	*  @SWG\Response(
	*   response=200,
	*   description="操作成功",
	*   examples={ "application/json": { "data": null, "status": { "code": "001", "message": "修改文章成功", "success": true } } }
	*  )
	* )
	* 修改文章
	* @author 	xww
	* @return  	json
	*/
	public function update()
	{

		try{

			//验证 
			$user = $this->getUserApi($this->_configs);

			$uid = $user[0]['id'];

			// 实例化对象
			$userModel = new \VirgoModel\UserModel;

			// 对象
			$model = new \VirgoModel\NewsModel;

			/**
			* 鉴权
			*/
			// 是否有权限
			$hasPrivilige = $userModel->hasBackCreateOperatePrivilige($uid, [1, 5]);

			if( !$hasPrivilige ) {
				// 没有权限提示
				throw new \Exception("没有登录权限和修改数据权限", '070');
			}

			//验证
			$this->configValid('required',$this->_configs,['id', 'class_id', 'title', 'content']);

			$id = $this->_configs['id'];
			$class_id = $this->_configs['class_id'];
			$title = $this->_configs['title'];
			$content = $this->_configs['content'];

			$updateData['class_id'] = $class_id;
			$updateData['title'] = $title;
			$updateData['content'] = $content;
			$updateData['updated_at'] = time();

			if( !empty($this->_configs['cover']) ) {
				$updateData['cover'] = $this->_configs['cover'];
			}

			if( !empty($this->_configs['description']) ) {
				$updateData['description'] = $this->_configs['description'];
			}

			if( !empty($this->_configs['keywords']) ) {
				$updateData['keywords'] = $this->_configs['keywords'];
			}

			if( !empty($this->_configs['flags']) ) {
				$updateData['flags'] = $this->_configs['flags'];
			}

			if( !empty($this->_configs['pics']) ) {
				$updateData['pics'] = $this->_configs['pics'];
			}

			if( !empty($this->_configs['pass']) ) {
				$updateData['pass'] = $this->_configs['pass'];
			}

			if( !empty($this->_configs['top']) ) {
				$updateData['top'] = $this->_configs['top'];
			}

			if( !empty($this->_configs['url']) ) {
				$updateData['url'] = $this->_configs['url'];
			}

			if( !empty($this->_configs['origin']) ) {
				$updateData['origin'] = $this->_configs['origin'];
			}

			// 查询数据
			$data = $model->readSingleTon($id);

			if( empty($data) ) {
				throw new \Exception("数据可能不存在或已删除", '006');	
			}

			$rs = $model->partUpdate($id, $updateData);

			if( !$rs ) {
				throw new \Exception("修改农场失败", '003');
			}

			$return = $this->functionObj->toAppJson(null, '001', '修改文章成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			$this->responseResult($return);
		}

	}

}