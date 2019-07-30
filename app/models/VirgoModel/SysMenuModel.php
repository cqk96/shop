<?php
namespace VirgoModel;
use Illuminate\Database\Capsule\Manager as DB;
class SysMenuModel extends BaseModel{
	protected $sysMenuObj = '';
	protected $delete_ids = '';

	/*判断已经获取的菜单的分页数量*/
	private $readyTake = 0;

	/*判断已经跳过的菜单的分页数量*/
	private $readySkip = 0;

	/*要跳过的菜单的分页数量*/
	private $skip = 0;

	/*分页数量*/
	private $data = [];

	public function __construct()
	{
		$this->sysMenuObj = new \EloquentModel\SysMenu;
		$this->functionsObj = new \VirgoUtil\Functions;
		$this->sysRoleObj = new \EloquentModel\SysRole;
		$this->userObj = new \EloquentModel\User;
	}

	public function lists()
	{
		$data = $this->sysMenuObj
				    // ->where('show','=',1)
				    ->where('status','=',0)
					->orderBy('order', 'asc')
					->get();

		return ($data);
		
	}

	public function getKVMenus()
	{
		$data = $this->sysMenuObj
				     // ->where('show','=',1)
				     ->where('status','=',0)
					 ->orderBy('order', 'asc')
					 ->get();
		foreach ($data as $key => $value) {
			$return[$value['id']] = $value;
		}

		return $return;
	}

	public function treeLists()
	{

		$navsObj = $this->sysMenuObj
					->where('status','=',0)
					->orderBy('order', 'asc');

		//父菜单总记录数
		$totalCount = $this->sysMenuObj->where('status','=',0)->where("parentid", '=', 0)->count();
		//分页
		$size = 2;
		if(!empty($_GET['page'])){
			$page = (int)$_GET['page'];
			$skip = ($page-1)*$size;
			$recordArr = $this->sysMenuObj->where('status','=',0)->where("parentid", '=', 0)->skip($skip)->take($size)->get()->toArray();
			//$navsObj = $navsObj->skip($skip)->take($size);
		} else {
			//$navsObj = $navsObj->skip(0)->take($size);
			$recordArr = $this->sysMenuObj->where('status','=',0)->where("parentid", '=', 0)->skip(0)->take($size)->get()->toArray();
		}
		$navs = $navsObj->get()->toArray();
		if(count($navs)==0||empty($recordArr)){
			$data = '';
		} else {
			$treeLevel = 1;
			//剔除不属于最后一条的菜单
			$finalRecord = $recordArr[count($recordArr)-1]['id'];
			$firstRecord = $recordArr[0]['id'];
			foreach ($navs as $nav_key => $nav_val) {
				if($nav_val['parentid']==0 && ($nav_val['id']>$finalRecord || $nav_val['id']<$firstRecord)){
					unset($navs[$nav_key]);
				}
			}
			//array_values($navs);
			//var_dump($navs);
	    	$data = $this->getTreeLists($navs,0,'');

		}
		
		$pageObj = new \VirgoUtil\Page2;
		$pageObj->setUrl('/admin/sys/menus');
		$pageObj->setData($data);
		$pageObj->setTotalCount($totalCount);
		$pageObj->setSize($size);
		return $pageObj->doPage();
	}

	public function backMenuLists()
	{
		
		$id = $_COOKIE['user_id'];

		if($id!=1){
			$has_privilege = $this->userObj
							  ->select('rel_role_to_menu.menu_id')
							  ->join("rel_role_to_user", "rel_role_to_user.user_id", '=', 'users.id')
							  ->join("rel_role_to_menu", "rel_role_to_menu.role_id", '=', 'rel_role_to_user.role_id')
							  ->where('rel_role_to_menu.deleted', '=', 0)
							  ->where('users.id', '=', $id)
							  ->where('rel_role_to_menu.deleted', '=', 0)
							  ->where("is_deleted", '=', 0)
							  //->where('users.user_status', '=', 1)
							  ->groupBy('menu_id')
							  ->get();
		} else {
			$has_privilege = $this->sysMenuObj
							->select('id as menu_id')
						    ->where('show','=',1)
						    ->where('status','=',0)
							->orderBy('order', 'asc')
							->get();
			 	
		}

		
		if(count($has_privilege)==0){
			//$this->showPage(['当前账号没有权限访问菜单，请联系管理员进行设置'], '/');
			return '';
			//exit();
		}

		$temp_menus = $has_privilege->toArray();
		$menus = array();					  

		foreach ($temp_menus as $key => $value) {
			array_push($menus, $value['menu_id']);
		}
		
		$navs = $this->sysMenuObj
					->where('show','=',1)
					->where('status','=',0)
					->whereIn('id',$menus)
					->orderBy('order', 'asc')
					->get();

		if(count($navs)==0)
			return '';

		//父级个数
		$pCount = 1;

    	$all_navs = $this->getBackMenuLists($navs,0,'');

	    //var_dump($all_navs);
	    //die;
    	return $all_navs;

	}


