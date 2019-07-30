<?php
/**
* Virgo  分页   基于Eloquent三方
* @author   	xww
* @version      0.1.1
*/
namespace VirgoUtil;
Class Page {

	/**
	* 属性
	*/

	//总条数
	public $total_count = 0;

	//总页数
	public $total_page = 0;

	//当前页数
	public $current_page = 1;

	//分页条数
	public $per_record = 0;

	//分页查询参数
	public $query = [];

	//跳转地址
	public $url = '';

	//设置and查询参数
	public $whereAnd = [];

	//设置or查询参数
	public $whereOr = [];

	//数据集
	public $data = [];

	//分页样式
	public $pagination = '';

	public function __construct()
	{

	}

	/**
	* @param 	[$eModel]    		Eloquent 对应类名
	* @param 	[$url]    			对应跳转地址
	* @param  	[$count]	 		每页条数默认10条
	* @param 	[$orderCondition]	排序条件数据形式
	* @param 	[$groupCondition]	分组条件数据形式
	* @return 	page object
	*/
	public function page($eModel,$url,$count=10,$orderCondition=[],$groupCondition=[])
	{
		try{
			if(empty($eModel) || empty($url)) {
				throw new \Exception("Error Param In EModel/Url Not Null", 1);
			}

			$this->url = $url;

			$this->per_record = $count;

			$eloquentObj = new  $eModel;

			//and查询参数
			if(!empty($this->whereAnd)){
				foreach ($this->whereAnd as $where_and_op_key => $where_and_op_arr) {

					if($where_and_op_key=='between'){
						if(!is_array($op_val_1[1])){
							throw new \Exception("between params must be array", 1);
						}
						foreach ($where_and_op_arr as $where_and_key => $where_and_val) {
							$eloquentObj = $eloquentObj->whereBetween($where_and_val[0], $where_and_val[1]);
						}
					} else if($where_and_op_key=='not between'){
						if(!is_array($op_val_1[1])){
							throw new \Exception("not between params must be array", 1);
						}
						foreach ($where_and_op_arr as $where_and_key => $where_and_val) {
							$eloquentObj = $eloquentObj->whereNotBetween($where_and_val[0], $where_and_val[1]);
						}
					} else if($where_and_op_key=='in'){
						if(!is_array($op_val_1[1])){
							throw new \Exception("in params must be array", 1);
						}
						foreach ($where_and_op_arr as $where_and_key => $where_and_val) {
							$eloquentObj = $eloquentObj->whereIn($where_and_val[0], $where_and_val[1]);
						}
					} else if($where_and_op_key=='not in'){
						if(!is_array($op_val_1[1])){
							throw new \Exception("not in params must be array", 1);
						}
						foreach ($where_and_op_arr as $where_and_key => $where_and_val) {
							$eloquentObj = $eloquentObj->whereNotIn($where_and_val[0], $where_and_val[1]);
						}
					} else if($where_and_op_key=='null'){
						foreach ($where_and_op_arr as $where_and_key => $where_and_val) {
							$eloquentObj = $eloquentObj->whereNull($where_and_val);
						}
					} else {
						foreach ($where_and_op_arr as $where_and_key => $where_and_val) {
							$eloquentObj = $eloquentObj->where($where_and_val[0], $where_and_op_key, $where_and_val[1]);	
						}
					}

				}

			}

			//or查询参数
			if(!empty($this->whereOr)){
				foreach ($this->whereOr as $where_or_op_key => $where_or_op_arr) {
					foreach ($where_or_op_arr as $where_or_key => $where_or_val) {
						$eloquentObj = $eloquentObj->orWhere($where_or_val[0], $where_or_op_key, $where_or_val[1]);	
					}
				}
			}

			if(!empty($orderCondition)){
				foreach ($orderCondition as $order_key => $order_value) {
					$eloquentObj = $eloquentObj->orderBy($order_value[0],$order_value[1]);
				}
			}

			if(!empty($groupCondition)){
				foreach ($groupCondition as $group_key => $group_value) {
					$eloquentObj = $eloquentObj->groupBy($group_value[0]);
				}
			}

			//计算总数
			$this->total_count = $eloquentObj->count();

			$this->total_page = ceil($this->total_count/$count);

			//分页
			if(!empty($_GET['page'])){
				$page = (int)$_GET['page'];
				$page = empty($page)? 0:$page-1;
				$skip = $page*$count;
				$eloquentObj = $eloquentObj->skip($skip);
				$this->current_page = ($page+1);
			}

			//获取分页样式
			$this->getPageStyle_ver_1($this->total_page);

			//读取条数
			$eloquentObj = $eloquentObj->take($count);

			//读取数据
			$this->data = $eloquentObj->get()->toArray();

			return $this;

		} catch(\Exception $e){
			echo $e->getMessage();
		}

	}

	//获取分页查询参数
	public function getPageQuery()
	{
		return $this->query;
	}

	//分页查询参数携带
	public function setPageQuery($paramArr)
	{
		
		foreach ($paramArr as $key => $value) {
			$this->query[$key] = $value;
		}

	}

	//设置and查询参数
	public function setWhereAnd($op,$kv)
	{
		
		if(!isset($this->whereAnd[$op])){
			$this->whereAnd[$op] = array();
		}

		array_push($this->whereAnd[$op],$kv);

	}

	//获取and查询参数
	public function getWhereAnd()
	{

		return $this->whereAnd;

	}

	//设置or查询参数
	public function setWhereOr($op,$kv)
	{
		
		if(!isset($this->whereOr[$op])){
			$this->whereOr[$op] = array();
		}

		array_push($this->whereOr[$op],$kv);

	}

	//获取and查询参数
	public function getWhereOr()
	{

		return $this->whereOr;

	}

	//要显示的分页样式 版本1 可用于后台
	public function getPageStyle_ver_1($all_page)
	{
		
		if($all_page==0){
			$this->pagination = '';
			return false;
		}

		//跳转地址
		$go_href = $this->url.'?page=';

		//页数
		$sub_li = '';

		//上一页
		$prev = "javascript:void(0);";

		//下一页
		$next = "javascript:void(0);";

		//参数
		$query = '';

		//带参
		if(!empty($this->query)){
			$queryArr = array();
			foreach ($this->query as $key => $value) {
				$temp = $key.'='.$value;
				array_push($queryArr, $temp);
			}
			$query = '&'.implode($queryArr, '&');
		}

		//间隔页数
		$defaultSplite = 5;
		// 缺省页
		$restNext = '';
		//渲染数
		$readerCount = 1;
		for ($i=1; $i <=$all_page; $i++) {

			if($this->current_page==$i){
				$active = 'active';
			} else {
				$active = '';
			}

			//缺省前面页
			$throwPosition = $this->current_page-$defaultSplite<0? 0:$this->current_page-$defaultSplite;

			if($i<=$throwPosition || $readerCount>$defaultSplite){
				continue;
			}

			$sub_li = $sub_li."<li class='".$active."'><a href='".$go_href.$i.$query."'>".$i."</a></li>";
			//已经渲染的页数
			$readerCount++;
		}

		if($this->total_page!=1) {
			
			//上一页组装
			if($this->current_page-1<=1){
				$prev = $go_href.'1'.$query;
			} else {
				$prev = $go_href.($this->current_page-1).$query;
			}

			//下一页组装
			if($this->current_page+1>=$this->total_page){
				$next = $go_href.$this->total_page.$query;
			} else {
				$next = $go_href.($this->current_page+1).$query;
			}

		}

		

		$this->pagination = "<ul class='page-pagination'>".
					  "<li class=''><a href='".$prev."'><</a></li>".
					  $sub_li.
					  "<li><a href='".$next."'>></a></li>".
					"</ul>";


	}

}