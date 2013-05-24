<?PHP
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");

$tpl = new sTemplate();
$tpl->setTemplate("new/priceinfo.htm");

$tpl->displayTemplate();
?>