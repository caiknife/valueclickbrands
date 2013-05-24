<?php
$fp = fopen("/usr/home/mywebroot/dahongbao/htdocs/pages/zzz.txt",'w');
fputs($fp, "this is the test string");
fclose($fp);

?>