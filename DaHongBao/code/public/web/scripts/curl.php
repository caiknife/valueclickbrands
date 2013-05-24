<?
ob_start();
system("/usr/local/bin/curl www.pku.edu.cn");
$content = ob_get_contents();
ob_end_clean();
echo $content;

?>