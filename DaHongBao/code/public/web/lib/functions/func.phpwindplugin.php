<?

$db_hash="Ww~E33i7k&";

function P_GetCookie($Var){
	//echo P_CookiePre();
    return StrCode($_COOKIE[P_CookiePre().'_'.$Var],"DECODE");
}

function P_CookiePre(){
	global $db_hash;
	return substr(md5($db_hash),0,5);
}

function PwdCode($pwd){
	global $db_hash;
	return md5($_SERVER["HTTP_USER_AGENT"].$pwd.$db_hash);
}

function get_date($timestamp,$timeformat=''){
	$db_datefm="Y-m-d H:i:s";
	$db_timedf="8";
	$date_show=$timeformat ? $timeformat : ($_datefm ? $_datefm : $db_datefm);
	if($_timedf){
		$offset = $_timedf=='111' ? 0 : $_timedf;
	}else{
		$offset = $db_timedf=='111' ? 0 : $db_timedf;
	}
	return gmdate($date_show,$timestamp+$offset*3600);
}

function onlineUserAgent() {

                   if(isset($_SERVER["HTTP_USER_AGENT"])) {

                            return $_SERVER["HTTP_USER_AGENT"];

                   } else {

                            return $_ENV["HTTP_USER_AGENT"];

                   }

         }


function onlineIP() {

                   //check the forward address X-Forwarded-For

                   if(isset($_REQUEST['X-Forwarded-For']) && $_REQUEST['X-Forwarded-For'] !=""){

                            $ip = $_REQUEST['X-Forwarded-For'];

                   } else {

                            //check the remote x client ip address

                            if(isset($_SERVER["HTTP_RLNCLIENTIPADDR"]) && $_SERVER["HTTP_RLNCLIENTIPADDR"] !="") {

                                     $ip = $_SERVER["HTTP_RLNCLIENTIPADDR"];

                            } else {

                                     //set the default client ip

                                     $ip = $_SERVER['REMOTE_ADDR'];

                            }

                   }

                   return $ip;

         }

function StrCode($string,$action='ENCODE'){
	global $db_hash;
	$key	= substr(md5($_SERVER["HTTP_USER_AGENT"].$db_hash),8,18);
	$string	= $action == 'ENCODE' ? $string : base64_decode($string);
	$len	= strlen($key);
	$code	= '';
	for($i=0; $i<strlen($string); $i++){
		$k		= $i % $len;
		$code  .= $string[$i] ^ $key[$k];
	}
	$code = $action == 'DECODE' ? $code : base64_encode($code);
	$user = explode("\t",$code);
	if($user[1]==""){
		return;
	}
	return $code;
}

function createDir($dir, $mode = 0777) {
	$ret = @mkdir($dir);
	if($ret == false) {
		return false;
	}
	//umask 
	return @chmod($dir, $mode);
}

function str_replace_limit($search, $replace, $subject, $limit=-1) {
    // constructing mask(s)...
    if (is_array($search)) {
        foreach ($search as $k=>$v) {
            $search[$k] = '`' . preg_quote($search[$k],'`') . '`';
        }
    }
    else {
        $search = '`' . preg_quote($search,'`') . '`';
    }
    // replacement
    return preg_replace($search, $replace, $subject, $limit);
} 

function chinesesubstr($arg_strContent,$arg_intTrimLength)
{
    $strReturnString = "";
    $intLoopCount = 0;
    while ($intLoopCount < $arg_intTrimLength)
    {
        $chrSingle = substr($arg_strContent,$intLoopCount,1);
        if(ord($chrSingle) > 0x80) 
        {
            $intLoopCount++;
            $arg_intTrimLength++;            
        }
        $intLoopCount++;
    }
    $strReturnString = substr($arg_strContent,0,$intLoopCount);
    
    return $strReturnString;
}

function getSourceFrom(){
	return (isset($_COOKIE['TRACKING_SOURCE_GROUP']))? $_COOKIE['TRACKING_SOURCE_GROUP'] : "";
} 
?>