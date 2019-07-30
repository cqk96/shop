<?php
/**
*  仿gii
*  减少冗余操作
* @author  xww
* @version 0.1.0
*/
namespace VirgoBack;
use Illuminate\Database\Capsule\Manager as DB;
class AdminGiiController extends AdminBaseController
{
	
	/**
	* @var prifix
	*/ 
	public $_prifix = 'comp_';

	public function __construct()
	{
		// @todo  验证用户登录

		// @todo  spl_autoload 多文件
		spl_autoload_register(array($this,'loader'));
	}

	/**
	* 添加gii传参
	* render the page
	* @author xww
	* @return void
	*/
	public function create()
	{

		//添加页面
		require_once dirname(__FILE__)."/../../views/admin/adminGii/index.php";

	}

	/**
	* 逻辑处理创建
	* 创建对应的 EloquentModel VirgoModel admin_routes api_routes views
	* @author xww
	* @return void
	*/
	public function doCreate()
	{

		// 防止超时
		set_time_limit(0);

		// 传递表名
		$tableName = $_POST['table_name'];

		// 数据库表名
		$totalTableName = $this->_prifix.$tableName;

		// 获取表名字数组
		$tableNameArr = explode('_', $tableName);

		// e's model名字
		$model = $this->getModelName($tableNameArr);

		// vm's model名字
		$vmModel = $model."Model";

		// control 名字
		$adminControl = 'Admin'.$model.'Controller';

		// view's directory 名字
		$viewDirectory = 'admin'.$model;

		// admin header url 
		$adminUrl = $this->getHeaderUrl($tableNameArr);

		// admin c,r,u,d and do crud url
		$adminCreateUrl = '/admin/'.$adminUrl.'/create';
		$adminDoCreateUrl = '/admin/'.$adminUrl.'/doCreate';
		$adminUpdateUrl = '/admin/'.$adminUrl.'/update';
		$adminDoUpdateUrl = '/admin/'.$adminUrl.'/doUpdate';
		$adminReadUrl = '/admin/'.$adminUrl.'/read';
		$adminDeleteUrl = '/admin/'.$adminUrl.'/doDelete';
		$adminDestroyUrl = '/admin/'.$adminUrl.'/destroy';

		// 创建数据库模型
		if(!file_exists($_SERVER['DOCUMENT_ROOT']."/../app/models/$model".".php")){
			$eloquentModelStr = "<?php\r\n/**\r\n* $model Model\r\n*/\r\nnamespace EloquentModel;\r\nclass $model extends \Illuminate\Database\Eloquent\Model\r\n{\r\n\tprotected \$table = '$tableName';
									\r\n\tpublic \$timestamps = false;\r\n}";
			// 创建模型
			file_put_contents($_SERVER['DOCUMENT_ROOT']."/../app/models/$model".".php", $eloquentModelStr);
		}

