<?php

/**
 * Debug for tracking
 *
 * Tracking_Debug::dump('some error message.');
 */
class Tracking_Debug
{
	const ERROR_LOG_File  = S_T_ERROR_LOG_FILE;

	public static function dump($msg = '', $echo = FALSE)
	{
		$time        = date('Y-m-d H:i:s');

		$file        = self::ERROR_LOG_File;
		$bakFile     = self::ERROR_LOG_File . '-' . date('YmdHi') . '_bak';

		if(file_exists($file) && !is_writable($file)){
			rename($file, $bakFile);
		}

		/** get debug back trace */
		ob_start();
        var_dump(debug_backtrace());
        $output = ob_get_clean();

		/** neaten the newlines and indents */
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);

		if((boolean)$handle = fopen($file, 'a')){
		    $logString = "\n$time\t$msg\n$output===============\n";

			fwrite($handle, $logString);
			fclose($handle);
		}

		if($echo) {
			echo "<pre>\n";
			echo "Tracking Exception, Session Halted.\n";
			echo "Time: $time\n";
			echo "ErrorMsg: $msg\n";
			echo "Debug: $output\n";
			echo "</pre>\n";
			exit;
		}
	}
}