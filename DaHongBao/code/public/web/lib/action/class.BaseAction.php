<?
/**
 * class.BaseAction.php
 * --------------------
 * 参照于struts框架设计,作为Controller层的基类
 * 注: HttpReuqest,HttpResponse类在此文件中定义,是为了快速装载的需要.
 *
 * @author XuLH <x.huan@163.com>
 * @date 2006-06-17
 */


/**
 * 请求对象,用于在各个模块之间传递参数
 * @author XuLH <x.huan@163.com>
 * @date 2006-06-17
 */
class HttpRequest {
	/** 分支KEY,即$_REQUEST['switch'] **/
	private static $SWITCH_KEY = "switch";
	/** 保存从浏览器提交变量,即$_REQUEST.不可修改 **/
	private $parameters = NULL;
	/** 保存在应用模块内部使用的变量 **/
	private $attributes = NULL;
	/** 分支 **/
	private $switchValue = NULL;

	/**
	 * 构造函数
	 * 若 $url 不为空，则$this->parameters, 从URL中解析
	 */
	public function __construct($url="", $post=null) {
		if($url == "") {
			$this->parameters = $_REQUEST;

			if(isset($_GET['__xparam'])) {
				$xparam = $_GET['__xparam'];
				$arr = split("--", $xparam);
				foreach($arr as $data) {
					list($key, $value) = split("-", $data);
					if(empty($key)) {
						continue;
					}
					$this->parameters[$key] = Utilities::decode($value);
				}
			}
		} else {
			$info = parse_url($url);
			parse_str($info['query'], $this->parameters);
			if($post != null) {
				while(list($key, $val) = each($post)) {
					if(!isset($this->parameters[$key])) {
						$this->parameters[$key] = $val;
					}
				}
			}
		}
		$this->attributes = array();
	}

	/**
	 * 取得内部分支
	 */
	public function getSwitch() {
		if($this->switchValue == NULL) {
			$this->switchValue = $this->parameters[self::$SWITCH_KEY];
		}
		return $this->switchValue;
	}

	/**
     * Was the request made by POST?
     *
     * @return boolean
     */
    public function isPost()
    {
        if ('POST' == $this->getMethod()) {
            return true;
        }

        return false;
    }

    /**
     * Was the request made by GET?
     *
     * @return boolean
     */
    public function isGet()
    {
        if ('GET' == $this->getMethod()) {
            return true;
        }

        return false;
    }

	public function getMethod()
    {
        return $this->getServer('REQUEST_METHOD');
    }

	public function getServer($key = null, $default = null)
    {
        if (null === $key) {
            return $_SERVER;
        }

        return (isset($_SERVER[$key])) ? $_SERVER[$key] : $default;
    }

	/**
	 * 取得内部分支
	 */
	public function setSwitch($switchValue) {
		$this->switchValue = $switchValue;
	}

	/**
	 * 取得$_REQUEST中的参数
	 */
	public function getParameter($name) {
		if(isset($this->parameters[$name])) {
			return $this->parameters[$name];
		}
		//debug model can log
		//logDebug("NOTICE: $name is not exists.");
		return null;
	}

	/**
	 * 取得属性
	 */
	public function &getAttribute($name) {
		if(isset($this->attributes[$name])) {
			return $this->attributes[$name];
		}
		//debug model can log
		//logDebug("NOTICE: $name is not exists.");
		return null;
	}

	/**
	 * 设置属性
	 */
	public function setAttribute($name, &$value) {
		if(empty($name)) {
			throw new Exception("attribute name cann't empty.");
		}
		$this->attributes[$name] = $value;
	}
}

/**
 * 响应对象,用于设置向View层传递的参数
 * @author XuLH <x.huan@163.com>
 * @date 2006-06-17
 */
class HttpResponse {
	/** 模板文件名 **/
	private $tplName = NULL;
	/** 模板参数 **/
	private $tplValues = NULL;
	private $dynamicalFuns = array();

	/**
	 * 构造函数
	 */
	public function __construct() {
		$this->tplName = NULL;
		$this->tplValues = array();
	}

	/**
	 * 取得模板名
	 */
	public function getTplName() {
		return $this->tplName;
	}

	/**
	 * 设定模板名
	 */
	public function setTplName($tplName) {
		$this->tplName = $tplName;
	}

	/**
	 * 设定(添加)模板参数
	 */
	public function setTplValue($name, $value) {
		if(empty($name)) {
			throw new Exception("tpl value's name cann't empty.");
		}
		$this->tplValues[$name] = $value;
	}

	/**
	 * 取得模板中的值(返回数组)
	 */
	public function getTplValues() {
		return $this->tplValues;
	}

	/**
	 * 动态绑定函数
	 * @param string $name 函数名,在tpl等中使用
	 * @param string $type 限制为'modifier', 'block', 'compiler_function', 'function'中的一种
	 * @param mix $fun 函数名,或者为array(&$ class, $method)
	 * @return void 没有返回值,如果type不正确抛出异常
	 */
	public function registerFun($name, $type, $fun) {
		if(! in_array($type, array('modifier', 'block', 'compiler_function', 'function'))) {
			throw "registerFun Error";
		}
		$this->dynamicalFuns[$name] = array('type' => $type, 'fun' => $fun);
	}
	/**
	 * 返回动态注册的函数
	 */
	public function getDynamicalFuns() {
		return $this->dynamicalFuns;
	}

}