		//创建model模型
		if(!file_exists($_SERVER['DOCUMENT_ROOT']."/../app/models/VirgoModel/$vmModel".".php")){
			$virgoModelStr = "<?php\r\n/**\r\n* 专区 model  逻辑层\r\n* @author  xww <5648*****@qq.com>\r\n* @version 1.0.0\r\n*/\r\nnamespace VirgoModel;\r\nclass ".$vmModel." extends BaseModel {\r\n\t/* @param object  reflect this model's  eloquent model object */\r\n\tprivate \$_model;\r\n\r\n\t// 初始化\r\n\tpublic function __construct()\r\n\t{\r\n\t\t\$this->_model = new \\EloquentModel\\".$model."; \r\n\t}\r\n\r\n\t/**\r\n\t* 列表\r\n\t* @author xww\r\n\t*@return object\r\n\t*/\r\n\tpublic function lists()\r\n\t{\r\n\r\n\t\t// 分页对象\r\n\t\t\$pageObj = new \VirgoUtil\Page2;\r\n\t\t// set query \r\n\t\t\$query = \$this->_model->where(\"is_deleted\", '=', 0)->orderBy(\"create_time\", \"desc\");\r\n\r\n\t\t// 标题过滤\r\n\t\tif(!empty(\$_GET['title'])){\r\n\t\t\t\$_GET['title'] = trim(\$_GET['title']);\r\n\t\t\t\$query = \$query->where(\"title\", 'like', '%'.\$_GET['title'].'%');\r\n\t\t\t\$pageObj->setPageQuery(['title'=>\$_GET['title']]);\r\n\t\t}\r\n\t\t// 开始时间过滤\r\n\t\tif(!empty(\$_GET['startTime'])){\r\n\t\t\t\$_GET['startTime'] = trim(\$_GET['startTime']);\r\n\t\t\t\$query = \$query->where(\"update_time\", '>=', strtotime(\$_GET['startTime'].\" 00:00:00\"));\r\n\t\t\t\$pageObj->setPageQuery(['startTime'=>\$_GET['startTime']]); \r\n\t\t}\r\n\t\t// 截止时间过滤\r\n\t\tif(!empty(\$_GET['endTime'])){\r\n\t\t\t\$_GET['endTime'] = trim(\$_GET['endTime']);\r\n\t\t\t\$query = \$query->where(\"update_time\", '<=', strtotime(\$_GET['endTime'].\" 23:59:59\"));\r\n\t\t\t\$pageObj->setPageQuery(['endTime'=>\$_GET['endTime']]);\r\n\t\t}\r\n\t\t// 父菜单总记录数\r\n\t\t\$totalCount = count(\$query->get()->toArray());\r\n\t\t//分页的take,size\r\n\t\t\$size = 10;\r\n\t\tif(!empty(\$_GET['page'])){\r\n\t\t\t\$page = (int)\$_GET['page'];\r\n\t\t\t\$skip = (\$page-1)*\$size;\r\n\t\t} else {\r\n\t\t\t\$skip = 0;\r\n\t\t}\r\n\t\t// 获取记录\r\n\t\t\$data = \$query->skip(\$skip)->take(\$size)->get()->toArray();\r\n\t\t//设置页数跳转地址\r\n\t\t\$pageObj->setUrl('/admin/".$adminUrl."');\r\n\t\t// 设置分页数据\r\n\t\t\$pageObj->setData(\$data);\r\n\t\t// 设置记录总数\r\n\t\t\$pageObj->setTotalCount(\$totalCount);\r\n\t\t// 设置分页大小\r\n\t\t\$pageObj->setSize(\$size);\r\n\t\t// 进行分页并返回\r\n\t\treturn \$pageObj->doPage();\r\n\t}\r\n\t/**\r\n\t* 逻辑增加\r\n\t* @author xww\r\n\t* @return sql result\r\n\t*/\r\n\tpublic function doCreate()\r\n\t{\r\n\t\tunset(\$_POST['id']);\r\n\t\tunset(\$_POST['coverPath']);\r\n\t\tunset(\$_POST['page']);\r\n\t\t// 上传文件\r\n\t\tif(!empty(\$_FILES['cover']) && \$_FILES['cover']['error']==0){\r\n\t\t\t\$ext = str_replace('image/', '', \$_FILES['cover']['type']);\r\n\t\t\t\$fpath = '/upload/product/'.microtime(true).\".\".\$ext;\r\n\t\t\t\$rs = move_uploaded_file(\$_FILES['cover']['tmp_name'], \$_SERVER['DOCUMENT_ROOT'].\$fpath);\r\n\t\t\tif(\$rs){\r\n\t\t\t\t\$_POST['cover'] = \$fpath;\r\n\t\t\t}\r\n\t\t}\r\n\t\t// 创建时间\r\n\t\t\$_POST['create_time'] = time();\r\n\t\t// 修改时间\r\n\t\t\$_POST['update_time'] = time();\r\n\t\treturn \$this->_model->insert(\$_POST);\r\n\t}\r\n\t/**\r\n\t* 返回对应id数据\r\n\t* @param  \$id  string/int    会话id\r\n\t* @author xww\r\n\t* @return object\r\n\t*/\r\n\tpublic function read(\$id)\r\n\t{\r\n\t\treturn \$this->_model->where(\"is_deleted\", '=', 0)->find(\$id);\r\n\t}\r\n\t/**\r\n\t* 逻辑修改\r\n\t* @author xww\r\n\t* @return sql result\r\n\t*/\r\n\tpublic function doUpdate()\r\n\t{\r\n\t\t\$id = \$_POST['id'];\r\n\t\tunset(\$_POST['id']);\r\n\t\tunset(\$_POST['coverPath']);\r\n\t\tunset(\$_POST['page']);\r\n\t\t// 上传文件\r\n\t\tif(!empty(\$_FILES['cover']) && \$_FILES['cover']['error']==0){\r\n\t\t\t\$ext = str_replace('image/', '', \$_FILES['cover']['type']);\r\n\t\t\t\$fpath = '/upload/product/'.microtime(true).\".\".\$ext;\r\n\t\t\t\$rs = move_uploaded_file(\$_FILES['cover']['tmp_name'], \$_SERVER['DOCUMENT_ROOT'].\$fpath);\r\n\t\t\tif(\$rs){\r\n\t\t\t\t\$_POST['cover'] = \$fpath;\r\n\t\t\t}\r\n\t\t}\r\n\t\t// 修改时间\r\n\t\t\$_POST['update_time'] = time();\r\n\t\t// 更新\r\n\t\treturn \$this->_model->where(\"id\", '=', \$id)->update(\$_POST);\r\n\t}\r\n\t/**\r\n\t* 逻辑删除\r\n\t* @author xww\r\n\t* @return sql result\r\n\t*/\r\n\tpublic function delete()\r\n\t{\r\n\t\t\$data['is_deleted'] = 1;\r\n\t\tif(\$_POST){\$ids = \$_POST['ids'];}\r\n\t\telse{\$ids = [\$_GET['id']];}\r\n\t\treturn \$this->_model->whereIn(\"id\", \$ids)->update(\$data);\r\n\t}\r\n}\r\n?>";
			// 创建模型
			file_put_contents($_SERVER['DOCUMENT_ROOT']."/../app/models/VirgoModel/$vmModel".".php", $virgoModelStr);
		}

