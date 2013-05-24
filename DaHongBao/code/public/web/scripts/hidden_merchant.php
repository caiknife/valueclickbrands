<?php
    require_once("../etc/const.inc.php");
?>
<html>
<frameset rows="0,0" frameborder="NO" border="0" framespacing="0">
<frame name="dropdown" border="0" scrolling="NO" noresize src="<?= __LINK_ROOT.'hot_coupons.php'; ?>">    
<frame name="merchant" border="0" scrolling="NO" noresize src="<?= __LINK_ROOT.'redir.php'._build_redir_url(); ?>">
</frameset>
</html>
