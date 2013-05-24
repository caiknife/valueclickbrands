<?
/**
 * class.BaseAction.php
 * --------------------
 * ������struts������,��ΪController��Ļ���
 * ע: HttpReuqest,HttpResponse���ڴ��ļ��ж���,��Ϊ�˿���װ�ص���Ҫ.
 *
 * @author XuLH <x.huan@163.com>
 * @date 2006-06-17
 */


/**
 * �������,�����ڸ���ģ��֮�䴫�ݲ���
 * @author XuLH <x.huan@163.com>
 * @date 2006-06-17
 */
class HttpRequest {
	/** ��֧KEY,��$_REQUEST['switch'] **/
	private static $SWITCH_KEY = "switch";
	/** �����������ύ����,��$_REQUEST.�����޸� **/
	private $parameters = NULL;
	/** ������Ӧ��ģ���ڲ�ʹ�õı��� **/
	private $attributes = NULL;
	/** ��֧ **/
	private $switchValue = NULL;

	/**
	 * ���캯��
	 * �� $url ��Ϊ�գ���$this->parameters, ��URL�н���
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
	 * ȡ���ڲ���֧
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
	 * ȡ���ڲ���֧
	 */
	public function setSwitch($switchValue) {
		$this->switchValue = $switchValue;
	}

	/**
	 * ȡ��$_REQUEST�еĲ���
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
	 * ȡ������
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
	 * ��������
	 */
	public function setAttribute($name, &$value) {
		if(empty($name)) {
			throw new Exception("attribute name cann't empty.");
		}
		$this->attributes[$name] = $value;
	}
}

/**
 * ��Ӧ����,����������View�㴫�ݵĲ���
 * @author XuLH <x.huan@163.com>
 * @date 2006-06-17
 */
class HttpResponse {
	/** ģ���ļ��� **/
	private $tplName = NULL;
	/** ģ����� **/
	private $tplValues = NULL;
	private $dynamicalFuns = array();

	/**
	 * ���캯��
	 */
	public function __construct() {
		$this->tplName = NULL;
		$this->tplValues = array();
	}

	/**
	 * ȡ��ģ����
	 */
	public function getTplName() {
		return $this->tplName;
	}

	/**
	 * �趨ģ����
	 */
	public function setTplName($tplName) {
		$this->tplName = $tplName;
	}

	/**
	 * �趨(���)ģ�����
	 */
	public function setTplValue($name, $value) {
		if(empty($name)) {
			throw new Exception("tpl value's name cann't empty.");
		}
		$this->tplValues[$name] = $value;
	}

	/**
	 * ȡ��ģ���е�ֵ(��������)
	 */
	public function getTplValues() {
		return $this->tplValues;
	}

	/**
	 * ��̬�󶨺���
	 * @param string $name ������,��tpl����ʹ��
	 * @param string $type ����Ϊ'modifier', 'block', 'compiler_function', 'function'�е�һ��
	 * @param mix $fun ������,����Ϊarray(&$ class, $method)
	 * @return void û�з���ֵ,���type����ȷ�׳��쳣
	 */
	public function registerFun($name, $type, $fun) {
		if(! in_array($type, array('modifier', 'block', 'compiler_function', 'function'))) {
			throw "registerFun Error";
		}
		$this->dynamicalFuns[$name] = array('type' => $type, 'fun' => $fun);
	}
	/**
	 * ���ض�̬ע��ĺ���
	 */
	public function getDynamicalFuns() {
		return $this->dynamicalFuns;
	}

}

/**
 * ��Ӧ����,������������View����ʾ������
 * @author XuLH <x.huan@163.com>
 * @date 2006-06-17
 */
abstract class BaseAction {
	private $displayDisabled = false;
	private $trackingDisabled = false;
	private $outCharset = __CHARSET;
	private $smartyData = "";

	/**
	 * �����������,����ϵͳ����(���ش���,���׳��쳣)
	 */
	protected abstract function check($request, $response);

	/**
	 * ִ��Ӧ���߼�
	 */
	protected abstract function service($request, $response);

	/**
	 * ��Դ����
	 */
	protected function release($request, $response) { }

	/**
	 * ������ʾ
	 */
	public function setDisplayDisabled($flag) {
		$this->displayDisabled = $flag;
	}