	//后台菜单列表
	public function getBackMenuLists($data, $pId,$vv,$count=0)
	{
		global $pCount;
		
		$tree = '';
		$j = 0;
		$count++;
		$subStr = '';
		$classLevel = 'menuLevel_1';

		

		foreach($data as $k => $v)
		{

		   if($v['parentid'] == $pId)
		   {    //第一个一级菜单 寻找子菜单
		   		$j++;
		    	$v = $this->getBackMenuLists($data, $v['id'],$v, $count);
		    	$tree = $tree.$v;
		   }

		}

		//子菜单没有子菜单 返回自身
		if(!$j && $pId!=''){
			//var_dump($vv['id'].':'.$j.":".($count-1));
			if(!empty($vv['url']))
				$urlStr = "onclick='changeUrl(this)' data-href='".$vv['url']."'";
			else
				$urlStr = '';
			$temp = 
					"<li>
						<a href='javascript:void(0);' ".$urlStr." ><span class='submenu-label'>".$vv['name']."</span></a>
					</li>";

			$tree = $temp;

		}

		//多种情况
		if($vv!=''){
			//var_dump($vv['id'].':'.$j);
			if($j){

				//子菜单返回
				if($vv['parentid']!=0){
					
					//二级菜单（含子菜单）
					if(($count-1)==2){
						//var_dump($vv['id'].':'.$j.":".($count-1));
						$sub_level_2_head = "<li class='openable' style='display: block;'>
										<a href='javascript:void(0);' ><span class='submenu-label'>".$vv['name']."</span>
										<small class='badge badge-success badge-square bounceIn animation-delay2 m-left-xs pull-right'>".$j."</small>
										</a>
 										<ul class='submenu third-level' style='display: none;'>";
						$sub_level_2_foot = "</ul>";
						$tree = $sub_level_2_head.$tree.$sub_level_2_foot;
					}

				} else {
					$pCount++;
					if(($pCount+1)%4==0)
						$bgNum = 3;
					else if($pCount%2==0)
						$bgNum = $pCount%4==0? 4:2;
					else
						$bgNum = 1;
					//var_dump($vv['id'].":".$bgNum);
					if($j!=0){
						$p_str_has_sub_head = "<li class='openable bg-palette".$bgNum."'>
													<a href='#'>
														<span class='menu-content block'>
															<span class='menu-icon'><i class='block fa fa-list fa-lg'></i></span>
															<span class='text m-left-sm'>".$vv['name']."</span>
															<span class='submenu-icon'></span>
														</span>
														<span class='menu-content-hover block'>
															".$vv['name']."
														</span>
													</a>
												<ul class='submenu'>";
						$p_str_has_sub_foot = "</ul>";
						$tree = $p_str_has_sub_head.$tree.$p_str_has_sub_foot;
					} else {
						if (!empty($vv['url']))
							$p_href = $vv['url'];
						else
							$p_href = '#';
						$p_str_head = "<li class='openable bg-palette4'>
								<a href='".$p_href."'>
									<span class='menu-content block'>
										<span class='menu-icon'><i class='block fa fa-list fa-lg'></i></span>
										<span class='text m-left-sm'>".$vv['name']."</span>
										<span class='submenu-icon'></span>
									</span>
									<span class='menu-content-hover block'>
										".$vv['name']."
									</span>
								</a>";
						$p_str_footer = "</li>";
						$tree = $p_str_head.$tree.$p_str_footer;
					}

				}

			}
			
		}

		return $tree;

	}

