<?php
namespace VirgoFront;
use Illuminate\Database\Capsule\Manager as DB;
class TestController extends BaseController {

	public $size = 1 ;
	
	public function index()
	{
		require dirname(__FILE__)."/../views/test/index.php";
	}

	//测试 socket
	public function doSocket()
	{
		//配置地址
		/*$address = '127.0.0.1';
		$port = '8082';

		//防止超时
		set_time_limit(0);

		//创造通讯节点
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or exit('Could not create socket');

		//绑定端口
		socket_bind($socket, $address, $port) or exit('Could not do bind');

		//监听事件
		socket_listen($socket) or exit('Could not do listen');

		//不这样做  链接一次后就会被废弃
		do {

			//等待客户端接入  此时会返回一个新的socket节点
			$socket2 = socket_accept($socket) or exit('Could not do accept');

			//获取客户端输入
			$clientInput = socket_read($socket2,'1204') or exit('Could not do socket');

			//返回处理的结果
			$output = "获取的输入: ".$clientInput;

			//返回给客户端
			socket_write($socket2, $output)or exit('Could not do write');

			//关闭socket
			socket_close($socket2);
			//socket_close($socket);
			//usleep(10000);
		} while (true);

		socket_close($socket);
		*/

	}

	/**
	* 测试memcache与数据库操作
	* @todo     逻辑处理
	* @return   void
	*/

	public function testMemcache()
	{
		$baseModelObj = new \VirgoModel\BaseModel;
		$baseModelObj->linkMemcache();
		//$data = $baseModelObj->doMemcache('newsData', '\\VirgoModel\\ProjectModel', 'lists', [],20);//
		//var_dump($data->toArray());
		//var_dump($baseModelObj->deleteMemcacheKey('newsData'));
		//var_dump($baseModelObj->getMemcacheKey('newsData'));
		$baseModelObj->closeMemcache();
	}

	/**
	* 导入用户的信息到员工表中 (不存在于用户表的数据)
	* @author 	xww
	* @return 	void
	*/ 
	public function importUserToStaff()
	{
		
		try{
			$ids = [7,13,27,32,35,36,37,38];

			$curTime = time();

			// 获取员工表特定用户
			$users  = \EloquentModel\User::leftJoin("staff", "staff.user_id", "=", "users.id")
										->whereIn("users.id", $ids)
										->where("users.is_deleted", 0)
										->whereNull("staff.id")
										->select("users.*", "staff.id as staffId")
										->get()
										->toArray();

			$temp = [];

			// 获取最大工号
			$workNum = \EloquentModel\Staff::where("is_deleted", 0)->max("work_num");

			DB::beginTransaction();

			for($i=0; $i<count($users); $i++) {
				// 判断是否有staff表结果集
				if(empty($users[$i]['staffId'])){

					$workNum++;

					// 新建记录
					$temp['name'] = empty($users[$i]['nickname'])? '':$users[$i]['nickname'];
					$temp['phone'] = $users[$i]['user_login'];
					$temp['gender'] = $users[$i]['gender'];
					$temp['create_time'] = $curTime;
					$temp['update_time'] = $curTime;
					$temp['work_num'] = $workNum;
					$temp['address'] = '';
					$temp['status'] = 1;
					$temp['user_id'] = $users[$i]['id'];
					$rs = \EloquentModel\Staff::insert($temp);
					if(!$rs){
						throw new \Exception("新建用户id: ".$users[$i]['id']."失败");
					}

				}
			}

			DB::commit();

		} catch(\Exception $e) {
			DB::rollback();
			echo "error: ".$e->getMessage()."<br />";
		} finally {
			// complete
			echo "完成";
		}

	}

	/**
	* 测试pdf文件
	* @author 	xww
	* @return 	void
	*/ 
	public function testPDF()
	{
		
		ob_clean();

		// create new PDF document
		$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);

		$pdf->SetAuthor('Nicola Asuni');

		$pdf->SetTitle('TCPDF Example 001');

