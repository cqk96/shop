<?php
namespace VirgoModel;
use VirgoApi\VirgoInterface as VirgoInterface;
class ExpressBirdExpress implements VirgoInterface\ExpressInterface {

	private $ebusinessID;

	private $appKey;

	private $_code;

	private $_number;

	private $traceUrl = "http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx";

	public $resultArr = [];

	public function __construct($EBusinessID, $appKey)
	{
		$this->ebusinessID = $EBusinessID;
		$this->appKey = $appKey;
	}

	public function setSearchTraceParam($code, $number)
	{
		$this->_code = $code;
		$this->_number = $number;
	}

	public function getTraceInfo()
	{
		
		if( empty($this->ebusinessID) || empty($this->appKey) ) {
			throw new \Exception("请先进行配置文件配置", 1);
		}

		if( empty($this->_code) || empty($this->_number) ) {
			throw new \Exception("尚未设置code, number", 1);
		}

		/*发起请求*/
		$data['ShipperCode'] = $this->_code;
		$data['LogisticCode'] = $this->_number;

		$jsonStr = json_encode( $data );

		$request['RequestData'] = urlencode($jsonStr);
		$request['EBusinessID'] = $this->ebusinessID;
		$request['RequestType'] = '1002';
		$request['DataSign'] = $this->getDataSign($jsonStr);

		$headers['Content-Type'] = 'application/x-www-form-urlencoded';
		$headers['charset'] = 'utf-8';

		return $this->doPostRequest($this->traceUrl, $request, $headers);

	}

	public function getDataSign($dataJsonStr)
	{
		
		$md5Str = md5( $dataJsonStr . $this->appKey );

		$base64Str = base64_encode( $md5Str );

		$urlencodeStr = urlencode( $base64Str );

		return $urlencodeStr;

	}

	public function doPostRequest($url, $data=null, $header=[])
	{
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		if( !empty($header) ) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}

		$out = curl_exec($ch);
		curl_close($ch);
		return $out;

	}
	
}