	//后台菜单管理列表
	public function getTreeLists($data, $pId,$vv,$count=0)
	{
		global $treeLevel;

		$tree = '';
		$j = 0;
		$count++;
		$subStr = '';
		$status = ['隐藏', '显示'];
		$classLevel = 'menuLevel_1';

		foreach($data as $k => $v)
		{

		   if($v['parentid'] == $pId)
		   {    //第一个一级菜单 寻找子菜单
		   		$j++;
		    	$v = $this->getTreeLists($data, $v['id'],$v, $count);
		    	$tree = $tree.$v;
		   }

		}

		
		//子菜单没有子菜单 返回自身
		if(!$j && $pId!=''){
			$treeLevel = $treeLevel+1;
			if($vv['parentid']!=0){
				$subStr = "|--";
				$classLevel = "menuLevel_".($count-1);
			}
			
			$temp = 
			"<tr>
				<td width='5%'><input type='checkbox' name='ids' class='ids' value='".$vv['id']."'></td>
				<td width='10%'>".$vv['id']."</td>
				<td width='20%' class='".$classLevel."'>".$subStr.$vv['name']."</td>
				<td width='30%'>".$vv['url']."</td>
				<td width='5%'>".$status[$vv['show']]."</td>
				<td width='5%'><input type='text' name='order' value='".$vv['order']."' size='5' maxLength='4'></td>
				<td class='operationBox' width='20%'>
					<a href='/admin/sys/menu/update?id=".$vv['id']."' ><span class='icon-img'><img src='/images/edit-icon.png' /></span>修改</a>
					<a href='/admin/sys/menu/doDelete?id=".$vv['id']."' onclick=\"return confirm('你确定要删除吗？')\" ><span class='icon-img'><img src='/images/delete-icon.png' /></span>删除</a>
				</td>
			</tr>";
			$tree = $temp;
		}

		//多种情况
		if($vv!=''){
			//var_dump();
			if($j){
				if($vv['parentid']!=0){
					$subStr = "|--";
					$classLevel = "menuLevel_".($count-1);
				}
				$treeLevel = $treeLevel+1;	
				$temp_p = 
				"<tr>
					<td width='5%'><input type='checkbox' name='ids' class='ids' value='".$vv['id']."'></td>
					<td width='10%'>".$vv['id']."</td>
					<td width='20%' class='".$classLevel."'>".$subStr.$vv['name']."</td>
					<td width='30%'>".$vv['url']."</td>
					<td width='5%'>".$status[$vv['show']]."</td>
					<td width='5%'><input type='text' name='order' value='".$vv['order']."' size='5' maxLength='4'></td>
					<td class='operationBox' width='20%'>
						<a href='/admin/sys/menu/update?id=".$vv['id']."' ><span class='icon-img'><img src='/images/edit-icon.png' /></span>修改</a>
						<a href='/admin/sys/menu/doDelete?id=".$vv['id']."' onclick=\"return confirm('你确定要删除吗？')\" ><span class='icon-img'><img src='/images/delete-icon.png' /></span>删除</a>
					</td>
				</tr>";
				$tree = $temp_p.$tree;
			}
			
		}

		return $tree;

	}

	public function create()
	{
		unset($_POST['id']);
		if($_POST['pNav']==1)
			$_POST['parentid'] = 0;
		unset($_POST['pNav']);
		$order = $this->getNextIncrement('comp_menus');
		$_POST['created_at'] = time();
		$_POST['updated_at'] = time();
		$_POST['order'] = $order;
		return $this->sysMenuObj->insert($_POST);
	}

	public function read()
	{
		$id = $_GET['id'];
		return $this->sysMenuObj->find($id);
	}

	public function update()
	{
		
		$id = $_POST['id'];
		unset($_POST['id']);
		if($_POST['pNav']==1)
			$_POST['parentid'] = 0;
		unset($_POST['pNav']);
		$_POST['updated_at'] = time();
		return $this->sysMenuObj->where('id',$id)->update($_POST);

	}