		$pdf->SetSubject('TCPDF Tutorial');

		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));

		$pdf->setFooterData(array(0,64,0), array(0,64,128));
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		// set default monospaced font

		// $pdf->setPrintHeader(false);
		// $pdf->setPrintFooter(false);

		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		// ---------------------------------------------------------
		// set default font subsetting mode
		$pdf->setFontSubsetting(true);
		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		// $pdf->SetFont('dejavusans', '', 14, '', true);
		$pdf->SetFont('stsongstdlight', '', 20);
		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();
		// set text shadow effect
		// $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
		// Set some content to print
$html = <<<EOD
		<div style='width: 375px;height: 225px; overflow: hidden;position: relative;'>
		<img style='display: block;width: 100%;height: auto;position: relative;z-index: 1;' src="/images/template/front-face-bg.png">

		<!-- 二维码 -->
		<div style="width: 328px;left: 40px;height: 145px;top: 40px;border: 1px solid transparent;position: absolute;z-index: 2;">
			<div style="width:40%;height: 100%;float: left;overflow:hidden;">
				<div style="margin-top:30px;margin-bottom:30px;margin-left: 12px;width: 90px;height: 90px; overflow:hidden;">
					<img style="width: 100%;height:auto" src="/images/template/qrcode.png">
				</div>
			</div>

			<!-- @todo 椭圆+ 渐变 -->
			<div style="width: 6%; height: 90%;border-radius: 100%;top: 8px; right: 49%;position: absolute;background: linear-gradient(right, #fff 0% , #a4a4a4 50%, #fff 100%);background: -webkit-linear-gradient(right, #fff 0% , #a4a4a4 50%, #fff 100%); background: -o-linear-gradient(right, #fff 0% , #a4a4a4 50%, #fff 100%); background: -moz-linear-gradient(right, #fff 0% , #a4a4a4 50%, #fff 100%);z-index: 1;    box-shadow: 0px 0px 9px #ebe9e9;">

			</div>

			<div style="width: 50%;height: 100%;float: right; padding-left: 10px;
    border-left: 1px solid #d9d9d9;position: relative;z-index: 2;background-color: #FFF;">

				<p style="font-size: 13px;color: #313131;margin-bottom: 0px;padding-left: 2px;">名称</p>
				<p style="font-size: 12px;color: #313131;margin-top: 6px;margin-bottom: 0px;padding-left: 2px;">职务</p>
				<table style="width: 100%;margin-top: 4px;">
					<tbody>
						<tr>
							<td valign="top" width="10%">
								<img style="width: 7.5px; height: 11.5px;margin-top: 4px;" src="/images/template/location.png">
							</td>
							<td style="font-size: 8px;padding-top: 4px;padding-bottom: 4px;"  width="90%">
								住址
							</td>
						</tr>
						<tr>
							<td valign="top" width="10%">
								<img style="width: 8px; height: 8.5px;margin-top: 5px;" src="/images/template/phone.png">
							</td>
							<td style="font-size: 8px;padding-top: 4px;padding-bottom: 4px;"  width="90%">
								电话
							</td>
						</tr>
						<tr>
							<td valign="top" width="10%">
								<img style="width: 10px; height: 6.5px;margin-top: 6px;" src="/images/template/email.png">
							</td>
							<td style="font-size: 8px;padding-top: 4px;padding-bottom: 4px;"  width="90%">
								邮箱
							</td>
						</tr>
						<tr>
							<td valign="top" width="10%">
								<img style="width: 9.5px; height: 9.5px;margin-top: 5px;" src="/images/template/internet.png">
							</td>
							<td style="font-size: 8px;padding-top: 4px;padding-bottom: 4px;"  width="90%">
								网址
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

	</div>

	<div style='width: 375px;height: 225px; overflow: hidden;'>
		<img style='display: block;width: 100%;height: auto;' src='/images/template/bg-1.jpg' />
	</div>		
