<?php
//验证码地址
require_once("../etc/const.inc.php");
require_once (__INCLUDE_ROOT."lib/classes/class.AuthNum.php");

$oAuthNum = new AuthNum();
$oAuthNum->doCreateImage();
?>