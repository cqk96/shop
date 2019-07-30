<?php
namespace EloquentModel;
class NewsClasses extends \Illuminate\Database\Eloquent\Model
{
	//protected $table = 'news_classes';
	public $timestamps = false;
	protected $fillable = array('class_name','pclass_id','hidden');
	
	//private
	protected $newsClassesObj;


	public function __construct()
	{
		//$this->newsClassesObj = new NewsClasses;
	}

	public function lists()
	{
		return \EloquentModel\NewsClasses::where('status','=',0)->orderBy('pclass_id','asc')->orderBy('id','asc')->get();
	}

	
	public function getNewsClasses()
	{

		$all_first_classes = \EloquentModel\NewsClasses::where('pclass_id','=',0)
			->where('status','=',0)
			->orderBy('id','asc')
			->get();

		foreach ($all_first_classes as $key => $value) {
			$all_children_classes = $this->getNewsClassChildren($value['id']);
			$all_first_classes[$key]['children'] = $all_children_classes;
		}

		return $all_first_classes;

	}

	
	public function getNewsClassChildren($parentId)
	{

		$childre_classes = \EloquentModel\NewsClasses::where('pclass_id','=',$parentId)
			->where('status','=',0)
			->orderBy('id','asc')
			->get();
		return $childre_classes;

	}

	
	public function getAllNewsClasses($pid,$curClassData)
	{

		$all_classes = \EloquentModel\NewsClasses::where('pclass_id','=',$pid)
			->where('hidden','=',0)
			->orderBy('id','asc')
			->get();

		
		foreach ($all_classes as $key => $value) {

			$result = $this->getAllNewsClasses($value['id'],$value);
			if($result){
				$value['children'] = $result;
				$tree[] = $value;
			}

		}

		
		if(count($all_classes)==0){
			if($pid!=0){
				$tree = $curClassData;
			} else {
				$tree = '';
			}
		}

		return $tree;

	}

	public function getParentNodes()
	{

		$all_first_classes = \EloquentModel\NewsClasses::where('pclass_id','=',0)
			->where('status','=',0)
			->orderBy('id','asc')
			->get();

		$total_str  = '';
		foreach ($all_first_classes as $key => $value) {
			$total_str = $total_str.
			"<tr>".
				"<td width=''><input type='checkbox' name='classesIds[]' class='eachNewsClassCheckBox' value='".$value['id']."' /></td>".
				"<td width=''>".$value['id']."</td>".
				"<td width='' class='classesName'>".
					$value['class_name'].
				"</td>".
				"<td class='operationBox' width=''>".
					"<a type='button' href='/admin/newsClass/update?id=".$value['id']."' class='btn btn-default btn-sm'>修改</a>".
					"<a type='button' href='/admin/newsClass/delete?id=".$value['id']."' onclick='return confirm('你确定要删除吗？')' class='btn btn-danger btn-sm'>删除</a>".
				"</td>".
			"</tr>";
			// var_dump($total_str);
			// die;
			$substr = $this->getChildrenNodes($value['id']);
			
			$total_str = $total_str.$substr;
		}

		return $total_str;

	}

	public function getChildrenNodes($parentId,$layer=1)
	{
		$substr = '';
		$layer++;
		$childre_classes = \EloquentModel\NewsClasses::where('pclass_id','=',$parentId)
									  ->where('status','=',0)
									  ->orderBy('id','asc')
									  ->get();

		if(count($childre_classes)!=0) {
			foreach ($childre_classes as $key => $value) {
				$tempStr = "<tr class='nosee hasChild layer".$layer."'>".
								"<td width=''><input type='checkbox' name='classesIds[]' class='eachNewsClassCheckBox' value='".$value['id']."' /></td>".
								"<td width=''>".$value['id']."</td>".
								"<td width='' class='childClass classesName'>|——".$value['class_name']."</td>".
								"<td class='operationBox' width=''>".
									"<a type='button' href='/admin/newsClass/update?id=".$value['id']."' class='btn btn-default btn-sm'>修改</a>".
									"<a type='button' href='/admin/newsClass/delete?id='".$value['id']."' onclick='return confirm(\'你确定要删除吗？\')' class='btn btn-danger btn-sm'>删除</a>".
								"</td>".
							"</tr>";
				$childrenStr = $this->getChildrenNodes($value['id'],$layer);
				$substr = $substr.$tempStr.$childrenStr;
			}
		} else {
			return '';
		}

		return $substr;
		
	}
	
	public function getNodesNewsClassChildren($parentId)
	{

		$childre_classes = \EloquentModel\NewsClasses::where('pclass_id','=',$parentId)
									  ->where('status','=',0)
									  ->orderBy('id','asc')
									  ->get();
		foreach ($all_first_classes as $key => $value) {
			
		}
		return $childre_classes;

	}

