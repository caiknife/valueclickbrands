<?PHP
	//-- 10.17.2001: Turn on all error messages
//	error_reporting(E_ALL);
//	set_error_handler("errorHandler");

	//-- 10.17.2001: Error handler function
	function errorHandler($errNo, $errMsg, $errFile, $errLine){
		switch($errNo){
			case E_PARSE:
			case E_ERROR:
			case E_CORE_ERROR:
			case E_COMPILE_ERROR:
			case E_USER_ERROR:
				if(defined("__CFG_IS_DEBUG") && (__CFG_IS_DEBUG==true)){
					errorMessage($errNo, $errMsg, $errFile, $errLine);
					ob_end_flush();
				} else {
					ob_end_clean();
					errorPage($errNo, $errMsg, $errFile, $errLine);
				}
				exit -1;
			default:
				if(defined("__CFG_IS_DEBUG") && (__CFG_IS_DEBUG==true))
					errorMessage($errNo, $errMsg, $errFile, $errLine);
		}
	}

	//-- 10.17.2001: Error message function
	function errorMessage($errNo, $errMsg, $errFile, $errLine){
		$error=array(
			E_ERROR           => "Fatal Error",
			E_WARNING         => "Warning",
			E_NOTICE          => "Notice",
			E_PARSE           => "Parse Error",
			E_CORE_ERROR      => "Core Error",
			E_CORE_WARNING    => "Core Warning",
			E_COMPILE_ERROR   => "Compile Error",
			E_COMPILE_WARNING => "Compile Warning",
			E_USER_ERROR      => "Critical Error",
			E_USER_WARNING    => "Warning",
			E_USER_NOTICE     => "Notice"
		);
		$errStr=$error[$errNo];
		if(empty($errStr)) $errStr="Unknown Error";
		$errorMessage ="<BR><FONT style=\"font-family:Verdana,Tahoma,Helvetica,Arial,Sans-Serif;font-size:11px;color:#FF0000;\">";
		$errorMessage.="<STRONG>".$errStr.":</STRONG>&nbsp;";
		$errorMessage.="[".$errMsg."] in";
		$errorMessage.="&nbsp;<STRONG>".$errFile."</STRONG>&nbsp;";
		$errorMessage.="on line <STRONG>".$errLine."</STRONG></FONT><BR>";
		echo($errorMessage);
	}

	//-- 10.17.2001: Error page function
	function errorPage($errNo, $errMsg, $errFile, $errLine){
		ob_start();
		$errorMessage ="<HTML><HEAD><TITLE>Fatal Error Page</TITLE></HEAD><BODY>";
		errorMessage($errNo, $errMsg, $errFile, $errLine);
		$errorMessage.=ob_get_contents();
		$errorMessage.="</BODY></HTML>";
		echo($errorMessage);
		ob_end_clean();
	}

	class xxObject{
		function setError($errorMessage, $errorType=E_USER_ERROR){
			user_error($errorMessage, $errorType);
		}
		function write($text){
			ob_start();
			echo($text);
			ob_end_flush();
		}
	}
?>