	public function delete()
	{
		
		$data['status'] = 1;
		if($_POST)
			$this->delete_ids = $_POST['ids'];
		else
			$this->delete_ids = [$_GET['id']];
		return $this->sysMenuObj->whereIn('id',$this->delete_ids)
								->orWhere(function($query){
									$query->whereIn('parentid',$this->delete_ids);
								})
		                        ->update($data);

	}

	public function updateColumn()
	{
		
		if(empty($_POST['value']) || (int)$_POST['value']==0)
			exit();
		
		$this->functionsObj->editColumnsValueById('\\EloquentModel\\SysMenu', ['order'=>$_POST['value']],['id'=>$_POST['id']]);

	}

	/**
	* 获取用户拥有的后台菜单
	* @author 	xww
	* @param 	int/string 		$uid
	* @return 	array
	*/
	public function getUserBackMenu( $uid )
	{

		/*非默认超管*/
		if($uid!=1){

			$menus = $this->userObj->select('menus.id', "menus.name", "menus.url", "menus.order", "menus.parentid")
							  ->join("rel_role_to_user", "rel_role_to_user.user_id", '=', 'users.id')
							  ->join("rel_role_to_menu", "rel_role_to_menu.role_id", '=', 'rel_role_to_user.role_id')
							  ->join("menus", "menus.id", '=', 'rel_role_to_menu.menu_id')
							  ->where('rel_role_to_menu.deleted', '=', 0)
							  ->where('users.id', '=', $uid)
							  ->where('rel_role_to_menu.deleted', '=', 0)
							  ->where("is_deleted", '=', 0)
							  ->where('menus.show','=',1)
						      ->where('menus.status','=',0)
							  ->groupBy('menus.id', "menus.name", "menus.url", "menus.order", "menus.parentid")
							  ->orderBy('menus.parentid', 'asc')
							  ->orderBy('menus.order', 'asc')
							  ->orderBy('menus.id', 'asc')
							  ->get()
							  ->toArray();

		} else {

			$menus = $this->sysMenuObj
							->select('id', "name", "url", "order", "parentid")
						    ->where('show','=',1)
						    ->where('status','=',0)
						    ->groupBy('id')
							->orderBy('parentid', 'asc')
						    ->orderBy('order', 'asc')
						    ->orderBy('id', 'asc')
							->get()
							->toArray();
			 	
		}

		return $this->getUserBackMenuLists($menus,0);

	}

	/**
	* 递归返回用户拥有的菜单数组
	* @author 	xww
	* @param  	array  			$data     		菜单数组
	* @param  	int/string  	$parentId     	父级id
	* @param  	array  			$selfMenu     	单个菜单自身数组
	* @return 	array
	*/
	public function getUserBackMenuLists($data, $parentId, $selfMenu=[])
	{


		$retunData = [];

		if( !empty($selfMenu) ) {

			if( !isset($selfMenu['children'] ) ){
	   			$selfMenu['children'] = [];
	   		}

			$retunData = $selfMenu;
		}
		
		foreach($data as $k => $v)
		{

		   if($v['parentid'] == $parentId)
		   {    

		   		if( empty($selfMenu)  ) {
		   			$selfMenu = $v;
		   			$selfMenu['children'] = [];	
		   		} else if( !isset($selfMenu['children'] ) ){
		   			$selfMenu['children'] = [];
		   		}

		   		unset($v['parentid']);
		   		unset($v['order']);

		   		//第一个一级菜单 寻找子菜单
		    	$returnArr = $this->getUserBackMenuLists($data, $v['id'],$v);

		    	if( $parentId==0 ) {
		    		$retunData[] = $returnArr;
		    	} else {
		    		$selfMenu['children'][] = $returnArr;
		    		$retunData = $selfMenu;
		    	}

		   }

		}

		return $retunData;

	}

	/**
	* 插入记录并返回id
	* @author 	xww
	* @param 	array 	$data
	* @return 	id
	*/ 
	public function doCreate($data)
	{
		return $this->sysMenuObj->insertGetId( $data );
	}