	//获取所有分类
	public function getAllNodes()
	{

		$all_first_classes_obj = \EloquentModel\NewsClasses::where('status','=',0)//where('pclass_id','=',0)
														->orderBy('id','asc');

		//父菜单总记录数
		$totalCount = \EloquentModel\NewsClasses::where('status','=',0)->where('pclass_id','=',0)->count();
		//分页
		$size = 10;
		if(!empty($_GET['page'])){
			$page = (int)$_GET['page'];
			$skip = ($page-1)*$size;
			$recordArr = \EloquentModel\NewsClasses::where('status','=',0)->where("pclass_id", '=', 0)->skip($skip)->take($size)->get()->toArray();
			//$navsObj = $navsObj->skip($skip)->take($size);
		} else {
			//$navsObj = $navsObj->skip(0)->take($size);
			$recordArr = \EloquentModel\NewsClasses::where('status','=',0)->where("pclass_id", '=', 0)->skip(0)->take($size)->get()->toArray();
		}	
        $all_first_classes = $all_first_classes_obj->get()->toArray();
		if(count($all_first_classes)==0||empty($all_first_classes)){
			$data = '';
		} else {
			$treeLevel = 1;
			//剔除不属于最后一条的菜单
			$finalRecord = $recordArr[count($recordArr)-1]['id'];
			$firstRecord = $recordArr[0]['id'];
			foreach ($all_first_classes as $newsClasses_key => $newsClasses_val) {
				if($newsClasses_val['pclass_id']==0 && ($newsClasses_val['id']>$finalRecord || $newsClasses_val['id']<$firstRecord)){
					unset($all_first_classes[$newsClasses_key]);
				}
			}
			array_values($all_first_classes);
			$data = $this->getTreeLists_ver2($all_first_classes,0,'');
		}

    	$pageObj = new \VirgoUtil\Page2;
		$pageObj->setUrl('/admin/newsClasses');
		$pageObj->setData($data);
		$pageObj->setTotalCount($totalCount);
		$pageObj->setSize($size);
		return $pageObj->doPage();

	}

	public function getTreeLists_ver2($data, $pId,$vv,$count=0)
	{
		
		global $treeLevel;
		
		$tree = '';
		$j = 0;
		$count++;
		$subStr = '';
		//$status = ['隐藏', '显示'];
		$classLevel = 'menuLevel_1';
		$layer = 1;

		foreach($data as $k => $v)
		{
		   
		   if($v['pclass_id'] == $pId)
		   {    //第一个一级菜单 寻找子菜单
		   		//var_dump($v['id']);
		   		$j++;
		    	$v = $this->getTreeLists_ver2($data, $v['id'],$v, $count);
		    	$tree = $tree.$v;
		   }

		}

		
		//子菜单没有子菜单 返回自身
		if(!$j && $pId!=''){
			$treeLevel = $treeLevel+1;
			if($vv['pclass_id']!=0){
				$subStr = "|--";
				$classLevel = "menuLevel_".($count-1);
			}
			
			$temp = 
			"<tr>".
				"<td width=''><input type='checkbox' name='classesIds[]' class='eachNewsClassCheckBox' value='".$vv['id']."' /></td>".
				"<td width=''>".$vv['id']."</td>".
				"<td width='' class='".$classLevel."'>".
					$subStr.$vv['class_name'].
				"</td>".
				"<td><img src='".$vv['cover']."' />".
				"</td>".
				"<td class='operationBox' width=''>".
					"<a href='/admin/newsClass/update?id=".$vv['id']."' ><span class='icon-img'><img src='/images/edit-icon.png' /></span>修改</a>".
					"<a href='/admin/newsClass/delete?id=".$vv['id']."' onclick='return confirm('你确定要删除吗？')' ><span class='icon-img'><img src='/images/delete-icon.png' /></span>删除</a>".
				"</td>".
			"</tr>";
			$tree = $temp;
		}

		//多种情况
		if($vv!=''){
			//var_dump();
			if($j){
				if($vv['pclass_id']!=0){
					$subStr = "|--";
					$layer = $count-1;
					$classLevel = "menuLevel_".($count-1);
				}
				$treeLevel = $treeLevel+1;	
				$temp_p = 
				"<tr class=''>".
								"<td width=''><input type='checkbox' name='classesIds[]' class='eachNewsClassCheckBox' value='".$vv['id']."' /></td>".
								"<td width=''>".$vv['id']."</td>".
								"<td width='' class='".$classLevel."'>".$subStr.$vv['class_name']."</td>".
								"<td><img src='".$vv['cover']."' />"."</td>".
								"<td class='operationBox' width=''>".
									"<a type='button' href='/admin/newsClass/update?id=".$vv['id']."'><span class='icon-img'><img src='/images/edit-icon.png' /></span>修改</a>".
									"<a type='button' href='/admin/newsClass/delete?id=".$vv['id']."' onclick='return confirm(\'你确定要删除吗？\')' ><span class='icon-img'><img src='/images/delete-icon.png' /></span>删除</a>".
								"</td>".
							"</tr>";
				$tree = $temp_p.$tree;
			}
			
		}

		return $tree;
	}

	
}