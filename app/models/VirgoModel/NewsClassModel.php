<?php
/**
* 专区 model  逻辑层
* @author  xww <5648*****@qq.com>
* @version 1.0.0
*/
namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class NewsClassModel extends BaseModel {
	/* @param object  reflect this model's  eloquent model object */
	private $_model;

	// 初始化
	public function __construct()
	{
		$this->_model = new \EloquentModel\NewsClasses; 
	}
	
	/**
	* 判断是否有特定名称的分类存在
	* @author 	xww
	* @param 	string 		$name
	* @return 	array
	*/ 
	public function getClassFromName($name)
	{
		return $this->_model->where("status", 0)->where("class_name", $name)->take(1)->get()->toArray();
	}

	/**
	* 判断是否有特定id数据
	* @author 	xww
	* @param 	int/string 		$id
	* @return 	array
	*/
	public function read($id)
	{
		return $this->_model->where("status", 0)->find($id);
	}

	/**
	* 可用全部数据
	* @author 	xww
	* @return 	array
	*/
	public function all()
	{
		return $this->_model->where("status", 0)->get()->toArray();
	}

	/**
	* 获取列表对象
	* @author 	xww
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	object
	*/
	public function getListsObject($skip, $size)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query = $this->_model->where("status", 0)
					  		  ->orderBy("pclass_id", "desc")
					  		  ->orderBy("id", "desc")
					  		  ->select("id", "class_name",  DB::raw(" IFNULL(cover, '') as cover "));

		// 父菜单总记录数
		$totalCount = count( $query->get()->toArray() );

		// 获取记录
		$data = $query->skip($skip)->take($size)->get()->toArray();

		$url = "";

		//设置页数跳转地址
		$pageObj->setUrl( $url );

		// 设置分页数据
		$pageObj->setData($data);

		// 设置记录总数
		$pageObj->setTotalCount($totalCount);

		// 设置分页大小
		$pageObj->setSize($size);

		// 进行分页并返回
		return $pageObj->doPage();

	}

	/**
	* 插入数据
	* @author 	xww
	* @param 	array 	$data
	* @return 	int 	insert id
	*/
	public function create($data)
	{
		return $this->_model->insertGetId($data);
	}

	/**
	* 获取当前菜单下属一层所有菜单
	* @author 	xww
	* @param 	int/string 		$pid
	* @return 	array
	*/
	public function getChildrensClasses($pid)
	{

		return $this->_model->where("status", 0)
									 ->where("pclass_id", $pid)
									 ->select("id", "class_name")
									->get()
									->toArray();

	}

	/**
	* 改变新上级分类 当当前上级分类 是该分类的所有分类
	* @author 	xww
	* @param 	int/string 	$id
	* @return 	int 	affect rows
	*/
	public function changeChildrenParentClasses($id, $pid=0)
	{
		$data['pclass_id'] = $pid;
		return $this->_model->where("pclass_id", $id)->update($data);
	}

	/**
	* 设定新下级部门 当当前上级部门 是该部门的所有部门
	* @author 	xww
	* @param 	int/string 	$id
	* @return 	int 	affect rows
	*/
	public function setChildrenParentClass($id, $cids)
	{
		$data['pclass_id'] = $id;
		return $this->_model->whereIn("id", $cids)->update($data);
	}

	/**
	* 多数据更新
	* @author 	xww
	* @param 	array			$ids
	* @param 	array 			$data
	* @return 	affect rows
	*/
	public function multiplePartUpdate($ids, $data)
	{
		return $this->_model->whereIn("id", $ids)->update($data);
	}
	
	/**
	* 记录查询
	* @author 	xww
	* @param 	int/string 	$id
	* @return 	object 	
	*/
	public function readSingleTon($id)
	{
		return $this->_model->where("status", 0)->find($id);
	}
	
	/**
	* 获取部门详情 --包括两个列表  一个上级部门列表，一个下级部门列表
	* @author 	xww
	* @param 	int/string 		$id  default null
	* @return 	array
	*/
	public function getInfoWidthParentAndChildren( $id=null )
	{
		
		$all = $this->_model->where("status", 0)
								   ->select("id", "class_name as name", "pclass_id")
								   ->orderBy("pclass_id", "asc")
								   ->orderBy("id", "asc")
								   ->get()
								   ->toArray();

		if( !is_null($id) ) {
			$single = $this->_model->find($id);
		}

		// 上级部门
		$parents = [];

		for ($i=0; $i < count($all); $i++) { 
			
			$all[$i]['checked'] = false;

			if( !empty($single) && $single['id']!=$all[$i]['id'] && $single['pclass_id']==$all[$i]['id'] ) {
				$all[$i]['checked'] = true;				
			}

			$temp = $all[$i];
			unset($temp['pclass_id']);
			$parents[] = $temp;

		}

		// 下级部门
		$children = [];

		for ($i=0; $i < count($all); $i++) { 
			
			$all[$i]['checked'] = false;

			if( !empty($single) && $single['id']!=$all[$i]['id'] && $single['id']==$all[$i]['pclass_id'] ) {
				$all[$i]['checked'] = true;				
			}

			$temp = $all[$i];
			unset($temp['pclass_id']);
			$children[] = $temp;

		}

		if( empty($single) ) {
			$selfData = null;	
		} else {

			unset($single['status']);
			// unset($single['pclass_id']);
			// unset($single['level']);
			// unset($single['create_time']);
			unset($single['update_time']);
			$selfData = $single;

		}

		$data['parents'] = empty($parents)? null:$parents;
		$data['data'] = $selfData;
		$data['children'] = empty($children)? null:$children;

		return $data;

	}

	/**
	* 更新
	* @author 	xww
	* @param 	int/string 	$id
	* @param 	array 	$data
	* @return 	int 	affect rows
	*/
	public function updateParts($id, $data)
	{
		return $this->_model->where("id", $id)->update($data);
	}

	/**
	* 根据id获取记录
	* @author 	xww
	* @param 	int/string 		$id
	* @return 	object
	*/
	public function readSingelTon($id)
	{
		return $this->_model->where("status", 0)->find($id);
	}

	/**
	* 获取所有文章分类
	* @author 	xww
	* @return 	array
	*/
	public function getAll()
	{
		return $this->_model->where("status", 0)->select("id", "class_name")->get()->toArray();
	}

	/**
	* 递归删除
	* @author 	xww
	* @return   void 	
	*/ 
	public function doDeleteRElMenu($ids)
	{
		
		$data = $this->sysMenuObj->where("status", 0)->whereIn("parentid", $ids)->select("id")->get()->toArray();

		$ids = [];
		for ($i=0; $i < count($data); $i++) { 
			array_push($ids, $data[$i]['id']);
		}

		if(empty($ids)) {
			return true;
		} else {
			$this->doDeleteRElMenu($ids);
		}

		// 进行删除
		$updateData['updated_at'] = time();
		$updateData['status'] = 1;

		$this->sysMenuObj->whereIn("id", $ids)->update($updateData);		

	}
	
}