		// 创建controller控制器
		if(!file_exists($_SERVER['DOCUMENT_ROOT']."/../app/controllers/back/$adminControl".".php")){
			$controllerStr = "<?php\r\n /**\r\n * 控制器\r\n * @author xww <5648*****@qq.com>\r\n * @version 1.0.0\r\n */\r\n namespace VirgoBack;\r\n class ".$adminControl." extends AdminBaseController{\r\n\t /*\r\n\t * @param  object  reflect this controller's  virgo model object\r\n\t */\r\n\t private \$model;\r\n\r\n\t public function __construct()\r\n\t {\r\n\t\t\$this->model = new \\VirgoModel\\".$vmModel.";\r\n\t\tparent::isLogin();\r\n\t }\r\n\r\n\t // 获取列表\r\n\t public function lists()\r\n\t {\r\n\t\t \$page_title = '管理';\r\n\t\t \$pageObj = \$this->model->lists();\r\n\t\t // 赋值数据\r\n\t\t\$data = \$pageObj->data;\r\n\t\t require_once dirname(__FILE__).'/../../views/admin/".$viewDirectory."/index.php';\r\n\t }\r\n\r\n\t // 增加专区分类界面\r\n\t public function create()\r\n\t {\r\n\t\t \$page_title = '增加管理';\r\n\t\t // 增加页面\r\n\t\t require_once dirname(__FILE__).'/../../views/admin/".$viewDirectory."/_create.php';\r\n\t }\r\n\r\n\t // 处理增加\r\n\t public function doCreate()\r\n\t {\r\n\t\t \$page = \$_POST['page'];\r\n\t\t \$rs = \$this->model->doCreate();\r\n\t\t if(\$rs){\$this->showPage(['添加专区分类成功'],'/admin/".$adminUrl."?page='.\$page); }\r\n\t\t else {\$this->showPage(['添加专区分类失败'],'/admin/".$adminUrl."?page='.\$page); }\r\n\t }\r\n\r\n\t //修改专区分类页面\r\n\t public function update()\r\n\t {\r\n\t\t \$page_title = '修改管理';\r\n\t\t\$id = \$_GET['id'];\r\n\t\t\$data = \$this->model->read(\$id);\r\n\t\t// 专区分类修改页面\r\n\t\t require_once dirname(__FILE__).'/../../views/admin/".$viewDirectory."/_update.php';\r\n\t }\r\n\r\n\t // 处理修改\r\n\t public function doUpdate()\r\n\t {\r\n\t\t \$page = \$_POST['page'];\r\n\t\t \$rs = \$this->model->doUpdate();\r\n\t\t if(\$rs){\$this->showPage(['修改成功'],'/admin/".$adminUrl."?page='.\$page); }\r\n\t\t else {\$this->showPage(['修改失败'],'/admin/".$adminUrl."?page='.\$page); }\r\n\t }\r\n\r\n\t // 处理删除\r\n\t public function doDelete()\r\n\t {\r\n\t\t \$rs =  \$this->model->delete();\r\n\t\t if(\$_POST){\r\n\t\t\t if(\$rs){echo json_encode(['success'=>true,'message'=>'delete success']);}\r\n\t\t\t else{echo json_encode(['success'=>false,'message'=>'delete failture']);}\r\n\t\t } else {\r\n\t\t\t if(\$rs){\$this->showPage(['删除成功'],'/admin/".$adminUrl."');}\r\n\t\t\t else {\$this->showPage(['删除失败'],'/admin/".$adminUrl."');}\r\n\t\t }\r\n\t }\r\n }\r\n ?>"; // 创建控制器
			file_put_contents($_SERVER['DOCUMENT_ROOT']."/../app/controllers/back/$adminControl".".php", $controllerStr);
		}
		
