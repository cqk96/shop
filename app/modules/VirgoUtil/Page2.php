<?php
/**
* 只是对数据其他操作进行一个包装
* @author   	xww
* @version      0.1.1
*/
namespace VirgoUtil;
Class Page2 {
	//数据
	public $data;

	//总记录数
	public $totalCount;

	//分页条数
	public $size=10;

	//分页样式
	public $pagination;

	//跳转地址
	public $url;

	//分页查询参数
	public $query = [];

	//当前页数
	public $current_page;

	public function __construct()
	{
		$this->current_page = empty($_GET['page'])? 1:$_GET['page'];
	}

	public function doPage()
	{
		$this->getPagination();
		return $this;
	}

	//设置跳转地址
	public function setUrl($url)
	{
		$this->url = $url;
	}

	//设置数据
	public function setData($data)
	{
		$this->data = $data;
	}

	//设置总记录数
	public function setTotalCount($totalCount)
	{
		$this->totalCount = $totalCount;
	}

	//设置分页大小
	public function setSize($size)
	{
		$this->size = $size;
	}

	//分页查询参数携带
	public function setPageQuery($paramArr)
	{
		
		foreach ($paramArr as $key => $value) {
			$this->query[$key] = $value;
		}

	}

	/**
	* 返回分页样式
	*/
	protected function getPagination()
	{
		
		//不进行分页
		if($this->size==0){
			$this->pagination = '';
			return false;
		}

		// if(empty($this->url)){
		// 	echo "分页地址不为空";
		// 	exit();
		// }

		//跳转地址
		$go_href = stripos($this->url, "?")!==false? $this->url.'&page=':$this->url.'?page=';

		//页码
		$sub_li = '';

		//上一页
		$prev = "javascript:void(0);";

		//下一页
		$next = "javascript:void(0);";

		//参数
		$query = '';

		$all_page = ceil($this->totalCount/$this->size);

		//带参
		$input = '';
		if(!empty($this->query)){
			$queryArr = array();
			foreach ($this->query as $key => $value) {
				$temp = $key.'='.$value;
				$input = $input."<input type='hidden' name='".$key."' value='".$value."' />";
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
				$input = $input."<input type='hidden' id='paginationPageInput' name='page' value='".$i."' />";
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

		//第一页
		$first_page = "javascript:void(0);";

		//最后一页
		$last_page = "javascript:void(0);";

		//跳转页数
		// $selectContentPage = "<option value='".$go_href.'1'.$query."'>第1页</option>";
		$selectContentPage = "<option value='1'>第1页</option>";

		if($all_page!=1) {
			
			//上一页组装
			if($this->current_page-1<=1){
				$prev = $go_href.'1'.$query;
			} else {
				$prev = $go_href.($this->current_page-1).$query;
			}

			//第一页组装
			$first_page = $go_href.'1'.$query;

			//最后一页组装
			$last_page = $go_href.$all_page.$query;			

			//下一页组装
			if($this->current_page+1>=$all_page){
				$next = $go_href.$all_page.$query;
			} else {
				$next = $go_href.($this->current_page+1).$query;
			}

			//中间所有页数组装
			for ($i=2; $i <=$all_page; $i++) {
				$selected = '';
				if($i==$this->current_page){
					$selected = 'selected';
				}
				$selectContentPage = $selectContentPage."<option value='".$i."' ".$selected.">第".$i."页</option>";//".$go_href.($i-1).$query."
			}

		}

		//显示跳转页
		$selectPage = "<form id='goForm' action='".$this->url."' method='get' style='display: inline-block;'>".$input."<select >".$selectContentPage."</select><button type='submit' class='btn btn-primary btn-xs' style='margin-left:10px'>go!</button></form>";

		

		$this->pagination = "<ul class='page-pagination'>".
					  "<li class=''><a href='".$first_page."'><<</a></li>".
					  "<li class=''><a href='".$prev."'><</a></li>".
					  $sub_li.
					  "<li><a href='".$next."'>></a></li>".
					  "<li><a href='".$last_page."'>>></a></li>".
					  $selectPage.
					"</ul>";

	}

}