/**
 * 响应对象,保存了用于在View层显示的数据
 * @author XuLH <x.huan@163.com>
 * @date 2006-06-17
 */
abstract class BaseAction {
	private $displayDisabled = false;
	private $trackingDisabled = false;
	private $outCharset = __CHARSET;
	private $smartyData = "";

	/**
	 * 检查入力参数,若是系统错误(严重错误,则抛出异常)
	 */
	protected abstract function check($request, $response);

	/**
	 * 执行应用逻辑
	 */
	protected abstract function service($request, $response);

	/**
	 * 资源回收
	 */
	protected function release($request, $response) { }

	/**
	 * 禁用显示
	 */
	public function setDisplayDisabled($flag) {
		$this->displayDisabled = $flag;
	}

	/**
	 * 禁用Tracking
	 */
	public function setTrackingDisabled($flag) {
		$this->trackingDisabled = $flag;
	}

	/**
	 * 显示字符集
	 */
	public function setOutCharset($charset) {
		$this->outCharset = $charset;
	}


	/**
	 * SEO: Inbound PPC Links - Remove Tracking Code With 301 Redirect
	 */
	protected function checkInbound() {
		if(isGetMethod() == false) {
			return;
		}
		$url = $_SERVER["REQUEST_URI"];
		$start = strpos($url, "source=");
		if($start < 1) {
			return; //mot match
		}
		$end = strpos($url, "&", $start);
		if($end !== FALSE) {
			$pre = substr($url, 0, $start);
			$next = substr($url, $end+1); // clean '&'
			$url = $pre . $next;
		} else {
			$url = substr($url, 0, $start-1); // clean '&' or '?'
		}
		redirect301($url);
	}

	public function & getSmartyData() {
		return $this->smartyData;
	}

	/**
	 * Controller层的调用入口函数,在scripts中调用
	 */
	public function execute($switch=NULL, $specialURL="", $postVals=NULL) {
		//$startTime = Utilities::getMicrotime();
		try {
			$request = new HttpRequest($specialURL, $postVals);
			$response = new HttpResponse();
			//指定switch
			if($switch != NULL) {
				$request->setSwitch($switch);
			}
			//tracking loading
			if($this->trackingDisabled == false) {
				$this->loadTracking($request, $response);
			}
			if($this->displayDisabled == false) { //不需显示的页面，不用检查source
				$this->checkInbound(); //SEO: check Inbound PPC Links
			}
			//入力检查

			$this->check($request, $response);
			//执行方法

			$this->service($request, $response);
			//显示

			$this->display($request, $response);

			//tracking execute..
			//if($this->trackingDisabled == false) {
			//	$this->commitTracking($request, $response);
			//}
			//资源回收
			$this->release($request, $response);

			//数据库事务提交(由DBQuery判断是否需要做)
			//DBQuery::instance()->commit();
		} catch (Exception $e) {

			try {
				//资源回收
				$this->release($request, $response);
				//数据库事务回滚(由DBQuery判断是否需要做)
				//DBQuery::instance()->rollback();
			} catch(Exception $e) {
				logError($e->getMessage(), __MODEL_EXCEPTION);
			}

			//错误日志
			logError($e->getMessage(), __MODEL_EXCEPTION);
			logError($e->getTraceAsString(), __MODEL_EMPTY);
			//重定向到错误页面
			redirect301("/errorpage.php");
			//$this->redirect("/errorpage.php");
		}
		$endTime = Utilities::getMicrotime();
		//$useTime = $endTime - $startTime;
		//debug...
		//logDebug("\n<center><BR><HR>页面执行时间<font color=red>$useTime</font>s</center>\n");
	}