		// 创建目录
		if(!is_dir($_SERVER['DOCUMENT_ROOT']."/../app/views/admin/".$viewDirectory)){
			mkdir($_SERVER['DOCUMENT_ROOT']."/../app/views/admin/".$viewDirectory);
		}

		// 获取数据表结构
		$structArr = DB::select("describe ".$totalTableName);
		
		// 创建首页视图
		if(!file_exists($_SERVER['DOCUMENT_ROOT']."/../app/views/admin/".$viewDirectory."/index.php")){
			// 获取首页头th
			$structThHeader = '';
			for ($i=0; $i < count($structArr); $i++) {
				if(in_array($structArr[$i]->Field, ['is_deleted'])) {continue;}
				$structThHeader = $structThHeader."<th>".$structArr[$i]->Field."</th>\r\n\t\t\t\t";
			}

			// 获取内部td
			$structTd = '';
			for ($i=0; $i < count($structArr); $i++) {
				if(in_array($structArr[$i]->Field, ['is_deleted'])) {continue;}
				if($structArr[$i]->Field=='id'){
					$structTd = "<td class=''>\r\n\t\t\t\t\t<input type='checkbox' name='classesIds[]' class='eachNewsClassCheckBox' value='<?php echo \$data[\$i]['id'] ?>' /></td>\r\n\t\t\t\t".$structTd;
				}
				$structTd = $structTd."<td><?php echo \$data[\$i]['".$structArr[$i]->Field."'];?></td>\r\n\t\t\t\t";
			}			
			
			$indexStr = "<?php include_once('../app/views/admin/_header.php') ?>\r\n <link rel='stylesheet' type='text/css' href='/css/admin/index.css'>\r\n <div class='main-container'>\r\n <div class='smart-widget'>\r\n\t <div class='smart-widget-header'>\r\n\t\t <?php echo \$page_title;?>\r\n\t\t<span class='smart-widget-option'>\r\n\t\t\t <a href='$adminCreateUrl'>\r\n\t\t\t\t <i class='fa fa-plus'></i>\r\n\t\t\t </a>\r\n\t\t\t <a href='#' onclick='location.reload()' class='widget-refresh-option'>\r\n\t\t\t\t <i class='fa fa-refresh'></i>\r\n\t\t\t </a>\r\n\t\t </span>\r\n\t\t<form class='searchForm' action='".$adminUrl."' method='get'>\r\n\t\t\t<input type='text' name='title' value='<?php echo empty(\$_GET['title'])? '':\$_GET['title']; ?>' placeholder='请输入文章标题' />\r\n\t\t\t<input type='text' name='startTime' id='startTime' value='<?php echo empty(\$_GET['startTime'])? '':\$_GET['startTime'] ?>' placeholder='请输入起始时间' />\r\n\t\t\t-\r\n\t\t\t<input type='text' name='endTime' id='endTime' value='<?php echo empty(\$_GET['endTime'])? '':\$_GET['endTime'] ?>' placeholder='请输入结束时间' />\r\n\t\t\t<button class='btn btn-primary btn-sm' type='submit'>提交</button>\r\n\t\t</form>\r\n\t\t\r\n\t </div>\r\n\t <table id='indexTable' class=''>\r\n\t\t <thead>\r\n\t\t\t <tr class='firstLine'>\r\n\t\t\t\t <th class=''><input type='checkbox' name='chooseAll' class='allCheckBox chooseAll'></th>\r\n\t\t\t\t ".$structThHeader."\r\n\t\t\t\t <!-- <th class=''>用户类型</th> -->\r\n\t\t\t\t <th class='' colspan=2>操作</th>\r\n\t\t\t </tr>\r\n\t\t </thead>\r\n\t\t <tbody>\r\n\t\t\t <?php for(\$i=0; \$i<count(\$data); \$i++){ ?>\r\n\t\t\t <tr class='<?php echo \$i%2==0? 'singular':'dual' ?>'>\r\n\t\t\t\t".$structTd."\r\n\t\t\t\t <td class='operationBox' colspan=2>\r\n\t\t\t\t\t <a href='".$adminUpdateUrl."?id=<?php echo \$data[\$i]['id']; ?>' ><span class='icon-img'><img src='/images/edit-icon.png' /></span>编辑</a>\r\n\t\t\t\t\t <a type='button' href='".$adminDeleteUrl."?id=<?php echo \$data[\$i]['id']; ?>' onclick='return confirm(\"你确定要删除吗？\")'><span class='icon-img'><img src='/images/delete-icon.png'></span>删除</a>\r\n\t\t\t\t </td>\r\n\t\t\t </tr>\r\n\t\t\t <?php } ?>\r\n\t\t </tbody>\r\n\t\t <?php include_once('../app/views/admin/".$viewDirectory."/_tfoot.php') ?>\r\n\t </table>\r\n </div><!-- ./smart-widget -->\r\n </div>\r\n <?php include_once('../app/views/admin/_footer.php') ?>\r\n <script type='text/javascript' src='/js/myFuncs.js'></script> \r\n <script type='text/javascript' src='/js/date-picker/jquery-ui.min.js'></script> \r\n <script type='text/javascript'>\r\n function showDeleteItems(data)\r\n {\r\n\t if(!data.success){alert('删除失败'); }\r\n\telse {window.location.reload(); }\r\n\t }\r\n \$(document).ready(function(){\r\n\t //全选checkbox\r\n\t var allCheckBoxBtnClick = 1;\r\n\t \$('.allCheckBox').click(function(){\r\n\t\t if(allCheckBoxBtnClick%2!=0){\r\n\t\t\t \$('.eachNewsClassCheckBox').each(function(){\r\n\t\t\t\t \$(this).prop('checked',true);\r\n\t\t\t });\r\n\t\t } else{\r\n\t\t\t \$('.eachNewsClassCheckBox').each(function(){\r\n\t\t\t\t \$(this).prop('checked',false);\r\n\t\t\t });\r\n\t\t }\r\n\t\t allCheckBoxBtnClick++;\r\n\t });\r\n\t //删除选择的项目\r\n\t \$('.deleteChooseBtn').click(function(){\r\n\t\t deleteChooseItems('eachNewsClassCheckBox','你确定要删除选定项吗？','".$adminDestroyUrl."','','showDeleteItems','');\r\n\t });\r\n\t//日期选择\r\n\t$( '#startTime' ).datepicker({dateFormat:'yy-mm-dd'});\r\n\t$( '#endTime' ).datepicker({dateFormat:'yy-mm-dd'});\r\n });\r\n </script>";

			// 创建首页视图
			file_put_contents($_SERVER['DOCUMENT_ROOT']."/../app/views/admin/".$viewDirectory."/index.php", $indexStr);

		}

