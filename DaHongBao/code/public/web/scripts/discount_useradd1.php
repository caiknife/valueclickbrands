<?PHP
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");

$tpl = new sTemplate();
$tpl->setTemplate("new/discount_useradd1.htm");


$tpl->displayTemplate();

?>