	/**
	 * 调用View层输出
	 * 说明：若禁止显示及Tpl文件名存在的话，则把TPL中内容取出，供fetchSmartyData返回
	 */
	private function display($request, $response) {
		$tplName = $response->getTplName();
		if($this->displayDisabled == false) {
			if(!empty($this->outCharset)) {
				header("Content-type: text/html; charset=".$this->outCharset);
			}
			if(empty($tplName)) {
				throw new Exception("template name cann't empty.");
			}
		} else {
			if(empty($tplName)) {
				return;
			}
		}
		// include smarty class
		require_once('Smarty.class.php');
        $smarty = new Smarty;
        $smarty->template_dir = __ROOT_TPLS_TPATH;
        $smarty->compile_dir  = __FILE_FULLPATH."tplcache/templates_c/";
		$smarty->config_dir   = __SETTING_FULLPATH."tplcache/configs/";
		$smarty->cache_dir    = __FILE_FULLPATH."tplcache/smartycache/";
        $smarty->debugging    = Debug::isShowTplDebug();
		$smarty->plugins_dir[] = __INCLUDE_ROOT .'lib/smarty_plugins';

		//$smarty->debugging    = true;

        //设置默认值(项目相关)
        $smarty->assign("__CHARSET", $this->outCharset);
        $smarty->assign("__RootPath", __HOME_ROOT_PATH);
        $smarty->assign("__StylePath", __SETTING_STYLE);
        $smarty->assign("__Top_Navigation", array(
        	array('Name'=>'优惠券', 'URL'=>'/', 'ClassStr'=>''),
        	array('Name'=>'折扣信息', 'URL'=>'/discount_hot.html', 'ClassStr'=>''),
        	array('Name'=>'劲爆特价', 'URL'=>'/onlinediscount.html', 'ClassStr'=>''),
        	array('Name'=>'旅游度假', 'URL'=>'/travel.html', 'ClassStr'=>''),
        	array('Name'=>'社区', 'URL'=>'/bbs/', 'ClassStr'=>'class="lileft"'),
        	));
        $keyword = $request->getParameter("q");
        if($keyword != NULL) {
			$charset = trim($request->getParameter("charset"));
			$keyword = "utf8" == $charset ? iconv("UTF-8", "GB2312", $keyword) : $keyword;
        	$keyword = stripslashes(Utilities::decode(stripslashes($keyword)));
        }
        $smarty->assign("__Keyword", $keyword);
        //login info
		$winduser = P_GetCookie("winduser");
		if(empty($winduser)){
			$smarty->assign('islogon',0);
		}else{
			//echo $winduser;
			$user = explode("\t",$winduser);
			$userinfo = MemberDao::getuserinfo($user[0]);
			//print_r($userinfo);
			$smarty->assign("userinfo",$userinfo[0]);
			$smarty->assign('islogon',1);
		}

		//$searchKey = CommonDao::getSearchKey();
		//$smarty->assign("searchKey", $searchKey);

		// bulk assign values
		$smarty->assign($response->getTplValues());



		//绑定函数
		foreach($response->getDynamicalFuns() as $name => $fun) {
			$smartyRegisterFun = 'register_'.$fun['type'];
			$smarty->{$smartyRegisterFun}($name,$fun['fun']);
		}

		if($this->displayDisabled == false) {
			$smarty->display($tplName.".tpl");
		} else {
			$this->smartyData = $smarty->fetch($tplName.".tpl");
		}
	}

//	protected function fetchSmartyData($request, $response) {
//		$tplName = $response->getTplName();
//		if(empty($tplName)) {
//			throw new Exception("template name cann't empty.");
//		}
//		// include smarty class
//		require_once('Smarty.class.php');
//        $smarty = new Smarty;
//        $smarty->template_dir = __ROOT_TEMPLATES_TPATH.__SETTING_STYLE;
//        $smarty->compile_dir  = __ROOT_TPLS_TPATH."templates_c/";
//        $smarty->config_dir   = __ROOT_TPLS_TPATH."config_dir/";
//        $smarty->cache_dir    = __ROOT_TPLS_TPATH."cache_dir/";
//        //设置默认值(项目相关)
//        $smarty->assign("__CHARSET", $this->outCharset);
//        $smarty->assign("__RootPath", __HOME_ROOT_PATH);
//        $smarty->assign("__StylePath", __SETTING_STYLE);
//        $keyword = $request->getParameter("q");
//        if($keyword != NULL) {
//			$charset = trim($request->getParameter("charset"));
//			$keyword = "utf8" == $charset ? iconv("UTF-8", "GB2312", $keyword) : $keyword;
//        	$keyword = stripslashes(Utilities::decode(stripslashes($keyword)));
//        }
//        $smarty->assign("__Keyword", $keyword);
//
//		$searchKey = CommonDao::getSearchKey();
//		$smarty->assign("searchKey", $searchKey);
//
//		// bulk assign values
//		$smarty->assign($response->getTplValues());
//
//		//绑定函数
//		foreach($response->getDynamicalFuns() as $name => $fun) {
//			$smartyRegisterFun = 'register_'.$fun['type'];
//			$smarty->{$smartyRegisterFun}($name,$fun['fun']);
//		}
//
//		// diplay the template
//		$chid = $request->getParameter("chid");
//		if($chid != NULL
//			&& file_exists(__ROOT_TEMPLATES_TPATH.__SETTING_STYLE.$tplName."_$chid.tpl")) {
//			$tplName .= "_$chid";
//		}
//		return $smarty->fetch($tplName.".tpl");
//	}

	/**
	 * 装载Tracking环境
	 */
	private function loadTracking($request, $response) {
		//$startTime = Utilities::getMicrotime(); //debug..

		require_once __INCLUDE_ROOT.'scripts/track/scripts/incoming.php';

	    //logDebug("tracking load use time:". (Utilities::getMicrotime()-$startTime));
	}

	/**
	 * 调用用户Tracking
	 */
	private function commitTracking($request, $response) {
		//$startTime = Utilities::getMicrotime(); //debug..
		//TrackingAPI::impressRegisterAll();
	    //logDebug("tracking commit use time:". (Utilities::getMicrotime()-$startTime));
	}

	/**
	 * 跳转
	 */
	protected function redirect($url) {
		header("Location: " . $url);
		exit;
	}
	
	protected function page404() {
	    
	}
}
?>