		// 创建form
		if(!file_exists($_SERVER['DOCUMENT_ROOT']."/../app/views/admin/".$viewDirectory."/_form.php")){
			$formLiStr = '';
			for ($i=0; $i < count($structArr); $i++) {
				if(in_array($structArr[$i]->Field, ['is_deleted', 'id', 'create_time','update_time'])) {continue;}

				// 驼峰id
				$fieldArr = explode("_", $structArr[$i]->Field);
				for ($j=1; $j < count($fieldArr); $j++) { 
					$fieldArr[$j] = ucfirst($fieldArr[$j]);
				}
				$humpField = implode("", $fieldArr);

				$formLiStr = $formLiStr."<li class='list-group-item' draggable='false'>".strtoupper($structArr[$i]->Field)."<input type='text' name='".$structArr[$i]->Field."' required class='form-control' id='".$humpField."' value='<?php echo empty(\$data['".$structArr[$i]->Field."'])? '':\$data['".$structArr[$i]->Field."'];  ?>' placeholder='请输入".$structArr[$i]->Field."' /> </li>\r\n\t\t";
			}

			$formStr = "<input type='hidden' name='id' value='<?php echo empty(\$data['id'])? '':\$data['id'];  ?>' />\r\n <input type='hidden' name='page' value='<?php echo empty(\$_GET['page'])? 1:\$_GET['page'];  ?>' />\r\n <div class='smart-widget-inner'>\r\n\t <ul class='list-group to-do-list sortable-list no-border'>\r\n\t\t".$formLiStr."\r\n\t\t <li class='list-group-item' draggable='false'>\r\n\t\t\t <button type='submit' class='btn btn-default btn-sm'>提交</button>\r\n\t\t </li>\r\n\t </ul>\r\n </div><!-- ./smart-widget-inner -->";

			// 创建form
			file_put_contents($_SERVER['DOCUMENT_ROOT']."/../app/views/admin/".$viewDirectory."/_form.php", $formStr);

		}