EOD;

		// Print text using writeHTMLCell()
		// $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
		$pdf->writeHTML($html, false, false, false, '');

		$pdf->lastPage();

		// ---------------------------------------------------------
		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output($_SERVER["DOCUMENT_ROOT"].'example_001.pdf', 'I');
	}

	/**
	* 用户
	*/ 
	public function parseUser()
	{
		
		try{

			// @todo size 非1报错
			if(count($_GET)!=1){ throw new \Exception("Wrong Request", 1); }

			// 将参数提取
			$paramArr = array_keys($_GET);
			$address = $paramArr[0];

			/*旧规则*/
			$orderRule = [
				'/staffInfo/1/generalManager/chengduo'
			];

			if(in_array($address, $orderRule)) {
				// 第一种特殊规则
				$format = "/^\/staffInfo\/(\d*)?\/(.*?)\/([^\?\/]*)/i";
				preg_match($format, $address, $matches);
				// 进行重定向
				header("Location: /staffInfo/".$matches[1]."/".$matches[3]);
				exit();
			}

			// 通用规则
			$normalFormat = "/^\/staffInfo\/(\d*)?\/([^\?\/]*)/i";
			$canMatch = preg_match($normalFormat, $address, $matches);

			// 匹配失败 扔出错误
			if(!$canMatch) {
				throw new \Exception("Error Processing Request");
			}

			// 对应员工表id
			$staffId = $matches[1];

			$spellEnglish = $matches[2];

			// 特殊的对应 (与数据库不符) 对应员工表id
			$specialRelation = [];

			// 如果符合特殊替换规则就就行替换
			if(!empty($specialRelation) && !empty($specialRelation[$staffId])) {
				$staffId = $specialRelation[$staffId];
			}

			// $data = \EloquentModel\User::leftJoin("staff", "staff.user_id", "=", "users.id")
			// 							   ->leftJoin("jobs", "jobs.id", "=", "staff.job_id")
			// 							   ->leftJoin("departments", "departments.id", "=", "staff.department_id")
			// 							   ->where("users.is_deleted", 0)
			// 							   ->where("staff.is_deleted", 0)
			// 							   ->where("users.id", $userId)
			// 							   ->select("staff.*", "jobs.name as jobName", "departments.name as departmentName", "departments.section", "users.gender", "users.age", "users.introduce")
			// 							   ->take(1)
			// 							   ->get()
			// 							   ->toArray();

			// if(empty($data)) { throw new \Exception("用户不存在"); }

			// $data[0]['genderImgUrl'] = $this->getInfoGenderImgUrl($data[0]['gender']);
		
			// if($userId==29) {
			// 	$data[0]['email'] = "chengduo@hzhanghuan.com";
			// } else {
			// 	$data[0]['email'] = '';
			// }
			
			// if(empty($data[0]['introduce'])){
			// 	$data[0]['introduce'] = '暂无';
			// } else {
			// 	if(mb_strlen($data[0]['introduce'])>10){
			// 		$data[0]['introduce'] = mb_substr($data[0]['introduce'], 0, 10)."...";
			// 	}
			// }

			// require dirname(__FILE__)."/../../views/front/company/info-ver2.php";

		} catch(\Exception $e) {
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

	public function test2()
	{
		
		$data = \EloquentModel\User::leftJoin("staff", "staff.user_id", "=", "users.id")
										   ->leftJoin("jobs", "jobs.id", "=", "staff.job_id")
										   ->leftJoin("departments", "departments.id", "=", "staff.department_id")
										   ->where("users.is_deleted", 0)
										   ->where("staff.is_deleted", 0)
										   ->where("users.id", 29)
										   ->select("staff.*", "jobs.name as jobName", "departments.name as departmentName", "departments.section", "users.gender", "users.age", "users.introduce")
										   ->take(1)
										   ->get()
										   ->toArray();

		// 性别
		$userId = 29;
		$data[0]['genderImgUrl'] = $this->getInfoGenderImgUrl($data[0]['gender']);
		
		if($userId==29) {
			$data[0]['email'] = "chengduo@hzhanghuan.com";
		} else {
			$data[0]['email'] = '';
		}

		if(empty($data[0]['introduce'])){
			$data[0]['introduce'] = '暂无';
		} else {
			if(mb_strlen($data[0]['introduce'])>10){
				$data[0]['introduce'] = mb_substr($data[0]['introduce'], 0, 10)."...";
			}
		}

		require dirname(__FILE__)."/../../views/front/company/info-ver2.php";
	}

	/**
	* 获取个人信息性别地址
	* @author 	xww
	* @param 	int/string 		gender
	* @return 	void
	*/ 
	public function getInfoGenderImgUrl($gender)
	{
		switch ((int)$gender) {
			case 1:
				return '/images/front/info/info-icon/male.png';
				break;
			case 2:
				return '/images/front/info/info-icon/female.png';
				break;
			default:
				return '/images/front/info/info-icon/secret-gender.png';
				break;
		}
	}

	public function showLog()
	{
		error_log(3, 3, $_SERVER['DOCUMENT_ROOT']."/2.txt");
	}
	
	/**
	* 用于车百用验证码
	* @author 	xww
	* @return 	void
	*/ 
	public function showCaptcha()
	{
		
		ob_clean();
		try{

			if(empty($_GET['code'])) { throw new \Exception("验证码字符串不为空"); }

			if(empty($_GET['appKey'])) { throw new \Exception("key不为空"); }

			if($_GET['appKey']!=md5('hanghuanB409')) { throw new \Exception("key不正确"); }

			// 生成验证码
			// 初始化
			$captchaObj = new \VirgoUtil\Captcha($_GET['code'], 180, 50);

			// 设置背景颜色
			$captchaObj->set_bg_color(255 ,255, 255);

			// 设置噪点颜色
			$captchaObj->setPixel(false);//(117 ,114, 255);

			// 设置文本颜色
			$captchaObj->set_str_color(169 ,171, 238);

			// 设置字号
			$captchaObj->setFontSize(17);

	        // 生成图形验证码
			$captchaObj->verifica();
		} catch(\Exception $e) {
			echo "<h1>".$e->getMessage()."</h1>";
		}

	}

	/**
	* 测试下载pdf
	* @author 	xww
	* @return 	void
	*/ 
	public function pdfDownload()
	{
		
		ob_clean();
		$fpath = $_SERVER['DOCUMENT_ROOT']."/userCardPdf/1511255303_1.pdf";
		// if(file_exists($fpath)) {
		// 	$file = fopen($fpath, "r");
		// 	Header("Content-type:application/pdf");    
		//     Header("Accept-Ranges: bytes");    
		//     Header("Accept-Length: " . filesize($fpath));    
		//     Header("Content-Disposition: attachment; filename='名片.pdf'"); // 输出文件内容
		//     echo readfile($fpath);  
  //   		fclose($file);
		// }
		Header("Content-type:application/pdf");    
	    Header("Content-Length: " . filesize($fpath));    
	    Header("Content-Disposition: attachment; filename='名片.pdf'"); // 输出文件内容
	    echo readfile($fpath);  

	}

	/**
	* 用来测试函数 以及自己写的方法
	* @author 	xww
	* @return 	void
	*/
	public function funcTest()
	{
		
		$userObj = new \VirgoModel\UserModel;
		$approverUserArr = $userObj->getNextApprover(2, ['2103']);

		var_dump($approverUserArr);

	}

	/**
	* 显示文件上传页面
	* @author 	xww
	* @return 	void
	*/
	public function showForm()
	{

		// 作物类型对象
		$model = new \VirgoModel\GoodsToSetmealToPropertiesModel;
		$rs = $model->getSetmealProperyArr( [10] );
		print_r( $rs );

		$jsonStr = '[{"setname": "套餐3", "skus": "123", "setprice": 112300, "setmealid": 173, "properties": [{"propertiesname": "颜色", "groupProperty": [{"chinese_name": "白色", "foreign_name": "白色", "image": "", "rel_id": "226", "group_id": 0 }] }] }]';
		print_r( json_decode($jsonStr, true) );
	}


}

?>	