	/**
	 * ����Tracking
	 */
	public function setTrackingDisabled($flag) {
		$this->trackingDisabled = $flag;
	}

	/**
	 * ��ʾ�ַ���
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
	 * Controller��ĵ�����ں���,��scripts�е���
	 */
	public function execute($switch=NULL, $specialURL="", $postVals=NULL) {
		//$startTime = Utilities::getMicrotime();
		try {
			$request = new HttpRequest($specialURL, $postVals);
			$response = new HttpResponse();
			//ָ��switch
			if($switch != NULL) {
				$request->setSwitch($switch);
			}
			//tracking loading
			if($this->trackingDisabled == false) {
				$this->loadTracking($request, $response);
			}
			if($this->displayDisabled == false) { //������ʾ��ҳ�棬���ü��source
				$this->checkInbound(); //SEO: check Inbound PPC Links
			}
			//�������

			$this->check($request, $response);
			//ִ�з���

			$this->service($request, $response);
			//��ʾ

			$this->display($request, $response);

			//tracking execute..
			//if($this->trackingDisabled == false) {
			//	$this->commitTracking($request, $response);
			//}
			//��Դ����
			$this->release($request, $response);

			//���ݿ������ύ(��DBQuery�ж��Ƿ���Ҫ��)
			//DBQuery::instance()->commit();
		} catch (Exception $e) {

			try {
				//��Դ����
				$this->release($request, $response);
				//���ݿ�����ع�(��DBQuery�ж��Ƿ���Ҫ��)
				//DBQuery::instance()->rollback();
			} catch(Exception $e) {
				logError($e->getMessage(), __MODEL_EXCEPTION);
			}

			//������־
			logError($e->getMessage(), __MODEL_EXCEPTION);
			logError($e->getTraceAsString(), __MODEL_EMPTY);
			//�ض��򵽴���ҳ��
			redirect301("/errorpage.php");
			//$this->redirect("/errorpage.php");
		}
		$endTime = Utilities::getMicrotime();
		//$useTime = $endTime - $startTime;
		//debug...
		//logDebug("\n<center><BR><HR>ҳ��ִ��ʱ��<font color=red>$useTime</font>s</center>\n");
	}

	/**
	 * ����View�����
	 * ˵��������ֹ��ʾ��Tpl�ļ������ڵĻ������TPL������ȡ������fetchSmartyData����
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

        //����Ĭ��ֵ(��Ŀ���)
        $smarty->assign("__CHARSET", $this->outCharset);
        $smarty->assign("__RootPath", __HOME_ROOT_PATH);
        $smarty->assign("__StylePath", __SETTING_STYLE);
        $smarty->assign("__Top_Navigation", array(
        	array('Name'=>'�Ż�ȯ', 'URL'=>'/', 'ClassStr'=>''),
        	array('Name'=>'�ۿ���Ϣ', 'URL'=>'/discount_hot.html', 'ClassStr'=>''),
        	array('Name'=>'�����ؼ�', 'URL'=>'/onlinediscount.html', 'ClassStr'=>''),
        	array('Name'=>'���ζȼ�', 'URL'=>'/travel.html', 'ClassStr'=>''),
        	array('Name'=>'����', 'URL'=>'/bbs/', 'ClassStr'=>'class="lileft"'),
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



		//�󶨺���
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
//        //����Ĭ��ֵ(��Ŀ���)
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
//		//�󶨺���
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
	 * װ��Tracking����
	 */
	private function loadTracking($request, $response) {
		//$startTime = Utilities::getMicrotime(); //debug..

		require_once __INCLUDE_ROOT.'scripts/track/scripts/incoming.php';

	    //logDebug("tracking load use time:". (Utilities::getMicrotime()-$startTime));
	}

	/**
	 * �����û�Tracking
	 */
	private function commitTracking($request, $response) {
		//$startTime = Utilities::getMicrotime(); //debug..
		//TrackingAPI::impressRegisterAll();
	    //logDebug("tracking commit use time:". (Utilities::getMicrotime()-$startTime));
	}

	/**
	 * ��ת
	 */
	protected function redirect($url) {
		header("Location: " . $url);
		exit;
	}
	
	protected function page404() {
	    
	}
}
?>