		// 创建create视图
		if(!file_exists($_SERVER['DOCUMENT_ROOT']."/../app/views/admin/".$viewDirectory."/_create.php")){
			$createStr = "<?php include_once('../app/views/admin/_header.php') ?>\r\n <div class='main-container'>\r\n\t <form id='myForm' method='POST' action='".$adminDoCreateUrl."' enctype='multipart/form-data' style='padding:20px !important;'>\r\n\t\t <div class='smart-widget'>\r\n\t\t\t <div class='smart-widget-header'>\r\n\t\t\t\t <?php echo \$page_title;?>\r\n\t\t\t </div>\r\n\t\t\t <?php include_once '_form.php'; ?>\r\n\t\t </div>\r\n\t </form>\r\n </div>\r\n <?php include_once('../app/views/admin/_footer.php') ?>\r\n <script type='text/javascript' src='/js/myFuncs.js'></script>";	

			// 创建create
			file_put_contents($_SERVER['DOCUMENT_ROOT']."/../app/views/admin/".$viewDirectory."/_create.php", $createStr);
		}

		// 创建update视图
		if(!file_exists($_SERVER['DOCUMENT_ROOT']."/../app/views/admin/".$viewDirectory."/_update.php")){
			$updateStr = "<?php include_once('../app/views/admin/_header.php') ?>\r\n <div class='main-container'>\r\n\t <form id='myForm' method='POST' action='".$adminDoUpdateUrl."' enctype='multipart/form-data'>\r\n\t\t <div class='smart-widget-header'>\r\n\t\t\t <?php echo \$page_title;?>\r\n\t\t </div>\r\n\t\t <?php include_once '_form.php'; ?>\r\n\t </form>\r\n </div>\r\n<?php include_once('../app/views/admin/_footer.php') ?>\r\n<script type='text/javascript' src='/js/myFuncs.js'></script>";

			// 编辑update
			file_put_contents($_SERVER['DOCUMENT_ROOT']."/../app/views/admin/".$viewDirectory."/_update.php", $updateStr);
		}

