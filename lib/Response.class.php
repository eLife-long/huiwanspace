<?php
/**
 *返回数据格式化类
 *tags
 *@author Joker-Long
 *编写日期：2016年8月13日上午9:32:57
 */
class Response {
	const JSON = "json";
	/**
	* 按综合方式输出通信数据
	* @param integer $code 状态码
	* @param string $message 提示信息
	* @param array $data 数据
	* @param string $type 数据类型
	* return string
	*/
	public static function show($code, $message = '', $data = array(), $type = self::JSON) {
		if(!is_numeric($code)) {
			return '';
		}

		$type = isset($_GET['format']) ? $_GET['format'] : self::JSON;

		$result = array(
			'code' => $code,
			'message' => $message,
			'data' => $data,
		);

		if($type == 'json') {
			self::json($code, $message, $data);
			exit;
		} elseif($type == 'array') {
			var_dump($result);
		} elseif($type == 'xml') {
			self::xmlEncode($code, $message, $data);
			exit;
		} else {
			// TODO
		}
	}
	/**
	* 按json方式输出通信数据
	* @param integer $code 状态码
	* @param string $message 提示信息
	* @param array $data 数据
	* return string
	*/
	public static function json($result = array('status'=>400,'sessionId'=>null,'message' => null, 'datas' => array())) {
		
		/*if(!is_numeric($result['status'])) {
			return '';
		}*/

		$result = array(
			'status' => $result['status']?$result['status']:400,
		    'sessionId' => $result['sessionId']?$result['sessionId']:null,
			'message' => urlencode($result['message'])?urlencode($result['message']):null,
			'datas' => $result['datas']?$result['datas']:null
		);
		$result = json_encode($result);
		$result = urldecode($result);
		$count = 0;
		$result = str_replace("\n", "\\n", $result,$count);//转义
		$result = str_replace("\r", "\\r", $result,$count);
		return $result;
	}

	/**
	* 按xml方式输出通信数据
	* @param integer $code 状态码
	* @param string $message 提示信息
	* @param array $data 数据
	* return string
	*/
	public static function xmlEncode($result = array('status'=>400,'sessionId'=>null,'message' => null, 'datas' => array())) {
		if(!is_numeric($result['status'])) {
			return '';
		}
		$result = array(
			'status' => $result['status']?$result['status']:400,
		    'sessionId' => $result['sessionId']?$result['sessionId']:null,
			'message' => urlencode($result['message'])?urlencode($result['message']):null,
			'datas' => $result['datas']?$result['datas']:null
		);

		header("Content-Type:text/xml");
		$xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
		$xml .= "<root>\n";

		$xml .= self::xmlToEncode($result);

		$xml .= "</root>";
		$result = json_encode($xml);
		return urldecode($xml);
	}

	public static function xmlToEncode($data) {

		$xml = $attr = "";
		foreach($data as $key => $value) {
			if(is_numeric($key)) {
				$attr = " id='{$key}'";
				$key = "item";
			}
			$xml .= "<{$key}{$attr}>";
			$xml .= is_array($value) ? self::xmlToEncode($value) : $value;
			$xml .= "</{$key}>\n";
		}
		return $xml;
	}

}