<?php
namespace VirgoBack;
class AdminPieceController extends AdminBaseController
{
	
	public function __construct()
	{
		$this->pageObj = new \VirgoUtil\Page;
	}

	public function index()
	{
		parent::isLogin();
		$page_title = '片段管理';
		// $data = \EloquentModel\Piece::all();

		// 分页对象
		$pageObj2 = new \VirgoUtil\Page2;

		// 父菜单总记录数
		$totalCount = \EloquentModel\Piece::count();

		//分页的take,size
		$size = 10;
		if(!empty($_GET['page'])){
			$page = (int)$_GET['page'];
			$skip = ($page-1)*$size;
		} else {
			$skip = 0;
		}

		$data2 = \EloquentModel\Piece::skip($skip)->take($size)->get()->toArray();

		//设置页数跳转地址 
		$pageObj2->setUrl('/admin/pieces');

		// 设置分页数据
		$pageObj2->setData($data2);

		// 设置记录总数
		$pageObj2->setTotalCount($totalCount);

		// 设置分页大小
		$pageObj2->setSize($size);

		// 进行分页并返回
		$pageObj = $pageObj2->doPage();

		$data = $pageObj->data;

		// 起始组装
		$page = $pageObj->current_page;
		$per_count = $size;
		$record_start = ($page-1)*$per_count;
		// 起始组装--end
		
		require_once dirname(__FILE__).'/../../views/admin/adminPieces/index.php';
	}

	public function add()
	{
		parent::isLogin();
		$page_title = '添加片段';
		$data = null;
		require_once dirname(__FILE__).'/../../views/admin/adminPieces/add.php';
	}

	public function create()
	{
		$functionsObj = new \VirgoUtil\Functions;
		$obj = new \EloquentModel\Piece;
		$data = $functionsObj->deleteNotNeedDataArray($_POST, array('id','file','pictures'));
		// 判断是否图片类型
		if($data['type']==2 && $_FILES['pictures']['size'][0]>0){
			$imgUploader = new \VirgoUtil\UploadImage;
			$imgUploader->setSaveUrl('/upload/pieces');
			$imgUploader->doUpload();
			if(!empty($imgUploader->getErrorMessage())){
				header("Refresh: 5;url=/admin/piece/edit?id=".$_POST['id']);
				echo "<script>alert('修改失败');</script>";
				return true;
			}
			
			$messages = $imgUploader->getSuccessMessage();
			$data['content'] = $messages[0]['url'];
		}
		$data['created_at'] = time();
		$data['updated_at'] = time();
		$rs = $obj->insert($data);

		header("Refresh: 5;url=/admin/pieces");
		if($rs)
			echo "添加成功";
		else
			echo "添加失败";

	}



	public function edit()
	{
		parent::isLogin();
		$page_title = '修改片段';
		$id = $_GET['id'];
		$data = \EloquentModel\Piece::find($id);
		require_once dirname(__FILE__).'/../../views/admin/adminPieces/edit.php';
	}




	public function update()
	{   
		$functionsObj = new \VirgoUtil\Functions;
		$obj = new \EloquentModel\Piece;
		$data = $functionsObj->deleteNotNeedDataArray($_POST, array('id','file','pictures'));
		// 判断是否图片类型
		if($data['type']==2 && $_FILES['pictures']['size'][0]>0){
			$imgUploader = new \VirgoUtil\UploadImage;
			$imgUploader->setSaveUrl('/upload/pieces');
			$imgUploader->doUpload();
			if(!empty($imgUploader->getErrorMessage())){
				header("Refresh: 5;url=/admin/piece/edit?id=".$_POST['id']);
				echo "<script>alert('修改失败');</script>";
				return true;
			}
			$data['content'] = $imgUploader->getSuccessMessage()[0]['url'];
		}
		$data['updated_at'] = time();
		$rs = $obj->where('id','=',$_POST['id'])->update($data);	
		header("Refresh: 5;url=/admin/pieces");
		if($rs)
			echo "修改成功";
		else
			echo "修改失败";
	}

	public function detail()
	{
		parent::isLogin();
		$page_title = '查看片段';
		$id = $_GET['id'];
		$piece = \EloquentModel\Piece::find($id);
		require_once dirname(__FILE__).'/../../views/admin/adminPieces/show.php';
	}

	
	public function delete()
	{
		parent::isLogin();
		$id = $_GET['id'];
		$rs = \EloquentModel\Piece::where('id', '=', $id)->delete($id);
		if($rs){
			header('Refresh: 5;url=/admin/pieces');
			echo "删除成功<br/>";
		} else {
			header('Refresh: 5;url=/admin/pieces');
			echo "删除失败";
		}
	}

	public function destroy()
	{
		parent::isLogin();
		$id = $_GET['id'];
		$rs = \EloquentModel\Piece::where('id', '=', $id)->delete($id);
		if($rs){
			header('Refresh: 5;url=/admin/pieces');
			echo "删除成功";
		} else {
			header('Refresh: 5;url=/admin/pieces');
			echo "删除失败";
		}
	}

	/*其他函数*/





}