		// 创建tfoot视图
		if(!file_exists($_SERVER['DOCUMENT_ROOT']."/../app/views/admin/".$viewDirectory."/_tfoot.php")){
			$tfootStr = "<tfoot>\r\n\t <tr>\r\n\t\t <td colspan='".(count($structArr)+1)."'>\r\n\t\t\t <?php echo \$pageObj->pagination; ?>\r\n\t\t </td>\r\n\t </tr>\r\n\t <tr>\r\n\t\t <td colspan=''>\r\n\t\t\t <a href='javascript:void(0);' style='' class='allCheckBox btn btn-default btn-xs'>全选</a>\r\n\t\t\t <th> <a href='javascript:void(0);' class='deleteChooseBtn btn btn-danger btn-xs'>删除</a> </th>\r\n\t\t </th>\r\n\t </tr>\r\n </tfoot>";
			// 创建tfoot视图
			file_put_contents($_SERVER['DOCUMENT_ROOT']."/../app/views/admin/".$viewDirectory."/_tfoot.php", $tfootStr);
		}

		// 字符串
		$fileStr = file_get_contents($_SERVER['DOCUMENT_ROOT']."/../config/admin_routes.php");

		// headers
		if(stripos($fileStr, $adminUrl)===false){
			// 打开路由文件
			$fp = fopen($_SERVER['DOCUMENT_ROOT']."/../config/admin_routes.php", 'a+');
			fwrite($fp, "\r\n\r\nMacaw::get('/admin/".$adminUrl."','VirgoBack\\".$adminControl."@lists');\r\n");
			// 关闭路由文件
			fclose($fp);
		}

		// create
		if(stripos($fileStr, $adminCreateUrl)===false){
			// 打开路由文件
			$fp = fopen($_SERVER['DOCUMENT_ROOT']."/../config/admin_routes.php", 'a+');
			fwrite($fp, "Macaw::get('".$adminCreateUrl."','VirgoBack\\".$adminControl."@create');\r\n");
			// 关闭路由文件
			fclose($fp);
		}

		// do create
		if(stripos($fileStr, $adminDoCreateUrl)===false){
			// 打开路由文件
			$fp = fopen($_SERVER['DOCUMENT_ROOT']."/../config/admin_routes.php", 'a+');
			fwrite($fp, "Macaw::post('".$adminDoCreateUrl."','VirgoBack\\".$adminControl."@doCreate');\r\n");
			// 关闭路由文件
			fclose($fp);
		}

