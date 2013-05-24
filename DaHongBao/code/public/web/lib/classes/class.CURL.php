<?php
/*
Sean Huber CURL library
This library is a basic implementation of CURL capabilities.
It works in most modern versions of IE and FF.
==================================== USAGE ====================================
It exports the CURL object globally, so set a callback with setCallback($func).
(Use setCallback(array('class_name', 'func_name')) to set a callback as a func
that lies within a different class)
Then use one of the CURL request methods:
get($url);
post($url, $vars); vars is a urlencoded string in query string format.
Your callback function will then be called with 1 argument, the response text.
If a callback is not defined, your request will return the response text.

[Informational 1xx]
100="Continue"
101="Switching Protocols"
[Successful 2xx]
200="OK"
201="Created"
202="Accepted"
203="Non-Authoritative Information"
204="No Content"
205="Reset Content"
206="Partial Content"
[Redirection 3xx]
300="Multiple Choices"
301="Moved Permanently"
302="Found"
303="See Other"
304="Not Modified"
305="Use Proxy"
306="(Unused)"
307="Temporary Redirect"
[Client Error 4xx]
400="Bad Request"
401="Unauthorized"
402="Payment Required"
403="Forbidden"
404="Not Found"
405="Method Not Allowed"
406="Not Acceptable"
407="Proxy Authentication Required"
408="Request Timeout"
409="Conflict"
410="Gone"
411="Length Required"
412="Precondition Failed"
413="Request Entity Too Large"
414="Request-URI Too Long"
415="Unsupported Media Type"
416="Requested Range Not Satisfiable"
417="Expectation Failed"
[Server Error 5xx]
500="Internal Server Error"
501="Not Implemented"
502="Bad Gateway"
503="Service Unavailable"
504="Gateway Timeout"
505="HTTP Version Not Supported"

*/
class CURL {
	var $callback = false;
	public static $instances = array();
	private $timeout = 30; //默认超时时间为30秒
	/**
	 * @var bool 是否在curl内容中返回header信息
	 */
	private $headerEnabled = true;

	function getInstance()
	{
    	if(isset(self::$instances[1])) {
	    	$instance = self::$instances[1];
	    	if(!empty($instance) && is_object($instance)) {
	    		return $instance;
	    	}
    	}
    	$instance = new CURL();
    	self::$instances[1] = $instance;
    	return $instance;
	}
	function setCallback($func_name) {
		$this->callback = $func_name;
	}
	function setHeaderEnable($enabled) {
		$this->headerEnabled = $enabled;
	}
	function setTimeout($second)
	{
		$this->timeout = $second;
	}
	function doRequest($method, $url, $vars) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		
		curl_setopt($ch, CURLOPT_HEADER, $this->headerEnabled);
		if(isset($_SERVER['HTTP_USER_AGENT'])) {
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		}
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		if ($method == 'POST') {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
		}
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

		//获得请求状态及数据
		$data = curl_exec($ch);
		$info = curl_getinfo($ch);
		$info['data'] = $data;
		curl_close($ch);

		if ($data) {
			if ($this->callback) {
				$callback = $this->callback;
				$this->callback = false;
				$info['data'] = call_user_func($callback, $info['data']);
			}
		}
		return $info;
	}
	function get($url) {
		return $this->doRequest('GET', $url, 'NULL');
	}
	function get_contents($url) {
		$this->setHeaderEnable(false);
		$info = $this->doRequest('GET', $url, 'NULL');
		if(empty($info['http_code']) === 0) {
			return false;
		}
		return $info['data'];
	}
	function post($url, $vars) {
		return $this->doRequest('POST', $url, $vars);
	}
}
?>