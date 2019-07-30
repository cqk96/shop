<?php
namespace VirgoApi\CarouselImg;
class ApiCarouselImgController extends \VirgoApi\ApiBaseController
{

	/**
	* api参数数组
	* @var array
	*/
	private $_configs;

	public function __construct()
	{
		$this->functionObj = new \VirgoUtil\Functions;
		$this->model = new \VirgoModel\CarouselImgModel;
		$this->_configs = parent::change();
	}

	/**
	* 获取轮播图列表
	* @author 	xww
	* @return 	json 	string/object
	*/
	public function lists()
	{
		
		try{

			//获取用户
			// $user = $this->getUserApi($this->_configs);

			$needs = ['cover', 'url'];

			$page = isset($this->_configs['page'])? (int)$this->_configs['page']:null;
			$size = isset($this->_configs['size'])? (int)$this->_configs['size']:null;

			if(!is_null($page) && !is_null($size)) {
				// 分页
				$page = $page? $page:1;
				$page -= 1;
				$skip = $page*$size;
			}

			$data = $this->model->getLists($skip, $size, $needs);
			$dataCount = $this->model->getListsCount();

			$data = empty($data)? null:$data;

			for ($i=0; $i < count($data); $i++) { 
				$data[$i]['cover'] = "http://".$_SERVER['HTTP_HOST'].$data[$i]['cover'];

				if( empty($data[$i]['url']) ) {
					$data[$i]['url'] = "";
				} else {
					if( stripos($data[$i]['url'], "http")===false ) {
						$data[$i]['url'] = "http://" . $_SERVER['HTTP_HOST'] . $data[$i]['url'];
					}
				}

			}

			$totalPage = is_null($size)? 1:ceil( $dataCount / $size );
			$totalPage = is_null($data)? 0:$totalPage;

			$return = $this->functionObj->toAppJson(['data'=>$data, 'totalPage'=>$totalPage], '001', '获取轮播图列表成功', true);

		} catch(\Exception $e) {
			$return = $this->functionObj->toAppJson(null, str_pad($e->getCode(), 3, 0,STR_PAD_LEFT), $e->getMessage(), false);
		} finally {
			//输出
			$this->responseResult($return);
		}

	}

}