		// update
		if(stripos($fileStr, $adminUpdateUrl)===false){
			// 打开路由文件
			$fp = fopen($_SERVER['DOCUMENT_ROOT']."/../config/admin_routes.php", 'a+');
			fwrite($fp, "Macaw::get('".$adminUpdateUrl."','VirgoBack\\".$adminControl."@update');\r\n");
			// 关闭路由文件
			fclose($fp);
		}

		// do update
		if(stripos($fileStr, $adminDoUpdateUrl)===false){
			// 打开路由文件
			$fp = fopen($_SERVER['DOCUMENT_ROOT']."/../config/admin_routes.php", 'a+');
			fwrite($fp, "Macaw::post('".$adminDoUpdateUrl."','VirgoBack\\".$adminControl."@doUpdate');\r\n");
			// 关闭路由文件
			fclose($fp);
		}

		// read
		if(stripos($fileStr, $adminReadUrl)===false){
			// 打开路由文件
			$fp = fopen($_SERVER['DOCUMENT_ROOT']."/../config/admin_routes.php", 'a+');
			fwrite($fp, "Macaw::get('".$adminReadUrl."','VirgoBack\\".$adminControl."@read');\r\n");
			// 关闭路由文件
			fclose($fp);
		}

		// delete
		if(stripos($fileStr, $adminDeleteUrl)===false){
			// 打开路由文件
			$fp = fopen($_SERVER['DOCUMENT_ROOT']."/../config/admin_routes.php", 'a+');
			fwrite($fp, "Macaw::get('".$adminDeleteUrl."','VirgoBack\\".$adminControl."@doDelete');\r\nMacaw::post('".$adminDestroyUrl."','VirgoBack\\".$adminControl."@doDelete');\r\n");
			// 关闭路由文件
			fclose($fp);
		}

	}

	/**
	* 自动加载
	* @author xww
	* @return void
	*/ 
	public function loader()
	{
		
		// eloquent类
		$ePath = dirname(__FILE__).'/../../models';
		$directoryObj = dir($ePath);
		while (($file=$directoryObj->read())!==false) {
			// var_dump('start',$file,is_file(dirname(__FILE__).'/../../models/'.$file),PHP_EOL,'end');
			if(is_file($ePath.'/'.$file)){
				require_once($ePath.'/'.$file);
			}
			clearstatcache();
		}
		
		$directoryObj->close();

		// virgoModel类
		$vmPath = dirname(__FILE__).'/../../models/VirgoModel';
		$directoryObj = dir($vmPath);
		while (($file=$directoryObj->read())!==false) {
			if(is_file($vmPath.'/'.$file)){
				require_once($vmPath.'/'.$file);
			}
			clearstatcache();
		}
		
		$directoryObj->close();

		// admin control类
		$acPath = dirname(__FILE__);
		$directoryObj = dir($acPath);
		while (($file=$directoryObj->read())!==false) {
			if(is_file($acPath.'/'.$file)){
				require_once($acPath.'/'.$file);
			}
			clearstatcache();
		}
		
		$directoryObj->close();

	}

	/**
	* @author xww
	* @param  [$tableNameArr]	被打散的表名数组
	* @return string
	*/ 
	public function getModelName($tableNameArr)
	{
		
		$nameArr = [];
		foreach ($tableNameArr as $value) {
			array_push($nameArr, ucfirst($value));
		}

		return implode('', $nameArr);

	}

	/**
	* @author 	xww
	* @param  	[$tableNameArr]
	* @return	string
	*/ 
	public function getHeaderUrl($tableNameArr)
	{
		
		// 个数
		$count = count($tableNameArr);

		// 长度
		$length = strlen($tableNameArr[$count-1]);

		// 末尾加s
		if($tableNameArr[$count-1][$length-1]!='s'){
			$tableNameArr[$count-1] = $tableNameArr[$count-1].'s';
		}

		$nameArr = [];
		foreach ($tableNameArr as $key => $value) {
			if($key!=0){
				$value = ucfirst($value);
			}
			array_push($nameArr, $value);
		}
		
		return implode('', $nameArr);

	}

}