	/**
	* 查询记录
	* @author 	xww
	* @param 	int/string 	$id
	* @return 	id
	*/ 
	public function readSingleTon($id)
	{
		return $this->sysMenuObj->find( $id );
	}

	/**
	* 记录更新
	* @author 	xww
	* @param 	int/string 		$id
	* @param 	array 			$data
	* @return 	int
	*/
	public function partUpdate($id, $data)
	{
		return $this->sysMenuObj->where("id", $id)->update($data);
	}

	/**
	* 多记录更新
	* @author 	xww
	* @param 	array 			$ids
	* @param 	array 			$data
	* @return 	int
	*/
	public function multipartPartUpdate($ids, $data)
	{
		return $this->sysMenuObj->whereIn("id", $ids)->update($data);
	}

	/**
	* 获取列表
	* @author 	xww
	* @param 	int/string 		$menuId 当前菜单id要获取比他小的级别 即等级是比他大的或同级别的
	* @return 	array
	*/
	public function getLevelMenu($menuId=null)
	{

		$levelId = null;
		if( !is_null($menuId) ) {
			$data = $this->sysMenuObj->find($menuId);	
			if( !empty($data) ) {
				$levelId = $data['level'];
			}	
		}

		$query = $this->sysMenuObj->where("status", 0)->where("show", 1)->select("id", "name")->orderBy("order", "asc");

		if( !is_null($levelId) ) {
			$query = $query->where("level", "<=", $levelId);
		}

		return $query->get()->toArray();

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

		$query = $this->sysMenuObj->where("status", 0)->orderBy("parentid", "asc")->orderBy("order", "asc")->orderBy("id", "asc")->select("id", "name", "url");

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
	* 获取一级菜单列表对象
	* @author 	xww
	* @param 	int/string 		$skip
	* @param 	int/string 		$size
	* @return 	object
	*/
	public function getParentListsObject($skip, $size, $name=null)
	{

		// 分页对象
		$pageObj = new \VirgoUtil\Page2;

		$query = $this->sysMenuObj->where("status", 0)
								  ->orderBy("parentid", "asc")
								  ->orderBy("order", "asc")
								  ->orderBy("id", "asc")
								  ->select("id", "name", "url");
								  // ->where("parentid", 0)

		if( !is_null($name) ) {
			$query = $query->where("name", "like", "%" . $name . "%");
		}

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
	* 创建全部菜单
	* @author 	xww
	* @return 	json
	*/
	public function createAll( $menusArr )
	{
		
		try{

			DB::beginTransaction();

			$dbParam = $GLOBALS['database_config']['database'];
			$tableParam = $GLOBALS['database_config']['prefix'] . "menus";
			$level = 1;
			$parentid = 0;

			for ($i=0; $i < count($menusArr); $i++) { 
				$menu = $menusArr[$i];

				if( !empty($menu['name']) && !empty($menu['url']) ) {
					$insertData['name'] = $menu['name'];
					$insertData['url'] = $menu['url'];
					$insertData['order'] = $this->getNextIncrement_ver_2($dbParam, $tableParam);
					$insertData['show'] = 1;
					$insertData['level'] = $level;
					$insertData['parentid'] = $parentid;
					$insertData['created_at'] = time();
					$insertData['updated_at'] = time();
					$insertData['status'] = 0;

					$recordId = $this->sysMenuObj->insertGetId( $insertData );

					if( !$recordId ) {
						throw new \Exception("创建菜单失败");
					}

					if( !empty($menu['children']) ) {
						$this->createInnderMenu($menu['children'], $recordId, ++$level);
					}

				}

			}

			DB::commit();
			return true;
		} catch(\Exception $e) {
			DB::rollback();
			return false;
		}

	}

	public function createInnderMenu($menusArr, $parentid, $level)
	{

		$dbParam = $GLOBALS['database_config']['database'];
		$tableParam = $GLOBALS['database_config']['prefix'] . "menus";

		for ($i=0; $i < count($menusArr); $i++) { 
			$menu = $menusArr[$i];

			if( !empty($menu['name']) && !empty($menu['url']) ) {
				$insertData['name'] = $menu['name'];
				$insertData['url'] = $menu['url'];
				$insertData['order'] = $this->getNextIncrement_ver_2($dbParam, $tableParam);
				$insertData['show'] = 1;
				$insertData['level'] = $level;
				$insertData['parentid'] = $parentid;
				$insertData['created_at'] = time();
				$insertData['updated_at'] = time();
				$insertData['status'] = 0;

				$recordId = $this->sysMenuObj->insertGetId( $insertData );

				if( !$recordId ) {
					throw new \Exception("创建菜单失败");
				}

				if( !empty($menu['children']) ) {
					$this->createInnderMenu($menu['children'], $recordId, ++$level);
				}

			}

		}

	}

	public function getMenuDetailWithChildren()
	{
		
		$menus = $this->sysMenuObj
							->select('id', "name", "url", "order", "parentid")
						    ->where('show','=',1)
						    ->where('status','=',0)
						    ->groupBy('id')
							->orderBy('parentid', 'asc')
						    ->orderBy('order', 'asc')
						    ->orderBy('id', 'asc')
							->get()
							->toArray();

		return $this->getUserBackMenuLists($menus,0);
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

	/**
	* 获取所有正常的父级菜单
	* @author 	xww
	* @return 	array
	*/
	public function getParentMenusAll()
	{
		return $this->sysMenuObj->where("status", 0)
								->where("parentid", 0)
								->where("show", 1)
							  	->orderBy("parentid", "asc")
							  	->orderBy("order", "asc")
							  	->orderBy("id", "asc")
							  	->select("id", "name", "url")
							  	->get()
							  	->toArray();
								  
	}

	/**
	* 获取顶层id下属所有菜单
	* @author 	xww
	* @todo
	* @param 	int/string  	$id
	* @return  	array
	*/
	public function getTopMenu($id, $skip=0, $size=1000)
	{

		if( empty($id) ) {
			return null;
		}

		/*判断已经获取的菜单的分页数量*/
		$this->readyTake = 0;

		/*判断已经跳过的菜单的分页数量*/
		$this->readySkip = 0;

		/*要跳过的菜单的分页数量*/
		$this->skip = $skip;

		/*分页数量*/
		$this->data = [];

		$query = $this->sysMenuObj->select("id", 'name', 'updated_at')->where("status", 0)->where("show", 1);

		// 全部数据
		$totalCount = $query->count();

		$singleData = $this->sysMenuObj->select("id", 'name', 'updated_at')->where("status", 0)->where("show", 1)->find($id);

		if(!empty($singleData)) {
			$singleData = $singleData->toArray();
			$singleData['level'] = 1;
			$this->readyTake += 1;
			$this->data[] = $singleData;
		} else {
			return null;
		}

		$this->getInnerData($id, $skip, $size, $totalCount);

		$data = $this->data;

		return $data;

	}

	/**
	* 获取内部数据
	* @author 	xww
	* @return 	array
	*/
	public function getInnerData($pid, $skip, $take, $total)
	{
		
		while($this->readyTake<$take && ($this->readyTake+$this->readySkip)<$total) {
			$rs =$this->getInnerMenuData($pid, 1, $skip, $take, $total);
			
			if($rs) {
				break;
			}
		}

	}

	/**
	* 获取所属单层子菜单
	*/
	public function getInnerMenuData($pid, $level=0, $skip, $take, $total)
	{
		
		$data = $this->sysMenuObj->select("id", 'name', 'updated_at')->where("status", 0)->where("show", 1)->where("parentid", $pid)->get()->toArray();

		// var_dump($pid);

		if(empty($data)) {
			return true;
		}

		$level += 1;
		
		while($this->readyTake<$take && ($this->readyTake+$this->readySkip)<$total) {

			for ($i=0; $i < count($data); $i++) { 

				// 持续拿取
				if($this->readySkip>0) {
					$this->readySkip -= 1;
					continue;
				}

				if($this->readyTake<$take && ($this->readyTake+$this->readySkip)<$total) {
					$data[$i]['level'] = $level;

					array_push($this->data, $data[$i]);

					$this->readyTake += 1;
					
					$rs = $this->getInnerMenuData($data[$i]['id'], $level, $skip, $take, $total);
				}

			}

			break;

		}
		
		return true;

	}

}
